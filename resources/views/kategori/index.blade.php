@extends('layouts.app')

@section('title', 'Kategori')
@section('description', 'Telusuri resep berdasarkan cuisine, meal type, tag, dan tingkat kesulitan.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Telusuri</p>
            <h1 class="display-title mt-2 text-4xl sm:text-5xl">Kategori & label</h1>
            <p class="mt-3 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                Pilih cuisine, waktu makan, atau kesulitan. Semua kategori dihitung langsung dari katalog.
            </p>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6">
        <h2 class="display-title text-2xl" data-reveal>Cuisine</h2>

        <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($cuisines as $index => $cuisine)
                <a href="{{ route('jelajah', ['cuisine' => $cuisine['name']]) }}"
                   data-spotlight data-reveal style="--reveal-delay: {{ ($index % 8) * 60 }}ms"
                   class="card group">
                    <div class="card-media !aspect-16/10">
                        @if ($cuisine['cover'])
                            <img src="{{ $cuisine['cover'] }}" alt="" loading="lazy">
                        @endif
                        <div class="card-overlay"></div>
                        <span class="absolute inset-x-3 bottom-3 text-sm font-semibold text-white">{{ $cuisine['name'] }}</span>
                    </div>

                    <div class="p-4">
                        <p class="text-xs text-slate-500">{{ $cuisine['count'] }} resep</p>
                        @if ($cuisine['top']->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach ($cuisine['top'] as $tag)
                                    <span class="badge badge-neutral">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-10 sm:px-6">
        <h2 class="display-title text-2xl" data-reveal>Momentum makan</h2>

        <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($mealTypes as $index => $type)
                <a href="{{ route('jelajah', ['meal_types' => [$type['name']]]) }}"
                   data-spotlight data-reveal style="--reveal-delay: {{ ($index % 5) * 70 }}ms"
                   class="surface flex items-center gap-3 p-4">
                    @if ($type['cover'])
                        <img src="{{ $type['cover'] }}" alt="" class="size-12 shrink-0 rounded-xl object-cover" loading="lazy">
                    @endif
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium">{{ $type['name'] }}</span>
                        <span class="block text-xs text-slate-500">{{ $type['count'] }} resep</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-10 sm:px-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div data-reveal>
                <h2 class="display-title text-2xl">Tingkat kesulitan</h2>
                <div class="mt-5 space-y-3">
                    @foreach ($difficulties as $difficulty => $total)
                        @continue($total === 0)
                        <a href="{{ route('jelajah', ['difficulty' => $difficulty]) }}"
                           class="surface flex items-center justify-between gap-4 p-4 transition hover:border-ember-400/40">
                            <span class="flex items-center gap-3">
                                <span class="badge badge-{{ strtolower($difficulty) }}">{{ $difficulty }}</span>
                                <span class="text-sm text-slate-500">{{ $total }} resep</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 text-slate-400" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div data-reveal style="--reveal-delay:100ms">
                <h2 class="display-title text-2xl">Tag populer</h2>
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                        <a href="{{ route('jelajah', ['tags' => [$tag['name']]]) }}" class="chip">
                            #{{ $tag['name'] }}
                            <span class="chip-count">{{ $tag['count'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection