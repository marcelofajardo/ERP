<script type="text/x-jsrender" id="product-templates-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
		        <th>Template no</th>
		        <th>Product Title</th>
		        <th>Brand</th>
		        <th>Currency</th>
		        <th>Price</th>
		        <th>Discounted price</th>
		        <th>Product</th>
		        <th>Text</th>
		        <th>Font Style</th>
		        <th>Font size</th>
		        <th>Background color</th>
		        <th>status</th>
		        <th>Website</th>
		        <th>Created at</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props result.data}}
			      <tr>
			      	<td>{{>prop.id}}</td>
			        <td>{{>prop.template_no}}</td>
			        <td>{{>prop.product_title}}</td>
			        <td>{{>prop.brand_name}}</td>
			        <td>{{>prop.currency}}</td>
			        <td>{{>prop.price}}</td>
			        <td>{{>prop.discounted_price}}</td>
			        <td>{{>prop.product_id}}</td>
			        <td>{{>prop.text}}</td>
			        <td>{{>prop.font_style}}</td>
			        <td>{{>prop.font_size}}</td>
			        <td>{{>prop.background_color}}</td>
			        <td>{{>prop.template_status}}</td>
			        <td>{{>prop.website_name}}</td>
			        <td>{{>prop.created_at}}</td>
			        <td><button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template"><img width="15px" src="/images/delete.png"></button>
			        	<button type="button" data-id="{{>prop.id}}" data-image="{{>prop.image_url}}" onClick="bigImg('{{>prop.image_url}}')" class="btn btn-secondary btn-sm show-image"><i class="fa fa-picture-o"></i></button>
			        	<button type="button" data-id="{{>prop.id}}" data-uid="{{>prop.uid}}" class="btn btn-secondary btn-sm reload-image" title="Reload image"><i class="fa fa-refresh"></i></button>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>
