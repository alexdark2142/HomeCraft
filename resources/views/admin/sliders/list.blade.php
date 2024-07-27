@extends('admin.templates.base')

@section('main')
    <div class="w-full">
        <h1 class="text-3xl font-medium mb-5">List Of home pictures: {{ $sliders->isEmpty() ? 'is empty!' : '' }}</h1>
        @if ($sliders->isEmpty())
            <p>No home pictures available.</p>
            <div class="mt-5">
                <a href="{{ route('sliders.create') }}" class="button-primary">
                    Add home picture
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                    <tr>
                        @foreach (['Img','Title','Description','Category', 'Action'] as $item)
                            <th class="text-center border px-2 header-cell">
                                {!! $item !!}
                            </th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($sliders as $slider)
                        <tr class="product-row" data-product-id="{{ $slider->id }}">
                            <td class="text-center p-2 border img-cell">
                                <div class="image-container">
                                    <img
                                        src="{{ asset('images/sliders/' . $slider->image_name) }}"
                                        alt="Slider Image"
                                        class="product-image"
                                    >
                                </div>
                            </td>

                            <td class="text-center p-2 border name-cell">
                                {{ $slider->title }}
                            </td>

                            <td class="text-center p-2 border price-cell">
                                {{ $slider->description }}
                            </td>

                            <td class="text-center p-2 border category-cell">
                                {{ $slider->category ? $slider->category->name : '' }}
                            </td>

                            <td class="p-2 border action-cell">
                                <div class="flex justify-evenly gap-x-1">
                                    <a href="{{ route('sliders.edit', $slider->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"/></svg>
                                    </a>
                                    <button id="delete-btn" data-url="{{ route('sliders.destroy', $slider->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#e53124" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if($sliders->isNotEmpty())
        @include('admin.sliders.paginate')
    @endif
@endsection
