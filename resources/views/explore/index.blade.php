@extends('layouts.app')

@section('title', 'Jelajah Resep')
@section('description', 'Jelajahi seluruh katalog resep dengan filter cuisine, difficulty, meal type, waktu masak, dan calories.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 pb-6 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Katalog lengkap</p>
            <h1 class="display-title mt-2 text-4xl sm:text-5xl">Jelajah semua resep</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                Filter berjalan langsung di server. Hasilmu tersimpan di URL, jadi bisa dibagikan kapan saja.
            </p>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 pb-16 sm:px-6">
        <form action="{{ route('jelajah') }}" method="GET" data-filter-form="{{ route('jelajah.kartu') }}" class="grid gap-6 lg:grid-cols-[18rem_1fr]">
            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="surface rounded-[1.75rem] p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-sm font-semibold">Filter</h2>
                        <span class="badge badge-ember">{{ $recipes->total() }} hasil</span>
                    </div>

                    <div class="mt-5 space-y-6">
                        <div>
                            <label for="q" class="field-label">Kata kunci</label>
                            <input id="q" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Nama atau cuisine" class="field">
                        </div>

                        <div>
                            <span class="field-label">Cuisine</span>
                            <select name="cuisine" class="field">
                                <option value="">Semua cuisine</option>
                                @foreach ($cuisines as $cuisine)
                                    <option value="{{ $cuisine }}" @selected($filters['cuisine'] === $cuisine)>{{ $cuisine }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <span class="field-label">Difficulty</span>
                            <div class="flex flex-wrap gap-2">
                                <label class="chip cursor-pointer">
                                    <input type="radio" name="difficulty" value="" class="sr-only" @checked(! $filters['difficulty'])>
                                    Semua
                                </label>
                                @foreach ($difficulties as $difficulty)
                                    <label class="chip cursor-pointer">
                                        <input type="radio" name="difficulty" value="{{ $difficulty }}" class="sr-only" @checked($filters['difficulty'] === $difficulty)>
                                        {{ $difficulty }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <span class="field-label">Maksimal waktu masak</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([15 => '15 menit', 30 => '30 menit', 60 => '1 jam', 999 => 'Semua'] as $value => $label)
                                    <label class="chip cursor-pointer">
                                        <input type="radio" name="max_time" value="{{ $value === 999 ? '' : $value }}" class="sr-only"
                                               @checked((string) $filters['max_time'] === (string) ($value === 999 ? '' : $value)))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <span class="field-label">Meal type</span>
                            <div class="max-h-40 space-y-1 overflow-y-auto pr-1">
                                @foreach ($mealTypes as $type)
                                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm transition hover:bg-ember-500/8">
                                        <input type="checkbox" name="meal_types[]" value="{{ $type }}" class="sr-only peer"
                                               @checked(in_array($type, $filters['meal_types'], true))>
                                        <span class="checkbox peer-checked:bg-ember-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-3" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-300">{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label for="max_calories" class="field-label">
                                Batas calories
                                <span class="font-normal text-slate-400">(<span data-calories-label>{{ $filters['max_calories'] ?: '—' }}</span> kkal/porsi)</span>
                            </label>
                            <input id="max_calories" type="range" name="max_calories" min="100" max="1000" step="50"
                                   value="{{ $filters['max_calories'] ?: 1000 }}" class="range"
                                   x-data x-on:input="$el.closest('label,div').querySelector('[data-calories-label]').textContent = $el.value">
                        </div>

                        <div>
                            <span class="field-label">Urutkan</span>
                            <select name="sort" class="field" onchange="this.form.requestSubmit()">
                                @foreach ([
                                    'relevan' => 'Paling relevan',
                                    'rating' => 'Rating tertinggi',
                                    'fastest' => 'Tercepat',
                                    'calories' => 'Calories terendah',
                                    'az' => 'Nama A-Z',
                                ] as $value => $label)
                                    <option value="{{ $value }}" @selected($filters['sort'] === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" data-filter-clear class="btn-ghost w-full">Reset semua filter</button>
                    </div>
                </div>
            </aside>

            <div data-filter-results>
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span data-filter-count>{{ $recipes->total() }}</span> resep
                        @if ($filters['q'])
                            untuk &ldquo;<span class="font-medium text-slate-700 dark:text-slate-200">{{ $filters['q'] }}</span>&rdquo;
                        @endif
                    </p>

                    <button type="button" class="btn-ghost lg:hidden" @click="filtersOpen = !filtersOpen">
                        <span x-text="filtersOpen ? 'Sembunyikan filter' : 'Tampilkan filter'"></span>
                    </button>
                </div>

                @if ($topTags->isNotEmpty())
                    <div class="mb-5 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-slate-500">Tag:</span>
                        @foreach ($topTags as $tag)
                            <label class="chip cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag }}" class="sr-only" @checked(in_array($tag, $filters['tags'], true))>
                                #{{ $tag }}
                            </label>
                        @endforeach
                    </div>
                @endif

                @include('partials.recipe-grid', ['recipes' => $recipes, 'filters' => $filters])
            </div>
        </form>
    </section>
@endsection