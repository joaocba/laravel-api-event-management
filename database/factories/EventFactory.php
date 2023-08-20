<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // This function defines the default attributes for an Event model
        return [
            'name' => fake()->unique()->sentence(3), // Generates a unique sentence as the event name
            'description' => fake()->text, // Generates a random text for the event description
            'start_time' => fake()->dateTimeBetween('now', '+1 month'), // Generates a random start time within the next month
            'end_time' => fake()->dateTimeBetween('+1 month', '+2 months') // Generates a random end time within the next two months
        ];
    }
}
