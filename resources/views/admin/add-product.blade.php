@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add Product:</h1>
        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('admin.create-product') }}"
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
                    >
                    </textarea>
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Count</label>
                    <input
                        required
                        type="number"
                        name="count"
                        id="count"
                        class="form-input"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Size:</label>
                    <div class="size-fields">
                        <div class="size-field">
                            <input
                                type="number"
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
                        required
                        type="number"
                        step="0.01"
                        name="price"
                        id="price"
                        class="form-input"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select
                        required
                        name="category_id"
                        id="category_id"
                        class="form-input"
                    >
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
                        <!-- Subcategory options will be loaded dynamically -->
                    </select>
                    <span class="error-message"></span>
                </div>

                <button id="btn" type="submit" class="form-button">Add item</button>
            </form>
        </div>
    </div>
@endsection
