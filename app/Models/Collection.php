<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    /** @use HasFactory<\Database\Factories\CollectionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'accent',
    ];

    protected static function booted(): void
    {
        static::creating(function (Collection $collection): void {
            $collection->slug = static::uniqueSlug($collection->name, $collection->user_id);
        });
    }

    public static function uniqueSlug(string $name, int $userId): string
    {
        $base = Str::slug($name) ?: 'koleksi';
        $slug = $base;
        $suffix = 2;

        while (static::where('user_id', $userId)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)
            ->withTimestamps()
            ->orderByDesc('collection_recipe.created_at');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}