<div id="app" class="container-paginate">
    <!-- Full version pagination -->
    <ul class="paginate full-version">
        @if ($products->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $products->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($products->currentPage() - 2, 1);
            $end = min($start + 4, $products->lastPage());

            // Adjust the range if we're too close to the end or the beginning
            if ($products->currentPage() + 2 > $products->lastPage()) {
                $start = max($products->lastPage() - 4, 1);
                $end = $products->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $products->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($products->currentPage() == $i) active @endif">
                <a href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $products->lastPage())
            @if ($end < $products->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
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
        @if ($products->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $products->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($products->currentPage() - 1, 1);
            $end = min($start + 2, $products->lastPage());

            if ($products->currentPage() + 1 > $products->lastPage()) {
                $start = max($products->lastPage() - 2, 1);
                $end = $products->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $products->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($products->currentPage() == $i) active @endif">
                <a href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $products->lastPage())
            @if ($end < $products->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
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

</div>
