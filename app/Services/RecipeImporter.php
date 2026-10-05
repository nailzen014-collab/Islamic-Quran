<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecipeImporter
{
    public const ENDPOINT = 'https://dummyjson.com/recipes';

    /**
     * Import every recipe from the public API, refreshing rows we already have.
     *
     * @return int Number of recipes written.
     *
     * @throws ConnectionException
     */
    public function sync(): int
    {
        $payload = Http::timeout(30)
            ->acceptJson()
            ->get(self::ENDPOINT, ['limit' => 100])
            ->throw()
            ->json();

        $recipes = $payload['recipes'] ?? [];

        foreach ($recipes as $data) {
            $this->persist($data);
        }

        return count($recipes);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function persist(array $data): Recipe
    {
        return Recipe::updateOrCreate(
            ['external_id' => (int) $data['id']],
            [
                'name' => (string) $data['name'],
                'summary' => $this->summary($data),
                'image' => $data['image'] ?? null,
                'cuisine' => $data['cuisine'] ?? null,
                'difficulty' => $data['difficulty'] ?? null,
                'prep_time_minutes' => (int) ($data['prepTimeMinutes'] ?? 0),
                'cook_time_minutes' => (int) ($data['cookTimeMinutes'] ?? 0),
                'servings' => (int) ($data['servings'] ?? 1),
                'calories_per_serving' => (int) ($data['caloriesPerServing'] ?? 0),
                'rating' => (float) ($data['rating'] ?? 0),
                'review_count' => (int) ($data['reviewCount'] ?? 0),
                'meal_types' => array_values((array) ($data['mealType'] ?? [])),
                'tags' => array_values((array) ($data['tags'] ?? [])),
                'ingredients' => array_values((array) ($data['ingredients'] ?? [])),
                'instructions' => array_values((array) ($data['instructions'] ?? [])),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function summary(array $data): ?string
    {
        $instructions = array_values((array) ($data['instructions'] ?? []));
        $ingredients = array_values((array) ($data['ingredients'] ?? []));

        if ($instructions === []) {
            return null;
        }

        return sprintf(
            '%d bahan, %d langkah, total %d menit untuk %d porsi.',
            count($ingredients),
            count($instructions),
            (int) ($data['prepTimeMinutes'] ?? 0) + (int) ($data['cookTimeMinutes'] ?? 0),
            (int) ($data['servings'] ?? 1),
        );
    }

    /**
     * Best-effort sync used when the database is still empty.
     */
    public function syncQuietly(): Collection
    {
        try {
            $this->sync();
        } catch (ConnectionException $exception) {
            Log::warning('Recipe sync skipped: '.$exception->getMessage());
        }

        return Recipe::query()->get();
    }
}
