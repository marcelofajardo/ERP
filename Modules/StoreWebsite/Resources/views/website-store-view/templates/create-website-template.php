<script type="text/x-jsrender" id="template-create-website">
	<form name="form-create-website" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Website Store View {{else}}Create Website Store View{{/if}}</h5>
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
	            <select name="name" class="form-control">
	            	<option value="">-- N/A --</option>
		            <?php
		            	foreach($languages as $k => $l) {
							echo "<option {{if data.name == '".strtolower($k)."'}} selected {{/if}} value='".$k."'>".$l."</option>";
						}
					?>
		         </select>
	         </div>
		      <div class="form-group col-md-6">
		         <label for="code">Code</label>
		         <input type="text" name="code" value="{{if data}}{{:data.code}}{{/if}}" class="form-control" id="code" placeholder="Enter code">
		      </div>
		      <div class="form-group col-md-6">
		         <label for="code">Status</label>
		         <select name="status" class="form-control">
	            	<option value="">-- N/A --</option>
		            <?php
		            	$statuses = ["In active", "Active"];
						foreach($statuses as $k => $l) {
							echo "<option {{if data.status == '".$k."'}} selected {{/if}} value='".$k."'>".$l."</option>";
						}
					?>
		         </select>
		      </div>
		      <div class="form-group col-md-6">
		         <label for="sort_order">Sort Order</label>
		         <input type="text" name="sort_order" value="{{if data}}{{:data.sort_order}}{{/if}}" class="form-control" id="sort_order" placeholder="Enter sort order">
		      </div>
		      <div class="form-group col-md-6">
		         <label for="website_store_id">Website Store</label>
		         <select name="website_store_id" class="form-control">
	            	<option value="">-- N/A --</option>
		            <?php
						foreach($websiteStores as $k => $l) {
							echo "<option {{if data.website_store_id == '".$k."'}} selected {{/if}} value='".$k."'>".$l."</option>";
						}
					?>
		         </select>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-store-site">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>