<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full scroll-smooth"
      x-data="themeToggle"
      :class="isDark ? 'dark' : 'light'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Recipes —-platform resep lengkap dengan pencarian, filter, favorites, koleksi, dan daftar belanja.')">

    <title>@yield('title', 'Recipes') &middot; Recipes</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.dummyjson.com" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=playfair-display:500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.documentElement.classList.add('js');
        (() => {
            try {
                const stored = localStorage.getItem('recipes-theme');
                const dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>
</head>
<body class="h-full min-h-full bg-slate-50 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100"
      x-data="appShell"
      @keydown.escape.window="closeOverlays()">

<div data-scroll-progress class="scroll-progress" role="progressbar" aria-label="Kemajuan scroll"></div>

@include('partials.background')

<header data-header class="site-header">
    <div class="mx-auto flex w-full max-w-7xl items-center gap-3 px-4 py-3 sm:px-6">
        <a href="{{ route('beranda') }}" class="group inline-flex shrink-0 items-center gap-3" data-cursor="hover">
            <span class="logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18Zm0 0c2.5-2 4-4.5 4-7a4 4 0 0 0-8 0c0 2.5 1.5 5 4 7Z"/>
                </svg>
            </span>
            <span class="hidden flex-col leading-none sm:flex">
                <span class="text-sm font-semibold tracking-tight">Recipes</span>
                <span class="mt-1 text-[10px] tracking-[0.18em] text-slate-500 uppercase">Dapur Digital</span>
            </span>
        </a>

        <form action="{{ route('cari') }}" method="GET" class="ml-auto hidden max-w-md flex-1 md:block" role="search">
            <label class="search-field" x-data="{ open: false }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0 text-slate-400" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari resep, cuisine, atau bahan..." class="min-w-0 flex-1 bg-transparent text-sm outline-none placeholder:text-slate-400">
                <kbd class="hidden rounded-md border border-black/10 px-1.5 py-0.5 font-mono text-[10px] text-slate-500 lg:inline dark:border-white/15">/</kbd>
            </label>
        </form>

        <nav class="ml-auto flex items-center gap-1 md:ml-0" aria-label="Navigasi utama">
            <a href="{{ route('jelajah') }}" @class([
                'nav-link',
                'is-active' => request()->routeIs('jelajah*'),
            ])>Jelajah</a>

            <a href="{{ route('kategori') }}" @class([
                'nav-link hidden lg:inline-flex',
                'is-active' => request()->routeIs('kategori*'),
            ])>Kategori</a>

            @auth
                <a href="{{ route('koleksi.index') }}" @class([
                    'nav-link hidden sm:inline-flex',
                    'is-active' => request()->routeIs('koleksi*'),
                ])>Koleksi</a>

                <a href="{{ route('koleksi.index') }}" @class([
                    'nav-link hidden sm:inline-flex',
                    'is-active' => request()->routeIs('koleksi*'),
                ])>Koleksi</a>

                <a href="{{ route('belanja') }}" @class([
                    'nav-link hidden lg:inline-flex',
                    'is-active' => request()->routeIs('belanja*'),
                ])>Belanja</a>
            @endauth

            <button type="button" class="icon-button lg:hidden" @click="toggleMobile()" :aria-expanded="mobileOpen" aria-label="Buka menu">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>

            <button type="button" class="icon-button" @click="toggleTheme()" :aria-label="isDark ? 'Mode terang' : 'Mode gelap'">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg x-show="isDark" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 12v2.25m9-9h-2.25M5.25 12H3m14.5-6.5-1.6 1.6m-9.9 9.9-1.6 1.6m13.1 0-1.6-1.6m-9.9-9.9-1.6-1.6M12 16.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"/>
                </svg>
            </button>

            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" class="avatar-button" @click="open = !open" :aria-expanded="open" aria-haspopup="true">
                        <span class="avatar avatar-{{ auth()->user()->avatar_color }}">{{ auth()->user()->initials }}</span>
                    </button>

                    <div x-show="open" x-cloak x-transition.duration.200ms class="dropdown-panel">
                        <div class="border-b border-black/5 px-4 py-3 dark:border-white/10">
                            <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ auth()->user()->kitchen_name }}</p>
                        </div>
                        <a href="{{ route('koleksi.index') }}" class="dropdown-item">Koleksi saya</a>
                        <a href="{{ route('belanja') }}" class="dropdown-item">Daftar belanja</a>
                        <a href="{{ route('statistik') }}" class="dropdown-item">Statistik</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-rose-500">Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-primary hidden sm:inline-flex">Masuk</a>
            @endauth
        </nav>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="mobile-panel">
        <form action="{{ route('cari') }}" method="GET" class="mb-3 md:hidden">
            <label class="search-field">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0 text-slate-400" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari resep..." class="min-w-0 flex-1 bg-transparent text-sm outline-none">
            </label>
        </form>

        <a href="{{ route('beranda') }}" class="mobile-link">Beranda</a>
        <a href="{{ route('jelajah') }}" class="mobile-link">Jelajah</a>
        <a href="{{ route('kategori') }}" class="mobile-link">Kategori</a>
        <a href="{{ route('statistik') }}" class="mobile-link">Statistik</a>
        @auth
            <a href="{{ route('koleksi.index') }}" class="mobile-link">Koleksi</a>
            <a href="{{ route('belanja') }}" class="mobile-link">Daftar belanja</a>
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="mobile-link w-full text-left text-rose-500">Keluar</button></form>
        @else
            <a href="{{ route('login') }}" class="mobile-link">Masuk</a>
            <a href="{{ route('register') }}" class="mobile-link">Daftar</a>
        @endauth
    </div>
</header>

<main class="flex-1">
    @include('partials.flash')

    @yield('content')
</main>

@include('partials.footer')

<button type="button" data-to-top aria-label="Kembali ke atas"
        class="to-top">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0-6.75 6.75M12 4.5l6.75 6.75"/>
    </svg>
</button>

<div data-cursor-glow class="cursor-glow" aria-hidden="true"></div>
</body>
</html>