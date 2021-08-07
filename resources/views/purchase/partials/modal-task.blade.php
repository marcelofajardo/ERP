<div id="taskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Task</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="task_type" value="quick_task">
        <input type="hidden" name="model_type" value="purchase">
        <input type="hidden" name="model_id" value="{{ $order->id }}">

        <div class="modal-body">
          <div class="form-group">
            <strong>Task Subject:</strong>
            <input type="text" class="form-control" name="task_subject" placeholder="Task Subject" id="task_subject" required />
            @if ($errors->has('task_subject'))
            <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Task Details:</strong>
            <textarea class="form-control" name="task_details" placeholder="Task Details" required></textarea>
            @if ($errors->has('task_details'))
            <div class="alert alert-danger">{{$errors->first('task_details')}}</div>
            @endif
          </div>

          <div class="form-group" id="completion_form_group">
            <strong>Completion Date:</strong>
            <div class='input-group date' id='completion-datetime'>
              <input type='text' class="form-control" name="completion_date" value="{{ date('Y-m-d H:i') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('completion_date'))
            <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Assigned To:</strong>
            <select name="assign_to[]" class="form-control" multiple required>
              @foreach($users as $user)
              <option value="{{$user['id']}}">{{$user['name']}}</option>
              @endforeach
            </select>
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
