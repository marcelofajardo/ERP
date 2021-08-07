<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="check-all">&nbsp;Id</th>
				<th>Product Id</th>
				<th>Description</th>
				<th>Price</th>
				<th>Discount</th>
				<th>Discount Type</th>
				<th>Site</th>
				<th>Actions</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input type="checkbox" class="groups" name="groups[]" value="{{:prop.id}}">&nbsp;{{:prop.id}}</td>
			      	<td>{{:prop.product_id}}</td>
			        <td>{{:prop.description}}</td>
			        <td>{{:prop.price}}</td>
			        <td>{{:prop.discount}}</td>
			        <td>{{:prop.discount_type}}</td>
			        <td>{{:prop.store_website_name}}</td>
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