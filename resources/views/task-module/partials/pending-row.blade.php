<tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }} {{ !$task->due_date ? 'no-due-date' : '' }} {{ $task->is_statutory == 3 ? 'row-highlight' : '' }}" id="task_{{ $task->id }}">
    <td class="p-2">
        @if(auth()->user()->isAdmin())
            <input type="checkbox" name="selected_issue[]" value="{{$task->id}}" title="Task is in priority" {{in_array($task->id, $priority) ? 'checked' : ''}}>
        @endif
        <input type="checkbox" title="Select task" class="select_task_checkbox" name="task" data-id="{{ $task->id }}" value="">
        {{ $task->id }}
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
    <td class="expand-row" data-subject="{{$task->task_subject ? $task->task_subject : 'Task Details'}}" data-details="{{$task->task_details}}" data-switch="0" style="word-break: break-all;">
        <span class="td-mini-container">
          {{ $task->task_subject ? substr($task->task_subject, 0, 15) . (strlen($task->task_subject) > 15 ? '...' : '') : 'Task Details' }}
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
    <td class="table-hover-cell p-2">
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
          {{ strlen($users_list) > 15 ? substr($users_list, 0, 15) : $users_list }}
        </span>

        <span class="td-full-container hidden">
          {{ $users_list }}
        </span>

        <div class="col-md-12 expand-col dis-none" style="padding:0px;">
            <br>
            <label for="" style="font-size: 12px;margin-top:10px;">Lead :</label>
            <select id="master_user_id" class="form-control assign-master-user select2" data-id="{{$task->id}}" name="master_user_id" id="user_{{$task->id}}">
                <option value="">Select...</option>
                <?php $masterUser = isset($task->master_user_id) ? $task->master_user_id : 0; ?>
                @foreach($users as $id=>$name)
                    @if( $masterUser == $id )
                        <option value="{{$id}}" selected>{{ $name }}</option>
                    @else
                        <option value="{{$id}}">{{ $name }}</option>
                    @endif
                @endforeach
            </select>

            <label for="" style="font-size: 12px;margin-top:10px;">Due date :</label>
            <div class="d-flex">
                <div class="form-group" style="padding-top:5px;">
                    <div class='input-group date due-datetime'>

                        <input type="text" class="form-control input-sm due_date_cls" name="due_date" value="{{$task->due_date}}"/>

                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>

                    </div>
                </div>
                <button class="btn btn-sm btn-image set-due-date" title="Set due date" data-taskid="{{ $task->id }}"><img style="padding: 0;margin-top: -14px;" src="{{asset('images/filled-sent.png')}}"/></button>
            </div>

            @if($task->is_milestone)
                <p style="margin-bottom:0px;">Total : {{$task->no_of_milestone}}</p>
                @if($task->no_of_milestone == $task->milestone_completed)
                    <p style="margin-bottom:0px;">Done : {{$task->milestone_completed}}</p>
                @else
                    <input type="number" name="milestone_completed" id="milestone_completed_{{$task->id}}" placeholder="Completed..." class="form-control save-milestone" value="{{$task->milestone_completed}}" data-id="{{$task->id}}">
                @endif
            @else
                <p>No milestone</p>
            @endif
        </div>
    </td>
    <td>

                                        

                                       
    <select id="master_user_id" class="form-control change-task-status select2" data-id="{{$task->id}}" name="master_user_id" id="user_{{$task->id}}">
        <option value="">Select...</option>
        <?php $masterUser = isset($task->master_user_id) ? $task->master_user_id : 0; ?>
        @if(!empty($task_statuses))
            @foreach($task_statuses as $index => $status)
                @if( $status->id == $task->status )
                    <option value="{{$status->id}}" selected>{{ $status->name }}</option>
                @else
                    <option value="{{$status->id}}">{{ $status->name }}</option>
                @endif
            @endforeach
        @endif
    </select>
                                           
                                               
                                         

                                    </td>
    <td>
        <div class="d-flex">
            <input  type="text" placeholder="ED" class="update_approximate form-control input-sm" name="approximate" data-id="{{$task->id}}" value="{{$task->approximate}}">
            <button type="button" class="btn btn-xs show-time-history" title="Show History" data-id="{{$task->id}}"><i class="fa fa-info-circle"></i></button>
            <span class="text-success update_approximate_msg" style="display: none;">Successfully updated</span>
            <input type="text" placeholder="Cost" class="update_cost form-control input-sm" name="cost" data-id="{{$task->id}}" value="{{$task->cost}}">
            <span class="text-success update_cost_msg" style="display: none;">Successfully updated</span>
        </div>
        @if (isset($special_task->timeSpent) && $special_task->timeSpent->task_id > 0)
            {{ formatDuration($special_task->timeSpent->tracked) }}

            <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-tracked-history" title="Show tracked time History" data-id="{{$task->id}}" data-type="developer"><i class="fa fa-info-circle"></i></button>
        @endif
    </td>
    <td class="table-hover-cell p-2 {{ ($task->message && $task->message_status == 0) || $task->message_is_reminder == 1 || ($task->message_user_id == $task->assign_from && $task->assign_from != Auth::id()) ? 'text-danger' : '' }}">
        @if ($task->assign_to == Auth::id() || ($task->assign_to != Auth::id() && $task->is_private == 0))
            <div class="d-flex">
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
                <input type="text" style="width: <?php echo $text_box;?>%;" class="form-control quick-message-field input-sm" id="getMsg{{$task->id}}" name="message" placeholder="Message" value="">
                <button class="btn btn-sm btn-image send-message" title="Send message" data-taskid="{{ $task->id }}"><img src="{{asset('images/filled-sent.png')}}"/></button>
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
        <div>
            <div class="row cls_action_box" style="margin:0px;">
                @if(auth()->user()->isAdmin())
                    <button type="button" class='btn btn-image whatsapp-group pd-5' data-id="{{ $task->id }}" data-toggle='modal' data-target='#whatsAppMessageModal'><img src="{{asset('images/whatsapp.png')}}" /></button>
                @endif  

                <button data-toggle="modal" data-target="#taskReminderModal"  
                    class='btn pd-5 task-set-reminder' 
                    data-id="{{ $task->id }}"
                    data-frequency="{{ !empty($task->reminder_message) ? $task->frequency : '60' }}"
                    data-reminder_message="{{ !empty($task->reminder_message) ? $task->reminder_message : 'Plz update' }}"
                    data-reminder_from="{{ $task->reminder_from }}"
                    data-reminder_last_reply="{{ ($task && !empty($task->reminder_last_reply)) ? $task->reminder_last_reply : '' }}"
                >
                    <i class="fa fa-bell @if(!empty($task->reminder_message) && $task->frequency > 0) {{ 'green-notification'  }} @else {{ 'red-notification' }} @endif" aria-hidden="true"></i>
                </button>

                @if ($special_task->users->contains(Auth::id()) || $task->assign_from == Auth::id() || $task->master_user_id == Auth::id())
                    <button type="button" title="Complete the task by user" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/incomplete.png"/></button>
                    @if ($task->assign_from == Auth::id())
                        <button type="button" title="Verify the task by admin" class="btn btn-image task-complete pd-5" data-id="{{ $task->id }}"><img src="/images/completed-green.png"/></button>
                    @else
                        <button type="button" class="btn btn-image pd-5"><img src="/images/completed-green.png"/></button>
                    @endif

                    <button type="button" class='btn btn-image ml-1 reminder-message pd-5' data-id="{{ $task->message_id }}" data-toggle='modal' data-target='#reminderMessageModal'><img src='/images/reminder.png'/></button>

                    <button type="button"  data-id="{{ $task->id }}" class="btn btn-file-upload pd-5">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                    </button>

                    <button type="button" class="btn preview-img-btn pd-5" data-id="{{ $task->id }}">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                @endif
                @if ((!$special_task->users->contains(Auth::id()) && $special_task->contacts()->count() == 0))
                    @if ($task->is_private == 1)
                        <button disabled type="button" class="btn btn-image pd-5"><img src="{{asset('images/private.png')}}"/></button>
                    @else
                        {{-- <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="{{asset('images/view.png')}}" /></a> --}}
                    @endif
                @endif

                @if ($special_task->users->contains(Auth::id()) || ($task->assign_from == Auth::id() && $task->is_private == 0) || ($task->assign_from == Auth::id() && $special_task->contacts()->count() > 0) || Auth::id() == 6)
                    <a href="{{ route('task.show', $task->id) }}" class="btn btn-image pd-5" href=""><img src="{{asset('images/view.png')}}"/></a>
                @endif

                @if ($task->is_flagged == 1)
                    <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $task->id }}"><img src="{{asset('images/flagged.png')}}"/></button>
                @else
                    <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $task->id }}"><img src="{{asset('images/unflagged.png')}}"/></button>
                @endif
                <button class="btn btn-image expand-row-btn"><img src="/images/forward.png"></button>
            </div>
        </div>
    </td>
</tr>