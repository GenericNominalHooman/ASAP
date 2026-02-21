<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserQuotationApplicationTimer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuotationApplicationTimer>
 */
class UserQuotationApplicationTimerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserQuotationApplicationTimer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'timing' => $this->faker->time('H:i'),
            'enabled' => $this->faker->boolean(),
        ];
    }
}
