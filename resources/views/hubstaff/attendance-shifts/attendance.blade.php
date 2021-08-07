@extends('layouts.app')

<!-- @section('link-css')
  <style type="text/css">
    .form-group{
      padding: 10px;
    }
  </style>
@endsection -->

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

        @if($results->attendance_shifts)
        @if(count($results->attendance_shifts) >= 1)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>User Id</th>
                <th>Organization Id</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Duration</th>
                <th>Minimum Time</th>
                <th>Status</th>
                <th>Actual Start Time</th>
                <th>Actual Stop Time</th>
                <th>Actual Duration</th>

              </tr>
            </thead>
            @foreach($results->attendance_shifts as $attendance)
              <tbody>
                <tr>
                  <td>{{ $attendance->id }}</td>
                  <td>{{ $attendance->user_id }}</td>
                  <td>{{ $attendance->organization_id }}</td>
                  <td>{{ $attendance->created_at }}</td>
                  <td>{{ $attendance->updated_at }}</td>
                  <td>{{ $attendance->date }}</td>
                  <td>{{ $attendance->start_time }}</td>
                  <td>{{ $attendance->duration }}</td>
                  <td>{{ $attendance->minimum_time }}</td>
                  <td>{{ $attendance->status }}</td>
                  <td>{{ $attendance->actual_start_time }}</td>
                  <td>{{ $attendance->actual_stop_time }}</td>
                  <td>{{ $attendance->actual_duration }}</td>
                </tr>
              </tbody>
            @endforeach
          </table>
        @endif

      @else

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Id</th>
            <th>User Id</th>
            <th>Organization Id</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>Duration</th>
            <th>Minimum Time</th>
            <th>Status</th>
            <th>Actual Start Time</th>
            <th>Actual Stop Time</th>
            <th>Actual Duration</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            No Results Found
          </tr>
        </tbody>
      </table>

      @endif

          <br>
          <hr>
          <br>

        @endforeach
      @endforeach

  <!-- </div>       -->
</div>
@endsection