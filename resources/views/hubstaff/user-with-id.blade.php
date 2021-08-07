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

<h2 class="text-center">User from Hubstaff</h2>

<div class="container">

<div class="row">
  <!-- <div class="token"> -->
    @foreach($results as $key => $value)

      <div class="form-group">
         <label for="token">Id: </label>
        <input class="form-control" type="text" name="user" value="{{ $value->id }}" disabled="">
        <small>Organization owner Name</small>
      </div>

      <div class="form-group">
         <label for="token">User Name: </label>
        <input class="form-control" type="text" name="user" value="{{ $value->name }}" disabled="">
        <small>Organization owner Name</small>
      </div>
     
      <div class="form-group">
         <label for="id">Last Activity: </label>
        <input class="form-control" type="text" name="id" value="{{ $value->last_activity }}" disabled="">
      </div>

      <div class="form-group">
         <label for="email">Email: </label>
        <input class="form-control" type="text" name="email" value="{{ $value->email }}" disabled="">
      </div>

    @endforeach
  <!-- </div>       -->
</div>
@endsection