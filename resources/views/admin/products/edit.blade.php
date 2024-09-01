@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Edit Product:</h1>
        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('products.update', $product->id) }}"
            >
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Pictures
                        <span style="color: red">(This field does not work in edit mode. Will be provided later)</span>
                    </label>
                    <input
                        type="file"
                        name="img"
                        id="img"
                        accept=".jpg, .jpeg, .png, .webp"
                        class="form-input"
                        disabled
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input
                        required
                        type="text"
                        name="name"
                        id="name"
                        class="form-input"
                        value="{{ $product->name }}"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea
                        rows="4"
                        name="description"
                        id="description"
                        class="form-input"
                    >{{ $product->description }}</textarea>
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input
                        min="0"
                        type="number"
                        name="quantity"
                        id="quantity"
                        class="form-input"
                        value="{{ $product->quantity }}"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Size:</label>
                    <div class="size-fields">
                        <div class="size-field">
                            <input
                                type="number"
                                min="0"
                                name="length"
                                id="length"
                                class="form-input"
                                placeholder="L"
                                value="{{ $product->length }}"
                            >
                            <span class="unit">mm</span>
                        </div>
                        <div class="size-field">
                            <input
                                type="number"
                                min="0"
                                name="height"
                                id="height"
                                class="form-input"
                                placeholder="H"
                                value="{{ $product->height }}"
                            >
                            <span class="unit">mm</span>
                        </div>
                        <div class="size-field">
                            <input
                                type="number"
                                min="0"
                                name="width"
                                id="width"
                                class="form-input"
                                placeholder="W"
                                value="{{ $product->width }}"
                            >
                            <span class="unit">mm</span>
                        </div>
                        <div class="size-field">
                            <input
                                type="number"
                                min="0"
                                name="depth"
                                id="depth"
                                class="form-input"
                                placeholder="D"
                                value="{{ $product->depth }}"
                            >
                            <span class="unit">mm</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Material</label>
                    <input
                        type="text"
                        name="material"
                        id="material"
                        class="form-input"
                        value="{{ $product->material }}"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.00"
                        name="price"
                        id="price"
                        class="form-input"
                        value="{{ $product->price }}"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select required name="category_id" id="category_id" class="form-input">
                        <option value="">Choose category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error-message"></span>
                </div>

                <div id="subcategory-container" class="form-group" style="{{ $product->subcategory_id ? 'display: flex;' : 'display: none;' }}">
                    <label class="form-label">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-input">
                        <option value="">Choose subcategory</option>
                        @foreach($categoriesWithSubcategories[$product->category_id] ?? [] as $subcategory)
                            <option value="{{ $subcategory['id'] }}" {{ $product->subcategory_id == $subcategory['id'] ? 'selected' : '' }}>
                                {{ $subcategory['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error-message"></span>
                </div>

                <div class="btn-group">
                    <button id="btn" type="submit" class="form-button">Update item</button>
                    <button id="backButton" class="form-button ">Back</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.categoriesWithSubcategories = @json($categoriesWithSubcategories);
    </script>
@endsection
