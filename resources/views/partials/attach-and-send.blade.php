<div id="attachAndSendModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" id="attach-and-send-form" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Attach and send Images</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <input type="hidden" name="products" id="product_lists" value="">
        <input type="hidden" name="type" id="forward_type" value="">
          <div class="form-group">
            <strong>Customer:</strong>
            <select class="form-control select2" name="customer_id" required>
              <option value="">Select Customer</option>
              @php 
              $customers = \App\Customer::pluck('name','id');
              @endphp
              @foreach ($customers as $key => $customer)
                <option value="{{ $key }}">{{ $customer }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Attach</button>
        </div>
      </form>
    </div>

  </div>
</div>
