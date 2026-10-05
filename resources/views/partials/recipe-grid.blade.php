@php
    $columns = 'sm:grid-cols-2 xl:grid-cols-3';
@endphp

<div data-filter-grid class="grid grid-cols-1 gap-5 {{ $columns }}">
    @forelse ($recipes as $index => $recipe)
        @include('partials.recipe-card', ['recipe' => $recipe, 'priority' => $index < 3])
    @empty
        <div class="col-span-full">
            <div class="surface flex flex-col items-center gap-3 px-6 py-16 text-center" data-reveal>
                <span class="flex size-14 items-center justify-center rounded-2xl bg-ember-500/10 text-ember-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0Z"/>
                    </svg>
                </span>
                <h3 class="text-lg font-semibold">Belum ada resep yang cocok</h3>
                <p class="max-w-sm text-sm text-slate-500">Coba longgarkan filter, atau cari dengan kata kunci lain.</p>
                <button type="button" data-filter-clear class="btn-ghost">Reset filter</button>
            </div>
        </div>
    @endforelse
</div>

@if ($recipes->hasPages())
    <div data-pagination class="mt-8 flex justify-center">
        {{ $recipes->onEachSide(1)->links('partials.pagination') }}
    </div>

    @if ($recipes->hasMorePages())
        <div data-infinite-sentinel="{{ $recipes->nextPageUrl() }}">
            <span>Memuat lebih banyak...</span>
        </div>
    @endif
@endif