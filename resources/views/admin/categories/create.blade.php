@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add a category:</h1>

        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('categories.store') }}"
                method="POST"
            >
                @csrf
                <div class="form-group">
                    <label class="form-label">Pictures</label>
                    <input
                        required
                        type="file"
                        name="image"
                        id="image"
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

                <div class="form-group" id="subcategory-box">
                    <div class="input-group">
                        <button type="button" id="add-subcategory" class="form-button add-category-btn">Add Subcategory</button>
                    </div>

                    <span class="error-message"></span>
                </div>

                <div class="btn-group">
                    <button id="btn" type="submit" class="form-button">Add category</button>
                    <button id="backButton" class="form-button ">Back</button>
                </div>
            </form>
        </div>
    </div>
@endsection
