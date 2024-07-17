<div id="app" class="container-paginate">
    {{ $items = $products ?? $sliders }}
    <!-- Full version pagination -->
    <ul class="paginate full-version">
        @if ($items->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $items->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($items->currentPage() - 2, 1);
            $end = min($start + 4, $items->lastPage());

            // Adjust the range if we're too close to the end or the beginning
            if ($items->currentPage() + 2 > $items->lastPage()) {
                $start = max($items->lastPage() - 4, 1);
                $end = $items->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $items->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($items->currentPage() == $i) active @endif">
                <a href="{{ $items->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $items->lastPage())
            @if ($end < $items->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
            </li>
        @endif

        @if ($items->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $items->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

    <!-- Mobile version pagination -->
    <ul class="paginate mobile-version">
        @if ($items->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $items->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($items->currentPage() - 1, 1);
            $end = min($start + 2, $items->lastPage());

            if ($items->currentPage() + 1 > $items->lastPage()) {
                $start = max($items->lastPage() - 2, 1);
                $end = $items->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $items->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($items->currentPage() == $i) active @endif">
                <a href="{{ $items->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $items->lastPage())
            @if ($end < $items->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
            </li>
        @endif

        @if ($items->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $items->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

</div>
