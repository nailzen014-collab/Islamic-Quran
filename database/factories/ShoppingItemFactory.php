<?php

namespace Database\Factories;

use App\Models\ShoppingItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShoppingItem>
 */
class ShoppingItemFactory extends Factory
{
    protected $model = ShoppingItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipe_id' => null,
            'name' => Str::title(fake()->words(2, true)),
            'category' => fake()->randomElement(['Sembako', 'Sayur', 'Protein', 'Bumbu', 'Rumah']),
            'quantity' => fake()->numberBetween(1, 5),
            'is_checked' => false,
        ];
    }
}