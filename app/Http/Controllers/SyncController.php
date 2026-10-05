<?php

namespace App\Http\Controllers;

use App\Services\RecipeImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class SyncController extends Controller
{
    /**
     * Re-import the catalogue from the public API.
     */
    public function store(RecipeImporter $importer): RedirectResponse
    {
        try {
            $count = $importer->sync();
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'sync' => 'Gagal mengambil data: '.$exception->getMessage(),
            ]);
        }

        return back()->with('status', "{$count} resep berhasil disegarkan dari DummyJSON.");
    }
}
