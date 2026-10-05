@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 pb-4 sm:px-6 sm:pt-16" data-parallax-scope>
        <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <p class="section-eyebrow" data-reveal>
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-ember-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-ember-500"></span>
                    </span>
                    {{ number_format($totalRecipes) }} resep langsung dari API
                </p>

                <h1 class="display-title mt-5 text-4xl text-slate-900 sm:text-5xl lg:text-6xl dark:text-white" data-reveal style="--reveal-delay:80ms">
                    Masak jadi lebih
                    <span class="bg-gradient-to-r from-ember-400 via-rose-400 to-fuchsia-400 bg-clip-text text-transparent">terstruktur.</span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600 dark:text-slate-400" data-reveal style="--reveal-delay:160ms">
                    Cari lewat {{ number_format($totalRecipes) }} resep, filter per cuisine, susun rencana makan
                    mingguan, dan bahan会自动 masuk daftar belanja. Semua dalam satu dapur digital.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3" data-reveal style="--reveal-delay:240ms">
                    <a href="{{ route('jelajah') }}" class="btn-primary">
                        Mulai jelajah
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('kategori') }}" class="btn-ghost">Lihat kategori</a>
                    <a href="{{ route('statistik') }}" class="btn-ghost">Statistik</a>
                </div>

                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-4" data-reveal style="--reveal-delay:320ms">
                    @foreach ([
                        ['label' => 'Resep', 'value' => $totalRecipes, 'suffix' => ''],
                        ['label' => 'Cuisine', 'value' => $totalCuisines, 'suffix' => ''],
                        ['label' => 'Tag', 'value' => $totalTags, 'suffix' => ''],
                    ] as $stat)
                        <div>
                            <dt class="text-[11px] tracking-wider text-slate-500 uppercase">{{ $stat['label'] }}</dt>
                            <dd class="display-title text-3xl text-slate-900 dark:text-white">
                                <span data-count-to="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] }}">0</span>
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="relative" data-reveal="zoom" style="--reveal-delay:200ms">
                <div class="surface-strong relative overflow-hidden rounded-[2rem] p-2">
                    @if ($featured->isNotEmpty())
                        <div class="relative overflow-hidden rounded-[1.5rem]">
                            <img src="{{ $featured->first()->image }}" alt="{{ $featured->first()->name }}"
                                 class="aspect-4/5 w-full object-cover sm:aspect-16/11 lg:aspect-4/5" data-parallax="0.06" fetchpriority="high">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-6">
                                <span class="badge badge-ember">Paling tinggi</span>
                                <h2 class="display-title mt-2 text-2xl text-white sm:text-3xl">{{ $featured->first()->name }}</h2>
                                <p class="mt-1.5 line-clamp-2 text-sm text-white/70">{{ $featured->first()->summary }}</p>

                                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-white/80">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-1 backdrop-blur">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        {{ $featured->first()->total_minutes }} menit
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-1 backdrop-blur">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3.5" aria-hidden="true">
                                            <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                        </svg>
                                        {{ number_format($featured->first()->rating, 1, ',', '.') }}
                                    </span>
                                    <a href="{{ route('recipes.show', $featured->first()) }}" class="btn-primary ml-auto">Lihat resep</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="surface absolute -bottom-6 -left-4 hidden w-52 p-4 sm:block" data-lift>
                    <p class="text-[11px] tracking-wider text-slate-500 uppercase">Menu cepat</p>
                    <div class="mt-3 space-y-2">
                        @foreach ($quick->take(2) as $recipe)
                            <a href="{{ route('recipes.show', $recipe) }}" class="group flex items-center gap-2.5">
                                <img src="{{ $recipe->image }}" alt="" class="size-9 rounded-lg object-cover" loading="lazy">
                                <span class="min-w-0">
                                    <span class="block truncate text-xs font-medium">{{ $recipe->name }}</span>
                                    <span class="block text-[11px] text-slate-500">{{ $recipe->total_minutes }} menit</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 py-14 sm:px-6" data-parallax-scope>
        <div class="surface overflow-hidden rounded-[2rem] px-6 py-8" data-reveal>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                <div class="shrink-0 lg:w-64">
                    <p class="section-eyebrow">Telusuri label</p>
                    <h2 class="display-title mt-2 text-2xl">Tag populer</h2>
                    <p class="mt-2 text-sm text-slate-500">Geser atau klik tag untuk langsung menyaring katalog.</p>
                </div>

                <div class="marquee flex-1" data-marquee>
                    <div class="marquee-track">
                        @foreach ($mealTypes as $type)
                            <a href="{{ route('jelajah', ['meal_types' => [$type]]) }}" class="chip">{{ $type }}</a>
                        @endforeach
                        @foreach ($cuisines as $cuisine)
                            <a href="{{ route('jelajah', ['cuisine' => $cuisine]) }}" class="chip">{{ $cuisine }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($quick->isNotEmpty())
        <section class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <p class="section-eyebrow">Di bawah 25 menit</p>
                    <h2 class="display-title mt-2 text-3xl sm:text-4xl">Masak kilat</h2>
                </div>
                <a href="{{ route('jelajah', ['max_time' => 25, 'sort' => 'fastest']) }}" class="btn-ghost">Lihat semua</a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($quick as $index => $recipe)
                    <div data-reveal style="--reveal-delay: {{ $index * 80 }}ms">
                        @include('partials.recipe-card', ['recipe' => $recipe, 'priority' => $index < 2])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto w-full max-w-7xl px-4 py-14 sm:px-6">
        <div class="grid gap-10 lg:grid-cols-[1.4fr_1fr]">
            <div>
                <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                    <div>
                        <p class="section-eyebrow">Paling banyak dibahas</p>
                        <h2 class="display-title mt-2 text-3xl sm:text-4xl">Sedang tren</h2>
                    </div>
                    <a href="{{ route('jelajah', ['sort' => 'rating']) }}" class="btn-ghost">Semua</a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @foreach ($trending as $index => $recipe)
                        <div data-reveal style="--reveal-delay: {{ $index * 70 }}ms">
                            @include('partials.recipe-card', ['recipe' => $recipe])
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-5" data-reveal style="--reveal-delay:120ms">
                <div class="surface-strong rounded-[1.75rem] p-6">
                    <p class="section-eyebrow">Peringkat</p>
                    <h2 class="display-title mt-2 text-2xl">Rating tertinggi</h2>

                    <ol class="mt-5 space-y-3">
                        @foreach ($topRated as $index => $recipe)
                            <li>
                                <a href="{{ route('recipes.show', $recipe) }}"
                                   class="group flex items-center gap-3 rounded-2xl border border-transparent p-2 transition hover:border-ember-400/40 hover:bg-ember-500/5">
                                    <span class="display-title w-6 shrink-0 text-lg text-slate-300 dark:text-slate-600">{{ $index + 1 }}</span>
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
                </div>

                @auth
                    <div class="surface rounded-[1.75rem] p-6">
                        <p class="section-eyebrow">Dapur kamu</p>
                        <h2 class="display-title mt-2 text-2xl">Favoritmu</h2>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            @forelse ($userFavorites as $recipe)
                                <a href="{{ route('recipes.show', $recipe) }}" class="group relative overflow-hidden rounded-xl">
                                    <img src="{{ $recipe->image }}" alt="{{ $recipe->name }}" class="aspect-square w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                                    <span class="absolute inset-0 bg-gradient-to-t from-slate-950/85 to-transparent"></span>
                                    <span class="absolute inset-x-2 bottom-2 line-clamp-2 text-[11px] font-medium text-white">{{ $recipe->name }}</span>
                                </a>
                            @empty
                                <p class="col-span-2 text-sm text-slate-500">
                                    Belum ada favorit. Ketuk ikon hati di kartu resep untuk menyimpan.
                                </p>
                            @endforelse
                        </div>

                        <a href="{{ route('koleksi.index') }}" class="btn-ghost mt-4 w-full">Buka koleksi</a>
                    </div>
                @else
                    <div class="surface rounded-[1.75rem] p-6 text-center">
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
            </aside>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
            <div>
                <p class="section-eyebrow">Baru masuk katalog</p>
                <h2 class="display-title mt-2 text-3xl sm:text-4xl">Resep terbaru</h2>
            </div>
            <a href="{{ route('jelajah') }}" class="btn-ghost">Jelajahi semua</a>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($newest as $index => $recipe)
                <div data-reveal style="--reveal-delay: {{ $index * 70 }}ms">
                    @include('partials.recipe-card', ['recipe' => $recipe])
                </div>
            @endforeach
        </div>
    </section>
@endsection