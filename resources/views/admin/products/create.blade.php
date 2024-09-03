@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add Product:</h1>
        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('products.store') }}"
            >
                @csrf
                <div class="form-group">
                    <label class="form-label">Pictures</label>
                    <input
                        required
                        type="file"
                        name="photos[]"
                        id="img"
                        accept=".jpg, .jpeg, .png, .webp"
                        class="form-input"
                        multiple
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
                    ></textarea>
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Colors and Quantities</label>
                    <div id="color-quantity-container">
                        <div class="color-quantity-row">
                            <input
                                type="text"
                                name="colors[]"
                                class="form-input"
                                placeholder="Color"
                            >

                            <input
                                type="number"
                                name="quantities[]"
                                class="form-input"
                                min="0"
                                placeholder="Quantity"
                            >
                        </div>
                    </div>
                    <button type="button" id="add-color-quantity" class="form-button">Add more</button>
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
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select required name="category_id" id="category_id" class="form-input">
                        <option value="">Choose category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <span class="error-message"></span>
                </div>

                <div id="subcategory-container" class="form-group" style="display: none;">
                    <label class="form-label">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-input">
                        <option value="">Choose subcategory</option>
                        <!-- Subcategory options will be populated by JS -->
                    </select>
                    <span class="error-message"></span>
                </div>

                <div class="btn-group">
                    <button id="btn" type="submit" class="form-button">Add item</button>
                    <button id="backButton" class="form-button ">Back</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.categoriesWithSubcategories = @json($categoriesWithSubcategories);
    </script>
@endsection
