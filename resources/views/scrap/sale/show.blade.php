@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $title }}</h2>
        </div>
        <div class="col-md-12">
            <div class="text-center">
                <div class="text-center">
                    {!! $products->links() !!}
                </div>
            </div>
            <div class="row">
                @if($products->count())
                    @foreach($products as $product)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-image">
                                    <img style="width: 100%;" src="{!! (strpos($product->images[0], 'http')===false) ? asset('uploads/social-media/'.$product->images[0]) : $product->images[0] !!}">
                                </div><!-- card image -->

                                <div class="card-content">
                                    <span class="card-title">
                                        <div style="font-size: 18px;">
                                            {{ $product->title }}
                                            <br>
                                            {{ $product->sku }}
                                        </div>
                                        <div style="font-size: 14px;">
                                            <strong>{{ $product->brand }}</strong>
                                            <br>
                                            {{ $product->country ?? 'N/A' }}
                                        </div>
                                        <div style="font-size: 14px;">
                                            <strong>Old Price: {!! $product->old_price ?? 'N/A' !!}</strong><br>
                                            <strong>New Price: {!! $product->new_price ?? 'N/A' !!}</strong>
                                        </div>
                                        <div style="font-size: 14px;">
                                            <strong>Dimensions: </strong> {{ $product->dimension }}
                                        </div>
                                    </span>
                                </div>
                                <div class="card-action">
                                    <p><a href="{{$product->product_link}}">Visit Product Page</a></p>
                                    <p>
                                        {!! $product->description !!}
                                    </p>
                                    <p>
                                        <strong>Sizes</strong>
                                            @if($product->sizes)
                                                @foreach($product->sizes as $size)
                                                    <li>{{ $size }}</li>
                                                @endforeach
                                            @endif
                                        </p>
                                    <p>
                                        <strong>Material Used</strong>
                                        {{ $product->material_used }}<br>
                                        <strong>Color</strong>
                                        <div style="width: 50px;height: 50px;background: #{{$product->color}}"></div>
                                    </p>
                                    <p>
                                        <strong>Category</strong>
                                        @foreach($product->category as $cat)
                                            <li>{{ $cat }}</li>
                                        @endforeach
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-center m-5">
                        There are no scraped images for this website at the moment.
                    </h3>
                @endif
            </div>
            <div class="text-center">
                {!! $products->links() !!}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection