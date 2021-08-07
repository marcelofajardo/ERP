<div id="taskStatusModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Task Status</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('task.status.create') }}" method="POST">
        @csrf
    

        <div class="modal-body">
          <div class="form-group">
            <label>Status Title</label>
            <div class='input-group' >
              <input type='text' class="form-control input-sm" name="task_status" value="" required />

             
            </div>

            @if ($errors->has('task_status'))
              <div class="alert alert-danger">{{$errors->first('task_status')}}</div>
            @endif
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Create</button>
        </div>
      </form>
    </div>

  </div>
</div>
