<script type="text/x-jsrender" id="template-result-block">
	<div class="row mt-2">
	    <div class="col-md-12">
	        <div class="collapse" id="show-total-update-category">
	            <div class="card card-body">
	            	{{if updated_history.length}}
		                <div class="row col-md-12">
		                    {{props updated_history}}
		                      <div class="col-md-2">
		                            <div class="card">
		                              <div class="card-header">
		                                {{:prop.user_name}}
		                              </div>
		                              <div class="card-body">
		                                  {{:prop.total_updated}}
		                              </div>
		                          </div>
		                       </div> 
		                  	{{/props}}
		                </div>
	                {{else}}
	                	No Records
	                {{/if}}
	            </div>
	        </div>
	    </div>    
	</div>
	<div class="row mt-2">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"></th>
		      	<th width="10%">Product</th>
		        <th width="10%">Updated By</th>
		        <th width="10%">Key</th>
		        <th width="30%">Content</th>
		        <th width="10%">Created At</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			      	<td>[<a target="_blank" href="/search?term={{:prop.product_id}}&roletype=Inventory">{{:prop.product_id}}</a>]{{:prop.product_name}}</td>
			      	<td>{{:prop.user_name}}</td>
			      	<td>{{:prop.action}}</td>
			      	<td>{{:prop.content}}</td>
			        <td>{{:prop.created_at}}</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>	