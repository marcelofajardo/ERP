<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th>Id</th>
		        <th>Account</th>
		        <th>Websites</th>
		        <th>Campaign</th>
		        <th>Date</th>
		        <th>Country</th>
		        <th>Headline</th>
		        <th>Status</th>
		        <th>Created at</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			        <td>{{:prop.account_name}}</td>
			        <td>-</td>
			        <td>{{:prop.campaign_name}}</td>
			        <td>-</td>
			        <td>-</td>
			        <td>{{:prop.headlines}}</td>
			        <td>{{:prop.status}}</td>
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