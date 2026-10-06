@if ($paginator->hasPages())
    <nav class="flex w-full max-w-sm items-center justify-between gap-3" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pagination-link cursor-not-allowed opacity-40" aria-disabled="true">← <span class="hidden sm:inline">Sebelumnya</span></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link gap-1.5">← <span class="hidden sm:inline">Sebelumnya</span></a>
        @endif

        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link gap-1.5"><span class="hidden sm:inline">Berikutnya</span> →</a>
        @else
            <span class="pagination-link cursor-not-allowed opacity-40" aria-disabled="true"><span class="hidden sm:inline">Berikutnya</span> →</span>
        @endif
    </nav>
@endif
