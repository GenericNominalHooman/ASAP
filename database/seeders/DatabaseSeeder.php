<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\QuotationApplicationSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create defualt user for debugging purposes
        User::factory()->create([
            'name' => 'MUHAMMAD ISKANDAR LUQMAN BIN ZAHARI',
            'email' => 'user1@mail.com',
            'ssm_number' => 'IP0302888-W',
            'password' => ('password'),
        ]);

        // Call all seeders
        $this->call([
            QuotationApplicationSeeder::class,
            GredLevelSeeder::class,
            SpecializationSeeder::class,
        ]);
    }
}
