<div id="taskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('development.issue.create') }}" method="POST" id="quickTaskForm">
        @csrf
        <input type="hidden" name="priority" value="1">
        <input type="hidden" name="module" value="52" id="module">
        <input type="hidden" name="response" value="1" id="response">
        <input type="hidden" id="references" name="reference">
        

        <div class="modal-header">
          <h4 class="modal-title">Store a Issue</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Issue Subject:</strong>
            <input type="text" class="form-control" name="issue" placeholder="Task Subject" value="{{ old('task_subject') }}" id="task_subject" required />
            @if ($errors->has('task_subject'))
              <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
            @endif
          </div>
          
          <div class="form-group">
              <strong>Assigned To:</strong>
              @php
                $quick_task_users = \App\User::all();
              @endphp

              <select class="selectpicker form-control" data-live-search="true" data-size="15"  name="responsible_user_id" title="Choose a User">
                @foreach ($quick_task_users as $user)
                  <option data-tokens="{{ $user['name'] }} {{ $user['email'] }}" value="{{ $user['id'] }}">{{ $user['name'] }} - {{ $user['email'] }}</option>
                @endforeach
              </select>
          </div>
          
          



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" id="submitIssue">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>
