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
                <th width="10%">Product</th>
                <th width="10%">SKU</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($products as $product)
            <tr>

                <td style="width: 40px; line-break: strict; word-wrap: break-word;">
                    {{ $product->name}}
                </td>
                <td style="width: 40px; line-break: strict; word-wrap: break-word;">
                    {{ $product->sku}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>