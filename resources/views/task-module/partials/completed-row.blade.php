<tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }} completed" id="task_{{ $task->id }}">
    <td class="p-2">{{ $task->id }}</td>
    <td class="p-2">{{ Carbon\Carbon::parse($task->created_at)->format('d-m H:i') }}
    <br>
    @if($task->customer_id)
        Cus-{{$task->customer_id}}
        <br>
        @if(Auth::user()->isAdmin())
        @php
            $customer = \App\Customer::find($task->customer_id);
        @endphp
        <span>
          {{ isset($customer ) ? $customer->name : '' }}
        </span>
        @endif
    @endif
    </td>
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
    <!-- <td class="expand-row table-hover-cell p-2">
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
    </td> -->
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
    <td>{{ Carbon\Carbon::parse($task->is_completed)->format('d-m H:i') }}</td>
    <td class="expand-row table-hover-cell p-2 {{ $task->message && $task->message_status == 0 ? 'text-danger' : '' }}">
        @if ($task->assign_to == Auth::id() || ($task->assign_to != Auth::id() && $task->is_private == 0))
            @if (isset($task->message))
                <div class="d-flex">
                    <p style="width:85%" class="td-mini-container">
                        {{ strlen($task->message) > 32 ? substr($task->message, 0, 29) . '...' : $task->message }}
                    </p>
                    <p style="width:85%" class="td-full-container hidden">
                        {{ $task->message }}
                    </p>
                    <button type="button" class="btn btn-xs btn-image load-communication-modal pull-right" data-object='task' data-id="{{ $task->id }}" title="Load messages"><img src="/images/chat.png" alt=""></button>
                </div>
            @endif
        @else
            Private
        @endif
    </td>
    <td class="p-2">
        <div class="row" style="margin:0px;">
            @if ((!$special_task->users->contains(Auth::id()) && $special_task->contacts()->count() == 0))
                @if ($task->is_private == 1)
                    <button disabled type="button" class="btn btn-image pd-5"><img src="/images/private.png"/></button>
                @endif
            @endif

            @if ($special_task->users->contains(Auth::id()) || ($task->assign_from == Auth::id() && $task->is_private == 0) || ($task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
                <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="/images/view.png"/></a>
            @endif

            @if ($task->assign_from == Auth::id() && $task->is_verified)
                <button type="button" title="Reopen the task" class="btn btn-image task-verify pd-5" data-id="{{ $task->id }}"><img src="/images/completed.png"/></button>     
            @endif


            <form action="{{ route('task.archive', $task->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-image pd-5"><img src="/images/archive.png"/></button>
            </form>
        </div>
    </td>
</tr>