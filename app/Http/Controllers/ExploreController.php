<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    /**
     * Searchable, filterable recipe catalogue with infinite scroll.
     */
    public function index(Request $request): View
    {
        $recipes = $this->filteredQuery($request)
            ->withCount(['reviews', 'favorites'])
            ->paginate(12)
            ->withQueryString();

        return view('explore.index', [
            'recipes' => $recipes,
            'filters' => $this->filterPayload($request),
            'cuisines' => Recipe::allCuisines(),
            'mealTypes' => Recipe::allMealTypes(),
            'difficulties' => Recipe::difficulties(),
            'topTags' => Recipe::allTags()->take(14),
        ]);
    }

    /**
     * The same list rendered as a partial for the "load more" endpoint.
     */
    public function cards(Request $request)
    {
        $recipes = $this->filteredQuery($request)
            ->withCount(['reviews', 'favorites'])
            ->paginate(12)
            ->withQueryString();

        return view('partials.recipe-grid', [
            'recipes' => $recipes,
            'filters' => $this->filterPayload($request),
        ]);
    }

    private function filteredQuery(Request $request)
    {
        return Recipe::query()
            ->search($request->string('q')->trim()->value())
            ->whereListContains('meal_types', $request->input('meal_types'))
            ->whereListContains('tags', $request->input('tags'))
            ->when($request->filled('cuisine'), fn ($query) => $query->where('cuisine', $request->input('cuisine')))
            ->when($request->filled('difficulty'), fn ($query) => $query->where('difficulty', $request->input('difficulty')))
            ->when($request->filled('max_time'), fn ($query) => $query->whereRaw(
                'prep_time_minutes + cook_time_minutes <= ?',
                [(int) $request->input('max_time')]
            ))
            ->when($request->filled('max_calories'), fn ($query) => $query->where(
                'calories_per_serving',
                '<=',
                (int) $request->input('max_calories')
            ))
            ->sorted($request->input('sort'));
    }

    /**
     * @return array<string, mixed>
     */
    private function filterPayload(Request $request): array
    {
        return [
            'q' => $request->string('q')->trim()->value(),
            'cuisine' => $request->input('cuisine'),
            'difficulty' => $request->input('difficulty'),
            'meal_types' => (array) $request->input('meal_types', []),
            'tags' => (array) $request->input('tags', []),
            'max_time' => $request->input('max_time'),
            'max_calories' => $request->input('max_calories'),
            'sort' => $request->input('sort', 'relevan'),
        ];
    }
}
