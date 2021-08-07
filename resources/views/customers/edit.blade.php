@extends('layouts.app')
@section('content')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"> --}}
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">
			<h2>Edit Customer</h2>
		</div>
		<div class="pull-right">
			<a class="btn btn-secondary" href="{{ route('customer.index') }}"> Back</a>

		</div>
	</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
	<p>{{ $message }}</p>
</div>
@endif

<form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Client Name:</strong>
				<input type="text" class="form-control" name="name" placeholder="Client Name" value="{{ $customer->name }}" required />
				@if ($errors->has('name'))
				<div class="alert alert-danger">{{$errors->first('name')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Email:</strong>
				<input type="email" class="form-control" name="email" placeholder="example@example.com" value="{{ $customer->email }}"/>
				@if ($errors->has('email'))
				<div class="alert alert-danger">{{$errors->first('email')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Phone:</strong>
				<input type="number" class="form-control" name="phone" placeholder="900000000" value="{{ str_replace('+', '', $customer->phone) }}" />
				@if ($errors->has('phone'))
				<div class="alert alert-danger">{{$errors->first('phone')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Solo phone:</strong>
				<Select name="whatsapp_number" class="form-control">
					<option value>None</option>
					@foreach ($solo_numbers as $number => $name)
						<option value="{{ $number }}" {{ $customer->whatsapp_number == $number ? 'selected' : '' }}>{{ $name }}</option>
					@endforeach
				</Select>
				@if ($errors->has('whatsapp_number'))
						<div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Instagram Handle:</strong>
				<input type="text" class="form-control" name="instahandler" placeholder="instahandle" value="{{ $customer->instahandler }}" />
				@if ($errors->has('instahandler'))
				<div class="alert alert-danger">{{$errors->first('instahandler')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Rating:</strong>
				<Select name="rating" class="form-control">
					<option value="1" {{ $customer->rating == '1' ? 'selected' : '' }}>1</option>
					<option value="2" {{ $customer->rating == '2' ? 'selected' : '' }}>2</option>
					<option value="3" {{ $customer->rating == '3' ? 'selected' : '' }}>3</option>
					<option value="4" {{ $customer->rating == '4' ? 'selected' : '' }}>4</option>
					<option value="5" {{ $customer->rating == '5' ? 'selected' : '' }}>5</option>
					<option value="6" {{ $customer->rating == '6' ? 'selected' : '' }}>6</option>
					<option value="7" {{ $customer->rating == '7' ? 'selected' : '' }}>7</option>
					<option value="8" {{ $customer->rating == '8' ? 'selected' : '' }}>8</option>
					<option value="9" {{ $customer->rating == '9' ? 'selected' : '' }}>9</option>
					<option value="10" {{ $customer->rating == '10' ? 'selected' : '' }}>10</option>
				</Select>
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Address:</strong>
				<input type="text" class="form-control" name="address" placeholder="Street, Apartment" value="{{ $customer->address }}" />
				@if ($errors->has('address'))
				<div class="alert alert-danger">{{$errors->first('address')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>City:</strong>
				<input type="text" class="form-control" name="city" placeholder="Mumbai" value="{{ $customer->city }}" />
				@if ($errors->has('city'))
				<div class="alert alert-danger">{{$errors->first('city')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Country:</strong>
				<input type="text" class="form-control" name="country" placeholder="India" value="{{ $customer->country }}" />
				@if ($errors->has('country'))
				<div class="alert alert-danger">{{$errors->first('country')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Pincode:</strong>
				<input type="number" class="form-control" name="pincode" max="999999" placeholder="411060" value="{{ $customer->pincode }}" />
				@if ($errors->has('pincode'))
				<div class="alert alert-danger">{{$errors->first('pincode')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<strong>Credit:</strong>
				<input type="number" class="form-control" name="credit" value="{{ $customer->credit }}" />
				@if ($errors->has('credit'))
				<div class="alert alert-danger">{{$errors->first('credit')}}</div>
				@endif
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<input type="checkbox" name="do_not_disturb" id="do_not_disturb" {{ $customer->do_not_disturb ? 'checked' : '' }}>
				<label for="do_not_disturb">Do Not Disturb</label>
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2">
			<div class="form-group">
				<input type="checkbox" name="is_blocked" id="is_blocked" {{ $customer->is_blocked ? 'checked' : '' }}>
				<label for="is_blocked">Add to Blocked List</label>
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">
			<button type="submit" class="btn btn-secondary" id="submitButton">+</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	$('#submitButton').on('click', function(e) {
		e.preventDefault();

		var phone = $('input[name="phone"]').val();

		if (phone.length != 0) {
			if (/^[91]{2}/.test(phone) != true) {
				$('input[name="phone"]').val('91' + phone);
			}
		}

		$(this).closest('form').submit();
	});
</script>

@endsection
