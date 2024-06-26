@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add Product:</h1>
        <div class="form-wrapper">
            <form id="product-form" class="form-space" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Picture</label>
                    <input
                        required
                        type="file"
                        name="img"
                        id="img"
                        accept=".jpg, .jpeg, .png, .webp"
                        class="form-input"
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
                        name="category"
                        id="category"
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
                    <select name="subcategory" id="subcategory" class="form-input">
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
