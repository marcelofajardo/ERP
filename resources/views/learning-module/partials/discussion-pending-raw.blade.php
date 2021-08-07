<tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }} {{ $task->is_statutory == 3 ? 'row-highlight' : '' }}" id="task_{{ $task->id }}">
    <td class="p-2">
        {{ $task->id }}
        <br>
        @if(auth()->user()->isAdmin())
            <input type="checkbox" name="selected_issue[]" value="{{$task->id}}" title="Task is in priority" {{in_array($task->id, $priority) ? 'checked' : ''}}>
        @endif
        <input type="checkbox" title="Select task" class="select_task_checkbox" name="task" data-id="{{ $task->id }}" value="">
    </td>
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
          {{ $task->task_subject ? substr($task->task_subject, 0, 50) . (strlen($task->task_subject) > 50 ? '...' : '') : 'Task Details' }}
        </span>
        <span class="td-full-container hidden">
          <strong>{{ $task->task_subject ? $task->task_subject : 'Task Details' }}</strong>
            {{ $task->task_details }}
        </span>
    </td>
    @php
        $special_task = \App\Task::find($task->id);  
    @endphp
    <td class="expand-row table-hover-cell p-2 {{ ($task->message && $task->message_status == 0) || $task->message_is_reminder == 1 || ($task->message_user_id == $task->assign_from && $task->assign_from != Auth::id()) ? 'text-danger' : '' }}">
        @if ($task->assign_to == Auth::id() || ($task->assign_to != Auth::id() && $task->is_private == 0))
            <div class="d-flex">
                <input type="text" class="form-control quick-message-field input-sm" name="message" placeholder="Message" value="">
                <button class="btn btn-sm btn-image send-message" title="Send message" data-taskid="{{ $task->id }}"><img src="/images/filled-sent.png"/></button>
                @if (isset($task->message))
                <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='task' data-id="{{ $task->id }}" title="Load messages"><img src="/images/chat.png" alt=""></button>
                @endif
                @if ($task->message_status != 0)
                        <a href='#' class='btn btn-image p-0 resend-message' title="Resend message" data-id="{{ $task->message_id }}"><img src="/images/resend.png"/></a>
                @endif
            </div>
            @if (isset($task->message))
                <div class="d-flex justify-content-between">
                    <span class="td-mini-container">
                        {{ strlen($task->message) > 32 ? substr($task->message, 0, 32) . '...' : $task->message }}
                    </span>
                    <span class="td-full-container hidden">
                        {{ $task->message }}
                    </span>
                 
                </div>
            @endif
        @else
            Private
        @endif
    </td>
    <td class="p-2">
        <div>
        <div class="row" style="margin:0px;">
            @if(auth()->user()->isAdmin())
                    <button type="button" class='btn btn-image whatsapp-group pd-5' data-id="{{ $task->id }}" data-toggle='modal' data-target='#whatsAppMessageModal'><img src='/images/whatsapp.png'/></button>
            @endif
            @if ($special_task->users->contains(Auth::id()) || $task->assign_from == Auth::id() || $task->master_user_id == Auth::id())
                    @if ($task->is_completed == '')
                        <button type="button" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/incomplete.png"/></button>
                    @else
                        @if ($task->assign_from == Auth::id())
                            <button type="button" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/completed-green.png"/></button>
                        @else
                            <button type="button" class="btn btn-image pd-5"><img src="/images/completed-green.png"/></button>
                        @endif
                    @endif

                    <button type="button" class='btn btn-image ml-1 reminder-message pd-5' data-id="{{ $task->message_id }}" data-toggle='modal' data-target='#reminderMessageModal'><img src='/images/reminder.png'/></button>

            @endif
            @if ((!$special_task->users->contains(Auth::id()) && $special_task->contacts()->count() == 0))
                @if ($task->is_private == 1)
                    <button disabled type="button" class="btn btn-image pd-5"><img src="/images/private.png"/></button>
                @else
                    {{-- <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="/images/view.png" /></a> --}}
                @endif
            @endif
   
            @if ($special_task->users->contains(Auth::id()) || ($task->assign_from == Auth::id() && $task->is_private == 0) || ($task->assign_from == Auth::id() && $special_task->contacts()->count() > 0) || Auth::id() == 6)
                <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="/images/view.png"/></a>
            @endif

            @if ($special_task->users->contains(Auth::id()) || (!$special_task->users->contains(Auth::id()) && $task->assign_from == Auth::id() && $special_task->contacts()->count() > 0))
                @if ($task->is_private == 1)
                    <button type="button" class="btn btn-image make-private-task pd-5" data-taskid="{{ $task->id }}"><img src="/images/private.png"/></button>
                @else
                    <button type="button" class="btn btn-image make-private-task pd-5" data-taskid="{{ $task->id }}"><img src="/images/not-private.png"/></button>
                @endif
            @endif

            @if ($task->is_flagged == 1)
                <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $task->id }}"><img src="/images/flagged.png"/></button>
            @else
                <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $task->id }}"><img src="/images/unflagged.png"/></button>
            @endif
         </div>
        </div>
    </td>
</tr>