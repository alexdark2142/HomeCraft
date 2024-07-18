@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add a home picture:</h1>
        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('sliders.store') }}"
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
                    <label class="form-label">Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
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

                <button id="btn" type="submit" class="form-button">Add picture</button>
            </form>
        </div>
    </div>
@endsection
