<div id="addPermission" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Permisson</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
      @csrf
        <div class="modal-body">
          <div class="form-group">
          
          <div class="form-group">
            <strong>Permission Name</strong>
            <input type="" name="name" class="form-control" required>
          </div>

          <div class="form-group">
            <strong>Permission Route</strong>
            <input type="" name="route" class="form-control" required>
          </div>

          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" onclick="addPermission()">Add Permission</button>
        </div>
      </form>
    </div>

  </div>
</div>
