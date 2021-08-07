@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row">
		  <div class="col-md-12">
		    <div class="panel panel-default">
		      <div class="panel-heading">Resources</div>
		      <div class="panel-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
		        <h2>Edit Resources Center 
		        	<!-- <div class="btn-group pull-right">
						<button type="button" class="btn btn-image" title="Add Category" data-toggle="modal" data-target="#addcategory">
		        			<i class="fa fa-plus"></i>
		        	   	</button>
		        	   	<button type="button" class="btn btn-image" title="Edit Category" data-toggle="modal" data-target="#editcategory">
		        			<i class="fa fa-pencil"></i>
		        	   	</button>
		        	   	<button type="button" class="btn btn-image" title="Add Resource" data-toggle="modal" data-target="#addresource">
		        	   		<i class="fa fa-plus"></i>
		        	   	</button>
		        	</div> -->
		        </h2><hr>
		        {!! Form::open(['route'=>'edit.resourceCat']) !!}
					<div class="row">
						<div class="col-md-12">
			                <div class="row">
			                	<div class="col-md-8 col-md-offset-2">
	                                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
	                                    {!! Form::label('Category:') !!}
	                	                <?=@$Categories?>
	                                    <span class="text-danger">{{ $errors->first('parent_id') }}</span>
	                                </div>
			                	</div>
			                	<div class="col-md-8 col-md-offset-2">
			  		                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
			  		                    {!! Form::label('Category Name:') !!}
			  			                <input type="text" name="title" class="form-control" value="{{$title}}" required placeholder="Create Category">
			  		                    <span class="text-danger">{{ $errors->first('title') }}</span>
			  		                </div>
			                	</div>
			                </div>
			                <div class="row">
			                	<div class="col-md-8 col-md-offset-2">
                			    	<button type="submit" name="type" value="edit" class="btn btn-image"><i class="fa fa-pencil"></i></button>
			                	</div>
			                </div>
			            </div>
					</div>
				{!! Form::close() !!}
		      </div>
		    </div>
		  </div>
		</div>
	</div>
@endsection