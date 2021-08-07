<script type="text/x-jsrender" id="template-create-website">
	<form name="form-create-website" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Product Attribute {{else}}Create Product Attribute{{/if}}</h5>
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
		      <div class="form-row">
		      	<div class="form-group col-md-12">
		            <label for="product_id">Product ID</label>
		            <input type="text" name="product_id" value="{{if data}}{{:data.product_id}}{{/if}}" class="form-control" id="product_id" placeholder="Enter product id">
		         </div>
		      </div>
		      <div class="form-row">
		      	<div class="form-group col-md-12">
		            <label for="name">Description</label>
		            <textarea name="description" class="form-control">{{if data}}{{:data.description}}{{/if}}</textarea>
		         </div>
		      </div>
		      <div class="form-row">
			      <div class="form-group col-md-3">
			         <label for="price">Price</label>
			         <input type="text" name="price" value="{{if data}}{{:data.price}}{{/if}}" class="form-control" id="price" placeholder="Enter price">
			      </div>
			      <div class="form-group col-md-4">
			         <label for="discount">Discount</label>
			         <input type="text" name="discount" value="{{if data}}{{:data.discount}}{{/if}}" class="form-control" id="discount" placeholder="Enter discount">
			      </div>
			      <div class="form-group col-md-5">
			         <label for="discount_type">Discount Type</label>
			         <select class="form-control" name="discount_type">
			         	<option value="percentage">Percentage</option>
			         	<option value="amount">Amount</option>
			         </select>
			      </div>
			  </div>
			  <div class="form-row">
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
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-store-site">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>