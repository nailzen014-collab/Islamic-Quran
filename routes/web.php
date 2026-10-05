<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeApiController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('beranda');

Route::get('/jelajah', [ExploreController::class, 'index'])->name('jelajah');
Route::get('/jelajah/kartu', [ExploreController::class, 'cards'])->name('jelajah.kartu');
Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori');
Route::get('/cari', SearchController::class)->name('cari');

Route::get('/resep/{slug}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/resep/{slug}/masak', [RecipeController::class, 'cook'])->name('recipes.cook');

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.store');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::post('/resep/{recipe}/favorit', [FavoriteController::class, 'store'])->name('favorites.store');

    Route::post('/resep/{recipe}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/ulasan/{review}/dimasak', [ReviewController::class, 'cooked'])->name('reviews.cooked');

    Route::get('/koleksi', [CollectionController::class, 'index'])->name('koleksi.index');
    Route::get('/koleksi/baru', [CollectionController::class, 'create'])->name('koleksi.create');
    Route::post('/koleksi', [CollectionController::class, 'store'])->name('koleksi.store');
    Route::get('/koleksi/{collection}', [CollectionController::class, 'show'])->name('koleksi.show');
    Route::put('/koleksi/{collection}', [CollectionController::class, 'update'])->name('koleksi.update');
    Route::delete('/koleksi/{collection}', [CollectionController::class, 'destroy'])->name('koleksi.destroy');
    Route::post('/koleksi/{collection}/resep', [CollectionController::class, 'toggleRecipe'])->name('koleksi.resep');

    Route::get('/belanja', [ShoppingListController::class, 'index'])->name('belanja');
    Route::post('/belanja', [ShoppingListController::class, 'store'])->name('belanja.store');
    Route::put('/belanja/{shoppingItem}', [ShoppingListController::class, 'update'])->name('belanja.update');
    Route::delete('/belanja/{shoppingItem}', [ShoppingListController::class, 'destroy'])->name('belanja.destroy');
    Route::post('/belanja/bersihkan', [ShoppingListController::class, 'clearChecked'])->name('belanja.bersihkan');
    Route::post('/belanja/impor', [ShoppingListController::class, 'importFromRecipes'])->name('belanja.impor');
    Route::post('/belanja/{shoppingItem}/centang', [ShoppingListController::class, 'toggle'])->name('belanja.centang');
});

Route::post('/sinkronisasi', [SyncController::class, 'store'])->name('sync');
Route::get('/statistik', StatsController::class)->name('statistik');

Route::prefix('api/resep')->name('api.resep.')->group(function (): void {
    Route::get('/', [RecipeApiController::class, 'index'])->name('index');
    Route::get('/{slug}', [RecipeApiController::class, 'show'])->name('show');
});
