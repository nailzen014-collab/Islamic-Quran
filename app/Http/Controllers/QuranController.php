<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class QuranController extends Controller
{
    public function index(): View
    {
        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/surat')
            ->throw();

        return view('quran', [
            'quran' => $response->json('data'),
        ]);
    }

    public function show(int $nomor): View
    {
        abort_unless($nomor >= 1 && $nomor <= 114, 404);

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/surat/'.$nomor)
            ->throw();

        return view('detail', [
            'quran' => $response->json('data'),
        ]);
    }
}
