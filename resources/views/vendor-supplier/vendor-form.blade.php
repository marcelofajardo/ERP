@extends('layouts.app')

@section('title', 'Vendor Form')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')
<main class="container">
    @include('partials.flash_messages')
    <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <input type="hidden" name="source" value="user">
        <div class="modal-header">
          <h4 class="modal-title">Store a Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Category:</strong>
            <select class="form-control" name="category_id">
              <option value="">Select a Category</option>
              @foreach (\App\VendorCategory::all()->pluck("title","id")->toArray() as $catID => $category)
                <option value="{{ $catID }}" {{ $catID == old('category_id') ? 'selected' : '' }}>{{ $category }}</option>
              @endforeach
            </select>

            @if ($errors->has('category_id'))
              <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Address:</strong>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">

            @if ($errors->has('address'))
              <div class="alert alert-danger">{{$errors->first('address')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Phone:</strong>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">

            @if ($errors->has('phone'))
              <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Social Handle:</strong>
            <input type="text" name="social_handle" class="form-control" value="{{ old('social_handle') }}">

            @if ($errors->has('social_handle'))
              <div class="alert alert-danger">{{$errors->first('social_handle')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Website:</strong>
            <input type="text" name="website" class="form-control" value="{{ old('website') }}">

            @if ($errors->has('website'))
              <div class="alert alert-danger">{{$errors->first('website')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Login:</strong>
            <input type="text" name="login" class="form-control" value="{{ old('login') }}">

            @if ($errors->has('login'))
              <div class="alert alert-danger">{{$errors->first('login')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Password:</strong>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}">

            @if ($errors->has('password'))
              <div class="alert alert-danger">{{$errors->first('password')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>GST:</strong>
            <input type="text" name="gst" class="form-control" value="{{ old('gst') }}">

            @if ($errors->has('gst'))
              <div class="alert alert-danger">{{$errors->first('gst')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Account Name:</strong>
            <input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}">

            @if ($errors->has('account_name'))
              <div class="alert alert-danger">{{$errors->first('account_name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>IBAN:</strong>
            <input type="text" name="account_iban" class="form-control" value="{{ old('account_iban') }}">

            @if ($errors->has('account_iban'))
              <div class="alert alert-danger">{{$errors->first('account_iban')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>SWIFT:</strong>
            <input type="text" name="account_swift" class="form-control" value="{{ old('account_swift') }}">

            @if ($errors->has('account_swift'))
              <div class="alert alert-danger">{{$errors->first('account_swift')}}</div>
            @endif
		  </div>
		  <div class="form-group">
            <strong>Frequency of Payment:</strong>
            <input type="text" name="frequency_of_payment" class="form-control" value="{{ old('frequency_of_payment') }}">

            @if ($errors->has('frequency_of_payment'))
              <div class="alert alert-danger">{{$errors->first('frequency_of_payment')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Bank Name:</strong>
            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">

            @if ($errors->has('bank_name'))
              <div class="alert alert-danger">{{$errors->first('bank_name')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Bank Address:</strong>
            <textarea name="bank_address" class="form-control">{{ old('bank_address') }}</textarea>

            @if ($errors->has('bank_address'))
              <div class="alert alert-danger">{{$errors->first('bank_address')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>City:</strong>
            <input type="text" name="city" class="form-control" value="{{ old('city') }}">

            @if ($errors->has('city'))
              <div class="alert alert-danger">{{$errors->first('city')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Country:</strong>
            <input type="text" name="country" class="form-control" value="{{ old('country') }}">

            @if ($errors->has('country'))
              <div class="alert alert-danger">{{$errors->first('country')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>IFSC:</strong>
            <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}">

            @if ($errors->has('ifsc_code'))
              <div class="alert alert-danger">{{$errors->first('ifsc_code')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Remark:</strong>
            <textarea name="remark" class="form-control">{{ old('remark') }}</textarea>

            @if ($errors->has('remark'))
              <div class="alert alert-danger">{{$errors->first('remark')}}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary">Add</button>
        </div>
      </form>
  </main>
@endsection

@section('scripts')
    <script type="text/javascript">
    
    </script>
@endsection