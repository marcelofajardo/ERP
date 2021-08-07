@extends('layouts.app')

@section('link-css')
  <style type="text/css">
    .form-group{
      padding: 10px;
    }
  </style>
@endsection

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Users List from Hubstaff API </h2>

<div class="container">

<div class="row">
  <!-- <div class="token"> -->
      @foreach($results as $key => $value) 
        @foreach($value as $user)
          <div class="form-group">
             <label for="token">User Name: </label>
            <input class="form-control" type="text" name="user" value="{{ $user->name }}" disabled="">
            <small>Organization owner Name</small>
          </div>
         
          <div class="form-group">
             <label for="id">Last Activity: </label>
            <input class="form-control" type="text" name="id" value="{{ $user->last_activity }}" disabled="">
          </div>

          <div class="form-group">
             <label for="email">Email: </label>
            <input class="form-control" type="text" name="email" value="{{ $user->email }}" disabled="">
          </div>

          @if($user->projects)
            @if(count($user->projects) >= 1)
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Project Name</th>
                    <th>Last Activity</th>
                    <th>Status</th>
                  </tr>
                </thead>
                @foreach($user->projects as $project)
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

          <br>
          <hr>
          <br>

        @endforeach
      @endforeach

  <!-- </div>       -->
</div>
@endsection