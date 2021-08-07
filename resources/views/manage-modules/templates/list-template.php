<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"></th>
		      	<th width="2%">Id</th>
		        <th width="38%">Title</th>
		        <th width="30%">Created At</th>
		        <th width="30%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>
			      		<input type="checkbox" class="manage-modules-ckbx" name="manage_module[]" value="{{:prop.id}}"/>
			      	</td>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.name}} ({{:prop.total_task}})</td>
			        <td>{{:prop.created_at}}</td>
			        <td>
			        	<button type="button" data-id="{{>prop.id}}" class="btn btn-edit-template"><img width="15px" title="Edit" src="/images/edit.png"></button>
			        	|<button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template"><i class="fa fa-trash" aria-hidden="true"></i></button>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>


<script type="text/x-jsrender" id="template-merge-module">
<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Merge Modules</h5>
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
					         	<?php echo Form::select("merge_module",\App\DeveloperModule::pluck("name","id")->toArray(),null,["class" => "form-control select2-manage-module merge-module"]); ?>
					         </div>
				        </div> 
				        <div class="col-md-12">
					    	<div class="form-group">
					      		<button class="btn btn-secondary merge-module-btn">Merge and Delete</button>
					    	</div>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
	</div>
</div>
</script>