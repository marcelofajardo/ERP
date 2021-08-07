<div id="advancePaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('customer.send.advanceLink', $customer->id) }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Send Advance Link</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="number" class="form-control" name="price_inr" placeholder="INR Price" value="">
          </div>

          <div class="form-group">
            <input type="number" class="form-control" name="price_special" placeholder="Special Price" value="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" id="sendAdvanceLink">Send Link</button>
        </div>
      </form>
    </div>

  </div>
</div>
