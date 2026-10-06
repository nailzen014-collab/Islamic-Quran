@extends('layouts.app')

@section('title', "Al-Qur'an")

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="mb-6 sm:mb-8">
            <p class="section-eyebrow">Al-Qur'an Digital</p>
            <h1 class="display-title mt-2 text-3xl sm:text-4xl">Daftar Surat</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Pilih surat untuk membaca ayat, terjemahan, dan mendengarkan lantunan audio.
            </p>
        </div>

        <p class="mb-4 text-xs text-slate-500 dark:text-slate-400">
            Menampilkan {{ $quran->firstItem() }}–{{ $quran->lastItem() }} dari {{ $quran->total() }} surat
        </p>
        <div class="grid gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
            @foreach ($quran as $surat)
                <a href="{{ route('quran.show', $surat['nomor']) }}" class="surface group flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                    <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-emerald-500/10 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                        {{ $surat['nomor'] }}
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate font-semibold">{{ $surat['namaLatin'] }}</span>
                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                            {{ $surat['arti'] }} · {{ $surat['jumlahAyat'] }} ayat
                        </span>
                    </span>
                    <span lang="ar" dir="rtl" class="font-display text-2xl text-emerald-800 dark:text-emerald-200">{{ $surat['nama'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-7 flex justify-center sm:mt-9">
            @include('partials.mobile-pagination', ['paginator' => $quran])
        </div>
    </section>
@endsection
