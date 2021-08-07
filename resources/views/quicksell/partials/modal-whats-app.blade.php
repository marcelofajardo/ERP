<div class="modal fade" id="whatsappModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Product Images Through Whats App</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{action('WhatsAppController@sendMessage', 'quicksell')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="customers[]" multiple>
                          @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                          @endforeach
                            </select>
                    </div>
                    <input type="hidden" name="quicksell_id" id="quicksell_id">
                    <input type="hidden" name="status" value="2">

                    <div class="form-group">
                        <strong>Sending Number</strong>
                        <select class="form-control" name="whatsapp_number">
                            <option value="">Select a Number</option>

                            @foreach ($api_keys as $api_key)
                                <option value="{{ $api_key->number }}">{{ $api_key->number }}</option>
                            @endforeach
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