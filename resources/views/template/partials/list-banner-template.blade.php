
	<div class="row" id="product-template-page">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
		        <th>Name</th>
		        <th>Image</th>
		        <th>No Of Images</th>
		        <th>Created At</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	@foreach($templates as $template)
		    
			      <tr>
			      	<td>{{$template->uid}}</td>
			      	<td>{{$template->name}}</td>
			      	<td><img src="{{$template->preview_url}}" width="100px" height="100px" onclick="bigImg('{{$template->preview_url}}')"></td>
			      	<td></td>
			        <td>{{$template->created_at}}</td>
			        <td><button type="button" class="btn btn-delete" onclick="editTemplate('{{$template->uid}}','{{$template->name}}','{{$template->preview_url}}')"><img width="15px" src="/images/edit.png"></button>
			        <button type="button" data-id="{{$template->uid}}" class="btn btn-delete-template"><img width="15px" src="/images/delete.png"></button>
					</td>
			      </tr>
			      @endforeach
			   
		    </tbody>
		</table>
	</div>

