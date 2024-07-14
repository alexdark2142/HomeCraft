@extends('templates.default')

@section('content')
    <section class="section-product">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Gallery Section -->
                <div class="col-12 col-md-6 px-5 py-3">
                    <div class="main-image mb-4">
                        @php
                            $mainImage = $product->gallery->firstWhere('type', 'main');
                        @endphp
                        @if ($mainImage)
                            <img src="{{ asset('images/gallery/' . $product->id . '/' . $mainImage->name) }}" alt="Main Product Image" class="object-contain">
                        @else
                            <img src="{{ asset('images/products/default-image.jpg') }}" alt="Main Product Image" class="object-contain">
                        @endif
                    </div>
                    <div class="thumbnails flex flex-wrap justify-center">
                        @foreach ($product->gallery as $image)
                            <div class="thumbnail m-2 {{ $image->type == 'main' ? 'selected' : '' }}">
                                <img src="{{ asset('images/gallery/' . $product->id . '/' . $image->name) }}" alt="Product Thumbnail" class="w-70 h-70 object-cover" data-main="{{ $image->tag === 'main' ? 'true' : 'false' }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description Section -->
                <div class="col-12 col-md-6 px-5 py-3">
                    <h3 class="text-3xl font-bold mb-4">{{ $product->name }}</h3>

                    @if($product->description)
                        <p class="mb-4">{{ $product->description }}</p>
                    @endif

                    <p class="mb-2 d-flex"><strong class="mr-3">Quantity in stock:</strong> {{ $product->count }}</p>

                    <p class="mb-2 d-flex"><strong class="mr-3">Category:</strong> {{ $product->category->name }}</p>

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
@endsection
