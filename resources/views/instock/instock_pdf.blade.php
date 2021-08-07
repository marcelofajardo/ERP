<html>
<head>
  <title>Sololuxury Products</title>
  <style>
  </style>
</head>
<body>
  <h3>In-Stock Products</h3>
  <table width="100%" border="1" style="border-collapse: collapse">
    @foreach ($products->chunk(3) as $productx)
      <tr>
        @foreach($productx as $product)
          <td width="30%">
            <a href="{{ route('products.show', $product->id) }}">
              <img style="width: 100px;" src="{{ $product->getMedia(config('constants.media_tags'))->first()
          ? $product->getMedia(config('constants.media_tags'))->first()->getAbsolutePath()
          : ''
        }}" alt="" />
              <p>Sku : {{ $product->sku }}</p>
              <p>Id : {{ $product->id }}</p>
              <p>Size : {{ $product->size}}</p>
              <p>Price : {{ $product->price_special }}</p>
            </a>
          </td>
        @endforeach
      </tr>
    @endforeach
  </table>
</body>
</html>