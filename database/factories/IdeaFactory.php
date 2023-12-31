<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => fake()->numberBetween(1, 10),
            "title" => fake()->words(4, true),
            "description" => fake()->paragraph(5, true),
            // "slug" => fake()->slug(),
            "category_id" => fake()->numberBetween(1, 4),
            "status_id" => fake()->numberBetween(1, 5),
        ];
    }
}
