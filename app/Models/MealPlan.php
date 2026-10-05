<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlan extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    public const SLOTS = ['Sarapan', 'Makan Siang', 'Makan Malam'];

    protected $fillable = [
        'user_id',
        'recipe_id',
        'planned_for',
        'slot',
        'servings',
    ];

    protected function casts(): array
    {
        return [
            'planned_for' => 'date',
            'servings' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
