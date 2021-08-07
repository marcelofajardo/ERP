@extends('layouts.app')

@section('content')
<h2 class="text-center">Organization members from Hubstaff</h2>
<div class="container">
@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<div class="row">
  <div class="col-md-5">
    <div class="well">
       {!! Form::open(['route' => 'post.organization-member']) !!}
        <div>
          <h3 class="text-center">Get Organization members</h3>
           
          <div class="form-group">
            <input class="form-control" name="auth_token" id="auth_token" type="text" placeholder="Your Auth Token" value="@if(auth()->user()->auth_token_hubstaff) {{ auth()->user()->auth_token_hubstaff }} @endif" required>
          </div>
          
          <div class="form-group">
            <input class="form-control" name="id" id="id" type="text" placeholder="Organization Id" required>
          </div>

          <div class="form-group">
            <select name="include_removed" class="form-control">
             <option value="">Include removed</option>
             <option value="true">True</option>
             <option value="false">False</option> 
            </select>
            <small>Include users that are removed from the organization</small>
          </div>

          <div class="form-group">
             <input class="form-control" name="offset" id="offset" type="number" placeholder="offset" min="0" required>
             <small>Index of the first record returned (starts at 0)</small>
          </div>
          
          <br/>
          <div class="text-center">
            <button class="btn btn-info btn-lg" type="submit">Get Members</button>
          </div>
        </div>
       {!! Form::close() !!}
     </div>
  </div>
</div>
@endsection