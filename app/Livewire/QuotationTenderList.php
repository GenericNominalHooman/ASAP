<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Livewire\Component;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\quotation_application;
use App\Models\Quotation;

#[Layout('layouts.app')]
class QuotationTenderList extends Component
{
    use WithPagination;

    public $url = '';
    public $url_details = '';
    public $content = '';
    public $status = '';
    protected $browser = null;
    public $userSSMNumber = '';
    public $userJabatan = [2, 3, 4]; // NOTE: REPLACE THIS WITH A WORKING USER'S JABATAN ID SETTINGS OPTION, AND USE ASSOCIATIVE ARRAY INSTEAD

    public function mount()
    {
        //
        $this->userSSMNumber = auth()->user()->ssm_number;
    }

    public function scrape()
    {
        // Remove this dd() or check network tab for its output
        logger()->info('Scrape method called');  // Check storage/logs/laravel.log
        // $this->url = "http://127.0.0.1:8000"; 
        $this->url = "https://s3pk.perak.gov.my/IklanList.aspx";
        $this->url_main = "https://s3pk.perak.gov.my";
        $this->validate(['url' => 'required|url']);

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
            $response = $client->get($this->url);

            if ($response->getStatusCode() === 200) {
                $crawler = new Crawler((string) $response->getBody());
                // dd($crawler);

                $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');
                // dd($viewState, $eventValidation);

                // Apply quotation for each jabatan
                foreach ($this->userJabatan as $jabatan) {
                    // Loop till the end of pagination
                    $lastPaginationQuotationNo = null; // S3pk loops back to the 1st page if pagination overexceed 
                    $isEndOfPagination = false;
                    $pagination = 0;
                    while (!$isEndOfPagination) {
                        // Data for accessing the listing page
                        $redirect_data = [
                            'ToolkitScriptManager1_HiddenField' => '',
                            '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl0' . $pagination, // Change ct100..ct103 to change pagination
                            // '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl03', // Change ct100..ct103 to change pagination
                            '__EVENTARGUMENT' => '',
                            '__LASTFOCUS' => '',
                            '__VIEWSTATE' => $viewState,
                            '__VIEWSTATEGENERATOR' => '0C32CE3C',
                            '__EVENTVALIDATION' => $eventValidation,
                            'ctl00$MainContent$dlJabatan' => $jabatan, // Change 1..8 to change jabatan
                        ];

                        // Submit form with Guzzle
                        $response = $client->post($this->url, [
                            'form_params' => $redirect_data,
                            'headers' => [
                                'Content-Type' => 'application/x-www-form-urlencoded',
                                'Referer' => $this->url,
                                'Origin' => parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST),
                            ]
                        ]);
                        // dd([
                        //     'status' => $response->status(), 
                        //     'headers' => $response->headers(),
                        //     'body' => $response->body(),
                        //     'body_json' => json_decode($response->body()),
                        //     'is_successful' => $response->successful()
                        // ]);

                        // After getting valid the response
                        if ($response->getStatusCode() === 200) {
                            $crawler = new Crawler((string) $response->getBody());

                            // Extract the data tables
                            // dd($crawler);   
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
                            // dd($tenders);

                            // session()->flush() removed as it might interfere with concurrent scraping sessions

                            // Check if end of pagination has been reached by comparing with prior pagination's last quotation_no
                            if ($pagination > 0 && $lastPaginationQuotationNo == $tenders[count($tenders) - 1]['quotation_no']) {
                                $isEndOfPagination = true;
                            }

                            // Create the quotation/tender within DB based on inputted db[prior: low]
                            foreach ($tenders as $tender) {
                                // Parse closing_date from "d M Y" format (e.g., "04 Mar 2026")
                                $closingDateParsed = null;
                                try {
                                    if ($tender['closing_date']) {
                                        $closingDateParsed = \Carbon\Carbon::createFromFormat('d M Y', trim($tender['closing_date']));
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
                                $existingTender = auth()->user()->quotationApplications()->where('file_name', $tender['quotation_no'])->first();
                                // dd($existingTender);

                                // Filter tenders based on user's gred levels(user_gred_levels.g_level) and specialization(user_specializations.specialization)
                                $userGredLevels = auth()->user()->gredLevels->pluck('g_level')->map(fn($g) => strtoupper(trim($g)))->toArray();
                                $userSpecializations = auth()->user()->specializations->pluck('specialization')->map(fn($s) => strtoupper(trim($s)))->toArray();

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
                                    $url_apply = $this->url_main . "/" . $tender['details_link']; // e.g: https://s3pk.perak.gov.my/IklanDetails.aspx?refNo=PRO250728145251182

                                    // Sanitize the URL to create a safe filename
                                    // dd($url_apply, parse_url($url_apply, PHP_URL_PATH), time());
                                    // With this more robust version:
                                    $path = parse_url($url_apply, PHP_URL_PATH) ?: 'unknown-path';
                                    $safeFilename = 'tender-' . time() . '-' . Str::random(8) . '.html';
                                    $safeFilename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $safeFilename); // Remove any remaining invalid characters

                                    if (!Browser::hasMacro('scrapeTender')) {
                                        Browser::macro('scrapeTender', function ($url_apply, $ssmNumber) {
                                            $data = [
                                                'success' => false,
                                                'error' => null,
                                                'viewState' => null,
                                                'eventValidation' => null,
                                                'toolkitValue' => null,
                                                'pageContent' => null,
                                                'begin_registration_date' => null,
                                                'end_registration_date' => null,
                                            ];

                                            try {
                                                logger()->info('Visiting URL: ' . $url_apply);
                                                $this->visit($url_apply)
                                                    ->waitFor('#header', 30);

                                                // Helper to safely extract text
                                                $safeText = function ($selector) {
                                                    try {
                                                        return $this->text($selector);
                                                    } catch (\Exception $e) {
                                                        return '';
                                                    }
                                                };

                                                // Extract fields BEFORE clicking or any redirect
                                                $siteVisitLocation = $safeText('#MainContent_tSVMeet');
                                                $siteVisitDate = $safeText('#MainContent_tSVDate');
                                                $siteVisitTime = $safeText('#MainContent_tSVTime');
                                                $beginRegDate = $safeText('#MainContent_pro1_lRegStart');
                                                $endRegDate = $safeText('#MainContent_pro1_lRegEnd');

                                                logger()->info('Checking if #MainContent_btn2 is enabled');
                                                $this->waitUntilEnabled('#MainContent_btn2', 10);
                                                $this->pause(1000); // Small pause for stability

                                                logger()->info('Clicking #MainContent_btn2');
                                                $this->click('#MainContent_btn2');

                                                // Check if the expected element appears or if we are still on the same page
                                                try {
                                                    $this->waitFor('input[name="ctl00$MainContent$tNoPendaftaran"]', 10);
                                                } catch (\Exception $e) {
                                                    logger()->error('Failed to find tNoPendaftaran after click. Capturing state.');
                                                    $this->screenshot('fail-after-btn2-click-' . time());
                                                    $this->storeSource('fail-after-btn2-click-' . time());
                                                    throw $e;
                                                }

                                                $this->with('form', function (Browser $form) use (&$data, $siteVisitLocation, $siteVisitDate, $siteVisitTime, $beginRegDate, $endRegDate, $ssmNumber) {
                                                    // Fill the form
                                                    logger()->info('Filling and submitting the form');
                                                    $form->type('input[name="ctl00$MainContent$tNoPendaftaran"]', $ssmNumber)
                                                        ->click('input[name="ctl00$MainContent$btnQua"]');

                                                    // Wait for any AJAX/redirect
                                                    $form->pause(2000);

                                                    // Get form values with null checks
                                                    $viewState = $form->attribute('input[name="__VIEWSTATE"]', 'value');
                                                    $eventValidation = $form->attribute('input[name="__EVENTVALIDATION"]', 'value');
                                                    $toolkitValue = $form->attribute('#ToolkitScriptManager1_HiddenField', 'value');

                                                    // Capture the data
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
                                                    ];
                                                });
                                            } catch (\Exception $e) {
                                                logger()->error('scrapeTender Exception: ' . $e->getMessage());
                                                $data['error'] = $e->getMessage();
                                                $data['pageContent'] = $this->driver->getPageSource() ?? 'Unable to capture page source';

                                                // Capture more context
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

                                                public function scrape($url_apply, $filename, $ssmNumber)
                                                {
                                                    $this->filename = $filename;
                                                    set_time_limit(300);
                                                    Browser::$storeScreenshotsAt = storage_path('logs/dusk/screenshots');
                                                    Browser::$storeConsoleLogAt = storage_path('logs/dusk/console');
                                                    Browser::$storeSourceAt = storage_path('logs/dusk/source');

                                                    // Use a reference variable to capture data since browse() doesn't return the callback value
                                                    $capturedData = null;

                                                    $this->browse(function (Browser $browser) use ($url_apply, &$capturedData, $ssmNumber) {
                                                        $capturedData = $browser->scrapeTender($url_apply, $ssmNumber);

                                                        // Capture success screenshot
                                                        $name = 'success-' . str_replace('.html', '', $this->filename);
                                                        $browser->screenshot($name);
                                                        $browser->storeConsoleLog($name);
                                                        $browser->storeSource($name); // Store source as well for completeness
                                                    });

                                                    return $capturedData;
                                                }

                                                /**
                                                 * Capture the failure screenshots for the browser.
                                                 *
                                                 * @param  \Illuminate\Support\Collection  $browsers
                                                 * @return void
                                                 */
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

                                        $data = $this->browser->scrape($url_apply, $safeFilename, $this->userSSMNumber); // PS if browser errors out it will loop back from here, the code below this will be ignored

                                        // 
                                        // Data massaging - Convert all dates into SQL-friendly format
                                        // Convert closing_date from "d M Y" format (e.g., "04 Mar 2026")
                                        $closingDateParsed = \Carbon\Carbon::createFromFormat('d M Y', trim($tender['closing_date']));

                                        // Convert begin_registration_date from "d/m/Y" format (e.g., "19/02/2026")
                                        $beginRegDateParsed = null;
                                        if (!empty($data['begin_registration_date'])) {
                                            try {
                                                $beginRegDateParsed = \Carbon\Carbon::createFromFormat('d/m/Y', trim($data['begin_registration_date']));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse begin_registration_date: ' . $data['begin_registration_date']);
                                                $beginRegDateParsed = now();
                                            }
                                        } else {
                                            $beginRegDateParsed = now();
                                        }

                                        // Convert end_registration_date from "d/m/Y" format (e.g., "20/02/2026")
                                        $endRegDateParsed = null;
                                        if (!empty($data['end_registration_date'])) {
                                            try {
                                                $endRegDateParsed = \Carbon\Carbon::createFromFormat('d/m/Y', trim($data['end_registration_date']));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse end_registration_date: ' . $data['end_registration_date']);
                                                $endRegDateParsed = now();
                                            }
                                        } else {
                                            $endRegDateParsed = now();
                                        }

                                        // Convert site_visit_date from "d/m/Y h:i A" or similar
                                        $siteVisitDateParsed = null;
                                        if (!empty($data['site_visit_date'])) {
                                            try {
                                                // Handle Malay time indicators by converting them to AM/PM
                                                $siteVisitDateRaw = str_replace(['PAGI', 'PETANG'], ['AM', 'PM'], strtoupper($data['site_visit_date']));
                                                $siteVisitDateParsed = \Carbon\Carbon::createFromFormat('d/m/Y h:i A', trim($siteVisitDateRaw));
                                            } catch (\Exception $e) {
                                                logger()->error('Failed to parse site_visit_date: ' . $data['site_visit_date']);
                                                $siteVisitDateParsed = null;
                                            }
                                        } else {
                                            $siteVisitDateParsed = null;
                                        }


                                        if (!empty($data['pageContent'])) {
                                            // Create new tender entry using User Model relationship
                                            auth()->user()->quotationApplications()->create([
                                                'file_name' => $tender['quotation_no'],
                                                'title' => $tender['title'],
                                                'specializations' => $tender['specialization'],
                                                'begin_register_date' => $beginRegDateParsed->format('Y-m-d H:i:s'),
                                                'end_register_date' => $endRegDateParsed->format('Y-m-d H:i:s'),
                                                'closing_date' => $closingDateParsed->format('Y-m-d H:i:s'),
                                                'site_visit_location' => $data['site_visit_location'] ?? '',
                                                'site_visit_date' => ($siteVisitDateParsed === null) ? null : $siteVisitDateParsed->format('Y-m-d H:i:s'),
                                                'serial_number' => $tender['ref_no'],
                                                'owner' => $tender['organization'],
                                                'status' => 'Pending', // Initial status
                                                'slip_path' => '', // TODO: Will be filled after application submission
                                                'advert_path' => '', // TODO: Will be filled after scraping details
                                            ]);

                                            // Debug: Save the page content to a file
                                            $debugPath = storage_path('logs/dusk/' . $safeFilename . '.html');
                                            file_put_contents($debugPath, $data['pageContent']);

                                            continue;
                                        }

                                        // Validate that we got data back
                                        if ($data === null) {
                                            throw new \Exception("Browser scraping returned null - browse() method failed");
                                        }

                                        // Check if scraping was successful
                                        if (!$data['success']) {
                                            throw new \Exception("Browser scraping failed: " . ($data['error'] ?? 'Unknown error'));
                                        }

                                        // Debug output
                                        logger()->info('Scraping successful', ['data_keys' => array_keys($data)]);

                                        // Process the results
                                        $crawler = new Crawler($data['pageContent']);
                                    } catch (\Exception $e) {
                                        // Ensure the logs directory exists
                                        $logDir = storage_path('logs/dusk');
                                        if (!is_dir($logDir)) {
                                            mkdir($logDir, 0755, true);
                                        }

                                        // Save the error to a file
                                        $errorPath = $logDir . '/error-' . $safeFilename . '.log';
                                        // dd('$errorPath: ' . $errorPath);
                                        file_put_contents($errorPath, $e->getMessage() . "\n\n" . $e->getTraceAsString());

                                        // Also log to Laravel's default log
                                        \Log::error('Dusk scraping error: ' . $e->getMessage(), [
                                            'exception' => $e,
                                            'url' => $url_apply
                                        ]);
                                    } finally {
                                        // We don't close the browser here to allow reuse for the next tender in the loop
                                        // The browser will be closed in __destruct or at the end of the scrape method
                                    }
                                }
                            }

                            // Update ViewState and EventValidation for next request
                            $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                            $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');
                            $viewStateGenerator = $crawler->filter('#__VIEWSTATEGENERATOR')->attr('value');

                            // REDUNDANT CODE!!!
                            // Store or process the tenders
                            $this->tenders = $tenders;

                            // For pagination, you can store the tokens for the next request
                            $this->viewState = $viewState;
                            $this->eventValidation = $eventValidation;
                            $this->viewStateGenerator = $viewStateGenerator;
                            // REDUNDANT CODE!!!

                            // Save current page's last quotation_no for next iteration comparison
                            $lastPaginationQuotationNo = $tenders[count($tenders) - 1]['quotation_no'];

                            logger()->info('currentPaginationQuotationNo: ' . $tender['quotation_no']);
                            logger()->info('lastPaginationQuotationNo: ' . $lastPaginationQuotationNo);
                            logger()->info('Pagination: ' . $pagination);
                            logger()->info('tenders: ' . json_encode($tenders));

                            // Increment pagination
                            $pagination++;
                            $this->status = 'Scraping completed successfully!';
                        } else { // Invalid response
                            logger()->error('Invalid response received', [
                                'status_code' => $response->getStatusCode(),
                                'reason_phrase' => $response->getReasonPhrase(),
                                'headers' => $response->getHeaders(),
                                'body_preview' => substr((string) $response->getBody(), 0, 500),
                                'pagination' => $pagination,
                                'request_url' => $this->url,
                                'request_data' => $redirect_data,
                            ]);

                            // Error message will be outputted if overpagination occured for JPS & PDT 
                            $isEndOfPagination = true;

                            break;
                        }

                    }
                }
            } else {
                $this->status = 'Failed to fetch URL: ' . $response->getStatusCode();
            }
            logger()->info($this->status);  // Check storage/logs/laravel.log
        } catch (\Exception $e) {
            $this->status = 'Error: ' . $e->getMessage();
            logger()->error($this->status);  // Check storage/logs/laravel.log
        } finally {
            // Final browser cleanup
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
        // Close the browser if it's still open
        if (isset($this->browser)) {
            $this->browser::closeAll();
            $this->browser = null;
        }
    }

    // Helper method to extract all hidden form fields
    private function extractHiddenFields(Crawler $crawler): array
    {
        $hiddenFields = [];
        $crawler->filter('input[type="hidden"]')->each(function (Crawler $node) use (&$hiddenFields) {
            $name = $node->attr('name');
            $value = $node->attr('value') ?? '';
            $hiddenFields[$name] = $value;
        });
        return $hiddenFields;
    }

    public function test()
    {
        logger()->info('Test method called'); // Check storage/logs/laravel.log
    }

    public function render()
    {
        return view('livewire.quotation-tender-list', [
            'QuotationApplicationModelToday' => quotation_application::whereDate('created_at', now()->today())->orderBy('created_at', 'desc')->paginate(10, ['*'], 'pageToday'),
            'QuotationApplicationModel' => quotation_application::orderBy('created_at', 'desc')->paginate(10, ['*'], 'pageHistory'),
        ]);
    }
}