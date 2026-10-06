@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-7 pb-8 sm:px-6 sm:pt-16 sm:pb-4">
        <div class="grid min-w-0 items-center gap-8 sm:gap-12 lg:grid-cols-2">
            <div class="min-w-0">
                <p class="section-eyebrow">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Panduan Islam Praktis
                </p>
                <h1 class="display-title mt-4 max-w-2xl text-[2.15rem] leading-[1.08] text-slate-900 sm:mt-5 sm:text-5xl lg:text-6xl dark:text-white">
                    Al-Qur'an, Doa Harian &amp;
                    <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-sky-400 bg-clip-text text-transparent">Jadwal Shalat</span>
                </h1>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-600 sm:mt-5 sm:text-base dark:text-slate-400">
                    Akses Al-Qur'an, kumpulan doa harian, dan jadwal shalat berdasarkan provinsi &amp; kabupaten/kota langsung dari sumber terpercaya.
                </p>
                <div class="mt-6 grid grid-cols-2 gap-2 sm:mt-8 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
                    <a href="{{ route('quran.index') }}" class="btn-primary col-span-2 w-full sm:w-auto">
                        Baca Al-Qur'an
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('doa.index') }}" class="btn-ghost w-full sm:w-auto">Kumpulan Doa</a>
                    <a href="{{ route('jadwal.index') }}" class="btn-ghost w-full sm:w-auto">Jadwal Shalat</a>
                </div>
                <dl class="mt-7 grid max-w-lg grid-cols-3 gap-2 sm:mt-12 sm:gap-4">
                    @foreach ([
                        ['label' => 'Surat', 'value' => $totalSurah],
                        ['label' => 'Ayat', 'value' => $totalAyat],
                        ['label' => 'Doa', 'value' => $totalDoa],
                    ] as $stat)
                        <div class="surface rounded-2xl px-3 py-3 sm:border-0 sm:bg-transparent sm:p-0 sm:shadow-none">
                            <dt class="text-[11px] tracking-wider text-slate-500 uppercase">{{ $stat['label'] }}</dt>
                            <dd class="display-title mt-1 text-2xl text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($stat['value']) }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <div class="relative min-w-0">
                <div class="surface-strong relative overflow-hidden rounded-[1.5rem] p-1.5 sm:rounded-[2rem] sm:p-2">
                    <div class="relative overflow-hidden rounded-[1.15rem] bg-gradient-to-br from-emerald-900/90 via-slate-900 to-slate-950 sm:rounded-[1.5rem]">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.25),transparent_55%)]"></div>
                        <div class="relative px-5 py-7 text-white sm:px-10 sm:py-10">
                            <span class="badge badge-emerald">Islamic</span>
                            <h2 class="display-title mt-4 break-words text-2xl leading-tight sm:text-4xl">Bismillahirrahmanirrahim</h2>
                            <p class="mt-3 max-w-md text-sm text-white/80">
                                Semoga platform ini menjadi sarana kebaikan, menambah ilmu, dan mendekatkan diri kepada Allah ﷻ.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-2 text-xs text-white/80 sm:mt-8">
                                <a href="{{ route('quran.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur hover:bg-white/20">
                                    Al-Qur'an Digital
                                </a>
                                <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur hover:bg-white/20">
                                    Cek Jadwal Shalat
                                </a>
                                <a href="{{ route('doa.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur hover:bg-white/20">
                                    Doa Sehari-hari
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
