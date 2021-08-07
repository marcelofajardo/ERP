@extends('layouts.app')

@section('content')
<h2 class="text-center">{{ isset($isNew)? 'Add task' : 'Edit Task' }}</h2>
<div>

    {{
        isset($isNew)
        ? Form::open(array('url' => '/hubstaff/tasks/addData'))
        : Form::model($task, array('url' => '/hubstaff/tasks/editData', 'method' => 'PUT'))
    }}
    {{ Form::hidden('id', Input::old('id')) }}
    {{ Form::hidden('lock_version', Input::old('lock_version')) }}
    <div class="form-group">
        {{ Form::label('summary', 'Summary') }}
        {{ Form::text('summary', Input::old('summary'), array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('project_id', 'Project') }}
        {{ 
            isset($isNew)
            ? Form::select('project_id', $projects, null , array('class' => 'form-control')) 
            : Form::select('project_id', $projects, $task['project_id'], array('class' => 'form-control')) 
        }}
    </div>
    
    @if(isset($isNew))
    <div class="form-group">
        {{ Form::label('assignee_id', 'Assignee') }}
        {{ Form::select('assignee_id', $users, null , array('class' => 'form-control')) }}
    </div>
    @endif

    {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection