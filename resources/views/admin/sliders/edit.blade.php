@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Add a home picture:</h1>
        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('sliders.update', $slider->id) }}"
            >
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Pictures</label>
                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept=".jpg, .jpeg, .png, .webp"
                        class="form-input"
                        disabled
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
                        value="{{  $slider->title }}"
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
                    >{{  $slider->description }}</textarea>
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select required name="category_id" id="category_id" class="form-input">
                        <option value="">Choose category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $slider->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error-message"></span>
                </div>

                <div class="btn-group">
                    <button id="btn" type="submit" class="form-button">Update home picture</button>
                    <button id="backButton" class="form-button ">Back</button>
                </div>
            </form>
        </div>
    </div>
@endsection
