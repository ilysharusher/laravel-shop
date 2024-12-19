@if ($paginator->hasPages())
    <nav>
        <ul class="flex flex-wrap items-center justify-center gap-3">
            @if (!$paginator->onFirstPage())
                <li>
                    <a class="block p-3 text-white hover:text-pink text-sm font-black leading-none" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="text-body/50 text-sm font-black leading-none" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li class="active" aria-current="page">
                                <span class="block p-3 pointer-events-none text-pink text-sm font-black leading-none">{{ $page }}</span>
                            </li>
                        @else
                            <li><a class="block p-3 text-white hover:text-pink text-sm font-black leading-none" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a class="block p-3 text-white hover:text-pink text-sm font-black leading-none" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
