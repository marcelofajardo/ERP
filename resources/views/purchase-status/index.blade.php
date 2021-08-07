@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Purchase Status Management</h2>
            <div class="pull-left">
            </div>
            <div class="pull-right">               
                    <a class="btn btn-secondary" href="{{ route('purchase-status.create') }}">+</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($purchaseStatus as $key => $data)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $data->name }}</td>
                <td><a class="btn btn-image" href="{{ route('purchase-status.edit',$data->id) }}"><img src="/images/edit.png" /></a>
                 
                {!! Form::open(['method' => 'DELETE','route' => ['purchase-status.destroy', $data->id],'style'=>'display:inline']) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
              
                </td>
            </tr>
        @endforeach
    </table>
    </div>


    {!! $purchaseStatus->render() !!}


@endsection
