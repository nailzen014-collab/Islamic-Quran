<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full scroll-smooth"
      x-data="themeToggle"
      :class="isDark ? 'dark' : 'light'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Islamic Platform - Al-Qur\'an, Doa Harian, dan Jadwal Shalat')">

    <title>@yield('title', 'Islamic') &middot; Islamic</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=playfair-display:500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.documentElement.classList.add('js');
        (() => {
            try {
                const stored = localStorage.getItem('islamic-theme');
                const dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>
</head>
<body class="h-full min-h-full bg-slate-50 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100"
      x-data="appShell">

<div data-scroll-progress class="scroll-progress" role="progressbar" aria-label="Kemajuan scroll"></div>

@include('partials.background')

<header data-header class="site-header">
    <div class="mx-auto w-full max-w-7xl px-4 py-3 sm:px-6">
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('beranda') }}" class="group inline-flex min-w-0 shrink-0 items-center gap-2.5 sm:gap-3">
                <span class="logo-mark size-10 sm:size-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18Zm0 0c2.5-2 4-4.5 4-7a4 4 0 0 0-8 0c0 2.5 1.5 5 4 7Z"/>
                    </svg>
                </span>
                <span class="flex-col leading-none">
                    <span class="text-sm font-semibold tracking-tight">Islamic</span>
                    <span class="mt-1 text-[9px] tracking-[0.14em] text-slate-500 uppercase sm:text-[10px] sm:tracking-[0.18em]">Al-Qur'an &amp; Doa</span>
                </span>
            </a>

            <nav class="ml-auto hidden items-center gap-1 md:flex">
                <a href="{{ route('quran.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('quran*')])>Al-Qur'an</a>
                <a href="{{ route('doa.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('doa*')])>Doa</a>
                <a href="{{ route('jadwal.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('jadwal*')])>Jadwal Shalat</a>
            </nav>

            <button type="button" class="icon-button ml-auto shrink-0" @click="toggleTheme()" :aria-label="isDark ? 'Mode terang' : 'Mode gelap'">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg x-show="isDark" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 12v2.25m9-9h-2.25M5.25 12H3m14.5-6.5-1.6 1.6m-9.9 9.9-1.6 1.6m13.1 0-1.6-1.6m-9.9-9.9-1.6-1.6M12 16.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"/>
                </svg>
            </button>

            <button type="button"
                    class="icon-button shrink-0 md:hidden"
                    @click="toggleMobile()"
                    :aria-expanded="mobileOpen"
                    aria-controls="mobile-navigation"
                    :aria-label="mobileOpen ? 'Tutup navigasi' : 'Buka navigasi'">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18"/>
                </svg>
            </button>
        </div>

        <nav id="mobile-navigation" x-cloak x-show="mobileOpen" x-transition class="mobile-panel -mx-4 mt-3 md:hidden" aria-label="Navigasi seluler">
            <a href="{{ route('beranda') }}" @click="closeOverlays()" @class(['nav-link', 'is-active' => request()->routeIs('beranda')])>Beranda</a>
            <a href="{{ route('quran.index') }}" @click="closeOverlays()" @class(['nav-link', 'is-active' => request()->routeIs('quran*')])>Al-Qur'an</a>
            <a href="{{ route('doa.index') }}" @click="closeOverlays()" @class(['nav-link', 'is-active' => request()->routeIs('doa*')])>Doa Harian</a>
            <a href="{{ route('jadwal.index') }}" @click="closeOverlays()" @class(['nav-link', 'is-active' => request()->routeIs('jadwal*')])>Jadwal Shalat</a>
        </nav>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

@include('partials.footer')

<button type="button" data-to-top aria-label="Kembali ke atas" class="to-top">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0-6.75 6.75M12 4.5l6.75 6.75"/>
    </svg>
</button>

<div data-cursor-glow class="cursor-glow" aria-hidden="true"></div>
</body>
</html>
