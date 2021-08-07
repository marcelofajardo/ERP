<script type="text/x-jsrender" id="template-result-block">
	<div class="row mt-2">
	    <div class="col-md-12">
	        <div class="collapse" id="show-total-update-category">
	            <div class="card card-body">
	            	{{if updated_history.length}}
		                <div class="row col-md-12">
		                    {{props updated_history}}
		                      <div class="col-md-2">
		                            <div class="card">
		                              <div class="card-header">
		                                {{:prop.user_name}}
		                              </div>
		                              <div class="card-body">
		                                  {{:prop.total_updated}}
		                              </div>
		                          </div>
		                       </div> 
		                  	{{/props}}
		                </div>
	                {{else}}
	                	No Records
	                {{/if}}
	            </div>
	        </div>
	        <div class="collapse" id="show-left-for-update-category">
	            <div class="card card-body">
	            	{{if products_left.length}}
		                <div class="row col-md-12">
		                    {{props products_left}}
		                        <div class="col-md-2 {{if prop.user_id }} assigned {{/if}}">
		                            <div class="card">
		                                <div class="card-header">
		                                    {{:prop.supplier_name}} &nbsp; <i data-supplier-id="{{:prop.supplier_id}}" data-user-id="{{:prop.user_id}}" class="fa fa-comment send-message-user"></i>
		                                </div>
		                                <div class="card-body">
		                                    {{:prop.total_left}} {{if prop.user_name}}<span class="badge badge-danger" style="background: green;">[{{:prop.user_name}}]</span>
		                                    {{else prop.total_left == 0}}
		                                    	<span class="badge badge-secondary" style="background: black;">[No Pending]
		                                    {{else}}
		                                    <span class="badge badge-secondary" style="background: red;">[Unassigned]
											{{/if}}
		                                </div>
		                            </div>
		                        </div>
		                  	{{/props}}
		                </div>
		            {{else}}
	                	No Records
	                {{/if}}    
	            </div>
	        </div>
	    </div>    
	</div>
	<div class="row mt-2">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%">Id</th>
		      	<th width="30%">Product</th>
		        <th width="10%">New category</th>
		        <th width="10%">Old category</th>
		        <th width="10%">Updated by</th>
		        <th width="10%">Created</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			      	<td>[<a target="_blank" href="/search?term={{:prop.product_id}}&roletype=Inventory">{{:prop.product_id}}</a>] {{:prop.product_name}}</td>
			      	<td>{{:prop.new_cat_name}}</td>
			        <td>{{:prop.old_cat_name}}</td>
			        <td>{{:prop.user_name}}</td>
			        <td>{{:prop.created_at}}</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>


<script type="text/x-jsrender" id="template-merge-category">
<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Merge Category</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      	<span aria-hidden="true">&times;</span>
      </button>
   </div>
   <div class="modal-body">
		<div class="row">
			<div class="col-lg-12">
				<form>
					<?php echo csrf_field(); ?>
					<div class="row">
				  		<div class="col-md-12">
				    		<div class="form-group">
					         	<?php echo Form::select("merge_category",\App\VendorCategory::pluck("title","id")->toArray(),null,["class" => "form-control select2-vendor-category merge-category"]); ?>
					         </div>
				        </div> 
				        <div class="col-md-12">
					    	<div class="form-group">
					      		<button class="btn btn-secondary merge-category-btn">Merge and Delete</button>
					    	</div>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
	</div>
</div>
</script>

<script type="text/x-jsrender" id="template-send-message">
<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Send Message / Assign User</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      	<span aria-hidden="true">&times;</span>
      </button>
   </div>
   <div class="modal-body">
		<div class="row">
			<div class="col-lg-12">
				<form>
					<?php echo csrf_field(); ?>
					<div class="row">
				  		<div class="col-md-12">
				    		<div class="form-group">
				    			<input type="hidden" name="supplier_id" value="{{:supplier_id}}">
				    			<select class="form-control" name="user_id">
				    				<option value="">-- Select User--</option>
					    			<?php foreach(\App\User::pluck("name","id")->toArray() as $id => $user) { ?>
					    				<option {{if user_id == <?php echo $id; ?>}} selected {{/if}} value="<?php echo $id; ?>"><?php echo $user; ?></option>
					    			<?php } ?>
				    			</select>
					         </div>
					         <div class="form-group">
					         	<textarea class="form-control" name="comment"></textarea>
					         </div>
				        </div> 
				        <div class="col-md-12">
					    	<div class="form-group">
					      		<button class="btn btn-secondary store-and-save-btn">Store and Save</button>
					    	</div>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
	</div>
</div>
</script>