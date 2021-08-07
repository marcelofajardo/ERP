@extends('layouts.app')

@section('title', 'Inventory suppliers')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
.ajax-loader{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1060;
}
.inner_loader {
	top: 30%;
    position: absolute;
    left: 40%;
    width: 100%;
    height: 100%;
}
.pd-5 {
  padding:5px !important;
}
.pd-3 {
  padding:3px !important;
}
.status-select-cls .multiselect {
  width:100%;
}
.btn-ht {
  height:30px;
}
.status-select-cls .btn-group {
  width:100%;
  padding: 0;
}
.table.table-bordered.order-table a{
color:black!important;
}
.fa-info-circle{
    padding-left:10px;
    cursor: pointer;
}
table tr td {
  word-wrap: break-word;
}
.fa-list-ul{
    cursor: pointer;
}

.fa-upload{
    cursor: pointer;
}
.fa-refresh{
    cursor: pointer;
    color:#000;
}
.red{
    color: red
}
#addEditTaskModal .modal-dialog{
    max-width: 1050px;
    width: 100%;
}
.uk-margin-remove th, .uk-margin-remove td{
    padding: 4px 10px;
}
.btn-default:focus{
    outline: none;
    border:1px solid #ddd;
    box-shadow: none !important;
    background: #fff;
    color: #757575;
}
</style>
@endsection

@section('large_content')
    <script src="/js/jquery.jscroll.min.js"></script>


	<div class="ajax-loader" style="display: none;">
		<div class="inner_loader">
		<img src="{{ asset('/images/loading2.gif') }}">
		</div>
	</div>

    <div class="row">
        <div class="col-12" style="padding:0px;">
            <h2 class="page-heading flex" style="padding: 8px 5px 8px 10px;border-bottom: 1px solid #ddd;line-height: 32px;">Cron Tasks
                <div class="margin-tb" style="flex-grow: 1;">
                    <div class="pull-right ">
                        <button type="button" class="btn btn-default btn-sm add-remark mr-1" data-toggle="modal" data-target="#addEditTaskModal">
                            <span class="glyphicon glyphicon-th-plus"></span> Add Task
                        </button>
                    </div>
                </div>
            </h2>



        </div>
         <div class="col-12 pl-2" style="padding-left:0px;">
            <div >
                <form class="form-inline" action="" method="GET">
                    <div class="form-group col-md-2 pd-3">
                        <input style="width:100%;" id="totem__search__form" name="q" type="text" class="form-control" value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}" placeholder="Search...">
                    </div> 
                    <div class="form-group col-md-1 pd-3">
                        <button type="submit" class="btn btn-image ml-0"><img src="{{asset('images/filter.png')}}" /></button>
                        <a href="{{ route('totem.tasks.all') }}" class="fa fa-refresh" aria-hidden="true"></a>
                    </div>

                </form> 
            </div>
        </div>
    </div>	



    <div class="row">
        <div class="infinite-scroll" style="width:100%;padding: 0 8px">
            {!! $tasks->links() !!}
	        <div class="table-responsive mt-2">
                <table class="table table-bordered order-table" style="color:black;table-layout:fixed">
                    <thead>
                        <tr>
                            <th width="2%">#</th>
                            <th width="5%">Description</th> 
                            <th width="8%">Average Runtime</th>
                            <th width="5%">Last Run</th>
                            <th width="5%">Next Run</th>
                            <th width="5%">Action</th> 
                        </tr>    
                    </thead>
                    <tbody> 
                            @foreach($tasks as $key => $task)
                            <tr class="{{$task->is_active ? '' : 'red' }}">
                                <td>{{$task->id}}</td>
                                <td>
                                        {{str_limit($task->description, 30)}}
                                </td>
                                <td>
                                    {{ number_format(  $task->averageRuntime / 1000 , 2 ) }} seconds
                                </td>
                                @if($last = $task->lastResult)
                                    <td>
                                        {{$last->ran_at->toDateTimeString()}}
                                    </td>
                                @else
                                    <td>
                                        N/A
                                    </td>
                                @endif
                                <td>
                                    {{$task->upcoming}}
                                </td>
                                <td class="uk-text-center@m">
                                    <a style="padding:1px;" class="btn d-inline btn-image view-task" href="#" data-id="{{$task->id}}" title="view task" data-expression="{{$task->getCronExpression()}}"><img src="/images/view.png" style="cursor: pointer; width: 0px;"></a>
                                    <a style="padding:1px;" class="btn d-inline btn-image edit-task" href="#" data-id="{{$task->id}}" title="edit task"><img src="/images/edit.png" style="cursor: pointer; width: 0px;"></a>
                                    <a style="padding:1px;" class="btn d-inline btn-image delete-tasks" href="#" data-id="{{$task->id}}" title="delete task"><img src="/images/delete.png" style="cursor: pointer; width: 0px;"></a>
                                    <a style="padding:1px;" class="btn d-inline btn-image execute-task" href="#" data-id="{{$task->id}}" title="execute Task"><img src="/images/send.png" style="cursor: pointer; width: 0px;"></a>
                                    <a style="padding:1px;" class="btn d-inline btn-image active-task" href="#" data-id="{{$task->id}}" title="task status" data-active="{{$task->is_active}}"><img src="/images/{{ $task->is_active ? 'flagged-green' : 'flagged'}}.png"  style="cursor: pointer; width: 0px;"></a>
                                    <a style="padding:1px;" class="btn d-inline btn-image execution-history" href="#" data-id="{{$task->id}}" title="task execution history" data-results="{{json_encode($task->results()->orderByDesc('created_at')->get())}}"><img src="/images/history.png"  style="cursor: pointer; width: 0px;"></a>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
                @if(!count($tasks))
                <h5 class="text-center">No Tasks found</h5> 
                @endif
	        </div>
        </div>
    </div>


    <div class="modal fade" id="view_task_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Task details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive mt-2">
                    <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
                        <thead>
                        <tr>
                            <th width="5%">Description</th>
                            <th width="5%">Command</th>
                            <th width="5%">Parameters</th> 
                            <th width="5%">Cron Expression</th> 
                            <th width="5%">Timezone</th> 
                            <th width="5%">Created At</th> 
                            <th width="5%">Updated At</th> 
                            <th width="5%">Email Notification</th> 
                            <th width="5%">SMS Notification</th> 
                            <th width="5%">Slack Notification</th> 
                            <th width="5%">Average Run Time</th> 
                            <th width="5%">Next Run Schedule</th>  
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>  
            </form>
            <br>
            <h4 class="modal-title notes d-none">Notes</h4>

                <li class="dont_overlap d-none">
                    <span class="uk-float-left">Doesn't Overlap with another instance of this task</span><br>
                </li>
                <li class="run_in_maintenance d-none">
                    <span class="uk-float-left">Runs in maintenance mode</span><br>
                </li>
                <li class="run_on_one_server d-none">
                    <span class="uk-float-left">Runs on a single server<br>
                </li>
        
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <div id="addFrequencyModal" class="modal fade" role="dialog" style="z-index: 999999999">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Frequency</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select id="frequency" class="form-control select2" placeholder="Select a type of frequency">
                            @foreach (collect($frequencies) as $key => $frequency)
                                <option value="{{ json_encode($frequency) }}">{{$frequency['label']}}</option>
                            @endforeach
                        </select>
                    </div>   
                </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default add_freq">Add</button>
                </div>
            </div>
        </div>
    </div>  


    <div class="modal fade" id="view_execution_history" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:auto;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">EXECUTION RESULTS</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive mt-2">
                <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
                    <thead>
                    <tr>
                        <th width="5%">Executes At</th>
                        <th width="5%">Duration</th>
                        <th width="5%">Status</th>  
                    </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>
            </div>  
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <div id="addEditTaskModal" class="modal fade" role="dialog" data-id = '' style="overflow-y:auto;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form class="taskForm" action="/" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label>Description</label><i class="fa fa-info-circle" title="Provide a descriptive name for your task"></i>
                            <input class="form-control" placeholder="e.g. Daily Backups" name="description" id="description" value="" type="text">
                            <p class="d-none"></p>
                        </div>
                        <div class="form-group">
                            <label>Command</label><i class="fa fa-info-circle" title="Select an artisan command to schedule"></i>
                            <select id="command" name="command" class="form-control select2" placeholder="Click here to select one of the available commands">
                                <option value="">Select a command</option>
                                @forelse ($commands as $k => $command)
                                <optgroup label="{{$command->getName()}}">
                                    <option value="{{$command->getName()}}">
                                        {{$command->getDescription()}}
                                    </option>
                                </optgroup>
                                <p class="d-none"></p>
                                @empty
                                @endforelse
                            </select>
                        </div>  
                        <div class="form-group">
                            <label>Parameters (Optional)</label><i class="fa fa-info-circle" title="Command parameters required to run the selected command"></i>
                            <input class="form-control" placeholder="e.g. --type=all for options or name=John for arguments" name="parameters" id="parameters" value="" type="text">
                            <p class="d-none"></p>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Timezone</label><i class="fa fa-info-circle" title="Select a timezone for your task. App timezone is selected by default"></i>
                            <select id="timezone" name="timezone" class="form-control select2" placeholder="Select a timezone">
                                @foreach ($timezones as $key => $timezone)
                                    <option value="{{$timezone}}">
                                        {{$timezone}}
                                    </option>
                                <p class="d-none"></p>
                                @endforeach
                            </select>
                        </div> 
                        <div class="form-group">
                            <label>Type</label><i class="fa fa-info-circle" title="Choose whether to define a cron expression or to add frequencies"></i><br>
                            <label>
                                <input type="radio" name="type" value="expression"> Expression
                            </label>
                            <label>
                                <input type="radio" name="type" value="frequency" checked> Frequencies
                            </label>
                        </div> 
                        <div class="form-group cron_expression d-none">
                            <label>CRON EXPRESSION</label><i class="fa fa-info-circle" title="Add a cron expression for your task"></i><br>
                            <input class="form-control" placeholder="e.g * * * * * to run this task all the time" name="expression" id="expression" value="" type="text">
                         </div> 
                        <div class="form-group frequencies">
                            <label>FREQUENCIES</label><i class="fa fa-info-circle" title="Add   to your task. These frequencies will be converted into a cron expression while scheduling the task"></i><br>
                            <button type="button" class="btn btn-default btn-sm add-remark" data-toggle="modal" data-target="#addFrequencyModal">Add Frequency</button>
                            <div class="table-responsive mt-2" style="width: fit-content">
                            <table class="uk-table table-bordered uk-table-divider uk-margin-remove">
                                <thead>
                                    <tr>
                                        <th>Frequency</th> 
                                        <th colspan="2">Parameters</th>
                                    </tr>
                                </thead>
                                <tbody class="freq">
                                    <td class="default_td">No Frequencies Found</td>
                                </tbody>
                            </table>
                            </div>
                        </div> 

                        <hr> 
                        <div class="form-group">
                            <label>Email Notification (optional)</label><i class="fa fa-info-circle" title="Add an email address to receive notifications when this task gets executed. Leave empty if you do not wish to receive email notifications"></i>
                            <input id="email" class="form-control" placeholder="e.g. john.doe@name.tld" name="notification_email_address" value="" type="text">
                            <p class="d-none"></p>
                        </div>
                        <div class="form-group">
                            <label>SMS Notification (optional)</label><i class="fa fa-info-circle" title="Add a phone number to receive SMS notifications. Leave empty if you do not wish to receive sms notifications"></i>
                            <input  id="phone" placeholder="e.g. 18701234567" class="form-control" name="notification_phone_number" value="" type="text">
                            <p class="d-none"></p>
                        </div>
                        <div class="form-group">
                            <label>Slack Notification (optional)</label><i class="fa fa-info-circle" title="Add a slack web hook url to recieve slack notifications. Phone numbers should include country code and are digits only. Leave empty if you do not wish to receive slack notifications"></i>
                            <input  id="slack" placeholder="e.g. https://hooks.slack.com/TXXXXX/BXXXXX/XXXXXXXXXX" class="form-control" name="notification_slack_webhook" value="" type="text">
                            <p class="d-none"></p>
                        </div> 
                        <hr>
                        <div class="form-group">
                            <label>Miscellaneous Options</label>
                            <br>
                            <input type="hidden" name="dont_overlap" id="dont_overlap" value="0" checked>
                            <input type="checkbox" name="dont_overlap" id="dont_overlap" value="1" class="mr-2">Don't Overlap
                            <i class="fa fa-info-circle" title="Add a slack web hook url to recieve slack notifications. Phone numbers should include country code and are digits only. Leave empty if you do not wish to receive slack notifications"></i>
                            <br>
                            
                            <input type="hidden" name="run_in_maintenance" id="run_in_maintenance" value="0" checked>
                            <input type="checkbox" name="run_in_maintenance" id="run_in_maintenance" value="1" class="mr-2">Run in maintenance mode
                            <i class="fa fa-info-circle" title="Add a slack web hook url to recieve slack notifications. Phone numbers should include country code and are digits only. Leave empty if you do not wish to receive slack notifications"></i>
                            <br>
                            
                            <input type="hidden" name="run_on_one_server" id="run_on_one_server" value="0" checked>
                            <input type="checkbox" name="run_on_one_server" id="run_on_one_server" value="1" class="mr-2">Run on a single server
                            <i class="fa fa-info-circle" title="Add a slack web hook url to recieve slack notifications. Phone numbers should include country code and are digits only. Leave empty if you do not wish to receive slack notifications"></i>
                             
                            <p class="d-none"></p>
                        </div>  
                        <hr>
                        <div class="form-group">
                            <label>Cleanup Options</label><i class="fa fa-info-circle" title="Determine if an over-abundance of results will be removed after a set limit or age. Set non-zero value to enable."></i>
                            <input class="form-control" name="auto_cleanup_num" value="0" type="number"><br>
                                    <label>
                                        <input type="radio" name="auto_cleanup_type" value="days" checked> Days
                                    </label><br>
                                    <label>
                                        <input type="radio" name="auto_cleanup_type" value="results"> Results
                                    </label>
                            <p class="d-none"></p>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-default submit_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
      </div>  


      <div id="showResultModal" class="modal fade" role="dialog" style="z-index: 999999999">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Output</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5></h5>
                </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>  
@endsection
@section('scripts')

<script src="/js/jquery.jscroll.min.js"></script>
<script type="text/javascript"> 
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 2500,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').hide();
                setTimeout(function(){
                    $('ul.pagination').first().remove();
                }, 2000);
                $(".select-multiple").select2();
                initialize_select2();
            }
        });
    });

    var freq = 0;
    $('#addEditTaskModal').on('hidden.bs.modal', function (e) {
        $('.error').remove();
        $(this).attr('data-id', '');
        $('#addEditTaskModal .modal-title').html('Create task');
        $('.freq').html('<tr><td class="default_td">No Frequencies Found</td></tr>');
    });

    $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 2500,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').first().remove();
                $(".select-multiple").select2();
            }
    });

    $(document).on("click",".view-task",function(e) {
        let expression = $(this).attr('data-expression');
        $.ajax({
            type: "GET",
            url: "/totem/tasks/"+$(this).data('id'), 
            dataType : "json",
            success: function (response) {
                var html_content = '';
                html_content += '<tr class="supplier-10">';      
                html_content += '<td>' + response.task.description.substring(0, 80) + '</td>';
                html_content += '<td>' + response.task.command + '</td>';
                var parameters = response.task.parameters != null ? response.task.parameters : 'N/A';
                html_content += '<td>' + parameters + '</td>';
                html_content += '<td>' + expression + '</td>'; 
                html_content += '<td>' + response.task.timezone + '</td>';
                html_content += '<td>' + response.task.created_at + '</td>';
                html_content += '<td>' + response.task.updated_at + '</td>';
                var notification_email_address = response.task.notification_email_address == null ? 'N/A' : response.task.notification_email_address;
                html_content += '<td>' + notification_email_address + '</td>';
                var notification_phone_number = response.task.notification_phone_number == null ? 'N/A' : response.task.notification_phone_number;
                html_content += '<td>' + notification_phone_number + '</td>';
                var notification_slack_webhook = response.task.notification_slack_webhook == null ? 'N/A' : response.task.notification_slack_webhook;
                html_content += '<td>' + notification_slack_webhook + '</td>';
                html_content += '<td>' + response.results + ' seconds' + '</td>';
                html_content += '<td>' + response.task.upcoming + '</td>';
                html_content += '</tr>';

                if(response.task.dont_overlap || response.task.run_in_maintenance || response.task.run_on_one_server){
                    $("#view_task_modal .notes").removeClass('d-none');                   
                }

                if(response.task.dont_overlap){
                    $("#view_task_modal .dont_overlap").removeClass('d-none');                   
                }

                if(response.task.run_in_maintenance){
                    $("#view_task_modal .run_in_maintenance").removeClass('d-none');                   
                }

                if(response.task.run_on_one_server){
                    $("#view_task_modal .run_on_one_server").removeClass('d-none');                   
                }
                $("#view_task_modal tbody").html(html_content);                   
                $('#view_task_modal').modal('show');   
            },
            error: function () {
                toastr['error']('Something went wrong!');
            }
        });
    }); 

    $(document).on("click",".execution-history",function(e) {
        let results = JSON.parse($(this).attr('data-results'));
        var html_content = '';
        for(let i=0; i< results.length; i++){ 
            html_content += '<tr>'; 
            html_content += '<td>' + results[i].ran_at + '</td>';
            html_content += '<td>' + (results[i].duration / 1000 , 2).toFixed(2) + ' seconds</td>';
            html_content += `<td id="show-result" data-output="${results[i].result}"><i class="fa fa-info-circle"></i></td>`;
            html_content += '</tr>';
        }
        if(results.length == 0){
            html_content += '<tr class="text-center"><td colspan="3"><h5>' + 'Not executed yet.' + '</h5></td></tr>';
        }
        $("#view_execution_history tbody").html(html_content);                   
        $('#view_execution_history').modal('show'); 
    });

    $(document).on("click","#show-result",function(e) {
        let results = $(this).attr('data-output'); 
        $("#showResultModal .modal-body h5").html(results);                   
        $('#showResultModal').modal('show'); 
    });

    $(document).on("click",".execute-task",function(e) {
        thiss = $(this);
        thiss.html(`<img src="/images/loading_new.gif" style="cursor: pointer; width: 0px;">`);
        $.ajax({
            type: "GET",
            url: "/totem/tasks/"+$(this).data('id')+"/execute", 
            dataType : "json",
            success: function (response) {
                toastr['success']('Task executed successfully!');
                thiss.html(`<img src="/images/send.png" style="cursor: pointer; width: 0px;">`);
            },
            error: function (response) {
                if(response.status == 200){
                    toastr['success']('Task executed successfully!');
                }else{
                    toastr['error']('Something went wrong!');
                }
                thiss.html(`<img src="/images/send.png" style="cursor: pointer; width: 0px;">`);
            }
        });
    }); 

    $(document).on("click",".delete-tasks",function(e) {
        if(confirm('Do you really want to delete this task?')){
            $.ajax({
            type: "POST",
            url: "/totem/tasks/"+$(this).data('id')+"/delete", 
            data: {
				_token: "{{ csrf_token() }}", 
            }, 
            dataType : "json",
            success: function (response) {
                if(response.status){
                    toastr['success'](response.message);
                }else{
                    toastr['error'](response.message);
                }
                setTimeout(function(){
                    window.location.reload(1);
                }, 1000);
            },
            error: function () {
                toastr['error']('Something went wrong!');
            }
        });
        }
    }); 

    $(document).on("click",".active-task",function(e) {
        let active = $(this).attr('data-active');
        $.ajax({
            type: "POST",
            url: "/totem/tasks/"+$(this).data('id')+"/status", 
            data: {
                active: active,
				_token: "{{ csrf_token() }}", 
            }, 
            dataType : "json",
            success: function (response) {
                if(response.status){
                    toastr['success'](response.message);
                }else{
                    toastr['error'](response.message);
                }
                setTimeout(function(){
                    window.location.reload(1);
                }, 1000);
            },
            error: function () {
                toastr['error']('Something went wrong!');
            }
        });
    });   

    $('#frequency').change(function(){
        $('.added_params').remove();
        let params = JSON.parse($(this).val());
        if(params.parameters){
            let html = '';
            for(let i=0; i<params.parameters.length; i++){
                html += `
                <div class="form-group added_params">
                    <input type="text" value="" name="${params.parameters[i].name}" placeholder="${params.parameters[i].label}" class="form-control">
                </div> 
                `;
            }
            $(this).closest('.modal-body').append(html);
        }
    }); 

    $('input[name="type"]').change(function(){
        if($(this).val() == 'expression'){
            $('.cron_expression').removeClass('d-none');
            $('.frequencies').addClass('d-none');
        }else{
            $('.frequencies').removeClass('d-none');
            $('.cron_expression').addClass('d-none');
        }
    }); 

    $(document).on('click', '.remove_td', function(){
        $(this).closest('tr').remove();
    });

    $('.add_freq').click(function(){
        $('.default_td').remove();
        let freq_type = JSON.parse($('#addFrequencyModal').find('select').val());
        let input_fields = $('#addFrequencyModal').find('input');
        let tr = `
                    <tr> 
                    <td data-id="${freq}">${freq_type.label}
                        <input type="hidden" name="frequencies[${freq}][interval]" value="${freq_type.interval}">
                        <input type="hidden" name="frequencies[${freq}][label]" value="${freq_type.label}">
                    </td>
                    <td data-id="${freq}">
                    `;
        for(let i=0; i<input_fields.length; i++){
            tr += `${i!=0 ? ',' : ''} ${$(input_fields[i]).val()}
                        <input type="hidden" name="frequencies[${freq}][parameters][${i}][name]" value="${$(input_fields[i]).attr('name')}">
                        <input type="hidden" name="frequencies[${freq}][parameters][${i}][value]" value="${$(input_fields[i]).val()}">
                    `;
        }
        if(input_fields.length == 0){
            tr += `No Parameters`; 
        }
        tr += ` </td>
                <td>
                    <a class="remove_td">
                        <i class="fa fa-window-close"></i>
                    </a>
                </td>
                </tr>`;
        $('.freq').append(tr);
        freq++;
        $('#addFrequencyModal').modal('hide');   
    }); 

    $('.submit_btn').click(function(){
        $('.error').remove();
        let url = $('#addEditTaskModal').attr('data-id') == '' ? '/totem/tasks/create' : `/totem/tasks/${$('#addEditTaskModal').attr('data-id')}/edit`
        var form_data =  $('.taskForm').serialize();
        $.ajax({
            type: "POST",
            url: url,  
            data: form_data,
            dataType : "json",
            success: function (response) {
                if(response.task){
                    toastr['success']('Task Updated Successfully.');
                }else{
                    // toastr['error']('Something went wrong!');
                }
                setTimeout(function(){
                    window.location.reload(1);
                }, 1000);
            },
            error: function (response) {
                if(response.status == 200){
                    toastr['success']('Task Created Successfully.');
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 1000);
                }else{
                    let errors = response.responseJSON.errors;
                    for (var key in errors) {
                        if($(`input[name="${key}"]`).length == 0){
                            let error = `<p class="error" style="color:red;margin-top:-15px">${errors[key][0]}</p>`;
                            $(`select[name="${key}"]`).parent().after(error);
                        }else{
                            let error = `<p class="error" style="color:red">${errors[key][0]}</p>`;
                            $(`input[name="${key}"]`).after(error);
                        }
                        if(key == 'frequencies'){
                            let error = `<p class="error" style="color:red;margin-top:-15px">${errors[key][0]}</p>`;
                            $('.frequencies').after(error);
                        }
                    }
                    // toastr['error']('Something went wrong!');
                }
            }
        });

    });

    $('.edit-task').click(function(){
        freq = 0;
        $('#addEditTaskModal').attr('data-id', $(this).data('id'));
        $('#addEditTaskModal .modal-title').html('Edit task');
        $.ajax({
            type: "GET",
            url: "/totem/tasks/"+$(this).data('id'),  
            dataType : "json",
            success: function (response) {
                let task_fields = response  .task;
                for (var key in task_fields) {
                    if($(`input[name="${key}"]`).length != 0){
                        $(`input[name="${key}"]`).val(task_fields[key]);
                    }else if($(`select[name="${key}"]`).length != 0){
                        $(`select[name="${key}"]`).val(task_fields[key]);
                    }
                    if(key == 'frequencies'){
                        if(task_fields[key].length){
                            $('.default_td').remove();
                        }
                        for(let i=0; i<task_fields[key].length; i++){
                            let interval = task_fields[key][i].interval; 
                            let label = task_fields[key][i].label; 
                            let parameters = task_fields[key][i].parameters; 
                            let tr = `
                                        <tr> 
                                        <td data-id="${freq}">${label}
                                            <input type="hidden" name="frequencies[${freq}][interval]" value="${interval}">
                                            <input type="hidden" name="frequencies[${freq}][label]" value="${label}">
                                        </td>
                                        <td data-id="${freq}">
                                        `;
                            for(let j=0; j<task_fields[key][i].parameters; j++){
                                tr += `${j!=0 ? ',' : ''} ${$(task_fields[i]).val()}
                                            <input type="hidden" name="frequencies[${freq}][parameters][${j}][name]" value="${$(task_fields[j]).attr('name')}">
                                            <input type="hidden" name="frequencies[${freq}][parameters][${j}][value]" value="${$(task_fields[j]).val()}">
                                        `;
                            }
                            if(task_fields[key][i].parameters.length == 0){
                                tr += `No Parameters`; 
                            }
                            tr += ` </td>
                                    <td>
                                        <a class="remove_td">
                                            <i class="fa fa-window-close"></i>
                                        </a>
                                    </td>
                                    </tr>`;
                            $('.freq').append(tr);
                            freq++;
                        }
                    }
                    $('#addEditTaskModal').modal('show');  
                }
            },
            error: function (response) { 
                if(response.status != 200){      
                    toastr['error']('Something went wrong!');
                }
            }
        });
    });

</script>
@endsection