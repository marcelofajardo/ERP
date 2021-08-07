<script type="text/x-jsrender" id="template-create-solution">
	<form name="template-create-solution" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Solution{{else}}Create Solution{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="solution_id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-6">
		            <label for="provider">Provider</label>
		            <input type="text" name="provider" value="{{if data}}{{:data.provider}}{{/if}}" class="form-control" id="provider" placeholder="Enter provider">
		         </div>
		         <div class="form-group col-md-6">
		            <label for="website">Website</label>
		            <input type="text" name="website" value="{{if data}}{{:data.website}}{{/if}}" class="form-control" id="website" placeholder="Enter website">
		         </div>
		      </div>
		      <div class="form-group">
		         <label for="contact">Contact</label>
		         <textarea name="contact" class="form-control" id="contact" placeholder="Enter contact">{{if data}}{{:data.contact}}{{/if}}</textarea>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-solution">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>