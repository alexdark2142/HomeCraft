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

                    @if($product->has_colors)
                        <div class="color-selection">
                            <div class="color-info">
                                <strong class="mr-3">Select a color:</strong>
                                <div id="selected-color">
                                    {{ $product->colors->first()->color }}
                                </div>
                            </div>

                            <div class="quantity-info">
                                <strong class="mr-3">Quantity:</strong>
                                <span id="quantity">
                                    {{
                                        $product->colors->first()->quantity > 0
                                        ? $product->colors->first()->quantity
                                        : 'Out of stock'
                                    }}
                                </span>
                            </div>

                            <div class="color-options">
                                @foreach($product->colors as $color)
                                    <div class="color-option">
                                        <input
                                            type="radio"
                                            id="color-{{ $color->id }}"
                                            name="color" value="{{ $color->color }}"
                                            data-quantity="{{ $color->quantity }}"
                                            data-id="{{ $color->id }}"
                                            data-color="{{ $color->color }}"
                                            {{ $loop->first ? 'checked' : '' }}
                                        >
                                        <label for="color-{{ $color->id }}" style="background-color: {{ $color->color }};"></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p id="without-color-quantity" data-quantity="{{ $product->quantity }}" class="mb-2 d-flex">
                            <strong class="mr-3">Quantity in stock:</strong>
                            {{ $product->quantity ?? 'The product is out of stock' }}
                        </p>
                    @endif

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

                    @if($product->price)
                        <p class="mb-2 d-flex"><strong class="mr-3">Price:</strong> ${{ $product->price }} </p>
                    @endif

                    <button
                        id="btn-add-product"
                        class="button button-md button-secondary button-ujarak add-to-cart"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-quantity="{{ $product->colors->first()->quantity ?? $product->quantity }}"
                        data-color-id="{{ $product->colors->first()->id ?? null }}"
                        data-color-name="{{ $product->colors->first()->color ?? null }}"
                        data-img="{{ asset('images/gallery/' . $mainImage->tag . '/' . $mainImage->name) }}"
                        {{ $product->quantity == 0 ? 'disabled' : '' }}
                    >
                        Add to Cart
                    </button>

                    <a
                        id="btn-contact-us"
                        class="button button-md button-secondary button-ujarak"
                        href="mailto:homecraft1sbdt@gmail.com?subject=Product%20to%20order&body=Hello,%0A%0AI%20would%20like%20to%20order%20this%20product:%20https://www.home-craft-quality.ca/product/%24product-%3Eid%0A%0AThank%20you"
                        style="display: none;"
                    >
                        Contact us to order
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('parts.photoSwipe')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Отримуємо елементи кнопок та кольорові варіанти
            const colorInputs = document.querySelectorAll('.color-option input[type="radio"]');
            const selectedColorElement = document.getElementById('selected-color');
            const quantityElement = document.getElementById('quantity');
            const btnAddProduct = document.getElementById('btn-add-product');
            const btnContactUs = document.getElementById('btn-contact-us');
            const productQuantity = document.getElementById('without-color-quantity');

            // Функція для оновлення видимості кнопок
            function updateButtonVisibility() {
                const selectedColorInput = document.querySelector('.color-option input[type="radio"]:checked');

                if (selectedColorInput) {
                    const quantity = selectedColorInput.getAttribute('data-quantity');

                    selectedColorElement.textContent = selectedColorInput.value;
                    btnAddProduct.dataset.quantity = quantity;
                    btnAddProduct.dataset.colorId = selectedColorInput.getAttribute('data-id');
                    btnAddProduct.dataset.colorName = selectedColorInput.getAttribute('data-color');

                    if (quantity > 0) {
                        quantityElement.textContent = quantity;
                        btnAddProduct.style.display = 'block'; // Показуємо кнопку "Add to Cart"
                        btnContactUs.style.display = 'none'; // Сховати кнопку "Contact us to order"
                    } else {
                        quantityElement.textContent = 'Out of stock';
                        btnAddProduct.style.display = 'none'; // Сховати кнопку "Add to Cart"
                        btnContactUs.style.display = 'block'; // Показати кнопку "Contact us to order"
                    }
                } else if (productQuantity){
                    const quantity = productQuantity.getAttribute('data-quantity');

                    if (quantity > 0) {
                        btnAddProduct.style.display = 'block'; // Показуємо кнопку "Add to Cart"
                        btnContactUs.style.display = 'none'; // Сховати кнопку "Contact us to order"
                    } else {
                        btnAddProduct.style.display = 'none'; // Сховати кнопку "Add to Cart"
                        btnContactUs.style.display = 'block'; // Показати кнопку "Contact us to order"
                    }
                } else {
                    // Якщо кольори не вибрані, показуємо кнопку "Contact us to order"
                    btnAddProduct.style.display = 'none';
                    btnContactUs.style.display = 'block';
                }
            }

            // Додаємо обробник подій для кожного кольорового варіанту
            colorInputs.forEach(input => {
                input.addEventListener('change', updateButtonVisibility);
            });

            // Ініціалізуємо видимість кнопок при завантаженні сторінки
            updateButtonVisibility();
        });

    </script>

@endsection
