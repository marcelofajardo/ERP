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
	<li class="node-child node_child_{{:data.id}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}">
	  <div class="node-container node--selected-sibling">
	     <div id="{{:data.name}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}" class="node">
	        <div class="node__expander">
	           <button id="node-expander-{{:data.name}}" type="button">
	              <svg width="8" height="12" viewBox="0 0 8 12" fill-rule="evenodd"><path d="M0 10.6L4.7 6 0 1.4 1.4 0l6.1 6-6.1 6z"></path></svg>
	           </button>
	        </div>
	        <div class="node__contents">
	           <div class="node__summary">
	              <div class="node__text">{{:data.name}} [{{:data.id}}]</div>
	              <div class="node__subtext">{{:data.match_condition}}</div>
	           </div>
	           <div dir="ltr" class="node__subtext"><span>{{:data.total_response}} Responses</span></div>
	        </div>
	        <div class="node__menu">
	           <div role="button" data-has-pop=false class="bx--overflow-menu" id="node__options-menu-{{:data.name}}" tabindex="0">
	              <svg class="bx--overflow-menu__icon" fill-rule="evenodd" height="15" role="img" viewBox="0 0 3 15" width="3" focusable="false" aria-label="Node options" alt="Node options">
	                 <title>Node options</title>
	                 <path d="M0 1.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0M0 7.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0M0 13.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0"></path>
	              </svg>
	           </div>
	        </div>
	     </div>
	     <ul class="node-children">
	     	
	     </ul>
	  </div>
	</li>
</script>

<script id="dialog-folder-leaf" type="text/x-jsrender">
	<li id="folder-leaf-node" class="node-child node_child_{{:data.id}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}">
	  <div class="node-container node--selected-sibling">
	     <div id="{{:data.name}}" data-id="{{:data.id}}" data-parent-id="{{:data.parent_id}}" class="node">
	        <div class="node__expander">
	           <button id="node-expander-{{:data.name}}" type="button">
			     <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px">    <path d="M22,6H12l-2-2H2v16h20V6z"/></svg>
	           </button>
	        </div>
	        <div class="node__contents">
	           <div class="node__summary">
	              <div class="node__text">{{:data.name}} [{{:data.id}}]</div>
	              <div class="node__subtext">{{:data.match_condition}}</div>
	           </div>
	           <div dir="ltr" class="node__subtext"><span>{{:data.childCount}} Dialog notes</span></div>
	        </div>
	        <div class="node__menu">
	           <div role="button" data-has-pop=false class="bx--overflow-menu" id="node__options-menu-{{:data.name}}" tabindex="0">
	              <svg class="bx--overflow-menu__icon" fill-rule="evenodd" height="15" role="img" viewBox="0 0 3 15" width="3" focusable="false" aria-label="Node options" alt="Node options">
	                 <title>Node options</title>
	                 <path d="M0 1.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0M0 7.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0M0 13.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 1 1-3 0"></path>
	              </svg>
	           </div>
	        </div>
	     </div>
	     <ul class="node-children">
	     	
	     </ul>
	  </div>
	</li>
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
	     	<input class="form-control" id="condition_value_{{>key}}" placeholder="Enter a response" name="response_condition[{{:identifier}}][condition_value]" type="text">
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
			<hr>
				<h4>Intent Section : <small>{{if data.intent.question}} {{:data.intent.question}} {{/if}}</small></h4>
			<hr>
			<div class="form-row">
		    	<div class="form-group col-md-9">
			      	<input class="form-control question-insert" name="intent[question]" value="{{if data.intent.question}} {{:data.intent.question}} {{/if}}" placeholder="Insert your question"></select>
			    </div>
			</div>
			<div class="form-row">
		    	<div class="form-group col-md-9">
			      	<select class="form-control search-category" name="intent[category_id]" placeholder="Select Category"></select>
			    </div>
			</div>
			<div class="form-row">
		    	<div class="form-group col-md-9">
			      	<select class="form-control search-intent" name="intent[name]" placeholder="Select Intent"></select>
			    </div>
			</div>
		{{else}}
			<div class="form-row">
		    	<div class="form-group col-md-9">
			      	<input class="form-control example-insert" name="" value="" placeholder="Example..."/>
			    </div>
			</div>
			<div class="form-row">
		    	<div class="form-group col-md-9">
					<input class="form-control question-insert" name="" value="" Placeholder="Question..."/>
			    </div>
			</div>
			<div class="form-row">
		    	<button class="btn btn-secondary save-example">Save</button>
			</div>
		{{/if}}
		<hr>
			<h4>Dialog Section : </h4>
		<hr>
		<div class="form-row">
		    <div class="form-group col-md-9">
		      <select class="form-control search-dialog" name="title" id="keyword_search" placeholder="Enter your keyword" name="keyword" value="{{:data.name}}">
		      	{{props data.dialog}}
		      		<option value="{{:prop.name}}" selected>{{:prop.name}}</option>
		      	{{/props}}
		      </select>					    
		      <small class="form-text text-muted">Node name will be shown to customers for disambiguation so use something descriptive</small>
		  	</div>
		</div>
		<div class="dialog-editor-section">
			<input type="hidden" name="id" value="{{:data.id}}"/>
			<input type="hidden" id="parent_id_form" name="parent_id" value="{{:data.parent_id}}"/>
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
			{{if data.dialog_type != "folder"}}	
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
									<input class="form-control response-value" id="value_{{>key}}" placeholder="Enter a value" name="response_condition[{{:prop.id}}][value]" value="{{:prop.response}}" type="text">
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
		</div>		
	</form>
</script>