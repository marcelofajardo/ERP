@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Sale List</h2>
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
            <th>ID</th>
            <th>Date</th>
            <th>Sales Person</th>
            <th>Client Name</th>
            <th>Work Allocated</th>
            <th>Remark</th>
            <th width="220px">Action</th>
        </tr>
        @foreach ($sales as $key => $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->date_of_request }}</td>
                <td>{{ $sale->sales_person_name ? $users[$sale->sales_person_name] : 'nil' }}</td>
                <td>{{ $sale->client_name }}</td>
                <td>{{ $sale->allocated_to ? $users[$sale->allocated_to] : 'nil' }}</td>
                <td>{{ $sale->remark }}</td>
                <td>
                    <a class="btn btn-image" href="{{ route('sales.show',$sale->id) }}"><img src="/images/view.png" /></a>
                    <a class="btn btn-image" href="{{ route('sales.edit',$sale->id) }}"><img src="/images/edit.png" /></a>
                    {!! Form::open(['method' => 'DELETE','route' => ['sales.destroy', $sale->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>
    </div>

    {!! $sales->links() !!}


@endsection
