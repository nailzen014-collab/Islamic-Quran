@extends('layouts.app')

@section('title', $term ? 'Cari ' . $term : 'Cari resep')
@section('description', 'Pencarian resep berdasarkan nama, cuisine, atau bahan.')

@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 pt-10 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Pencarian</p>
            <h1 class="display-title mt-2 text-3xl sm:text-4xl">
                @if ($term)
                    Hasil untuk &ldquo;{{ $term }}&rdquo;
                @else
                    Cari resep
                @endif
            </h1>
        </div>

        <form action="{{ route('cari') }}" method="GET" class="mt-6" data-reveal style="--reveal-delay:80ms" role="search">
            <label class="search-field !py-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5 shrink-0 text-slate-400" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="search" name="q" value="{{ $term }}" autofocus
                       placeholder="Cari resep, cuisine, atau bahan..." class="min-w-0 flex-1 bg-transparent text-base outline-none">
                <button type="submit" class="btn-primary !px-4 !py-1.5 text-xs">Cari</button>
            </label>
        </form>

        @if ($suggestions->isNotEmpty())
            <div class="mt-4 flex flex-wrap items-center gap-2" data-reveal style="--reveal-delay:120ms">
                <span class="text-xs text-slate-500">Tag yang cocok:</span>
                @foreach ($suggestions as $tag)
                    <a href="{{ route('jelajah', ['tags' => [$tag]]) }}" class="chip">#{{ $tag }}</a>
                @endforeach
            </div>
        @endif

        @if ($term === '')
            <div class="mt-10" data-reveal>
                <h2 class="text-sm font-semibold">Cuisine populer</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($popularCuisines as $cuisine)
                        <a href="{{ route('jelajah', ['cuisine' => $cuisine]) }}" class="chip">{{ $cuisine }}</a>
                    @endforeach
                </div>

                <a href="{{ route('jelajah') }}" class="btn-ghost mt-6">Lihat semua resep</a>
            </div>
        @else
            <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($results as $index => $recipe)
                    <div data-reveal style="--reveal-delay: {{ ($index % 6) * 60 }}ms">
                        @include('partials.recipe-card', ['recipe' => $recipe, 'priority' => $index < 3])
                    </div>
                @empty
                    <div class="surface col-span-full flex flex-col items-center gap-3 px-6 py-14 text-center">
                        <h2 class="display-title text-xl">Tidak ada hasil</h2>
                        <p class="max-w-sm text-sm text-slate-500">Coba kata kunci lain, atau jelajahi semua resep.</p>
                        <a href="{{ route('jelajah') }}" class="btn-primary">Jelajahi semua</a>
                    </div>
                @endforelse
            </div>
        @endif
    </section>
@endsection