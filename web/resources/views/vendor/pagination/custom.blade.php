@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination-wrapper">
        <div class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angles-left"></i>
                </span>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angle-left"></i>
                </span>
            @else
                <a href="{{ $paginator->url(1) }}" class="custom-page-btn" rel="prev" aria-label="Primera página">
                    <i class="fas fa-angles-left"></i>
                </a>
                <a href="{{ $paginator->previousPageUrl() }}" class="custom-page-btn" rel="prev" aria-label="Anterior">
                    <i class="fas fa-angle-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="custom-page-dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="custom-page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="custom-page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="custom-page-btn" rel="next" aria-label="Siguiente">
                    <i class="fas fa-angle-right"></i>
                </a>
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="custom-page-btn" aria-label="Última página">
                    <i class="fas fa-angles-right"></i>
                </a>
            @else
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angle-right"></i>
                </span>
                <span class="custom-page-btn disabled" aria-disabled="true">
                    <i class="fas fa-angles-right"></i>
                </span>
            @endif
        </div>

        {{-- Info de resultados --}}
        <div class="custom-pagination-info">
            Mostrando {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
        </div>
    </nav>
@endif
