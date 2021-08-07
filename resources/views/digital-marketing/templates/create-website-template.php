<script type="text/x-jsrender" id="template-create-platform">
	<form name="form-create-platform" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Platform{{else}}Create Platform{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-6">
		            <label for="platform">Platform</label>
		            <input type="text" name="platform" value="{{if data}}{{:data.platform}}{{/if}}" class="form-control" id="platform" placeholder="Enter platform">
		         </div>
		         <div class="form-group col-md-6">
		            <label for="sub_platform">Sub Platform</label>
		            <input type="text" name="sub_platform" value="{{if data}}{{:data.sub_platform}}{{/if}}" class="form-control" id="sub_platform" placeholder="Enter sub platform">
		         </div>
		      </div>
		      <div class="form-group">
		         <label for="description">Description</label>
		         <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{if data}}{{:data.description}}{{/if}}</textarea>
		      </div>
		      <div class="form-row">
		         <div class="form-group col-md-4">
		            <label for="inputState">Status?</label>
		            <select name="status" id="inputState" class="form-control">
		               <?php foreach($status as  $k => $s) { ?>
		               		<option {{if data && data.status == <?php echo $k; ?>}}selected{{/if}} value="<?php echo $k; ?>"><?php echo $s; ?></option>
		               <?php } ?>
		            </select>
		         </div>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-platform">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>