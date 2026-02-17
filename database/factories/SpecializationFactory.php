<?php

namespace Database\Factories;

use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    protected $model = Specialization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'specialization' => $this->faker->regexify('[B|C|M|E][0-9]{2}'),
            'user_id' => User::factory(),
        ];
    }
}
