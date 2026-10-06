<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class QuranControllerTest extends TestCase
{
    public function test_quran_index_renders_api_surahs_and_shared_footer(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/surat' => Http::response([
                'data' => [[
                    'nomor' => 1,
                    'nama' => 'الفاتحة',
                    'namaLatin' => 'Al-Fatihah',
                    'arti' => 'Pembukaan',
                    'jumlahAyat' => 7,
                ]],
            ]),
        ]);

        $this->get(route('quran.index'))
            ->assertOk()
            ->assertSee('Al-Fatihah')
            ->assertSee('Kumpulan Doa');
    }

    public function test_quran_detail_renders_arabic_translation_and_audio(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/surat/1' => Http::response([
                'data' => [
                    'nomor' => 1,
                    'nama' => 'الفاتحة',
                    'namaLatin' => 'Al-Fatihah',
                    'arti' => 'Pembukaan',
                    'tempatTurun' => 'Mekah',
                    'jumlahAyat' => 1,
                    'audioFull' => ['01' => 'https://cdn.equran.id/full.mp3'],
                    'ayat' => [[
                        'nomorAyat' => 1,
                        'teksArab' => 'بِسْمِ اللّٰهِ',
                        'teksLatin' => 'Bismillah',
                        'teksIndonesia' => 'Dengan nama Allah.',
                        'audio' => ['01' => 'https://cdn.equran.id/ayah.mp3'],
                    ]],
                ],
            ]),
        ]);

        $this->get(route('quran.show', 1))
            ->assertOk()
            ->assertSee('بِسْمِ اللّٰهِ')
            ->assertSee('Dengan nama Allah.')
            ->assertSee('https://cdn.equran.id/ayah.mp3');
    }

    public function test_quran_detail_outside_surah_range_returns_404_without_api_request(): void
    {
        Http::preventStrayRequests();

        $this->get(route('quran.show', 115))->assertNotFound();
    }
}
