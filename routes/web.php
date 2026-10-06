<?php

use App\Http\Controllers\DoaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalShalatController;
use App\Http\Controllers\QuranController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('beranda');

Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
Route::get('/quran/{nomor}', [QuranController::class, 'show'])
    ->whereNumber('nomor')
    ->name('quran.show');

Route::get('/doa', [DoaController::class, 'index'])->name('doa.index');
Route::get('/doa/{id}', [DoaController::class, 'show'])
    ->whereNumber('id')
    ->name('doa.show');

Route::get('/jadwal-shalat', [JadwalShalatController::class, 'index'])->name('jadwal.index');
Route::post('/jadwal-shalat/kabkota', [JadwalShalatController::class, 'getKabkota'])->name('jadwal.kabkota');
Route::post('/jadwal-shalat', [JadwalShalatController::class, 'getJadwal'])->name('jadwal.get');
