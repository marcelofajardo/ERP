<script type="text/x-jsrender" id="template-create-form">
	<form name="form-create-forn" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Module{{else}}Create Module{{/if}}</h5>
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
		            <label for="title">Title</label>
		            <input type="text" name="name" value="{{if data}}{{:data.name}}{{/if}}" class="form-control" id="title" placeholder="Enter title">
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