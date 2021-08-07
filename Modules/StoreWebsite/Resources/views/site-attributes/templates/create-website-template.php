<script type="text/x-jsrender" id="template-create-website">
	<form name="form-create-website" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Site Attributes{{else}}Create Site Attributes{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-6">
		            <label for="attribute_key">Attribute Key</label>
		            <input type="text" name="attribute_key" value="{{if data}}{{:data.attribute_key}}{{/if}}" class="form-control" id="attribute_key" placeholder="Attribute Key">
		         </div>
		         <div class="form-group col-md-6">
			         <label for="attribute_val">Attribute Value</label>
			         <input type="text" name="attribute_val" value="{{if data}}{{:data.attribute_val}}{{/if}}" class="form-control" id="attribute_val" placeholder="Enter Attribute Value">
			      </div>
		      </div>
		      <div class="form-group">
		         <label for="store_website_id">Store Website ID</label>
		         <select name="store_website_id" id="store_website_id">
		         	<option value=''> Select Website </option>
		         	{{props websitelist}}
		         		{{if data}}
			         		{{if ~data.store_website_id == prop.id}}
				         		<option value='{{:prop.id}}' selected>{{:prop.website}}</option>
				         	{{else}}
				         		<option value='{{:prop.id}}'>{{:prop.website}}</option>
				         	{{/if}}
				        {{else}}
				         	<option value='{{:prop.id}}'>{{:prop.website}}</option>
				        {{/if}}
			         {{/props}} 
		         </select>
		         <!--input type="text" maxlength="14" minlength="14" name="store_website_id" value="{{if data}}{{:data.store_website_id}}{{/if}}" class="form-control" id="store_website_id" placeholder="Enter Store Website ID" -->
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-store-site">Save changes</button>
		   </div>
		</div>
	</form>
</script>