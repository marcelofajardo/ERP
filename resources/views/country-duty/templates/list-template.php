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
		        <th width="5%">ExchangeRate</th>
		        <th width="5%">CurrencyType origin</th>
		        <th width="5%">CurrencyType Destination</th>
		        <th width="5%">Duty Rate</th>
		        <th width="5%">Duty Type</th>
		        <th width="5%">Vat Rate</th>
		        <th width="5%">Quantity</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>
			      		<input type="checkbox" class="duty-rate-ckbx" 
				      		name="duty_country[]" 
				      		value="{{:prop.HsCode}}"
				      		data-hs-code="{{:prop.HsCode}}"
				      		data-origin="{{:prop.Origin}}"
				      		data-destination="{{:prop.Destination}}"
				      		data-vat-rate="{{:prop.VatRate}}"
				      		data-duty-rate="{{:prop.DutyRate}}"
				      		data-vat-val="{{:prop.VAT}}"
				      		data-duty-val="{{:prop.Duty}}"
				      		data-total="{{:prop.Total}}"
				      		data-currency-origin="{{:prop.CurrencyTypeOrigin}}"
				      		data-currency-destination="{{:prop.CurrencyTypeDestination}}"
			      		/>
			      	</td>
			      	<td>{{:prop.HsCode}}</td>
			      	<td>{{:prop.Origin}}</td>
			      	<td>{{:prop.Destination}}</td>
			      	<td>{{:prop.Value}}</td>
			      	<td>{{:prop.VAT}}</td>
			      	<td>{{:prop.Duty}}</td>
			      	<td>{{:prop.Total}}</td>
			      	<td>{{:prop.ExchangeRate}}</td>
			      	<td>{{:prop.CurrencyTypeOrigin}}</td>
			      	<td>{{:prop.CurrencyTypeDestination}}</td>
			      	<td>{{:prop.DutyRate}}</td>
			      	<td>{{:prop.DutyType}}</td>
			      	<td>{{:prop.VatRate}}</td>
			      	<td>{{:prop.Quantity}}</td>
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