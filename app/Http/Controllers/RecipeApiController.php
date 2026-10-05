<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Services\RecipeImporter;
use Illuminate\Http\Request;

class RecipeApiController extends Controller
{
    /**
     * JSON feed consumed by the front-end "load more" and external clients.
     */
    public function index(Request $request)
    {
        $recipes = Recipe::query()
            ->search($request->string('q')->trim()->value())
            ->whereListContains('meal_types', $request->input('meal_types'))
            ->whereListContains('tags', $request->input('tags'))
            ->when($request->filled('cuisine'), fn ($query) => $query->where('cuisine', $request->input('cuisine')))
            ->when($request->filled('difficulty'), fn ($query) => $query->where('difficulty', $request->input('difficulty')))
            ->when($request->filled('max_time'), fn ($query) => $query->whereRaw(
                'prep_time_minutes + cook_time_minutes <= ?',
                [(int) $request->input('max_time')]
            ))
            ->sorted($request->input('sort'))
            ->paginate((int) $request->input('per_page', 12));

        return response()->json([
            'data' => $recipes->through(fn (Recipe $recipe): array => [
                'id' => $recipe->external_id,
                'slug' => $recipe->slug,
                'name' => $recipe->name,
                'image' => $recipe->image,
                'cuisine' => $recipe->cuisine,
                'difficulty' => $recipe->difficulty,
                'meal_types' => $recipe->meal_types,
                'tags' => $recipe->tags,
                'prep_time_minutes' => $recipe->prep_time_minutes,
                'cook_time_minutes' => $recipe->cook_time_minutes,
                'total_minutes' => $recipe->total_minutes,
                'servings' => $recipe->servings,
                'calories_per_serving' => $recipe->calories_per_serving,
                'rating' => $recipe->rating,
                'review_count' => $recipe->review_count,
                'url' => route('recipes.show', $recipe),
            ]),
            'meta' => [
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage(),
                'per_page' => $recipes->perPage(),
                'total' => $recipes->total(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $recipe = Recipe::query()->where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $recipe->external_id,
                'name' => $recipe->name,
                'summary' => $recipe->summary,
                'image' => $recipe->image,
                'cuisine' => $recipe->cuisine,
                'difficulty' => $recipe->difficulty,
                'meal_types' => $recipe->meal_types,
                'tags' => $recipe->tags,
                'prep_time_minutes' => $recipe->prep_time_minutes,
                'cook_time_minutes' => $recipe->cook_time_minutes,
                'servings' => $recipe->servings,
                'calories_per_serving' => $recipe->calories_per_serving,
                'rating' => $recipe->rating,
                'review_count' => $recipe->review_count,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
            ],
            'source' => RecipeImporter::ENDPOINT.'/'.$recipe->external_id,
        ]);
    }
}