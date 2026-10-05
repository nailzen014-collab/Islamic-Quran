<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CollectionController extends Controller
{
    public function index(): RedirectResponse
    {
        $collection = Auth::user()->collections()->orderByDesc('updated_at')->first();

        if (! $collection) {
            return redirect()->route('koleksi.create');
        }

        return redirect()->route('koleksi.show', $collection);
    }

    public function show(Collection $collection)
    {
        abort_unless($collection->user_id === Auth::id(), 404);

        $collection->load(['recipes' => fn ($query) => $query->withCount('reviews')]);
        $collection->loadCount('recipes');

        return view('koleksi.show', [
            'collection' => $collection,
        ]);
    }

    public function create(): RedirectResponse
    {
        $this->ensureStarterCollection();

        return redirect()->route('koleksi.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'description' => ['nullable', 'string', 'max:200'],
            'accent' => ['required', Rule::in(['orange', 'amber', 'rose', 'fuchsia', 'sky', 'emerald'])],
        ], [
            'name.min' => 'Nama koleksi minimal 2 karakter.',
            'accent.required' => 'Pilih warna aksen.',
        ]);

        Collection::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('koleksi.index')->with('status', 'Koleksi baru dibuat.');
    }

    public function update(Request $request, Collection $collection): RedirectResponse
    {
        abort_unless($collection->user_id === Auth::id(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'description' => ['nullable', 'string', 'max:200'],
            'accent' => ['required', Rule::in(['orange', 'amber', 'rose', 'fuchsia', 'sky', 'emerald'])],
        ]);

        $collection->update($validated);

        return back()->with('status', 'Koleksi diperbarui.');
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        abort_unless($collection->user_id === Auth::id(), 404);

        $collection->delete();

        return redirect()->route('koleksi.index')->with('status', 'Koleksi dihapus.');
    }

    /**
     * Add or remove a recipe from a collection.
     */
    public function toggleRecipe(Request $request, Collection $collection): JsonResponse|RedirectResponse
    {
        abort_unless($collection->user_id === Auth::id(), 404);

        $validated = $request->validate([
            'recipe_id' => ['required', Rule::exists('recipes', 'id')],
        ]);

        $recipeId = (int) $validated['recipe_id'];
        $attached = $collection->recipes()->where('recipes.id', $recipeId)->exists();

        if ($attached) {
            $collection->recipes()->detach($recipeId);
            $inCollection = false;
        } else {
            $collection->recipes()->attach($recipeId);
            $inCollection = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'in_collection' => $inCollection,
                'count' => $collection->recipes()->count(),
                'message' => $inCollection ? 'Masuk ke koleksi.' : 'Dikeluarkan dari koleksi.',
            ]);
        }

        return back();
    }

    private function ensureStarterCollection(): void
    {
        $user = Auth::user();

        if ($user->collections()->exists()) {
            return;
        }

        $collection = Collection::create([
            'user_id' => $user->id,
            'name' => 'Favorit Pertama',
            'description' => 'Resep yang selalu kamu masak ulang.',
            'accent' => 'orange',
        ]);

        $collection->recipes()->sync(
            Recipe::query()->inRandomOrder()->limit(3)->pluck('id')
        );
    }
}
