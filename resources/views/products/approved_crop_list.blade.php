@extends('layouts.app')

@section('favicon' , 'approvedcroppergrid.png')
@section('title', 'Approved Crop Grid- ERP Sololuxury')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">
                Cropped Images ({{$products->total()}})
            </h2>
        </div>
        <div class="col-md-12">
            <form method="get" action="{{ action('ProductCropperController@getApprovedImages') }}">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="">Select User...</option>
                            @if ( is_array($users) )
                                @foreach($users as $user)
                                    <option {{ $user->id == $user_id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary">Filter</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $products->appends(Request::except('page'))->links() !!}
                </div>
            </div>
            <div class="row">
                @if ( is_array($products) )
                    @foreach($products as $product)
                        <div class="col-md-4 mt-2">
                            <div class="card">
                                <img class="card-img-top" src="{{ $product->imageurl }}" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->title }}</h5>
                                    <p class="card-text">
                                        <a href="{{ action('ProductController@show', $product->id) }}">{{ $product->sku }}</a><br>
                                        {{ $product->supplier }}<br>
                                        Approver: {{ $product->cropApprover ? $product->cropApprover->name : 'N/A' }}
                                    </p>
                                    <a href="{{ action('ProductCropperController@showImageToBeVerified', $product->id) }}" class="btn btn-primary">Check Cropping</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $products->appends(Request::except('page'))->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection