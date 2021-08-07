@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Specific Task from Hubstaff API </h2>

<div class="container">

<div class="row">
  <!-- <div class="token"> -->
      @if($results->task)
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
            
              <tbody>
                <tr>
                  <td>{{ $results->task->id }}</td>
                  <td>{{ $results->task->project_id }}</td>
                  <td>{{ $results->task->summary }}</td>
                  <td>{{ $results->task->details }}</td>
                  <td>{{ $results->task->integration_id }}</td>
                  <td>{{ $results->task->remote_id }}</td>
                  <td>{{ $results->task->remote_alternate_id }}</td>
                  <td>{{ $results->task->completed_at }}</td>
                  <td>{{ $results->task->status }}</td>
                </tr>
              </tbody>
            
          </table>
      @endif
  <!-- </div>       -->
</div>
@endsection