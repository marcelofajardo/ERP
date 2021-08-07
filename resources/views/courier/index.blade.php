@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Courier Management</h2>
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
                <strong>Courier</strong>
            </div>
            <div class="panel-body">
                <form action="{{ action('CourierController@store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Courier Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button class="btn btn-secondary">Add Courier</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @if($courier->count())
            <table class="table-bordered table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach($courier as $key=>$courierRaw)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $courierRaw->name }}</td>
                        <td>
                            <form method="post" action="{{ action('CourierController@destroy', $courierRaw->id) }}">
                                <button class="btn btn-image btn-xs">
                                    @csrf
                                    @method('DELETE')
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete Courier">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="alert alert-info">
                <h3>No Courier!</h3>
                <p>There are no Courier saved yet. Please save it using the form above!</p>
            </div>
        @endif
    </div>

@endsection