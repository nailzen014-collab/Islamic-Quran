<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    protected $fillable = [
        'external_id',
        'slug',
        'name',
        'summary',
        'image',
        'cuisine',
        'difficulty',
        'prep_time_minutes',
        'cook_time_minutes',
        'servings',
        'calories_per_serving',
        'rating',
        'review_count',
        'meal_types',
        'tags',
        'ingredients',
        'instructions',
    ];

    protected function casts(): array
    {
        return [
            'meal_types' => 'array',
            'tags' => 'array',
            'ingredients' => 'array',
            'instructions' => 'array',
            'prep_time_minutes' => 'integer',
            'cook_time_minutes' => 'integer',
            'servings' => 'integer',
            'calories_per_serving' => 'integer',
            'review_count' => 'integer',
            'rating' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Recipe $recipe): void {
            $recipe->slug = static::uniqueSlug($recipe->name, $recipe->external_id);
        });
    }

    /**
     * Build a slug that stays unique even when two recipes share a name.
     */
    public static function uniqueSlug(string $name, ?int $externalId = null): string
    {
        $base = Str::slug($name) ?: 'resep';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function totalMinutes(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->prep_time_minutes + $this->cook_time_minutes,
        );
    }

    /**
     * A short teaser, derived from the first instruction when none was imported.
     */
    public function getTeaserAttribute(): ?string
    {
        if (filled($this->summary)) {
            return $this->summary;
        }

        $first = collect($this->instructions ?? [])->first();

        return $first ? Str::limit($first, 150) : null;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.str_replace('%', '\%', $term).'%';

        return $query->where(function (Builder $builder) use ($like): void {
            $builder->where('name', 'like', $like)
                ->orWhere('cuisine', 'like', $like)
                ->orWhere('summary', 'like', $like);
        });
    }

    /**
     * @param  array<int, string>|string|null  $value
     */
    public function scopeWhereListContains(Builder $query, string $column, array|string|null $value): Builder
    {
        $values = array_filter((array) $value);

        if ($values === []) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($column, $values): void {
            foreach ($values as $item) {
                $builder->orWhere($column, 'like', '%"'.$item.'"%');
            }
        });
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'rating' => $query->orderByDesc('rating')->orderByDesc('review_count'),
            'fastest' => $query->orderByRaw('prep_time_minutes + cook_time_minutes asc'),
            'calories' => $query->orderBy('calories_per_serving'),
            'az' => $query->orderBy('name'),
            default => $query->orderByDesc('rating')->orderByDesc('review_count'),
        };
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Every tag used by any recipe, most used first.
     *
     * @return Collection<int, string>
     */
    public static function allTags(): Collection
    {
        return static::query()
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    public static function allCuisines(): Collection
    {
        return static::query()
            ->whereNotNull('cuisine')
            ->distinct()
            ->orderBy('cuisine')
            ->pluck('cuisine')
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    public static function allMealTypes(): Collection
    {
        return static::query()
            ->pluck('meal_types')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    public static function difficulties(): Collection
    {
        return collect(['Easy', 'Medium', 'Hard']);
    }

    public function isFavoritedBy(?User $user): bool
    {
        return $user !== null && $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}