<div id="privateViewingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('productinventory.instock') }}" method="GET">
        <div class="modal-header">
          <h4 class="modal-title">Set Up for Private Viewing</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="type" value="private_viewing">
          <input type="hidden" name="customer_id" value="{{ $customer->id }}">
          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='date'>
              <input type='text' class="form-control" name="date" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Select Products</button>
        </div>
      </form>
    </div>

  </div>
</div>
