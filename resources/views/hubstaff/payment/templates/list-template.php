<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%"></th>
		      	<th width="2%">User</th>
		        <th width="10%">Billing Start</th>
		        <th width="10%">Billing End</th>
		        <th width="2%">Hrs</th>
		        <th width="2%">Rate</th>
		        <th width="10%">Total Payout</th>
		        <th width="10%">Total Paid</th>
		        <th width="10%">Scheduled on</th>
		        <th width="10%">Status</th>
		        <th width="10%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>
			      		<input type="checkbox" class="payment-update-ckbx" name="payment[]" value="{{:prop.id}}"/>
			      	</td>
			      	<td>{{:prop.user_name}}</td>
			        <td>{{:prop.billing_start}}</td>
			        <td>{{:prop.billing_end}}</td>
			        <td>{{:prop.hrs}}</td>
			        <td>{{:prop.rate}}</td>
			        <td>{{:prop.total_payout}} {{:prop.payment_currency}}</td>
			        <td>{{:prop.total_paid}} {{:prop.payment_currency}}</td>
			        <td>{{:prop.scheduled_on}}</td>
			        <td>{{:prop.status_name}}</td>
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


<script type="text/x-jsrender" id="template-merge-category">
<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Merge Category</h5>
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
					         	<?php echo Form::select("merge_category",\App\VendorCategory::pluck("title","id")->toArray(),null,["class" => "form-control select2-vendor-category merge-category"]); ?>
					         </div>
				        </div> 
				        <div class="col-md-12">
					    	<div class="form-group">
					      		<button class="btn btn-secondary merge-category-btn">Merge and Delete</button>
					    	</div>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
	</div>
</div>
</script>