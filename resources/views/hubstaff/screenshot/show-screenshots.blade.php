@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Screenshot list from Hubstaff API </h2>

<div class="container">

<div class="row">
  <div class="token">

      @if($results->screenshots)
        @if(count($results->screenshots) >= 1)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>Url</th>
                <th>Time Slot</th>
                <th>Recorded at</th>
                <th>User Id</th>
                <th>Project Id</th>
                <th>Offset x</th>
                <th>Offset y</th>
                <th>Width</th>
                <th>Height</th>
                <th>Screen</th>
              </tr>
            </thead>
            @foreach($results->screenshots as $screenshot)
              <tbody>
                <tr>
                  <td>{{ $screenshot->id }}</td>
                  <td><a href="{{ $screenshot->url }}" target="_blank">Screenshot URL</a></td>
                  <td>{{ $screenshot->time_slot }}</td>
                  <td>{{ $screenshot->recorded_at }}</td>
                  <td>{{ $screenshot->user_id }}</td>
                  <td>{{ $screenshot->project_id }}</td>
                  <td>{{ $screenshot->offset_x }}</td>
                  <td>{{ $screenshot->offset_y }}</td>
                  <td>{{ $screenshot->width }}</td>
                  <td>{{ $screenshot->height }}</td>
                  <td>{{ $screenshot->screen }}</td>
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
            <th>Url</th>
            <th>Time Slot</th>
            <th>Recorded at</th>
            <th>User Id</th>
            <th>Project Id</th>
            <th>Offset x</th>
            <th>Offset y</th>
            <th>Width</th>
            <th>Height</th>
            <th>Screen</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            No Results Found
          </tr>
        </tbody>
      </table>

      @endif

  </div>      
</div>
@endsection