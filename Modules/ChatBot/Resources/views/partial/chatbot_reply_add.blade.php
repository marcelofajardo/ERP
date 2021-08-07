<div class="modal" id="chatbotReplyAddModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="add-website-reply-form" method="post" action="<?php echo route("chatbot.question.reply.add"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Add reply</h5>
	      </div>
	      <div class="modal-body">
	      		<input type="hidden" id="add-reply-hidden-id" name="id" value="">
                  <div class="form-group">
                  <select name="store_website_id" class="form-control">
						<option value="">Select Website</option>
						@foreach($watson_accounts as $acc)
						<option value="{{$acc->store_website_id}}" {{request()->get('store_website_id') == $acc->store_website_id ? 'selected' : ''}}>{{$acc->storeWebsite->title}}</option>
						@endforeach
				</select>
               </div>
                <div class="form-group">
                <textarea name="suggested_reply" class="form-control" cols="30" rows="10" placeholder="Add a reply here"></textarea>
                </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Add</button>
	      </div>
	  </form>
    </div>
  </div>
</div>