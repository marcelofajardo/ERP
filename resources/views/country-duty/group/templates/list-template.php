<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
              	<th width="2%"></th>
		      	<th width="2%">HsCode</th>
		      	<th width="2%">Origin</th>
		      	<th width="2%">Destination</th>
		        <th width="5%">Value</th>
		        <th width="5%">VAT</th>
		        <th width="5%">Duty</th>
		        <th width="5%">Total</th>
		        <th width="5%">CurrencyType origin</th>
		        <th width="5%">CurrencyType Destination</th>
		        <th width="5%">Duty Rate</th>
		        <th width="5%">Vat Rate</th>
		        <th width="5%">Group Name</th>
		        <th width="5%">Group Duty</th>
		        <th width="5%">Group Vat</th>
		        <th width="5%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data ~isAdmin=is_admin}}
			      <tr>
			      	<td>
			      		<input type="checkbox" class="duty-rate-ckbx" 
				      		name="duty_country[]" 
				      		value="{{:prop.hs_code}}"
				      		data-hs-code="{{:prop.hs_code}}"
				      		data-origin="{{:prop.origin}}"
				      		data-destination="{{:prop.destination}}"
				      		data-vat-rate="{{:prop.vat_percentage}}"
				      		data-duty-rate="{{:prop.duty_percentage}}"
				      		data-vat-val="{{:prop.vat}}"
				      		data-duty-val="{{:prop.duty}}"
				      		data-total="{{:prop.price}}"
				      		data-currency-origin="{{:prop.currency}}"
				      		data-currency-destination="{{:prop.currency}}"
			      		/>
			      	</td>
			      	<td>{{:prop.hs_code}}</td>
			      	<td>{{:prop.origin}}</td>
			      	<td>{{:prop.destination}}</td>
			      	<td>{{:prop.price}}</td>
			      	<td>{{:prop.vat}}</td>
			      	<td>{{:prop.duty}}</td>
			      	<td>{{:prop.price}}</td>
			      	<td>{{:prop.currency}}</td>
			      	<td>{{:prop.currency}}</td>
			      	<td>{{:prop.duty_percentage}}</td>
			      	<td>{{:prop.vat_percentage}}</td>
			      	{{if ~isAdmin}}
			      		<td><input type="text" class="change-inline-field" data-field="name" data-field-master="{{:prop.duty_group_id}}" name="group_name" value="{{:prop.group_name}}"></td>
				      	<td><input type="text" class="change-inline-field" data-field="duty" data-field-master="{{:prop.duty_group_id}}" name="group_duty" value="{{:prop.group_duty}}"></td>
				      	<td><input type="text" class="change-inline-field" data-field="vat" data-field-master="{{:prop.duty_group_id}}" name="group_vat" value="{{:prop.group_vat}}"></td>
			      	{{else}}
				      	<td>{{:prop.group_name}}</td>
				      	<td>{{:prop.group_duty}}</td>
				      	<td>{{:prop.group_vat}}</td>
			      	{{/if}}
			      	<td>
			      		<a class="group-copy-another-country" data-id="{{:prop.id}}" href="javascript:;">Copy</a>
			      		<a class="group-delete-country" data-id="{{:prop.id}}" href="javascript:;">Delete</a>
			      	</td>

			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>


<script type="text/x-jsrender" id="template-create-country-group-form">
<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Create Country Group</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      	<span aria-hidden="true">&times;</span>
      </button>
   </div>
   <div class="modal-body">
		<div class="row">
			<div class="col-lg-12">
				<form>
					<?php echo csrf_field(); ?>
					<div class="row">
				  		<div class="col-md-12">
				    		<div class="form-group">
					         	<?php echo Form::text("name",null,["class" => "form-control group-name"]); ?>
					         </div>
				        </div> 
				        <div class="col-md-12">
					    	<div class="form-group">
					      		<button class="btn btn-secondary create-country-group-btn">Create Country Group</button>
					    	</div>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
	</div>
</div>
</script>

<script type="text/x-jsrender" id="template-duty-group">
<form name="form-create-forn" method="post">
	<?php echo csrf_field(); ?>
	<div class="modal-content">
	   <div class="modal-header">
	      <h5 class="modal-title">Create a Group</h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      <span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
	      <div class="form-row">
	         <div class="form-group col-md-12">
	            <label for="hs_code">HsCode</label>
	            <input type="text" name="hs_code" value="{{if data}}{{:data.hs_code}}{{/if}}" class="form-control" id="title" placeholder="Enter Hscode">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="origin">Origin</label>
	            <input type="text" name="origin" value="{{if data}}{{:data.origin}}{{/if}}" class="form-control" id="title" placeholder="Enter origin">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="destination">Destination</label>
	            <input type="text" name="destination" value="{{if data}}{{:data.destination}}{{/if}}" class="form-control" id="title" placeholder="Enter destination">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="currency">Currency</label>
	            <input type="text" name="currency" value="{{if data}}{{:data.currency}}{{/if}}" class="form-control" id="title" placeholder="Enter currency">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="price">Price</label>
	            <input type="text" name="price" value="{{if data}}{{:data.price}}{{/if}}" class="form-control" id="title" placeholder="Enter price">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="duty">Duty</label>
	            <input type="text" name="duty" value="{{if data}}{{:data.duty}}{{/if}}" class="form-control" id="title" placeholder="Enter duty">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="vat">VAT</label>
	            <input type="text" name="vat" value="{{if data}}{{:data.vat}}{{/if}}" class="form-control" id="title" placeholder="Enter vat">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="duty_percentage">Duty percentage</label>
	            <input type="text" name="duty_percentage" value="{{if data}}{{:data.duty_percentage}}{{/if}}" class="form-control" id="title" placeholder="Enter duty_percentage">
	         </div>
	         <div class="form-group col-md-12">
	            <label for="vat_percentage">VAT percentage</label>
	            <input type="text" name="vat_percentage" value="{{if data}}{{:data.vat_percentage}}{{/if}}" class="form-control" id="title" placeholder="Enter vat_percentage">
	         </div>
	          <div class="form-group col-md-12">
	            <label for="duty_group_id">Duty Group</label>
	            <select class="form-control" name="duty_group_id">
		            <?php foreach(\App\DutyGroup::selectList() as $k => $dglist) { ?>
		            	<option {{if data && data.duty_group_id == "<?php echo $k; ?>"}} selected {{/if}} value="<?php echo $k; ?>"><?php echo $dglist; ?></option>
		            <?php } ?>
	        	</select>
	         </div>
	      </div>
	   </div>
	   <div class="modal-footer">
	      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      <button type="button" class="btn btn-secondary submit-form">Save changes</button>
	   </div>
	</div>
	</form>	
</script>

