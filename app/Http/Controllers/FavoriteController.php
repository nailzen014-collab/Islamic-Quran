<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle a recipe in the signed-in user's favorites.
     */
    public function store(Request $request, Recipe $recipe): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'favorited' => false,
                    'message' => 'Masuk dulu untuk menyimpan favorit.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        $existing = Favorite::where('user_id', Auth::id())->where('recipe_id', $recipe->id)->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create(['user_id' => Auth::id(), 'recipe_id' => $recipe->id]);
            $favorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'favorited' => $favorited,
                'count' => $recipe->favorites()->count(),
                'message' => $favorited ? 'Disimpan ke favorit.' : 'Dihapus dari favorit.',
            ]);
        }

        return back()->with('status', $favorited ? 'Resep disimpan ke favorit.' : 'Resep dihapus dari favorit.');
    }
}