<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="check-all">&nbsp;Id</th>
				<th>Name</th>
				<th>Code</th>
				<th>Countries</th>
				<th>Site</th>
				<th>Magento Id</th>
				<th>Price Ovveride</th>
				<th>Actions</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input type="checkbox" class="groups" name="groups[]" value="{{:prop.id}}">&nbsp;{{:prop.id}}</td>
			      	<td>{{:prop.name}}</td>
			        <td>{{:prop.code}}</td>
			        <td>{{:prop.countires_str}}</td>
			        <td>{{:prop.store_website_name}}</td>
			        <td>{{:prop.platform_id}}</td>
			        <td>
			        	{{if prop.is_price_ovveride == 1}}
			        		<span class="badge badge-success change-is_price_ovveride" data-id="{{:prop.id}}" data-value="0">Yes</span>
			        	{{else}}
			        		<span class="badge badge-danger change-is_price_ovveride" data-id="{{:prop.id}}" data-value="1">No</span>	
			        	{{/if}}
			        </td>
			        <td>
			        	<button type="button" title="Edit" data-id="{{>prop.id}}" class="btn btn-edit-template">
			        		<i class="fa fa-edit" aria-hidden="true"></i>
			        	</button>
			        	<button type="button" title="Push" data-id="{{>prop.id}}" class="btn btn-push">
			        		<i class="fa fa-upload" aria-hidden="true"></i>
			        	</button>
			        	<button type="button" title="Copy" data-id="{{>prop.id}}" class="btn btn-copy-template">
			        		<i class="fa fa-copy" aria-hidden="true"></i>
			        	</button>
			        	<a href="/store-website/website-stores?website_id={{>prop.id}}">
				        	<button type="button" title="View" data-id="{{>prop.id}}" class="btn">
				        		<i class="fa fa-eye" aria-hidden="true"></i>
				        	</button>
				        </a>
			        	<button type="button" title="Delete" data-id="{{>prop.id}}" class="btn btn-delete-template">
			        		<i class="fa fa-trash" aria-hidden="true"></i>
			        	</button>
			        	{{if prop.is_finished == 1}}
			        		<span class="badge badge-success change-status" data-id="{{>prop.id}}" data-value="0">Finished</span>
			        	{{else}}
			        		<span class="badge badge-danger change-status" data-id="{{>prop.id}}" data-value="1">Pending</span>
			        	{{/if}}
			        </td>
			      </tr>
			    {{/props}}
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>