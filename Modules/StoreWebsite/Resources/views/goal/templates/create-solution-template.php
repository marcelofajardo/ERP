<script type="text/x-jsrender" id="template-create-goal">
	<form name="template-create-goal" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Goal{{else}}Create Goal{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		      <div class="form-row">
		         {{if data}}
		         	<input type="hidden" name="goal_id" value="{{:data.id}}"/>
		         {{/if}}
		         <div class="form-group col-md-6">
		            <label for="goal">Goal</label>
		            <input type="text" name="goal" value="{{if data}}{{:data.goal}}{{/if}}" class="form-control" id="goal" placeholder="Enter goal">
		         </div>
		         <div class="form-group col-md-6">
		            <label for="solution">Solution</label>
		            <input type="text" name="solution" value="{{if data}}{{:data.solution}}{{/if}}" class="form-control" id="solution" placeholder="Enter solution">
		         </div>
		      </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-secondary submit-goal">Save changes</button>
		   </div>
		</div>
	</form>  	
</script>