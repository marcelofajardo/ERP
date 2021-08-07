<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"><?php echo '#'; ?></th>
		      	<th width="2%">Id</th>
		      	<th width="10%">Brand</th>
		        <th width="10%">Product <I></I>d</th>
		        <th width="10%">Name</th>
		        <th width="10%">Description</th>
		        <th width="10%">Price</th>
		        <th width="10%">Go Live Date</th>
		        <th width="10%">Landing page Status</th>
		        <th width="20%">Image</th>
		        <th width="10%">Product Status</th>
		        <th width="8%">Created At</th>
		        <th width="25%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input type="checkbox" value="{{:prop.product_id}}" name="check-product" class="check-product"></td>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.brand_name}}</td>
			        <td>
			        	{{:prop.product_id}}
			        	<br>
			        	<div class="row">
			        		<lable>Shopify Store</lable>
			        		<select name="store_website_id" class="form-control store-website-change" data-id="{{:prop.id}}">
			        		<option value="">--SELECT-</option>
			        		<?php foreach(\App\StoreWebsite::shopifyWebsite() as $k => $v) {  ?>
			        			<option {{if prop.store_website_id == "<?php echo $k; ?>"}} selected {{/if}} value="<?php echo $k; ?>"><?php echo $v; ?></option>
			        		<?php } ?>
			        		</select>
			        	</div>
			        	<div class="row">
			        		<lable>Magento Website Store</lable>
			        		<select name="store_website_id" class="form-control store-website-change" data-id="{{:prop.id}}">
			        		<option value="">--SELECT-</option>
			        		<?php foreach(\App\StoreWebsite::magentoWebsite() as $k => $v) {  ?>
			        			<option {{if prop.store_website_id == "<?php echo $k; ?>"}} selected {{/if}} value="<?php echo $k; ?>"><?php echo $v; ?></option>
			        		<?php } ?>
			        		</select>
			        	</div>

			        </td>
			        <td>{{:prop.name}}</td>
			        <td>{{:prop.short_dec}}</td>
			        <td>{{:prop.price}}</td>
			        <td>Start: {{:prop.start_date}}<br>End:{{:prop.end_date}}</td>
			        <td><span>{{:prop.status_name}}</span>
                       {{if "<?php echo Auth::user()->isAdmin(); ?>"}}
                         {{if prop.status_name == "<?php echo App\LandingPageProduct::STATUS['USER_UPLOADED']; ?>"}}
                            <div>
                                <button class="approveLandingPageStatus" data-id="{{:prop.id}}">Approve</button>
                            </div>
                        {{/if}}
                       {{/if}}
			        </td>
			        <td>
			        {{props prop.images}}
			        {{if prop.show == true}}
			        <div data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}" class="l-container">
						<img height=60 width=60 src="{{:prop.url}}" class="l-image"/>
						<div class="l-middle btn-delete-image" data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}">
							<div class="l-text"><i class="fa fa-trash"></i></div>
						</div>
  					</div>
  					{{/if}}
			        {{/props}}
  					<a href="javascript:void(0);" type="button" data-attr="{{:prop.id}}" class="btn btn-image open_images">
                                            <img src="/images/forward.png" style="cursor: default;" width="2px;">
                                        </a>
			        </td>
			        <td>{{:prop.productStatus}}</td>
			        <td>{{:prop.created}}</td>
			        <td>
                        <div style="width:126px;">
                            <button type="button" data-id="{{>prop.id}}" class="btn btn-edit-template"><img width="15px" title="Edit" src="/images/edit.png"></button>
                            <button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            <button type="button" data-id="{{>prop.id}}" class="btn btn-push-icon-mangto" title="Refresh product in magnto"><i class="fa fa-upload" aria-hidden="true"></i></button>
                            {{if prop.stock_status == 1}}
                                    <button type="button" data-id="{{>prop.id}}" data-value="0" class="btn btn-stock-status-magnto" title="Stock Status Magnto"><i class="fa fa fa-toggle-on" aria-hidden="true"></i></button>
                                {{else}}
                                    <button type="button" data-id="{{>prop.id}}" data-value="1" class="btn btn-stock-status-magnto" title="Stock Status Magnto"><i class="fa fa fa-toggle-off" aria-hidden="true"></i></button>
                            {{/if}}
                        </div>
			        </td>
			      </tr>
			      <tr class="hideall" id="{{:prop.id}}" style="display:none;">
			      		 {{props prop.images}}
			      		<td>
			      			<div data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}" class="l-container">
								<img height=60 width=60 src="{{:prop.url}}" class="l-image"/>
								<div class="l-middle btn-delete-image" data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}">
								<div class="l-text"><i class="fa fa-trash"></i></div>
								</div>
  							</div>
  						</td>
  						 {{/props}}
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>

<script type="text/x-jsrender" id="template-attached-category">
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">{{if data.id}} Edit Site {{else}}Create Site{{/if}}</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      <span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<form>
						<?php echo csrf_field(); ?>
						<input type="hidden" name="store_website_id" value="{{:store_website_id}}">
					  	<div class="row">
					    	<div class="col">
					      		{{:scdropdown}}
					      	</div>
					    	<div class="col">
					      		<input type="text" class="form-control" name="remote_id" placeholder="Remote Id">
					    	</div>
					    	<div class="col">
					      		<button class="btn btn-secondary add-attached-category">ADD</button>
					    	</div>
					  	</div>
					</form>
				</div>
			</div>
			<div class="row mt-5 preview-category">
			</div>	
			<div class="row mt-5">		
				<div class="col-lg-12">
					<table class="table table-bordered">
					    <thead>
					      <tr>
					      	<th>No</th>
					        <th>Name</th>
					        <th>Remote id</th>
					        <th>Created At</th>
					        <th>Action</th>
					      </tr>
					    </thead>
					    <tbody>
					    	{{props data}}
						      <tr>
						      	<td>{{:prop.id}}</td>
						        <td>{{:prop.title}}</td>
						        <td>{{:prop.remote_id}}</td>
						        <td>{{:prop.created_at}}</td>
						        <td>
						        	<button type="button" data-store-website-id="{{>prop.store_website_id}}" data-id="{{>prop.id}}" class="btn btn-delete-store-website-category"><i class="fa fa-trash" aria-hidden="true"></i></button>
						        </td>
						      </tr>
						    {{/props}}  
					    </tbody>
					</table>
					{{:pagination}}
				</div>	
			</div>
		</div>
	</div>			
</script>

<script type="text/x-jsrender" id="template-attached-brands">
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">{{if data.id}} Edit Site {{else}}Create Site{{/if}}</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      	<span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<form>
						<?php echo csrf_field(); ?>
						<input type="hidden" name="store_website_id" value="{{:store_website_id}}">
					  	<div class="row">
					    	<div class="col">
					    		<select class="form-control" name="brand_id">
					    			{{props brands}}
					    				<option value="{{>key}}">{{>prop}}</option>
					    			{{/props}}		
					    		</select>
					      	</div>
					    	<div class="col">
					      		<input type="text" class="form-control" name="markup" placeholder="Mark up percentage">
					    	</div>
					    	<div class="col">
					      		<button class="btn btn-secondary add-attached-brands">ADD</button>
					    	</div>
					  	</div>
					</form>
				</div>
			</div>	
			<div class="row mt-5">		
				<div class="col-lg-12">
					<table class="table table-bordered">
					    <thead>
					      <tr>
					      	<th>No</th>
					        <th>Name</th>
					        <th>Markup</th>
					        <th>Created At</th>
					        <th>Action</th>
					      </tr>
					    </thead>
					    <tbody>
					    	{{props data}}
						      <tr>
						      	<td>{{:prop.id}}</td>
						        <td>{{:prop.name}}</td>
						        <td>{{:prop.markup}}</td>
						        <td>{{:prop.created_at}}</td>
						        <td>
						        	<button type="button" data-store-website-id="{{>prop.store_website_id}}" data-id="{{>prop.id}}" class="btn btn-delete-store-website-brand"><i class="fa fa-trash" aria-hidden="true"></i></button>
						        </td>
						      </tr>
						    {{/props}}  
					    </tbody>
					</table>
					{{:pagination}}
				</div>	
			</div>
		</div>
	</div>			
</script>
<script type="text/x-jsrender" id="template-category-list">
	<div class="col-lg-12">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="select-all-preview-category"></th>
		      	<th>Title</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr id="preview-category-{{:prop.id}}">
			      	<td><input class="preview-checkbox" type="checkbox" name="push_category" value="{{:prop.id}}"></td>
			      	<td>{{:prop.title}}</td>
			        <td>
			        	<button type="button" data-category-id="{{:prop.id}}" class="btn btn-delete-preview-category">
			        		<i class="fa fa-trash" aria-hidden="true"></i>
			        	</button>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
	</div>
	<div class="col-lg-12">
		<button class="btn btn-secondary save-preview-categories"><i class="fa fa-save"></i> Save</button>
	</div>			
</script>

<script type="text/x-jsrender" id="template-update-remarks">
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">Edit Remarks</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      	<span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<form>
						<?php echo csrf_field(); ?>
						<input type="hidden" class="frm_store_website_id" name="store_website_id" value="{{:id}}">
					  	<div class="row">
					  		<div class="col-md-12">
					    		<div class="form-group">
						         	<label for="{{:field}}">{{if field == "facebook_remarks"}}Facebook{{else}}Instagram{{/if}} Remarks</label>
						         	<textarea name="{{:field}}" class="form-control" id="facebook_remarks" placeholder="Enter {{:field}}">{{if field}}{{:remarks}}{{/if}}</textarea>
						         </div>
					        </div> 
					        <div class="col-md-12">
						    	<div class="form-group">
						      		<button class="btn btn-secondary update-remark-btn">Update</button>
						    	</div>
					    	</div>
					  	</div>
					</form>
				</div>
			</div>
		</div>
	</div>			
</script>

<script type="text/x-jsrender" id="template-create-components">
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">Add Components</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      	<span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<form>
						<?php echo csrf_field(); ?>
						<input type="hidden" class="frm_store_website_id" name="store_website_id" value="{{:data.id}}">
					  	<div class="row">
					  		<div class="col-md-12">
					    		<div class="form-group">
						         	<label for="components">Components</label>
						         	<select name="components[]" multiple="true" class="form-control select2-components-tags">
						         		{{props data.components}}
						         			<option value="{{:prop}}" selected="selected">{{:prop}}</option> 
						         		{{/props}}
						         	</select>	
						         </div>
					        </div> 
					        <div class="col-md-12">
						    	<div class="form-group">
						      		<button data-id="{{:data.id}}" class="btn btn-secondary update-components-btn">Update</button>
						    	</div>
					    	</div>
					  	</div>
					</form>
				</div>
			</div>
		</div>
	</div>			
</script>
