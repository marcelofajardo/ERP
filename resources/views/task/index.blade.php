@extends('layouts.app')


@section('favicon' , 'task.png')

@section('title', 'Task List')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Task</h2>
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('task.create') }}">+</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered" style="margin-top: 25px">
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Details</th>
                <th>Related to</th>
                <th width="200px">Action</th>
            </tr>
            @foreach ($task as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->details }}</td>
                    <td>{{ $value->related}}</td>
                    <td>
                        <a class="btn btn-image" href="{{ route('task.edit',$value->id) }}"><img src="/images/edit.png"/></a>
                        @if ($value->userid == Auth::id())
                            {!! Form::open(['method' => 'DELETE','route' => ['task.destroy',$value->id],'style'=>'display:inline']) !!}
                            <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {!! $task->links() !!}

@endsection
