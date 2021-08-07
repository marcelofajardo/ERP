<script type="text/x-jsrender" id="template-team-edit">
<form  method="post">
    <?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Team lists</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
		   <div class="form-group">
                  <strong>Leader</strong>
                  <input type="text" value="{{:team.user.name}}" class="form-control" readonly>
			</div>
         <div class="form-group">
                  <strong>Members</strong>
                  {{props team.members}}
                     <input style="margin-top:5px;" type="text" value="{{:prop}}" class="form-control" readonly>
                  {{/props}}
                  
			</div>
			<div class="form-group">
                  <strong>Team name</strong>
                  <input type="text" id="name" name="name" value="{{:team.name}}" class="form-control">
            </div>
           <div class="overflow-auto" id="collapse" style="height:400px;overflow-y:scroll;">
                    <strong>Members {{if totalMembers}} ({{:totalMembers}}) {{/if}}:</strong>
                    <input type="text" id="myInput" placeholder="Search for users.." class="form-control search-user">
                    <ul id="myUL" class="padding-left-zero">
                    {{props users}}
                           <li style="list-style-type: none;">
                            <a>
                            <input type="checkbox" name="members[]" value="{{>key}}" {{:~isMemberSelected(prop)}}>
                            <strong>{{>prop}}</strong></a>
                            </li>
                        {{/props}} 
                    </ul>
                </div>
		   </div>
		   <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" data-id="{{:team.id}}" class="btn btn-secondary delete-team" data-dismiss="modal">Delete Team</button>
		      <button type="button" data-id="{{:team.id}}" class="btn btn-secondary edit-team" data-dismiss="modal">Submit</button>
		   </div>
		</div>
	</form> 
</script>