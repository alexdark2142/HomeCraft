@extends('admin.templates.base')

@section('main')
    <div class="w-full">
        <h1 class="text-3xl font-medium mb-5">
            List of {{ $currentStatus }} orders {{ $orders->isEmpty() ? 'is empty!' : ':' }}
        </h1>

        @if ($orders->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                        <tr>
                            @foreach ([
                                'img' => 'Img',
                                'name' => 'Name',
                                'quantity' => 'Quantity',
                                'color' => 'Color',
                                'price_per_piece' => 'Price per piece',
                                'total_price' => 'Total price',
                                'full_name' => 'Customer full name',
                                'email' => 'Customer email',
                                'full_address' => 'Customer delivery address',
                                'select_status' => 'Select a status',
                                'action' => 'Action'
                            ] as $key => $value)
                                @if(!(
                                    $currentStatus === 'cancelled'
                                    && in_array($key, ['full_name', 'email', 'full_address', 'select_status'])
                                ))
                                    @php
                                        $style = $key === 'select_status' ? 'min-width: 100px;' : '';
                                    @endphp

                                    <th class="text-center border px-2" style="{{ $style }}">
                                        {!! $value !!}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $order)
                        <tr class="product-row" data-product-id="{{ $order->id }}">
                            <td class="text-center p-2 border">
                                <div class="image-container">
                                    @php
                                        $mainImage = $order->product->gallery->firstWhere('type', 'main');
                                    @endphp

                                    <img
                                        src="{{ asset('images/gallery/' . $mainImage->tag . '/' . $mainImage->name) }}"
                                        alt="Product Image"
                                        class="product-image"
                                    >
                                </div>
                            </td>

                            <td class="text-center p-2 border">
                                {{ $order->product->name }}
                            </td>

                            <td class="text-center p-2 border">
                                {{ $order->quantity }}
                            </td>

                            <td class="text-center p-2 border">
                                {{ $order->productColor ? $order->productColor->color : ''}}
                            </td>

                            <td class="text-center p-2 border">
                                ${{ number_format($order->price_per_piece, 2) }}
                            </td>

                            <td class="text-center p-2 border">
                                ${{ number_format($order->quantity * $order->price_per_piece, 2) }}
                            </td>

                            @if($order->customer)
                                <td class="text-center p-2 border">
                                    {{ $order->customer->full_name }}
                                </td>

                                <td class="text-center p-2 border">
                                    {{ $order->customer->email }}
                                </td>

                                <td class="text-center p-2 border">
                                    {{ $order->customer->full_address }}
                                </td>

                                <td class="p-2 border">
                                    <select name="order_status" id="order-status-{{ $order->id }}" class="form-input order-status-select">
                                        @foreach($orderStatuses as $status)
                                            <option value="{{ $status }}" {{ strtoupper($currentStatus) == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endif

                            <td class="p-2 border">
                                <div class="flex justify-evenly gap-x-1">
                                    <button id="apply-btn-{{ $order->id }}" class="apply-btn" data-url="{{ route('orders.update', $order->id) }}" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28" viewBox="0 0 384 512">
                                            <path d="M192 0c-41.8 0-77.4 26.7-90.5 64L64 64C28.7 64 0 92.7 0 128L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64l-37.5 0C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM305 273L177 401c-9.4 9.4-24.6 9.4-33.9 0L79 337c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L271 239c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                                        </svg>
                                    </button>

                                    <button id="delete-btn" data-url="{{ route('orders.destroy', $order->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28" viewBox="0 0 448 512">
                                            <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                            <path fill="#e53124" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/>
                                        </svg>
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

    @if($orders->isNotEmpty())
        @include('parts.paginate', ['items' => $orders])
    @endif
@endsection
