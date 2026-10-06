@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 pb-4 sm:px-6 sm:pt-16">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <p class="section-eyebrow">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Panduan Islam Praktis
                </p>
                <h1 class="display-title mt-5 text-4xl text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                    Al-Qur'an, Doa Harian &amp;
                    <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-sky-400 bg-clip-text text-transparent">Jadwal Shalat</span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600 dark:text-slate-400">
                    Akses Al-Qur'an, kumpulan doa harian, dan jadwal shalat berdasarkan provinsi &amp; kabupaten/kota langsung dari sumber terpercaya.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('quran.index') }}" class="btn-primary">
                        Baca Al-Qur'an
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('doa.index') }}" class="btn-ghost">Kumpulan Doa</a>
                    <a href="{{ route('jadwal.index') }}" class="btn-ghost">Jadwal Shalat</a>
                </div>
                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-4">
                    @foreach ([
                        ['label' => 'Surat', 'value' => $totalSurah],
                        ['label' => 'Ayat', 'value' => $totalAyat],
                        ['label' => 'Doa', 'value' => $totalDoa],
                    ] as $stat)
                        <div>
                            <dt class="text-[11px] tracking-wider text-slate-500 uppercase">{{ $stat['label'] }}</dt>
                            <dd class="display-title text-3xl text-slate-900 dark:text-white">
                                {{ number_format($stat['value']) }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <div class="relative">
                <div class="surface-strong relative overflow-hidden rounded-[2rem] p-2">
                    <div class="relative overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-emerald-900/90 via-slate-900 to-slate-950">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.25),transparent_55%)]"></div>
                        <div class="relative px-6 py-10 text-white sm:px-10">
                            <span class="badge badge-emerald">Islamic</span>
                            <h2 class="display-title mt-4 text-3xl sm:text-4xl">Bismillahirrahmanirrahim</h2>
                            <p class="mt-3 max-w-md text-sm text-white/80">
                                Semoga platform ini menjadi sarana kebaikan, menambah ilmu, dan mendekatkan diri kepada Allah ﷻ.
                            </p>
                            <div class="mt-8 flex flex-wrap gap-2 text-xs text-white/80">
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
