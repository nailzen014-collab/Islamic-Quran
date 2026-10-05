<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $recipe = Recipe::query()
            ->where('slug', $slug)
            ->with(['reviews.user', 'collections' => fn ($query) => $query
                ->where('user_id', Auth::id())
                ->select('collections.id'),
            ])
            ->withCount('reviews')
            ->firstOrFail();

        $recipe->loadMissing(['reviews' => fn ($query) => $query->latest()->limit(6)]);

        $related = Recipe::query()
            ->whereKeyNot($recipe->id)
            ->where(function ($query) use ($recipe) {
                $query->where('cuisine', $recipe->cuisine)
                    ->orWhereIn('difficulty', $recipe->meal_types ?? []);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $related = $related->concat(
                Recipe::query()->whereKeyNot($recipe->id)->inRandomOrder()->limit(4 - $related->count())->get()
            );
        }

        return view('recipes.show', [
            'recipe' => $recipe,
            'related' => $related,
            'isFavorited' => $recipe->isFavoritedBy(Auth::user()),
            'userCollections' => Auth::check()
                ? Auth::user()->collections()->withCount('recipes')->get()
                : collect(),
            'distribution' => $this->ratingDistribution($recipe),
        ]);
    }

    /**
     * Step-by-step cook mode: one instruction per screen.
     */
    public function cook(string $slug): View
    {
        $recipe = Recipe::query()->where('slug', $slug)->firstOrFail();

        return view('recipes.cook', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @return array<int, int>
     */
    private function ratingDistribution(Recipe $recipe): array
    {
        $counts = $recipe->reviews()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->all();

        $distribution = [];

        for ($star = 5; $star >= 1; $star--) {
            $distribution[$star] = (int) ($counts[$star] ?? 0);
        }

        return $distribution;
    }
}
