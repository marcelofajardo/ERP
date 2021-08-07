<script type="text/x-jsrender" id="template-add-role">
    <form name="template-create-goal" method="post">
    <?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Add or Edit Role</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="overflow-auto" id="collapse1" style="height:400px;overflow-y:scroll;">
                        <strong>Role:</strong>
                        <input type="text" id="myInputRole" placeholder="Search for roles.." class="form-control search-role">
                        <ul id="myRole" class="padding-left-zero">
                        {{props roles}}
                           <li style="list-style-type: none;">
                           <!-- (in_array($value, $userRole)) ? "checked" : '') -->
                            <a>
                            <input type="checkbox" name="roles[]" value="{{>key}}" {{:~isSelected(prop)}}>
                            <strong>{{>prop}}</strong></a>
                            </li>
                        {{/props}}
                        </ul>
                    </div>
                </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      <button type="button" data-id="{{if user}}{{:user.id}}{{/if}}" class="btn btn-secondary submit-role" data-dismiss="modal">Submit</button>
		   </div>
		</div>
	</form> 
</script>