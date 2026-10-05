<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const DEMO_OWNER_EMAIL = 'nadia@resep.id';

    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'body' => ['required', 'string', 'min:10', 'max:1000'],
            'is_recipe_owner_cook' => ['nullable', 'boolean'],
        ], [
            'rating.required' => 'Pilih rating dulu ya.',
            'body.min' => 'Ulasan minimal 10 karakter.',
            'body.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        Review::create([
            'recipe_id' => $recipe->id,
            'user_id' => Auth::id(),
            'author_name' => $this->resolveAuthorName($request),
            'rating' => $validated['rating'],
            'body' => $validated['body'],
            'is_recipe_owner_cook' => $this->resolveCookedFlag($request, $recipe),
        ]);

        return back()->with('status', 'Ulasan kamu sudah tersimpan. Terima kasih!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        abort_unless(
            Auth::check() && ($review->user_id === Auth::id() || Auth::user()->email === self::DEMO_OWNER_EMAIL),
            403,
        );

        $review->delete();

        return back()->with('status', 'Ulasan dihapus.');
    }

    /**
     * Toggle the "I made this" badge without a full page reload.
     */
    public function cooked(Request $request, Review $review): JsonResponse
    {
        abort_unless(
            Auth::check() && ($review->user_id === Auth::id() || Auth::user()->email === self::DEMO_OWNER_EMAIL),
            403,
        );

        $review->update(['is_recipe_owner_cook' => ! $review->is_recipe_owner_cook]);

        return response()->json(['is_recipe_owner_cook' => $review->is_recipe_owner_cook]);
    }

    private function resolveAuthorName(Request $request): string
    {
        if (Auth::check()) {
            return Auth::user()->name;
        }

        $name = trim((string) $request->input('author_name'));

        return $name === '' ? 'Tamu Dapur' : $name;
    }

    private function resolveCookedFlag(Request $request, Recipe $recipe): bool
    {
        if (! Auth::check() || ! $request->boolean('is_recipe_owner_cook')) {
            return false;
        }

        return ! Review::where('recipe_id', $recipe->id)
            ->where('user_id', Auth::id())
            ->where('is_recipe_owner_cook', true)
            ->exists();
    }
}