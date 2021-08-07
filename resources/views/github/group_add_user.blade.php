@extends('layouts.app')

@section('content')
<h2 class="text-center">Add user to <i>{{$group->name}}</i></h2>
<div>
    {{Form::open(array('url' => '/github/groups/users/add', 'method' => 'POST'))}}
    {{ Form::hidden('group_id', $group->id) }}
    <div class="form-group">
        {{ Form::label('username', 'User') }}
        {{ Form::select('username', $users, null , array('class' => 'form-control'))  }}
    </div>
    <div class="form-group">
        {{ Form::label('role', 'Role') }}
        <select name="role" class="form-control">
            <option value="member">Member</option>
            <option value="maintainer">Maintainer</option>
        </select>
    </div>
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>
@endsection