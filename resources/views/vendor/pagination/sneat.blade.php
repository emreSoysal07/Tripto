@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            {{-- Ilk Sayfaya Git (First Page) --}}
            @if ($paginator->onFirstPage())
                <li class="page-item first disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></span>
                </li>
                <li class="page-item prev disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item first">
                    <a class="page-link" href="{{ $paginator->getUrlRange(1, 1)[1] }}"><i class="tf-icon bx bx-chevrons-left"></i></a>
                </li>
                <li class="page-item prev">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="tf-icon bx bx-chevron-left"></i></a>
                </li>
            @endif

            {{-- Sayfa Numaraları --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Ayırıcı --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Link Dizileri --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Sonraki ve Son Sayfa --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="tf-icon bx bx-chevron-right"></i></a>
                </li>
                <li class="page-item last">
                    <a class="page-link" href="{{ $paginator->getUrlRange($paginator->lastPage(), $paginator->lastPage())[$paginator->lastPage()] }}"><i class="tf-icon bx bx-chevrons-right"></i></a>
                </li>
            @else
                <li class="page-item next disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevron-right"></i></span>
                </li>
                <li class="page-item last disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif