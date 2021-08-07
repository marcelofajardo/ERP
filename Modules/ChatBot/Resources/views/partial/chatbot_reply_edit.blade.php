<div class="modal" id="chatbotReplyEditModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo route("chatbot.question.reply.update"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Update reply</h5>
	      </div>
	      <div class="modal-body">
	      		<input type="hidden" id="reply-hidden-id" name="id" value="">
                <div class="form-group">
                <textarea name="suggested_reply" class="form-control" id="reply-hidden-data" cols="30" rows="10"></textarea>
                </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary reply-update-save-btn">Save changes</button>
	      </div>
	  </form>
    </div>
  </div>
</div>