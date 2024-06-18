@extends('templates.default')

@section('content')
    <!-- Swiper-->
    @include('parts.swiper')

    @include('parts.category')
    <!-- Products-->
    <section class="section-products">
        <div class="container">
            <div class="row row-40 justify-content-center">
                <div>
                    <div class="row row-30 justify-content-center">
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-4">
                                <div class="oh-desktop">
                                    <!-- Product-->
                                    <article class="product product-2 box-ordered-item wow slideInRight" data-wow-delay="0s">
                                        <div class="unit flex-row flex-lg-column">
                                            <div class="unit-left">
                                                <div class="product-figure">
                                                    <img src="{{ asset('images/products/' . $product->img) }}" alt="{{ $product->name }}" width="270" height="280" />
                                                    <div class="product-button">
                                                        <button class="button button-md button-white button-ujarak add-to-cart" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-img="{{ asset('images/products/' . $product->img) }}">
                                                            Add to cart
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="unit-body">
                                                <h6 class="product-title">
                                                    {{ $product->name }}
                                                </h6>

                                                <div class="product-price-wrap">
                                                    <div class="product-price">${{ $product->price }}</div>
                                                </div>

                                                <button
                                                    class="button
                                                        button-sm
                                                        button-secondary
                                                        button-ujarak
                                                        add-to-cart
                                                    "
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    data-price="{{ $product->price }}"
                                                    data-img="{{ asset('images/products/' . $product->img) }}"
                                                >
                                                    Add to cart
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($products->isNotEmpty())
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
    @endif

    <!-- About us-->
    {{--     <section class="section">
         <div class="parallax-container" data-parallax-img="images/bg-parallax-2.jpg">
             <div class="parallax-content section-xl context-dark bg-overlay-68">
                 <div class="container">
                     <div class="row row-lg row-50 justify-content-center border-classic border-classic-big">

                     </div>
                 </div>
             </div>
         </div>
     </section>--}}
@endsection
