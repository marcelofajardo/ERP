<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
				<th>Name</th>
				<th>Code</th>
				<th>Sort Order</th>
				<th>Status</th>
				<th>Store Name</th>
				<th>Platform id</th>
				<th>Actions</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			        <td>{{:prop.name}}</td>
			        <td>{{:prop.code}}</td>
			        {{if prop.status == 1}} 
			        	<td>Active</td>
			        {{else}}
			        	<td>In Active</td>
			        {{/if}}
			        <td>{{:prop.sort_order}}</td>
			        <td>{{:prop.website_store_name}}</td>
			        <td>{{:prop.platform_id}}</td>
			        <td>
			        	<button type="button" title="Edit" data-id="{{>prop.id}}" class="btn btn-edit-template">
			        		<i class="fa fa-edit" aria-hidden="true"></i>
			        	</button>
			        	<button type="button" title="Push" data-id="{{>prop.id}}" class="btn btn-push">
			        		<i class="fa fa-upload" aria-hidden="true"></i>
			        	</button>
			        	<button type="button" title="Delete" data-id="{{>prop.id}}" class="btn btn-delete-template">
			        		<i class="fa fa-trash" aria-hidden="true"></i>
			        	</button>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>