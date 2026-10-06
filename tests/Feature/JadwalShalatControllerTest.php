<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JadwalShalatControllerTest extends TestCase
{
    public function test_jadwal_page_loads_provinces_from_api(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/shalat/provinsi' => Http::response([
                'data' => ['Bali'],
            ]),
        ]);

        $this->get(route('jadwal.index'))
            ->assertOk()
            ->assertSee('Jadwal Shalat Indonesia')
            ->assertSee('Bali');
    }

    public function test_jadwal_request_requires_a_province_and_city(): void
    {
        Http::preventStrayRequests();

        $this->from(route('jadwal.index'))
            ->post(route('jadwal.get'), [])
            ->assertRedirect(route('jadwal.index'))
            ->assertSessionHasErrors(['provinsi', 'kabkota', 'bulan', 'tahun']);
    }

    public function test_jadwal_request_renders_monthly_api_schedule(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'equran.id/api/v2/shalat/provinsi' => Http::response([
                'data' => ['Bali'],
            ]),
            'equran.id/api/v2/shalat/kabkota' => Http::response([
                'data' => ['Denpasar'],
            ]),
            'equran.id/api/v2/shalat' => Http::response([
                'data' => [
                    'lokasi' => 'KOTA DENPASAR',
                    'daerah' => 'BALI',
                    'jadwal' => [[
                        'tanggal' => '1 Oktober 2026',
                        'imsak' => '04:30',
                        'subuh' => '04:40',
                        'terbit' => '05:55',
                        'dhuha' => '06:20',
                        'dzuhur' => '12:10',
                        'ashar' => '15:20',
                        'maghrib' => '18:15',
                        'isya' => '19:25',
                    ]],
                ],
            ]),
        ]);

        $this->post(route('jadwal.get'), [
            'provinsi' => 'Bali',
            'kabkota' => 'Denpasar',
            'bulan' => 10,
            'tahun' => 2026,
        ])
            ->assertOk()
            ->assertSee('KOTA DENPASAR')
            ->assertSee('04:30');
    }
}
