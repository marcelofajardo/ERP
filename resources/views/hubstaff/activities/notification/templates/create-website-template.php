<script type="text/x-jsrender" id="template-create-form">
	<form name="form-create-forn" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Hubstaff Payment{{else}}Create Hubstaff Payment{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-12">
		            <label for="billing_start">Billing Start</label>
		            <input type="text" name="billing_start" value="{{if data}}{{:data.billing_start}}{{/if}}" class="form-control" id="billing_start" placeholder="Enter Billing start">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="billing_end">Billing End</label>
		            <input type="text" name="billing_end" value="{{if data}}{{:data.billing_end}}{{/if}}" class="form-control" id="billing_end" placeholder="Enter Billing end">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="hrs">Hrs</label>
		            <input type="text" name="hrs" value="{{if data}}{{:data.hrs}}{{/if}}" class="form-control" id="hrs" placeholder="Enter Billing hrs">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="rate">Rate</label>
		            <input type="text" name="rate" value="{{if data}}{{:data.rate}}{{/if}}" class="form-control" id="rate" placeholder="Enter Billing rate">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="currency">Rate Currency</label>
		            <input type="text" name="currency" value="{{if data}}{{:data.currency}}{{/if}}" class="form-control" id="currency" placeholder="Enter Billing currency">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="payment_currency">Payment Currency</label>
		            <input type="text" name="payment_currency" value="{{if data}}{{:data.payment_currency}}{{/if}}" class="form-control" id="payment_currency" placeholder="Enter Payment currency">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="total_payout">Total Payout</label>
		            <input type="text" name="total_payout" value="{{if data}}{{:data.total_payout}}{{/if}}" class="form-control" id="total_payout" placeholder="Enter total payout">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="total_payout">Total Paid</label>
		            <input type="text" name="total_paid" value="{{if data}}{{:data.total_paid}}{{/if}}" class="form-control" id="total_paid" placeholder="Enter total paid">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="ex_rate">Exchange Rate</label>
		            <input type="text" name="ex_rate" value="{{if data}}{{:data.ex_rate}}{{/if}}" class="form-control" id="ex_rate" placeholder="Enter exchange rate">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="scheduled_on">Scheduled on</label>
		            <input type="text" name="scheduled_on" value="{{if data}}{{:data.scheduled_on}}{{/if}}" class="form-control" id="scheduled_on" placeholder="Enter Scheduled on">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="ex_rate">Exchange Rate</label>
		            <select name="status" class="form-control">
						<?php foreach(\App\Hubstaff\HubstaffPaymentAccount::STATUS as $k => $status){ ?>
			            	<option {{if data && data.status == <?php echo $k; ?>}}selected{{/if}} value="<?php echo $k; ?>"><?php echo $status; ?></option>
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