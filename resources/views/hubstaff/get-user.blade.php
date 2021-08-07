@extends('layouts.app')

@section('content')
<h2 class="text-center">Get Users from Hubstaff</h2>
<div class="container">

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
      <strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
	<div class="row">
		<div class="col-md-5">
			<div class="well">
         {!! Form::open(['route' => 'get.users.api']) !!}
          <div>
          	<h3 class="text-center">Obtain Auth Token to work with Hubstaff API</h3>
             <div class="form-group">
                <input class="form-control" name="auth_token" id="auth_token" type="text" placeholder="Your Auth Token" value="@if(auth()->user()->auth_token_hubstaff) {{ auth()->user()->auth_token_hubstaff }} @endif" required>
             </div>
             <div class="form-group">
              <select name="organization_memberships" class="form-control">
               <option value="">Organization Memberships</option>
               <option value="true">True</option>
               <option value="false">False</option>
              </select>
              <small>Include the organization memberships for each user</small>
             </div>

             <div class="form-group">
              <select name="project_memberships" class="form-control">
               <option value="">Project Memberships</option>
               <option value="true">True</option>
               <option value="false">False</option>
              </select>
              <small>Include the project memberships for each user</small>
             </div>
             <div class="form-group">
               <input class="form-control" name="offset" id="offset" type="number" placeholder="offset" min="0" required>
               <small>Index of the first record returned (starts at 0)</small>
             </div>
            
             <br/>
             <div class="text-center">
             	<button class="btn btn-info btn-lg" type="submit">Get Users Details</button>
             </div>
          </div>
         {!! Form::close() !!}
    	 </div>
		</div>
   
</div>
@endsection