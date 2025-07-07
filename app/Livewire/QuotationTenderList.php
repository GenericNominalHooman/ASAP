<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Attributes\Layout;
use App\Models\quotation_application;

#[Layout('layouts.app')] 
class QuotationTenderList extends Component
{
    public $url = '';
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
        $this->validate(['url' => 'required|url']);
        
        try {
            // $response = Http::get($this->url);
            $response = Http::withOptions([
                'verify' => storage_path('certs/cacert.pem')
            ])->get($this->url);            
            
            if ($response->successful()) {
                $crawler = new Crawler($response->body());
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
                    '__EVENTTARGET' => 'ctl00$MainContent$DataPager1$ctl00$ctl02', // Change ct100..ct103 to change pagination
                    '__EVENTARGUMENT' => '',
                    '__LASTFOCUS' => '',
                    '__VIEWSTATE' => $viewState,
                    '__VIEWSTATEGENERATOR' => '0C32CE3C',
                    '__EVENTVALIDATION' => $eventValidation,
                    'ctl00$MainContent$dlJabatan' => 4, // Change 1..8 to change jabatan
                ];

                $response = Http::withOptions([
                    'verify' => storage_path('certs/cacert.pem'),
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Referer' => $this->url,
                        'Origin' => parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST),
                    ]
                ])->asForm()->post($this->url, $redirect_data);
                // dd([
                //     'status' => $response->status(),
                //     'headers' => $response->headers(),
                //     'body' => $response->body(),
                //     'body_json' => json_decode($response->body()),
                //     'is_successful' => $response->successful()
                // ]);
                // After getting the response
                if ($response->successful()) {
                    $crawler = new Crawler($response->body());
                    
                    // Extract the data tables
                    dd($crawler);   
                    $tenders = $crawler->filter('Table')->each(function (Crawler $node) {
                        return [
                            'ref_no' => $node->filter('refNo')->text(),
                            'pro_no' => $node->filter('ProNo')->text(),
                            'title' => $node->filter('Tajuk')->text(),
                            'start_date' => $node->filter('IklanStartDate')->text(),
                            'end_date' => $node->filter('IklanEndDate')->text(),
                            'site_visit' => $node->filter('SiteVisit')->text(),
                        ];
                    });

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