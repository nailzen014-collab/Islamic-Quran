<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $quotes['author'] ?? 'Quotes' }} &middot; Quotes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased">
    <div class="relative isolate flex min-h-full flex-col overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-40 left-1/2 h-80 w-[36rem] -translate-x-1/2 rounded-full bg-indigo-600/20 blur-3xl"></div>
            <div class="absolute -bottom-32 right-0 h-72 w-72 rounded-full bg-fuchsia-600/15 blur-3xl"></div>
            <div class="absolute left-0 top-1/3 h-64 w-64 rounded-full bg-sky-500/10 blur-3xl"></div>
        </div>

        <header class="mx-auto flex w-full max-w-3xl items-center justify-between px-6 py-8">
            <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 text-sm font-medium">
                <span class="flex size-8 items-center justify-center rounded-lg bg-white/10 text-base ring-1 ring-white/15 transition group-hover:bg-white/20">
                    &ldquo;
                </span>
                <span class="tracking-tight text-slate-200">Quotes</span>
            </a>

            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 rounded-full bg-white/5 px-4 py-2 text-sm font-medium text-slate-200 ring-1 ring-white/10 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">
                Acak lagi
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" class="size-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.023 9.348h4.992V4.356M3.977 14.652H8.97v4.992M4.031 9.348a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m-18 4.99 3.182 3.182a8.25 8.25 0 0 0 13.803-3.7"/>
                </svg>
            </a>
        </header>

        <main class="mx-auto flex w-full max-w-3xl flex-1 items-center px-6 pb-16">
            <article
                class="w-full rounded-3xl border border-white/10 bg-white/[0.04] p-8 shadow-2xl shadow-black/40 ring-1 ring-white/5 backdrop-blur-xl sm:p-12">

                @if (! empty($quotes))
                    <blockquote class="relative">
                        <span aria-hidden="true"
                              class="absolute -top-2 -left-1 select-none font-serif text-6xl leading-none text-indigo-400/40 sm:-left-3 sm:text-7xl">
                            &ldquo;
                        </span>

                        <p class="relative text-balance text-2xl leading-relaxed font-medium tracking-tight text-white sm:text-3xl sm:leading-snug">
                            {{ $quotes['quote'] }}
                        </p>
                    </blockquote>

                    <footer class="mt-10 flex items-center gap-4">
                        <span aria-hidden="true" class="h-px flex-1 bg-gradient-to-r from-white/25 to-transparent"></span>
                        <cite class="text-sm font-semibold tracking-wide text-slate-300 not-italic">
                            {{ $quotes['author'] }}
                        </cite>
                    </footer>
                @else
                    <div class="py-10 text-center">
                        <span aria-hidden="true"
                              class="mx-auto mb-6 flex size-14 items-center justify-center rounded-2xl bg-amber-400/10 text-2xl ring-1 ring-amber-300/20">
                            !
                        </span>
                        <h1 class="text-2xl font-semibold tracking-tight text-white">Quote tidak tersedia</h1>
                        <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-400">
                            Kami gagal mengambil data dari sumber. Silakan coba lagi beberapa saat lagi.
                        </p>
                    </div>
                @endif
            </article>
        </main>

        <footer class="mx-auto w-full max-w-3xl px-6 pb-8">
            <div class="flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-xs text-slate-500 sm:flex-row">
                <p>&copy; {{ date('Y') }} Quotes &middot; data dari DummyJSON</p>
                <p>
                    @if (! empty($quotes))
                        <span class="font-mono">#{{ $quotes['id'] }}</span>
                    @endif
                </p>
            </div>
        </footer>
    </div>
</body>
</html>