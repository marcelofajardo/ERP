@extends('layouts.app')

@section('favicon' , 'cropapprovalgrid.png')
@section('title', 'Crop Approval Grid - ERP Sololuxury')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">
                Cropped Images ({{$products->total()}})
                <a href="{{ asset('Crop_approval_SOP.pdf') }}" class="pull-right">SOP</a>
            </h2>
        </div>
        <div class="col-md-12">
            <h2>Crop Progress</h2>
            @if(Auth::user()->hasRole('Crop Approval'))
                <form method="get" action="">
                    <input type="date" value="{{ Request::get('date') ?? date('Y-m-d') }}" name="date"> <button class="btn btn-secondary">Ok</button>
                </form>
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>Approved</td>
                        <td>{{ $totalApproved }}</td>
                    </tr>
                    <tr>
                        <td>Rejected</td>
                        <td>{{ $totalRejected }}</td>
                    </tr>
                    <tr>
                        <td>Sequenced</td>
                        <td>{{ $totalSequenced }}</td>
                    </tr>
                </table>
            @else
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>Cropped</td>
                        <td>{{ $stats->cropped }}</td>
                    </tr>
                    <tr>
                        <td>Total Products Scraped</td>
                        <td>{{ $stats->total }}</td>
                    </tr>
                    <tr>
                        <td>To be processed</td>
                        <td>{{ $stats->total-$stats->cropped }}</td>
                    </tr>
                    <tr>
                        <td>Approved</td>
                        <td>{{ $stats->approved }}</td>
                    </tr>
                    <tr>
                        <td>Rejected</td>
                        <td>{{ $stats->rejected }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $stats->total > 0 ? ($stats->cropped/$stats->total)*100 : 100 }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><strong>{{ $stats->cropped }} of {{$stats->total}}</strong></div>
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        </div>
        <div class="col-md-12">
            <h2 class="page-heading">My Rejected Crops ({{ $rejectedCrops->total() }})</h2>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $rejectedCrops->links() !!}
                </div>
            </div>
            <div class="row">
                @foreach($rejectedCrops as $product)
                    <div class="col-md-4 mt-2">
                        <div class="card">
                            <img class="card-img-top" src="{{ $product->imageurl }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->title }}</h5>
                                <p class="card-text">
                                    {{ $product->sku }}<br>
                                    {{ $product->supplier }}<br>
                                    <strong>Reason: {{ $product->crop_remark ?? 'N/A' }}</strong>
                                </p>
                                <a href="{{ action('ProductCropperController@showImageToBeVerified', $product->id) }}?rejected=yes" class="btn btn-primary">Check Cropping</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $rejectedCrops->links() !!}
                </div>
            </div>
        </div>
        @if($rejectedCrops->total() == 0)
            <div class="col-md-12">
                <h2 class="page-heading">Cropped Products</h2>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 text-center">
                        {!! $products->links() !!}
                    </div>
                </div>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 mt-2">
                            <div class="card">
                                <img class="card-img-top" src="{{ $product->imageurl }}" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->title }}</h5>
                                    <p class="card-text">
                                        {{ $product->sku }}<br>
                                        {{ $product->supplier }}
                                    </p>
                                    <a href="{{ action('ProductCropperController@showImageToBeVerified', $product->id) }}" class="btn btn-primary">Check Cropping</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        {!! $products->links() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection