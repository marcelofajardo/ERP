<style type="text/css">
	.select2-dropdown {
		z-index: 3250;
	}
</style>
<script id="dialog-leaf-button-options" type="text/x-jsrender">
	<ul class="bx--overflow-menu-options bx--overflow-menu--flip bx--overflow-menu-options--open" tabindex="-1" role="menu">
	  	<li class="bx--overflow-menu-options__option" role="menuitem">
	  		<button class="bx--overflow-menu-options__btn" tabindex="-1" role="add_child">Add child node</button>
	  	</li>
	  	<li class="bx--overflow-menu-options__option" role="menuitem">
	  		<button class="bx--overflow-menu-options__btn" tabindex="-1" role="add_above">Add node above</button>
	  	</li>
	  	<li class="bx--overflow-menu-options__option" role="menuitem">
	  		<button class="bx--overflow-menu-options__btn" tabindex="-1" role="add_below">Add node below</button>
	  	</li>
	  	<li class="bx--overflow-menu-options__option bx--overflow-menu--divider bx--overflow-menu-options__option--danger" role="menuitem">
	  		<button class="bx--overflow-menu-options__btn" tabindex="-1" role="delete">Delete</button>
	  	</li>
	</ul>
</script>	
	
<script id="dialog-leaf" type="text/x-jsrender">
    
    <tr class="node-child node_child_{{:data.id}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}">
				      <td style="width:7%" class="word-wrap">{{:data.response_type}}</td>
				      <td style="width:7%" class="word-wrap">{{:data.total_response}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.name}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.title}}</td>
				      <td style="width:21%" class="word-wrap">{{:data.match_condition}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.dialog_response}}
					  {{if data.dialog_response}}
					  <button data-id="{{:data.id}}" class="btn btn-xs btn-secondary pull-right view-all-response">View</button>
					 {{/if}} 
					  
					  </td>
				      <td style="width:5%" class="word-wrap">{{:data.dialog_type}}</td>
				      <td style="width:5%" class="word-wrap">{{:data.parent_id}}</td>
				      <td style="width:10%">
                        <div class="d-flex">
                        <a data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" class="call_child_node pd-3">
                        <img style="height: 15px; cursor: nwse-resize;" src="/images/forward.png">
                      </a>&nbsp;
                      <a title="Add child node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_child" class="create-new-node pd-3">
                        <i class="fa fa-plus-circle"></i>
                      </a>&nbsp;
                      <a title="Add node above" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_above" class="create-new-node pd-3">
                        <i class="fa fa fa-arrow-circle-up"></i>
                      </a>&nbsp;
                      <a title="Add node below" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_below" class="create-new-node pd-3">
                        <i class="fa fa fa-arrow-circle-down"></i>
                      </a>&nbsp;
                      <a title="Delete node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="delete" class="create-new-node pd-3">
                        <i class="fa fa-trash"></i>
                      </a>&nbsp;
                      <a style="padding:0px;" title="Edit node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="edit" class="node__contents pd-3">
                        <i class="fa fa-pencil"></i>
                      </a>
                        </div>
                      </td>
				    </tr>
</script>

<script id="dialog-folder-leaf" type="text/x-jsrender">
    <tr class="node-child node_child_{{:data.id}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}">
				      <td style="width:7%" class="word-wrap">{{:data.response_type}}</td>
				      <td style="width:7%" class="word-wrap">{{:data.total_response}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.name}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.title}}</td>
				      <td style="width:21%" class="word-wrap">{{:data.match_condition}}</td>
				      <td style="width:15%" class="word-wrap">{{:data.dialog_response}}
					  {{if data.dialog_response}}
					  <button data-id="{{:data.id}}" class="btn btn-xs btn-secondary pull-right view-all-response">View</button>
					 {{/if}}
					  </td>
				      <td style="width:5%" class="word-wrap">{{:data.dialog_type}}</td>
				      <td style="width:5%" class="word-wrap">{{:data.parent_id}}</td>
				      <td style="width:10%">
                      <div class="d-flex">
                      <a data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" class="call_child_node pd-3">
                        <img style="height: 15px; cursor: nwse-resize;" src="/images/forward.png">
                      </a>&nbsp;
                      <a title="Add child node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_child" class="create-new-node pd-3">
                        <i class="fa fa-plus-circle"></i>
                      </a>&nbsp;
                      <a title="Add node above" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_above" class="create-new-node pd-3">
                        <i class="fa fa fa-arrow-circle-up"></i>
                      </a>&nbsp;
                      <a title="Add node below" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="add_below" class="create-new-node pd-3">
                        <i class="fa fa fa-arrow-circle-down"></i>
                      </a>&nbsp;
                      <a title="Delete node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="delete" class="create-new-node pd-3">
                        <i class="fa fa-trash"></i>
                      </a>&nbsp;
                      <a style="padding:0px;" title="Edit node" data-id="{{:data.id}}" data-parent_id="{{:data.parent_id}}" data-role="edit" class="node__contents pd-3">
                        <i class="fa fa-pencil"></i>
                      </a>
                        </div>
                      
                    </td>
	</tr>
</script>

<script id="multiple-response-condition" type="text/x-jsrender">
	<div class="form-row">
		<div class="form-group col-md-3">
	      <select class="form-control search-alias" name="response_condition[{{:identifier}}][condition]">
			{{props allSuggestedOptions}}
	      		<option value="{{:prop}}">{{:prop}}</option>
	      	{{/props}}
	      </select>
	      <small id="emailHelp_{{:identifier}}" class="form-text text-muted">IF ASSISTANT RECOGNIZES</small>
	  	</div>
	  	<div class="form-group col-md-3">
	      <select class="form-control" name="response_condition[{{:identifier}}][condition_sign]">
			 <option value="">Any</option>
			 <option value=":">Is</option>
			 <option value="!=">Is Not</option>
			 <option value=">">Greater than</option>
			 <option value="<">Less than</option>
	      </select>
	  	</div>
	  	<div class="form-group col-md-6">
	     	<input class="form-control" id="condition_value_{{>key}}" placeholder="Enter a value" name="response_condition[{{:identifier}}][condition_value]" type="text">
	  	</div>
	  	<div class="form-group col-md-9">
	      <input class="form-control" id="value_{{:identifier}}" placeholder="Enter a response" name="response_condition[{{:identifier}}][value]" type="text">
	  	</div>
	  	<div class="form-group col-md-3">
	  		<button type="button" data-id="{{:identifier}}" class="btn btn-image btn-delete-mul-response"><img src="/images/delete.png"></button>
	  		<button type="button" class="btn btn-image btn-add-mul-response"><img src="/images/add.png"></button>
	  	</div>
	</div>
</script>
<script id="single-response-condition" type="text/x-jsrender">
	<div class="form-row">
		<div class="form-group col-md-9">
	      <input class="form-control response-value" id="value" placeholder="Enter a response" name="response_condition[0][value]" type="text">
	    </div>
	</div>
</script>

<script id="add-more-condition" type="text/x-jsrender">
	<div class="form-row dynamic-row">
		<div class="form-group col-md-3">
	      <select name="conditions[]" class="form-control">
	      	<option value="&&">AND</option>
	      	<option value="||">OR</option>
	      </select>
	  	</div>
	  	<div class="form-group col-md-3">
	      <select class="form-control search-alias" name="conditions[]">
			{{props allSuggestedOptions}}
	      		<option value="{{:prop}}">{{:prop}}</option>
	      	{{/props}}
	      </select>
	  	</div>
	  	<div class="form-group col-md-3">
		  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
	          <span class="glyphicon glyphicon-plus"></span> 
	        </a>
	        <a href="javascript:;" class="btn btn-secondary btn-sm remove-more-condition-btn">
	          <span class="glyphicon glyphicon-minus"></span> 
	        </a>	
	  	</div>
	</div>
</script>

<script id="edit-dialog-form-section" type="text/x-jsrender">
	<div class="form-row">
	    <input type="hidden" name="id" value="{{:data.id}}"/>
		<input type="hidden" id="parent_id_form" name="parent_id" value="{{:data.parent_id}}"/>		
	</div>
	<hr>
		<h4>If assistant recognizes</h4>
	<hr>
	<div class="form-row">
	    <div class="form-group col-md-3">
	      <select class="form-control search-alias" name="conditions[]">
			{{props data.allSuggestedOptions ~first_condition = data.first_condition}}
	      		<option {{if ~first_condition == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
	      	{{/props}}
	      </select>
	    </div>
	  	<div class="form-group col-md-3">
		  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
	          <span class="glyphicon glyphicon-plus"></span> 
	        </a>	
	  	</div>
	</div>
	<div class="show-more-conditions">
		{{props data.extra_condition ~allSuggestedOptions=data.allSuggestedOptions}}
			<div class="form-row">
				<div class="form-group col-md-3">
			      <select name="conditions[]" class="form-control">
			      	<option {{if prop[0] == "&&"}} selected {{/if}} value="&&">AND</option>
			      	<option {{if prop[0] == "||"}} selected {{/if}} value="||">OR</option>
			      </select>
			  	</div>
			  	<div class="form-group col-md-3">
			      <select class="form-control search-alias" name="conditions[]">
					{{props ~allSuggestedOptions ~selectedValue=prop[1]}}
			      		<option {{if ~selectedValue == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
			      	{{/props}}
			      </select>
			  	</div>
			  	<div class="form-group col-md-3">
				  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
			          <span class="glyphicon glyphicon-plus"></span> 
			        </a>
			        <a href="javascript:;" class="btn btn-secondary btn-sm remove-more-condition-btn">
			          <span class="glyphicon glyphicon-minus"></span> 
			        </a>	
			  	</div>
			</div>
		{{/props}}
	</div>	
	<hr>
		<h4>Assistant responds</h4>
	<hr>
	<div class="form-row">
		<div class="col-md-9">
	  		<input type="checkbox" name="response_type" value="response_condition" {{if data.response_condition}} checked {{/if}} class="multiple-conditioned-response" data-toggle="toggle">
	  		<small class="form-text text-muted">Multiple conditioned responses</small>
	  	</div>
	</div>
	<div class="assistant-response-based">
		{{if data.assistant_report && data.assistant_report.length}}
			{{props data.assistant_report ~allSuggestedOptions = data.allSuggestedOptions}}
				<div class="form-row">
					{{if prop.condition != ''}}
						<div class="form-group col-md-3">
					      <select class="form-control search-alias" name="response_condition[{{:prop.id}}][condition]">
							{{props ~allSuggestedOptions ~selectedValue=prop.condition }}
					      		<option {{if ~selectedValue == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
					      	{{/props}}
					      </select>
					      <small id="emailHelp_{{>key}}" class="form-text text-muted">IF ASSISTANT RECOGNIZES</small>
					  	</div>
				  	{{/if}}
				  	<div class="form-group col-md-3 extra_condtions {{if prop.condition_sign == ''}} dis-none {{/if}}">
				      <select class="form-control" name="response_condition[{{:prop.id}}][condition_sign]">
						 <option value="">Any</option>
						 <option {{if prop.condition_sign == ':'}} selected {{/if}} value=":">Is</option>
						 <option {{if prop.condition_sign == '!='}} selected {{/if}} value="!=">Is Not</option>
						 <option {{if prop.condition_sign == '>'}} selected {{/if}} value=">">Greater than</option>
						 <option {{if prop.condition_sign == '<'}} selected {{/if}} value="<">Less than</option>
				      </select>
				  	</div>
				  	<div class="form-group col-md-6 extra_condtions {{if prop.condition_value == ''}} dis-none {{/if}}">
				     	<input class="form-control response-value" id="condition_value_{{>key}}" placeholder="Enter a response" name="response_condition[{{:prop.id}}][condition_value]" value="{{:prop.condition_value}}" type="text">
				  	</div>
				  	<div class="form-group col-md-9">
				      	<input class="form-control response-value id="value_{{>key}}" placeholder="Enter a value" name="response_condition[{{:prop.id}}][value]" value="{{:prop.response}}" type="text">
				  	</div>
				  	<div class="form-group col-md-3">
				  		<button type="button" data-id="{{:prop.id}}" class="btn btn-image btn-delete-mul-response"><img src="/images/delete.png"></button>
				  		<button type="button" class="btn btn-image btn-add-mul-response"><img src="/images/add.png"></button>
				  	</div>	
				</div>
			{{/props}}
		{{else}}
			<div class="form-row">
				<div class="form-group col-md-9">
			      <input class="form-control response-value" placeholder="Enter a response" name="response_condition[0][value]" type="text">
			    </div>
			</div>
		{{/if}}
	</div>
	{{if data.create_type == "intents_create"}}
		<hr>
			<h4>Dialog Location</h4>
		<hr>
		<div class="form-row">
		    <div class="form-group col-md-9">
		      <select class="form-control parent-dialog-node" id="parent_dialog" placeholder="Enter your Parent dialog" name="parent_id"> </select>					    
		  	</div>
		</div>
		<div class="form-row">
		    <div class="form-group col-md-9">
		      <select class="form-control previous-dialog-node" id="previous_sibling" placeholder="Enter your Previous node" name="previous_sibling"> </select>					    
		  	</div>
		</div>
	{{/if}}
</script>
<script id="search-alias-template" type="text/x-jsrender">
	{{props allSuggestedOptions}}
  		<option value="{{:prop}}">{{:prop}}</option>
  	{{/props}}
</script>	

<script id="add-dialog-form" type="text/x-jsrender">
	<form action="<?php echo route('chatbot.dialog.saveajax'); ?>" method="post" id="dialog-save-response-form">
		<?php echo csrf_field(); ?>
		{{if data.create_type == "intents_create"}}
				<h4>Intent Section : <small>{{if data.intent.question}} {{:data.intent.question}} {{/if}}</small></h4>
			<div class="form-row">
		    	<div class="form-group col-md-6">
			      	<select class="form-control search-intent" name="intent[name]" placeholder="Select Intent"></select>
			    </div>
				<div class="form-group col-md-6">
			      	<select class="form-control search-category" name="intent[category_id]" placeholder="Select Category"></select>
				</div>
			</div>
			<div class="form-row">
		    	<div class="form-group col-md-6">
			      	<input class="form-control question-insert" name="intent[question]" value="{{if data.intent.question}} {{:data.intent.question}} {{/if}}" placeholder="Insert your question"></select>
			    </div>
				<div class="form-group col-md-3">
			      	<input class="form-control reply-insert" name="intent[suggested_reply]" value="{{if data.intent.suggested_reply}} {{:data.intent.suggested_reply}} {{/if}}" placeholder="Insert Suggested reply"/>
			    </div>
				<div class="form-group col-md-3">
					<button class="btn btn-secondary save-intent">Save</button>
				</div>
			</div>
		{{else}}
			<div class="form-row">
		    	<div class="form-group col-md-6">
			      	<select class="form-control search-intent" name="" placeholder="Select Intent"></select>
			    </div>
				<div class="form-group col-md-6">
			      	<select class="form-control search-category" name="" placeholder="Select Category"></select>
				</div>
			</div>
			<div class="form-row">
		    	<div class="form-group col-md-6">
			      	<input class="form-control question-insert" name="" value="" placeholder="Insert your question"></select>
			    </div>
				<div class="form-group col-md-3">
			      	<input class="form-control reply-insert" name="intent" value="" placeholder="Insert Suggested reply"/>
			    </div>
				<div class="form-group col-md-3">
					<button class="btn btn-secondary save-intent">Save</button>
				</div>
			</div>
		{{/if}}
			<h4>Dialog Section : </h4>
		<div class="form-row">
		    <div class="form-group col-md-12">
		      <select class="form-control search-dialog" name="title" id="keyword_search" placeholder="Enter your keyword" name="keyword" value="{{:data.name}}">
		      	{{props data.dialog}}
		      		<option value="{{:prop.name}}" selected>{{:prop.name}}</option>
		      	{{/props}}
		      </select>					    
		      <small class="form-text text-muted">Node name will be shown to customers for disambiguation so use something descriptive</small>
		  	</div>
		</div>
		<h4>Store Website : </h4>
			<div class="form-row">
		    <div class="form-group col-md-12">
		      <select class="form-control store_on_change" data-dialog={{:data.id}} name="store_website_id" placeholder="Enter your keyword">
			  	<option value="">Select Website</option>
				  {{props data.sites ~selectedCondition = data.store_website_id}}
		      		<option value="{{:prop.id}}" {{if ~selectedCondition == prop.id}} selected {{/if}}>{{:prop.name}}</option>
		      	{{/props}}
		      </select>
		      <small class="form-text text-muted">Node name will be shown to customers for disambiguation so use something descriptive</small>
		  	</div>
		</div>
		<div class="dialog-editor-section">
			<input type="hidden" name="id" value="{{:data.id}}"/>
			<input type="hidden" id="parent_id_form" name="parent_id" value="{{:data.parent_id}}"/>
				<h4>If assistant recognizes</h4>
			<div class="form-row">
			    <div class="form-group col-md-6">
			      <select class="form-control search-alias" name="conditions[]">
					{{props data.allSuggestedOptions ~first_condition = data.first_condition}}
			      		<option {{if ~first_condition == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
			      	{{/props}}
			      </select>
			    </div>
			  	<div class="form-group col-md-3">
				  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
			          <span class="glyphicon glyphicon-plus"></span> 
			        </a>	
			  	</div>
			</div>
			<div class="show-more-conditions">
				{{props data.extra_condition ~allSuggestedOptions=data.allSuggestedOptions}}
					<div class="form-row">
						<div class="form-group col-md-6">
					      <select name="conditions[]" class="form-control">
					      	<option {{if prop[0] == "&&"}} selected {{/if}} value="&&">AND</option>
					      	<option {{if prop[0] == "||"}} selected {{/if}} value="||">OR</option>
					      </select>
					  	</div>
					  	<div class="form-group col-md-3">
					      <select class="form-control search-alias" name="conditions[]">
							{{props ~allSuggestedOptions ~selectedValue=prop[1]}}
					      		<option {{if ~selectedValue == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
					      	{{/props}}
					      </select>
					  	</div>
					  	<div class="form-group col-md-3">
						  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
					          <span class="glyphicon glyphicon-plus"></span> 
					        </a>
					        <a href="javascript:;" class="btn btn-secondary btn-sm remove-more-condition-btn">
					          <span class="glyphicon glyphicon-minus"></span> 
					        </a>	
					  	</div>
					</div>
				{{/props}}
			</div>
			{{if data.dialog_type != "folder"}}	
					<h4>Assistant responds</h4>
				<div class="form-row">
					<div class="col-md-9">
						<input type="checkbox" name="response_type" value="response_condition" {{if data.response_condition}} checked {{/if}} class="multiple-conditioned-response" data-toggle="toggle">
						<small class="form-text text-muted">Multiple conditioned responses</small>
					</div>
				</div>	
				<div class="assistant-response-based">
					{{if data.assistant_report && data.assistant_report.length}}
						{{props data.assistant_report ~allSuggestedOptions = data.allSuggestedOptions }}
							<div class="form-row">
								{{if prop.condition != ''}}
									<div class="form-group col-md-3">
									<select class="form-control search-alias" name="response_condition[{{:prop.id}}][condition]">
										{{props ~allSuggestedOptions  ~selectedValue=prop.condition}}
											<option {{if ~selectedValue == prop}} selected {{/if}} value="{{:prop}}">{{:prop}}</option>
										{{/props}}
									</select>
									<small id="emailHelp_{{>key}}" class="form-text text-muted">IF ASSISTANT RECOGNIZES</small>
									</div>
								{{/if}}
								<div class="form-group col-md-3 extra_condtions">
								<select class="form-control" name="response_condition[{{:prop.id}}][condition_sign]">
									<option {{if prop.condition_sign == ''}} selected {{/if}}value="">Any</option>
									<option {{if prop.condition_sign == ':'}} selected {{/if}} value=":">Is</option>
									<option {{if prop.condition_sign == '!='}} selected {{/if}} value="!=">Is Not</option>
									<option {{if prop.condition_sign == '>'}} selected {{/if}} value=">">Greater than</option>
									<option {{if prop.condition_sign == '<'}} selected {{/if}} value="<">Less than</option>
								</select>
								</div>
								<div class="form-group col-md-6 extra_condtions ">
									<input class="form-control response-value" id="condition_value_{{>key}}" placeholder="Enter a value" name="response_condition[{{:prop.id}}][condition_value]" value="{{:prop.condition_value}}" type="text">
								</div>
								<div class="form-group col-md-9">
									<input class="form-control response-value" id="value_{{>key}}" placeholder="Enter a response" name="response_condition[{{:prop.id}}][value]" value="{{:prop.response}}" type="text">
								</div>
								<div class="form-group col-md-3">
									<button type="button" data-id="{{:prop.id}}" class="btn btn-image btn-delete-mul-response"><img src="/images/delete.png"></button>
									<button type="button" class="btn btn-image btn-add-mul-response"><img src="/images/add.png"></button>
								</div>	
							</div>
						{{/props}}
					{{else}}
						<div class="form-row">
							<div class="form-group col-md-9">
							<input class="form-control response-value" placeholder="Enter a response" name="response_condition[0][value]" type="text">
							</div>
						</div>
					{{/if}}
				</div>
			{{/if}}
			{{if data.create_type == "intents_create"}}
					<h4>Dialog Location</h4>
				<div class="form-row">
				    <div class="form-group col-md-6">
				      <select class="form-control parent-dialog-node" id="parent_dialog" placeholder="Enter your Parent dialog" name="parent_id"> </select>					    
				  	</div>
					  <div class="form-group col-md-6">
				      <select class="form-control previous-dialog-node" id="previous_sibling" placeholder="Enter your Previous node" name="previous_sibling"> </select>					    
				  	</div>
				    
				</div>
			{{/if}}
		</div>		
	</form>
</script>