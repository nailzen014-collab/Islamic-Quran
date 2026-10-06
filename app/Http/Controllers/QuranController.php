<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class QuranController extends Controller
{
    public function index(Request $request): View
    {
        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/surat')
            ->throw();

        $surahs = collect($response->json('data'));
        $perPage = 12;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $quran = new LengthAwarePaginator(
            $surahs->forPage($page, $perPage)->values(),
            $surahs->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );

        return view('quran', [
            'quran' => $quran,
        ]);
    }

    public function show(Request $request, int $nomor): View
    {
        abort_unless($nomor >= 1 && $nomor <= 114, 404);

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/surat/'.$nomor)
            ->throw();
        $quran = $response->json('data');
        $ayahs = collect($quran['ayat']);
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $quran['ayat'] = new LengthAwarePaginator(
            $ayahs->forPage($page, $perPage)->values(),
            $ayahs->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );

        return view('detail', [
            'quran' => $quran,
            'previousSurah' => $nomor > 1 ? $nomor - 1 : null,
            'nextSurah' => $nomor < 114 ? $nomor + 1 : null,
        ]);
    }
}
