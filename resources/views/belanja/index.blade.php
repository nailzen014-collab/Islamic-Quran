@extends('layouts.app')

@section('title', 'Daftar Belanja')
@section('description', 'Kelola daftar belanja dan tarik bahan dari resep pilihanmu.')

@section('content')
    @php
        $done = $stats['done'];
        $total = max($stats['total'], 1);
        $percent = round($done / $total * 100);
    @endphp

    <section class="mx-auto w-full max-w-7xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
            <div>
                <p class="section-eyebrow">Belanja</p>
                <h1 class="display-title mt-2 text-4xl">Daftar belanja</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    Centang yang sudah dibeli, atau tarik bahan langsung dari resep yang sedang direncanakan.
                </p>
            </div>

            <form action="{{ route('belanja.bersihkan') }}" method="POST" onsubmit="return confirm('Bersihkan item yang sudah dicentang?')">
                @csrf
                <button type="submit" class="btn-danger">Bersihkan yang dicentang</button>
            </form>
        </div>

        <div class="surface mt-8 rounded-[1.75rem] p-5" data-reveal>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm">
                    <span class="display-title text-2xl" data-shopping-done>{{ $done }}</span>
                    <span class="text-slate-500">dari</span>
                    <span class="display-title text-2xl" data-shopping-total>{{ $stats['total'] }}</span>
                    <span class="text-slate-500">item</span>
                </p>

                <p class="text-sm font-semibold text-ember-600 dark:text-ember-400">{{ $percent }}% selesai</p>
            </div>

            <div class="bar-track mt-3">
                <div class="bar-fill" data-shopping-bar style="width: {{ $percent }}%"></div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ([
                    'all' => 'Semua',
                    'todo' => 'Belum diambil',
                    'done' => 'Sudah diambil',
                ] as $key => $label)
                    <a href="{{ route('belanja', array_filter(['filter' => $key, 'week' => request('week')])) }}"
                       @class(['chip', 'is-active' => $filter === $key])>{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_20rem]">
            <div class="space-y-6">
                @forelse ($grouped as $category => $items)
                    <section data-reveal>
                        <h2 class="text-sm font-semibold">{{ $category }}</h2>

                        <ul class="mt-3 space-y-1.5">
                            @foreach ($items as $item)
                                <li data-shopping-row class="flex items-center gap-3 rounded-xl border border-transparent px-3 py-2 transition hover:border-ember-400/30 hover:bg-ember-500/5 {{ $item->is_checked ? 'is-done' : '' }}">
                                    <label class="flex min-w-0 flex-1 cursor-pointer items-center gap-3">
                                        <input type="checkbox" class="sr-only" @checked($item->is_checked)
                                               data-shopping-toggle="{{ route('belanja.centang', $item) }}">
                                        <span class="checkbox">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-3" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        </span>

                                        <span class="shopping-name min-w-0 flex-1 truncate text-sm">{{ $item->name }}</span>
                                        <span class="chip-count shrink-0">&times;{{ $item->quantity }}</span>
                                    </label>

                                    @if ($item->recipe)
                                        <a href="{{ route('recipes.show', $item->recipe) }}"
                                           class="hidden shrink-0 text-[11px] text-slate-400 hover:text-ember-600 sm:inline">{{ Str::limit($item->recipe->name, 18) }}</a>
                                    @endif

                                    <form action="{{ route('belanja.destroy', $item) }}" method="POST" class="shrink-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" aria-label="Hapus {{ $item->name }}" class="icon-button !size-7">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @empty
                    <div class="surface flex flex-col items-center gap-3 px-6 py-14 text-center">
                        <h2 class="display-title text-xl">Daftar masih kosong</h2>
                        <p class="max-w-sm text-sm text-slate-500">Tambah item manual, atau tarik bahan dari resep minggu ini.</p>
                    </div>
                @endforelse
            </div>

            <aside class="space-y-5">
                <form action="{{ route('belanja.store') }}" method="POST" class="surface rounded-[1.75rem] p-6" data-reveal>
                    @csrf
                    <h2 class="text-sm font-semibold">Tambah item</h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="field-label">Nama barang</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="80" class="field" placeholder="Contoh: Bawang merah 1 kg">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="category" class="field-label">Kategori</label>
                                <select id="category" name="category" class="field">
                                    @foreach (['Sembako', 'Sayur', 'Protein', 'Susu', 'Bumbu', 'Rumah', 'Lainnya'] as $category)
                                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="field-label">Jumlah</label>
                                <input id="quantity" name="quantity" type="number" value="{{ old('quantity', 1) }}" min="1" max="99" class="field">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary mt-5 w-full">Tambah</button>
                </form>

                @if ($plannedRecipes->isNotEmpty())
                    <form action="{{ route('belanja.impor') }}" method="POST" class="surface rounded-[1.75rem] p-6" data-reveal style="--reveal-delay:80ms">
                        @csrf
                        <h2 class="text-sm font-semibold">Tarik dari rencana</h2>
                        <p class="mt-1 text-xs text-slate-500">Pilih resep, bahannya otomatis ditambahkan.</p>

                        <div class="mt-4 space-y-1.5">
                            @foreach ($plannedRecipes as $recipe)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-ember-500/8">
                                    <input type="checkbox" name="recipe_ids[]" value="{{ $recipe->id }}" class="sr-only peer">
                                    <span class="checkbox peer-checked:bg-ember-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-3" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </span>
                                    <img src="{{ $recipe->image }}" alt="" class="size-8 shrink-0 rounded-lg object-cover" loading="lazy">
                                    <span class="min-w-0 flex-1 truncate text-xs">{{ $recipe->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn-primary mt-4 w-full">Tambah bahan</button>
                    </form>
                @endif
            </aside>
        </div>
    </section>
@endsection