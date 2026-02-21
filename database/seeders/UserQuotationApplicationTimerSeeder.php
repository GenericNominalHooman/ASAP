<?php

namespace Database\Seeders;

use App\Models\UserQuotationApplicationTimer;
use Illuminate\Database\Seeder;

class UserQuotationApplicationTimerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timings = ["09:00", "13:00", "22:00"];

        foreach ($timings as $timing) {
            UserQuotationApplicationTimer::factory()->create([
                'user_id' => 1,
                'timing' => $timing,
                'enabled' => true,
            ]);
        }
    }
}
