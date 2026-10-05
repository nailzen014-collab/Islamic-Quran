@extends('layouts.app')

@section('title', 'Favorit')
@section('description', 'Resep favoritmu')

@section('content')

    <section class="mx-auto w-full max-w-7xl px-4 pt-10 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Resep favorit</p>
            <h1 class="display-title mt-2 text-4xl sm:text-5xl">Favoritmu</h1>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                Resep yang kamu simpan di sini akan muncul di halaman favorit
            </p>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-6 sm:px-6">
        <div class="surface overflow-hidden rounded-[2rem] px-6 py-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                <div>
                    <p class="section-eyebrow">Resep menyimpan</p>
                    <h2 class="display-title mt-2 text-2xl">Semua favoritmu</h2>
                    <p class="text-sm text-slate-500">Resep yang disukai akan muncul di sini</p>
                </div>

                <div class="hidden lg:block">
                    <p class="section-eyebrow">Dapur kamu</p>
                    <h2 class="display-title mt-2 text-2xl">Koleksimu</h2>
                    <p class="text-sm text-slate-500">Groupkan resep jadi daftar</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-10 sm:px-6">
        <div class="surface overflow-hidden rounded-[2rem] p-6">
            @auth
                <div class="space-y-4">
                    @if ($userFavorites->isNotEmpty())
                        <ol class="space-y-3">
                            @foreach ($userFavorites as $recipe)
                                <li>
                                    <a href="{{ route('recipes.show', $recipe) }}"
                                       class="group flex items-center gap-3 rounded-2xl border border-transparent p-2 transition hover:border-ember-400/40 hover:bg-ember-500/5">
                                        <span class="display-title w-6 shrink-0 text-lg text-slate-300 dark:text-slate-600">{{ $recipe->id }}</span>
                                        <img src="{{ $recipe->image }}" alt="" class="size-11 rounded-xl object-cover" loading="lazy">
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-sm font-medium">{{ $recipe->name }}</span>
                                            <span class="block text-[11px] text-slate-500">{{ $recipe->cuisine }} &middot; {{ $recipe->total_minutes }} menit</span>
                                        </span>
                                        <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-amber-500">
                                            {{ number_format($recipe->rating, 1, ',', '.') }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="col-span-2 text-sm text-slate-500">
                            Belum ada favorit. Ketuk ikon hati di kartu resep untuk menyimpan.
                        </p>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-ember-500/10 text-ember-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.5-1.632Z"/>
                        </svg>
                    </span>
                    <h2 class="display-title mt-4 text-xl">Bikin akun, simpan semua</h2>
                    <p class="mt-2 text-sm text-slate-500">Koleksi pribadi, rencana makan, dan daftar belanja tersimpan otomatis.</p>
                    <div class="mt-4 flex justify-center gap-2">
                        <a href="{{ route('register') }}" class="btn-primary">Daftar gratis</a>
                        <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                    </div>
                </div>
            @endauth
        </div>
    </section>

@endsection