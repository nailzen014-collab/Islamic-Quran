<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $term = $request->string('q')->trim()->value();

        $results = Recipe::query()
            ->search($term)
            ->orderByDesc('rating')
            ->limit(24)
            ->get();

        $suggestions = $term === ''
            ? collect()
            : Recipe::allTags()
                ->filter(fn (string $tag): bool => str_contains(mb_strtolower($tag), mb_strtolower($term)))
                ->take(8)
                ->values();

        return view('search.index', [
            'term' => $term,
            'results' => $results,
            'suggestions' => $suggestions,
            'popularCuisines' => Recipe::allCuisines()->take(8),
        ]);
    }
}