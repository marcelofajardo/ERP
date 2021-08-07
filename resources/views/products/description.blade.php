@extends('layouts.app')


@section('favicon' , 'productstats.png')


@section('title', 'Product Description')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Total Product found ({{$products_count}})</h2>
        </div>
    </div>
    <form action="{{ action('ProductController@productDescription') }}" method="get">
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="form-group">
                <select class="form-control" name="supplier" id="supplier">
                    <option value="">Supplier</option>
                    @foreach($supplier as  $k => $val)
                        <option {{ $request->get('supplier')==$val->id ? 'selected' : '' }} value="{{ $val->id }}">{{ ucwords($val->supplier) }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="col-md-3">
            <input type="text" name="product_id" class="form-control" id="product_id" placeholder="Enter Product ID" value="{{isset($request->product_id) ? $request->product_id : ''}}">
            </div>
            <div class="col-md-3">
            <input type="text" name="sku" class="form-control" id="sku" placeholder="Enter SKU" value="{{isset($request->sku) ? $request->sku : ''}}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-image btn-default">
                    <img src="{{ asset('images/search.png') }}" alt="Search">
                </button>
            </div>
        </div>
    </form>
    
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $products->appends($request->except('page'))->links() }}.
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Product ID</th>
                    <th>SKU</th>
                    <th>Supplier</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Category</th>
                    <th>Composition</th>
                    <th>Price</th>
                    <th>Size System</th>
                    <th>Discount</th>
                    <th>Dimensions</th>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="{{ action('ProductController@show', $product->id) }}">{{$product->product_id}}</a>
                        </td>
                        <td>
                            {{isset($product->product->sku) ? $product->product->sku : "-"}}
                        </td>
                        <td>
                            {{isset($product->supplier->supplier) ? $product->supplier->supplier : "-"}}
                        </td>
                        <td>
                            {{isset($product->title) ? $product->title : "-"}}
                        </td>
                        <td>
                            {{isset($product->description) ? $product->description : "-"}}
                        </td>
                        <td>
                            {{isset($product->color) ? $product->color : "-"}}
                        </td>
                        <td>
                            {{isset($product->size) ? $product->size : "-"}}
                        </td>
                        <td>
                            {{isset($product->product->category) ? $product->product->categories->title : "-"}}
                        </td>
                        <td>
                            {{isset($product->composition) ? $product->composition : "-"}}
                        </td>
                        <td>
                            {{isset($product->price) ? $product->price : "-"}}
                        </td>
                        <td>
                            {{isset($product->size_system) ? $product->size_system : "-"}}
                        </td>
                        <td>
                            {{isset($product->price_discounted) ? $product->price_discounted : "-"}}
                        </td>
                        <td>
                            {{isset($product->product) ? $product->product->lmeasurement.",".$product->product->hmeasurement.",".$product->product->dmeasurement : "-"}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $products->appends($request->except('page'))->links() }}.
        </div>
    </div>
@endsection


@section('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("#select_date").datepicker({
	  	format: 'yyyy-mm-dd'
	});
    </script>
@endsection