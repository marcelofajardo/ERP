@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Refund</h2>

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
        {!! Form::open(array('route' => ['refund.update', $refund->id],'method'=>'PUT')) !!}

        <div class="form-group">
            <strong>Client:</strong>
            <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer" id="customer_id" required>
              @foreach ($customers as $customer)
               <option data-tokens="{{ $customer->name }} {{ $customer->email }}  {{ $customer->phone }} {{ $customer->instahandler }}" value="{{ $customer->id }}" {{ $customer->id == $refund->customer_id ? 'selected' : '' }}>{{ $customer->name }} - {{ $customer->phone }}</option>
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
  					<option value="Cash" {{ $refund->type == 'Cash' ? 'selected' : '' }}>Cash</option>
  					<option value="Bank Transfer" {{ $refund->type == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
  				</select>
  				@if ($errors->has('type'))
  						<div class="alert alert-danger">{{$errors->first('type')}}</div>
  				@endif
  			</div>

        <div class="form-group">
  				<strong>CHQ Number:</strong>
  				<input type="text" name="chq_number" class="form-control" placeholder="00000000" value="{{ $refund->chq_number }}">
  				@if ($errors->has('chq_number'))
  						<div class="alert alert-danger">{{$errors->first('chq_number')}}</div>
  				@endif
  			</div>

        <div class="form-group">
          <strong>Date of Refund Request:</strong>
          <div class='input-group date' id='date_of_request'>
            <input type='text' class="form-control" name="date_of_request" value="{{ $refund->date_of_request }}" />

            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>

          @if ($errors->has('date_of_request'))
              <div class="alert alert-danger">{{$errors->first('date_of_request')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Date of Issue:</strong> {{ $refund->date_of_issue ? \Carbon\Carbon::parse($refund->date_of_issue)->format('d-m') : '' }}
        </div>

        <div class="form-group">
          <strong>Details:</strong>
          <textarea name="details" rows="8" cols="80" class="form-control">{{ $refund->details }}</textarea>

          @if ($errors->has('details'))
              <div class="alert alert-danger">{{$errors->first('details')}}</div>
          @endif
        </div>

        <div class="form-group">
          <input type="checkbox" name="dispatched" id="dispatch_date" {{ $refund->dispatch_date ? 'checked' : '' }}>
          <label for="dispatch_date">Mark as Dispatched</label>
        </div>

        <div id="additional-fields" style="display: none;">
          <div class="form-group">
            <strong>Date of Dispatch</strong>
            <div class='input-group date' id='dispatch_date'>
              <input type='text' class="form-control" name="dispatch_date" value="{{ $refund->dispatch_date }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('dispatch_date'))
                <div class="alert alert-danger">{{$errors->first('dispatch_date')}}</div>
            @endif
          </div>

          <div class="form-group">
    				<strong>AWB:</strong>
    				<input type="text" name="awb" class="form-control" placeholder="00000000" value="{{ $refund->awb }}">
    				@if ($errors->has('awb'))
    						<div class="alert alert-danger">{{$errors->first('awb')}}</div>
    				@endif
    			</div>
        </div>

        <div class="form-group">
          <input type="checkbox" name="credited" id="credited" {{ $refund->credited ? 'checked' : '' }}>
          <label for="credited">Mark as Credited</label>
        </div>

        <button type="submit" class="btn btn-secondary">Update</button>

        {!! Form::close() !!}
      </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script>
    $(document).ready(function() {
      var customer_id = {{ $refund->customer_id }};
      var order_id = {{ $refund->order_id }};
      var orders_array = {!! json_encode($orders_array) !!};
      var filtered_orders = orders_array.filter(function (el) {
        return el.customer_id == customer_id;
      });

      var select_orders = '';
      var selected = '';
      filtered_orders.forEach(function(order) {
        selected = order.id == order_id ? "selected" : "";
        select_orders += '<option value="' + order.id + '" ' + selected + '>' + order.order_id + '</option>';
      });

      $('#order_id').html(select_orders);

      if ($('#dispatch_date').prop('checked')) {
        $('#additional-fields').show();
      } else {
        $('#additional-fields').hide();
      }
    });

    $('#date_of_request, #dispatch_date').datetimepicker({
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

    $('#dispatch_date').on('click', function() {
      if ($(this).prop('checked')) {
        $('#additional-fields').show();
      } else {
        $('#additional-fields').hide();
      }
    });
  </script>
@endsection
