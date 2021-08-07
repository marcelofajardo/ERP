<div class="modal fade" id="sendCustomerMessage" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Customer Message</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{action('WhatsAppController@sendMessage', 'quicksell_group')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="status" value="2">
                    <div class="form-group">
                        <label for="send_type">Number of selected Checkbox</label>
                        <p class="selected_checkbox_customer"></p>
                    </div>
                    <input type="hidden" class="products_customer" name="products"/>
                    <input type="hidden" name="redirect_back" value="{{route('quicksell.index')}}"/>
                    <hr>

                    <div class="form-group">
                        <strong>Select Customers</strong>
                        <select class="form-control customer_multi_select" name="customers_id[]" required multiple>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Send WhatsApp</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>