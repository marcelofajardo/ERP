@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Rejected Stats</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>Supplier</td>
                    <td>Reason</td>
                    <td>Count</td>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>{!! $product->supplier ?? '<strong class="text-danger">N/A</strong>' !!}</td>
                        <td>{{ $product->listing_remark }}</td>
                        <td>{{ $product->total_count }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection