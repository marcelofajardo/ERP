@extends('layouts.app')

@section('link-css')
<style type="text/css">
  .form-group {
    padding: 10px;
  }
</style>
@endsection

@section('content')

@if(Session::has('message'))
<div class="alert alert-success alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>{{ Session::get('message') }}</strong>
</div>
@endif

@if(!empty($auth))
<div class="text-center">
  <p>You are not authorized on hubstaff</p>
  <a class="btn btn-primary" href="{{ $auth }}">Authorize</a>
</div>
@endif

<h2 class="text-center">Tasks List from Hubstaff </h2>

<div class="text-right">
  <a href="/hubstaff/tasks/add" class="btn btn-primary">New</a>
</div>

<div class="container">
  @if(!empty($tasks))
  <div class="row">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Task Id</th>
          <th>Summary</th>
          <th>Action</th>
        </tr>
      </thead>
      @foreach($tasks as $task)
      <tbody>
        <tr>
          <td>{{ $task->hubstaff_task_id }}</td>
          <td>{{ ucwords($task->summary) }}</td>
          <td>
            <span><a href="/hubstaff/tasks/{{$task->id}}">Edit</a></span>
          </td>
        </tr>
      </tbody>
      @endforeach
    </table>
    <br>
    <hr>
  </div>
  @else
  <div style="text-align: center;color: red;font-size: 14px;">
  </div>
  @endif

</div>
@endsection