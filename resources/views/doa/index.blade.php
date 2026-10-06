@extends('layouts.app')

@section('title', 'Kumpulan Doa')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="section-eyebrow">Kumpulan Doa Harian</p>
                <h1 class="display-title mt-2 text-3xl sm:text-4xl">Doa Sehari-hari</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                    Berdasarkan API equran.id, total {{ number_format(count($doa)) }} doa.
                </p>
            </div>
            <form action="{{ route('doa.index') }}" method="GET" class="w-full sm:w-auto">
                <label class="search-field">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0 text-slate-400" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="search" name="q" value="{{ $query }}" placeholder="Cari nama doa, grup, atau terjemah..." class="min-w-0 flex-1 bg-transparent text-sm outline-none">
                </label>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($doa as $item)
                <a href="{{ route('doa.show', $item['id']) }}" class="surface block p-5 transition hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="text-xs text-slate-500">{{ $item['grup'] ?? '-' }}</span>
                            <h2 class="display-title mt-1 text-lg">{{ $item['nama'] }}</h2>
                        </div>
                        <span class="badge">#{{ $item['id'] }}</span>
                    </div>
                    @if (!empty($item['idn']))
                        <p class="mt-3 line-clamp-3 text-sm text-slate-600 dark:text-slate-400">{{ $item['idn'] }}</p>
                    @endif
                    <div class="mt-4 text-sm font-medium text-emerald-600">Lihat detail →</div>
                </a>
            @empty
                <div class="surface col-span-full p-8 text-center text-sm text-slate-500">
                    Tidak ditemukan doa dengan kata kunci "{{ $query }}"
                </div>
            @endforelse
        </div>
    </section>
@endsection
