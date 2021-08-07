@extends('layouts.app')

@section('content')
<h2 class="text-center">Get Task from Hubstaff</h2>
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
         {!! Form::open(['route' => 'post-project.task-from-id']) !!}
          <div>
            <h3 class="text-center">Get Task list from projects</h3>
             
             <div class="form-group">
                <input class="form-control" name="auth_token" id="auth_token" type="text" placeholder="Your Auth Token" value="@if(auth()->user()->auth_token_hubstaff) {{ auth()->user()->auth_token_hubstaff }} @endif" required>
             </div>

             <div class="form-group">
               <input type="text" class="form-control" name="id" placeholder="Task Id">
             </div>
            
             <br/>
             <div class="text-center">
              <button class="btn btn-info btn-lg" type="submit">Get User Projects</button>
             </div>
          </div>
         {!! Form::close() !!}
       </div>
    </div>
</div>
@endsection