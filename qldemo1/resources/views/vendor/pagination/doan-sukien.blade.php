@if ($paginator->hasPages())
  <nav class="sv-pagination" aria-label="Pagination">
    <ul class="pagination sv-pagination__list mb-0">

      {{-- Prev --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link sv-page-link" aria-hidden="true">‹</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link sv-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">‹</a>
        </li>
      @endif

      {{-- Pages --}}
      @foreach ($elements as $element)
        {{-- Dots --}}
        @if (is_string($element))
          <li class="page-item disabled" aria-disabled="true">
            <span class="page-link sv-page-link">{{ $element }}</span>
          </li>
        @endif

        {{-- Array page => url --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link sv-page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link sv-page-link" href="{{ $url }}" aria-label="Trang {{ $page }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link sv-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">›</a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link sv-page-link" aria-hidden="true">›</span>
        </li>
      @endif

    </ul>
  </nav>
@endif
