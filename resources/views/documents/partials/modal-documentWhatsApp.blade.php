<div class="modal fade" id="whatsappModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send WhatsApp Document</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{action('WhatsAppController@sendMessage', 'document')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Enter Category Name</p>
                    <div class="form-group">
                        <select class="form-control users" name="user_type">
                            <option>Select User Type</option>
                            <option value ="1">Users</option>
                            <option value ="2">Vendors</option>
                            <option value ="3">Contact</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="user_select_id" name="users[]" multiple class="form-control">
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <a class="add-contact mr-3" href="#">Send Document To Contact</a>
                    </div>
                    <div id="contact-label" class="form-group" style="display:none;">
                        <strong class="mr-3">New Contact</strong>
                        <button type="button" class="add-contact">+</button>
                    </div>
                    <div id="contact-list" class="form-group">

                    </div>
                    <input type="hidden" name="document_id" id="document_id">
                    <input type="hidden" name="status" value="2">
                    <div class="form-group">
                        <strong>Message</strong>
                        <textarea name="message" id="message_to_all_field" rows="8" cols="80" class="form-control" required></textarea>
                    </div>
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