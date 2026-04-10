@if ($paginator->hasPages())
<div class="pagination-wrap">
    <div class="pagination-info">
        Menampilkan <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </div>
    <div class="pagination-links">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="pg-btn pg-arrow disabled">&#8592;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pg-btn pg-arrow">&#8592;</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg-dots">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pg-btn pg-arrow">&#8594;</a>
        @else
            <span class="pg-btn pg-arrow disabled">&#8594;</span>
        @endif
    </div>
</div>
@endif
