@extends('layouts.app')
@section('favicon' , 'task.png')
@section('title', 'Create | Email Templates')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Create | Email Templates</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-12 margin-tb">
		<form>
		  <div class="form-row col-md-12">
		    <div class="col-md-6 mb-3">
		      <label for="validationServer01">First name</label>
		      <input type="text" class="form-control is-valid" id="validationServer01" placeholder="First name" value="Mark" required>
		      <div class="valid-feedback">
		        Looks good!
		      </div>
		    </div>
		    <div class="col-md-6 mb-3">
		      <label for="validationServer02">Last name</label>
		      <input type="text" class="form-control is-valid" id="validationServer02" placeholder="Last name" value="Otto" required>
		      <div class="valid-feedback">
		        Looks good!
		      </div>
		    </div>
		  </div>
		  <button class="btn btn-primary" type="submit">Submit form</button>
		</form>
	</div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>

@endsection

