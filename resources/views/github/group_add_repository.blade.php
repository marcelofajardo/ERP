@extends('layouts.app')

@section('content')
<h2 class="text-center">Add repository to <i>{{ $group->name }}</i></h2>
<div>
    {{Form::open(array('url' => '/github/groups/repositories/add', 'method' => 'POST'))}}
    {{ Form::hidden('group_id', $group->id) }}
    <div class="form-group">
        {{ Form::label('repository_name', 'Repository') }}
        {{ Form::select('repository_name', $repositories, null , array('class' => 'form-control'))  }}
    </div>
    <div class="form-group">
        {{ Form::label('permission', 'Permission') }}
        <select name="permission" class="form-control">
            <option value="pull">Pull</option>
            <option value="push">Push</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>
@endsection