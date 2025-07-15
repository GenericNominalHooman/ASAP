<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\Attributes\Layout;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Http;
use App\Models\quotation_application;
use Symfony\Component\Process\Process;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client as PantherClient;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
                    'ctl00$MainContent$dlJabatan' => 4, // Change 1..8 to change jabatan
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
                    // dd($crawler);   
                    
                    // Extract the data tables
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
                            // dd($url_apply);
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
                            // $client->get($this->url_main . '/IklanList.aspx');

                            // 2. Then make a GET request to the details page
                            // $detailResponse = $client->get($url_apply);
                            // $crawler = new Crawler((string)$detailResponse->getBody());
                            
                            // First get the page with Guzzle
                            $response = $client->get($url_apply);
                            $html = (string) $response->getBody();

                            // Save to temp file
                            file_put_contents('temp.html', $html);

                            

// try {
//     $process = new Process(['node', '-e', '
//         const { JSDOM, VirtualConsole } = require("jsdom");
        
//         // Create a virtual console to capture errors
//         const virtualConsole = new VirtualConsole();
//         virtualConsole.sendTo(console, { omitJSDOMErrors: true });

//         // Create a DOM environment that includes the required ASP.NET AJAX globals
//         const dom = new JSDOM(`'.addslashes($html).'`, { 
//             runScripts: "dangerously",
//             resources: "usable",
//             virtualConsole: virtualConsole,
//             beforeParse(window) {
//                 // Mock required ASP.NET AJAX globals
//                 window.Sys = window.Sys || {};
//                 window.Sys.UI = window.Sys.UI || {};
//                 window.Sys.UI.DomElement = window.Sys.UI.DomElement || {};
//                 window.Sys.UI.DomElement.getVisible = function() { return true; };
//                 window.Sys.UI.DomElement.setVisible = function() {};
//                 window.Sys.UI.DomElement.getLocation = function() { return { x: 0, y: 0 }; };
//                 window.Sys.UI.DomElement.setLocation = function() {};
//                 window.Sys.UI.DomElement.getBounds = function() { return { x: 0, y: 0, width: 0, height: 0 }; };
//                 window.Sys.UI.DomElement.setBounds = function() {};
                
//                 // Mock $common object
//                 window.$common = {
//                     getCurrentStyle: function() { return ""; },
//                     getInheritedBackgroundColor: function() { return "#FFFFFF"; },
//                     setElementOpacity: function() {},
//                     getElementOpacity: function() { return 1; }
//                 };
                
//                 // Mock other required objects
//                 window.Array = window.Array || {};
//                 window.Array.indexOf = window.Array.indexOf || function() { return -1; };
//                 window.String = window.String || {};
//                 window.String.isInstanceOfType = window.String.isInstanceOfType || function() { return false; };
//             }
//         });

//         // Wait for scripts to execute
//         dom.window.addEventListener("load", () => {
//             setTimeout(() => {
//                 try {
//                     // Try to get the value directly
//                     const toolkitInput = dom.window.document.getElementById("ToolkitScriptManager1_HiddenField");
//                     const toolkitValue = toolkitInput ? toolkitInput.value : "Element not found";
                    
//                     // If not found, try to find it in the page\'s JavaScript
//                     if (!toolkitValue || toolkitValue === "Element not found") {
//                         const scripts = dom.window.document.getElementsByTagName("script");
//                         for (let script of scripts) {
//                             if (script.textContent && script.textContent.includes("ToolkitScriptManager1_HiddenField")) {
//                                 const match = script.textContent.match(/ToolkitScriptManager1_HiddenField[^>]*value=["\']([^"\']*)["\']/i);
//                                 if (match && match[1]) {
//                                     console.log(match[1]);
//                                     return;
//                                 }
//                             }
//                         }
//                     }
                    
//                     console.log(toolkitValue || "Value not found in DOM");
//                 } catch (e) {
//                     console.error("Error:", e.message);
//                 }
//             }, 5000); // Increased timeout to 5 seconds
//         });
//     ']);

//     // Set process timeout
//     $process->setTimeout(30); // 30 seconds timeout
    
//     // Run the process
//     $process->mustRun();
    
//     // Get and return the output
//     $output = $process->getOutput();
//     dd($output);
    
// } catch (ProcessFailedException $e) {
//     \Log::error('Node.js process failed: ' . $e->getMessage());
//     \Log::error('Process output: ' . $e->getProcess()->getOutput());
//     dd('Error: ' . $e->getMessage());
// }

// Attempt 2
try {
    // Check if Node.js is installed
    if (!file_exists('/usr/bin/node') && !file_exists('C:\Program Files\nodejs\node.exe')) {
        throw new \Exception('Node.js is not installed');
    }
    
    // Check if jsdom is installed
    if (!file_exists(base_path('node_modules/jsdom/package.json'))) {
        throw new \Exception('JSDOM package is not installed. Please run: npm install jsdom');
    }
                            dd($html);
    
    // Create the process with error handling
   $process = new Process(['node', '-e', '
    const { JSDOM, VirtualConsole, ResourceLoader } = require("jsdom");
    
    // Create a virtual console to capture errors
    const virtualConsole = new VirtualConsole();
    virtualConsole.sendTo(console, { omitJSDOMErrors: true });

    // Create a custom resource loader that ignores external resources
    const resourceLoader = new ResourceLoader({
        fetchExternalResources: ["script"],
        processExternalResources: ["script"],
        beforeParse(window) {
            // Mock required ASP.NET AJAX globals
            window.Sys = window.Sys || {};
            window.Sys.UI = window.Sys.UI || {};
            window.Sys.UI.DomElement = window.Sys.UI.DomElement || {};
            window.Sys.UI.DomElement.getVisible = function() { return true; };
            window.Sys.UI.DomElement.setVisible = function() {};
            window.Sys.UI.DomElement.getLocation = function() { return { x: 0, y: 0 }; };
            window.Sys.UI.DomElement.setLocation = function() {};
            window.Sys.UI.DomElement.getBounds = function() { return { x: 0, y: 0, width: 0, height: 0 }; };
            window.Sys.UI.DomElement.setBounds = function() {};
            
            // Mock $common object
            window.$common = {
                getCurrentStyle: function() { return ""; },
                getInheritedBackgroundColor: function() { return "#FFFFFF"; },
                setElementOpacity: function() {},
                getElementOpacity: function() { return 1; }
            };
            
            // Mock other required objects
            window.Array = window.Array || {};
            window.Array.indexOf = window.Array.indexOf || function() { return -1; };
            window.String = window.String || {};
            window.String.isInstanceOfType = window.String.isInstanceOfType || function() { return false; };
            
            // Set the base URL for relative URL resolution
            window.location.href = "'.addslashes($url_apply).'";
        }
    });

    // Create DOM with custom resource loader
    const dom = new JSDOM(`'.addslashes($html).'\\n\\n<script>\\n\\n// Override XMLHttpRequest to prevent external requests\\nwindow.XMLHttpRequest = function() {\\n    this.open = function() {};\\n    this.send = function() {};\\n    this.setRequestHeader = function() {};\\n    this.addEventListener = function() {};\\n};\\n</script>`, { 
        runScripts: "dangerously",
        resources: resourceLoader,
        virtualConsole: virtualConsole,
        beforeParse: resourceLoader.beforeParse
    });

    // Wait for scripts to execute
    dom.window.addEventListener("load", () => {
        setTimeout(() => {
            try {
                // Try to get the value directly
                const toolkitInput = dom.window.document.getElementById("ToolkitScriptManager1_HiddenField");
                const toolkitValue = toolkitInput ? toolkitInput.value : "Element not found";
                
                // If not found, try to find it in the page JavaScript
                if (!toolkitValue || toolkitValue === "Element not found") {
                    const scripts = dom.window.document.getElementsByTagName("script");
                    for (let script of scripts) {
                        if (script.textContent && script.textContent.includes("ToolkitScriptManager1_HiddenField")) {
                            const match = script.textContent.match(/ToolkitScriptManager1_HiddenField[^>]*value=["\']([^"\']*)["\']/i);
                            if (match && match[1]) {
                                console.log(match[1]);
                                return;
                            }
                        }
                    }
                }
                
                console.log(toolkitValue || "Value not found in DOM");
            } catch (e) {
                console.error("Error:", e.message);
            }
        }, 5000); // Increased timeout to 5 seconds
    });
']);

// $process = new Process(['node', '-e', '']);


    // Set timeout and working directory
    $process->setTimeout(30); // 30 seconds timeout
    $process->setWorkingDirectory(base_path());
    
    // Run the process
    $process->mustRun();
    
    // Get and return the output
    $output = $process->getOutput();
    dd($output);
    
} catch (ProcessFailedException $e) {
    \Log::error('Node.js process failed: ' . $e->getMessage());
    \Log::error('Process output: ' . $e->getProcess()->getOutput());
    dd('Error: ' . $e->getMessage());
} catch (\Exception $e) {
    \Log::error('Error: ' . $e->getMessage());
    dd('Error: ' . $e->getMessage());
}

                            // try {
                            //     $detailResponse = $client->get($url_apply);
                            //     $crawler = new Crawler((string)$detailResponse->getBody());
                            //     $value = $crawler->filter('#ToolkitScriptManager1_HiddenField')->attr('value');
                                
                            //     // If not found, try to extract from JavaScript
                            //     if (empty($value)) {
                            //         $html = (string)$detailResponse->getBody();
                            //         if (preg_match('/ToolkitScriptManager1_HiddenField[^>]*value="([^"]*)"/i', $html, $matches)) {
                            //             $value = $matches[1];
                            //         }
                            //     }
                                
                            //     // If still not found, try with a different approach
                            //     if (empty($value)) {
                            //         $value = $this->extractToolkitValue($html);
                            //     }
                                
                            //     dd('Extracted value:', $value);
                                
                            // } catch (\Exception $e) {
                            //     dd('Error:', $e->getMessage());
                            // }
                            
                            // In your method:
                            // $client = PantherClient::createFirefoxClient();
                            // $crawler = $client->request('GET', $url_apply);

                            // // Wait for the element to be present
                            // $client->waitFor('#ToolkitScriptManager1_HiddenField');

                            // // Now you can get the value
                            // $value = $crawler->filter('#ToolkitScriptManager1_HiddenField')->attr('value');
                            // dd($value);

                            // // 3. Extract the new viewstate and event validation
                            // $ToolkitScriptManager1_HiddenField_value = $crawler->filter('input#ToolkitScriptManager1_HiddenField')->attr('value');
                            $toolkitScriptManagerValue = $browser->script("return document.getElementById('ToolkitScriptManager1_HiddenField').value;");
                            $viewState = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value');
                            $viewStateGenerator = $crawler->filter('input[name="__VIEWSTATEGENERATOR"]')->attr('value');
                            $eventValidation = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value');
                            dd($toolkitScriptManagerValue);

                            $apply_quotation_data = [
                                'ToolkitScriptManager1_HiddenField' => '',
                                '__EVENTTARGET' => 'ctl00$MainContent$btn2',
                                // 'ToolkitScriptManager1_HiddenField' => ';;AjaxControlToolkit, Version=3.5.40412.0, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:065e08c0-e2d1-42ff-9483-e5c14441b311:475a4ef5:5546a2b:d2e10b12:effe2a26:b209f5e5',
                                // '__EVENTTARGET' => '',
                                '__EVENTARGUMENT' => '',
                                '__LASTFOCUS' => '',
                                '__VIEWSTATE' => $viewState,
                                '__VIEWSTATEGENERATOR' => '9FB3928F',
                                '__EVENTVALIDATION' => $eventValidation,
                                'ctl00$MainContent$tNoPendaftaran' => 'IP0302888-W',
                                'ctl00$MainContent$btnQuotation' => 'Semak',
                                'ctl00$MainContent$lbllayak' => '',
                                // 'ctl00$MainContent$btnBack' => 'Kembali',
                            ];
                            // dd($apply_quotation_data);
                            
                            // 5. Submit the form with the same client to maintain session
                            try {
                                $response = $client->post($url_apply, [
                                    'form_params' => $apply_quotation_data,
                                    'headers' => [
                                        'Content-Type' => 'application/x-www-form-urlencoded',
                                        'Referer' => $url_apply,
                                        'Origin' => parse_url($url_apply, PHP_URL_SCHEME) . '://' . parse_url($url_apply, PHP_URL_HOST),
                                    ]
                                ]);
                                
                                $crawler = new Crawler((string)$response->getBody());
                                dd($apply_quotation_data, $crawler->html());
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

    // Helper for extracting ToolkitScriptManager1_HiddenField value
    // private function extractToolkitValue($html)
    // {
    //     // Try different patterns to extract the value
    //     $patterns = [
    //         '/ToolkitScriptManager1_HiddenField[^>]*value="([^"]*)"/i',
    //         '/id="ToolkitScriptManager1_HiddenField"[^>]*value="([^"]*)"/i',
    //         '/ToolkitScriptManager1_HiddenField.*?value="(.*?)"/is'
    //     ];
        
    //     foreach ($patterns as $pattern) {
    //         if (preg_match($pattern, $html, $matches)) {
    //             return $matches[1];
    //         }
    //     }
        
    //     return null;
    // }
    

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