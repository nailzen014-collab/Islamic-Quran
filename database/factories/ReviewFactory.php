<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'user_id' => User::factory(),
            'author_name' => fake()->name(),
            'rating' => fake()->numberBetween(3, 5),
            'body' => fake()->paragraph(),
            'is_recipe_owner_cook' => false,
        ];
    }
}