<?php

namespace Database\Seeders;

use App\Models\GredLevel;
use Illuminate\Database\Seeder;

class GredLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GredLevel::create([
            'user_id' => 1,
            'g_level' => 'G2',
        ]);
    }
}
