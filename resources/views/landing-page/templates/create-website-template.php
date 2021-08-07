<script type="text/x-jsrender" id="form-create-landing-page">
	<form name="form-create-landing-page" method="post">
		<?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}}Edit Landing Page Product{{else}}Create Landing Page Product{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
	         {{if data}}
	         	<input type="hidden" name="id" value="{{:data.id}}"/>
	         {{/if}}
	         <div class="form-group">
	            <label for="product_id">Product Id</label>
	            <input type="text" name="product_id" value="{{if data}}{{:data.product_id}}{{/if}}" class="form-control" id="product_id" placeholder="Enter product id">
	         </div>
	         <div class="form-group">
	            <label for="name">Product name</label>
	            <input type="text" name="name" value="{{if data}}{{:data.name}}{{/if}}" class="form-control" id="name" placeholder="Enter product name">
	         </div>
	         <div class="form-group">
	            <label for="description">Product description</label>
	            <textarea name="description" class="form-control" id="name" placeholder="Enter description">{{if data}}{{:data.description}}{{/if}}</textarea>
	         </div>
	         <div class="form-group">
	            <label for="price">Product Price</label>
	            <input type="text" name="price" value="{{if data}}{{:data.price}}{{/if}}" class="form-control" id="price" placeholder="Enter product price">
	         </div>
	         <div class="form-group">
                <strong>Date Range</strong>
                <input type="text" value="{{if data && data.start_date}}{{:data.start_date}}{{else}}<?php echo date("Y-m-d 00:00:00"); ?>{{/if}}" name="start_date" hidden/>
                <input type="text" value="{{if data && data.end_date}}{{:data.end_date}}{{else}}<?php echo date("Y-m-d 00:00:00"); ?>{{/if}}" name="end_date" hidden/>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
	         <div class="form-group">
	            <label for="inputState">Status?</label>
	            <select name="status" id="inputState" class="form-control">
	               <?php  foreach($statuses as  $k => $s) { ?>
	               		<option {{if data && data.status == <?php echo $k; ?>}}selected{{/if}} value="<?php echo $k; ?>"><?php echo $s; ?></option>
	               <?php } ?>
	            </select>
	         </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-secondary submit-platform">Save changes</button>
		   </div>
		</div>
	</form>
</script>