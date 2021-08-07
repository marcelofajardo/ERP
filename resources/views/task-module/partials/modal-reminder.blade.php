<div id="reminderMessageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Message Reminder</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('task.message.reminder') }}" method="POST">
        @csrf
        <input type="hidden" name="message_id" value="">

        <div class="modal-body">
          <div class="form-group">
            <div class='input-group date' id='reminder-datetime'>
              <input type='text' class="form-control input-sm" name="reminder_date" value="{{ date('Y-m-d H:i') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('reminder_date'))
              <div class="alert alert-danger">{{$errors->first('reminder_date')}}</div>
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
