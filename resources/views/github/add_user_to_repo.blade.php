@extends('layouts.app')

@section('content')
<h2 class="text-center">Add user to repository</h2>
<div>
    {{Form::open(array('url' => '/github/add_user_to_repo', 'method' => 'POST'))}}
    {{ Form::hidden('repo_name', Input::old('repo_name')) }}
    <div class="form-group">
        {{ Form::label('username', 'Users') }}
        {{ Form::select('username', $users, null , array('class' => 'form-control'))  }}
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