<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        $prep = fake()->numberBetween(5, 40);
        $cook = fake()->numberBetween(5, 60);
        $cuisine = fake()->randomElement(['Italian', 'Japanese', 'Mexican', 'Indian', 'Thai', 'American']);

        return [
            'external_id' => fake()->unique()->numberBetween(10000, 99999),
            'slug' => Str::slug(fake()->unique()->words(3, true)),
            'name' => Str::title(fake()->words(3, true)),
            'summary' => fake()->sentence(12),
            'image' => 'https://cdn.dummyjson.com/recipe-images/'.fake()->numberBetween(1, 50).'.webp',
            'cuisine' => $cuisine,
            'difficulty' => fake()->randomElement(['Easy', 'Medium', 'Hard']),
            'prep_time_minutes' => $prep,
            'cook_time_minutes' => $cook,
            'servings' => fake()->numberBetween(2, 6),
            'calories_per_serving' => fake()->numberBetween(120, 900),
            'rating' => fake()->randomFloat(1, 3.5, 5),
            'review_count' => fake()->numberBetween(10, 400),
            'meal_types' => fake()->randomElements([['Breakfast'], ['Lunch'], ['Dinner'], ['Dessert'], ['Snack']]),
            'tags' => fake()->randomElements([['Quick'], ['Vegetarian'], ['Grilling'], ['Street food'], ['Soup']]),
            'ingredients' => fake()->sentences(fake()->numberBetween(4, 8)),
            'instructions' => fake()->sentences(fake()->numberBetween(4, 7)),
        ];
    }
}