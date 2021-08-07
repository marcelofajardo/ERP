@extends('layouts.app')

@section('title', 'Supplier Form')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')
  <main class="container">
    @include('partials.flash_messages')
    <form action="{{ route('supplier.store') }}" method="POST">
        @csrf
        <input type="hidden" name="source" value="user">
        <div class="modal-header">
          <h4 class="modal-title">Store a Supplier</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Supplier:</strong>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}">

            @if ($errors->has('supplier'))
              <div class="alert alert-danger">{{$errors->first('supplier')}}</div>
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
            <strong>GST:</strong>
            <input type="text" name="gst" class="form-control" value="{{ old('gst') }}">

            @if ($errors->has('gst'))
              <div class="alert alert-danger">{{$errors->first('gst')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Supplier Category:</strong>
            {!!Form::select('supplier_category_id', $suppliercategory, old('supplier_category_id') , ['class' => 'form-control form-control-sm'])!!}
          </div>

          <div class="form-group">
            <strong>Supplier Status:</strong>
            {!!Form::select('supplier_status_id', $supplierstatus, old('supplier_status_id'), ['class' => 'form-control form-control-sm'])!!}
          </div>

          <div class="form-group">
            <strong>Scraper Name:</strong>
            <input type="text" name="scraper_name" class="form-control" value="{{ old('scraper_name') }}">

          </div>

          <div class="form-group">
            <strong>Inventory LifeTime:</strong>
            <input type="text" name="inventory_lifetime" class="form-control" value="{{ old('inventory_lifetime') }}">
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