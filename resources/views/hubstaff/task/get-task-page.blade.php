@extends('layouts.app')

@section('link-css')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-tagsinput.css') }}">
@endsection

@section('content')
<h2 class="text-center">Get Users from Hubstaff</h2>
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
         {!! Form::open(['route' => 'post.project-task']) !!}
          <div>
            <h3 class="text-center">Get Task list from projects</h3>
             
             <div class="form-group">
                <input class="form-control" name="auth_token" id="auth_token" type="text" placeholder="Your Auth Token" value="@if(auth()->user()->auth_token_hubstaff) {{ auth()->user()->auth_token_hubstaff }} @endif" required>
             </div>
            
             <div class="form-group">
               <input type="text" name="projects" value="" data-role="tagsinput" class="form-control" placeholder="Projects"><br>
               <small>List of project IDs (comma separated)</small>
             </div>

             <div class="form-group">
               <input class="form-control" name="offset" id="offset" type="number" placeholder="Offset" min="0" required>
               <small>Index of the first record returned (starts at 0)</small>
             </div>
            
             <br/>
             <div class="text-center">
              <button class="btn btn-info btn-lg" type="submit">Get Task from Projects</button>
             </div>
          </div>
         {!! Form::close() !!}
       </div>
    </div>
</div>

@section('scripts')
  <script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
@endsection
@endsection