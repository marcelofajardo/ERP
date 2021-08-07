<div id="emailToAllModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Email to Multiple Users</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('document.email.send.bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <strong>Users</strong>
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
                    </div>
                    <input type="hidden" name="document_id" id="document_email_id">

                    <div class="form-group text-right">
                        <a class="add-email-contact mr-3" href="#">Send Document To Contact</a>
                    </div>
                    <div id="contact-email-label" class="form-group" style="display:none;">
                        <strong class="mr-3">New Email</strong>
                        <button type="button" class="add-email-contact">+</button>
                    </div>
                    <div id="contact-email-list" class="form-group">

                    </div>

                    <div class="form-group text-right">
                        <a class="add-cc mr-3" href="#">Cc</a>
                        <a class="add-bcc" href="#">Bcc</a>
                    </div>

                    <div id="cc-label" class="form-group" style="display:none;">
                        <strong class="mr-3">Cc</strong>
                        <a href="#" class="add-cc">+</a>
                    </div>

                    <div id="cc-list" class="form-group">

                    </div>

                    <div id="bcc-label" class="form-group" style="display:none;">
                        <strong class="mr-3">Bcc</strong>
                        <a href="#" class="add-bcc">+</a>
                    </div>

                    <div id="bcc-list" class="form-group">

                    </div>

                    <div class="form-group">
                        <strong>Subject *</strong>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
                    </div>

                    <div class="form-group">
                        <strong>Message *</strong>
                        <textarea name="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
                    </div>

                    <div class="form-group">
                        <strong>Files</strong>
                        <input type="file" name="file[]" value="" multiple>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
