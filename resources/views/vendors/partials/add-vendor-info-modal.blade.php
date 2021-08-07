<div id="add-vendor-info-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
        <form id="add-vendor-info-form" action="{{ route('vendors.edit-vendor') }}" method="POST">
            <div class="modal-header">
                <h2>Edit Vendor</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                    <label for="cloumn">Column</label>
                    <select class="form-control" name="column">
                        <option value="">Select Column</option>
                        <option value="name" >Name</option>
                        <option value="email" >Email</option>
                        <option value="phone" >Phone</option>
                        <option value="address" >Address</option>
                        <option value="city" >City</option>
                        <option value="country" >Country</option>
                        <option value="whatsapp_number" >Whatsapp number</option>
                        <option value="default_phone" >Default Phone</option>
                        <option value="account_name" >Account name</option>
                        <option value="account_iban" >Account iban</option>
                        <option value="account_swift" >Account swift</option>
                        <option value="bank_name" >Bank name</option>
                        <option value="bank_address" >Bank address</option>
                        <option value="ifsc_code" >Ifsc code</option>
                        <option value="social_handle" >Social Handle</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="frequency">Value</label>
                    <input type="text" name="value" id="value" class="form-control">
                </div>
                <input type="hidden" id='hidden_edit_vendor_id' name="vendor_id">
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary btn-submit-info">Submit</button>

              </div>
        </div>
    </form>
  </div>
    </div>
</div>