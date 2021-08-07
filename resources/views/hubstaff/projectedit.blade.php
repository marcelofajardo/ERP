@extends('layouts.app')

@section('content')
<h2 class="text-center">Edit project</h2>
<div>

    {{Form::model($project, array('url' => '/hubstaff/projects/edit', 'method' => 'PUT'))}}
    {{ Form::hidden('id', Input::old('id')) }}
    {{ Form::hidden('hubstaff_project_id', Input::old('hubstaff_project_id')) }}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
    </div>
    
    {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection