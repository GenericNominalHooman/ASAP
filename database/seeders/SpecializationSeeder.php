<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'B04',
            'B07',
            'B11',
            'B28',
            'CE01',
            'CE08',
            'CE18',
            'CE21',
            'CE32',
            'CE36',
            'CE42',
            'M02',
            'M15',
            'M20'
        ];

        foreach ($specializations as $spec) {
            Specialization::create([
                'user_id' => 1,
                'specialization' => $spec,
            ]);
        }
    }
}
