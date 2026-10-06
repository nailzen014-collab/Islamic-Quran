<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $totalDoa = 0;
        $totalSurah = 0;

        try {
            $doaRes = Http::timeout(5)->get('https://equran.id/api/doa');
            $totalDoa = count($doaRes->json('data') ?? []);
        } catch (\Throwable $e) {
            $totalDoa = 0;
        }

        try {
            $surahRes = Http::timeout(5)->get('https://equran.id/api/v2/surat');
            $totalSurah = count($surahRes->json('data') ?? []);
        } catch (\Throwable $e) {
            $totalSurah = 0;
        }

        return view('home', [
            'totalDoa' => $totalDoa,
            'totalSurah' => $totalSurah,
            'totalAyat' => 6236,
        ]);
    }
}
