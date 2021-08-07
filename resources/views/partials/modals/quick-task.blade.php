<div id="quickTaskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('task.store') }}" method="POST" id="quickTaskForm">
        @csrf
        <input type="hidden" name="is_statutory" value="0">
        <input type="hidden" name="reference" value="1">
        <div class="modal-header">
          <h4 class="modal-title">Store a Task</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Task Subject:</strong>
            <input type="text" class="form-control" name="task_subject" placeholder="Task Subject" value="{{ old('task_subject') }}" id="quick_task_subject" required />
            @if ($errors->has('task_subject'))
              <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Task Details:</strong>
            <textarea class="form-control" name="task_details" placeholder="Task Details" id="quick_task_details" required>{{ old('task_details') }}</textarea>
            @if ($errors->has('task_details'))
              <div class="alert alert-danger">{{$errors->first('task_details')}}</div>
            @endif
          </div>

          <div class="form-group">
              <strong>Assigned To:</strong>
              @php
                // $quick_task_users = \App\User::all();
              @endphp

              <select class="globalSelect2 form-control" data-ajax="{{ route('select2.user',['format' => 'name-email']) }}" data-live-search="true" data-size="15" id="quick_task_assign_to" name="assign_to[]" data-placeholder="Choose a User" multiple>
                <option></option>
                {{-- @foreach ($quick_task_users as $user)
                  <option data-tokens="{{ $user['name'] }} {{ $user['email'] }}" value="{{ $user['id'] }}">{{ $user['name'] }} - {{ $user['email'] }}</option>
                @endforeach --}}
              </select>

              @if ($errors->has('assign_to'))
                <div class="alert alert-danger">{{$errors->first('assign_to')}}</div>
              @endif
          </div>

          <div class="form-group">
            <select class="selectpicker form-control" data-live-search="true" data-size="15" id="quick_task_assign_to_contacts" name="assign_to_contacts[]" title="Choose a Contact" multiple>
              @foreach (Auth::user()->contacts as $contact)
                <option data-tokens="{{ $contact['name'] }} {{ $contact['phone'] }} {{ $contact['category'] }}" value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
              @endforeach
            </select>

            @if ($errors->has('assign_to_contacts'))
              <div class="alert alert-danger">{{$errors->first('assign_to_contacts')}}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" id="quickTaskSubmit">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>
