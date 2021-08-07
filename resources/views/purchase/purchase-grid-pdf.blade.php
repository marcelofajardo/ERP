<html>
<head>
    <title>Orders</title>
    <style>
        table {
            width: 100%;
            ;
        }
    </style>
</head>
<body>
    <table class="table table-bordered" width="100%" border="1" style="border-collapse: collapse;">
        <thead>
            <tr>
{{--                <th width="10%">#</th>--}}
                <th width="10%">Product</th>
                <th width="10%">SKU</th>
                <th width="10%">Customers</th>
                <th width="10%">Price In Order</th>
                <th width="10%">Order Date</th>
                <th width="10%">Order Advance</th>
                <th width="10%">Suppliers</th>
                <th width="10%">Brand</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($products as $product)
            <tr>
{{--                <td>--}}
{{--                    <input type="checkbox" class="select-product" name="products[]" value="{{ $product['id'] }}" data-supplier="{{ $product['single_supplier'] }}" />--}}
{{--                </td>--}}
                <td>
                    <a href="{{ route('products.show', $product['id']) }}" target="_blank"><img src="{{ $product['abs_img_url'] }}" class="img-responsive" style="width: 100px !important" alt=""></a>
                </td>
                <td style="width: 40px; line-break: strict; word-wrap: break-word;">
                    {{ $product['sku'] }}
                </td>
                <td>
                    <ul class="list-unstyled">
                        @foreach ($product['customers'] as $customer)
                            <li><a href="{{ route('customer.show', $customer->id) }}" target="_blank">{{ $customer->name }}</a></li>
                        @endforeach
                    </ul>

                </td>
                <td>
                    <ul class="list-unstyled">
                        @foreach ($product['order_products'] as $order_product)
                            <li>{{ $order_product->product_price }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul class="list-unstyled">
                        @foreach ($product['order_products'] as $order_product)
                            @if ($order_product->order)
                                <li>{{ \Carbon\Carbon::parse($order_product->order->order_date)->format('d-m') }}</li>
                            @else
                                <li>No Order</li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul class="list-unstyled">
                        @foreach ($product['order_products'] as $order_product)
                            @if ($order_product->order)
                                <li>{{ $order_product->order->advance_detail }}</li>
                            @else
                                <li>No Order</li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>{{ $product['supplier_list'] }}</td>
                <td>{{ $product['brand'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>