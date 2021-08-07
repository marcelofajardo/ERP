<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
		        <th>Provider</th>
		        <th>Website</th>
		        <th>Contact</th>
		        {{props usps}}
		        	<th>{{:prop.name}}</th>
		        {{/props}}
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data ~allusp=usps ~filledUsp=filledUsp}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			        <td>{{:prop.provider}}</td>
			        <td>{{:prop.website}}</td>
			        <td>{{:prop.contact}}</td>
			        {{props ~allusp ~x=~filledUsp ~solutionId=prop.id}}
			        	<td>
			        		<div class="row">
						    	<div class="col">
						      		<input type="text" class="form-control" name="usps[{{:prop.id}}]" value="{{if ~x && ~x[~solutionId] }}{{:~x[~solutionId][prop.id] }} {{/if}}" placeholder="{{:prop.name}}">
						    	</div>
						    </div>
			        	</td>
			        {{/props}}
			        <td>
			        	<button type="button" data-id="{{>prop.id}}" class="btn btn-edit-template"><img width="15px" title="Edit" src="/images/edit.png"></button>
			        	|<button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template"><i class="fa fa-trash" aria-hidden="true"></i></button>
			        	|<button type="button" data-id="{{>prop.id}}" class="btn btn-save-usp"><i class="fa fa-save" aria-hidden="true"></i></button>
			        	|<a href="/digital-marketing/{{>prop.digital_marketing_platform_id}}/solution/{{>prop.id}}/research">
			        		<button type="button" data-id="{{>prop.id}}" class="btn"><i class="fa fa-cubes" aria-hidden="true"></i></button>
			        	</a> | 
						<label for="upload_file{{>prop.id}}" data-id="{{>prop.id}}" class="fa fa-upload"></label>
						<input type="file" multiple data-id="{{>prop.id}}" class="upload_file_solution hide" name="upload_file{{>prop.id}}" id="upload_file{{>prop.id}}">
						<a href="javascript:;" class="get_Files_solution" data-id="{{>prop.id}}">Show Files</a>
			        </td>
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
<script type="text/x-jsrender" id="template-files-components">
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">Uploaded Files</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      	<span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					 <table class="table table-bordered">
						<thead>
							<tr>
								<td>File Link</td>
								<td>Upload Date</td>
								<td>Upload By</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</script>
