<script type="text/x-jsrender" id="form-create-size-page">
	<form name="form-create-size-page" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Size{{else}}Create Size{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
	         {{if data}}
	         	<input type="hidden" name="id" value="{{:data.id}}"/>
	         {{/if}}
	         <div class="form-group">
	            <label for="name">Name</label>
	            <input type="text" name="name" value="{{if data}}{{:data.name}}{{/if}}" class="form-control" id="name" placeholder="Enter name">
	         </div>
	         <?php if(!empty($storeWebsites)) { ?>
		        <?php foreach($storeWebsites as $v => $sw){  ?>
		        	<div class="form-group">
		        		<label for="name"><?php echo $sw; ?> (Platform id)</label>
		        		<input class="form-control" name="store_website[<?php echo $v; ?>]" value="{{if stores && stores.store_<?php echo $v; ?>}}{{:stores.store_<?php echo $v; ?>}}{{/if}}">
		        	</div>
		        <?php } ?>
	         <?php } ?>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-secondary submit-platform">Save changes</button>
		   </div>
		</div>
	</form>
</script>