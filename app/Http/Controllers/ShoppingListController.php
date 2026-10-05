<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\ShoppingItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShoppingListController extends Controller
{
    /**
     * Group categories used when ingredients are pulled out of a recipe.
     *
     * @var array<string, string>
     */
    private const CATEGORY_MAP = [
        'milk|cream|yogurt|butter|cheese' => 'Susu',
        'chicken|beef|pork|shrimp|fish|egg|tempe|tofu' => 'Protein',
        'onion|garlic|tomato|potato|carrot|pepper|leaf|lettuce|spinach|cabbage' => 'Sayur',
        'sugar|salt|flour|cumin|paprika|peppercorn|oil|coriander|turmeric|vinegar|soy' => 'Bumbu',
        'rice|noodle|pasta|bread|oat' => 'Sembako',
    ];

    public function index(Request $request): View
    {
        $filter = $request->input('filter', 'all');

        $query = Auth::user()->shoppingItems();

        if ($filter === 'todo') {
            $query->where('is_checked', false);
        }

        if ($filter === 'done') {
            $query->where('is_checked', true);
        }

        $items = $query->orderBy('category')->orderBy('name')->get();

        $grouped = $items->groupBy('category');

        $mealPlanRecipeIds = Auth::user()->mealPlans()
            ->whereBetween('planned_for', [
                Carbon::parse($request->input('week', now()->toDateString()))->startOfWeek()->toDateString(),
                Carbon::parse($request->input('week', now()->toDateString()))->endOfWeek()->toDateString(),
            ])
            ->pluck('recipe_id')
            ->unique();

        return view('belanja.index', [
            'items' => $items,
            'grouped' => $grouped,
            'filter' => $filter,
            'plannedRecipes' => Recipe::query()->whereIn('id', $mealPlanRecipeIds)->get(['id', 'name', 'image']),
            'stats' => [
                'total' => Auth::user()->shoppingItems()->count(),
                'done' => Auth::user()->shoppingItems()->where('is_checked', true)->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'category' => ['required', Rule::in(['Sembako', 'Sayur', 'Protein', 'Susu', 'Bumbu', 'Rumah', 'Lainnya'])],
            'quantity' => ['nullable', 'integer', 'between:1,99'],
        ]);

        ShoppingItem::create([
            ...$validated,
            'quantity' => $validated['quantity'] ?? 1,
            'user_id' => Auth::id(),
        ]);

        return back()->with('status', 'Item ditambahkan ke daftar belanja.');
    }

    public function update(Request $request, ShoppingItem $shoppingItem): RedirectResponse
    {
        abort_unless($shoppingItem->user_id === Auth::id(), 404);

        $validated = $request->validate([
            'is_checked' => ['required', 'boolean'],
        ]);

        $shoppingItem->update($validated);

        return back();
    }

    public function destroy(ShoppingItem $shoppingItem): RedirectResponse
    {
        abort_unless($shoppingItem->user_id === Auth::id(), 404);

        $shoppingItem->delete();

        return back()->with('status', 'Item dihapus.');
    }

    public function clearChecked(): RedirectResponse
    {
        Auth::user()->shoppingItems()->where('is_checked', true)->delete();

        return back()->with('status', 'Item yang sudah dicentang dibersihkan.');
    }

    /**
     * Push every ingredient of the chosen recipes into the list.
     */
    public function importFromRecipes(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipe_ids' => ['required', 'array', 'min:1'],
            'recipe_ids.*' => ['integer', Rule::exists('recipes', 'id')],
        ]);

        $added = 0;

        foreach (Recipe::query()->whereIn('id', $validated['recipe_ids'])->get() as $recipe) {
            foreach ($recipe->ingredients ?? [] as $ingredient) {
                $already = Auth::user()->shoppingItems()
                    ->whereRaw('lower(name) = ?', [mb_strtolower(trim($ingredient))])
                    ->exists();

                if ($already) {
                    continue;
                }

                ShoppingItem::create([
                    'user_id' => Auth::id(),
                    'recipe_id' => $recipe->id,
                    'name' => $ingredient,
                    'category' => $this->guessCategory($ingredient),
                    'quantity' => 1,
                ]);

                $added++;
            }
        }

        return back()->with('status', "{$added} bahan baru masuk daftar belanja.");
    }

    public function toggle(Request $request, ShoppingItem $shoppingItem): JsonResponse
    {
        abort_unless($shoppingItem->user_id === Auth::id(), 404);

        $shoppingItem->update(['is_checked' => ! $shoppingItem->is_checked]);

        return response()->json([
            'is_checked' => $shoppingItem->is_checked,
            'done' => Auth::user()->shoppingItems()->where('is_checked', true)->count(),
            'total' => Auth::user()->shoppingItems()->count(),
        ]);
    }

    private function guessCategory(string $ingredient): string
    {
        $needle = mb_strtolower($ingredient);

        foreach (self::CATEGORY_MAP as $pattern => $category) {
            if (preg_match('/('.$pattern.')/', $needle) === 1) {
                return $category;
            }
        }

        return 'Lainnya';
    }
}
