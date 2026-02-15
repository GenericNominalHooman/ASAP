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
use App\Models\quotation_application;
use App\Models\Quotation;

#[Layout('layouts.app')]
class QuotationTenderList extends Component
{
    public $url = '';
    public $url_details = '';
    public $content = '';
    public $status = '';
    public $QuotationApplicationModel;
    protected $browser = null;
    public $userJabatan = [2, 3, 4]; // NOTE: REPLACE THIS WITH A WORKING USER'S JABATAN ID SETTINGS OPTION

    public function mount()
    {
        $this->QuotationApplicationModel = quotation_application::all();
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
                            $tenders = $crawler->filter('table.gv tr.gvr, table.gv tr.gva')->each(function (Crawler $node) {
                                $linkNode = $node->filter('a[href*="IklanDetails.aspx"]');
                                $href = $linkNode->count() > 0 ? $linkNode->attr('href') : null;
                                $quotationNo = $linkNode->count() > 0 ? trim($linkNode->text()) : null;

                                return [
                                    'ref_no' => $node->filter('span[id*="_lApplication_Label60_"]')->text(),
                                    'quotation_no' => $quotationNo,
                                    'organization' => $quotationNo ? substr($quotationNo, 0, 3) : null,
                                    'title' => $node->filter('span[id*="_lApplication_Label1_"]')->text(),
                                    'grade' => trim($node->filter('span[id*="_lApplication_tGred_"]')->text()),
                                    'specialization' => trim($node->filter('span[id*="_lApplication_tGred2_"]')->text()),
                                    'site_visit' => $node->filter('span[id*="_lApplication_lSv_"]')->text(),
                                    'closing_date' => $node->filter('span[id*="_lApplication_Label4_"], span[id*="_lApplication_Label7_"]')->text(),
                                    'details_link' => $href,
                                ];
                            });
                            // dd($tenders);

                            if (session_status() === PHP_SESSION_ACTIVE) {
                                session()->flush();
                                session()->regenerate();
                            }

                            // Check if end of pagination has been reached by comparing with prior pagination's last quotation_no
                            if ($pagination > 0 && $lastPaginationQuotationNo == $tenders[count($tenders) - 1]['quotation_no']) {
                                $isEndOfPagination = true;
                            }

                            // Create the quotation/tender within DB based on inputted db[prior: low]
                            foreach ($tenders as $tender) {
                                Quotation::firstOrCreate(
                                    ['quotation_no' => $tender['quotation_no']],
                                    [
                                        'ref_no' => $tender['ref_no'],
                                        'title' => $tender['title'],
                                        'grade' => $tender['grade'],
                                        'organization' => $tender['organization'],
                                        'specializations' => $tender['specialization'],
                                        'site_visit' => $tender['site_visit'],
                                        'closing_date' => $tender['closing_date'],
                                        'status' => $tender['closing_date'] > now() ? 'Open' : 'Closed',
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
                                        Browser::macro('scrapeTender', function ($url_apply) {
                                            $data = [];
                                            $this->visit($url_apply)
                                                ->waitFor('#header', 30)
                                                ->click('#MainContent_btn2')
                                                ->waitFor('input[name="ctl00$MainContent$tNoPendaftaran"]', 30)
                                                ->with('form', function (Browser $form) use (&$data) {
                                                    // Fill the form
                                                    $form->type('input[name="ctl00$MainContent$tNoPendaftaran"]', 'IP0302888-W')
                                                        ->click('input[name="ctl00$MainContent$btnQua"]');

                                                    // Wait for any AJAX/redirect
                                                    $form->pause(2000);

                                                    // Get form values
                                                    $viewState = $form->attribute('input[name="__VIEWSTATE"]', 'value');
                                                    $eventValidation = $form->attribute('input[name="__EVENTVALIDATION"]', 'value');
                                                    $toolkitValue = $form->attribute('#ToolkitScriptManager1_HiddenField', 'value');

                                                    // Capture the data
                                                    $data = [
                                                        'viewState' => $viewState,
                                                        'eventValidation' => $eventValidation,
                                                        'toolkitValue' => $toolkitValue,
                                                        'pageContent' => $form->driver->getPageSource()
                                                    ];
                                                });
                                            return $data;
                                        });
                                    }

                                    // Execute the browser
                                    try {
                                        if (!isset($this->browser)) {
                                            $this->browser = new class ('test') extends DuskTestCase {
                                                protected $filename;

                                                public function scrape($url_apply, $filename)
                                                {
                                                    $this->filename = $filename;
                                                    set_time_limit(300);
                                                    Browser::$storeScreenshotsAt = storage_path('logs/dusk/screenshots');
                                                    Browser::$storeConsoleLogAt = storage_path('logs/dusk/console');
                                                    Browser::$storeSourceAt = storage_path('logs/dusk/source');

                                                    return $this->browse(function (Browser $browser) use ($url_apply) {
                                                        $data = $browser->scrapeTender($url_apply);

                                                        // Capture success screenshot
                                                        $name = 'success-' . str_replace('.html', '', $this->filename);
                                                        $browser->screenshot($name);
                                                        $browser->storeConsoleLog($name);
                                                        $browser->storeSource($name); // Store source as well for completeness

                                                        return $data;
                                                    });
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

                                        $data = $this->browser->scrape($url_apply, $safeFilename);

                                        // Debug: Save the page content to a file
                                        if (!empty($data['pageContent'])) {
                                            $debugPath = storage_path('logs/dusk/' . $safeFilename . '.html');
                                            file_put_contents($debugPath, $data['pageContent']);
                                        }

                                        // Process the results
                                        $crawler = new Crawler($data['pageContent']);

                                        // Quotation application settings check

                                        // Submitting application form


                                        // Create new tender entry
                                        quotation_application::create([
                                            'ref_no' => $tender['ref_no'],
                                            'quotation_no' => $tender['quotation_no'],
                                            'user_id' => auth()->user()->id,
                                            'title' => $tender['title'],
                                            'grade' => $tender['grade'],
                                            'specialization' => $tender['specialization'],
                                            'site_visit' => $tender['site_visit'],
                                            'closing_date' => $tender['closing_date'],
                                            'details_link' => $tender['details_link'],
                                        ]);
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
                                        // Clean up the browser instance
                                        if (isset($this->browser)) {
                                            try {
                                                $this->browser::closeAll();
                                            } catch (\Exception $e) {
                                                \Log::error('Error during browser teardown: ' . $e->getMessage());
                                            }
                                            $this->browser = null;
                                        }
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
            logger()->info($this->status);  // Check storage/logs/laravel.log
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
        // return dd($this->QuotationApplicationModel);
        return view('livewire.quotation-tender-list');
    }
}