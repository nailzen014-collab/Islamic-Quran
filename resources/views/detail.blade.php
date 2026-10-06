@extends('layouts.app')

@section('title', $quran['namaLatin'])

@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 py-10 sm:px-6 sm:py-14">
        <a href="{{ route('quran.index') }}" class="text-sm text-slate-500 transition hover:text-emerald-600">← Kembali ke daftar surat</a>

        <header class="surface-strong mt-6 p-5 text-center sm:p-10">
            <p class="section-eyebrow justify-center">Surat {{ $quran['nomor'] }}</p>
            <h1 class="display-title mt-3 text-2xl sm:text-4xl">{{ $quran['namaLatin'] }}</h1>
            <p class="mt-2 text-xs text-slate-500 sm:text-sm dark:text-slate-400">
                {{ $quran['arti'] }} · {{ $quran['tempatTurun'] }} · {{ $quran['jumlahAyat'] }} ayat
            </p>
            <p lang="ar" dir="rtl" class="mt-5 font-display text-3xl text-emerald-800 sm:text-4xl dark:text-emerald-200">{{ $quran['nama'] }}</p>
            @if (!empty($quran['audioFull']['01']))
                <audio class="mx-auto mt-6 w-full max-w-md" controls preload="none">
                    <source src="{{ $quran['audioFull']['01'] }}" type="audio/mpeg">
                    Browser Anda tidak mendukung pemutar audio.
                </audio>
            @endif
        </header>

        <div class="mt-4 flex justify-center sm:mt-6">
            @include('partials.mobile-pagination', ['paginator' => $quran['ayat']])
        </div>

        <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
            Menampilkan ayat {{ $quran['ayat']->firstItem() }}–{{ $quran['ayat']->lastItem() }} dari {{ $quran['ayat']->total() }}
        </p>

        <div class="mt-4 grid gap-3 sm:mt-6 sm:gap-4">
            @foreach ($quran['ayat'] as $ayat)
                <article class="surface p-4 sm:p-7">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="badge">Ayat {{ $ayat['nomorAyat'] }}</span>
                        @if (!empty($ayat['audio']['01']))
                            <audio controls preload="none" class="h-10 max-w-48">
                                <source src="{{ $ayat['audio']['01'] }}" type="audio/mpeg">
                                Browser Anda tidak mendukung pemutar audio.
                            </audio>
                        @endif
                    </div>
                    <p lang="ar" dir="rtl" class="mt-6 text-right font-display text-3xl leading-[2.2] text-slate-900 dark:text-white sm:text-4xl">
                        {{ $ayat['teksArab'] }}
                    </p>
                    @if (!empty($ayat['teksLatin']))
                        <p class="mt-5 text-sm italic leading-relaxed text-emerald-700 dark:text-emerald-300">{{ $ayat['teksLatin'] }}</p>
                    @endif
                    @if (!empty($ayat['teksIndonesia']))
                        <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $ayat['teksIndonesia'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="mt-5 flex justify-center">
            @include('partials.mobile-pagination', ['paginator' => $quran['ayat']])
        </div>

        <nav class="mt-8 grid grid-cols-2 gap-3 border-t border-black/5 pt-6 dark:border-white/10" aria-label="Navigasi surat">
            @if ($previousSurah)
                <a href="{{ route('quran.show', $previousSurah) }}" class="surface px-3 py-3 text-sm transition hover:text-emerald-600 sm:px-4">
                    <span class="block text-xs text-slate-500">← Surat sebelumnya</span>
                    <span class="mt-1 block truncate font-medium">Surat {{ $previousSurah }}</span>
                </a>
            @else
                <span></span>
            @endif
            @if ($nextSurah)
                <a href="{{ route('quran.show', $nextSurah) }}" class="surface px-3 py-3 text-right text-sm transition hover:text-emerald-600 sm:px-4">
                    <span class="block text-xs text-slate-500">Surat berikutnya →</span>
                    <span class="mt-1 block truncate font-medium">Surat {{ $nextSurah }}</span>
                </a>
            @endif
        </nav>
    </section>
@endsection
