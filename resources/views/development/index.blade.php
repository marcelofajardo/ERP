@extends('layouts.app')

@section('favicon' , 'development.png')

@section('title', 'Development')


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Developer Tasks</h2>

            @if(auth()->user()->checkPermission('development-list'))
                <div class="pull-left">
                    <form class="form-inline" action="{{ route('development.index') }}" method="GET">
                        <div class="form-group">
                            <select class="form-control" name="user">
                                @foreach ($users as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $user ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ml-3">
                            <input type="text" value="" name="range_start" hidden/>
                            <input type="text" value="" name="range_end" hidden/>
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>

                        <div class="form-group ml-3">
                            <input value="" type="text" name="id" id="id" placeholder="Id, subject..." class="form-control">
                        </div>

                        <div class="form-group ml-3">

                            <select class="form-control" name="task_type">
                                <option value="">Please select Type</option>
                                @foreach ($tasksTypes as $id => $taskType)
                                    <option value="{{ $taskType->id }}" {{ app('request')->input('task_type') == $taskType->id ? 'selected' : '' }}>{{ $taskType->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <button type="submit" class="btn btn-secondary ml-3">Submit</button>

                    </form>
                </div>
            @endcan
            <a  href="javascript:;"  class="btn btn-secondary priority_model_btn" style="margin-left: 5px;">Priority</a>
            <a href="javascript:" class="btn btn-default"  id="newTaskModalBtn" data-toggle="modal" data-target="#newTaskModal" style="float: right; padding: 7px;">Add New Task </a>
        </div>
    </div>

    @include('development.partials.modal-quick-task')
    @include('development.partials.modal-remark')
    @include('partials.flash_messages')


    <div id="moveToProgressModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Move To Progress</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Please enter the estimated completion time so that we can alert you when the time is about to end or is expired.
                    </p>
                    <div class="form-group">
                        <input type="date" name="progress_date" id="progress_date" placeholder="Enter Date..." class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="progress_hour">Hour</label>
                            <select class="form-control" name="progress_hour" id="progress_hour">
                                <option value="13">1 PM</option>
                                <option value="14">2 PM</option>
                                <option value="15">3 PM</option>
                                <option value="16">4 PM</option>
                                <option value="17">5 PM</option>
                                <option value="18">6 PM</option>
                                <option value="19">7 PM</option>
                                <option value="20">8 PM</option>
                                <option value="21">9 PM</option>
                                <option value="22">10 PM</option>
                                <option value="23">11 PM</option>
                                <option value="00">12 AM</option>
                                <option value="01">1 AM</option>
                                <option value="02">2 AM</option>
                                <option value="03">3 AM</option>
                                <option value="04">4 AM</option>
                                <option value="05">5 AM</option>
                                <option value="06">6 AM</option>
                                <option value="07">7 AM</option>
                                <option value="08">8 AM</option>
                                <option value="09">9 AM</option>
                                <option value="10">10 AM</option>
                                <option value="11">11 AM</option>
                                <option value="12">12 PM</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="progress_minute">Minutes</label>
                            <select class="form-control" name="progress_munite" id="progress_minute">
                                <option value="00">00</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="59">59</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary btn-sm move-to-progress">Move To Progress</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-2">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#pending">Planned Tasks</a></li>
                <li><a data-toggle="tab" href="#progress">Task On Progress</a></li>
                <li><a data-toggle="tab" href="#done">Completed Tasks</a></li>
                <li><a data-toggle="tab" href="#modules">Modules</a></li>
            </ul>

            <div class="tab-content">
                <div id="pending" class="tab-pane fade in active">
                    <div class="panel-group" style="margin-top: 10px;">
                        <div class="panel panel-default" style="display: none;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">
                                        <strong>Add New Task</strong>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
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
                                                <label for="module_id">Module:</label>
                                                <br>
                                                <select class="form-control select2" id="module_id" name="module_id">
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
                                                <label for="priority">Priority:</label>
                                                <select class="form-control" name="priority" id="priority" required>
                                                    <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
                                                    <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
                                                    <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
                                                </select>

                                                @if ($errors->has('priority'))
                                                    <div class="alert alert-danger">{{$errors->first('priority')}}</div>
                                                @endif
                                            </div>

                                                <div class="form-group">
                                                    <label for="priority">Type:</label>
                                                    <select class="form-control" name="task_type_id" id="task_type_id" required>
                                                        @foreach($tasksTypes as $taskType)
                                                            <option value="{{$taskType->id}}">{{$taskType->name}}</option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('priority'))
                                                        <div class="alert alert-danger">{{$errors->first('priority')}}</div>
                                                    @endif
                                                </div>

                                            <div class="form-group">
                                                <strong>Subject:</strong>
                                                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"/>
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
                                                <input type="number" class="form-control" name="cost" value="{{ old('cost') }}"/>
                                                </select>

                                                @if ($errors->has('cost'))
                                                    <div class="alert alert-danger">{{$errors->first('cost')}}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <strong>Status:</strong>
                                                <select class="form-control" name="status" required>
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
                    </div>

                    <table class="table table-striped table-lg">
                        <tr>
                            <th>Task ID</th>
                            <th>Assignee</th>
                            <th>Module</th>
                            <th>Subject</th>
                            <th>Task Description</th>
                            <th>Communication</th>
                            <th>Actions</th>
                        </tr>

                        @foreach($plannedTasks as $task)
                            <tr id="tr_{{$task->id}}">
                                <td>{{ $task->id }}
                                    @if(auth()->user()->isReviwerLikeAdmin() && $task->status != 'Done')
                                        <input type="checkbox" name="selected_issue[]" value="{{$task->id}}" {{in_array($task->id, isset($priority) ? $priority : []) ? 'checked' : ''}}>
                                    @endif
                                </td>
                                <td>
                                    @if(Auth::user()->isReviwerLikeAdmin())
                                        @php
                                            $userId = $task->user_id;
                                        @endphp
                                        <select data-id="{{$task->id}}" class="form-control change-assignee" name="user_{{$task->id}}" id="user_{{$task->id}}">
                                            @foreach($users as $id=>$name)
                                                <option {{ $id==$userId ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{ $task->user ? $task->user->name : 'Unassigned' }}
                                    @endif
                                </td>
                                <td>{{ $task->developerModule ? $task->developerModule->name : 'N/A' }}</td>
                                <td>{{ $task->subject ?? 'N/A' }}</td>
                                <td>
                                    <div id="task{{ $task->id }}" class="task-line" style="height: 2em; overflow: hidden;">
                                        {!! nl2br($task->task) !!}
                                    </div>
                                    <script>
                                        $('#task{{ $task->id }}').click(function () {
                                            if ($(this).hasClass('task-line')) {
                                                var reducedHeight = $(this).height();
                                                $(this).css('height', 'auto');
                                                var fullHeight = $(this).height();
                                                $(this).height(reducedHeight);
                                                $(this).animate({height: fullHeight}, 500);
                                                $(this).removeClass('task-line');
                                            } else {
                                                $(this).height('2em');
                                                $(this).addClass('task-line');
                                            }

                                        });
                                    </script>
                                    <div>
                                        @foreach($task->getMedia(config('constants.media_tags')) as $media)
                                            <a href="{{ $media->getUrl() }}" target="_new">
                                                <img style="width: 25px;" src="{{ asset('images/download.png') }}" alt="Download">
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="panel-group">
                                        <div class="panel panel-default" style="width: 140px;">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse_{{$task->id}}">Messages ({{ count($task->messages) }})</a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button data-toggle="modal" data-target="#moveToProgressModal" class="btn btn-secondary btn-xs move-progress-init" data-id="{{ $task->id }}">Move To Progress</button>
                                </td>
                            </tr>
                            <tr id="tr_{{$task->id}} messages">
                                <td colspan="7">
                                    <div id="collapse_{{$task->id}}" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            @foreach($task->messages as $message)
                                                <p>
                                                    <b>{{ date('d-m-Y H:i:s', strtotime($message->created_at)) }}</b><br />
                                                    {!! nl2br($message->message) !!}
                                                </p>
                                            @endforeach
                                        </div>
                                        <div class="panel-footer">
                                            <textarea name="message" id="message_{{$task->id}}" rows="6" class="form-control send-message" data-id="{{$task->id}}" placeholder="Enter to send.."></textarea>
                                            <button type="submit" id="submit_message" class="btn btn-secondary ml-3" data-id="{{$task->id}}" style="float: right;margin-top: 2%;">Submit</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div id="progress" class="tab-pane fade">
                    <table class="table table-striped table-lg">
                        <tr>
                            <th>Task ID</th>
                            <th>Assignee</th>
                            <th>Module</th>
                            <th>Subject</th>
                            <th>Task Description</th>
                            <th>Communication</th>
                            <th>Estd Completion</th>
                            <th>Actions</th>
                        </tr>

                        @foreach($progressTasks as $task)
                            <tr id="tr_{{$task->id}}">
                                <td>{{ $task->id }}
                                    @if($task->status != 'Done')
                                        <input type="checkbox" name="selected_issue[]" value="{{$task->id}}" {{in_array($task->id, isset($priority) ? $priority : []) ? 'checked' : ''}}>
                                    @endif
                                </td>
                                <td>
                                    @if(Auth::user()->isReviwerLikeAdmin())
                                        @php
                                            $userId = $task->user_id;
                                        @endphp
                                        <select data-id="{{$task->id}}" class="form-control change-assignee" name="user_{{$task->id}}" id="user_{{$task->id}}">
                                            @foreach($users as $id=>$name)
                                                <option {{ $id==$userId ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{ $task->user ? $task->user->name : 'Unassigned' }}
                                    @endif
                                </td>
                                <td>{{ $task->developerModule ? $task->developerModule->name : 'N/A' }}</td>
                                <td>{{ $task->subject ?? 'N/A' }}</td>
                                <td>
                                    {{ $task->task }}
                                    <div>
                                        @foreach($task->getMedia(config('constants.media_tags')) as $media)
                                            <a href="{{ $media->getUrl() }}" target="_new">
                                                <img style="width: 25px;" src="{{ asset('images/download.png') }}" alt="Download">
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="panel-group">
                                        <div class="panel panel-default" style="width: 140px;">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse_{{$task->id}}">Messages ({{ count($task->messages) }})</a>
                                                </h4>
                                            </div>
                                            <div id="collapse_{{$task->id}}" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    @foreach($task->messages as $message)
                                                        <li>-{{ $message->message }}</li>
                                                    @endforeach
                                                </div>
                                                <div class="panel-footer">
                                                    <input type="text" class="form-control send-message" name="message" data-id="{{$task->id}}" placeholder="Enter to send..">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $task->estimate_minutes }}</td>
                                <td>
                                    <button class="btn btn-secondary btn-xs complete-task" data-id="{{ $task->id }}">Mark Complete</button>
                                    <button class="btn btn-secondary btn-xs relist-task" data-id="{{ $task->id }}">Re-list Task</button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div id="done" class="tab-pane fade">
                    <table class="table table-striped table-lg">
                        <tr>
                            <th>Task ID</th>
                            <td>Assignee</td>
                            <th>Module</th>
                            <th>Subject</th>
                            <th>Task Description</th>
                            <th>Communication</th>
                            <th>Actions</th>
                        </tr>

                        @foreach($completedTasks as $task)
                            <tr id="tr_{{$task->id}}">
                                <td>{{ $task->id }}</td>
                                <td>
                                    @if(Auth::user()->isReviwerLikeAdmin())
                                        @php
                                            $userId = $task->user_id;
                                        @endphp
                                        <select data-id="{{$task->id}}" class="form-control change-assignee" name="user_{{$task->id}}" id="user_{{$task->id}}">
                                            @foreach($users as $id=>$name)
                                                <option {{ $id==$userId ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{ $task->user ? $task->user->name : 'Unassigned' }}
                                    @endif
                                </td>
                                <td>{{ $task->developerModule ? $task->developerModule->name : 'N/A' }}</td>
                                <td>{{ $task->subject ?? 'N/A' }}</td>
                                <td>
                                    {{ $task->task }}
                                    <div>
                                        @foreach($task->getMedia(config('constants.media_tags')) as $media)
                                            <a href="{{ $media->getUrl() }}" target="_new">
                                                <img style="width: 25px;" src="{{ asset('images/download.png') }}" alt="Download">
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="panel-group">
                                        <div class="panel panel-default" style="width: 140px;">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse_{{$task->id}}">Messages ({{ count($task->messages) }})</a>
                                                </h4>
                                            </div>
                                            <div id="collapse_{{$task->id}}" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    @foreach($task->messages as $message)
                                                        <li>-{{ $message->message }}</li>
                                                    @endforeach
                                                </div>
                                                <div class="panel-footer">
                                                    <input type="text" class="form-control send-message" name="message" data-id="{{$task->id}}" placeholder="Enter to send..">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-xs relist-task" data-id="{{ $task->id }}">Re-list Task</button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div id="modules" class="tab-pane fade">
                    <h3>Modules</h3>
                    <form class="form-inline" action="{{ route('development.module.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Module" value="{{ old('name') }}" required>

                            @if ($errors->has('name'))
                                <div class="alert alert-danger">{{$errors->first('name')}}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-secondary ml-3">Add Module</button>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <tr>
                                <th>Module</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($modules as $key => $module)
                                <tr>
                                    <td>{{ $module->name }}</td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE','route' => ['development.module.destroy', $module->id],'style'=>'display:inline']) !!}
                                        <button type="submit" class="btn btn-image"><img src="/images/archive.png"/></button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="priority_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Priority</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="priorityForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <strong>User:</strong>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    @if(auth()->user()->isReviwerLikeAdmin())
                                        <select class="form-control" name="user_id" id="priority_user_id">
                                            @foreach ($users as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{auth()->user()->name}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <strong>Remarks:</strong>
                            </div>
                            <div class="col-md-8">
                                @if(auth()->user()->isReviwerLikeAdmin())
                                     <div class="form-group">
                                        <textarea cols="45" class="form-control" name="global_remarkes"></textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="1%">ID</th>
                                    <th width="5%">Module</th>
                                    <th width="15%">Subject</th>
                                    <th width="67%">Task</th>
                                    <th width="5%">Submitted By</th>
                                    <th width="2%">Action</th>
                                </tr>
                                <tbody class="show_task_priority">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(auth()->user()->isReviwerLikeAdmin())
                        <button type="submit" class="btn btn-secondary">Confirm</button>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        var developerTaskToAlter = null;
        $(document).ready(function () {
            $('.select2').select2();
        });


        function getPriorityTaskList(id) {
            var selected_issue = [0];

            $('input[name ="selected_issue[]"]').each(function(){
                if ($(this).prop("checked") == true) {
                    selected_issue.push($(this).val());
                }
            });

            $.ajax({
                url: "{{route('development.task.list.by.user.id')}}",
                type: 'POST',
                data: {
                    user_id : id,
                    _token : "{{csrf_token()}}",
                    selected_issue : selected_issue,
                },
                success: function (response) {
                    var html = '';
                    response.forEach(function (task) {
                        html += '<tr>';
                            html += '<td><input type="hidden" name="priority[]" value="'+task.id+'">'+task.id+'</td>';
                            html += '<td>'+task.module+'</td>';
                            html += '<td>'+task.subject+'</td>';
                            html += '<td>'+task.task+'</td>';
                            html += '<td>'+task.created_by+'</td>';
                            html += '<td><a href="javascript:;" class="delete_priority" data-id="'+task.id+'">Remove<a></td>';
                         html += '</tdr>';
                    });
                    $( ".show_task_priority" ).html(html);
                    <?php if (auth()->user()->isAdmin()) { ?>
                      $( ".show_task_priority" ).sortable();
                    <?php } ?>
                },
                error: function () {
                    alert('There was error loading priority task list data');
                }
            });
        }

        $(document).on('click', '.delete_priority', function (e) {
            var id = $(this).data('id');
            $('input[value ="'+id+'"]').prop('checked', false);
            $(this).closest('tr').remove();
        });

        $('.priority_model_btn').click(function(){
            $( "#priority_user_id" ).val('0');
            $( ".show_task_priority" ).html('');
            <?php if (auth()->user()->isAdmin()) { ?>
              getPriorityTaskList($('#priority_user_id').val());
            <?php } else { ?>
              getPriorityTaskList('{{auth()->user()->id}}');
            <?php } ?>
            $('#priority_model').modal('show');
        })



        $('#priority_user_id').change(function(){
                getPriorityTaskList($(this).val())
        });

        $(document).on('submit', '#priorityForm', function (e) {
            e.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
                $.ajax({
                    url: "{{route('development.task.set.priority')}}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr['success']('Priority successfully update!!', 'success');
                    },
                    error: function () {
                        alert('There was error loading priority task list data');
                    }
                });
            <?php } ?>
        });

        $('#start_time, #end_time, #estimate_time').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $('#paid_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        var taskIdToProgress = null;

        $(document).on('click', '.move-progress-init', function () {
            taskIdToProgress = $(this).attr('data-id');
        });

        $(document).on('change', '.change-assignee', function () {
            let taskId = $(this).attr('data-id');
            let user_id = $(this).val();

            $.ajax({
                url: "{{ action('DevelopmentController@updateAssignee') }}",
                type: 'POST',
                data: {
                    task_id: taskId,
                    _token: "{{csrf_token()}}",
                    user_id: user_id
                },
                success: function () {
                    toastr['success']('Assigned user successfully!')
                }
            });

        });

        $(document).on('click', '.complete-task', function () {
            let taskId = $(this).attr('data-id');
            $.ajax({
                url: '{{ action('DevelopmentController@completeTask') }}',
                data: {
                    task_id: taskId,
                    _token: "{{csrf_token()}}"
                },
                type: 'post',
                success: function () {
                    toastr['success']('Task marked as complete!', 'success');
                    $('#tr_' + taskId).slideUp('slow');
                }
            });
        });

        $(document).on('click', '.relist-task', function () {
            let taskId = $(this).attr('data-id');
            $.ajax({
                url: '{{ action('DevelopmentController@relistTask') }}',
                data: {
                    task_id: taskId,
                    _token: "{{csrf_token()}}"
                },
                type: 'post',
                success: function () {
                    toastr['success']('Task relisted successfully!', 'success');
                    $('#tr_' + taskId).slideUp('slow');
                }
            });
        });

        $(document).on('click', '.move-to-progress', function () {
            let date = $("#progress_date").val();
            let hour = $('#progress_hour').val();
            let minutes = $('#progress_minute').val();
            let self = this;
            $.ajax({
                url: '{{ action('DevelopmentController@moveTaskToProgress') }}',
                type: 'post',
                data: {
                    _token: "{{csrf_token()}}",
                    date: date,
                    hour: hour,
                    minutes: minutes,
                    task_id: taskIdToProgress
                },
                success: function (response) {
                    toastr['success']('Task moved to progress!', 'Success!');
                    $('#tr_' + taskIdToProgress).slideUp('slow');
                    // $(self).click();
                }
            });
        });

        $(document).on('click', '.move-progress', function () {
            let taskId = $(this).attr('data-id');
            developerTaskToAlter = taskId;
        });

        $(document).on('click', '.edit-task-button', function () {
            var task = $(this).data('task');
            var url = "{{ url('development') }}/" + task.id + "/edit";

            @if(auth()->user()->checkPermission('development-list'))
            $('#user_field').val(task.user_id);
            @endif
            $('#priority_field').val(task.priority);
            $('#module_id_field option[value="' + task.module_id + '"]').attr('selected', true);
            $('#task_field').val(task.task);
            $('#task_subject').val(task.subject);
            $('#cost_field').val(task.cost);
            $('#status_field').val(task.status);
            $('#estimate_time_field').val(task.estimate_time);
            $('#estimate_minutes').val(task.estimate_minutes);
            $('#start_time_field').val(task.start_time);
            $('#end_time_field').val(task.end_time);

            $('#editTaskForm').attr('action', url);
        });

        $(document).on('click', '.assign-module-button', function () {
            var module_id = $(this).data('id');
            var url = "{{ url('development') }}/" + module_id + "/assignModule";

            $('#assignModuleForm').attr('action', url);
        });

        $(document).on('click', '.awaiting-response-button', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var comment_id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('development') }}/" + comment_id + "/awaiting/response",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    $(thiss).text('Loading...');
                }
            }).done(function () {
                var badge = $('<span class="badge badge-warning">!</span>');
                $(thiss).closest('.talk-bubble').prepend(badge);
                $(thiss).remove();
            }).fail(function (response) {
                console.log(response);
                alert('Something went wrong');
            });
        });

        let r_s = '{{ $start }}';
        let r_e = '{{ $end }}';

        let start = r_s ? moment(r_s, 'YYYY-MM-DD') : '2018-01-01';
        let end = r_e ? moment(r_e, 'YYYY-MM-DD') : moment();

        jQuery('input[name="range_start"]').val(start.format('YYYY-MM-DD'));
        jQuery('input[name="range_end"]').val(end.format('YYYY-MM-DD'));

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            maxYear: 1,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {

            jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

        });

        $('.add-task').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);
        });

        $('#addRemarkButton').on('click', function () {
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark textarea[name="remark"]').val();

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'developer'
                },
            }).done(response => {
                alert('Remark Added Success!')
                window.location.reload();
            }).fail(function (response) {
                console.log(response);
            });
        });


        $(".view-remark").click(function () {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "developer"
                },
            }).done(response => {
                var html = '';

                $.each(response, function (index, value) {
                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#viewRemarkModal").find('#remark-list').html(html);
            });
        });

        $(document).on('dblclick', '.quick-edit-price', function () {
            var id = $(this).data('id');

            $(this).find('.quick-price').addClass('hidden');
            $(this).find('.quick-edit-price-input').removeClass('hidden');
            $(this).find('.quick-edit-price-input').focus();

            $(this).find('.quick-edit-price-input').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);

                if (key == 13) {
                    e.preventDefault();
                    var cost = $(thiss).val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ url('development') }}/" + id + '/updateCost',
                        data: {
                            _token: "{{ csrf_token() }}",
                            cost: cost,
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.quick-price').text(cost);
                        $(thiss).siblings('.quick-price').removeClass('hidden');
                    }).fail(function (response) {
                        console.log(response);

                        alert('Could not update cost');
                    });
                }
            });
        });

        $(document).on('change', '.update-task-status', function () {
            var status = $(this).val();
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('development') }}/" + id + '/status',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                }
            }).done(function () {
                if (status == 'Done') {
                    $(thiss).closest('tr').remove();
                } else if (status == 'In Progress') {
                    $(thiss).closest('tr').addClass('task-border-success');
                } else {
                    $(thiss).closest('tr').removeClass('task-border-success');
                    $(thiss).siblings('.change_status_message').fadeIn(400);

                    setTimeout(function () {
                        $(thiss).siblings('.change_status_message').fadeOut(400);
                    }, 2000);
                }
            }).fail(function (response) {
                alert('Could not change the status');
                console.log(response);
            });
        });

        $(document).on('click', '.task-delete-button', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('development') }}/" + id + '/destroy',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                }
            }).done(function () {
                $(thiss).closest('tr').remove();
            }).fail(function (response) {
                alert('Could not delete the task');
                console.log(response);
            });
        });

        $(document).on('click', '.task-verify-button', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('development') }}/" + id + '/verify',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    $(thiss).text('Verifying...');
                }
            }).done(function () {
                $(thiss).closest('tr').remove();
            }).fail(function (response) {
                $(thiss).text('Verify');
                alert('Could not verify the task');
                console.log(response);
            });
        });

        $(document).on('click', '.read-more-button', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.short-task-container').toggleClass('hidden');
                $(this).find('.long-task-container').toggleClass('hidden');
            }
        });

        $(document).on('click', '.quick-task-edit-textarea, .quick-message-input', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', '.quick-edit-task', function (e) {
            e.stopPropagation();

            var id = $(this).data('id');

            $(this).siblings('.task-container').addClass('hidden');
            $(this).siblings('.quick-task-edit-textarea').removeClass('hidden');

            $(this).siblings('.quick-task-edit-textarea').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);

                if (key == 13) {
                    e.preventDefault();
                    var task = $(thiss).val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ url('development') }}/" + id + '/updateTask',
                        data: {
                            _token: "{{ csrf_token() }}",
                            task: task,
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.task-container').text(task);
                        $(thiss).siblings('.task-container').removeClass('hidden');
                        $(thiss).siblings('.quick-task-edit-textarea').addClass('hidden');

                        var short_task = task.substr(0, 100);

                        $(thiss).closest('.long-task-container').siblings('.short-task-container').text(short_task);
                    }).fail(function (response) {
                        console.log(response);

                        alert('Could not update task');
                    });
                }
            });
        });

        $(document).on('keypress', '.quick-message-input', function (e) {
            var key = e.which;
            var thiss = $(this);
            var type = $(this).data('type');

            if (type == 'task') {
                var module_type = 'task-detail';
                var container = '.task-details-container';
            } else if (type == 'task-discussion') {
                var module_type = 'task-discussion';
                var container = '.task-discussion-container';
            }
            //  else {
            //   var module_type = 'complaint-plan-comment';
            //   var container = '.plan-comments-container';
            // }

            if (key == 13) {
                e.preventDefault();
                var phone = $(thiss).val();

                var id = $(thiss).data('id');
                var remark = $(thiss).val();

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('task.addRemark') }}',
                    data: {
                        id: id,
                        remark: remark,
                        module_type: module_type,
                        user: {{ $user }},
                    },
                }).done(response => {
                    // alert('Remark Added Success!')
                    // window.location.reload();
                    var remark_message = $('<li>' + remark + ' - ' + moment().format('HH:mm DD-MM') + '</li>');
                    $(thiss).siblings(container).prepend(remark_message);
                    $(thiss).val('');
                }).fail(function (response) {
                    console.log(response);
                });
            }
        });

        $(document).on('click', '.quick-task-add-button', function () {
            var id = $(this).data('id');

            $('#quick_module_id').val(id);
        });

        $(document).on('click', '#quickTaskSubmit', function () {
            var module_id = $('#quick_module_id').val();
            var task = $('#quick_task_task').val();
            var thiss = $(this);
            var auth_id = "{{ Auth::id() }}";

            $.ajax({
                type: "POST",
                url: "{{ route('development.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    module_id: module_id,
                    user_id: {{ $user }},
                    priority: 3,
                    status: 'Discussing',
                    task: task
                },
                beforeSend: function () {
                    $(thiss).text('Adding...');
                }
            }).done(function (response) {
                $('#quick_module_id').val('');
                $('#quick_task_task').val('');
                $(thiss).text('Add');
                $('#quickDevTaskModal').find('.close').click();

                var class_name = response.task.user_id == auth_id ? 'table-hover-cell quick-edit-price' : '';
                var task_html = `<tr id="task_` + response.task.id + `">
          <td>` + moment(response.task.created_at).format('HH:mm DD-MM') + `</td>
          <td></td>
          <td></td>
          <td>
            <div class="d-flex flex-column">
              <button type="button" class="btn btn-image flag-task" data-id="` + response.task.id + `" data-priority="1"><img src="/images/unflagged.png" /></button>
              <button type="button" class="btn btn-image flag-task" data-id="` + response.task.id + `" data-priority="2"><img src="/images/unflagged.png" /></button>
              <button type="button" class="btn btn-image flag-task" data-id="` + response.task.id + `" data-priority="3"><img src="/images/flagged-green.png" /></button>
            </div>
          </td>
          <td class="read-more-button table-hover-cell">
            <span class="short-task-container">` + response.task.task.substr(0, 100) + `</span>

            <span class="long-task-container hidden">
              <span class="task-container">` + response.task.task + `</span>

              <textarea name="task" class="form-control quick-task-edit-textarea hidden" rows="8" cols="80">` + response.task.task + `</textarea>

              <button type="button" class="btn-link quick-edit-task" data-id="` + response.task.id + `">Edit</button>

              <ul class="task-details-container">

              </ul>

              <input type="text" name="message" class="form-control quick-message-input" data-type="task" placeholder="Details" value="" data-id="` + response.task.id + `">

              <h4>Discussion</h4>

              <input type="text" name="message" class="form-control quick-message-input" data-type="task-discussion" placeholder="Message" value="" data-id="` + response.task.id + `">

              <ul class="task-discussion-container">

              </ul>
            </span>
          </td>
          <td class="` + class_name + `" data-id="` + response.task.id + `">
            <span class="quick-price"></span>
            <input type="number" name="price" class="form-control quick-edit-price-input hidden" placeholder="100" value="">
          </td>
          <td>
            <div class="form-group">
              <select class="form-control update-task-status" name="status" data-id="` + response.task.id + `">
                <option value="Discussing">Discussing</option>
                <option value="Planned">Planned</option>
                <option value="In Progress">In Progress</option>
                <option value="Done">Done</option>
              </select>

              <span class="text-success change_status_message" style="display: none;">Successfully changed task status</span>
            </div>
          </td>
          <td>
            <button type="button" data-toggle="modal" data-target="#editTaskModal" data-task="` + response.task + `" class="btn btn-image edit-task-button"><img src="/images/edit.png" /></button>

            <button type="button" class="btn btn-image task-delete-button" data-id="` + response.task.id + `"><img src="/images/archive.png" /></button>
          </td>
        </tr>`;

                var module_id = response.task.module_id ? response.task.module_id : '';

                $('#module_' + module_id).after(task_html);
            }).fail(function (response) {
                $(thiss).text('Add');

                console.log(response);
                alert('Could not create a quick task');
            });
        });

        $(document).on('click', '.flag-task', function () {
            var task_id = $(this).data('id');
            var priority = $(this).data('priority');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('development') }}/" + task_id + '/updatePriority',
                data: {
                    _token: "{{ csrf_token() }}",
                    priority: priority
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
                if (response.priority == 1) {
                    // var badge = $('<span class="badge badge-secondary">Flagged</span>');
                    //
                    // $(thiss).parent().append(badge);
                    // $(thiss).html('<img src="/images/flagged.png" />');
                    var buttons = $(thiss).closest('div').find('button');

                    $(buttons[0]).html('<img src="/images/flagged.png" />');
                    $(buttons[1]).html('<img src="/images/unflagged.png" />');
                    $(buttons[2]).html('<img src="/images/unflagged.png" />');
                } else if (response.priority == 2) {
                    // $(thiss).html('<img src="/images/unflagged.png" />');
                    // $(thiss).parent().find('.badge').remove();
                    var buttons = $(thiss).closest('div').find('button');

                    $(buttons[0]).html('<img src="/images/unflagged.png" />');
                    $(buttons[1]).html('<img src="/images/flagged-yellow.png" />');
                    $(buttons[2]).html('<img src="/images/unflagged.png" />');
                } else if (response.priority == 3) {
                    var buttons = $(thiss).closest('div').find('button');

                    $(buttons[0]).html('<img src="/images/unflagged.png" />');
                    $(buttons[1]).html('<img src="/images/unflagged.png" />');
                    $(buttons[2]).html('<img src="/images/flagged-green.png" />');
                }

                // $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not change priority!');

                console.log(response);
            });
        });

        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                // if ($(this).data('switch') == 0) {
                //   $(this).text($(this).data('details'));
                //   $(this).data('switch', 1);
                // } else {
                //   $(this).text($(this).data('subject'));
                //   $(this).data('switch', 0);
                // }
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        $(document).on('change', '.change-module', function () {
            let id = $(this).attr('data-id');
            let self = this;
            let value = $(this).val();

            $.ajax({
                url: "{{action('DevelopmentController@updateValues')}}",
                data: {
                    id: id,
                    type: 'module',
                    value: value
                },
                success: function () {
                    toastr['success']('Module updated successfully!');
                },
                error: function () {
                    toastr['error']('Could not change module!');
                }
            });

        });

        $(document).on('change', '.change-value', function () {
            let id = $(this).attr('data-id');
            let type = $(this).attr('data-type');
            let value = $(this).val();

            if (type == '' || value == '' || id == '') {
                return;
            }

            let self = this;

            $.ajax({
                url: "{{action('DevelopmentController@updateValues')}}",
                data: {
                    id: id,
                    type: type,
                    value: value
                },
                type: 'GET',
                success: function () {
                    $(self).removeAttr('disabled');
                    $(self).css('transition', 'background 0.5s  linear 0s')
                    $(self).css('background', '#badab8');
                    setTimeout($(self).css('background', '#ffffff'), 0.4);
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    $(self).removeAttr('disabled');
                }
            });

        });

        $(document).on('click', '#submit_message', function (event) {
            let self = this;
            let developer_task_id = $(this).attr('data-id');
            let message = $("#message_" + developer_task_id).val();

            // if (event.which != 13) {
            //     return;
            // }

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'developer_task')}}",
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    message: message,
                    developer_task_id: developer_task_id,
                    status: 2
                },
                success: function () {
                    $(self).removeAttr('disabled');
                    $("#message_" + developer_task_id).removeAttr('disabled');
                    $(self).val('');
                    $("#message_" + developer_task_id).val('');
                    toastr['success']('Message sent successfully!', 'Message');
                },
                error: function () {
                    $(self).removeAttr('disabled');
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                    $("#message_" + developer_task_id).attr('disabled', true);
                }
            });
        });

        $(document).ready(function () {
            $('.select2').select2({
                tags: true
            });
        });


        //Popup for add new task
        $(document).on('click', '#newTaskModalBtn', function () {
            if ($("#newTaskModal").length > 0) {
                $("#newTaskModal").remove();
            }

            $.ajax({
                url: "{{ action('DevelopmentController@openNewTaskPopup') }}",
                type: 'GET',
                dataType: "JSON",
                success: function (resp) {
                    if(resp.status == 'ok') {
                        $("body").append(resp.html);
                        $('#newTaskModal').modal('show');
                        $('.select2').select2({tags :true});
                    }
                }
            });
        });

    </script>
@endsection
