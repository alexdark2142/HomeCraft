<div id="app" class="container-paginate">
    <!-- Full version pagination -->
    <ul class="paginate full-version">
        @if ($sliders->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $sliders->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($sliders->currentPage() - 2, 1);
            $end = min($start + 4, $sliders->lastPage());

            // Adjust the range if we're too close to the end or the beginning
            if ($sliders->currentPage() + 2 > $sliders->lastPage()) {
                $start = max($sliders->lastPage() - 4, 1);
                $end = $sliders->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $sliders->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($sliders->currentPage() == $i) active @endif">
                <a href="{{ $sliders->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $sliders->lastPage())
            @if ($end < $sliders->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $sliders->url($sliders->lastPage()) }}">{{ $sliders->lastPage() }}</a>
            </li>
        @endif

        @if ($sliders->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $sliders->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

    <!-- Mobile version pagination -->
    <ul class="paginate mobile-version">
        @if ($sliders->previousPageUrl())
            <li class="paginate__btn">
                <a href="{{ $sliders->previousPageUrl() }}">
                    &lt;
                </a>
            </li>
        @endif

        @php
            $start = max($sliders->currentPage() - 1, 1);
            $end = min($start + 2, $sliders->lastPage());

            if ($sliders->currentPage() + 1 > $sliders->lastPage()) {
                $start = max($sliders->lastPage() - 2, 1);
                $end = $sliders->lastPage();
            }
        @endphp

        @if ($start > 1)
            <li class="paginate__numbers">
                <a href="{{ $sliders->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="paginate__dots">...</li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="paginate__numbers @if ($sliders->currentPage() == $i) active @endif">
                <a href="{{ $sliders->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $sliders->lastPage())
            @if ($end < $sliders->lastPage() - 1)
                <li class="paginate__dots">...</li>
            @endif
            <li class="paginate__numbers">
                <a href="{{ $sliders->url($sliders->lastPage()) }}">{{ $sliders->lastPage() }}</a>
            </li>
        @endif

        @if ($sliders->hasMorePages())
            <li class="paginate__btn">
                <a href="{{ $sliders->nextPageUrl() }}">
                    &gt;
                </a>
            </li>
        @endif
    </ul>

</div>
