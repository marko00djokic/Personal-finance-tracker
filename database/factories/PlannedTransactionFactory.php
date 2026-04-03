<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlannedTransaction>
 */
class PlannedTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'description' => $this->faker->optional()->sentence(),
            'recurrence_type' => $this->faker->randomElement(['none', 'daily', 'weekly', 'monthly', 'yearly']),
            'recurrence_day' => $this->faker->optional()->numberBetween(1, 28),
            'next_due_date' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'is_active' => true,
        ];
    }
}
