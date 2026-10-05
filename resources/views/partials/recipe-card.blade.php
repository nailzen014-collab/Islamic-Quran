@props(['recipe', 'compact' => false, 'priority' => false])

<article data-card-entry data-spotlight class="card group">
    <div class="card-media">
        <img src="{{ $recipe->image }}" alt="{{ $recipe->name }}" loading="lazy" decoding="async"
             @if ($priority) fetchpriority="high" @endif>

        <div class="card-overlay"></div>

        <div class="absolute inset-x-3 top-3 flex items-start justify-between gap-2">
            <span class="badge badge-{{ strtolower($recipe->difficulty ?? 'neutral') }} shadow-sm backdrop-blur">
                {{ $recipe->difficulty ?? 'Umum' }}
            </span>

            @auth
                <button type="button"
                        data-favorite="{{ route('favorites.store', $recipe) }}"
                        data-recipe="{{ $recipe->id }}"
                        data-favorite-label="{{ $recipe->isFavoritedBy(auth()->user()) ? 'Hapus dari favorit' : 'Simpan ke favorit' }}"
                        aria-pressed="{{ $recipe->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}"
                        aria-label="{{ $recipe->isFavoritedBy(auth()->user()) ? 'Hapus dari favorit' : 'Simpan ke favorit' }}"
                        class="heart-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                </button>
            @else
                <a href="{{ route('login') }}" aria-label="Masuk untuk menyimpan favorit"
                   class="heart-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                </a>
            @endauth
        </div>

        @if (! $compact)
            <div class="absolute inset-x-3 bottom-3 flex items-center gap-2 text-[11px] font-medium text-white/90 opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-950/60 px-2 py-1 backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    {{ $recipe->total_minutes }} menit
                </span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-950/60 px-2 py-1 backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                    {{ $recipe->servings }} porsi
                </span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-3 p-4">
        <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
            <span class="font-medium text-ember-600 dark:text-ember-400">{{ $recipe->cuisine ?? 'Tanpa asal' }}</span>
            @if (! $compact && filled($recipe->meal_types))
                <span aria-hidden="true">&middot;</span>
                <span class="truncate">{{ implode(', ', array_slice($recipe->meal_types, 0, 2)) }}</span>
            @endif
        </div>

        <h3 class="line-clamp-2 text-sm font-semibold leading-snug tracking-tight">
            <a href="{{ route('recipes.show', $recipe) }}" class="link-underline">{{ $recipe->name }}</a>
        </h3>

        <div class="mt-auto flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                <span class="relative h-4 w-20">
                    <span class="star-row">
                        @for ($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true">
                                <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                            </svg>
                        @endfor
                    </span>
                    <span class="star-fill" style="width: {{ round(($recipe->rating / 5) * 100, 1) }}%">
                        <span class="star-row">
                            @for ($i = 0; $i < 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 shrink-0" aria-hidden="true">
                                    <path d="m12 17.27 5.18 3.13-1.37-5.89 4.57-3.96-6.03-.52L12 4.5 9.65 10.03l-6.03.52 4.57 3.96-1.37 5.89L12 17.27Z"/>
                                </svg>
                            @endfor
                        </span>
                    </span>
                </span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ number_format($recipe->rating, 1, ',', '.') }}</span>
                <span class="text-slate-400">({{ number_format($recipe->review_count, 0, ',', '.') }})</span>
            </span>

            <span class="text-[11px] font-medium text-slate-400">
                <span data-favorite-count="{{ $recipe->id }}">{{ $recipe->favorites_count ?? 0 }}</span>
                <span class="sr-only">favorit</span>
            </span>
        </div>
    </div>
</article>