
    <form action="{{route('voucher.payment.manual-payment-submit')}}" method="post">
    @csrf

    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">



<div class="form-group">
    <strong>Payment to:</strong>
    <select class="form-control select-multiple" name="user_id" id="user-select" required>
        <option value="">Select User</option>
            @foreach($users as $key => $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
  </div>


<div class="form-group">
  <strong>Date :</strong>
  <div class='input-group date' id='date_of_payment'>
    <input type='text' class="form-control" name="date" value="{{ request('date') ?? date('Y-m-d') }}" />

    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
<br>
  <div class="form-group">
    <strong>Amount:</strong>
    <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required>

    @if ($errors->has('amount'))
      <div class="alert alert-danger">{{$errors->first('amount')}}</div>
    @endif
  </div>

  <div class="form-group">
    <strong>Currency:</strong>
    <!--input type="text" name="currency" class="form-control" value="{{ old('currency') }}" required-->
    <select name="currency" class="form-control currency-select2" required>
      <option value="">Select Currency</option>
      @foreach($currencies as $currency)  
        <option @if($currency->code == old('currency')) selected @endif value="{{$currency->code}}">{{$currency->name}}</option>
      @endforeach
    </select>
    @if ($errors->has('currency'))
      <div class="alert alert-danger">{{$errors->first('currency')}}</div>
    @endif
  </div>
</div>

<div class="form-group">
    <strong>Method:</strong>
    <select name="payment_method_id" id="payment_method_id" class="form-control payment-method-select2" required>
        <option value="">Select method</option>
        
        @foreach($paymentMethods as $key => $method)
            <option value="{{ $method->id }}">{{ $method->name }}</option>
        @endforeach
    </select>
  </div>


        <div class="form-group">
        <strong>Note:</strong>
        <textarea name="note" rows="4" cols="50" class="form-control">{{ request('note',old('note'))  }}</textarea>

        @if ($errors->has('note'))
            <div class="alert alert-danger">{{$errors->first('note')}}</div>
        @endif
        </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-danger">Submit</button>
    </div>
</form>


