    <div class="modal-body">
        <input type="hidden" id="reply_email_id" name="reply_email_id" value="{{ $email['id'] }}" />

        <div class="form-group">
            <textarea id="reply-message" name="message" class="form-control reply-message-textarea" rows="3" placeholder="Reply..."></textarea>
        <div class="message-to-reply">
            <blockquote style="margin:15px 0px 0px 0.8ex;border-left:1px solid rgb(204,204,204);padding-left:1ex">
                {!! $email['message'] !!}
            </blockquote>
        </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default submit-reply">Reply</button>
    </div>