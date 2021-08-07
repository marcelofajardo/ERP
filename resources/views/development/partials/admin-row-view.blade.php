

<tr style="color:grey;">
    <td  >

    <a href="{{ url("development/task-detail/$issue->id") }}">{{ $issue->id }}
            @if($issue->is_resolved==0)	
                <input type="checkbox" name="selected_issue[]" value="{{$issue->id}}" {{in_array($issue->id, $priority) ? 'checked' : ''}}>	
            @endif	
        </a>
        <input type="checkbox" title="Select task" class="select_task_checkbox" name="task" data-id="{{ $issue->id }}" value="">	


        
        <!-- <a href="{{ url("development/task-detail/{$issue->id}") }}">{{ $issue->id }}
        </a> -->
        <a href="javascript:;" data-id="{{ $issue->id }}" class="upload-document-btn"><img width="15px" src="/images/attach.png" alt="" style="cursor: default;"><a>
        <a href="javascript:;" data-id="{{ $issue->id }}" class="list-document-btn"><img width="15px" src="/images/archive.png" alt="" style="cursor: default;"><a>
            
        <a href="javascript:;" data-toggle="modal" data-target="#developmentReminderModal"  
            class='pd-5 development-set-reminder' 
            data-id="{{ $issue->id }}"
            data-frequency="{{ !empty($issue->reminder_message) ? $issue->frequency : '60' }}"
            data-reminder_message="{{ !empty($issue->reminder_message) ? $issue->reminder_message : 'Plz update' }}"
            data-reminder_from="{{ $issue->reminder_from }}"
            data-reminder_last_reply="{{ $issue->reminder_last_reply }}"
        >
            <i class="fa fa-bell @if(!empty($issue->reminder_message) && $issue->frequency > 0) {{ 'green-notification'  }} @else {{ 'red-notification' }} @endif"  aria-hidden="true"></i>
        </a>     

        <br>
        {{ \Carbon\Carbon::parse($issue->created_at)->format('H:i d-m') }}
        @if($issue->task_type_id == 1) Devtask @elseif($issue->task_type_id == 3) Issue @endif
    </td>
    <td style="vertical-align: middle;">    
        <select name="module" class="form-control task-module" data-id="{{$issue->id}}">
            <option value=''>Select Module..</option>
            @foreach($modules as $module)

             @if( isset($issue->module_id) && (int) $issue->module_id == $module->id )
                <option value="{{$module->id}}" selected>{{$module->name}}</option>
                @else
                <option value="{{$module->id}}">{{$module->name}}</option>
                @endif
            @endforeach
        </select>
    </td>
    <td style="vertical-align: middle;word-break: break-all;"><p>{{ $issue->subject ?? 'N/A' }}</p> </td>
    <td class="expand-row">
    <!-- class="expand-row" -->
    <span class="{{ ($issue->message && $issue->message_status == 0) || $issue->message_is_reminder == 1 || ($issue->sent_to_user_id == Auth::id() && $issue->message_status == 0) ? 'text-danger' : '' }}" style="word-break: break-all;">{{  \Illuminate\Support\Str::limit($issue->message, 150, $end='...') }}</span>
    <input type="text" class="form-control send-message-textbox addToAutoComplete" data-id="{{$issue->id}}" id="send_message_{{$issue->id}}" name="send_message_{{$issue->id}}" style="margin-bottom:5px"/>
    <input class="" name="add_to_autocomplete" class="add_to_autocomplete" type="checkbox" value="true">
    <?php echo Form::select("send_message_".$issue->id,[
                        "to_developer" => "Send To Developer",
                        "to_master" => "Send To Master Developer",
                        "to_team_lead" => "Send To Team Lead",
                        "to_tester" => "Send To Tester"
                    ],null,["class" => "form-control send-message-number", "style" => "width:30% !important;display: inline;"]); 
    ?>
      
    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image send-message-open" type="submit" id="submit_message"  data-id="{{$issue->id}}" ><img src="/images/filled-sent.png"/></button>

        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='developer_task' data-id="{{ $issue->id }}" style="margin-top:-0%;margin-left: -3%;" title="Load messages"><img src="/images/chat.png" alt=""></button>
    <br>
        <div class="td-full-container hidden">
            <button class="btn btn-secondary btn-xs" onclick="sendImage({{ $issue->id }} )">Send Attachment</button>
            <button class="btn btn-secondary btn-xs" onclick="sendUploadImage({{$issue->id}} )">Send Images</button>
            <input id="file-input{{ $issue->id }}" type="file" name="files" style="display: none;" multiple/>
         </div>
    </td>
    <td data-id="{{ $issue->id }}">
        <div class="form-group">
            <div class='input-group estimate_minutes'>
                <input style="min-width: 30px;" placeholder="E.minutes" value="{{ $issue->estimate_minutes }}" type="text" class="form-control estimate-time-change" name="estimate_minutes_{{$issue->id}}" data-id="{{$issue->id}}" id="estimate_minutes_{{$issue->id}}">
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-time-history" title="Show History" data-id="{{$issue->id}}" data-userId="{{$issue->user_id}}"><i class="fa fa-info-circle"></i></button>
            </div>
            <!-- <button class="btn btn-secondary btn-xs estimate-time-change" data-id="{{$issue->id}}">Save</button> -->
                @if($issue->ApprovedDeveloperTaskHistory)
                    <span>Approved : {{$issue->ApprovedDeveloperTaskHistory ? $issue->ApprovedDeveloperTaskHistory->new_value:0  }}</span>
                    @else 
                    <p style="color:#337ab7"><strong>Unapproved</strong> </p>
                @endif
        </div>

     

        @if(auth()->user()->id == $issue->assigned_to)
        <button type="button" class="btn btn-xs meeting-timing-popup" title="Add Meeting timings" data-id="{{$issue->id}}" data-type="developer">Meeting time</button>
        @elseif(auth()->user()->id == $issue->master_user_id)
        <button type="button" class="btn btn-xs meeting-timing-popup" title="Add Meeting timings" data-id="{{$issue->id}}" data-type="lead">Meeting time</button>
        @elseif(auth()->user()->id == $issue->tester_id) 
        <button type="button" class="btn btn-xs meeting-timing-popup" title="Add Meeting timings" data-id="{{$issue->id}}" data-type="tester">Meeting time</button>
        @elseif(auth()->user()->isAdmin())
        <button type="button" class="btn btn-xs meeting-timing-popup" title="Add Meeting timings" data-id="{{$issue->id}}" data-type="admin">Meeting time</button>
        @endif

        <div class="form-group mt-2">
            <span>Lead dev : </span>
            <div class='input-group estimate_minutes'>
                <input style="min-width: 30px;" placeholder="E.minutes" value="{{ $issue->lead_estimate_time }}" type="text" class="form-control lead-estimate-time-change" name="lead_estimate_minutes_{{$issue->id}}" data-id="{{$issue->id}}" id="lead_estimate_minutes_{{$issue->id}}">
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-lead-time-history" title="Show History" data-id="{{$issue->id}}"><i class="fa fa-info-circle"></i></button>
            </div>
        </div>
    </td>
    <td data-id="{{ $issue->id }}">
        <div class="form-group">
            <div class='input-group estimate_dates'>
                <input style="min-width: 30px;" placeholder="E.Date" value="{{ $issue->estimate_date }}" type="text" class="form-control estimate-date estimate-date-update" name="estimate_date_{{$issue->id}}" data-id="{{$issue->id}}" id="estimate_date_{{$issue->id}}">
           
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-date-history" title="Show Date History" data-id="{{$issue->id}}"><i class="fa fa-info-circle"></i></button>
         
                  <span>Approved : {{$issue->developerTaskHistory ? $issue->developerTaskHistory->new_value :'--'  }}</span>

            </div>
        </div>
    </td>
    <td>
        @if (isset($issue->timeSpent) && $issue->timeSpent->task_id > 0)
        Developer : {{ formatDuration($issue->timeSpent->tracked) }}
        
        <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-tracked-history" title="Show tracked time History" data-id="{{$issue->id}}" data-type="developer"><i class="fa fa-info-circle"></i></button>
        @endif

        @if (isset($issue->leadtimeSpent) && $issue->leadtimeSpent->task_id > 0)
        Lead : {{ formatDuration($issue->leadtimeSpent->tracked) }}
        
        <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-tracked-history" title="Show tracked time History" data-id="{{$issue->id}}" data-type="lead"><i class="fa fa-info-circle"></i></button>
        @endif

        @if (isset($issue->testertimeSpent) && $issue->testertimeSpent->task_id > 0)
        Tester : {{ formatDuration($issue->testertimeSpent->tracked) }}
        
        <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-tracked-history" title="Show tracked time History" data-id="{{$issue->id}}" data-type="tester"><i class="fa fa-info-circle"></i></button>
        @endif


        @if(!$issue->hubstaff_task_id && (auth()->user()->isAdmin() || auth()->user()->id == $issue->assigned_to)) 
        <button type="button" class="btn btn-xs create-hubstaff-task" title="Create Hubstaff task for User" data-id="{{$issue->id}}" data-type="developer">Create D Task</button>
        @endif
        @if(!$issue->lead_hubstaff_task_id && $issue->master_user_id && (auth()->user()->isAdmin() || auth()->user()->id == $issue->master_user_id)) 
        <button style="margin-top:10px;" type="button" class="btn btn-xs create-hubstaff-task" title="Create Hubstaff task for Master user" data-id="{{$issue->id}}" data-type="lead">Create L Task</button>
        @endif

        @if(!$issue->tester_hubstaff_task_id && $issue->tester_id && (auth()->user()->isAdmin() || auth()->user()->id == $issue->tester_id)) 
        <button style="margin-top:10px;" type="button" class="btn btn-xs create-hubstaff-task" title="Create Hubstaff task for Tester" data-id="{{$issue->id}}" data-type="tester">Create T Task</button>
        @endif
    </td>
    {{--<td>{{ $issue->submitter ? $issue->submitter->name : 'N/A' }} </td>--}}
    <td>
      <div>
      <select class="form-control assign-user select2" data-id="{{$issue->id}}" name="assigned_to" id="user_{{$issue->id}}">
            <option value="">Select...</option>
            <?php $assignedId = isset($issue->assignedUser->id) ? $issue->assignedUser->id : 0; ?>
            @foreach($users as $id => $name)
                @if( $assignedId == $id )
                    <option value="{{$id}}" selected>{{ $name }}</option>
                @else
                    <option value="{{$id}}">{{ $name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="mr-t-5">
        <select class="form-control assign-master-user select2" data-id="{{$issue->id}}" name="master_user_id" id="user_{{$issue->id}}">
            <option value="">Select...</option>
            <?php $masterUser = isset($issue->masterUser->id) ? $issue->masterUser->id : 0; ?>
            @foreach($users as $id=>$name)
                @if( $masterUser == $id )
                    <option value="{{$id}}" selected>{{ $name }}</option>
                @else
                    <option value="{{$id}}">{{ $name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="mr-t-5">
        <select class="form-control assign-team-lead select2" data-id="{{$issue->id}}" name="team_lead_id" id="user_{{$issue->id}}">
            <option value="">Select...</option>
            @foreach($users as $id=>$name)
                    <option value="{{$id}}" {{$issue->team_lead_id == $id ? 'selected' : ''}}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mr-t-5">
        <select class="form-control assign-tester select2" data-id="{{$issue->id}}" name="tester_id" id="user_{{$issue->id}}">
            <option value="">Select...</option>
            @foreach($users as $id=>$name)
            <option value="{{$id}}" {{$issue->tester_id == $id ? 'selected' : ''}}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-user-history" title="Show History" data-id="{{$issue->id}}"><i class="fa fa-info-circle"></i></button>
    </td>
    <td>
        <div>
            @if($issue->is_resolved)
                <strong>Done</strong>
            @else
                <?php echo Form::select("task_status",$statusList,$issue->status,["class" => "form-control resolve-issue","onchange" => "resolveIssue(this,".$issue->id.")"]); ?>
            @endif
            <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-status-history" title="Show Status History" data-id="{{$issue->id}}">
                <i class="fa fa-info-circle"></i>
            </button>
        </div>
    </td>
    <td>
        @if($issue->cost > 0)
            {{ $issue->cost }}
        @else
            <input type="text" name="cost" id="cost_{{$issue->id}}" placeholder="Amount..." class="form-control save-cost" data-id="{{$issue->id}}">
        @endif
    </td>
    <td>
    @if($issue->is_milestone)
        <p style="margin-bottom:0px;">Milestone : @if($issue->is_milestone) Yes @else No @endif</p>
        <p style="margin-bottom:0px;">Total : {{$issue->no_of_milestone}}</p>
        @if($issue->no_of_milestone == $issue->milestone_completed) 
        <p style="margin-bottom:0px;">Done : {{$issue->milestone_completed}}</p>
        @else
        <input type="number" name="milestone_completed" id="milestone_completed_{{$issue->id}}" placeholder="Completed..." class="form-control save-milestone" value="{{$issue->milestone_completed}}" data-id="{{$issue->id}}">
        @endif
    @else
    No 
    @endif
    </td>
</tr>