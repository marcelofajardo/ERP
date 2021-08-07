<script type="text/x-jsrender" id="product-templates-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
		        <th>Name</th>
		        <th>Image</th>
		        <th>No Of Images</th>
		        <th>UID</th>
		        <th>Created At</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props result.data}}
			      <tr>
			      	<td>{{>prop.id}}</td>
			      	<td>{{>prop.name}}</td>
			      	<td><img src="{{>prop.image}}" width="100px" height="100px" onclick="bigImg('{{>prop.image}}')"></td>
			      	<td>{{>prop.no_of_images}}</td>
			      	<td>{{>prop.uid}}</td>
			        <td>{{>prop.created_at}}</td>
			        <td><button type="button" class="btn btn-delete" onclick="editTemplate('{{>prop.id}}','{{>prop.name}}','{{>prop.image}}','{{>prop.no_of_images}}','{{>prop.auto_generate_product}}','{{>prop.uid}}')"><img width="15px" src="/images/edit.png"></button>
			        <button type="button" data-uid="{{>prop.uid}}" data-id="{{>prop.id}}" class="btn btn-delete-template"><img width="15px" src="/images/delete.png"></button>
					</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>
