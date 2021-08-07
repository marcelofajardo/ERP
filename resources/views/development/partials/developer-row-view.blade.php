<tr style="color:grey;">
    <td>
        <a href="{{ url("development/task-detail/$issue->id") }}">{{ $issue->id }}
            @if($issue->is_resolved==0)	
                <input type="checkbox" name="selected_issue[]" value="{{$issue->id}}" {{in_array($issue->id, $priority) ? 'checked' : ''}}>	
            @endif	
        </a>


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
            <i class="fa fa-bell" aria-hidden="true"></i>
        </a>

        <br>
        {{ \Carbon\Carbon::parse($issue->created_at)->format('H:i d-m') }}
        @if($issue->task_type_id == 1) Devtask @elseif($issue->task_type_id == 3) Issue @endif
    </td>
    <td><a href="{{ url("development/task-detail/$issue->id") }}">{{ $issue->developerModule ? $issue->developerModule->name : 'Not Specified' }}</a></td>

    <td>{{ $issue->subject }}</td>
    
    <td class="expand-row">
    <!--span style="word-break: break-all;">{{  \Illuminate\Support\Str::limit($issue->message, 150, $end='...') }}</span>
        @if ($issue->getMedia(config('constants.media_tags'))->first())
            <br>
            @foreach ($issue->getMedia(config('constants.media_tags')) as $image)
                <a href="{{ $image->getUrl() }}" target="_blank" class="d-inline-block">
                    <img src="{{ $image->getUrl() }}" class="img-responsive" style="width: 50px" alt="">
                </a>
            @endforeach
        @endif
        <div>
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse_{{$issue->id}}">Messages({{count($issue->messages)}})</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div-->
    
    <!-- class="expand-row" -->
    <span class="{{ ($issue->message && $issue->message_status == 0) || $issue->message_is_reminder == 1 || ($issue->sent_to_user_id == Auth::id() && $issue->message_status == 0) ? 'text-danger' : '' }}" style="word-break: break-all;">{{  \Illuminate\Support\Str::limit($issue->message, 150, $end='...') }}</span>
    <input type="text" class="form-control send-message-textbox" data-id="{{$issue->id}}" id="send_message_{{$issue->id}}" name="send_message_{{$issue->id}}" style="margin-bottom:5px"/>
    <?php echo Form::select("send_message_".$issue->id,[
                        "to_master" => "Send To Master Developer",
                        "to_developer" => "Send To Developer",                       
                        "to_team_lead" => "Send To Team Lead",
                        "to_tester" => "Send To Tester"
                    ],null,["class" => "form-control send-message-number", "style" => "width:85% !important;display: inline;"]); ?>
    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image send-message-open" type="submit" id="submit_message"  data-id="{{$issue->id}}" ><img src="/images/filled-sent.png"/></button>

  
        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='developer_task' data-id="{{ $issue->id }}" style="margin-top: 2%;" title="Load messages"><img src="/images/chat.png" alt=""></button>
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
            @if((auth()->user()->isAdmin() || auth()->user()->id == $issue->assigned_to || auth()->user()->id == $issue->master_user_id))
                <input style="min-width: 30px;" placeholder="E.minutes" value="{{ $issue->estimate_minutes }}" type="text" class="form-control estimate-time-change" name="estimate_minutes_{{$issue->id}}" data-id="{{$issue->id}}" id="estimate_minutes_{{$issue->id}}">
            @endif
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-time-history" title="Show History" data-id="{{$issue->id}}"><i class="fa fa-info-circle"></i></button>
                @if($issue->developerTaskHistory)
                <span>Approved : {{$issue->developerTaskHistory ? $issue->developerTaskHistory->new_value:0  }}</span>
                @else 
                    <p style="color:#337ab7"><strong>Unapproved</strong> </p>
                @endif
            </div>            
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

        @if(auth()->user()->id == $issue->assigned_to)
        <?php 
            $developerTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->assigned_to)->where('approve',1)->sum('time');
        ?>
        Others : {{$developerTime}}
        @elseif(auth()->user()->id == $issue->master_user_id)
        <?php 
            $leadTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->master_user_id)->where('approve',1)->sum('time');
        ?>
        Others : {{$leadTime}}
        @elseif(auth()->user()->id == $issue->tester_id) 
        <?php 
            $testerTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->tester_id)->where('approve',1)->sum('time');
        ?>
        Others : {{$testerTime}}
        @endif
    </td>
    <td data-id="{{ $issue->id }}">
        <div class="form-group">
            <div class='input-group estimate_dates'>
            @if((auth()->user()->isAdmin() || auth()->user()->id == $issue->assigned_to || auth()->user()->id == $issue->master_user_id))
                <input style="min-width: 30px;" placeholder="E.Date" value="{{ $issue->estimate_date }}" type="text" class="form-control estimate-date estimate-date-update" name="estimate_date_{{$issue->id}}" data-id="{{$issue->id}}" id="estimate_date_{{$issue->id}}">
            @endif
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-date-history" title="Show Date History" data-id="{{$issue->id}}"><i class="fa fa-info-circle"></i></button>
               <span>Approved : {{$issue->developerTaskHistory ? $issue->developerTaskHistory->new_value :'--'  }}</span>
            </div>
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

        @if(auth()->user()->id == $issue->assigned_to)
        <?php 
            $developerTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->assigned_to)->where('approve',1)->sum('time');
        ?>
        Others : {{$developerTime}}
        @elseif(auth()->user()->id == $issue->master_user_id)
        <?php 
            $leadTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->master_user_id)->where('approve',1)->sum('time');
        ?>
        Others : {{$leadTime}}
        @elseif(auth()->user()->id == $issue->tester_id) 
        <?php 
            $testerTime = \App\MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$issue->id)->where('user_id',$issue->tester_id)->where('approve',1)->sum('time');
        ?>
        Others : {{$testerTime}}
        @endif
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
    <td>
    <label for="" style="font-size: 12px;">Assigned To :</label>
        @if(isset($userID) && $issue->team_lead_id == $userID)
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
        @else
            @if($issue->assignedUser)
                <p>{{ $issue->assignedUser->name }}</p>
            @else
                <p>Unassigned</p>
            @endif
        @endif
        <label for="" style="font-size: 12px;">Lead :</label>
        @if($issue->masterUser)
            <p>{{ $issue->masterUser->name  }}</p>
        @else
            <p>N/A</p>
        @endif

        @if($issue->teamLead)
        <label for="" style="font-size: 12px;">Team Lead :</label>
            <p>{{ $issue->teamLead->name  }}</p>
        @endif

        @if($issue->tester)
        <label for="" style="font-size: 12px;">Tester :</label>
            <p>{{ $issue->tester->name  }}</p>
        @endif
    </td>
    <td>
        @if($issue->is_resolved)
            <strong>Done</strong>
        @else
            <select name="task_status" id="task_status" class="form-control" onchange="resolveIssue(this,{{$issue->id}})">
                @foreach($statusList  as $status)
                <option value="{{$status}}" {{ (!empty($issue->status) && $issue->status ==  $status ? 'selected' : '') }}>{{$status}}</option>
                @endforeach
            </select>
        @endif
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
        <p style="margin-bottom:0px;">Completed : {{$issue->milestone_completed}}</p>
    @else
    No 
    @endif

    </td>

    <td>
        <?php echo $issue->language; ?>

    </td>
    </tr>
    <tr>
        <td colspan="14">
            <div id="collapse_{{$issue->id}}" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="messageList" id="message_list_{{$issue->id}}">
                        @foreach($issue->messages as $message)
                            <p>
                                <strong>
                                    <?php echo !empty($message->taskUser) ? "To : ".$message->taskUser->name : ""; ?>
                                    <?php echo !empty($message->user) ? "From : ".$message->user->name : ""; ?>
                                    At {{ date('d-M-Y H:i:s', strtotime($message->created_at)) }}
                                </strong>
                            </p>
                            {!! nl2br($message->message) !!}
                            <hr/>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer">
                    <textarea class="form-control send-message-textbox" data-id="{{$issue->id}}" id="send_message_{{$issue->id}}" name="send_message_{{$issue->id}}"></textarea>
                    <button type="submit" id="submit_message" class="btn btn-secondary ml-3 send-message" data-id="{{$issue->id}}" style="float: right;margin-top: 2%;">Submit</button>
                </div>
            </div>
        </td>
    </tr>