@extends('layouts.app')

@section('title', 'Statistik')
@section('description', 'Ringkasan data katalog resep dan aktivitas dapurmu.')

@section('content')
    <section class="mx-auto w-full max-w-7xl px-4 pt-10 sm:px-6">
        <div data-reveal>
            <p class="section-eyebrow">Analitik</p>
            <h1 class="display-title mt-2 text-4xl sm:text-5xl">Statistik dapur</h1>
            <p class="mt-3 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                Angka dihitung langsung dari katalog resep yang tersimpan di database.
            </p>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Total resep', 'value' => $totalRecipes, 'suffix' => '', 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
                ['label' => 'Total bahan', 'value' => $totalIngredients, 'suffix' => '', 'icon' => 'M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75'],
                ['label' => 'Total langkah', 'value' => $totalSteps, 'suffix' => '', 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['label' => 'Rata-rata rating', 'value' => $avgRating, 'suffix' => '', 'decimals' => 2, 'icon' => 'M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'],
            ] as $index => $stat)
                <div data-spotlight data-reveal style="--reveal-delay: {{ $index * 70 }}ms" class="surface rounded-[1.5rem] p-5">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-ember-500/10 text-ember-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>

                    <p class="display-title mt-4 text-3xl">
                        <span data-count-to="{{ $stat['value'] }}" data-decimals="{{ $stat['decimals'] ?? 0 }}">0</span>
                    </p>
                    <p class="mt-1 text-xs tracking-wider text-slate-500 uppercase">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div data-reveal>
                <div class="surface rounded-[1.75rem] p-6">
                    <h2 class="text-lg font-semibold">Resep per cuisine</h2>
                    <p class="mt-1 text-sm text-slate-500">Top 12 cuisine dengan jumlah resep terbanyak.</p>

                    @php $maxCuisine = max(1, collect($byCuisine)->max('count')); @endphp

                    <div class="mt-6 space-y-3">
                        @foreach ($byCuisine as $row)
                            <div>
                                <div class="flex items-center justify-between gap-3 text-xs">
                                    <a href="{{ route('jelajah', ['cuisine' => $row['name']]) }}" class="font-medium hover:text-ember-600">{{ $row['name'] }}</a>
                                    <span class="tabular-nums text-slate-500">{{ $row['count'] }} resep &middot; {{ $row['minutes'] }} mnt avg</span>
                                </div>
                                <div class="bar-track mt-1.5 !h-2">
                                    <div class="bar-fill" style="width: {{ round($row['count'] / $maxCuisine * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div data-reveal style="--reveal-delay:80ms">
                    <div class="surface rounded-[1.75rem] p-6">
                        <h2 class="text-lg font-semibold">Sebaran calories</h2>
                        <p class="mt-1 text-sm text-slate-500">Kalori per porsi tiap resep.</p>

                        <div class="mt-6 grid grid-cols-4 gap-3 text-center">
                            @foreach ($calorieBuckets as $bucket)
                                <div class="rounded-2xl border border-black/5 p-4 dark:border-white/8">
                                    <p class="display-title text-2xl" data-count-to="{{ $bucket['count'] }}">0</p>
                                    <p class="mt-1 text-[11px] text-slate-500">{{ $bucket['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div data-reveal style="--reveal-delay:140ms">
                    <div class="surface rounded-[1.75rem] p-6">
                        <h2 class="text-lg font-semibold">Ringkasan lain</h2>

                        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            @foreach ([
                                ['Total waktu masak', number_format($totalMinutes, 0, ',', '.').' menit'],
                                ['Rata-rata calories', number_format($avgCalories, 0, ',', '.').' kkal'],
                                ['Rata-rata porsi', $avgServings],
                                ['Resep tercepat', $fastest->first()?->name ?? '-'],
                                ['Rating tertinggi', ($topRated->first() ? number_format($topRated->first()->rating, 1, ',', '.') : '-')],
                                ['Resep terlama', $fastest->last()?->name ?? '-'],
                            ] as $row)
                                <div class="rounded-xl border border-black/5 p-3 dark:border-white/8">
                                    <dt class="text-[11px] tracking-wider text-slate-500 uppercase">{{ $row[0] }}</dt>
                                    <dd class="mt-1 truncate font-medium">{{ $row[1] }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($tagCounts as $tag)
                                <a href="{{ route('jelajah', ['tags' => [$tag]]) }}" class="chip">#{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <section class="mt-6" data-reveal>
                <div class="surface-strong rounded-[1.75rem] p-6">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="section-eyebrow">Aktivitasmu</p>
                            <h2 class="display-title mt-2 text-2xl">Dapur {{ auth()->user()->kitchen_name }}</h2>
                        </div>
                        <a href="{{ route('koleksi.index') }}" class="btn-ghost">Buka koleksi</a>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        @foreach ([
                            ['Favorit', $myStats['favorites']],
                            ['Ulasan', $myStats['reviews']],
                            ['Koleksi', $myStats['collections']],
                            ['Menu direncanakan', $plannedThisWeek],
                            ['Belanja tersisa', $myStats['shopping']],
                        ] as $row)
                            <div class="rounded-2xl border border-black/5 p-4 dark:border-white/8">
                                <p class="display-title text-2xl" data-count-to="{{ $row[1] }}">0</p>
                                <p class="mt-1 text-[11px] tracking-wider text-slate-500 uppercase">{{ $row[0] }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if ($recentReviews->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-sm font-semibold">Ulasan terbaru di katalog</h3>
                            <ul class="mt-3 space-y-2">
                                @foreach ($recentReviews as $review)
                                    <li class="flex flex-wrap items-center gap-2 rounded-xl border border-black/5 px-3 py-2 text-sm dark:border-white/8">
                                        <span class="font-medium">{{ $review->author_name }}</span>
                                        <span class="text-slate-400">menilai</span>
                                        <a href="{{ route('recipes.show', $review->recipe) }}" class="link-underline hover:text-ember-600">{{ Str::limit($review->recipe->name, 28) }}</a>
                                        <span class="ml-auto text-xs text-amber-500">{{ $review->rating }}/5</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>
        @endauth
    </section>
@endsection