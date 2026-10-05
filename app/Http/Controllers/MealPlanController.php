<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    public function index(Request $request): View
    {
        $weekStart = $request->date('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        $days = collect(range(0, 6))->map(function (int $offset) use ($weekStart): array {
            $date = $weekStart->copy()->addDays($offset);

            return [
                'date' => $date,
                'label' => $date->translatedFormat('D'),
                'day' => $date->day,
                'is_today' => $date->isToday(),
            ];
        });

        $plans = Auth::user()->mealPlans()
            ->whereBetween('planned_for', [$weekStart->toDateString(), $weekStart->copy()->addDays(6)->toDateString()])
            ->with('recipe')
            ->get()
            ->groupBy(fn (MealPlan $plan): string => $plan->planned_for->toDateString());

        return view('rencana.index', [
            'weekStart' => $weekStart,
            'days' => $days,
            'plans' => $plans,
            'slots' => MealPlan::SLOTS,
            'recipes' => Recipe::query()->orderBy('name')->get(['id', 'name', 'image', 'total_minutes', 'difficulty']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipe_id' => ['required', Rule::exists('recipes', 'id')],
            'planned_for' => ['required', 'date'],
            'slot' => ['required', Rule::in(MealPlan::SLOTS)],
            'servings' => ['nullable', 'integer', 'between:1,20'],
        ], [
            'slot.required' => 'Pilih jam makan dulu.',
            'planned_for.required' => 'Pilih tanggal.',
        ]);

        $validated['servings'] = $validated['servings'] ?? 2;
        $validated['user_id'] = Auth::id();

        MealPlan::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'recipe_id' => $validated['recipe_id'],
                'planned_for' => $validated['planned_for'],
                'slot' => $validated['slot'],
            ],
            ['servings' => $validated['servings']],
        );

        return back()->with('status', 'Rencana makan disimpan.');
    }

    public function update(Request $request, MealPlan $mealPlan): RedirectResponse
    {
        abort_unless($mealPlan->user_id === Auth::id(), 404);

        $validated = $request->validate([
            'planned_for' => ['required', 'date'],
            'slot' => ['required', Rule::in(MealPlan::SLOTS)],
            'servings' => ['required', 'integer', 'between:1,20'],
        ]);

        $mealPlan->update($validated);

        return back()->with('status', 'Rencana makan diperbarui.');
    }

    public function destroy(MealPlan $mealPlan): RedirectResponse
    {
        abort_unless($mealPlan->user_id === Auth::id(), 404);

        $mealPlan->delete();

        return back()->with('status', 'Rencana makan dihapus.');
    }

    public function clearWeek(Request $request): RedirectResponse
    {
        $weekStart = $request->date('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        Auth::user()->mealPlans()
            ->whereBetween('planned_for', [$weekStart->toDateString(), $weekStart->copy()->addDays(6)->toDateString()])
            ->delete();

        return back()->with('status', 'Rencana minggu ini dikosongkan.');
    }
}