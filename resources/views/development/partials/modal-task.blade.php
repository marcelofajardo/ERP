<div id="createTaskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Task</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('development.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">
         @if(auth()->user()->checkPermission('development-list'))
            <div class="form-group">
              <strong>User:</strong>
              <select class="form-control" name="user_id" required>
                @foreach ($users as $id => $name)
                  <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
             </select>

              @if ($errors->has('user_id'))
                  <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
              @endif
            </div>
          @endif

          <div class="form-group">
            <strong>Attach files:</strong>
            <input type="file" name="images[]" class="form-control" multiple>
            @if ($errors->has('images'))
            <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Module:</strong>
              <br>
            <select class="form-control select2" name="module_id" >
              <option value>Select a Module</option>
              @foreach ($modules as $module)
                <option value="{{ $module->id }}" {{ $module->id == old('module_id') ? 'selected' : '' }}>{{ $module->name }}</option>
              @endforeach
           </select>

            @if ($errors->has('module_id'))
                <div class="alert alert-danger">{{$errors->first('module_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Priority:</strong>
            <select class="form-control" name="priority" required>
              <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
              <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
              <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
           </select>

            @if ($errors->has('priority'))
                <div class="alert alert-danger">{{$errors->first('priority')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Subject:</strong>
            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" />
           </select>

            @if ($errors->has('subject'))
              <div class="alert alert-danger">{{$errors->first('subject')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Task:</strong>
            <textarea class="form-control" name="task" rows="8" cols="80" required>{{ old('task') }}</textarea>
           </select>

            @if ($errors->has('task'))
              <div class="alert alert-danger">{{$errors->first('task')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Cost:</strong>
            <input type="number" class="form-control" name="cost" value="{{ old('cost') }}" />
           </select>

            @if ($errors->has('cost'))
              <div class="alert alert-danger">{{$errors->first('cost')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Status:</strong>
            <select class="form-control" name="status" required>
              <option value="Discussing" {{ old('status') == 'Discussing' ? 'selected' : '' }}>Discussing</option>
              <option value="Planned" {{ old('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
              <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
              <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
           </select>

            @if ($errors->has('status'))
                <div class="alert alert-danger">{{$errors->first('status')}}</div>
            @endif
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>

<div id="editTaskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Task</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="" id="editTaskForm" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">
         @if(auth()->user()->checkPermission('development-list'))
            <div class="form-group">
              <strong>User:</strong>
              <select class="form-control" name="user_id" id="user_field" required>
                @foreach ($users as $id => $name)
                  <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
             </select>

              @if ($errors->has('user_id'))
                  <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
              @endif
            </div>
          @endif

          <div class="form-group">
            <strong>Attach files:</strong>
            <input type="file" name="images[]" class="form-control" multiple>
            @if ($errors->has('images'))
            <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Priority:</strong>
            <select class="form-control" name="priority" id="priority_field" required>
              <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
              <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
              <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
           </select>

            @if ($errors->has('priority'))
                <div class="alert alert-danger">{{$errors->first('priority')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Module:</strong>
            <select class="form-control" name="module_id" id="module_id_field">
              <option value>Select a Module</option>
              @foreach ($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
              @endforeach
           </select>

            @if ($errors->has('module_id'))
                <div class="alert alert-danger">{{$errors->first('module_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Subject:</strong>
            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" id="task_subject" />
           </select>

            @if ($errors->has('subject'))
              <div class="alert alert-danger">{{$errors->first('subject')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Task:</strong>
            <textarea class="form-control" name="task" rows="8" cols="80" id="task_field" required>{{ old('task') }}</textarea>
           </select>

            @if ($errors->has('task'))
              <div class="alert alert-danger">{{$errors->first('task')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Cost:</strong>
            <input type="number" class="form-control" name="cost" id="cost_field" value="{{ old('cost') }}" />
           </select>

            @if ($errors->has('cost'))
              <div class="alert alert-danger">{{$errors->first('cost')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Status:</strong>
            <select class="form-control" name="status" id="status_field" required>
              <option value="Discussing" {{ old('status') == 'Discussing' ? 'selected' : '' }}>Discussing</option>
              <option value="Planned" {{ old('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
              <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
              <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
           </select>

            @if ($errors->has('status'))
                <div class="alert alert-danger">{{$errors->first('status')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Estimate minutes:</strong>
            <div class='input-group' id='estimate_minutes'>
              <input type='text' class="form-control" name="estimate_minutes" id="estimate_minutes_field" value="" />
            </div>

            @if ($errors->has('estimate_minutes'))
                <div class="alert alert-danger">{{$errors->first('estimate_minutes')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Start Time:</strong>
            <div class='input-group date' id='start_time'>
              <input type='text' class="form-control" name="start_time" id="start_time_field" value="{{ date('Y-m-d H:i') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('start_time'))
                <div class="alert alert-danger">{{$errors->first('start_time')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>End Time:</strong>
            <div class='input-group date' id='end_time'>
              <input type='text' class="form-control" name="end_time" id="end_time_field" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('end_time'))
                <div class="alert alert-danger">{{$errors->first('end_time')}}</div>
            @endif
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Update</button>
        </div>
      </form>
    </div>

  </div>
</div>
