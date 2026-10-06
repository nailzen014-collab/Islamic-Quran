<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DoaController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/doa')
            ->throw();

        $query = trim($validated['q'] ?? '');
        $doa = collect($response->json('data'))
            ->filter(function (array $item) use ($query): bool {
                if ($query === '') {
                    return true;
                }

                $searchableText = implode(' ', [
                    $item['nama'] ?? '',
                    $item['grup'] ?? '',
                    $item['ar'] ?? '',
                    $item['tr'] ?? '',
                    $item['idn'] ?? '',
                ]);

                return Str::contains(Str::lower($searchableText), Str::lower($query));
            })
            ->values();

        return view('doa.index', [
            'doa' => $doa,
            'query' => $query,
        ]);
    }

    public function show(int $id): View
    {
        abort_if($id < 1, 404);

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get('https://equran.id/api/doa/'.$id)
            ->throw();

        return view('doa.show', [
            'item' => $response->json('data'),
        ]);
    }
}
