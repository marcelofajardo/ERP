<div id="userHubstaffRoleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" id="user-hubstaff-role-form" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Change Vendor Role In Hubstaff</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <input type="hidden" id="hidden-vendor-id" name="vendor_id">
          <div class="form-group">
            <strong>Role:</strong>
            <select class="form-control" name="role">
              <option value="">Select a Role</option>
              <option value="user">User</option>
              <option value="viewer">Viewer</option>
              <option value="manager">Manager</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>
      </form>
    </div>

  </div>
</div>
