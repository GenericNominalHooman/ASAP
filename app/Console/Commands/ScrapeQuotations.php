<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\ScraperService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScrapeQuotations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-quotations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks user timers and scrapes quotations if scheduled time is reached';

    /**
     * Execute the console command.
     */
    public function handle(ScraperService $scraperService)
    {
        $this->info('Starting background quotation scraping check...');
        $now = Carbon::now();
        $range = 5; // Allow a 5-minute variance

        // Fetch users who have enabled timers
        $users = User::whereHas('quotationApplicationTimers', function ($query) {
            $query->where('enabled', true);
        })->get();

        $scrapedCount = 0;

        foreach ($users as $user) {
            $timerSet = false;
            $timers = $user->quotationApplicationTimers()->where('enabled', true)->pluck('timing');

            foreach ($timers as $timer) {
                try {
                    $timerCarbon = Carbon::parse($timer);
                    $lowerBound = $timerCarbon->copy()->subMinutes($range);
                    $upperBound = $timerCarbon->copy()->addMinutes($range);

                    if ($now->between($lowerBound, $upperBound)) {
                        $timerSet = true;
                        break;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to parse user timer for user {$user->id}: {$timer}");
                }
            }

            if ($timerSet) {
                $this->info("Scheduled scraping applies for User ID: {$user->id} ({$user->name}). Initiating scrape...");
                try {
                    // Call the shared ScraperService
                    $scraperService->scrapeForUser($user);
                    $this->info("Successfully completed scraping for User ID: {$user->id}");
                    $scrapedCount++;
                } catch (\Exception $e) {
                    $this->error("Scraping failed for User ID: {$user->id}. Error: " . $e->getMessage());
                    Log::error("Scraping command failed for User ID: {$user->id}", ['exception' => $e]);
                }
            }
        }

        $this->info("Scraping check completed. Processed {$scrapedCount} user(s).");
    }
}
