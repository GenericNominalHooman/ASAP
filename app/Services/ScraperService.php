<?php

namespace App\Services;

use App\Models\Quotation;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;
use Tests\DuskTestCase;

class ScraperService
{
    protected $browser = null;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function scrapeForUser($user, $url = "https://s3pk.perak.gov.my/IklanList.aspx")
    {
        // Initialize user-specific variables
        $userSSMNumber = $user->ssm_number;
        $userJabatan = [2, 3, 4]; // TODO: UPDATE WITH REAL DB CONFIGURATION
        $url_main = "https://s3pk.perak.gov.my";

        logger()->info("Scrape method called for user ID: {$user->id}");

        try {
            // Initialize Guzzle client with cookie handling
            $client = new Client([
                'verify' => storage_path('certs/cacert.pem'),
                'cookies' => new CookieJar(),
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Connection' => 'keep-alive'
                ],
                'allow_redirects' => true,
                'http_errors' => false
            ]);

            // Make the initial GET request
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $crawler = new Crawler((string) $response->getBody());

                $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');

                // Apply quotation for each jabatan
                foreach ($userJabatan as $jabatan) {
                    // Loop till the end of pagination
                    $lastPaginationQuotationNo = null; // S3pk loops back to the 1st page if pagination overexceed 
                    $isEndOfPagination = false;
                    $pagination = 0;
                    while (!$isEndOfPagination) {
                        // Data for accessing the listing page
                        $redirect_data = [
                            'ToolkitScriptManager1_HiddenField' => '',
                            '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl0' . $pagination, // Change ct100..ct103 to change pagination
                            '__EVENTARGUMENT' => '',
                            '__LASTFOCUS' => '',
                            '__VIEWSTATE' => $viewState,
                            '__VIEWSTATEGENERATOR' => '0C32CE3C',
                            '__EVENTVALIDATION' => $eventValidation,
                            'ctl00$MainContent$dlJabatan' => $jabatan, // Change 1..8 to change jabatan
                        ];

                        // Submit form with Guzzle
                        $response = $client->post($url, [
                            'form_params' => $redirect_data,
                            'headers' => [
                                'Content-Type' => 'application/x-www-form-urlencoded',
                                'Referer' => $url,
                                'Origin' => parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST),
                            ]
                        ]);

                        // After getting valid the response
                        if ($response->getStatusCode() === 200) {
                            $crawler = new Crawler((string) $response->getBody());

                            // Extract the data tables
                            $tenders = $crawler->filter('table.gv tr.gvr, table.gv tr.gva')->each(function (Crawler $node) use ($jabatan) {
                                $linkNode = $node->filter('a[href*="IklanDetails.aspx"]');
                                $href = $linkNode->count() > 0 ? $linkNode->attr('href') : null;
                                $quotationNo = $linkNode->count() > 0 ? trim($linkNode->text()) : null;

                                // Map organization to the current user's organization
                                $orgMap = [
                                    2 => 'JKR',
                                    3 => 'PDT',
                                    4 => 'JPS',
                                ];
                                $orgName = $orgMap[$jabatan] ?? ($quotationNo ? substr($quotationNo, 0, 3) : null);

                                return [
                                    'ref_no' => $node->filter('span[id*="_lApplication_Label60_"]')->text(), // rows count number
                                    'quotation_no' => $quotationNo,
                                    'organization' => $orgName,
                                    'title' => $node->filter('span[id*="_lApplication_Label1_"]')->text(),
                                    'grade' => trim($node->filter('span[id*="_lApplication_tGred_"]')->text()),
                                    'specialization' => trim($node->filter('span[id*="_lApplication_tGred2_"]')->text()),
                                    'site_visit' => $node->filter('span[id*="_lApplication_lSv_"]')->text(),
                                    'closing_date' => $node->filter('span[id*="_lApplication_Label4_"], span[id*="_lApplication_Label7_"]')->text(),
                                    'details_link' => $href,
                                ];
                            });

                            // Check if end of pagination has been reached by comparing with prior pagination's last quotation_no
                            if ($pagination > 0 && count($tenders) > 0 && $lastPaginationQuotationNo == $tenders[count($tenders) - 1]['quotation_no']) {
                                $isEndOfPagination = true;
                            }

                            // Create the quotation/tender within DB based on inputted db[prior: low]
                            foreach ($tenders as $tender) {
                                // Parse closing_date from "d M Y" format (e.g., "04 Mar 2026")
                                $closingDateParsed = null;
                                try {
                                    if ($tender['closing_date']) {
                                        $closingDateParsed = Carbon::createFromFormat('d M Y', trim($tender['closing_date']));
                                    }
                                } catch (\Exception $e) {
                                    logger()->error('Failed to parse closing_date: ' . $tender['closing_date']);
                                    $closingDateParsed = now(); // Fallback to current date
                                }

                                Quotation::firstOrCreate(
                                    ['quotation_no' => $tender['quotation_no']],
                                    [
                                        'ref_no' => $tender['ref_no'],
                                        'title' => $tender['title'],
                                        'grade' => $tender['grade'],
                                        'organization' => $tender['organization'],
                                        'specializations' => $tender['specialization'],
                                        'site_visit' => $tender['site_visit'],
                                        'closing_date' => $closingDateParsed,
                                        'status' => $closingDateParsed > now() ? 'Open' : 'Closed',
                                        'details_link' => $tender['details_link'],
                                    ]
                                );
                            }

                            // Apply the tenders/quotations to the user
                            foreach ($tenders as $tender) {
                                // Check whether the tenders/quotations already exists within DB for the current user
                                $existingTender = $user->quotationApplications()->where('file_name', $tender['quotation_no'])->first();

                                // Filter tenders based on user's gred levels(user_gred_levels.g_level) and specialization(user_specializations.specialization)
                                $userGredLevels = $user->gredLevels->pluck('g_level')->map(fn($g) => strtoupper(trim($g)))->toArray();
                                $userSpecializations = $user->specializations->pluck('specialization')->map(fn($s) => strtoupper(trim($s)))->toArray();

                                $tenderGrade = strtoupper($tender['grade']);
                                $tenderSpecialization = strtoupper($tender['specialization']);

                                $hasMatchingGrade = false;
                                foreach ($userGredLevels as $userGrade) {
                                    if (str_contains($tenderGrade, $userGrade)) {
                                        $hasMatchingGrade = true;
                                        break;
                                    }
                                }

                                $hasMatchingSpecialization = false;
                                foreach ($userSpecializations as $userSpec) {
                                    if (str_contains($tenderSpecialization, $userSpec)) {
                                        $hasMatchingSpecialization = true;
                                        break;
                                    }
                                }

                                if (!$hasMatchingGrade || !$hasMatchingSpecialization) {
                                    continue;
                                }

                                if ($existingTender) {
                                    // Update existing tender: * A quotation is updated through the system due to mistakes - not yet handle
                                } else {
                                    $url_apply = $url_main . "/" . $tender['details_link'];

                                    // Sanitize the URL to create a safe filename
                                    $path = parse_url($url_apply, PHP_URL_PATH) ?: 'unknown-path';
                                    $safeFilename = 'tender-' . time() . '-' . Str::random(8) . '.html';
                                    $safeFilename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $safeFilename);

                                    if (!Browser::hasMacro('scrapeTender')) {
                                        Browser::macro('scrapeTender', function ($url_apply, $ssmNumber, $quotationNo) {
                                            $data = [
                                                'success' => false,
                                                'error' => null,
                                                'viewState' => null,
                                                'eventValidation' => null,
                                                'toolkitValue' => null,
                                                'pageContent' => null,
                                                'begin_registration_date' => null,
                                                'end_registration_date' => null,
                                                'slip_content' => null,
                                                'slip_name' => null,
                                            ];

                                            $advertContent = null;
                                            $advertName = null;

                                            try {
                                                logger()->info('Visiting URL: ' . $url_apply);
                                                $this->visit($url_apply)
                                                    ->waitFor('#header', 15);

                                                $safeText = function ($selector) {
                                                    try {
                                                        return $this->text($selector);
                                                    } catch (\Exception $e) {
                                                        return '';
                                                    }
                                                };

                                                $siteVisitLocation = $safeText('#MainContent_tSVMeet');
                                                $siteVisitDate = $safeText('#MainContent_tSVDate');
                                                $siteVisitTime = $safeText('#MainContent_tSVTime');
                                                $beginRegDate = $safeText('#MainContent_pro1_lRegStart');
                                                $endRegDate = $safeText('#MainContent_pro1_lRegEnd');

                                                // Scrape Advertisement PDF
                                                try {
                                                    logger()->info('Checking for advert button (ImageButton1)');
                                                    $advertButtons = $this->driver->findElements(\Facebook\WebDriver\WebDriverBy::name('ctl00$MainContent$ImageButton1'));

                                                    if (count($advertButtons) > 0) {
                                                        logger()->info('Advert button found. Preparing to download...');
                                                        $tempDir = str_replace('/', DIRECTORY_SEPARATOR, storage_path('app/temp'));

                                                        if (!file_exists($tempDir)) {
                                                            mkdir($tempDir, 0755, true);
                                                        }

                                                        try {
                                                            $this->driver->executeCustomCommand(
                                                                '/session/:sessionId/chromium/send_command_and_get_result',
                                                                'POST',
                                                                [
                                                                    'cmd' => 'Page.setDownloadBehavior',
                                                                    'params' => [
                                                                        'behavior' => 'allow',
                                                                        'downloadPath' => $tempDir
                                                                    ]
                                                                ]
                                                            );
                                                            logger()->info('CDP Page.setDownloadBehavior command sent with path: ' . $tempDir);
                                                        } catch (\Exception $cdpEx) {
                                                            logger()->warning('Failed to send CDP command: ' . $cdpEx->getMessage());
                                                        }

                                                        $this->driver->executeScript('arguments[0].click();', [$advertButtons[0]]);

                                                        $maxWait = 30; // seconds
                                                        $waited = 0;
                                                        $downloadedFile = null;

                                                        while ($waited < $maxWait) {
                                                            $files = glob($tempDir . '/*.pdf');
                                                            if (!empty($files)) {
                                                                usort($files, function ($a, $b) {
                                                                    return filemtime($b) - filemtime($a);
                                                                });
                                                                $downloadedFile = $files[0];
                                                                sleep(2);
                                                                break;
                                                            }
                                                            sleep(1);
                                                            $waited++;
                                                        }

                                                        if ($downloadedFile && file_exists($downloadedFile)) {
                                                            $advertContent = file_get_contents($downloadedFile);
                                                            $sanitizedQuotationNo = str_replace(['/', '\\'], '_', $quotationNo);
                                                            $advertName = 'advert-' . $sanitizedQuotationNo . '.pdf';
                                                            logger()->info('Advert PDF downloaded successfully: ' . $advertName);
                                                            unlink($downloadedFile);
                                                        } else {
                                                            logger()->warning('Timeout waiting for advert PDF download.');
                                                        }

                                                        $this->waitFor('#MainContent_btn2', 10);
                                                        $this->pause(1000);
                                                    } else {
                                                        logger()->info('Advert button (ImageButton1) not found');
                                                    }
                                                } catch (\Exception $e) {
                                                    logger()->error('Error scraping advert: ' . $e->getMessage());
                                                    try {
                                                        if (strpos($this->driver->getCurrentURL(), 'IklanDetails.aspx') === false) {
                                                            logger()->info('Attempting to recover navigation...');
                                                            $this->visit($url_apply)->waitFor('#header', 30);
                                                        }
                                                    } catch (\Exception $recEx) {
                                                        logger()->error('Failed to recover from advert error: ' . $recEx->getMessage());
                                                    }
                                                }

                                                logger()->info('Checking if #MainContent_btn2 is enabled');
                                                $this->waitUntilEnabled('#MainContent_btn2', 10);
                                                $this->pause(250);

                                                logger()->info('Clicking #MainContent_btn2');
                                                $this->click('#MainContent_btn2');

                                                try {
                                                    $this->waitFor('input[name="ctl00$MainContent$tNoPendaftaran"]', 10);
                                                } catch (\Exception $e) {
                                                    logger()->error('Failed to find tNoPendaftaran after click. Capturing state.');
                                                    $this->screenshot('fail-after-btn2-click-' . time());
                                                    $this->storeSource('fail-after-btn2-click-' . time());
                                                    throw $e;
                                                }

                                                $this->with('form', function (Browser $form) use (&$data, $siteVisitLocation, $siteVisitDate, $siteVisitTime, $beginRegDate, $endRegDate, $ssmNumber, $quotationNo, $advertContent, $advertName) {
                                                    logger()->info('Filling and submitting the form');
                                                    $form->type('input[name="ctl00$MainContent$tNoPendaftaran"]', $ssmNumber)
                                                        ->click('input[name="ctl00$MainContent$btnQua"]');

                                                    $form->pause(2000);

                                                    $viewState = $form->attribute('input[name="__VIEWSTATE"]', 'value');
                                                    $eventValidation = $form->attribute('input[name="__EVENTVALIDATION"]', 'value');
                                                    $toolkitValue = $form->attribute('#ToolkitScriptManager1_HiddenField', 'value');

                                                    $data = [
                                                        'success' => true,
                                                        'error' => null,
                                                        'viewState' => $viewState,
                                                        'eventValidation' => $eventValidation,
                                                        'toolkitValue' => $toolkitValue,
                                                        'pageContent' => $form->driver->getPageSource(),
                                                        'begin_registration_date' => $beginRegDate,
                                                        'end_registration_date' => $endRegDate,
                                                        'site_visit_location' => $siteVisitLocation,
                                                        'site_visit_date' => trim($siteVisitDate . ' ' . $siteVisitTime),
                                                        'slip_path' => null,
                                                        'advert_content' => $advertContent,
                                                        'advert_name' => $advertName,
                                                    ];

                                                    try {
                                                        $btnSlip = $form->driver->findElements(\Facebook\WebDriver\WebDriverBy::name('ctl00$MainContent$btnSlip'));
                                                        $btnInterest = $form->driver->findElements(\Facebook\WebDriver\WebDriverBy::name('ctl00$MainContent$btnInterest'));

                                                        if ($btnSlip || $btnInterest) {
                                                            $buttonName = $btnSlip ? 'ctl00$MainContent$btnSlip' : 'ctl00$MainContent$btnInterest';
                                                            logger()->info("Found {$buttonName}, clicking to redirect to printing page");
                                                            $form->click("input[name=\"{$buttonName}\"]");

                                                            $form->pause(1500);

                                                            logger()->info('Capturing PDF of the printing page');
                                                            $pdfBase64 = $form->driver->executeCustomCommand('/session/:sessionId/print', 'POST', [
                                                                'orientation' => 'portrait',
                                                                'background' => true,
                                                            ]);

                                                            if ($pdfBase64) {
                                                                $data['slip_content'] = base64_decode($pdfBase64);
                                                                $sanitizedQuotationNo = str_replace(['/', '\\'], '_', $quotationNo);
                                                                $data['slip_name'] = 'slip-' . $ssmNumber . '-' . $sanitizedQuotationNo . '.pdf';
                                                                logger()->info('PDF captured successfully: ' . $data['slip_name']);
                                                            }
                                                        } else {
                                                            logger()->info('Neither btnSlip nor btnInterest found on this page');
                                                        }
                                                    } catch (\Exception $slipEx) {
                                                        logger()->error('Error during slip capture: ' . $slipEx->getMessage());
                                                    }
                                                });
                                            } catch (\Exception $e) {
                                                logger()->error('scrapeTender Exception: ' . $e->getMessage());
                                                $data['error'] = $e->getMessage();
                                                $data['pageContent'] = $this->driver->getPageSource() ?? 'Unable to capture page source';

                                                $this->screenshot('scrape-error-' . time());
                                                $this->storeSource('scrape-error-' . time());
                                            }

                                            return $data;
                                        });
                                    }

                                    // Execute the browser
                                    try {
                                        if (!isset($this->browser)) {
                                            $this->browser = new class ('test') extends DuskTestCase {
                                                protected $filename;

                                                public function scrape($url_apply, $filename, $ssmNumber, $quotationNo)
                                                {
                                                    $this->filename = $filename;
                                                    set_time_limit(300);
                                                    Browser::$storeScreenshotsAt = storage_path('logs/dusk/screenshots');
                                                    Browser::$storeConsoleLogAt = storage_path('logs/dusk/console');
                                                    Browser::$storeSourceAt = storage_path('logs/dusk/source');

                                                    $capturedData = null;

                                                    $this->browse(function (Browser $browser) use ($url_apply, &$capturedData, $ssmNumber, $quotationNo) {
                                                        $capturedData = $browser->scrapeTender($url_apply, $ssmNumber, $quotationNo);

                                                        $name = 'success-' . str_replace('.html', '', $this->filename);
                                                        $browser->screenshot($name);
                                                        $browser->storeConsoleLog($name);
                                                        $browser->storeSource($name);
                                                    });

                                                    return $capturedData;
                                                }

                                                protected function captureFailuresFor($browsers)
                                                {
                                                    $browsers->each(function ($browser, $key) {
                                                        $name = 'failure-' . str_replace('.html', '', $this->filename) . '-' . $key;
                                                        $browser->screenshot($name);
                                                        $browser->storeConsoleLog($name);
                                                        $browser->storeSource($name);
                                                    });
                                                }
                                            };
                                        }

                                        $data = $this->browser->scrape($url_apply, $safeFilename, $userSSMNumber, $tender['quotation_no']);

                                        $closingDateParsed = Carbon::createFromFormat('d M Y', trim($tender['closing_date']));

                                        $beginRegDateParsed = null;
                                        if (!empty($data['begin_registration_date'])) {
                                            try {
                                                $beginRegDateParsed = Carbon::createFromFormat('d/m/Y', trim($data['begin_registration_date']));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse begin_registration_date: ' . $data['begin_registration_date']);
                                                $beginRegDateParsed = now();
                                            }
                                        } else {
                                            $beginRegDateParsed = now();
                                        }

                                        $endRegDateParsed = null;
                                        if (!empty($data['end_registration_date'])) {
                                            try {
                                                $endRegDateParsed = Carbon::createFromFormat('d/m/Y', trim($data['end_registration_date']));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse end_registration_date: ' . $data['end_registration_date']);
                                                $endRegDateParsed = now();
                                            }
                                        } else {
                                            $endRegDateParsed = now();
                                        }

                                        $siteVisitDateParsed = null;
                                        if (!empty($data['site_visit_date'])) {
                                            try {
                                                $siteVisitDateRaw = str_replace(['PAGI', 'PETANG'], ['AM', 'PM'], strtoupper($data['site_visit_date']));
                                                $siteVisitDateParsed = Carbon::createFromFormat('d/m/Y h:i A', trim($siteVisitDateRaw));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse site_visit_date: ' . $data['site_visit_date']);
                                                $siteVisitDateParsed = null;
                                            }
                                        } else {
                                            $siteVisitDateParsed = null;
                                        }

                                        if (!empty($data['pageContent'])) {
                                            $initialStatus = 'Pending';
                                            if (now()->lt($beginRegDateParsed)) {
                                                $initialStatus = 'Not yet applied';
                                            } elseif (now()->gt($endRegDateParsed)) {
                                                $initialStatus = 'Expired';
                                            }

                                            $user->quotationApplications()->create([
                                                'file_name' => $tender['quotation_no'],
                                                'title' => $tender['title'],
                                                'specializations' => $tender['specialization'],
                                                'begin_register_date' => $beginRegDateParsed->format('Y-m-d H:i:s'),
                                                'end_register_date' => $endRegDateParsed->format('Y-m-d H:i:s'),
                                                'closing_date' => $closingDateParsed->format('Y-m-d H:i:s'),
                                                'site_visit_location' => $data['site_visit_location'] ?? '',
                                                'site_visit_date' => ($siteVisitDateParsed === null) ? null : $siteVisitDateParsed->format('Y-m-d H:i:s'),
                                                'serial_number' => $tender['ref_no'],
                                                'owner' => "NOT SET",
                                                'organization' => $tender['organization'],
                                                'status' => $initialStatus,
                                                'advert_path' => '',
                                            ]);

                                            if (!empty($data['slip_content']) && !empty($data['slip_name'])) {
                                                $userId = $user->id;
                                                $slipPath = "slips/{$userId}/" . $data['slip_name'];

                                                Storage::disk('local')->put($slipPath, $data['slip_content']);

                                                $user->quotationApplications()
                                                    ->where('file_name', $tender['quotation_no'])
                                                    ->update([
                                                        'slip_path' => $slipPath,
                                                        'status' => 'Applied',
                                                    ]);

                                                logger()->info('Slip PDF saved via Storage to: ' . $slipPath);
                                            }

                                            if (!empty($data['advert_content']) && !empty($data['advert_name'])) {
                                                $userId = $user->id;
                                                $advertPath = "adverts/{$userId}/" . $data['advert_name'];

                                                Storage::disk('local')->put($advertPath, $data['advert_content']);

                                                $user->quotationApplications()
                                                    ->where('file_name', $tender['quotation_no'])
                                                    ->update(['advert_path' => $advertPath]);

                                                logger()->info('Advert PDF saved via Storage to: ' . $advertPath);
                                            }

                                            $debugPath = storage_path('logs/dusk/' . $safeFilename . '.html');
                                            file_put_contents($debugPath, $data['pageContent']);

                                            continue;
                                        }

                                        if ($data === null) {
                                            throw new \Exception("Browser scraping returned null - browse() method failed");
                                        }

                                        if (!$data['success']) {
                                            throw new \Exception("Browser scraping failed: " . ($data['error'] ?? 'Unknown error'));
                                        }

                                        logger()->info('Scraping successful', ['data_keys' => array_keys($data)]);

                                        $crawler = new Crawler($data['pageContent']);
                                    } catch (\Exception $e) {
                                        $logDir = storage_path('logs/dusk');
                                        if (!is_dir($logDir)) {
                                            mkdir($logDir, 0755, true);
                                        }

                                        $errorPath = $logDir . '/error-' . $safeFilename . '.log';
                                        file_put_contents($errorPath, $e->getMessage() . "\n\n" . $e->getTraceAsString());

                                        \Log::error('Dusk scraping error: ' . $e->getMessage(), [
                                            'exception' => $e,
                                            'url' => $url_apply
                                        ]);
                                    } finally {
                                    }
                                }
                            }

                            $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                            $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');

                            if (count($tenders) > 0) {
                                $lastPaginationQuotationNo = $tenders[count($tenders) - 1]['quotation_no'];
                            }

                            $pagination++;
                        } else {
                            logger()->error('Invalid response received', [
                                'status_code' => $response->getStatusCode(),
                                'pagination' => $pagination,
                                'request_url' => $url,
                            ]);

                            $isEndOfPagination = true;
                            break;
                        }
                    }
                }
            } else {
                logger()->error('Failed to fetch URL: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            logger()->error('Error: ' . $e->getMessage());
        } finally {
            if (isset($this->browser)) {
                try {
                    $this->browser::closeAll();
                } catch (\Exception $e) {
                    \Log::error('Final browser teardown error: ' . $e->getMessage());
                }
                $this->browser = null;
            }
        }
    }

    public function __destruct()
    {
        if (isset($this->browser)) {
            $this->browser::closeAll();
            $this->browser = null;
        }
    }
}
