<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Services\RecipeImporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(RecipeImporter $importer): View
    {
        if (Recipe::query()->doesntExist()) {
            $importer->syncQuietly();
        }

        $user = Auth::user();

        $featured = Recipe::query()
            ->orderByDesc('rating')
            ->orderByDesc('review_count')
            ->limit(3)
            ->get();

        $quick = Recipe::query()
            ->whereRaw('prep_time_minutes + cook_time_minutes <= 25')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $trending = Recipe::query()
            ->orderByDesc('review_count')
            ->limit(6)
            ->get();

        $newest = Recipe::query()
            ->latest('external_id')
            ->limit(6)
            ->get();

        $topRated = Recipe::query()
            ->orderByDesc('rating')
            ->limit(5)
            ->get();

        return view('home', [
            'featured' => $featured,
            'quick' => $quick,
            'trending' => $trending,
            'newest' => $newest,
            'topRated' => $topRated,
            'cuisines' => Recipe::allCuisines(),
            'mealTypes' => Recipe::allMealTypes(),
            'totalRecipes' => Recipe::count(),
            'totalCuisines' => Recipe::allCuisines()->count(),
            'totalTags' => Recipe::allTags()->count(),
            'userFavorites' => $user?->favoritedRecipes()->limit(4)->get() ?? collect(),
        ]);
    }
}
