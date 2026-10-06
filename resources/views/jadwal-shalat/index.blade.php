@extends('layouts.app')

@section('title', 'Jadwal Shalat')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="mb-8">
            <p class="section-eyebrow">Jadwal Shalat</p>
            <h1 class="display-title mt-2 text-3xl sm:text-4xl">Jadwal Shalat Indonesia</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                Data dari API equran.id. Pilih provinsi dan kabupaten/kota untuk melihat jadwal shalat per bulan.
            </p>
        </div>

        <div class="surface p-4 sm:p-6">
            <form action="{{ route('jadwal.get') }}" method="POST" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium">Provinsi</label>
                    <select name="provinsi" class="min-h-12 w-full rounded-xl border border-black/10 bg-white px-3 py-3 text-base sm:text-sm dark:border-white/15 dark:bg-slate-900" required onchange="loadKabkota(this.value)">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach ($provinsi as $p)
                            <option value="{{ $p }}" {{ $selected['provinsi'] === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Kabupaten/Kota</label>
                    <select name="kabkota" id="kabkota" class="min-h-12 w-full rounded-xl border border-black/10 bg-white px-3 py-3 text-base sm:text-sm disabled:cursor-not-allowed disabled:opacity-60 dark:border-white/15 dark:bg-slate-900" required {{ empty($kabkota) ? 'disabled' : '' }}>
                        <option value="">{{ empty($selected['provinsi']) ? 'Pilih provinsi terlebih dahulu' : '-- Pilih Kab/Kota --' }}</option>
                        @foreach ($kabkota as $k)
                            <option value="{{ $k }}" {{ $selected['kabkota'] === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    <p id="kabkota-hint" class="mt-2 text-xs text-slate-500 dark:text-slate-400" aria-live="polite">
                        {{ empty($selected['provinsi']) ? 'Pilih provinsi agar daftar kota dapat dimuat.' : '' }}
                    </p>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Bulan</label>
                    <select name="bulan" class="min-h-12 w-full rounded-xl border border-black/10 bg-white px-3 py-3 text-base sm:text-sm dark:border-white/15 dark:bg-slate-900">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (int)$selected['bulan'] === $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(0, $i, 1)->locale('id')->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Tahun</label>
                    <input type="number" name="tahun" value="{{ $selected['tahun'] }}" class="min-h-12 w-full rounded-xl border border-black/10 bg-white px-3 py-3 text-base sm:text-sm dark:border-white/15 dark:bg-slate-900" min="2020" max="2035">
                </div>
                <div class="flex items-end sm:col-span-2 lg:col-span-1">
                    <button type="submit" class="btn-primary w-full">Tampilkan Jadwal</button>
                </div>
            </form>
            <p id="kabkota-error" class="mt-3 text-sm text-rose-600 dark:text-rose-400" role="alert" aria-live="polite"></p>
            <button id="kabkota-retry" type="button" class="mt-2 hidden text-sm font-medium text-emerald-700 underline underline-offset-4 dark:text-emerald-300" onclick="loadKabkota(document.querySelector('[name=provinsi]').value)">
                Coba muat kota lagi
            </button>
        </div>

        @if ($jadwal)
            <div class="mt-8 surface overflow-hidden">
                <div class="mb-4 px-6 pt-6">
                    <h2 class="text-lg font-semibold">{{ $jadwal['lokasi'] ?? ($selected['kabkota'] . ', ' . $selected['provinsi']) }}</h2>
                    <p class="text-sm text-slate-500">{{ $jadwal['daerah'] ?? '' }}</p>
                </div>
                <p class="px-4 pb-2 text-xs text-slate-500 sm:hidden">Geser tabel ke samping untuk melihat semua waktu shalat.</p>
                <div class="overflow-x-auto" role="region" aria-label="Tabel jadwal shalat" tabindex="0">
                <table class="w-full min-w-[800px] text-sm">
                    <thead>
                        <tr class="border-b border-black/5 text-left dark:border-white/10">
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Imsak</th>
                            <th class="px-6 py-3 font-medium">Subuh</th>
                            <th class="px-6 py-3 font-medium">Terbit</th>
                            <th class="px-6 py-3 font-medium">Dhuha</th>
                            <th class="px-6 py-3 font-medium">Dzuhur</th>
                            <th class="px-6 py-3 font-medium">Ashar</th>
                            <th class="px-6 py-3 font-medium">Maghrib</th>
                            <th class="px-6 py-3 font-medium">Isya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal['jadwal'] ?? [] as $j)
                            <tr class="border-b border-black/5 last:border-0 dark:border-white/10">
                                <td class="px-6 py-2">{{ $j['tanggal'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['imsak'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['subuh'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['terbit'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['dhuha'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['dzuhur'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['ashar'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['maghrib'] ?? '' }}</td>
                                <td class="px-6 py-2">{{ $j['isya'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endif
    </section>

    <script>
        async function loadKabkota(provinsi) {
            const select = document.getElementById('kabkota');
            const error = document.getElementById('kabkota-error');
            const hint = document.getElementById('kabkota-hint');
            const retry = document.getElementById('kabkota-retry');
            select.replaceChildren(new Option(provinsi ? 'Memuat kabupaten/kota...' : 'Pilih provinsi terlebih dahulu', ''));
            error.textContent = '';
            retry.classList.add('hidden');

            if (!provinsi) {
                select.disabled = true;
                hint.textContent = 'Pilih provinsi agar daftar kota dapat dimuat.';
                return;
            }

            select.disabled = true;
            hint.textContent = 'Sedang memuat daftar kabupaten/kota...';
            try {
                const response = await fetch('{{ route('jadwal.kabkota') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({provinsi})
                });

                if (!response.ok) {
                    throw new Error(`Request gagal (${response.status})`);
                }

                const result = await response.json();
                if (!Array.isArray(result.data)) {
                    throw new Error('Data kabupaten/kota tidak valid');
                }

                result.data.forEach((kabkota) => {
                    select.add(new Option(kabkota, kabkota));
                });
                select.options[0].textContent = result.data.length
                    ? '-- Pilih Kab/Kota --'
                    : 'Tidak ada kota tersedia';
                select.disabled = result.data.length === 0;
                hint.textContent = result.data.length
                    ? `${result.data.length} kabupaten/kota tersedia. Silakan pilih salah satu.`
                    : 'Belum ada kabupaten/kota untuk provinsi ini.';
            } catch (exception) {
                select.replaceChildren(new Option('Gagal memuat kabupaten/kota', ''));
                hint.textContent = '';
                error.textContent = 'Daftar kabupaten/kota gagal dimuat. Periksa koneksi lalu coba lagi.';
                retry.classList.remove('hidden');
                console.error('Gagal memuat kabupaten/kota:', exception);
            }
        }
    </script>
@endsection
