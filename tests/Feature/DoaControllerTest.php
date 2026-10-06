<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DoaControllerTest extends TestCase
{
    public function test_doa_list_search_filters_api_results(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/doa' => Http::response([
                'data' => [
                    ['id' => 1, 'nama' => 'Doa Sebelum Tidur', 'grup' => 'Tidur', 'ar' => 'دُعَاء', 'tr' => 'Dua', 'idn' => 'Permohonan sebelum tidur'],
                    ['id' => 2, 'nama' => 'Doa Makan', 'grup' => 'Makan', 'ar' => 'دُعَاء', 'tr' => 'Dua', 'idn' => 'Permohonan sebelum makan'],
                ],
            ]),
        ]);

        $this->get(route('doa.index', ['q' => 'tidur']))
            ->assertOk()
            ->assertSee('Doa Sebelum Tidur')
            ->assertDontSee('Doa Makan');
    }

    public function test_doa_pagination_preserves_search_query(): void
    {
        $items = collect(range(1, 13))
            ->map(fn (int $id): array => [
                'id' => $id,
                'nama' => 'Doa tidur '.$id,
                'grup' => 'Tidur',
                'ar' => 'دُعَاء',
                'tr' => 'Dua',
                'idn' => 'Doa untuk tidur',
            ])
            ->all();

        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/doa' => Http::response(['data' => $items]),
        ]);

        $this->get(route('doa.index', ['q' => 'tidur']))
            ->assertOk()
            ->assertSee('Halaman 1 dari 2')
            ->assertSee('q=tidur')
            ->assertDontSee('Doa tidur 13');

        $this->get(route('doa.index', ['q' => 'tidur', 'page' => 2]))
            ->assertOk()
            ->assertSee('Doa tidur 13')
            ->assertSee('Halaman 2 dari 2');
    }

    public function test_doa_detail_renders_arabic_latin_and_translation(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/doa/1' => Http::response([
                'data' => [
                    'id' => 1,
                    'nama' => 'Doa Sebelum Tidur',
                    'grup' => 'Doa Harian',
                    'ar' => 'بِاسْمِكَ رَبِّيْ',
                    'tr' => 'Bismika robbii',
                    'idn' => 'Dengan nama-Mu, Tuhanku.',
                    'tentang' => 'Riwayat hadis.',
                    'tag' => ['tidur'],
                ],
            ]),
            'equran.id/api/doa' => Http::response([
                'data' => [
                    ['id' => 1],
                    ['id' => 2],
                    ['id' => 3],
                ],
            ]),
        ]);

        $this->get(route('doa.show', 1))
            ->assertOk()
            ->assertSee('بِاسْمِكَ رَبِّيْ')
            ->assertSee('Bismika robbii')
            ->assertSee('Dengan nama-Mu, Tuhanku.')
            ->assertSee(route('doa.show', 2), false)
            ->assertDontSee(route('doa.show', 0), false);
    }
}
