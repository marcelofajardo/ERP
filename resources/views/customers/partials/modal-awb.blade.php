<div id="generateAWBMODAL{{ $order->id }}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('order.generate.awb') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <div class="modal-header">
          <h4 class="modal-title">Generate AWB</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Customer Name:</strong>
            <input type="text" name="customer_name" class="form-control" value="{{ $customer->name }}" required>
          </div>

          <div class="form-group">
            <strong>Customer Phone:</strong>
            <input type="number" name="customer_phone" class="form-control" value="{{ $customer->phone }}" required>
          </div>

          <div class="form-group">
            <strong>Customer Address 1:</strong>
            <input type="text" name="customer_address1" maxlength="20" class="form-control" value="{{ $customer->address }}" required>
          </div>

          <div class="form-group">
            <strong>Customer Address 2:</strong>
            <input type="text" name="customer_address2" class="form-control" value="{{ $customer->city }}" required>
          </div>

          <div class="form-group">
            <strong>Customer Pincode:</strong>
            <input type="number" name="customer_pincode" class="form-control" value="{{ $customer->pincode }}" max="999999" required>
          </div>

          {{-- <div class="form-group">
            <strong>Actual Weight:</strong>
            <input type="number" name="actual_weight" class="form-control" value="1" step="0.01" required>
          </div> --}}

          <div class="row">
            <div class="col">
              <div class="form-group">
                <strong>Length:</strong>
                <input type="number" name="box_length" class="form-control" placeholder="1.0" value="" step="0.1" max="1000" required>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <strong>Width:</strong>
                <input type="number" name="box_width" class="form-control" placeholder="1.0" value="" step="0.1" max="1000" required>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <strong>Height:</strong>
                <input type="number" name="box_height" class="form-control" placeholder="1.0" value="" step="0.1" max="1000" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <strong>Pick Up Date and Time</strong>
            <div class='input-group date' id='pickup-datetime'>
              <input type='text' class="form-control" name="pickup_time" value="{{ date('Y-m-d H:i') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Update and Generate</button>
        </div>
      </form>
    </div>

  </div>
</div>
