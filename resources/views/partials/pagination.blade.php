@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="flex size-9 items-center justify-center rounded-xl text-slate-400 opacity-50" aria-disabled="true">&larr;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link" aria-label="Halaman sebelumnya">&larr;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1.5 text-slate-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="pagination-link is-active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link" aria-label="Halaman berikutnya">&rarr;</a>
        @else
            <span class="flex size-9 items-center justify-center rounded-xl text-slate-400 opacity-50" aria-disabled="true">&rarr;</span>
        @endif
    </nav>
@endif