@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Create New Refund</h2>

            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('refund.index') }}">Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        {!! Form::open(array('route' => 'refund.store','method'=>'POST')) !!}

        <div class="form-group">
            <strong>Client:</strong>
            <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer" id="customer_id" required>
              @foreach ($customers as $customer)
               <option data-tokens="{{ $customer->name }} {{ $customer->email }}  {{ $customer->phone }} {{ $customer->instahandler }}" value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
             @endforeach
           </select>

            @if ($errors->has('customer_id'))
                <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
            @endif
        </div>

        <div class="form-group">
          <strong>Order:</strong>
          <select class="form-control" name="order_id" id="order_id" required>

          </select>
        </div>

  			<div class="form-group">
  				<strong>Refund Type:</strong>
  				<select name="type" class="form-control" required>
  					<option value="Cash" {{ old('type') == 'Cash' ? 'selected' : '' }}>Cash</option>
  					<option value="Bank Transfer" {{ old('type') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
  				</select>
  				@if ($errors->has('type'))
  						<div class="alert alert-danger">{{$errors->first('type')}}</div>
  				@endif
  			</div>

        <div class="form-group">
  				<strong>CHQ Number:</strong>
  				<input type="text" name="chq_number" class="form-control" placeholder="00000000" value="{{ old('chq_number') }}">
  				@if ($errors->has('chq_number'))
  						<div class="alert alert-danger">{{$errors->first('chq_number')}}</div>
  				@endif
  			</div>

        <div class="form-group">
          <strong>Date of Refund Request:</strong>
          <div class='input-group date' id='date_of_request'>
            <input type='text' class="form-control" name="date_of_request" value="{{ date('Y-m-d H:i') }}" />

            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>

          @if ($errors->has('date_of_request'))
              <div class="alert alert-danger">{{$errors->first('date_of_request')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Details:</strong>
          <textarea name="details" rows="8" cols="80" class="form-control">{{ old('details') }}</textarea>

          @if ($errors->has('details'))
              <div class="alert alert-danger">{{$errors->first('details')}}</div>
          @endif
        </div>

        <button type="submit" class="btn btn-secondary">Create Refund</button>

        {!! Form::close() !!}
      </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script>
    $('#date_of_request').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });

    $('#customer_id').on('change', function() {
      var thiss = $(this);
      var orders_array = {!! json_encode($orders_array) !!};
      var filtered_orders = orders_array.filter(function (el) {
        return el.customer_id == $(thiss).val();
      });

      var select_orders = '';
      filtered_orders.forEach(function(order) {
        select_orders += '<option value="' + order.id + '">' + order.order_id + '</option>';
      });

      $('#order_id').html(select_orders);
    });
  </script>
@endsection
