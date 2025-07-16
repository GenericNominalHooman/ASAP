<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Livewire\Component;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Attributes\Layout;
use App\Models\quotation_application;

#[Layout('layouts.app')] 
class QuotationTenderList extends Component
{
    public $url = '';
    public $url_details = '';
    public $content = '';
    public $status = '';
    public $QuotationApplicationModel;

    public function mount(){
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
                'cookies' => true,
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
                $crawler = new Crawler((string)$response->getBody());
                // dd($crawler);
                
                $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');
                // dd($viewState, $eventValidation);

                // Then submit the form
                // $redirect_data = [
                //     'ToolkitScriptManager1_HiddenField' => '',
                //     '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl01',
                //     '__EVENTARGUMENT' => '',
                //     '__LASTFOCUS' => '',
                //     '__VIEWSTATE' => $viewState,
                //     '__VIEWSTATEGENERATOR' => $crawler->filter('#__VIEWSTATEGENERATOR')->attr('value'),
                //     '__EVENTVALIDATION' => $eventValidation,
                //     '__ASYNCPOST' => 'true',
                //     // Add any other hidden fields present in the form
                //     ...$this->extractHiddenFields($crawler)
                // ];

                $redirect_data = [
                    'ToolkitScriptManager1_HiddenField' => '',
                    '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl00', // Change ct100..ct103 to change pagination
                    '__EVENTARGUMENT' => '',
                    '__LASTFOCUS' => '',
                    '__VIEWSTATE' => $viewState,
                    '__VIEWSTATEGENERATOR' => '0C32CE3C',
                    '__EVENTVALIDATION' => $eventValidation,
                    'ctl00$MainContent$dlJabatan' => 2, // Change 1..8 to change jabatan
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
                // After getting the response
                if ($response->getStatusCode() === 200) {
                    $crawler = new Crawler((string)$response->getBody());
                    
                    // Extract the data tables
                    // dd($crawler);   
                    // $tenders = $crawler->filter('Table')->each(function (Crawler $node) {
                    //     return [
                    //         'ref_no' => $node->filter('refNo')->text(),
                    //         'pro_no' => $node->filter('ProNo')->text(),
                    //         'title' => $node->filter('Tajuk')->text(),
                    //         'start_date' => $node->filter('IklanStartDate')->text(),
                    //         'end_date' => $node->filter('IklanEndDate')->text(),
                    //         'site_visit' => $node->filter('SiteVisit')->text(),
                    //     ];
                    // });
                    $tenders = $crawler->filter('table.gv tr.gvr, table.gv tr.gva')->each(function (Crawler $node) {
                        $linkNode = $node->filter('a[href*="IklanDetails.aspx"]');
                        $href = $linkNode->count() > 0 ? $linkNode->attr('href') : null;
                        $quotationNo = $linkNode->count() > 0 ? trim($linkNode->text()) : null;
                        
                        return [
                            'ref_no' => $node->filter('span[id*="_lApplication_Label60_"]')->text(),
                            'quotation_no' => $quotationNo,
                            'title' => $node->filter('span[id*="_lApplication_Label1_"]')->text(),
                            'grade' => trim($node->filter('span[id*="_lApplication_tGred_"]')->text()),
                            'specialization' => trim($node->filter('span[id*="_lApplication_tGred2_"]')->text()),
                            'site_visit' => $node->filter('span[id*="_lApplication_lSv_"]')->text(),
                            'closing_date' => $node->filter('span[id*="_lApplication_Label4_"], span[id*="_lApplication_Label7_"]')->text(),
                            'details_link' => $href,
                        ];
                    });
                    // dd($tenders);
                    // Check whether the tenders/quotations already exists within DB for the current user
                    foreach ($tenders as $tender) {
                        $existingTender = auth()->user()->quotationApplications()->where('file_name', $tender['quotation_no'])->first();
                        // dd($existingTender);
                        // $existingTender = quotation_application::where('quotation_no', $tender['quotation_no'])->where('user_id', Auth::user()->id)->first();
                        if ($existingTender) {
                            // Update existing tender: * A quotation is updated through the system due to mistakes - not yet handle
                            // $existingTender->update([
                            //     'ref_no' => $tender['ref_no'],
                            //     'title' => $tender['title'],
                            //     'grade' => $tender['grade'],
                            //     'specialization' => $tender['specialization'],
                            //     'site_visit' => $tender['site_visit'],
                            //     'closing_date' => $tender['closing_date'],
                            //     'details_link' => $tender['details_link'],
                            // ]);
                        } else {
                            // Accessing referencedNo link
                            $url_apply = $this->url_main."/".$tender['details_link'];
                            // // First, make a GET request to get the form with fresh viewstate and event validation
                            // $initialResponse = Http::withOptions([
                            //     'verify' => storage_path('certs/cacert.pem'),
                            //     'headers' => [
                            //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            //         'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            //     ]
                            // ])->get($url_apply);

                            // $crawler = new Crawler($initialResponse->body());

                            // // Extract the new viewstate and event validation
                            // $viewState2 = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value');
                            // $eventValidation2 = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value');
                            // // dd($viewState, $eventValidation, $viewState2, $eventValidation2);

                            // Reuse the existing client to maintain session
                            // 1. First, make a GET request to the main page to establish session
                            $client->get($this->url_main . '/IklanList.aspx');

                            // 2. Then make a GET request to the details page
                            $detailResponse = $client->get($url_apply);
                            $crawler = new Crawler((string)$detailResponse->getBody());
                            // dd($crawler);

                            // 3. Extract the new viewstate and event validation
                            $viewState = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value');
                            $viewStateGenerator = $crawler->filter('input[name="__VIEWSTATEGENERATOR"]')->attr('value');
                            $eventValidation = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value');
                            // Extract the value from the script source
                            $scriptSrc = $crawler->filter('script[src*="ToolkitScriptManager1_HiddenField"]')->attr('src');
                            preg_match('/_TSM_CombinedScripts_=(.*?)(?:&|$)/', $scriptSrc, $matches);
                            $toolkitValue = urldecode($matches[1]);
                            // Add the + symbol right after the comma
                            $toolkitValue = str_replace(', ', ',+', $toolkitValue);
                            
                            // Get the cookie jar from the client
                            $cookieJar = $client->getConfig('cookies');
                            
                            // Get the session ID cookie
                            $cookie = $cookieJar->getCookieByName('ASP.NET_SessionId');
                
                            // Extract the session ID value
                            $sessionId = $cookie ? $cookie->getValue() : null;
                            
                            // Debug output
                            // dd([
                            //     'sessionId' => $sessionId,
                            //     'cookieJar' => $cookieJar
                            // ]);

                            $apply_quotation_data = [
                                'ToolkitScriptManager1_HiddenField' => $toolkitValue,
                                '__LASTFOCUS' => '',
                                '__EVENTTARGET' => '',
                                '__EVENTARGUMENT' => '',
                                '__VIEWSTATE' => $viewState,
                                '__VIEWSTATEGENERATOR' => '9FB3928F',
                                '__EVENTVALIDATION' => $eventValidation,
                                'ctl00$MainContent$tNoPendaftaran' => 'ip0302888-w',
                                'ctl00$MainContent$btnQua' => 'Semak',
                                'ctl00$MainContent$lbllayak' => '',
                                // 'ctl00$MainContent$btnBack' => 'Kembali',
                            ];
                            // dd($apply_quotation_data);
                            
                            // 5. Submit the form with the same client to maintain session
                            try {
                                $response = $client->post($url_apply, [
                                    'form_params' => $apply_quotation_data,
                                    'headers' => [
                                        // 'Origin' => parse_url($url_apply, PHP_URL_SCHEME) . '://' . parse_url($url_apply, PHP_URL_HOST),
                                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                        'Accept-Encoding' => 'gzip, deflate, br, zstd',
                                        'Accept-Language' => 'en-US,en;q=0.5',
                                        // // 'Content-Type' => 'application/x-www-form-urlencoded',
                                        'Connection' => 'keep-alive',
                                        'Content-Length' => '3075',
                                        'Content-Type' => 'application/x-www-form-urlencoded',
                                        'Cookie' => 'ASP.NET_SessionId=' . $sessionId,
                                        'Host' => 's3pk.perak.gov.my',
                                        'Origin' => 'https://s3pk.perak.gov.my',
                                        'Priority' => 'u=0, i',
                                        'Referer' => $url_apply,
                                        'Sec-Fetch-Dest' => 'document',
                                        'Sec-Fetch-Mode' => 'navigate',
                                        'Sec-Fetch-Site' => 'same-origin',
                                        'Sec-Fetch-User' => '?1',
                                        'TE' => 'trailers',
                                        'Upgrade-Insecure-Requests' => '1',
                                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:140.0) Gecko/20100101 Firefox/140.0',
                                        // // 'Referer' => $url_apply,
                                    ]
                                ]);
                                
                                $crawler = new Crawler((string)$response->getBody());
                                dd($url_apply, $apply_quotation_data, $crawler);
                            } catch (\Exception $e) {
                                dd($e->getMessage());
                            }

                            
                            // $response = Http::withOptions([
                            //     'verify' => storage_path('certs/cacert.pem'),
                            //     'headers' => [
                            //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            //         'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            //         'Content-Type' => 'application/x-www-form-urlencoded',
                            //         'Referer' => $url_apply,
                            //         'Origin' => parse_url($url_apply, PHP_URL_SCHEME) . '://' . parse_url($url_apply, PHP_URL_HOST),
                            //     ]
                            // ])->asForm()->post($url_apply, $apply_quotation_data);            
                            // $crawler = new Crawler($response->body());
                            // dd($crawler);
                            
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
                        }
                    }

                    // Update ViewState and EventValidation for next request
                    $viewState = $crawler->filter('#__VIEWSTATE')->attr('value');
                    $eventValidation = $crawler->filter('#__EVENTVALIDATION')->attr('value');
                    $viewStateGenerator = $crawler->filter('#__VIEWSTATEGENERATOR')->attr('value');

                    // Store or process the tenders
                    $this->tenders = $tenders;
                    
                    // For pagination, you can store the tokens for the next request
                    $this->viewState = $viewState;
                    $this->eventValidation = $eventValidation;
                    $this->viewStateGenerator = $viewStateGenerator;
                }

                
                // Example: Extract all headings
                $headings = $crawler->filter('h1, h2, h3, title')->each(function (Crawler $node) {
                    return $node->text();
                });
                
                $this->content = implode("\n", $headings);
                dd($this->content);
                $this->status = 'Scraping completed successfully!';
            } else {
                $this->status = 'Failed to fetch URL: ' . $response->status();
            }
            logger()->info($this->status);  // Check storage/logs/laravel.log
        } catch (\Exception $e) {
            $this->status = 'Error: ' . $e->getMessage();
            logger()->info($this->status);  // Check storage/logs/laravel.log
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

    public function test(){
        logger()->info('Test method called'); // Check storage/logs/laravel.log
    }

    public function render()
    {
        // return dd($this->QuotationApplicationModel);
        return view('livewire.quotation-tender-list');
    }
}