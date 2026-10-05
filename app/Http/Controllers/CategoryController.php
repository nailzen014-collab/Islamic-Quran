<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $recipes = Recipe::query()->get(['name', 'cuisine', 'difficulty', 'meal_types', 'tags', 'image']);

        $cuisines = Recipe::allCuisines()->map(fn (string $cuisine): array => [
            'name' => $cuisine,
            'count' => $recipes->where('cuisine', $cuisine)->count(),
            'cover' => $recipes->firstWhere('cuisine', $cuisine)?->image,
            'top' => $recipes->where('cuisine', $cuisine)->pluck('tags')->flatten()->unique()->take(4)->values(),
        ])->sortByDesc('count')->values();

        $mealTypes = Recipe::allMealTypes()->map(fn (string $type): array => [
            'name' => $type,
            'count' => $recipes->filter(fn (Recipe $recipe): bool => in_array($type, $recipe->meal_types ?? [], true))->count(),
            'cover' => $recipes->first(fn (Recipe $recipe): bool => in_array($type, $recipe->meal_types ?? [], true))?->image,
        ])->sortByDesc('count')->values();

        $tags = Recipe::allTags()->take(30)->map(fn (string $tag): array => [
            'name' => $tag,
            'count' => $recipes->filter(fn (Recipe $recipe): bool => in_array($tag, $recipe->tags ?? [], true))->count(),
        ])->sortByDesc('count')->values();

        $difficulties = Recipe::query()
            ->select('difficulty')
            ->selectRaw('count(*) as total')
            ->groupBy('difficulty')
            ->pluck('total', 'difficulty');

        return view('kategori.index', [
            'cuisines' => $cuisines,
            'mealTypes' => $mealTypes,
            'tags' => $tags,
            'difficulties' => $difficulties,
        ]);
    }
}