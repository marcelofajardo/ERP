@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Organizations list from Hubstaff API </h2>

<div class="container">
  <div class="row">
    @if($results->organizations)
      @if(count($results->organizations) >= 1)
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>Project Name</th>
              <th>Last Activity</th>
            </tr>
          </thead>
          @foreach($results->organizations as $org)
            <tbody>
              <tr>
                <td>{{ $org->id }}</td>
                <td>{{ $org->name }}</td>
                <td>{{ $org->last_activity }}</td>
              </tr>
            </tbody>
          @endforeach
        </table>
      @endif
    @endif
  </div>
</div>
@endsection