<div id="app" class="container-paginate">
    <!-- Full version pagination -->
    <ul class="paginate full-version">
        @if ($categories->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $categories->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($categories->currentPage() - 2, 1);
            $end = min($start + 4, $categories->lastPage());

            // Adjust the range if we're too close to the end or the beginning
            if ($categories->currentPage() + 2 > $categories->lastPage()) {
                $start = max($categories->lastPage() - 4, 1);
                $end = $categories->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $categories->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($categories->currentPage() == $i) active @endif">
                <a href="{{ $categories->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $categories->lastPage())
            @if ($end < $categories->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $categories->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a>
            </li>
        @endif

        @if ($categories->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $categories->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

    <!-- Mobile version pagination -->
    <ul class="paginate mobile-version">
        @if ($categories->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $categories->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($categories->currentPage() - 1, 1);
            $end = min($start + 2, $categories->lastPage());

            if ($categories->currentPage() + 1 > $categories->lastPage()) {
                $start = max($categories->lastPage() - 2, 1);
                $end = $categories->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $categories->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($categories->currentPage() == $i) active @endif">
                <a href="{{ $categories->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $categories->lastPage())
            @if ($end < $categories->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $categories->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a>
            </li>
        @endif

        @if ($categories->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $categories->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

</div>
