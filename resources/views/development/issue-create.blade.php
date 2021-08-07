@extends('layouts.app')
@section('content')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"> --}}
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">
			<h2>Submit an Issue</h2>
		</div>
		<div class="pull-right">
			<a class="btn btn-secondary" href="{{ route('development.index') }}"> Back</a>
		</div>
	</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
	<p>{{ $message }}</p>
</div>
@endif

@if ($errors->any())
		<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
						@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
						@endforeach
				</ul>
		</div>
@endif

<form action="{{ route('development.issue.store') }}" method="POST" enctype="multipart/form-data">
	@csrf

	<div class="form-group">
		<strong>Attach files:</strong>
		<input type="file" name="images[]" class="form-control" multiple>
		@if ($errors->has('images'))
		<div class="alert alert-danger">{{$errors->first('images')}}</div>
		@endif
	</div>

	<div class="form-group">
		<strong>Issue:</strong>
		<textarea name="issue" class="form-control" rows="8" cols="80" required>{{ old('issue') }}</textarea>
		@if ($errors->has('issue'))
		<div class="alert alert-danger">{{$errors->first('issue')}}</div>
		@endif
	</div>

	<div class="form-group">
		<strong>Priority:</strong>
		<select class="form-control" name="priority" required>
			<option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
			<option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
			<option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
	 </select>

		@if ($errors->has('priority'))
				<div class="alert alert-danger">{{$errors->first('priority')}}</div>
		@endif
	</div>

	<button type="submit" class="btn btn-secondary">Submit Issue</button>
</form>

<h3>Modules</h3>

<form class="form-inline" action="{{ route('development.module.store') }}" method="POST">
	@csrf

	{{-- <input type="hidden" name="priority" value="5">
	<input type="hidden" name="status" value="Planned"> --}}
	<div class="form-group">
		<input type="text" class="form-control" name="name" placeholder="Module" value="{{ old('name') }}" required>

		@if ($errors->has('name'))
			<div class="alert alert-danger">{{$errors->first('name')}}</div>
		@endif
	</div>

	<button type="submit" class="btn btn-secondary ml-3">Add Module</button>
</form>

@endsection
