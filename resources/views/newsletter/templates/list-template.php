<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"><?php echo '#'; ?></th>
		      	<th width="2%">Id</th>
		      	<th width="10%">Subject</th>
		      	<th width="10%">StoreWebsite</th>
		      	<th width="10%">Send On</th>
		      	<th width="10%">Send At</th>
		      	<th width="10%">Mail List</th>
		      	<th width="10%">Products</th>
		      	<th width="10%">Updated by</th>
		        <th width="25%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input type="checkbox" value="{{:prop.id}}" name="check-product" class="check-product"></td>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.subject}}</td>
			      	<td>{{:prop.store_websiteName}}</td>
			      	<td>{{:prop.sent_on}}</td>
			      	<td>{{:prop.sent_at}}</td>
			      	<td>{{:prop.mailinglist_name}}</td>
			      	<td><a href="javascript:;" class="show-more-image" data-attr="section_p_{{:prop.id}}">View All</a></td>
			      	<td>{{:prop.updated_by}}</td>
			      	<td>
                        <div style="width:126px;">
                            <button type="button" data-id="{{>prop.id}}" class="btn btn-edit-template">
                            	<img width="15px" title="Edit" src="/images/edit.png">
                            </button>
                            <button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template" title="Delete">
                            	<i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <a href="newsletters/{{>prop.id}}/preview" title="Preview">
                            	<i class="fa fa-globe" aria-hidden="true"></i>
                            </a>
                        </div>
			        </td>
			      </tr>
			       <tr class="hideall" id="section_p_{{:prop.id}}" style="display:none;">
			       	<td colspan="9">
			      		 {{props prop.product_images}}
					        <div data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}" class="l-container">
								<img height=60 width=60 src="{{:prop.url}}" class="l-image"/>
								<div class="l-middle btn-delete-image" data-id="{{:prop.id}}" data-productid="{{:prop.product_id}}">
									<div class="l-text"><i class="fa fa-trash"></i></div>
								</div>
		  					</div>
				        {{/props}}
				    </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>