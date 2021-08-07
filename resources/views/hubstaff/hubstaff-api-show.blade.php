@extends('layouts.app')

@section('content')
<h2 class="text-center">Users Api from Hubstaff</h2>
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
         {!! Form::open(['route' => 'user.token']) !!}
          <div>
          	<h3 class="text-center">Obtain Auth Token to work with Hubstaff API</h3>
             <div class="form-group">
                <input class="form-control" name="email" id="email" type="email" placeholder="Your Hubstaff Email" required>
             </div>
             <div class="form-group">
               <input class="form-control" name="password" id="password" type="password" placeholder="Your password" required>
             </div>
            
             <br/>
             <div class="text-center">
             	<button class="btn btn-info btn-lg" type="submit">Get Token</button>
             </div>
          </div>
         {!! Form::close() !!}
    	 </div>
		</div>
   
</div>
@endsection