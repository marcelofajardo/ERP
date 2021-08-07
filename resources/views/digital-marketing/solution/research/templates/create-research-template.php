<script type="text/x-jsrender" id="template-create-research">
	<form name="template-create-research" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Research{{else}}Create Research{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="research_id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-12">
		            <label for="subject">Subject</label>
		            <input type="text" name="subject" value="{{if data}}{{:data.subject}}{{/if}}" class="form-control" id="subject" placeholder="Enter subject">
		         </div>
		      </div>
		      <div class="form-group">
		            <label for="description">Description</label>
		            <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{if data}}{{:data.description}}{{/if}}</textarea>
		         </div>
		      <div class="form-group">
		         <label for="remarks">Remarks</label>
		         <textarea name="remarks" class="form-control" id="remarks" placeholder="Enter remarks">{{if data}}{{:data.remarks}}{{/if}}</textarea>
		      </div>
		      <div class="form-row">
		         <div class="form-group col-md-4">
		            <label for="inputState">Priority?</label>
		            <select name="priority" id="inputState" class="form-control">
		               <?php foreach($priority as  $k => $s) { ?>
		               		<option {{if data && data.priority == <?php echo $k; ?>}}selected{{/if}} value="<?php echo $k; ?>"><?php echo $s; ?></option>
		               <?php } ?>
		            </select>
		         </div>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-research">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>