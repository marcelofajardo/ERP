<div class="modal" id="create-keyword" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo route("chatbot.keyword.save"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Create Entity</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
				@include('chatbot::partial.form.keyword')
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary form-save-btn">Save changes</button>
	      </div>
	  </form>
    </div>
  </div>
</div>
