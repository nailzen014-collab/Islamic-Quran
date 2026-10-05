<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::resource('/', QuoteController::class);
Route::resource('/food', FoodController::class);
Route::get('/produk/1', function () {
    return response()->json([
        "id" => 1,
        "nama" => "Buku",
        "harga" => 20000,
        "kategori" => "Alat Tulis",
        "tersedia" => true
    ]);
});

Route::get('/produk/2', function () {
    return response()->json([
        "id" => 2,
        "nama" => "Pensil",
        "harga" => 5000,
        "kategori" => "Alat Tulis",
        "tersedia" => true
    ]);
});


Route::get('/produk/3', function () {
    return response()->json([
        "id" => 3,
        "nama" => "Penghapus",
        "harga" => 3000,
        "kategori" => "Alat Tulis",
        "tersedia" => true
    ]);
});

Route::get('/produk/4', function () {
    return response()->json([
        "id" => 4,
        "nama" => "Buku",
        "harga" => 20000,
        "kategori" => "Alat Tulis",
        "tersedia" => false
    ]);
});

Route::get('/produk/5', function () {
    return response()->json([
        "id" => 5,
        "nama" => "Penggaris",
        "harga" => 10000,
        "kategori" => "Alat Tulis",
        "tersedia" => true
    ]);
});

Route::get('/produk', function () {
    return response()->json([
        [
            "id" => 1,
            "nama" => "Buku",
            "harga" => 20000,
            "kategori" => "Alat Tulis",
            "tersedia" => true
        ],
        [
            "id" => 2,
            "nama" => "Pensil",
            "harga" => 5000,
            "kategori" => "Alat Tulis",
            "tersedia" => true
        ],
        [
            "id" => 3,
            "nama" => "Penghapus",
            "harga" => 3000,
            "kategori" => "Alat Tulis",
            "tersedia" => true
        ],
        [
            "id" => 4,
            "nama" => "Buku",
            "harga" => 20000,
            "kategori" => "Alat Tulis",
            "tersedia" => false
        ],
        [
            "id" => 5,
            "nama" => "Penggaris",
            "harga" => 10000,
            "kategori" => "Alat Tulis",
            "tersedia" => true
        ]
    ]);
});
ROute::get('quotes', [App\Http\Controllers\QuoteController::class, 'index']);