@extends('admin.templates.base')

@section('main')
    <div class="form-container">
        <h1 class="form-title">Edit category:</h1>

        <div class="form-wrapper">
            <form
                id="product-form"
                class="form-space"
                enctype="multipart/form-data"
                data-url="{{ route('categories.update', $category->id) }}"
                method="POST"
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
                    <label class="form-label">Name</label>
                    <input
                        required
                        type="text"
                        name="name"
                        id="name"
                        class="form-input"
                        value="{{ $category->name }}"
                    >
                    <span class="error-message"></span>
                </div>

                <div class="form-group" id="subcategory-box">
                    <div class="input-group">
                        <button type="button" id="add-subcategory" class="form-button add-category-btn">Add Subcategory</button>
                    </div>

                    <span class="error-message"></span>
                    @foreach($category->subcategories as $subcategory)
                        <div class="subcategory-group">
                            <input
                                type="text"
                                name="subcategories[{{ $subcategory->id }}]"
                                class="form-input"
                                placeholder="Subcategory"
                                value="{{ $subcategory->name }}"
                            >
                            <button type="button" class="remove-button" data-id="{{ $subcategory->id }}">-</button>
                        </div>
                    @endforeach
                </div>

                <div id="removed-subcategories"></div>

                <div class="btn-group">
                    <button id="btn" type="submit" class="form-button">Update Category</button>
                    <button id="backButton" type="button" class="form-button">Back</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.remove-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const subcategoryGroup = this.parentElement;
                    const subcategoryId = this.dataset.id;

                    if (subcategoryId) {
                        const removedSubcategories = document.getElementById('removed-subcategories');
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'removed_subcategories[]';
                        input.value = subcategoryId;
                        removedSubcategories.appendChild(input);
                    }

                    subcategoryGroup.remove();
                });
            });
        });
    </script>
@endsection
