<script type="text/x-jsrender" id="template-add-permission">



    <form  method="post">
    <?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Add or Edit Permission</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           <p class="btn btn-primary open-permission-input pull-right">Add New</p><br>
           <br>
           <br>
          <div id="permission-from" class="dropdown-wrapper hidden">
              <div class="payment-dropdown-header">
                  <div class="form-group">
                  <strong>Permission Name</strong>
                  <input type="text" id="name" name="name" class="form-control" required>
                  </div>
                <div class="form-group">
                  <strong>Permission Route</strong>
                  <input type="text" id="route" name="route" class="form-control" required>
                </div>
                  <button type="button" class="btn btn-sm btn-primary add-permission pull-right">Submit</button><br><br>
              </div>
          </div>
           <div class="overflow-auto" id="collapse" style="height:400px;overflow-y:scroll;">
                    <strong>Permission:</strong>
                    <input type="text" id="myInput" placeholder="Search for permissions.." class="form-control search-permission">
                    <ul id="myUL" class="padding-left-zero">
                    {{props permissions}}
                           <li style="list-style-type: none;">
                            <a>
                            <input type="checkbox" name="permissions[]" value="{{>prop.id}}" {{:~isPermissionSelected(prop.name)}}>
                            <strong>{{>prop.name}}</strong></a>
                            </li>
                        {{/props}} 
                    </ul>
                </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      <button type="button" data-id="{{if user}}{{:user.id}}{{/if}}" class="btn btn-secondary submit-permission" data-dismiss="modal">Submit</button>
		   </div>
		</div>
	</form> 
</script>