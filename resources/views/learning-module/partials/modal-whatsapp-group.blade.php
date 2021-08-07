<div id="whatsAppMessageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Whats App Group</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('task.add.whatsapp.participant') }}" method="POST">
        @csrf
        <input type="hidden" name="task_id" id="task_id">
          <input type="hidden" name="group_id" id="group_id">

        <div class="modal-body">
          <div class="form-group">
            <label class="form-group">Add Member To Group</label>
           
             <select class="form-group" name="user_id[]" multiple>
              @foreach($users as $key => $value)
               <option value="{{ $key }}">{{ $value }}</option>
              @endforeach 
             </select>

           </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Add Member To Group</button>
        </div>
    </form>
    </div>

  </div>
</div>
