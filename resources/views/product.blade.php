@extends('templates.default')

@section('content')
    <section class="section-product">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Gallery -->
                <div class="col-12 col-md-6 px-5 py-3 my-gallery">
                    <div class="main-image mb-4">
                        @foreach ($product->gallery as $index => $image)
                            @if ($index == 0)
                                @php $mainImage = $image @endphp
                                <a href="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" data-pswp-width="1600" data-pswp-height="1067" class="gallery-item">
                                    <img src="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" alt="Main Product Image" class="object-contain">
                                </a>
                            @else
                                <a href="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" data-pswp-width="1600" data-pswp-height="1067" class="gallery-item" style="display: none;">
                                    <img src="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" alt="Main Product Image" class="object-contain">
                                </a>
                            @endif
                        @endforeach
                    </div>
                    <div class="thumbnails flex flex-wrap justify-center">
                        @foreach ($product->gallery as $image)
                            <div class="thumbnail m-2">
                                <img src="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" alt="Product Thumbnail" class="w-70 h-70 object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description Section -->
                <div class="col-12 col-md-6 px-5 py-3">
                    <h3 class="text-3xl font-bold mb-4">{{ $product->name }}</h3>

                    @if($product->description)
                        <p class="mb-3 text-justify">{{ $product->description }}</p>
                    @endif

                    <h3 class="text-3xl font-bold mb-4">Product details</h3>

                    <p class="mb-2 d-flex">
                        <i class="fa-solid fa-hand"></i>
                        <strong class="mr-3">Handmade product</strong>
                    </p>
                    <p class="mb-2 d-flex"><strong class="mr-3">Made to order</strong></p>

                    <p class="mb-2 d-flex"><strong class="mr-3">Quantity in stock:</strong> {{ $product->count }}</p>

                    @if($product->category)
                        <p class="mb-2 d-flex"><strong class="mr-3">Category:</strong> {{ $product->category->name }}</p>
                    @endif

                    @if($product->material)
                        <p class="mb-2 d-flex"><strong class="mr-3">Material:</strong> {{ $product->material }}</p>
                    @endif

                    @if($product->length || $product->height || $product->width || $product->depth)
                        <div class="mb-2 text-left">
                            <strong class="mr-3">Size:</strong>
                            @if($product->length)
                                <div>Length: {{ $product->length }} mm</div>
                            @endif
                            @if($product->height)
                                <div>Height: {{ $product->height }} mm</div>
                            @endif
                            @if($product->width)
                                <div>Width: {{ $product->width }} mm</div>
                            @endif
                            @if($product->depth)
                                <div>Depth: {{ $product->depth }} mm</div>
                            @endif
                        </div>
                    @endif

                    @if($product->subcategory)
                        <p class="mb-2 d-flex"><strong class="mr-3">Subcategory:</strong> {{ $product->subcategory->name }}</p>
                    @endif

                    <p class="mb-2 d-flex"><strong class="mr-3">Price:</strong> ${{ $product->price }} </p>

                    <button
                        class="button button-md button-secondary button-ujarak add-to-cart"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-count="{{ $product->count }}"
                        data-img="{{ asset('images/gallery/' . $mainImage->tag . '/' . $mainImage->name) }}"
                        {{ $product->count == 0 ? 'disabled' : '' }}
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </section>

    @include('parts.photoSwipe')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

        });

    </script>

@endsection
