<div id="editVoucherModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST" id="editVoucherForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="partial">

        <div class="modal-header">
          <h4 class="modal-title">Update Voucher</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Travel Type:</strong>
            <select class="form-control" name="travel_type" id="voucher_travel_field">
              <option value="">Select Travel type</option>
              <option value="flight">Flight</option>
              <option value="train">Train</option>
              <option value="taxi">Taxi</option>
              <option value="auto">Auto</option>
            </select>
          </div>

          <div class="form-group">
            <strong>Amount:</strong>
            <input type="number" name="amount" id="voucher_amount_field" class="form-control" value="">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Update</button>
        </div>
      </form>
    </div>
  </div>

</div>
