<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('external_id')->unique();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('summary')->nullable();
            $table->string('image')->nullable();
            $table->string('cuisine')->nullable()->index();
            $table->string('difficulty')->nullable()->index();
            $table->unsignedSmallInteger('prep_time_minutes')->default(0);
            $table->unsignedSmallInteger('cook_time_minutes')->default(0);
            $table->unsignedSmallInteger('servings')->default(1);
            $table->unsignedSmallInteger('calories_per_serving')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->json('meal_types')->nullable();
            $table->json('tags')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('instructions')->nullable();
            $table->timestamps();

            $table->index(['difficulty', 'cuisine']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
