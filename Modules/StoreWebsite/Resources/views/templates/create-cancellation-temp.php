<script type="text/x-jsrender" id="template-create-website-cancellation">
	<form name="form-create-website-cancellation" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edit Cancellation Policy {{else}}Create Cancellation Policy{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="id" id="cancellation_id" value="{{:data.id}}"/>
		         {{/if}}
                 <input type="hidden" name="store_website_id" id="store_website_id" value="{{:data.store_website_id}}"/>
		         </div>
                 <div class="form-group">
		            <label for="website">No. of Days if Cancellation</label>
		            <input type="text" name="days_cancelation" value="{{if data}}{{:data.days_cancelation}}{{/if}}" class="form-control" id="days_cancelation" placeholder="Enter No. of Days for Cacellation">
		         </div>
                 <div class="form-group">
		            <label for="website">No. of Days if Refund</label>
		            <input type="text" name="days_refund" value="{{if data}}{{:data.days_refund}}{{/if}}" class="form-control" id="days_refund" placeholder="Enter No. of Days for Refund">
		         </div>
                 <div class="form-group">
		            <label for="website">Percentage</label>
		            <input type="text" name="percentage" value="{{if data}}{{:data.percentage}}{{/if}}" class="form-control" id="percentage" placeholder="Enter Percentage">
		         </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-primary submit-store-site-cancellation">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>