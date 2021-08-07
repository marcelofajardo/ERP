@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Users Project list from Hubstaff API </h2>

<div class="container">

<div class="row">
  <!-- <div class="token"> -->
      @if($results->projects)
        @if(count($results->projects) >= 1)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>Project Name</th>
                <th>Last Activity</th>
                <th>Status</th>
              </tr>
            </thead>
            @foreach($results->projects as $project)
              <tbody>
                <tr>
                  <td>{{ $project->id }}</td>
                  <td>{{ $project->name }}</td>
                  <td>{{ $project->last_activity }}</td>
                  <td>
                    @if($project->status == "Active")
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-danger">In active</span>
                    @endif
                  </td>
                </tr>
              </tbody>
            @endforeach
          </table>
        @endif
      @endif
  <!-- </div>       -->
</div>
@endsection