<script type="text/x-jsrender" id="template-create-form">
	<form name="template-create-form" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Price override{{else}}Create Price override{{/if}}</h5>
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
		            <label for="store_website_id">Storewebsite</label>
		            <select name="store_website_id" class="form-control">
		            	<?php
			            	foreach(\App\StoreWebsite::list() as $k => $l) {
			            		echo "<option {{if data.store_website_id == $k}} selected {{/if}}  value='".$k."'>".$l."</option>";
			            	}
			             ?>
			         </select>
		         </div>
		         {{if data && data.id}}
			         <div class="form-group col-md-6">
			            <label for="brand_segment">Brand Segment</label>
			            <select name="brand_segment" class="form-control">
			            	<option value="">-- Select --</option>
				            <?php
				            	foreach(\App\Brand::BRAND_SEGMENT as $k => $l) {
				            		echo "<option {{if data.brand_segment == '$k'}} selected {{/if}}  value='".$k."'>".$l."</option>";
				            	}
				             ?>
				         </select>
			         </div>
		         {{else}}
		         	<div class="form-group col-md-6">
			            <label for="brand_segment">Brand Segment</label>
			            <select name="brand_segments[]" class="form-control select2" multiple="multiple">
			            	<option value="">-- Select --</option>
				            <?php
				            	foreach(\App\Brand::BRAND_SEGMENT as $k => $l) {
				            		echo "<option {{if data.brand_segment == '$k'}} selected {{/if}}  value='".$k."'>".$l."</option>";
				            	}
				             ?>
				         </select>
			         </div>
		         {{/if}}
		         {{if data && data.id}}
			         <div class="form-group col-md-6">
			            <label for="category_ids">Category</label>
			                <?php
				            	echo $allCategoriesDropdown;
				             ?>
				     </div>
			     {{else}}
			     	<div class="form-group col-md-6">
			            <label for="category_id">Category</label>
			                <?php
				            	echo $allMultipleCategoriesDropdown;
				             ?>
				     </div>
			     {{/if}}
		         {{if data && data.id}}
			         <div class="form-group col-md-6">
			            <label for="country_code">Country</label>
			            <select name="country_code" class="form-control">
			            	<option value="">-- N/A --</option>
				            <?php
								foreach(\App\SimplyDutyCountry::all() as $k => $l) {
									echo "<option {{if data.country_code == '".$l->country_code."'}} selected {{/if}} value='".$l->country_code."'>".$l->country_name."</option>";
								}
							?>
				         </select>
			         </div>
		         {{else}}
		         	<div class="form-group col-md-6">
			            <label for="country_code">Country</label>
			            <select name="country_codes[]" class="form-control select2" multiple="multiple">
			            	<option value="">-- N/A --</option>
				            <?php
								foreach(\App\SimplyDutyCountry::all() as $k => $l) {
									echo "<option {{if data.country_code == '".$l->country_code."'}} selected {{/if}} value='".$l->country_code."'>".$l->country_name."</option>";
								}
							?>
				         </select>
			         </div>
		         {{/if}}
		         <div class="form-group col-md-6">
		            <label for="type">Type</label>
		            <select name="type" class="form-control">
			           	<option {{if data.type == "PERCENTAGE" }} selected {{/if}} value="PERCENTAGE">PERCENTAGE</option> 
			           	<option {{if data.type == "FIXED" }} selected {{/if}} value="FIXED">FIXED</option>
			         </select>
		         </div>
		         <div class="form-group col-md-6">
		            <label for="calculated">Calculated</label>
		            <select name="calculated" class="form-control">
			           	<option {{if data.type == "+" }} selected {{/if}} value="+">+</option> 
			           	<option {{if data.type == "-" }} selected {{/if}} value="-">-</option>
			         </select>
		         </div>
		         <div class="form-group col-md-6">
		            <label for="value">Amount</label>
		            <input type="text" name="value" value="{{:data.value}}" class="form-control">
		         </div>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-secondary submit-price-override">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>