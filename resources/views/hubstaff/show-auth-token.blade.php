@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">User details from Hubstaff API</h2>

<div class="container">

<div class="row">
  <div class="token">
    
    <div class="form-group">
       <label for="token">Token: </label>
      <input class="form-control" type="text" name="token" value="{{ $authToken }}">
      <small>You can use this token to get results in other api calls.</small>
    </div>
   
    <div class="form-group">
       <label for="id">ID: </label>
      <input class="form-control" type="text" name="id" value="{{ $users[0]->id }}" disabled="">
    </div>

    <div class="form-group">
       <label for="email">Name: </label>
      <input class="form-control" type="text" name="email" value="{{ $users[0]->name }}" disabled="">
    </div>

    <div class="form-group">
       <label for="email">Email: </label>
      <input class="form-control" type="text" name="email" value="{{ $users[0]->email }}" disabled="">
    </div>

    <div class="form-group">
       <label for="id">Last Activity: </label>
      <input class="form-control" type="text" name="id" value="{{ $users[0]->last_activity }}" disabled="">
    </div>

  </div>	    
</div>
@endsection