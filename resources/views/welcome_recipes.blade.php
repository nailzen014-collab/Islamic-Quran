@php
    $dataset = [
        'id' => 1,
        'name' => 'Classic Margherita Pizza',
        'ingredients' => [
            'Pizza dough',
            'Tomato sauce',
            'Fresh mozzarella cheese',
            'Fresh basil leaves',
            'Olive oil',
            'Salt and pepper to taste',
        ],
        'instructions' => [
            'Preheat the oven to 475°F (245°C).',
            'Roll out the pizza dough and spread tomato sauce evenly.',
            'Top with slices of fresh mozzarella and fresh basil leaves.',
            'Drizzle with olive oil and season with salt and pepper.',
            'Bake in the preheated oven for 12-15 minutes or until the crust is golden brown.',
            'Slice and serve hot.',
        ],
        'prepTimeMinutes' => 20,
        'cookTimeMinutes' => 15,
        'servings' => 4,
        'difficulty' => 'Easy',
        'cuisine' => 'Italian',
        'caloriesPerServing' => 300,
        'tags' => ['Pizza', 'Italian'],
        'userId' => 166,
        'image' => 'https://cdn.dummyjson.com/recipe-images/1.webp',
        'rating' => 4.6,
        'reviewCount' => 98,
        'mealType' => ['Dinner'],
    ];

    // Normalisasi: controller mengirim satu resep array; fallback dipakai bila kosong.
    $recipe = $dataset;
    if (is_array($recipes ?? null) && $recipes !== []) {
        $recipe = isset($recipes['name']) ? $recipes : ($recipes[array_rand($recipes)] ?? $dataset);
    }

    $ingredients = $recipe['ingredients'] ?? [];
    $instructions = $recipe['instructions'] ?? [];
    $tags = $recipe['tags'] ?? [];
    $mealType = $recipe['mealType'] ?? [];

    $prepTime = (int) ($recipe['prepTimeMinutes'] ?? 0);
    $cookTime = (int) ($recipe['cookTimeMinutes'] ?? 0);
    $totalTime = $prepTime + $cookTime;

    $rating = (float) ($recipe['rating'] ?? 0);
    $ratingPercent = min(100, max(0, $rating / 5 * 100));

    $formatMinutes = static function (int $minutes): string {
        $hours = intdiv($minutes, 60);
        $rest = $minutes % 60;

        return $hours > 0
            ? trim($hours . 'j ' . ($rest > 0 ? $rest . 'm' : ''))
            : $rest . 'm';
    };

    // Kelas ditulis utuh agar terbaca oleh pemindai Tailwind.
    [$difficultyBadge, $difficultyDot] = match (strtolower((string) ($recipe['difficulty'] ?? ''))) {
        'easy' => ['bg-emerald-500/10 text-emerald-300 ring-emerald-400/25', 'bg-emerald-400'],
        'medium' => ['bg-amber-500/10 text-amber-300 ring-amber-400/25', 'bg-amber-400'],
        'hard' => ['bg-rose-500/10 text-rose-300 ring-rose-400/25', 'bg-rose-400'],
        default => ['bg-slate-500/10 text-slate-300 ring-slate-400/25', 'bg-slate-400'],
    };

    $stats = [
        [
            'label' => 'Prep time',
            'value' => $formatMinutes($prepTime),
            'count' => $prepTime,
            'suffix' => 'm',
            'icon' => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 22.5H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 8.25h12.974c.576 0 1.059.435 1.119 1.007Z',
            'tone' => 'text-sky-300 ring-sky-400/20',
        ],
        [
            'label' => 'Cook time',
            'value' => $formatMinutes($cookTime),
            'count' => $cookTime,
            'suffix' => 'm',
            'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25',
            'tone' => 'text-rose-300 ring-rose-400/20',
        ],
        [
            'label' => 'Total time',
            'value' => $formatMinutes($totalTime),
            'count' => $totalTime,
            'suffix' => 'm',
            'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'tone' => 'text-orange-300 ring-orange-400/20',
        ],
        [
            'label' => 'Servings',
            'value' => number_format((float) ($recipe['servings'] ?? 0), 0, ',', '.') . ' orang',
            'count' => (int) ($recipe['servings'] ?? 0),
            'suffix' => ' orang',
            'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
            'tone' => 'text-emerald-300 ring-emerald-400/20',
        ],
        [
            'label' => 'Kalori / porsi',
            'value' => number_format((float) ($recipe['caloriesPerServing'] ?? 0), 0, ',', '.') . ' kkal',
            'count' => (int) ($recipe['caloriesPerServing'] ?? 0),
            'suffix' => ' kkal',
            'icon' => 'M15.75 12c0 1.5-1.5 2.25-3.75 2.25S8.25 13.5 8.25 12c0-1.5 1.5-2.25 3.75-2.25S15.75 10.5 15.75 12Zm-7.5 0v6.75m7.5-6.75v6.75M5.25 18.75h10.5M12 3v2.25m0 0L9.75 7.5M12 5.25l2.25 2.25',
            'tone' => 'text-fuchsia-300 ring-fuchsia-400/20',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Koleksi resep dari DummyJSON: bahan, langkah memasak, dan seluruh nilai JSON ditampilkan lengkap.">

    <title>{{ $recipe['name'] ?? 'Recipes' }} &middot; Recipes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>document.documentElement.classList.add('js');</script>
</head>
<body class="h-full bg-slate-950 font-sans text-slate-100 antialiased">
<div data-scroll-progress class="scroll-progress" role="progressbar" aria-label="Kemajuan scroll" aria-hidden="true"></div>

<div class="relative isolate flex min-h-full flex-col overflow-hidden">
    <div aria-hidden="true" class="aurora -z-10">
        <span></span>
        <span></span>
        <span></span>
        <div class="grid-drift"></div>
    </div>

    {{-- Header --}}
    <header data-header class="sticky top-0 z-30 border-b border-white/5 bg-slate-950/70 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-6 py-4">
            <a href="{{ url('/') }}" data-reveal="left" class="group inline-flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-rose-500 shadow-lg shadow-orange-500/20 ring-1 ring-white/20 transition duration-500 group-hover:scale-110 group-hover:rotate-6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18Zm0 0c2.5-2 4-4.5 4-7a4 4 0 0 0-8 0c0 2.5 1.5 5 4 7Z"/>
                    </svg>
                </span>
                <span class="flex flex-col leading-none">
                    <span class="tracking-tight text-slate-100">Recipes</span>
                    <span class="mt-1 text-[11px] tracking-widest text-slate-500 uppercase">DummyJSON API</span>
                </span>
            </a>

            <div class="flex items-center gap-2">
                <span data-reveal="right" style="--reveal-delay:120ms" class="hidden items-center gap-2 rounded-full bg-white/5 px-3 py-1.5 text-xs text-slate-400 ring-1 ring-white/10 sm:inline-flex">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Live API
                </span>
                <a href="{{ url('/food') }}" data-shine data-reveal="right" style="--reveal-delay:200ms"
                   class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition duration-500 hover:scale-[1.04] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400 active:scale-95">
                    Acak resep
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 transition-transform duration-700 group-hover:rotate-180" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356M3.977 14.652H8.97v4.992M4.031 9.348a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m-18 4.99 3.182 3.182a8.25 8.25 0 0 0 13.803-3.7"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-8 sm:py-12">
        {{-- Hero --}}
        <section data-lift data-reveal="zoom-out" class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.04] shadow-2xl shadow-black/50 ring-1 ring-white/5 backdrop-blur-xl">
            <div class="grid lg:grid-cols-2">
                <div data-reveal="blur" style="--reveal-delay:80ms" class="relative aspect-video overflow-hidden lg:aspect-auto lg:min-h-[26rem]">
                    @if (! empty($recipe['image']))
                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['name'] ?? 'Resep' }}" loading="eager" decoding="async"
                             data-parallax="0.12" style="transform: scale(1.12)"
                             class="absolute inset-0 size-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:via-slate-950/10 lg:to-slate-950/80"></div>
                    <div class="absolute inset-0 ring-1 ring-inset ring-white/10"></div>

                    <div class="absolute bottom-4 left-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-950/70 px-3 py-1.5 text-xs font-medium text-slate-200 ring-1 ring-white/15 backdrop-blur-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-4a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/>
                            </svg>
                            {{ $recipe['cuisine'] ?? 'Unknown' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-950/70 px-3 py-1.5 text-xs font-medium text-slate-200 ring-1 ring-white/15 backdrop-blur-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3m9-9h-3M6 12H3m14.5-6.5-2 2m-9 9-2 2m13 0-2-2m-9-9-2-2"/>
                            </svg>
                            {{ $mealType[0] ?? 'Any time' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col justify-center gap-6 p-6 sm:p-10">
                    <div data-reveal="up" style="--reveal-delay:160ms" class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-orange-500/10 px-2.5 py-1 font-mono text-xs font-semibold text-orange-300 ring-1 ring-orange-400/25">
                            RECIPE #{{ $recipe['id'] ?? 0 }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $difficultyBadge }}">
                            <span class="size-1.5 rounded-full {{ $difficultyDot }}"></span>
                            {{ $recipe['difficulty'] ?? 'Unknown' }}
                        </span>
                    </div>

                    <div data-reveal="up" style="--reveal-delay:260ms">
                        <h1 class="text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                            {{ $recipe['name'] ?? 'Resep tidak ditemukan' }}
                        </h1>
                        <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-400 sm:text-base">
                            Resep klasik Italia dengan {{ count($ingredients) }} bahan sederhana
                            dan {{ count($instructions) }} langkah memasak yang mudah diikuti.
                        </p>
                    </div>

                    <div data-reveal="up" style="--reveal-delay:360ms" class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div data-stars class="relative w-26" role="img" aria-label="Rating {{ number_format($rating, 1, ',', '.') }} dari 5">
                                <div class="flex gap-0.5 text-slate-700">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                            <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <div data-stars-fill class="absolute inset-0 overflow-hidden text-amber-400 transition-[width] duration-1000" style="width:0%">
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                                <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm">
                                <p class="font-semibold text-white">
                                    <span data-counter data-count-to="{{ $rating }}" data-decimals="1">{{ number_format($rating, 1, ',', '.') }}</span>
                                    <span class="font-normal text-slate-500">/ 5</span>
                                </p>
                                <p class="text-xs text-slate-500">
                                    <span data-counter data-count-to="{{ (int) ($recipe['reviewCount'] ?? 0) }}">{{ number_format((float) ($recipe['reviewCount'] ?? 0), 0, ',', '.') }}</span>
                                    ulasan
                                </p>
                            </div>
                        </div>

                        <span class="hidden h-8 w-px bg-white/10 sm:block"></span>

                        <div class="flex items-center gap-2">
                            <span class="flex size-8 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-slate-200 ring-1 ring-white/15">
                                U<span data-counter data-count-to="{{ (int) ($recipe['userId'] ?? 0) }}">{{ $recipe['userId'] ?? 0 }}</span>
                            </span>
                            <span class="leading-tight">
                                <span class="block text-xs text-slate-500">userId</span>
                                <span class="block font-mono text-xs font-semibold text-slate-300">{{ $recipe['userId'] ?? 0 }}</span>
                            </span>
                        </div>
                    </div>

                    @if ($tags !== [])
                        <div data-reveal="up" style="--reveal-delay:460ms" class="flex flex-wrap items-center gap-2">
                            @foreach ($tags as $tag)
                                <span data-lift class="cursor-default rounded-lg bg-white/5 px-2.5 py-1 text-xs font-medium text-slate-300 ring-1 ring-white/10">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Statistik --}}
        <section class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
            @foreach ($stats as $index => $stat)
                <div data-spotlight data-reveal="up" style="--reveal-delay:{{ $index * 90 }}ms"
                     class="group rounded-2xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/5 backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white/5 ring-1 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3 {{ $stat['tone'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-4.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] tracking-wide text-slate-500 uppercase">{{ $stat['label'] }}</p>
                            <p class="truncate text-lg font-semibold tracking-tight text-white">
                                <span data-counter data-count-to="{{ $stat['count'] }}" data-suffix="{{ $stat['suffix'] }}">{{ $stat['value'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- Konten utama --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-5">
            {{-- Bahan --}}
            <section class="lg:col-span-2">
                <div data-reveal="left" class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl shadow-black/40 ring-1 ring-white/5 backdrop-blur-xl">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight text-white">Bahan-bahan</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                <span data-progress-current>0</span> dari {{ count($ingredients) }} bahan
                            </p>
                        </div>
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 text-orange-300 ring-1 ring-orange-400/25">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-4.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.42 48.42 0 0 0 12 4.5a48.42 48.42 0 0 0-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.99 5.99 0 0 1-4.062 0c-.483-.174-.711-.703-.59-1.202L18.75 4.97Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.99 5.99 0 0 1-4.062 0c-.483-.174-.711-.703-.59-1.202L5.25 4.97Z"/>
                            </svg>
                        </span>
                    </div>

                    <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-white/10">
                        <div data-progress-bar class="h-full w-0 rounded-full bg-gradient-to-r from-orange-500 to-rose-500 transition-[width] duration-500"></div>
                    </div>

                    @if ($ingredients !== [])
                        <ul class="mt-5 flex-1 space-y-2">
                            @foreach ($ingredients as $index => $ingredient)
                                <li data-reveal="left" style="--reveal-delay:{{ 140 + $index * 70 }}ms">
                                    <label data-ingredient-row class="flex cursor-pointer items-center gap-3 rounded-2xl border border-transparent bg-white/[0.03] px-3 py-2.5">
                                        <input type="checkbox" value="{{ $index }}" data-ingredient-checkbox class="peer sr-only">
                                        <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-white/20 bg-white/5 text-transparent transition duration-300 peer-checked:scale-110 peer-checked:border-orange-400 peer-checked:bg-orange-500 peer-checked:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-3.5" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        <span class="font-mono text-xs text-slate-600">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-sm text-slate-300 transition duration-300 peer-checked:text-slate-500 peer-checked:line-through">{{ $ingredient }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-5 text-sm text-slate-500">Bahan tidak tersedia.</p>
                    @endif

                    <div class="mt-5 flex gap-2">
                        <button type="button" data-toggle-all
                                class="flex-1 rounded-xl bg-white/5 px-4 py-2.5 text-sm font-medium text-slate-200 ring-1 ring-white/10 transition duration-300 hover:scale-[1.02] hover:bg-white/10 active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400">
                            Centang semua
                        </button>
                        <button type="button" data-copy-text="{{ $ingredients === [] ? '' : '- ' . implode("\n- ", $ingredients) }}"
                                class="flex-1 rounded-xl bg-white/5 px-4 py-2.5 text-sm font-medium text-slate-200 ring-1 ring-white/10 transition duration-300 hover:scale-[1.02] hover:bg-white/10 active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400">
                            Salin bahan
                        </button>
                    </div>
                </div>
            </section>

            {{-- Langkah --}}
            <section class="lg:col-span-3">
                <div data-reveal="right" class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl shadow-black/40 ring-1 ring-white/5 backdrop-blur-xl">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight text-white">Langkah memasak</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ count($instructions) }} langkah berurutan</p>
                        </div>
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300 ring-1 ring-sky-400/25">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-4.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </span>
                    </div>

                    @if ($instructions !== [])
                        <ol class="relative mt-6 space-y-6 before:absolute before:top-2 before:bottom-2 before:left-4 before:w-px before:bg-gradient-to-b before:from-orange-500/50 before:via-white/10 before:to-transparent">
                            @foreach ($instructions as $index => $step)
                                <li data-step data-reveal="right" style="--reveal-delay:{{ 160 + $index * 80 }}ms" class="group relative flex gap-4">
                                    <span class="step-marker relative z-10 flex size-8 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-xs font-bold text-orange-300 ring-1 ring-white/10">
                                        {{ $index + 1 }}
                                    </span>
                                    <p class="pt-1 text-sm leading-relaxed text-slate-300 transition duration-500 group-hover:text-white">
                                        {{ $step }}
                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mt-6 text-sm text-slate-500">Langkah memasak tidak tersedia.</p>
                    @endif

                    {{-- Nilai JSON lainnya --}}
                    <dl class="mt-8 grid gap-3 border-t border-white/10 pt-6 sm:grid-cols-2">
                        <div data-reveal="up" style="--reveal-delay:660ms" class="flex items-center justify-between gap-3 rounded-xl bg-white/[0.03] px-3 py-2.5 ring-1 ring-white/5">
                            <dt class="font-mono text-xs text-slate-500">mealType</dt>
                            <dd class="flex flex-wrap justify-end gap-1.5">
                                @forelse ($mealType as $type)
                                    <span class="rounded-md bg-sky-500/10 px-2 py-0.5 text-xs font-medium text-sky-300 ring-1 ring-sky-400/20">{{ $type }}</span>
                                @empty
                                    <span class="text-xs text-slate-500">&mdash;</span>
                                @endforelse
                            </dd>
                        </div>
                        <div data-reveal="up" style="--reveal-delay:720ms" class="flex items-center justify-between gap-3 rounded-xl bg-white/[0.03] px-3 py-2.5 ring-1 ring-white/5">
                            <dt class="font-mono text-xs text-slate-500">cuisine</dt>
                            <dd class="text-sm font-medium text-slate-200">{{ $recipe['cuisine'] ?? '—' }}</dd>
                        </div>
                        <div data-reveal="up" style="--reveal-delay:780ms" class="flex items-center justify-between gap-3 rounded-xl bg-white/[0.03] px-3 py-2.5 ring-1 ring-white/5">
                            <dt class="font-mono text-xs text-slate-500">difficulty</dt>
                            <dd class="text-sm font-medium text-slate-200">{{ $recipe['difficulty'] ?? '—' }}</dd>
                        </div>
                        <div data-reveal="up" style="--reveal-delay:840ms" class="flex items-center justify-between gap-3 rounded-xl bg-white/[0.03] px-3 py-2.5 ring-1 ring-white/5">
                            <dt class="font-mono text-xs text-slate-500">image</dt>
                            <dd class="max-w-[60%] truncate font-mono text-xs text-slate-400" title="{{ $recipe['image'] ?? '' }}">{{ $recipe['image'] ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </section>
        </div>

        {{-- Respons JSON mentah --}}
        <section data-reveal="up" class="mt-6 overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl shadow-black/40 ring-1 ring-white/5">
            <button type="button" data-json-toggle aria-expanded="false" aria-controls="json-panel"
                    class="flex w-full items-center justify-between gap-4 px-6 py-4 text-left transition hover:bg-white/[0.03]">
                <span class="flex items-center gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300 ring-1 ring-emerald-400/25">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-4.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 9 3 3 4.5-4.5M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-white">Respons JSON mentah</span>
                        <span class="block font-mono text-xs text-slate-500">https://dummyjson.com/recipes/{{ $recipe['id'] ?? 1 }}</span>
                    </span>
                </span>
                <svg data-json-chevron xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5 shrink-0 text-slate-400 transition-transform duration-300" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>

            <div id="json-panel" data-json-panel class="grid grid-rows-[0fr] border-t border-white/10 opacity-0 transition-[grid-template-rows,opacity] duration-700 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] [&>*]:overflow-hidden">
                <div>
                <div class="flex items-center justify-between gap-3 border-b border-white/5 px-6 py-2.5">
                    <span class="font-mono text-xs text-slate-600">recipes[0]</span>
                    <button type="button" data-copy-json
                            class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-300 ring-1 ring-white/10 transition duration-300 hover:scale-105 hover:bg-white/10 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/>
                        </svg>
                        <span data-copy-label>Salin JSON</span>
                    </button>
                </div>
                <pre class="max-h-96 overflow-auto bg-slate-950/70 p-6 font-mono text-xs leading-relaxed text-slate-300"><code>{{ json_encode(['recipes' => [$recipe]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-white/5">
        <div data-reveal="up" class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-2 px-6 py-6 text-xs text-slate-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} Recipes &middot; data dari DummyJSON</p>
            <p class="font-mono">#{{ $recipe['id'] ?? 0 }} &middot; {{ count($ingredients) }} bahan &middot; {{ count($instructions) }} langkah</p>
        </div>
    </footer>
</div>

<button type="button" data-to-top aria-label="Kembali ke atas"
        class="to-top fixed right-5 bottom-5 z-50 flex size-11 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-rose-500 text-white shadow-xl shadow-orange-500/30 ring-1 ring-white/20">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0-6.75 6.75M12 4.5l6.75 6.75"/>
    </svg>
</button>

<script>
    (() => {
        const RECIPE = {{ Illuminate\Support\Js::from($recipe) }};
        const RATING_PERCENT = {{ round($ratingPercent, 2) }};
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));
        const formatNumber = (value, decimals) => value.toLocaleString('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        });

        const countUp = (el) => {
            if (el.dataset.counted) return;
            el.dataset.counted = 'true';

            const target = parseFloat(el.dataset.countTo ?? '0');
            const decimals = parseInt(el.dataset.decimals ?? '0', 10);
            const suffix = el.dataset.suffix ?? '';
            const duration = 1500;
            let start = null;

            const frame = (timestamp) => {
                if (start === null) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);

                el.textContent = formatNumber(target * easeOutExpo(progress), decimals) + suffix;

                if (progress < 1) requestAnimationFrame(frame);
            };

            requestAnimationFrame(frame);
        };

        const revealables = Array.from(document.querySelectorAll('[data-reveal]'));
        const stars = document.querySelector('[data-stars-fill]');

        const fillStars = () => {
            if (stars) stars.style.width = RATING_PERCENT + '%';
        };

        if (reduceMotion) {
            revealables.forEach((el) => el.classList.add('is-revealed'));
            fillStars();
            document.querySelectorAll('[data-counter]').forEach((el) => { el.dataset.counted = 'true'; });
        } else {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    entry.target.classList.add('is-revealed');
                    entry.target.querySelectorAll('[data-counter]').forEach(countUp);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

            revealables.forEach((el) => observer.observe(el));
        }

        const progress = document.querySelector('[data-scroll-progress]');
        const header = document.querySelector('[data-header]');
        const toTop = document.querySelector('[data-to-top]');
        const parallax = Array.from(document.querySelectorAll('[data-parallax]'));
        let ticking = false;

        const onScroll = () => {
            const y = window.scrollY;
            const max = document.documentElement.scrollHeight - window.innerHeight;

            progress?.style.setProperty('--scroll', max > 0 ? (y / max).toFixed(4) : '0');
            header?.classList.toggle('is-scrolled', y > 16);
            toTop?.classList.toggle('is-visible', y > 560);

            parallax.forEach((el) => {
                const frame = el.parentElement.getBoundingClientRect();

                if (frame.bottom < -240 || frame.top > window.innerHeight + 240) return;

                const speed = parseFloat(el.dataset.parallax) || 0.1;
                const shift = (frame.top + frame.height / 2 - window.innerHeight / 2) * -speed;

                el.style.transform = `translate3d(0, ${shift.toFixed(2)}px, 0) scale(1.12)`;
            });

            ticking = false;
        };

        const requestTick = () => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(onScroll);
        };

        window.addEventListener('scroll', requestTick, { passive: true });
        window.addEventListener('resize', requestTick, { passive: true });
        onScroll();

        document.querySelectorAll('[data-spotlight]').forEach((card) => {
            let frame = null;

            card.addEventListener('pointermove', (event) => {
                if (frame) return;

                frame = requestAnimationFrame(() => {
                    frame = null;
                    const rect = card.getBoundingClientRect();

                    card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
                    card.style.setProperty('--my', `${event.clientY - rect.top}px`);
                });
            });
        });

        toTop?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
        });

        const storageKey = `recipe-checklist-${RECIPE.id ?? 0}`;
        const boxes = Array.from(document.querySelectorAll('[data-ingredient-checkbox]'));
        const bar = document.querySelector('[data-progress-bar]');
        const counter = document.querySelector('[data-progress-current]');
        const toggleAll = document.querySelector('[data-toggle-all]');

        const refresh = () => {
            const checked = boxes.filter((box) => box.checked);
            const total = boxes.length || 1;

            if (bar) bar.style.width = `${(checked.length / total) * 100}%`;
            if (counter) counter.textContent = checked.length;
            if (toggleAll) toggleAll.textContent = checked.length === boxes.length ? 'Hapus centang' : 'Centang semua';

            try {
                localStorage.setItem(storageKey, JSON.stringify(checked.map((box) => box.value)));
            } catch {
                return;
            }
        };

        try {
            const saved = JSON.parse(localStorage.getItem(storageKey) ?? '[]');
            boxes.forEach((box) => { box.checked = saved.includes(box.value); });
        } catch {
            console.warn('Checklist resep tidak dapat dipulihkan.');
        }

        boxes.forEach((box) => box.addEventListener('change', refresh));

        toggleAll?.addEventListener('click', () => {
            const shouldCheck = boxes.some((box) => !box.checked);
            boxes.forEach((box) => { box.checked = shouldCheck; });
            refresh();
        });

        refresh();

        const jsonToggle = document.querySelector('[data-json-toggle]');
        const jsonPanel = document.querySelector('[data-json-panel]');
        const jsonChevron = document.querySelector('[data-json-chevron]');

        jsonToggle?.addEventListener('click', () => {
            const isOpen = jsonPanel.getAttribute('data-open') === 'true';

            jsonPanel.setAttribute('data-open', String(!isOpen));
            jsonPanel.classList.toggle('grid-rows-[0fr]', isOpen);
            jsonPanel.classList.toggle('grid-rows-[1fr]', !isOpen);
            jsonPanel.classList.toggle('opacity-0', isOpen);
            jsonPanel.classList.toggle('opacity-100', !isOpen);
            jsonToggle.setAttribute('aria-expanded', String(!isOpen));
            jsonChevron?.classList.toggle('-rotate-180', !isOpen);
        });

        const flash = (button, text) => {
            const label = button.querySelector('[data-copy-label]') ?? button;
            const original = label.textContent;

            label.textContent = text;
            setTimeout(() => { label.textContent = original; }, 1600);
        };

        const copyHandler = (resolveText, okLabel = 'Tersalin!', failLabel = 'Gagal') => async (event) => {
            const button = event.currentTarget;

            try {
                await navigator.clipboard.writeText(resolveText(button));
                flash(button, okLabel);
            } catch {
                flash(button, failLabel);
            }
        };

        document.querySelector('[data-copy-json]')
            ?.addEventListener('click', copyHandler(() => JSON.stringify({ recipes: [RECIPE] }, null, 2), 'JSON tersalin!', 'Gagal'));

        document.querySelector('[data-copy-text]')
            ?.addEventListener('click', copyHandler((button) => button.dataset.copyText ?? '', 'Bahan tersalin!', 'Gagal'));
    })();
</script>
</body>
</html>
