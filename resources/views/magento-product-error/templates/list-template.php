<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="10%">Product ID</th>
		        <th width="10%">Date</th>
		        <th width="10%">Website</th>
		        <th width="15%">Message</th>
		        <th width="15%">Request data</th>
		        <th width="15%">Response Data</th>
		        <th width="10%">Status</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.product_id}}</td>
			      	<td>{{:prop.updated_at}}</td>
			      	<td>{{:prop.store_website}}</td>
			      	<td>{{:prop.message}}</td>
			      	<td>{{:prop.request_data}}</td>
			      	<td>{{:prop.response_data}}</td>
			      	<td>{{:prop.response_status}}</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
	</div>
	{{:pagination}}
</script>


<script type="text/x-jsrender" id="template-load-data">
	<div class="modal-content">
	   <div class="modal-header">
	      
	      <h5 class="modal-title"></h5>
	      
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      <span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			{{:data}}
		</div>
	</div>			
</script>