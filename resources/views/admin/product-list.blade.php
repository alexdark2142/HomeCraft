@extends('admin.templates.base')

@section('main')
	<div class="w-full">
		<h1 class="text-3xl font-medium mb-5">List Of Products: {{ $products->isEmpty() ? 'is empty!' : '' }}</h1>
        @if ($products->isEmpty())
            <p>No products available.</p>
            <div class="mt-5">
                <a href="{{ route('admin.add-product') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Product
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                    <tr>
                        @foreach (['Img','Name','Price','Count','Category','Subcategory', 'Action'] as $item)
                            <th class="align-top text-center border-r border-l border-b px-2 text-blue-800">
                                {!!$item!!}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b border-r border-l h-full product-row" data-product-id="{{ $product->id }}">
                            <td class="text-center p-2 border-r border-l h-full flex justify-center items-center align-middle">
                                <div class="flex flex-col justify-center items-center h-full">
                                    <img src="{{ asset('/images/' . ($product->img)) }}" alt="Img product" class="u-embed__obj">
                                </div>
                            </td>
                            <td class="text-center border-r border-l h-full flex justify-center items-center align-middle">
                                {{--                            <a href="{{ route('admin.products-info', $product->id) }}" class="block p-2 hover:bg-blue-200 transition">--}}
                                {{ $product->name }}
                                {{--                            </a>--}}
                            </td>
                            <td class="text-center p-2 border-r border-l h-full flex justify-center items-center align-middle">
                                ${{ $product->price }}&nbsp;
                            </td>
                            <td class="text-center p-2 border-r border-l h-full flex justify-center items-center align-middle">
                                {{ $product->count }}
                            </td>
                            <td class="text-center p-2 border-r border-l h-full flex justify-center items-center align-middle">
                                {{ $product->category->name }}
                            </td>
                            <td class="text-center p-2 border-r border-l h-full flex justify-center items-center align-middle">
                                {{ $product->subcategory->name ?? '' }}
                            </td>
                            <td class="p-2 border-r border-l flex justify-center items-center h-full gap-x-1 align-middle">
                                <div class="flex flex-col justify-center items-center h-full">
                                    <button class="btn btn-dark i i-black-list mb-2"></button>
                                    <button id="delete-product-btn" class="btn btn-danger i i-trash"></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
	</div>
@endsection
