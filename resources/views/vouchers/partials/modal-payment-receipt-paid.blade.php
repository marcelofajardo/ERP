<form id="vendor-payment-receipt-form" action="/voucher/pay-multiple" method="POST" >
   @csrf
   <div class="modal-header">
      <h4 class="modal-title">Pay selected payment</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
   </div>
   <div class="modal-body">
      <div class="col-md-12 col-lg-12 @if($errors->has('reject_reason')) has-danger @elseif(count($errors->all())>0) has-success @endif">
         <div class="form-group">
            <strong>Date :</strong>
            <div class='input-group date' id='date_of_payment'>
               <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" />
               <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
               </span>
            </div>
         </div>
         <?php foreach($paymentReceipt as $payReceipt) { ?>
             <div class="form-group">
                <strong>Receipt #{{ $payReceipt->id }} Amount:</strong>
                <div class="row">
                  <div class="col-6">
                      <input readonly="readonly" class="form-control" value="{{ $payReceipt->rate_estimated }}">
                  </div>
                  <div class="col-6">
                    <input type="number" name="amount[{{ $payReceipt->id }}]" class="form-control" value="{{ old('amount') }}" required>
                  </div>
                </div>
             </div>
         <?php } ?>
         <div class="form-group">
            <strong>Currency:</strong>
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
            <textarea name="note" rows="4" cols="50" class="form-control">{{ old('note') }}</textarea>
            @if ($errors->has('note'))
                <div class="alert alert-danger">{{$errors->first('note')}}</div>
            @endif
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-danger">Submit</button>
   </div>
</form>