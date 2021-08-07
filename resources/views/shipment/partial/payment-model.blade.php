<form action="{!! route('shipment.save-payment-info') !!}" method="POST" id="shipment-payment-info">
  @csrf
  <input type="hidden" name="waybill_id" value="{!! $wayBill->id !!}">
  <div class="modal-body">
          <div class="form-group">
             <strong>Invoice Number:</strong>
             <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{!! $wayBill->invoice_number !!}" required="" readonly disabled>
          </div>
          <div class="form-group">
             <strong>AWB:</strong>
             <input type="text" name="awb" id="awb" class="form-control" value="{!! $wayBill->awb !!}" required="" readonly disabled>
          </div>
          <div class="form-group">
             <strong>Cost of Shipment:</strong>
             <input type="text" name="cost_of_shipment" id="cost_of_shipment" class="form-control" value="{!! $wayBill->cost_of_shipment !!}" required="" readonly disabled>
          </div>
          <div class="form-group">
             <strong>Payment Date:</strong>
             <input type="text" name="paid_date" id="paid_date" class="form-control" value="{!! now() !!}" required="" readonly disabled>
          </div>
          <div class="form-group" id="order_id">
               <strong>Payment Mode:</strong>
               <select name="payment_mode" required="" class="form-control">
                 <option> Choose Payment mode</option>
                 @foreach(\App\Waybill::PaymentMode() as $k => $val)
                  <option value="{!! $k !!}">{!! $val !!}</option>
                 @endforeach
               </select>
           </div>
      </div>
  </div>
</form>
