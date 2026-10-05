<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->date('planned_for');
            $table->string('slot', 16);
            $table->unsignedSmallInteger('servings')->default(2);
            $table->timestamps();

            $table->unique(['user_id', 'recipe_id', 'planned_for', 'slot'], 'meal_plans_unique_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};
