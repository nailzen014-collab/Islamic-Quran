<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function __invoke(): View
    {
        $recipes = Recipe::query()->get();

        $byDifficulty = $recipes
            ->groupBy('difficulty')
            ->map(fn ($group, $difficulty): array => [
                'name' => $difficulty ?: 'Tidak ditentukan',
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->values();

        $byCuisine = $recipes
            ->groupBy('cuisine')
            ->map(fn ($group, $cuisine): array => [
                'name' => $cuisine ?: 'Tanpa asal',
                'count' => $group->count(),
                'minutes' => (int) $group->avg(fn (Recipe $recipe): int => $recipe->total_minutes),
                'calories' => (int) $group->avg('calories_per_serving'),
            ])
            ->sortByDesc('count')
            ->take(12)
            ->values();

        $totalMinutes = $recipes->sum(fn (Recipe $recipe): int => $recipe->total_minutes);
        $maxMinutes = max(1, $recipes->max(fn (Recipe $recipe): int => $recipe->total_minutes));

        $calorieBuckets = collect([
            ['label' => '< 200 kkal', 'min' => 0, 'max' => 199],
            ['label' => '200 - 399', 'min' => 200, 'max' => 399],
            ['label' => '400 - 599', 'min' => 400, 'max' => 599],
            ['label' => '600+', 'min' => 600, 'max' => PHP_INT_MAX],
        ])->map(fn (array $bucket): array => [
            'label' => $bucket['label'],
            'count' => $recipes->whereBetween('calories_per_serving', [$bucket['min'], $bucket['max']])->count(),
        ])->values();

        $user = Auth::user();

        return view('statistik.index', [
            'totalRecipes' => $recipes->count(),
            'totalIngredients' => $recipes->sum(fn (Recipe $recipe): int => count($recipe->ingredients ?? [])),
            'totalSteps' => $recipes->sum(fn (Recipe $recipe): int => count($recipe->instructions ?? [])),
            'totalMinutes' => $totalMinutes,
            'maxMinutes' => $maxMinutes,
            'avgRating' => round((float) $recipes->avg('rating'), 2),
            'avgCalories' => (int) $recipes->avg('calories_per_serving'),
            'avgServings' => round((float) $recipes->avg('servings'), 1),
            'byDifficulty' => $byDifficulty,
            'byCuisine' => $byCuisine,
            'calorieBuckets' => $calorieBuckets,
            'tagCounts' => Recipe::allTags()->take(18),
            'fastest' => $recipes->sortBy(fn (Recipe $recipe): int => $recipe->total_minutes)->take(5)->values(),
            'topRated' => $recipes->sortByDesc('rating')->take(5)->values(),
            'myStats' => $user ? [
                'favorites' => $user->favorites()->count(),
                'reviews' => $user->reviews()->count(),
                'collections' => $user->collections()->count(),
                'planned' => $user->mealPlans()->where('planned_for', '>=', now()->startOfWeek())->count(),
                'shopping' => $user->shoppingItems()->where('is_checked', false)->count(),
            ] : null,
            'recentReviews' => Review::query()->with('recipe')->latest()->take(5)->get(),
            'plannedThisWeek' => $user ? $user->mealPlans()->whereBetween('planned_for', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])->count() : 0,
            'slotUsage' => MealPlan::query()
                ->selectRaw('slot, count(*) as total')
                ->groupBy('slot')
                ->pluck('total', 'slot'),
        ]);
    }
}