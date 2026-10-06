<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class QuranControllerTest extends TestCase
{
    public function test_quran_index_renders_api_surahs_and_shared_footer(): void
    {
        $surahs = collect(range(1, 13))
            ->map(fn (int $number): array => [
                'nomor' => $number,
                'nama' => 'سورة',
                'namaLatin' => 'Surat '.$number,
                'arti' => 'Arti '.$number,
                'jumlahAyat' => 7,
            ])
            ->all();

        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/surat' => Http::response([
                'data' => $surahs,
            ]),
        ]);

        $this->get(route('quran.index'))
            ->assertOk()
            ->assertSee('Surat 1')
            ->assertDontSee('Surat 13')
            ->assertSee('Halaman 1 dari 2')
            ->assertSee('Kumpulan Doa');

        $this->get(route('quran.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Surat 13')
            ->assertSee('Halaman 2 dari 2');
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

    public function test_quran_detail_paginates_ayahs_and_keeps_surah_navigation(): void
    {
        $ayahs = collect(range(1, 12))
            ->map(fn (int $number): array => [
                'nomorAyat' => $number,
                'teksArab' => 'آية '.$number,
                'teksLatin' => 'Ayah '.$number,
                'teksIndonesia' => 'Terjemahan ayat '.$number,
                'audio' => ['01' => 'https://cdn.equran.id/'.$number.'.mp3'],
            ])
            ->all();

        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/surat/2' => Http::response([
                'data' => [
                    'nomor' => 2,
                    'nama' => 'البقرة',
                    'namaLatin' => 'Al-Baqarah',
                    'arti' => 'Sapi Betina',
                    'tempatTurun' => 'Madinah',
                    'jumlahAyat' => 12,
                    'audioFull' => ['01' => 'https://cdn.equran.id/full.mp3'],
                    'ayat' => $ayahs,
                ],
            ]),
        ]);

        $this->get(route('quran.show', ['nomor' => 2, 'page' => 2]))
            ->assertOk()
            ->assertSee('Ayat 11')
            ->assertSee('Ayat 12')
            ->assertDontSee('Terjemahan ayat 1</p>')
            ->assertSee('Halaman 2 dari 2')
            ->assertSee(route('quran.show', 1), false)
            ->assertSee(route('quran.show', 3), false);
    }

    public function test_quran_detail_outside_surah_range_returns_404_without_api_request(): void
    {
        Http::preventStrayRequests();

        $this->get(route('quran.show', 115))->assertNotFound();
    }
}
