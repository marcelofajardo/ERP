<div id="quickDevTaskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Quick Task</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <input type="hidden" name="module_id" value="" id="quick_module_id">
      <input type="hidden" name="status" value="Discussing">

      <div class="modal-body">
        <div class="form-group">
          <textarea class="form-control" name="task" rows="8" cols="80" placeholder="Quick Task" id="quick_task_task">{{ old('task') }}</textarea>
         </select>

          @if ($errors->has('task'))
            <div class="alert alert-danger">{{$errors->first('task')}}</div>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" id="quickTaskSubmit">Add</button>
      </div>
    </div>

  </div>
</div>
