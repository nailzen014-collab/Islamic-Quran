@extends('layouts.app')

@section('title', $recipe->name)
@section('description', $recipe->summary)

@section('content')
    <article class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6">
        <nav aria-label="Remah roti" class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('beranda') }}" class="link-underline hover:text-ember-600">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('jelajah') }}" class="link-underline hover:text-ember-600">Jelajah</a>
            <span aria-hidden="true">/</span>
            <span class="truncate text-slate-700 dark:text-slate-300">{{ $recipe->name }}</span>
        </nav>

        <section class="mt-5 overflow-hidden rounded-[2rem] border border-black/5 bg-white/70 backdrop-blur-xl dark:border-white/8 dark:bg-white/4">
            <div class="grid lg:grid-cols-2">
                <div class="relative aspect-video overflow-hidden lg:aspect-auto lg:min-h-[28rem]" data-parallax-scope>
                    @if ($recipe->image)
                        <img src="{{ $recipe->image }}" alt="{{ $recipe->name }}" fetchpriority="high"
                             class="absolute inset-0 size-full scale-110 object-cover" data-parallax="0.08">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/10 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:via-transparent lg:to-slate-950/60"></div>

                    <div class="absolute inset-x-4 bottom-4 flex flex-wrap items-center gap-2">
                        <span class="badge border-white/20 bg-slate-950/60 text-white backdrop-blur">{{ $recipe->cuisine ?? 'Tanpa asal' }}</span>
                        @foreach (array_slice($recipe->meal_types ?? [], 0, 2) as $type)
                            <span class="badge border-white/20 bg-slate-950/60 text-white backdrop-blur">{{ $type }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-6 p-6 sm:p-10">
                    <div class="flex flex-wrap items-center gap-2" data-reveal>
                        <span class="badge badge-ember font-mono">#{{ $recipe->external_id }}</span>
                        <span class="badge badge-{{ strtolower($recipe->difficulty ?? 'neutral') }}">{{ $recipe->difficulty ?? 'Umum' }}</span>
                        @auth
                            <button type="button"
                                    data-favorite="{{ route('favorites.store', $recipe) }}"
                                    data-recipe="{{ $recipe->id }}"
                                    data-favorite-label="{{ $isFavorited ? 'Hapus dari favorit' : 'Simpan ke favorit' }}"
                                    aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
                                    class="chip ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>
                                <span data-favorite-count="{{ $recipe->id }}">{{ $recipe->favorites_count ?? 0 }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="chip ml-auto">Masuk untuk menyimpan</a>
                        @endauth
                    </div>

                    <div data-reveal style="--reveal-delay:80ms">
                        <h1 class="display-title text-3xl text-slate-900 sm:text-4xl lg:text-5xl dark:text-white">{{ $recipe->name }}</h1>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $recipe->summary }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4" data-reveal style="--reveal-delay:140ms">
                        <div class="flex items-center gap-2">
                            <span class="relative h-5 w-24">
                                <span class="star-row">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5" aria-hidden="true">
                                            <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                        </svg>
                                    @endfor
                                </span>
                                <span class="star-fill" style="width: {{ round(($recipe->rating / 5) * 100, 1) }}%">
                                    <span class="star-row">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                                <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                            </svg>
                                        @endfor
                                    </span>
                                </span>
                            </span>
                            <span class="text-sm">
                                <span class="font-semibold">{{ number_format($recipe->rating, 1, ',', '.') }}</span>
                                <span class="text-slate-500">/ 5</span>
                                <span class="block text-xs text-slate-500">{{ number_format($recipe->review_count, 0, ',', '.') }} ulasan</span>
                            </span>
                        </div>

                        <a href="{{ route('recipes.cook', $recipe) }}" class="btn-primary ml-auto">
                            Mode masak
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 0 1 0 1.971l-11.54 6.347a1.125 1.125 0 0 1-1.667-.985V5.653Z"/>
                            </svg>
                        </a>
                    </div>

                    <dl class="grid grid-cols-2 gap-3 sm:grid-cols-4" data-reveal style="--reveal-delay:200ms">
                        @foreach ([
                            ['Prep', $recipe->prep_time_minutes, 'menit', 'text-sky-500'],
                            ['Masak', $recipe->cook_time_minutes, 'menit', 'text-rose-500'],
                            ['Porsi', $recipe->servings, 'orang', 'text-emerald-500'],
                            ['Kalori', $recipe->calories_per_serving, 'kkal', 'text-fuchsia-500'],
                        ] as $metric)
                            <div class="metric" data-spotlight>
                                <span class="text-2xl font-semibold {{ $metric[3] }} tabular-nums" data-count-to="{{ $metric[1] }}">{{ $metric[1] }}</span>
                                <span class="min-w-0">
                                    <span class="block text-[11px] tracking-wider text-slate-500 uppercase">{{ $metric[0] }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ $metric[2] }}</span>
                                </span>
                            </div>
                        @endforeach
                    </dl>

                    @if (filled($recipe->tags))
                        <div class="flex flex-wrap gap-2" data-reveal style="--reveal-delay:260ms">
                            @foreach ($recipe->tags as $tag)
                                <a href="{{ route('jelajah', ['tags' => [$tag]]) }}" class="chip">#{{ $tag }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-5" data-servings="{{ $recipe->servings }}">
            <section class="lg:col-span-2" data-reveal>
                <div class="surface h-full rounded-[1.75rem] p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold">Bahan-bahan</h2>
                            <p class="mt-1 text-sm text-slate-500" data-ingredient-progress-label>0 dari {{ count($recipe->ingredients ?? []) }} bahan</p>
                        </div>

                        <div class="flex items-center gap-1 rounded-full border border-black/10 p-1 dark:border-white/12">
                            <button type="button" data-servings-minus class="icon-button !size-7" aria-label="Kurangi porsi">&minus;</button>
                            <span class="min-w-16 text-center text-sm font-semibold tabular-nums">
                                <span data-servings-display>{{ $recipe->servings }}</span> porsi
                            </span>
                            <button type="button" data-servings-plus class="icon-button !size-7" aria-label="Tambah porsi">+</button>
                        </div>
                    </div>

                    <div class="bar-track mt-4">
                        <div class="bar-fill" data-servings-progress style="width: 0%"></div>
                    </div>

                    <ul class="mt-5 space-y-1.5">
                        @foreach ($recipe->ingredients ?? [] as $index => $ingredient)
                            <li data-ingredient-item data-checked="false">
                                <div data-ingredient-row class="flex items-center gap-3 rounded-xl px-2 py-2">
                                    <input type="checkbox" id="ing-{{ $index }}" data-ingredient-checkbox class="sr-only">
                                    <span class="checkbox">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-3" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </span>
                                    <label for="ing-{{ $index }}" class="ingredient-name min-w-0 flex-1 cursor-pointer text-sm text-slate-700 dark:text-slate-300">
                                        <span class="mr-2 font-mono text-xs text-slate-400">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                        <span data-ingredient-quantity data-ingredient-quantity="{{ $ingredient }}">{{ $ingredient }}</span>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <button type="button" data-toggle-all class="btn-ghost flex-1">Centang semua</button>
                        <button type="button" class="btn-ghost flex-1"
                                x-data
                                x-on:click="$el.textContent = (() => { navigator.clipboard?.writeText(@js($recipe->ingredients)); return 'Tersalin!'; })()"
                                x-init="setTimeout(() => $el.textContent = @js('Salin bahan'), 1800)">
                            Salin bahan
                        </button>
                    </div>

                    @auth
                        <div class="mt-5 border-t border-black/5 pt-5 dark:border-white/8">
                            <p class="field-label">Simpan ke koleksi</p>

                            @if ($userCollections->isEmpty())
                                <a href="{{ route('koleksi.create') }}" class="btn-ghost w-full">Buat koleksi dulu</a>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($userCollections as $collection)
                                        <label class="chip cursor-pointer">
                                            <input type="radio" name="collection_option" value="{{ $collection->id }}" class="sr-only">
                                            {{ $collection->name }}
                                            <span class="chip-count">{{ $collection->recipes_count }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <button type="button"
                                        data-collection-toggle="{{ route('koleksi.resep', $userCollections->first()) }}"
                                        data-recipe="{{ $recipe->id }}"
                                        class="btn-primary mt-3 w-full">
                                    Simpan ke koleksi
                                </button>
                            @endif
                        </div>
                    @endauth
                </div>
            </section>

            <section class="lg:col-span-3" data-reveal style="--reveal-delay:100ms">
                <div class="surface h-full rounded-[1.75rem] p-6">
                    <h2 class="text-lg font-semibold">Langkah memasak</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ count($recipe->instructions ?? []) }} langkah berurutan</p>

                    <ol class="relative mt-6 space-y-5 before:absolute before:top-2 before:bottom-2 before:left-4 before:w-px before:bg-gradient-to-b before:from-ember-500/50 before:via-slate-500/20 before:to-transparent">
                        @foreach ($recipe->instructions ?? [] as $index => $step)
                            <li class="reveal-step relative flex gap-4">
                                <span class="step-marker relative z-10 flex size-8 shrink-0 items-center justify-center rounded-xl border border-black/10 bg-white text-xs font-bold text-ember-600 dark:border-white/12 dark:bg-slate-900 dark:text-ember-400">
                                    {{ $index + 1 }}
                                </span>
                                <p class="pt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $step }}</p>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('recipes.cook', $recipe) }}" class="btn-ghost">Buka mode masak</a>
                        <button type="button" class="btn-ghost"
                                x-data
                                x-on:click="$el.textContent = (() => { navigator.clipboard?.writeText(@js($recipe->instructions)); return 'Tersalin!'; })()"
                                x-init="setTimeout(() => $el.textContent = @js('Salin langkah'), 1800)">
                            Salin langkah
                        </button>
                    </div>

                    <div x-data="{ open: false }" class="mt-6 overflow-hidden rounded-2xl border border-black/5 dark:border-white/8" :class="open ? 'accordion is-open' : 'accordion'">
                        <button type="button" x-on:click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-sm font-medium transition hover:bg-ember-500/5">
                            <span>Data JSON mentah</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="accordion-icon size-4 text-slate-400" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>

                        <div class="json-panel" :class="open ? 'is-open' : ''">
                            <div>
                                <pre class="max-h-80 overflow-auto bg-slate-950/85 p-4 font-mono text-[11px] leading-relaxed text-slate-300 dark:bg-black/40"><code>{{ json_encode($recipe->only(['external_id', 'name', 'cuisine', 'difficulty', 'prep_time_minutes', 'cook_time_minutes', 'servings', 'calories_per_serving', 'meal_types', 'tags']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>

    <section class="mx-auto w-full max-w-7xl px-4 py-14 sm:px-6">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2" data-reveal>
                <div class="surface rounded-[1.75rem] p-6">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="section-eyebrow">Ulasan pembaca</p>
                            <h2 class="display-title mt-2 text-2xl">{{ $recipe->reviews->count() }} ulasan untuk {{ Str::limit($recipe->name, 30) }}</h2>
                        </div>
                        <span class="text-sm text-slate-500">Rating sumber: {{ number_format($recipe->rating, 1, ',', '.') }}</span>
                    </div>

                    @if (count($distribution) > 0 && array_sum($distribution) > 0)
                        <div class="mt-6 grid gap-4 sm:grid-cols-[auto_1fr] sm:items-center">
                            <div class="text-center">
                                <p class="display-title text-5xl">
                                    <span data-count-to="{{ round(array_sum($distribution) ? collect($distribution)->sum() : 0, 0) }}" data-decimals="0">0</span>
                                </p>
                                <p class="text-xs text-slate-500">ulasan lokal</p>
                            </div>

                            <div class="space-y-1.5">
                                @php $maxCount = max(1, collect($distribution)->max()); @endphp
                                @foreach ($distribution as $star => $count)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="w-8 shrink-0 tabular-nums">{{ $star }} ★</span>
                                        <span class="bar-track flex-1 !h-2">
                                            <span class="bar-fill bar-fill-sky block" style="width: {{ round($count / $maxCount * 100) }}%"></span>
                                        </span>
                                        <span class="w-6 shrink-0 text-right tabular-nums text-slate-500">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <ul class="mt-8 space-y-4">
                        @forelse ($recipe->reviews as $review)
                            <li class="rounded-2xl border border-black/5 p-4 dark:border-white/8" data-reveal>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="avatar !size-9 avatar-{{ $review->user?->avatar_color ?? 'orange' }} text-[11px]">{{ Str::upper(Str::substr($review->author_name, 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium">{{ $review->author_name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>

                                    <span class="ml-auto inline-flex items-center gap-0.5 text-amber-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" class="size-3.5" aria-hidden="true">
                                                <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                            </svg>
                                        @endfor
                                    </span>

                                    @if ($review->is_recipe_owner_cook)
                                        <span class="badge badge-emerald">Sudah masak</span>
                                    @endif
                                </div>

                                <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $review->body }}</p>

                                @if (auth()->check() && ($review->user_id === auth()->id() || auth()->user()->email === 'nadia@resep.id'))
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="mt-3">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger">Hapus ulasan</button>
                                    </form>
                                @endif
                            </li>
                        @empty
                            <li class="text-sm text-slate-500">Belum ada ulasan lokal untuk resep ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="space-y-5" data-reveal style="--reveal-delay:100ms">
                <form action="{{ route('reviews.store', $recipe) }}" method="POST" class="surface rounded-[1.75rem] p-6">
                    @csrf
                    <h2 class="text-lg font-semibold">Tulis ulasan</h2>
                    <p class="mt-1 text-sm text-slate-500">Bagikan hasil masakmu.</p>

                    @unless (auth()->check())
                        <div class="mt-4">
                            <label for="author_name" class="field-label">Namamu</label>
                            <input id="author_name" name="author_name" type="text" value="{{ old('author_name') }}" class="field" required>
                        </div>
                    @endunless

                    <div class="mt-4" x-data="ratingInput" data-value="{{ old('rating', 5) }}">
                        <span class="field-label">Rating</span>
                        <input type="hidden" name="rating" value="{{ old('rating', 5) }}">
                        <div class="rating-stars-interactive flex gap-1">
                            @for ($star = 1; $star <= 5; $star++)
                                <button type="button" @click="set({{ $star }})"
                                        @mouseenter="hover = {{ $star }}" @mouseleave="hover = 0"
                                        :aria-label="'Beri ' + {{ $star }} + ' bintang'">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="size-7"
                                         :fill="(hover || value) >= {{ $star }} ? '#f59e0b' : 'none'"
                                         :class="(hover || value) >= {{ $star }} ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">
                                        <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="body" class="field-label">Ulasan</label>
                        <textarea id="body" name="body" rows="4" class="field" required minlength="10" placeholder="Ceritakan pengalamanmu masak resep ini...">{{ old('body') }}</textarea>
                    </div>

                    @auth
                        <label class="mt-3 flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                            <input type="checkbox" name="is_recipe_owner_cook" value="1" class="checkbox" @checked(old('is_recipe_owner_cook'))>
                            Saya sudah mempraktikkan resep ini
                        </label>
                    @endauth

                    <button type="submit" class="btn-primary mt-4 w-full">Kirim ulasan</button>
                </form>

                <div class="surface rounded-[1.75rem] p-6">
                    <h2 class="text-sm font-semibold">Info sumber</h2>
                    <dl class="mt-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-mono text-slate-500">external_id</dt>
                            <dd class="font-mono">{{ $recipe->external_id }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-mono text-slate-500">cuisine</dt>
                            <dd>{{ $recipe->cuisine ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-mono text-slate-500">total</dt>
                            <dd>{{ $recipe->total_minutes }} menit</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-mono text-slate-500">api</dt>
                            <dd class="truncate font-mono text-slate-500">dummyjson.com/recipes/{{ $recipe->external_id }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="mx-auto w-full max-w-7xl px-4 pb-6 sm:px-6" data-parallax-scope>
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <p class="section-eyebrow">Mungkin kamu suka</p>
                    <h2 class="display-title mt-2 text-3xl">Resep terkait</h2>
                </div>
                <a href="{{ route('jelajah', ['cuisine' => $recipe->cuisine]) }}" class="btn-ghost">Semua {{ $recipe->cuisine }}</a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($related as $index => $item)
                    <div data-reveal style="--reveal-delay: {{ $index * 80 }}ms">
                        @include('partials.recipe-card', ['recipe' => $item, 'compact' => true])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection