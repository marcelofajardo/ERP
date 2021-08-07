<form  method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <input type="hidden" id="forward_email_id" name="forward_email_id" value="{{ $email['id'] }}">

        <div class="form-group">
            <input type="text" id="forward-email" name="email" class="form-control forward-message-input" placeholder="Forward To..." />
        <div class="message-to-forward">
            <blockquote style="margin:15px 0px 0px 0.8ex;border-left:1px solid rgb(204,204,204);padding-left:1ex">
                {!! $email['message'] !!}
            </blockquote>
        </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default submit-forward">Forward</button>
    </div>
</form>