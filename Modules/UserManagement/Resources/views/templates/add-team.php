<script type="text/x-jsrender" id="template-team-add">
<form  method="post">
    <?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Add team</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           <div class="form-group">
                  <strong>Name</strong>
                  <input type="text" id="name" name="name" class="form-control">
            </div>
			<div class="overflow-auto" id="collapse" style="height:400px;overflow-y:scroll;">
                <strong>Members:</strong>
                <input type="text" id="myInput" placeholder="Search for users.." class="form-control search-user">
                <ul id="myUL" class="padding-left-zero">
					{{props users}}
						<li style="list-style-type: none;">
							<a>
								<input type="checkbox" name="members[]" value="{{>key}}">
								<strong>{{>prop}}</strong></a>
						</li>
					{{/props}} 
                </ul>
                </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      <button type="button" data-id="{{if user}}{{:user.id}}{{/if}}" class="btn btn-secondary submit-team" data-dismiss="modal">Submit</button>
		   </div>
		</div>
	</form> 
</script>