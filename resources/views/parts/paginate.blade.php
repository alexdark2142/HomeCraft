<div id="app" class="container-paginate">
    <!-- Full version pagination -->
    <ul class="paginate full-version">
        @if ($products->currentPage() < $products->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $products->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif
        @php
            $start = max($products->currentPage() - 2, 1);
            $end = min($start + 4, $products->lastPage());
        @endphp
        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($products->currentPage() == $i) active @endif">
                <a href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
        @if ($end < $products->lastPage())
            <li class="paginate__dots">...</li>
            <li class="paginate__numbers">
                <a href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
            </li>
        @endif
        @if ($products->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $products->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

    <!-- Mobile version pagination -->
    <ul class="paginate mobile-version">
        @if ($products->currentPage() > 1)
            @php
                $previousPageNumber = $products->currentPage() - 1;
            @endphp
            <li class="paginate__btn">
                <a href="{{ $products->previousPageUrl() }}">
                    &lt; {{ $previousPageNumber }}
                </a>
            </li>
        @else

        @endif
        <li class="paginate__numbers active">
            <a href="{{ $products->url($products->currentPage()) }}">
                {{ $products->currentPage() }}
            </a>
        </li>
        @if ($products->hasMorePages())
            @php
                $nextPageNumber = $products->currentPage() + 1;
            @endphp
            <li class="paginate__btn">
                <a href="{{ $products->nextPageUrl() }}">
                    {{ $nextPageNumber }} &gt;
                </a>
            </li>
        @endif
    </ul>
</div>
