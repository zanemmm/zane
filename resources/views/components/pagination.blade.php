@if ($paginator->hasPages())
    <div id="pagination">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">&longleftarrow;</li>
                <li class="disabled">&leftarrow;</li>
            @else
                <li><a href="{{ url('?page=1') }}" rel="first" title="第一页" data-navigo>&longleftarrow;</a></li>
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" title="上一页" data-navigo>&leftarrow;</a></li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled">{{ $element }}</li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @php
                        $lastPage    = $paginator->lastPage();
                        $currentPage = $paginator->currentPage();
                        $endPage     = ($currentPage + 3 < 7) ? 7 : $currentPage + 3;
                        $endPage     = ($endPage > $lastPage) ? $lastPage : $endPage;
                        $startPage   = $endPage - 6;
                        $startPage   = ($startPage >= 1) ? $startPage : 1;
                    @endphp
                    @for($page=$startPage; $page<=$endPage; $page++)
                        @if ($page == $paginator->currentPage())
                            <li class="number active">{{ $page }}</li>
                        @else
                            <li class="number"><a href="{{ $element[$page] }}" title="第{{ $page }}页" data-navigo>{{ $page }}</a></li>
                        @endif
                    @endfor
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" title="下一页" data-navigo>&rightarrow;</a></li>
                <li><a href="{{ url('?page=' . $paginator->lastPage()) }}" rel="last" title="最后一页" data-navigo>&longrightarrow;</a></li>
            @else
                <li class="disabled">&rightarrow;</li>
                <li class="disabled">&longrightarrow;</li>
            @endif
        </ul>
    </div>
@endif