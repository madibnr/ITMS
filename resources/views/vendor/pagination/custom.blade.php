@if ($paginator->hasPages())
<nav class="pagination-nav" aria-label="Pagination">
    <div class="pagination-info">
        Menampilkan
        <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </div>
    <ul class="pagination-list">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="pagination-item disabled" aria-disabled="true">
                <span class="pagination-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </span>
            </li>
        @else
            <li class="pagination-item">
                <a class="pagination-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)

            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="pagination-item disabled" aria-disabled="true">
                    <span class="pagination-btn pagination-dots">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination-item active" aria-current="page">
                            <span class="pagination-btn">{{ $page }}</span>
                        </li>
                    @else
                        <li class="pagination-item">
                            <a class="pagination-btn" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="pagination-item">
                <a class="pagination-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            </li>
        @else
            <li class="pagination-item disabled" aria-disabled="true">
                <span class="pagination-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif
