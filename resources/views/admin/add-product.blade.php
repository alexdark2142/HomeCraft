@extends('admin.templates.base')

@section('main')
	<div class="w-full">
		<h1 class="text-3xl font-medium mb-5">Add Product:</h1>
		<div class="overflow-x-auto">
            <form id="product-form" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Picture</label>
                    <input type="file" name="img" id="img" accept=".jpg, .jpeg, .png, .webp" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Count</label>
                    <input type="number" name="count" id="count" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" step="0.01" name="price" id="price" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="">Choose category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="subcategory-container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700">Subcategory</label>
                    <select name="subcategory" id="subcategory" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="">Choose subcategory</option>
                        <!-- Subcategory options will be loaded dynamically -->
                    </select>
                </div>
                <button id="btn" type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">Add item</button>
            </form>
        </div>
	</div>
@endsection
