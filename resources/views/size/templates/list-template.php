<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"><?php echo '#'; ?></th>
		      	<th width="10%">Size</th>
		        <th width="10%">Platform</th>
		        <th width="10%">Created</th>
		        <th width="5%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.name}}</td>
			      	<td>
			        	{{props prop.store_wesites}}
			        		{{>prop}}<br>
			        	{{/props}}
			    	</td>
			        <td>{{:prop.created_at}}</td>
			        <td>
			       		<div style="width:126px;">
	                        <button type="button" data-id="{{>prop.id}}" class="btn btn-edit-template"><img width="15px" title="Edit" src="/images/edit.png"></button>
	                        <button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
	                        <button type="button" data-id="{{>prop.id}}" class="btn btn-push-size-template" title="Push to size"><i class="fa fa-globe" aria-hidden="true"></i></button>
                        </div>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>