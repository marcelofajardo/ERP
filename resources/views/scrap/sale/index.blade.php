@extends('layouts.app')

@section('favicon' , 'scraparsales.png')

@section('title', 'Sale Item Info')
s
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Designer's List</h2>
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Supplier</th>
                    <th>Link</th>
                </tr>
                @foreach($items as $key=>$item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $item->supplier }}</td>
                        <td>
                            <a href="{{ action('SalesItemController@show', $item->supplier) }}">Show All</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection
