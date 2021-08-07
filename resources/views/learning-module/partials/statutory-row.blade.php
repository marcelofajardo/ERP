<tr id="task_{{ $task->id }}">
    <td class="p-2">
        @if(auth()->user()->isAdmin())
            <input type="checkbox"  name="selected_issue[]" title="Task is in priority" value="{{$task->id}}" {{in_array($task->id, $priority) ? 'checked' : ''}}>
        @endif
        {{ $task->id }}
    </td>
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
    <td class="p-2">
        {{ strlen($task->recurring_type) > 6 ? substr($task->recurring_type, 0, 6) : $task->recurring_type }}
    </td>
    <td>
        @if(auth()->user()->id == $task->assign_to || auth()->user()->isAdmin())
            <input type="text" style="width:80%;display:inline;" class="update_approximate form-control input-sm" name="approximate" data-id="{{$task->id}}" value="{{$task->approximate}}">
            <button type="button" style="width:10%;display:inline-block;padding:0px;" class="btn btn-xs show-time-history" title="Show History" data-id="{{$task->id}}"><i class="fa fa-info-circle"></i></button>
            <span class="text-success update_approximate_msg" style="display: none;">Successfully updated</span>
        @else
            <span class="apx-val">{{$task->approximate}}</span>
        @endif
    </td>
    <td class="expand-row table-hover-cell p-2 {{ $task->message && $task->message_status == 0 ? 'text-danger' : '' }}">
        @if ($task->assign_to == Auth::id() || ($task->assign_to != Auth::id() && $task->is_private == 0))
        <?php
                $text_box = "";
                if(isset($task->message))
                {   
                    $text_box = "55";
                }
                else
                {
                    $text_box = "100";   
                }
                ?>
            <div class="d-flex">
                <input type="text" style="width: <?php echo $text_box;?>%;" class="form-control quick-message-field input-sm" name="message" placeholder="Message" value="">
                <button class="btn btn-sm btn-image send-message" data-taskid="{{ $task->id }}"><img src="/images/filled-sent.png"/></button>
                @if (isset($task->message))
                <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='task' data-id="{{ $task->id }}" title="Load messages"><img src="{{asset('images/chat.png')}}" alt=""></button>
                @endif
                @if (isset($task->message))
                    <div class="d-flex justify-content-between">
                        <span class="td-mini-container">
                            {{ strlen($task->message) > 25 ? substr($task->message, 0, 25) . '...' : $task->message }}
                        </span>
                        <span class="td-full-container hidden">
                            {{ $task->message }}
                        </span>
                        
                    </div>
                @endif
            </div>
        @else
            Private
        @endif
    </td>
    <td class="p-2">
        <div class="row" style="margin:0px;">
            @if ($special_task->users->contains(Auth::id()) || $task->assign_from == Auth::id())
                @if ($task->is_completed == '')
                    <button type="button" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/incomplete.png"/></button>
                @else
                    @if ($task->assign_from == Auth::id())
                        <button type="button" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/completed-green.png"/></button>
                    @else
                        <button type="button" class="btn btn-image pd-5"><img src="/images/completed-green.png"/></button>
                    @endif
                @endif
            @endif

            @if ((!$special_task->users->contains(Auth::id()) && $special_task->contacts()->count() == 0))
                @if ($task->is_private == 1)
                    <button disabled type="button" class="btn btn-image pd-5"><img src="/images/private.png"/></button>
                @else
                    {{-- <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="/images/view.png" /></a> --}}
                @endif
            @endif

            @if ($special_task->users->contains(Auth::id()) || ($task->assign_from == Auth::id() && $task->is_private == 0) || ($task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
                <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="/images/view.png"/></a>
            @endif

            @if ($special_task->users->contains(Auth::id()) || (!$special_task->users->contains(Auth::id()) && $task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
                @if ($task->is_private == 1)
                    <button type="button" class="btn btn-image make-private-task pd-5" data-taskid="{{ $task->id }}"><img src="/images/private.png"/></button>
                @else
                    <button type="button" class="btn btn-image make-private-task pd-5" data-taskid="{{ $task->id }}"><img src="/images/not-private.png"/></button>
                @endif
            @endif
            <button type="button" onClick="return confirm('Are you sure you want to delete this task ?');" data-id="<?php echo $task->id; ?>" class="btn btn-image delete-task-btn pd-5"><img src="/images/delete.png"/></button>
        </div>
    </td>
</tr>