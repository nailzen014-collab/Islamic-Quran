@php
    $columns = 'sm:grid-cols-2 xl:grid-cols-3';
@endphp

<div data-filter-grid class="grid grid-cols-1 gap-5 {{ $columns }}">

    @forelse ($recipes as $index => $recipe)
        @include('partials.recipe-card', ['recipe' => $recipe, 'priority' => $index < 3])
    @empty
        <div class="skeleton col-span-full h-48 rounded-lg border border-slate-200/50 mx-auto my-8" style="width: 100%;"></div>
    @endforelse
</div>

@if ($recipes->hasPages())
    <div data-pagination class="mt-8 flex justify-center">
        {{ $recipes->onEachSide(1)->links('partials.pagination') }}
    </div>

    @if ($recipes->hasMorePages())
        <div data-infinite-sentinel="{{ $recipes->nextPageUrl() }}">
            <span class="loading-text">Memuat lebih banyak...</span>
        </div>
    @endif
@endif