@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Manual Cropping Grid!</h2>
    </div>
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class="col-md-12">
            @if($products->count())
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Sku</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->short_description }}</td>
                                <td>
                                    <img width="100" src="{{ $product->getMedia('gallery')->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" alt="Image">
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-secondary" href="{{ action('Products\ManualCroppingController@show', $product->id) }}">Manual Crop</a>
                                </td>
                            </tr>
                        @endforeach
                </table>
            @else
                <div class="alert alert-danger text-center">
                    <h3>No Assigned Products!</h3>
                    There are no products assigned to you at the moment. Click the button below to assign one.
                    <br>
                    <br>
                    <a class="btn btn-secondary btn-sm" href="{{ action('Products\ManualCroppingController@assignProductsToUser') }}">Assign Products</a>
                </div>
            @endif
    </div>
@endsection