<script type="text/x-jsrender" id="form-create-newsletters">
	<form name="form-create-landing-page" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Newsletter{{else}}Create Landing Page Product{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
	         {{if data}}
	         	<input type="hidden" name="id" value="{{:data.id}}"/>
	         {{/if}}
	         <div class="form-group">
	            <label for="subject">Subject</label>
	            <input type="text" name="subject" value="{{if data}}{{:data.subject}}{{/if}}" class="form-control" id="subject" placeholder="Enter subject">
	         </div>
	         <div class="form-group">
	            <label for="sent_at">Sent At</label>
	            <input type="text" name="sent_at" value="{{if data}}{{:data.sent_at}}{{/if}}" class="form-control" id="sent_at" placeholder="Enter sent_at">
	         </div>

	         <div class="form-group">
	         	<label for="store_website_id">Store Website</label>
        		<select name="store_website_id" class="form-control">
        		<option value="">--SELECT-</option>
        		<?php foreach(\App\StoreWebsite::pluck('website','id')->toArray() as $k => $v) {  ?>
        			<option {{if data}} {{if data.store_website_id == "<?php echo $k; ?>"}} selected {{/if}} {{/if}} value="<?php echo $k; ?>"><?php echo $v; ?></option>
        		<?php } ?>
        		</select>
        	</div>
        	<div class="form-group">
	         	<label for="mail_list_id">Mailing List</label>
        		<select name="mail_list_id" class="form-control">
        		<option value="">--SELECT-</option>
        		<?php foreach(\App\Mailinglist::pluck('name','id')->toArray() as $k => $v) {  ?>
        			<option {{if data}} {{if data.mail_list_id == "<?php echo $k; ?>"}} selected {{/if}} {{/if}} value="<?php echo $k; ?>"><?php echo $v; ?></option>
        		<?php } ?>
        		</select>
        	</div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-secondary submit-platform">Save changes</button>
		   </div>
		</div>
	</form>
</script>