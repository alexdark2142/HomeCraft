<section class="section-products">
    <div class="container">
        <div class="row row-40 justify-content-center">
            <div class="col-12">
                <div class="category-header">
                    @if (!empty($selectedCategory))
                        <h2>{{ $selectedCategory }}</h2>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="row row-30 justify-content-center">
                    @if ($products->isEmpty())
                        <div class="col-12">
                            <div class="no-products-message">
                                <p>There are no products in this category.</p>
                                <a href="/" class="button button-primary">Return to Home</a>
                            </div>
                        </div>
                    @else
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-4">
                                <div class="oh-desktop">
                                    <!-- Product-->
                                    <article class="product product-2 box-ordered-item wow slideInRight" data-wow-delay="0s">
                                        <div class="unit flex-row flex-lg-column">
                                            <div class="unit-left">
                                                <div class="product-figure">
                                                    @php
                                                        $mainImage = $product->gallery->firstWhere('type', 'main');
                                                    @endphp

                                                    <img
                                                        src="{{ asset('images/gallery/' . $mainImage->tag . '/' . $mainImage->name) }}"
                                                        alt="{{ $product->name }}"
                                                        width="270"
                                                        height="280"
                                                    />
                                                    <div class="product-button">
                                                        <button
                                                            class="button button-md button-white button-ujarak view-product"
                                                            data-url="{{ route('product.show', $product->id) }}"
                                                        >
                                                            View
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="unit-body">
                                                <h6 class="product-title truncate">
                                                    {{ $product->name }}
                                                </h6>

                                                @if(($product->quantity > 0 && $product->price !== null && $product->price > 0))
                                                    <div class="product-price-wrap">
                                                        <div class="product-price">${{ $product->price }}</div>
                                                    </div>
                                                @else
                                                    <div class="product-out-of-stock truncate">
                                                        Out of stock
                                                    </div>
                                                @endif

                                                <button
                                                    class="button button-sm button-dark button-ujarak view-product"
                                                    data-url="{{ route('product.show', $product->id) }}"
                                                >
                                                    View
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@if ($products->isNotEmpty())
    @include('parts.paginate', ['items' => $products])
@endif
