<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar_color', 'bio', 'kitchen_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn (): string => collect(explode(' ', trim($this->name)))
                ->filter()
                ->take(2)
                ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode(''),
        );
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class)->latest();
    }

    public function favoritedRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'favorites')
            ->withTimestamps()
            ->orderByDesc('favorites.created_at');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class)->orderBy('name');
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class)->orderBy('planned_for')->orderBy('slot');
    }

    public function shoppingItems(): HasMany
    {
        return $this->hasMany(ShoppingItem::class)->orderBy('category')->orderBy('name');
    }
}