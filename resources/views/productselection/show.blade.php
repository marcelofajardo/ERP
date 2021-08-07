@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('productselection.index') }}"> Back</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>SKU:</strong>
                {{ $productselection->sku }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{--<strong>Image:</strong>--}}
                <img src="/uploads/{{ $productselection->image }}" class="img-responsive" alt="">
            </div>
        </div>
    </div>
@endsection
