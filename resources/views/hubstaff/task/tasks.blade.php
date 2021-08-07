@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Tasks list of Project from Hubstaff API </h2>

<div class="container">

<div class="row">
  <!-- <div class="token"> -->

    @if($results->tasks)
      @if(count($results->tasks) >= 1)
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>Project Id</th>
              <th>Summary</th>
              <th>Details</th>
              <th>Integration Id</th>
              <th>Remote Id</th>
              <th>Remote Alternate Id</th>
              <th>completed At</th>
              <th>Status</th>
            </tr>
          </thead>
          @foreach($results->tasks as $task)
            <tbody>
              <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->project_id }}</td>
                <td>{{ $task->summary }}</td>
                <td>{{ $task->details }}</td>
                <td>{{ $task->integration_id }}</td>
                <td>{{ $task->remote_id }}</td>
                <td>{{ $task->remote_alternate_id }}</td>
                <td>{{ $task->completed_at }}</td>
                <td>{{ $task->status }}</td>
              </tr>
            </tbody>
          @endforeach
        </table>
      @endif
    @endif

  <!-- </div>       -->
</div>
@endsection