<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\quotation_application>
 */
class QuotationApplicationFactory extends Factory
{
    protected $model = \App\Models\quotation_application::class; // Define model location

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => Str::random(20),
            'title' => fake()->sentence(6),
            'specializations' => 'CE' . str_pad(rand(1, 39), 2, '0', STR_PAD_LEFT),
            'begin_register_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_register_date' => fake()->dateTimeBetween('+1 week', '+2 week'),
            'closing_date' => fake()->dateTimeBetween('+2 week', '+3 week'),
            'slip_path' => fake()->url(),
            'site_visit_location' => fake()->address(),
            'site_visit_date' => fake()->dateTimeBetween('+1 week', '+2 week'),
            'advert_path' => fake()->url(),
            'serial_number' => "ip" . fake()->randomNumber(8),
            'owner' => fake()->randomElement(['Abg E', 'Zahari']),
            'status' => fake()->randomElement(['sudah hantar', 'tidak hantar', 'proses']),
            'user_id' => fake()->randomElement([1]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
