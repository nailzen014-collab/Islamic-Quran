<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category', 64)->default('Lainnya');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->boolean('is_checked')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_checked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_items');
    }
};
