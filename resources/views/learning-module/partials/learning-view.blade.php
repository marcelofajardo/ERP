<table class="table table-sm table-bordered">
  <thead>
    <tr>
      <th width="5%">ID</th>
      <th width="15%">Date</th>
      <th width="10%" class="category">Category</th>
      <th width="45%">Task Subject</th>
      <th width="15%" colspan="2">From / To</th>
      <th width="10%">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tasks_view as $task)
    <tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }} {{ $task->is_statutory == 3 ? 'row-highlight' : '' }}" id="task_{{ $task->id }}">
      <td class="p-2">{{ $task->id }}</td>
      <td class="p-2">{{ Carbon\Carbon::parse($task->created_at)->format('d-m H:i') }}</td>
      <td class="expand-row table-hover-cell p-2">
        @if (isset($categories[$task->category]))
        <span class="td-mini-container">
          {{ strlen($categories[$task->category]) > 10 ? substr($categories[$task->category], 0, 10) : $categories[$task->category] }}
        </span>

        <span class="td-full-container hidden">
          {{ $categories[$task->category] }}
        </span>
        @endif
      </td>
      <td class="expand-row table-hover-cell p-2" data-subject="{{$task->task_subject ? $task->task_subject : 'Task Details'}}" data-details="{{$task->task_details}}" data-switch="0" style="word-break: break-all;">

        <span class="td-mini-container">
          {{ $task->task_subject ? substr($task->task_subject, 0, 18) . (strlen($task->task_subject) > 15 ? '...' : '') : 'Task Details' }}
        </span>

        <span class="td-full-container hidden">
          <strong>{{ $task->task_subject ? $task->task_subject : 'Task Details' }}</strong>

          {{ $task->task_details }}
        </span>
      </td>
      <td class="expand-row table-hover-cell p-2">
        @if (array_key_exists($task->assign_from, $users))
        @if ($task->assign_from == Auth::id())
        <span class="td-mini-container">
          <a href="{{ route('users.show', $task->assign_from) }}">{{ strlen($users[$task->assign_from]) > 4 ? substr($users[$task->assign_from], 0, 4) : $users[$task->assign_from] }}</a>
        </span>

        <span class="td-full-container hidden">
          <a href="{{ route('users.show', $task->assign_from) }}">{{ $users[$task->assign_from] }}</a>
        </span>
        @else
        <span class="td-mini-container">
          {{ strlen($users[$task->assign_from]) > 4 ? substr($users[$task->assign_from], 0, 4) : $users[$task->assign_from] }}
        </span>

        <span class="td-full-container hidden">
          {{ $users[$task->assign_from] }}
        </span>
        @endif
        @else
        Doesn't Exist
        @endif
      </td>
      <td class="expand-row table-hover-cell p-2">
        @php
        $special_task = \App\Task::find($task->id);
        $users_list = '';

        foreach ($special_task->users as $key => $user) {
        if ($key != 0) {
        $users_list .= ', ';
        }

        if (array_key_exists($user->id, $users)) {
        $users_list .= $users[$user->id];
        } else {
        $users_list = 'User Does Not Exist';
        }
        }

        $users_list .= ' ';

        foreach ($special_task->contacts as $key => $contact) {
        if ($key != 0) {
        $users_list .= ', ';
        }

        $users_list .= "$contact->name - $contact->phone" . ucwords($contact->category);
        }
        @endphp

        <span class="td-mini-container">
          {{ strlen($users_list) > 6 ? substr($users_list, 0, 6) : $users_list }}
        </span>

        <span class="td-full-container hidden">
          {{ $users_list }}
        </span>
      </td>

      <td class="p-2">
        <div class="d-flex">
          @if ($special_task->users->contains(Auth::id()) || $task->assign_from == Auth::id() || $task->master_user_id == Auth::id())
          @if ($task->is_completed == '')
          <button type="button" class="btn btn-image task-complete" data-id="{{ $task->id }}"><img src="/images/incomplete.png" /></button>
          @else
          @if ($task->assign_from == Auth::id())
          <button type="button" class="btn btn-image task-complete" data-id="{{ $task->id }}"><img src="/images/completed-green.png" /></button>
          @else
          <button type="button" class="btn btn-image"><img src="/images/completed-green.png" /></button>
          @endif
          @endif

          <button type="button" class='btn btn-image ml-1 reminder-message' data-id="{{ $task->message_id }}" data-toggle='modal' data-target='#reminderMessageModal'><img src='/images/reminder.png' /></button>
         @endif

          @if ((!$special_task->users->contains(Auth::id()) && $special_task->contacts()->count() == 0))
          @if ($task->is_private == 1)
          <button disabled type="button" class="btn btn-image"><img src="/images/private.png" /></button>
          @else
          {{-- <a href="{{ route('task.show', $task->id) }}" class="btn btn-image" href=""><img src="/images/view.png" /></a> --}}
          @endif
          @endif

          @if ($special_task->users->contains(Auth::id()) || ($task->assign_from == Auth::id() && $task->is_private == 0) || ($task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
          <a href="{{ route('task.show', $task->id) }}" class="btn btn-image" href=""><img src="/images/view.png" /></a>
          @endif

          @if ($special_task->users->contains(Auth::id()) || (!$special_task->users->contains(Auth::id()) && $task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
          @if ($task->is_private == 1)
          <button type="button" class="btn btn-image make-private-task" data-taskid="{{ $task->id }}"><img src="/images/private.png" /></button>
          @else
          <button type="button" class="btn btn-image make-private-task" data-taskid="{{ $task->id }}"><img src="/images/not-private.png" /></button>
          @endif
          @endif

          @if ($task->is_flagged == 1)
          <button type="button" class="btn btn-image flag-task" data-id="{{ $task->id }}"><img src="/images/flagged.png" /></button>
          @else
          <button type="button" class="btn btn-image flag-task" data-id="{{ $task->id }}"><img src="/images/unflagged.png" /></button>
          @endif
        </div>

      </td>
    </tr>
    @endforeach
  </tbody>
</table>
