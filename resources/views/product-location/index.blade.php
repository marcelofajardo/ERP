@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Location Management</h2>
    </div>
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Location</strong>
            </div>
            <div class="panel-body">
                <form action="{{ action('ProductLocationController@store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Location Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button class="btn btn-secondary">Add Location</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @if($productLocation->count())
            <table class="table-bordered table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach($productLocation as $key=>$productLocationRaw)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productLocationRaw->name }}</td>
                        <td>
                            <form method="post" action="{{ action('ProductLocationController@destroy', $productLocationRaw->id) }}">
                                <button class="btn btn-image btn-xs">
                                    @csrf
                                    @method('DELETE')
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete Product location">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="alert alert-info">
                <h3>No location!</h3>
                <p>There are no location saved yet. Please save it using the form above!</p>
            </div>
        @endif
    </div>

@endsection