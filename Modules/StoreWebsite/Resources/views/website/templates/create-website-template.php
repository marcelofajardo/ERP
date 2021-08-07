<script type="text/x-jsrender" id="template-create-website">
	<form name="form-create-website" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Website {{else}}Create Website{{/if}}</h5>
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
		         <label for="sort_order">Sort Order</label>
		         <input type="text" name="sort_order" value="{{if data}}{{:data.sort_order}}{{/if}}" class="form-control" id="sort_order" placeholder="Enter sort order">
		      </div>
		      <div class="form-group col-md-6">
		         <label for="countries">Countries</label>
		         {{if form}}
		         	{{:form}}
		         {{else}}
			         <select name="countries[]" class="form-control select-2" multiple>
		            	<option value="">-- N/A --</option>
			            <?php
							foreach($countries as $k => $l) {
								echo "<option {{if data.countries == '".$k."'}} selected {{/if}} value='".$k."'>".$l."</option>";
							}
						?>
			         </select>
		         {{/if}}
		      </div>
		      <div class="form-group col-md-6">
		         <label for="store_website_id">Store website</label>
		         <select name="store_website_id" class="form-control">
	            	<option value="">-- N/A --</option>
		            <?php
						foreach($storeWebsites as $k => $l) {
							echo "<option {{if data.store_website_id == '".$k."'}} selected {{/if}} value='".$k."'>".$l."</option>";
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