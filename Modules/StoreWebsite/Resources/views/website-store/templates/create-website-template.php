<script type="text/x-jsrender" id="template-create-website">
	<form name="form-create-website" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Website Store {{else}}Create Website Store{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      	<span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="id" value="{{:data.id}}"/>
		         {{/if}}
		         
		      </div>
		      <div class="form-group col-md-6">
	            <label for="name">Name</label>
	            <input type="text" name="name" value="{{if data}}{{:data.name}}{{/if}}" class="form-control" id="name" placeholder="Enter Name">
	         </div>
		      <div class="form-group col-md-6">
		         <label for="code">Code</label>
		         <input type="text" name="code" value="{{if data}}{{:data.code}}{{/if}}" class="form-control" id="code" placeholder="Enter code">
		      </div>
		      <div class="form-group col-md-6">
		         <label for="website_id">Website</label>
		         <select name="website_id" class="form-control">
	            	<option value="">-- N/A --</option>
		            <?php
						foreach($websites as $k => $l) {
							echo "<option {{if data.website_id == '".$k."'}} selected {{/if}} value='".$k."'>".$l."</option>";
						}
					?>
		         </select>
		      </div>
		      <div class="form-group col-md-6">
		         <label for="root_category">Root Category</label>
		         <input type="text" name="root_category" value="{{if data}}{{:data.root_category}}{{/if}}" class="form-control" id="root_category" placeholder="Enter root cateogry">
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-store-site">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>