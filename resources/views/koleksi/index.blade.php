@extends('layouts.app')

@section('title', 'Koleksi')
@section('description', 'Koleksi resep pribadi milikmu.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
            <div>
                <p class="section-eyebrow">Koleksi pribadi</p>
                <h1 class="display-title mt-2 text-4xl">Koleksi saya</h1>
                <p class="mt-2 max-w-xl text-sm text-slate-600 dark:text-slate-400">
                    Groupkan resep jadi beberapa daftar, misalnya cepat saji, menu makan malam, atau dessert.
                </p>
            </div>
            <a href="{{ route('koleksi.create') }}" class="btn-primary">Buat koleksi</a>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                @forelse ($collections as $index => $collection)
                    <a href="{{ route('koleksi.show', $collection) }}"
                       data-spotlight data-reveal style="--reveal-delay: {{ $index * 70 }}ms"
                       class="surface flex items-center gap-4 p-4 transition hover:border-ember-400/40">
                        <span class="grid size-16 shrink-0 place-items-center rounded-2xl bg-gradient-to-br text-white avatar-{{ $collection->accent }}">
                            <span class="display-title text-2xl" data-count-to="{{ $collection->recipes_count }}">0</span>
                        </span>

                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-base font-semibold">{{ $collection->name }}</span>
                            <span class="mt-0.5 block line-clamp-2 text-xs text-slate-500">{{ $collection->description ?? 'Tanpa deskripsi' }}</span>
                            <span class="mt-1.5 block text-[11px] text-slate-400">
                                {{ $collection->recipes_count }} resep &middot; {{ $collection->created_at->translatedFormat('d M Y') }}
                            </span>
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0 text-slate-400" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                @empty
                    <div class="surface flex flex-col items-center gap-3 px-6 py-14 text-center">
                        <h2 class="display-title text-xl">Belum ada koleksi</h2>
                        <p class="max-w-sm text-sm text-slate-500">Buat koleksi pertamamu, nanti bisa diisi dari halaman resep.</p>
                        <a href="{{ route('koleksi.create') }}" class="btn-primary">Buat koleksi</a>
                    </div>
                @endforelse
            </div>

            <div class="space-y-5">
                <form action="{{ route('koleksi.store') }}" method="POST" class="surface rounded-[1.75rem] p-6" data-reveal>
                    @csrf
                    <h2 class="text-sm font-semibold">Koleksi baru</h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="field-label">Nama</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required minlength="2" maxlength="60" class="field" placeholder="Contoh: Masakan rumahan">
                        </div>

                        <div>
                            <label for="description" class="field-label">Deskripsi</label>
                            <textarea id="description" name="description" rows="3" maxlength="200" class="field" placeholder="Kapan koleksi ini dipakai?">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <span class="field-label">Warna aksen</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach (['orange', 'amber', 'rose', 'fuchsia', 'sky', 'emerald'] as $accent)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="accent" value="{{ $accent }}" class="sr-only" @checked(old('accent', 'orange') === $accent)>
                                        <span class="block size-8 rounded-xl avatar-{{ $accent }} ring-offset-2 ring-offset-white transition dark:ring-offset-slate-950 has-checked:ring-2 has-checked:ring-ember-500"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary mt-5 w-full">Simpan koleksi</button>
                </form>

                <div class="surface rounded-[1.75rem] p-6" data-reveal style="--reveal-delay:80ms">
                    <h2 class="text-sm font-semibold">Favorit terpisah</h2>
                    <p class="mt-2 text-xs text-slate-500">Hati di kartu resep masuk ke favorit, bukan ke koleksi.</p>

                    @php $favoriteCount = auth()->user()->favorites()->count(); @endphp

                    <p class="display-title mt-4 text-4xl">{{ $favoriteCount }}</p>
                    <p class="text-xs text-slate-500">resep difavoritkan</p>
                </div>
            </div>
        </div>
    </section>
@endsection