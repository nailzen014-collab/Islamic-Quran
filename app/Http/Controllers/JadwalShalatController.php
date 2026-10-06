<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class JadwalShalatController extends Controller
{
    public function index(Request $request): View
    {
        if (
            $request->query->has('provinsi')
            && $request->query->has('kabkota')
            && $request->query->has('bulan')
            && $request->query->has('tahun')
        ) {
            return $this->getJadwal($request);
        }

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/shalat/provinsi')
            ->throw();
        $provinsi = $response->json('data');

        return view('jadwal-shalat.index', [
            'provinsi' => $provinsi,
            'kabkota' => [],
            'jadwal' => null,
            'selected' => [
                'provinsi' => null,
                'kabkota' => null,
                'bulan' => now()->month,
                'tahun' => now()->year,
            ],
        ]);
    }

    public function getKabkota(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'provinsi' => ['required', 'string', 'max:100'],
        ]);

        $response = Http::acceptJson()
            ->connectTimeout(3)
            ->timeout(10)
            ->post('https://equran.id/api/v2/shalat/kabkota', [
                'provinsi' => $validated['provinsi'],
            ])
            ->throw();

        return response()->json($response->json());
    }

    public function getJadwal(Request $request): View
    {
        $validated = $request->validate([
            'provinsi' => ['required', 'string', 'max:100'],
            'kabkota' => ['required', 'string', 'max:100'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2035'],
        ]);

        $provinsi = $validated['provinsi'];
        $kabkota = $validated['kabkota'];
        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];

        $provList = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/v2/shalat/provinsi')
            ->throw()
            ->json('data');

        $kabList = Http::acceptJson()
            ->connectTimeout(3)
            ->timeout(10)
            ->post('https://equran.id/api/v2/shalat/kabkota', [
                'provinsi' => $provinsi,
            ])
            ->throw()
            ->json('data');

        $jadwalRes = Http::acceptJson()
            ->connectTimeout(3)
            ->timeout(10)
            ->post('https://equran.id/api/v2/shalat', [
                'provinsi' => $provinsi,
                'kabkota' => $kabkota,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ])
            ->throw();

        $jadwal = $jadwalRes->json('data');
        $jadwalItems = collect($jadwal['jadwal'] ?? []);
        $perPage = 7;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $jadwal['jadwal'] = new LengthAwarePaginator(
            $jadwalItems->forPage($page, $perPage)->values(),
            $jadwalItems->count(),
            $perPage,
            $page,
            [
                'path' => url('/jadwal-shalat'),
                'query' => [
                    'provinsi' => $provinsi,
                    'kabkota' => $kabkota,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
            ],
        );

        return view('jadwal-shalat.index', [
            'provinsi' => $provList,
            'kabkota' => $kabList,
            'jadwal' => $jadwal,
            'selected' => [
                'provinsi' => $provinsi,
                'kabkota' => $kabkota,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
        ]);
    }
}
