@extends('layouts.app')


@section('favicon' , 'productstats.png')


@section('title', 'Product Status Log')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Total Product found ({{$products_count}})</h2>
        </div>
    </div>
    <form action="{{ action('ProductController@productScrapLog') }}" method="get">
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="form-group">
                <input type="text" name="select_date" class="form-control datepicker" id="select_date" placeholder="Enter Date" value="{{isset($request->select_date) ? $request->select_date : ''}}">
                </div>
            </div>
            <div class="col-md-3">
            <input type="text" name="product_id" class="form-control" id="product_id" placeholder="Enter Product ID" value="{{isset($request->product_id) ? $request->product_id : ''}}">
            </div>
            <div class="col-md-3">
                <div class="form-group">
                <select class="form-control" name="status" id="status">
                    <option value="">Status</option>
                    @foreach($status as  $k => $val)
                        <option {{ $request->get('status')==$k ? 'selected' : '' }} value="{{ $k }}">{{ ucwords($val) }}</option>
                    @endforeach
                </select>
                </div>
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
                    <th>Date</th>
                    <th>Product ID</th>
                    <th>Scrape</th>
                    <th>Auto crop</th>
                    <th>Final approval</th>
                    <th>Is being cropped</th>
                    <th>Is being scraped</th>
                    <th>Pending products without category</th>
                    <th>Request For external Scraper</th>
                    <th>Send external Scraper</th>
                    <th>Finished external Scraper</th>
                    <th>Unknown Color</th>
                    <th>Unknown Size</th>
                    <th>Unknown Composition</th>
                    <th>Unknown Measurement</th>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>{{isset($request->select_date) ? $request->select_date : date('Y-m-d')}}</td>
                        <td>
                            <a href="{{ action('ProductController@show', $product->id) }}">{{$product->id}}</a>
                        </td>
                        <td>
                            {{isset($product->alllog_status[2][0]["created_at"]) ? $product->alllog_status[2][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[4][0]["created_at"]) ? $product->alllog_status[4][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[9][0]["created_at"]) ? $product->alllog_status[9][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[15][0]["created_at"]) ? $product->alllog_status[15][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[20][0]["created_at"]) ? $product->alllog_status[20][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[33][0]["created_at"]) ? $product->alllog_status[33][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[35][0]["created_at"]) ? $product->alllog_status[35][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[46][0]["created_at"]) ? $product->alllog_status[46][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[47][0]["created_at"]) ? $product->alllog_status[47][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[36][0]["created_at"]) ? $product->alllog_status[36][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[38][0]["created_at"]) ? $product->alllog_status[38][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[39][0]["created_at"]) ? $product->alllog_status[39][0]["created_at"] : "NA"}}
                        </td>
                        <td>
                            {{isset($product->alllog_status[40][0]["created_at"]) ? $product->alllog_status[40][0]["created_at"] : "NA"}}
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