@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
<style type="text/css">
  .dis-none {
    display: none;
  }
</style>
@section('content')
  <div class="row">
    <div class="col-lg-12 margin-tb">
      <div class="">
        <h2 class="page-heading">In Delivered Products</h2>

        <!--Product Search Input -->
        <form action="{{ route('productinventory.indelivered') }}" method="GET" class="form-inline align-items-start">
          <div class="form-group mr-3 mb-3">
            <input name="term" type="text" class="form-control" id="product-search" value="{{ isset($term) ? $term : '' }}" placeholder="sku,brand,category,status">
          </div>
          <div class="form-group mr-3 mb-3">
            {!! $category_selection !!}
          </div>

          

          <div class="form-group mr-3">
            @php $brands = \App\Brand::getAll();
            @endphp
            <select data-placeholder="Select brands"  class="form-control select-multiple2" name="brand[]" multiple>
              <optgroup label="Brands">
                @foreach ($brands as $id => $name)
                  <option value="{{ $id }}" {{ isset($brand) && $brand == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
              </optgroup>
            </select>
          </div>
          <div class="form-group mr-3 mb-3">
              <input placeholder="Shoe Size" type="text" name="shoe_size" value="{{request()->get('shoe_size')}}" class="form-control-sm form-control">
          </div>
          <div class="form-group mr-3">
            @php $colors = new \App\Colors();
            @endphp
            <select data-placeholder="Select color"  class="form-control select-multiple2" name="color[]" multiple>
              <optgroup label="Colors">
                @foreach ($colors->all() as $id => $name)
                  <option value="{{ $id }}" {{ isset($color) && $color == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
              </optgroup>
            </select>
          </div>
          
          <div class="form-group mr-3 mb-3">
            <strong class="mr-3">Price</strong>
            <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="10000000" data-slider-step="10" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '10000000' }}]" />
          </div>
          <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
        </form>


      </div>
    </div>
  </div>

  @include('partials.flash_messages')

  <div class="productGrid" id="productGrid">
    {!! $products->appends(Request::except('page'))->links() !!}
      <div class="row">
        @foreach ($products as $product)
        <div class="col-md-3 col-xs-6 text-center">
          <a href="{{ route('products.show', $product->id) }}">
            <img src="{{ $product->getMedia(config('constants.media_tags'))->first()
                ? $product->getMedia(config('constants.media_tags'))->first()->getUrl()
                : ''
              }}" class="img-responsive grid-image" alt="" />
            <p>Brand : {{ isset($product->brands) ? $product->brands->name : "" }}</p>
            <p>Transist Status : {{ $product->purchase_status }}</p>
            <p>Location : {{ ($product->location) ? $product->location : "" }}</p>
            <p>Sku : {{ $product->sku }}</p>
            <p>Id : {{ $product->id }}</p>
            <p>Size : {{ $product->size}}</p>
            <p>Price : {{ $product->price_special }}</p>
          </a>
        </div>
        @endforeach
      </div>
      {!! $products->appends(Request::except('page'))->links() !!}
  </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>
    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
    });
  </script>
@endsection
