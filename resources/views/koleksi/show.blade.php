@extends('layouts.app')

@section('title', $collection->name)
@section('description', $collection->description)

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-8 sm:px-6">
        <nav aria-label="Remah roti" class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('koleksi.index') }}" class="link-underline hover:text-ember-600">Koleksi</a>
            <span aria-hidden="true">/</span>
            <span class="truncate text-slate-700 dark:text-slate-300">{{ $collection->name }}</span>
        </nav>

        <div class="surface-strong mt-5 overflow-hidden rounded-[2rem]">
            <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <span class="badge avatar-{{ $collection->accent }} !text-white">{{ $collection->recipes_count }} resep</span>
                    <h1 class="display-title mt-3 text-3xl sm:text-4xl">{{ $collection->name }}</h1>
                    <p class="mt-2 max-w-xl text-sm text-slate-600 dark:text-slate-400">{{ $collection->description ?? 'Koleksi tanpa deskripsi.' }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="btn-ghost" x-data x-on:click="$el.textContent = (() => { navigator.clipboard?.writeText(window.location.href); return 'Link disalin!'; })()">Salin link</button>

                    <form action="{{ route('koleksi.destroy', $collection) }}" method="POST"
                          onsubmit="return confirm('Hapus koleksi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Hapus koleksi</button>
                    </form>
                </div>
            </div>

            <details class="group border-t border-black/5 px-6 py-4 sm:px-8 dark:border-white/8" x-data="{ open: false }">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-sm font-medium">
                    <span>Ubah detail koleksi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="accordion-icon size-4 text-slate-400" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </summary>

                <form action="{{ route('koleksi.update', $collection) }}" method="POST" class="mt-4 grid gap-4 sm:grid-cols-2">
                    @csrf @method('PUT')

                    <div class="sm:col-span-2">
                        <label for="name" class="field-label">Nama</label>
                        <input id="name" name="name" type="text" value="{{ $collection->name }}" required minlength="2" maxlength="60" class="field">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="field-label">Deskripsi</label>
                        <textarea id="description" name="description" rows="2" maxlength="200" class="field">{{ $collection->description }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="field-label">Warna aksen</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach (['orange', 'amber', 'rose', 'fuchsia', 'sky', 'emerald'] as $accent)
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent" value="{{ $accent }}" class="sr-only" @checked($collection->accent === $accent)>
                                    <span class="block size-8 rounded-xl avatar-{{ $accent }} ring-offset-2 ring-offset-white transition dark:ring-offset-slate-950 has-checked:ring-2 has-checked:ring-ember-500"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary">Simpan perubahan</button>
                    </div>
                </form>
            </details>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($collection->recipes as $index => $recipe)
                <div data-reveal style="--reveal-delay: {{ ($index % 8) * 60 }}ms">
                    @include('partials.recipe-card', ['recipe' => $recipe, 'priority' => $index < 2])
                </div>
            @empty
                <div class="surface col-span-full flex flex-col items-center gap-3 px-6 py-14 text-center">
                    <h2 class="display-title text-xl">Koleksi masih kosong</h2>
                    <p class="max-w-sm text-sm text-slate-500">Buka halaman resep, lalu tekan "Simpan ke koleksi".</p>
                    <a href="{{ route('jelajah') }}" class="btn-primary">Cari resep</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection