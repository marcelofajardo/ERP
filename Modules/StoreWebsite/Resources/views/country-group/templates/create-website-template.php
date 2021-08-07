<script type="text/x-jsrender" id="template-create-form">
	<form name="form-create-forn" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Group{{else}}Create Group{{/if}}</h5>
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
		            <label for="status">Group Name</label>
		            <input type="text" name="name" value="{{if data}}{{:data.name}}{{/if}}" class="form-control" id="name" placeholder="Enter name">
		         </div>
		         <div class="form-group col-md-12">
		            <label for="status">Group Country</label>
		            <select name="country_code[]" class="form-control select2 option-mul-code-group" multiple="true" style="width:100%;">
			            <?php foreach(\App\SimplyDutyCountry::getSelectList() as $k => $l){ ?>
							<option
							  {{if ~inArray('<?php echo $k ?>',data.items)}}
							  	selected
							  {{/if}}
							 value="<?php echo $k ?>"><?php echo $l; ?></option>
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