@extends('layouts.app')

@section('favicon' , 'task.png')

@section('title', 'Discussion tasks')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

    <style>
        #message-wrapper {
            height: 450px;
            overflow-y: scroll;
        }
        .dis-none {
            display: none;
        }
        .pd-5 {
            padding:5px;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="page-heading">{{$title}}</h2>
        </div>
    </div>
    <!--- Pre Loader -->
    <img src="/images/pre-loader.gif" id="Preloader" style="display:none;"/>
    @include('learning-module.partials.modal-contact')
    @include('learning-module.partials.modal-learning-category')
    @include('learning-module.partials.modal-learning-view')
    @include('learning-module.partials.modal-whatsapp-group')
    @include('partials.flash_messages')

    <div class="row mb-4">
        <div class="col-12">
            <form class="form-inline form-search-data">
                <input type="hidden" name="daily_activity_date" value="{{ $data['daily_activity_date'] }}">
                <input type="hidden" name="type" value="pending">
                <input type="hidden" name="is_statutory_query" value="3">
                <div class="form-group">
                    <input type="text" name="term" placeholder="Search Term" id="task_search" class="form-control input-sm" value="{{ isset($term) ? $term : "" }}">
                </div>
                <div class="form-group ml-3">
                    {!! $task_categories_dropdown !!}
                </div>
                <button type="submit" class="btn btn-image ml-3"><img src="/images/filter.png"/></button>
            </form>
        </div>
    </div>

    <?php
    if (\App\Helpers::getadminorsupervisor() && !empty($selected_user))
        $isAdmin = true;
    else
        $isAdmin = false;
    ?>
    <div class="row mb-4">
        <div class="col-xs-12">
            <form action="{{ route('task.store') }}" method="POST" id="taskCreateForm">
                @csrf
                <input type="hidden" name="has_render" value="1">
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                        
                            <input type="text" class="form-control input-sm" name="task_subject" placeholder="Task Subject" id="task_subject" value="{{ old('task_subject') }}" required/>
                            @if ($errors->has('task_subject'))
                                <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <textarea rows="1" class="form-control input-sm" name="task_details" placeholder="Task Details" id="task_details" required>{{ old('task_details') }}</textarea>
                            @if ($errors->has('task_details'))
                                <div class="alert alert-danger">{{$errors->first('task_details')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <select name="is_statutory" class="form-control is_statutory input-sm">
                            <option value="0">Other Task</option>
                                <option value="1">Statutory Task</option>
                                <option value="2">Calendar Task</option>
                                <option value="3">Discussion Task</option>
                            </select>
                        </div>

                        <div id="recurring-task" style="display: none;">
                            <div class="form-group">
                                <select name="recurring_type" class="form-control input-sm">
                                    <option value="EveryHour">EveryHour</option>
                                    <option value="EveryDay">EveryDay</option>
                                    <option value="EveryWeek">EveryWeek</option>
                                    <option value="EveryMonth">EveryMonth</option>
                                    <option value="EveryYear">EveryYear</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class='input-group date' id='sending-datetime'>
                                    <input type='text' class="form-control input-sm" name="sending_time" value="{{ date('Y-m-d H:i') }}" required/>

                                    <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                </div>

                                @if ($errors->has('sending_time'))
                                    <div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
                                @endif
                            </div>
                        </div>

                        <div id="calendar-task" style="display: none;">
                            <div class="form-group">
                                <div class='input-group date' id='completion-datetime'>
                                    <input type='text' class="form-control input-sm" name="completion_date" value="{{ date('Y-m-d H:i') }}"/>

                                    <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                </div>

                                @if ($errors->has('completion_date'))
                                    <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div class="form-group">
                                <select id="multi_users" class="form-control input-sm" name="assign_to[]" multiple>
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['name'] }} - {{ $user['email'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('assign_to'))
                                    <div class="alert alert-danger">{{$errors->first('assign_to')}}</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <div class="form-inline mb-3">
                            <div class="form-group flex-fill">
                                <select id="multi_contacts" class="form-control input-sm" name="assign_to_contacts[]" multiple>
                                    @foreach (Auth::user()->contacts as $contact)
                                        <option value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                                    @endforeach
                                </select>

                                {{-- <select class="selectpicker form-control input-sm" data-live-search="true" data-size="15" name="assign_to_contacts[]" title="Choose a Contact" multiple>
                                  @foreach (Auth::user()->contacts as $contact)
                                    <option data-tokens="{{ $contact['name'] }} {{ $contact['phone'] }} {{ $contact['category'] }}" value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                                  @endforeach
                                </select> --}}

                                @if ($errors->has('assign_to_contacts'))
                                    <div class="alert alert-danger">{{$errors->first('assign_to_contacts')}}</div>
                                @endif
                            </div>

                            <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createQuickContactModal"><img src="/images/add.png"/></button>
                        </div>
                        <div class="form-inline mb-3">
                            <div class="form-group flex-fill">
                                {{-- <strong>Category:</strong> --}}
                                {!! $task_categories_dropdown !!}
                                {{-- <select class="form-control input-sm" name="category" id="required_category" required>
                                  <option value="">Select a Category</option>
                                  @foreach ($task_categories_dropdown as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['title'] }}</option>

                                    @foreach ($category['child'] as $child)
                                      <option value="{{ $child['id'] }}">&nbsp;&nbsp;{{ $child['title'] }}</option>
                                    @endforeach
                                  @endforeach
                                </select> --}}
                            </div>

                            <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createTaskCategorytModal"><img src="/images/add.png"/></button>
                        </div>
                    </div>

                    <div class="col-xs-4" style="display: none;" id="appointment-container">
                        <div class="form-group">
                            <?php echo Form::select("task_id",["0" => "-- Add New --"] + \App\Task::where("is_statutory",3)->where("task_subject","!=","''")->get()->pluck("task_subject","id")->toArray(),null,[
                                "class" => "form-control select2-task-disscussion"
                            ]); ?>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" name="note[]" placeholder="Note" value="">
                        </div>
                        <div id="note-container">

                        </div>
                        <button type="button" class="btn btn-xs btn-secondary" id="addNoteButton">Add Note</button>
                        <button type="button" class="btn btn-xs btn-secondary dis-none" id="saveNewNotes">Save New Notes</button>
                    </div>



                    <div class="form-group ml-3">
                    <button type="submit" class="btn btn-secondary" id="taskCreateButton">Create</button>
                    </div>
                    @if(auth()->user()->isAdmin())
                    <div class="form-group ml-3">
                        <a class="btn btn-secondary" data-toggle="collapse" href="#openFilterCount" role="button" aria-expanded="false" aria-controls="openFilterCount">
                        Open Task count
                        </a>
                    </div>
                    @endif
                    <!-- <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-xs btn-secondary" id="taskCreateButton">Create</button>
                    </div> -->
                </div>
            </form>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <div class="row">
            <div class="col-md-12">
                <div class="collapse" id="openFilterCount">
                    <div class="card card-body">
                      <?php if(!empty($openTask)) { ?>
                        <div class="row col-md-12">
                            <?php foreach($openTask as $k => $v) { ?>
                              <div class="col-md-2">
                                    <div class="card">
                                      <div class="card-header">
                                        <?php echo $k; ?>
                                      </div>
                                      <div class="card-body">
                                          <?php echo $v; ?>
                                      </div>
                                  </div>
                               </div> 
                          <?php } ?>
                        </div>
                      <?php } else  { 
                        echo "Sorry , No data available";
                      } ?>
                    </div>
                </div>
            </div>    
        </div>
    @endif  

    @include('learning-module.partials.modal-reminder')
    <div class="tab-pane active" id="1">
                <div class="row">
                    <div class="infinite-scroll" style="width:100%;">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">Date</th>
                                <th width="12%" class="category">Category</th>
                                <th width="20%">Task Subject</th>
                                <th width="37%">Communication</th>
                                <th width="16%">Action&nbsp;
                                    <input type="checkbox" class="show-finished-task" name="show_finished" value="on">
                                    <label>Finished</label>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="discussion-row-render-view">
                            @if(count($data['task']['pending']) >0)
                            @foreach($data['task']['pending'] as $task)
                                @include("learning-module.partials.discussion-pending-raw",compact('task'))
                            @endforeach
                            @endif
                            </tbody>
                        </table>
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
                                        @if(auth()->user()->isAdmin())
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
                                    @if(auth()->user()->isAdmin())
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
                                        <th width="15%">Subject</th>
                                        <th width="69%">Task</th>
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
                        @if(auth()->user()->isAdmin())
                            <button type="submit" class="btn btn-secondary">Confirm</button>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="create-task-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create task</h4>
                </div>
                <div class="modal-body" id="create-task-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
              50% 50% no-repeat;display:none;">
    </div>
    @include("development.partials.time-history-modal")
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var taskSuggestions = {!! json_encode($search_suggestions, true) !!};
        var searchSuggestions = {!! json_encode($search_term_suggestions, true) !!};
        var cached_suggestions = localStorage['message_suggestions'];
        var suggestions = [];

        $(document).ready(function () {

            $('#priority_user_id').select2({
                tags: true,
                width: '100%'
            });

            $(document).on('click', '.btn-call-data', function (e) {
                e.preventDefault();
                var type = $(this).data('type');
                if(type && type != "") {
                    type = $("#tasktype").val(type);
                }
                type = $("#tasktype").val();
                $.ajax({
                    url: "/task",
                    type: 'GET',
                    data: $('.form-search-data').serialize(),
                    success: function (response) {
                        if(type == 'pending') {
                            $('.pending-row-render-view').html(response);
                        }
                        if(type == 'statutory_not_completed') {
                            $('.statutory-row-render-view').html(response);
                        }
                        if(type == 'completed') {
                            $('.completed-row-render-view').html(response);
                        }
                    },
                    error: function () {
                    }
                });
            });

            function getPriorityTaskList(id) {
                var selected_issue = [0];

                $('input[name ="selected_issue[]"]').each(function () {
                    if ($(this).prop("checked") == true) {
                        selected_issue.push($(this).val());
                    }
                });

                $.ajax({
                    url: "{{route('task.list.by.user.id')}}",
                    type: 'POST',
                    data: {
                        user_id: id,
                        _token: "{{csrf_token()}}",
                        selected_issue: selected_issue,
                    },
                    success: function (response) {
                        var html = '';
                        response.forEach(function (task) {
                            html += '<tr>';
                            html += '<td><input type="hidden" name="priority[]" value="' + task.id + '">' + task.id + '</td>';
                            html += '<td>' + task.task_subject + '</td>';
                            html += '<td>' + task.task_details + '</td>';
                            html += '<td>' + task.created_by + '</td>';
                            html += '<td><a href="javascript:;" class="delete_priority" data-id="' + task.id + '">Remove<a></td>';
                            html += '</tr>';
                        });
                        $(".show_task_priority").html(html);
                        <?php if (auth()->user()->isAdmin()) { ?>
                        $(".show_task_priority").sortable();
                        <?php } ?>
                    },
                    error: function () {
                        alert('There was error loading priority task list data');
                    }
                });
            }

            $(document).on('click', '.delete_priority', function (e) {
                var id = $(this).data('id');
                $('input[value ="' + id + '"]').prop('checked', false);
                $(this).closest('tr').remove();
            });

            $('.priority_model_btn').click(function () {
                $("#priority_user_id").val('0');
                $(".show_task_priority").html('');
                <?php if (auth()->user()->isAdmin()) { ?>
                getPriorityTaskList($('#priority_user_id').val());
                <?php } else { ?>
                getPriorityTaskList('{{auth()->user()->id}}');
                <?php } ?>
                $('#priority_model').modal('show');
            })


            $('#priority_user_id').change(function () {
                getPriorityTaskList($(this).val())
            });

            $(document).on('submit', '#priorityForm', function (e) {
                e.preventDefault();
                <?php if (auth()->user()->isAdmin()) { ?>
                $.ajax({
                    url: "{{route('task.set.priority')}}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr['success']('Priority successfully update!!', 'success');
                        $('#priority_model').modal('hide');
                    },
                    error: function () {
                        alert('There was error loading priority task list data');
                    }
                });
                <?php } ?>
            });

            $('#task_subject, #task_details').autocomplete({
                source: function (request, response) {
                    console.log(taskSuggestions);
                    var results = $.ui.autocomplete.filter(taskSuggestions, request.term);
                    console.log(results);
                    response(results.slice(0, 10));
                }
            });

            $('#task_search').autocomplete({
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(searchSuggestions, request.term);

                    response(results.slice(0, 10));
                }
            });

            var hash = window.location.hash.substr(1);

            if (hash == '3') {
                $('a[href="#3"]').click();
            }

            $('.selectpicker').selectpicker({
                selectOnTab: true
            });

            $('#multi_users').select2({
                placeholder: 'Select a User',
            });

            $('#multi_contacts').select2({
                placeholder: 'Select a Contact',
            });

            // $('ul.pagination').hide();
            // $(function() {
            //     $('.infinite-scroll').jscroll({
            //         autoTrigger: true,
            //         loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            //         padding: 2500,
            //         nextSelector: '.pagination li.active + li a',
            //         contentSelector: 'div.infinite-scroll',
            //         callback: function() {
            //             // $('ul.pagination').remove();
            //         }
            //     });
            // });

            // $('div.dropdown-menu.open li').on('keydown', function (e) {
            //   alert('yes');
            //   if (e.keyCode == 13) { // Enter
            //     alert('a');
            //     var previousEle = $(this).prev();
            //     if (previousEle.length == 0) {
            //       previousEle = $(this).nextAll().last();
            //     }
            //     var selVal = $('.selectpicker option').filter(function () {
            //       return $(this).text() == previousEle.text();
            //     }).val();
            //     $('.selectpicker').selectpicker('val', selVal);
            //
            //     return;
            //   }
            //   // if (e.keyCode == 40) { // Down
            //   //   var nextEle = $(this).next();
            //   //   if (nextEle.length == 0) {
            //   //     nextEle = $(this).prevAll().last();
            //   //   }
            //   //   var selVal = $('.selectpicker option').filter(function () {
            //   //     return $(this).text() == nextEle.text();
            //   //   }).val();
            //   //   $('.selectpicker').selectpicker('val', selVal);
            //   //
            //   //   return;
            //   // }
            //
            // });
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

        function addNewRemark(id) {

            var formData = $("#add-new-remark").find('#add-remark').serialize();
            // console.log(id);
            var remark = $('#remark-text_' + id).val();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {id: id, remark: remark, module_type: "task"},
            }).done(response => {
                alert('Remark Added Success!')
                // $('#add-new-remark').modal('hide');
                // $("#add-new-remark").hide();
                window.location.reload();
            });
        }

        $('#completion-datetime, #reminder-datetime, #sending-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $('#daily_activity_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        let users = {!! json_encode( $data['users'] ) !!};

        let isAdmin = {{ $isAdmin ? 1 : 0}};

        // let table = new Tabulator("#daily_activity", {
        //     height: "311px",
        //     layout: "fitColumns",
        //     resizableRows: true,
        //     columns: [
        //         {
        //             title: "Time",
        //             field: "time_slot",
        //             editor: "select",
        //             editorParams: {
        //                 '12:00am - 01:00am': '12:00am - 01:00am',
        //                 '01:00am - 02:00am': '01:00am - 02:00am',
        //                 '02:00am - 03:00am': '02:00am - 03:00am',
        //                 '03:00am - 04:00am': '03:00am - 04:00am',
        //                 '04:00am - 05:00am': '04:00am - 05:00am',
        //                 '05:00am - 06:00am': '05:00am - 06:00am',
        //                 '06:00am - 07:00am': '06:00am - 07:00am',
        //                 '07:00am - 08:00am': '07:00am - 08:00am',
        //
        //                 '08:00am - 09:00am': '08:00am - 09:00am',
        //                 '09:00am - 10:00am': '09:00am - 10:00am',
        //                 '10:00am - 11:00am': '10:00am - 11:00am',
        //                 '11:00am - 12:00pm': '11:00am - 12:00pm',
        //                 '12:00pm - 01:00pm': '12:00pm - 01:00pm',
        //                 '01:00pm - 02:00pm': '01:00pm - 02:00pm',
        //                 '02:00pm - 03:00pm': '02:00pm - 03:00pm',
        //                 '03:00pm - 04:00pm': '03:00pm - 04:00pm',
        //                 '04:00pm - 05:00pm': '04:00pm - 05:00pm',
        //                 '05:00pm - 06:00pm': '05:00pm - 06:00pm',
        //                 '06:00pm - 07:00pm': '06:00pm - 07:00pm',
        //                 '07:00pm - 08:00pm': '07:00pm - 08:00pm',
        //
        //                 '08:00pm - 09:00pm': '08:00pm - 09:00pm',
        //                 '09:00pm - 10:00pm': '09:00pm - 10:00pm',
        //                 '10:00pm - 11:00pm': '10:00pm - 11:00pm',
        //                 '11:00pm - 12:00am': '11:00pm - 12:00am',
        //             },
        //             editable: !isAdmin
        //         },
        //         {title: "Activity", field: "activity", editor: "textarea", formatter:"textarea", editable: !isAdmin},
        //         {title: "Assessment", field: "assist_msg", editor: "input", editable: !!isAdmin, visible: !!isAdmin},
        //         {title: "id", field: "id", visible: false},
        //         {title: "user_id", field: "user_id", visible: false},
        //     ],
        // });

        $("#add-row").click(function () {
            table.addRow({});
        });

        $(".add-task").click(function () {
            var taskId = $(this).attr('data-id');
            $("#add-new-remark").find('input[name="id"]').val(taskId);
        });

        $(".view-remark").click(function () {

            var taskId = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {id: taskId, module_type: "task"},
            }).done(response => {
                console.log(response);

                var html = '';

                $.each(response, function (index, value) {

                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#view-remark-list").find('#remark-list').html(html);
                // getActivity();
                //
                // $('#loading_activty').hide();
            });
        });

        // $("#save-activity").click(function () {
        //
        //     $('#loading_activty').show();
        //     console.log(table.getData());
        //
        //     let data = [];
        //
        //     if (isAdmin) {
        //         data = deleteKeyFromObjectArray(table.getData(), ['time_slot', 'activity']);
        //     }
        //     else {
        //         data = deleteKeyFromObjectArray(table.getData(), ['assist_msg']);
        //     }
        //
        //     $.ajax({
        //         type: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: '{{ route('dailyActivity.store') }}',
        //         data: {
        //             activity_table_data: encodeURI(JSON.stringify(data)),
        //         },
        //     }).done(response => {
        //         console.log(response);
        //         getActivity();
        //
        //         $('#loading_activty').hide();
        //     });
        // });

        // function deleteKeyFromObjectArray(data, key) {
        //
        //     let newData = [];
        //
        //     for (let item of data) {
        //
        //         for (let eachKey of key)
        //             delete  item[eachKey];
        //
        //         newData = [...newData, item];
        //     }
        //
        //     return newData;
        // }

        // function getActivity() {
        //     $.ajax({
        //         type: 'GET',
        //         data :{
        //             selected_user : '{{ $selected_user }}',
        //             daily_activity_date: "{{ $data['daily_activity_date'] }}",
        //         },
        //         url: '{{ route('dailyActivity.get') }}',
        //     }).done(response => {
        //         table.setData(response);
        //         setTimeout(getActivity, interval_daily_activtiy);
        //     });
        // }
        //
        // getActivity();
        // let interval_daily_activtiy = 1000*600;  // 1000 = 1 second
        // setTimeout(getActivity, interval_daily_activtiy);


        $(document).ready(function () {
            $(document).on('change', '.is_statutory', function () {


                if ($(".is_statutory").val() == 1) {

                    // $('input[name="completion_date"]').val("1976-01-01");
                    // $("#completion-datetime").hide();
                    $("#calendar-task").hide();
                    $('#appointment-container').hide();

                    if (!isAdmin)
                        $('select[name="assign_to"]').html(`<option value="${current_userid}">${ current_username }</option>`);

                    $('#recurring-task').show();
                } else if ($(".is_statutory").val() == 2) {
                    $("#calendar-task").show();
                    $('#recurring-task').hide();
                    $('#appointment-container').hide();
                } else if ($(".is_statutory").val() == 3) {
                    $("#calendar-task").hide();
                    $('#recurring-task').hide();
                    $('#appointment-container').show();
                } else {

                    // $("#completion-datetime").show();
                    $("#calendar-task").hide();
                    $('#appointment-container').hide();

                    let select_html = '';
                    for (user of users)
                        select_html += `<option value="${user['id']}">${ user['name'] }</option>`;
                    $('select[name="assign_to"]').html(select_html);

                    $('#recurring-task').hide();

                }

            });

            jQuery('#userList').select2(
                {
                    placeholder: 'All user'
                }
            );

            let r_s = '';
            let r_e = '{{ date('y-m-d') }}';

            let start = r_s ? moment(r_s, 'YYYY-MM-DD') : moment().subtract(6, 'days');
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
            }, cb);

            cb(start, end);

            $('#reportrange').on('apply.daterangepicker', function (ev, picker) {

                jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
                jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

            });

            $(".table").tablesorter();
        });

        $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var task_id = $(this).data('taskid');
            var message = $(this).siblings('input').val();

            data.append("task_id", task_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/task',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function (response) {
                        $(thiss).siblings('input').val('');

                        if (cached_suggestions) {
                            suggestions = JSON.parse(cached_suggestions);

                            if (suggestions.length == 10) {
                                suggestions.push(message);
                                suggestions.splice(0, 1);
                            } else {
                                suggestions.push(message);
                            }
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('EXISTING');
                            console.log(suggestions);
                        } else {
                            suggestions.push(message);
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('NOT');
                            console.log(suggestions);
                        }

                        // $.post( "/whatsapp/approve/customer", { messageId: response.message.id })
                        //   .done(function( data ) {
                        //
                        //   }).fail(function(response) {
                        //     console.log(response);
                        //     alert(response.responseJSON.message);
                        //   });

                        $(thiss).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });
        
        // $(document).on('click', '.create-task-btn', function () {
        //     $.ajax({
        //         type: "GET",
        //         url: "/task/create-task",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //         },
        //         beforeSend: function () {
        //             $("#loading-image").show();
        //         }
        //     }).done(function (response) {
        //         $("#loading-image").hide();
        //         $("#create-task-modal").modal("show");
        //         $("#create-task-body").html(response);
        //     }).fail(function (response) {
        //         $("#loading-image").hide();
        //     });
        // });

        $(document).on('click', '.make-private-task', function () {
            var task_id = $(this).data('taskid');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('task') }}/" + task_id + "/makePrivate",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Changing...');
                }
            }).done(function (response) {
                if (response.task.is_private == 1) {
                    $(thiss).html('<img src="/images/private.png" />');
                } else {
                    $(thiss).html('<img src="/images/not-private.png" />');
                }
            }).fail(function (response) {
                $(thiss).html('<img src="/images/not-private.png" />');

                console.log(response);

                alert('Could not make task private');
            });
        });

        $(document).on('click', ".collapsible-message", function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                var short_message = $(this).data('messageshort');
                var message = $(this).data('message');
                var status = $(this).data('expanded');

                if (status == false) {
                    $(this).addClass('expanded');
                    $(this).html(message);
                    $(this).data('expanded', true);
                    // $(this).siblings('.thumbnail-wrapper').remove();
                    $(this).closest('.talktext').find('.message-img').removeClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', 'auto');
                } else {
                    $(this).removeClass('expanded');
                    $(this).html(short_message);
                    $(this).data('expanded', false);
                    $(this).closest('.talktext').find('.message-img').addClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', '200px');
                }
            }
        });

        $(document).ready(function () {
            var container = $("div#message-container");
            var suggestion_container = $("div#suggestion-container");
            // var sendBtn = $("#waMessageSend");
            var erpUser = "{{ Auth::id() }}";
            var addElapse = false;

            function errorHandler(error) {
                console.error("error occured: ", error);
            }

            function approveMessage(element, message) {
                if (!$(element).attr('disabled')) {
                    $.ajax({
                        type: "POST",
                        url: "/whatsapp/approve/user",
                        data: {
                            _token: "{{ csrf_token() }}",
                            messageId: message.id
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).text('Approving...');
                        }
                    }).done(function (data) {
                        element.remove();
                        console.log(data);
                    }).fail(function (response) {
                        $(element).attr('disabled', false);
                        $(element).text('Approve');

                        console.log(response);
                        alert(response.responseJSON.message);
                    });
                }
            }

            function renderMessage(message, tobottom = null) {
                var domId = "waMessage_" + message.id;
                var current = $("#" + domId);
                var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
                var is_hod_crm = "{{ Auth::user()->hasRole('HOD of CRM') }}";
                var users_array = {!! json_encode($users) !!};
                var leads_assigned_user = "";

                if (current.get(0)) {
                    return false;
                }

                // CHAT MESSAGES
                var row = $("<div class='talk-bubble'></div>");
                var body = $("<span id='message_body_" + message.id + "'></span>");
                var text = $("<div class='talktext'></div>");
                var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.message + '</textarea>');
                var p = $("<p class='collapsible-message'></p>");

                var forward = $('<button class="btn btn-image forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><img src="/images/forward.png" /></button>');

                if (message.status == 0 || message.status == 5 || message.status == 6) {
                    var meta = $("<em>" + users_array[message.user_id] + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
                    var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
                    var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');

                    // row.attr("id", domId);
                    p.appendTo(text);

                    // $(images).appendTo(text);
                    meta.appendTo(text);

                    if (message.status == 0) {
                        mark_read.appendTo(meta);
                    }

                    if (message.status == 0 || message.status == 5) {
                        mark_replied.appendTo(meta);
                    }

                    text.appendTo(row);

                    if (tobottom) {
                        row.appendTo(container);
                    } else {
                        row.prependTo(container);
                    }

                    forward.appendTo(meta);

                } else if (message.status == 4) {
                    var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
                    var chat_friend = (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.user_id != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
                    var meta = $("<em>" + users_array[message.user_id] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");

                    // row.attr("id", domId);

                    p.appendTo(text);
                    $(images).appendTo(text);
                    meta.appendTo(text);

                    text.appendTo(row);
                    if (tobottom) {
                        row.appendTo(container);
                    } else {
                        row.prependTo(container);
                    }
                } else {
                    if (message.sent == 0) {
                        var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>";
                    } else {
                        var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /></em>";
                    }

                    var error_flag = '';
                    if (message.error_status == 1) {
                        error_flag = "<a href='#' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /></a><a href='#' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'>Resend</a>";
                    } else if (message.error_status == 2) {
                        error_flag = "<a href='#' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /><img src='/images/flagged.png' /></a><a href='#' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'>Resend</a>";
                    }


                    var meta = $(meta_content);

                    edit_field.appendTo(text);

                    if (!message.approved) {
                        var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
                        var editBtn = ' <a href="#" style="font-size: 9px" class="edit-message whatsapp-message ml-2" data-messageid="' + message.id + '">Edit</a>';
                        approveBtn.click(function () {
                            approveMessage(this, message);
                        });
                        if (is_admin || is_hod_crm) {
                            approveBtn.appendTo(meta);
                            $(editBtn).appendTo(meta);
                        }
                    }

                    forward.appendTo(meta);

                    $(error_flag).appendTo(meta);
                }

                row.attr("id", domId);

                p.attr("data-messageshort", message.message);
                p.attr("data-message", message.message);
                p.attr("data-expanded", "true");
                p.attr("data-messageid", message.id);
                // console.log("renderMessage message is ", message);
                if (message.message) {
                    p.html(message.message);
                } else if (message.media_url) {
                    var splitted = message.content_type.split("/");
                    if (splitted[0] === "image" || splitted[0] === 'm') {
                        var a = $("<a></a>");
                        a.attr("target", "_blank");
                        a.attr("href", message.media_url);
                        var img = $("<img></img>");
                        img.attr("src", message.media_url);
                        img.attr("width", "100");
                        img.attr("height", "100");
                        img.appendTo(a);
                        a.appendTo(p);
                        // console.log("rendered image message ", a);
                    } else if (splitted[0] === "video") {
                        $("<a target='_blank' href='" + message.media_url + "'>" + message.media_url + "</a>").appendTo(p);
                    }
                }

                var has_product_image = false;

                if (message.images) {
                    var images = '';
                    message.images.forEach(function (image) {
                        images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '<br><strong>Supplier: </strong>' + image.supplier_initials + '">' : '';
                        images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete whatsapp-image" data-image="' + image.key + '">x</span></div>';
                        images += image.product_id !== '' ? '<input type="checkbox" name="product" style="width: 20px; height: 20px;" class="d-block mx-auto select-product-image" data-id="' + image.product_id + '" /></a>' : '';

                        if (image.product_id !== '') {
                            has_product_image = true;
                        }
                    });

                    images += '<br>';

                    if (has_product_image) {
                        var show_images_wrapper = $('<div class="show-images-wrapper hidden"></div>');
                        var show_images_button = $('<button type="button" class="btn btn-xs btn-secondary show-images-button">Show Images</button>');

                        $(images).appendTo(show_images_wrapper);
                        $(show_images_wrapper).appendTo(text);
                        $(show_images_button).appendTo(text);
                    } else {
                        $(images).appendTo(text);
                    }

                }

                p.appendTo(body);
                body.appendTo(text);
                meta.appendTo(text);

                var select_box = $('<input type="checkbox" name="selected_message" class="select-message" data-id="' + message.id + '" />');

                select_box.appendTo(meta);

                if (has_product_image) {
                    var create_lead = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-lead">+ Lead</a>');
                    var create_order = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-order">+ Order</a>');

                    create_lead.appendTo(meta);
                    create_order.appendTo(meta);
                }

                text.appendTo(row);

                if (message.status == 7) {
                    if (tobottom) {
                        row.appendTo(suggestion_container);
                    } else {
                        row.prependTo(suggestion_container);
                    }
                } else {
                    if (tobottom) {
                        row.appendTo(container);
                    } else {
                        row.prependTo(container);
                    }
                }


                return true;
            }

            function pollMessages(page = null, tobottom = null, addElapse = null) {
                var qs = "";
                qs += "?erpUser=" + erpUser;
                if (page) {
                    qs += "&page=" + page;
                }
                if (addElapse) {
                    qs += "&elapse=3600";
                }
                var anyNewMessages = false;

                return new Promise(function (resolve, reject) {
                    $.getJSON("/whatsapp/pollMessagesCustomer" + qs, function (data) {

                        data.data.forEach(function (message) {
                            var rendered = renderMessage(message, tobottom);
                            if (!anyNewMessages && rendered) {
                                anyNewMessages = true;
                            }
                        });

                        if (page) {
                            $('#load-more-messages').text('Load More');
                            can_load_more = true;
                        }

                        if (anyNewMessages) {
                            // scrollChatTop();
                            anyNewMessages = false;
                        }
                        if (!addElapse) {
                            addElapse = true; // load less messages now
                        }


                        resolve();
                    });

                });
            }

            function startPolling() {
                setTimeout(function () {
                    pollMessages(null, null, addElapse).then(function () {
                        startPolling();
                    }, errorHandler);
                }, 1000);
            }

            $('a[href="#unassigned-tab"]').on('click', function () {
                startPolling();
            });

            var can_load_more = true;

            $('#message-wrapper').scroll(function () {
                var top = $('#message-wrapper').scrollTop();
                var document_height = $(document).height();
                var window_height = $('#message-container').height();

                console.log($('#message-wrapper').scrollTop());
                console.log($(document).height());
                console.log($('#message-container').height());

                // if (top >= (document_height - window_height - 200)) {
                if (top >= (window_height - 1500)) {
                    console.log('should load', can_load_more);
                    if (can_load_more) {
                        var current_page = $('#load-more-messages').data('nextpage');
                        $('#load-more-messages').data('nextpage', current_page + 1);
                        var next_page = $('#load-more-messages').data('nextpage');
                        console.log(next_page);
                        $('#load-more-messages').text('Loading...');

                        can_load_more = false;

                        pollMessages(next_page, true);
                    }
                }
            });

            $(document).on('click', '#load-more-messages', function () {
                var current_page = $(this).data('nextpage');
                $(this).data('nextpage', current_page + 1);
                var next_page = $(this).data('nextpage');
                $('#load-more-messages').text('Loading...');

                pollMessages(next_page, true);
            });

        });

        var selected_messages = [];
        $(document).on('click', '.select-message', function () {
            var message_id = $(this).data('id');

            if ($(this).prop('checked')) {
                selected_messages.push(message_id);
            } else {
                var index = selected_messages.indexOf(message_id);

                selected_messages.splice(index, 1);
            }

            console.log(selected_messages);
        });

        $('#assignMessagesButton').on('click', function (e) {
            e.preventDefault();

            if (selected_messages.length > 0) {
                $('#selected_messages').val(JSON.stringify(selected_messages));

                if ($(this).closest('form')[0].checkValidity()) {
                    $(this).closest('form').submit();
                } else {
                    $(this).closest('form')[0].reportValidity();
                }
            } else {
                alert('Please select atleast 1 message');
            }
        });

        var timer = 0;
        var delay = 200;
        var prevent = false;

        $(document).on('click', '.task-complete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var thiss = $(this);

            timer = setTimeout(function () {
                if (!prevent) {
                    var task_id = $(thiss).data('id');
                    var image = $(thiss).html();
                    var url = "/task/complete/" + task_id;
                    var current_user = {{ Auth::id() }};

                    if (!$(thiss).is(':disabled')) {
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                type: 'complete'
                            },
                            beforeSend: function () {
                                $(thiss).text('Completing...');
                            }
                        }).done(function (response) {
                            if (response.task.is_verified != null) {
                                $(thiss).html('<img src="/images/completed.png" />');
                            } else if (response.task.is_completed != null) {
                                $(thiss).html('<img src="/images/completed-green.png" />');
                            } else {
                                $(thiss).html('<img src="/images/incomplete.png" />');
                            }

                            if (response.task.assign_from != current_user) {
                                $(thiss).attr('disabled', true);
                            }
                        }).fail(function (response) {
                            $(thiss).html(image);

                            alert('Could not mark as completed!');
                            toastr['error'](response.responseJSON.message);
                            console.log(response);
                        });
                    }
                }

                prevent = false;
            }, delay);
        });

        $(document).on('dblclick', '.task-complete', function (e) {
            e.preventDefault();
            e.stopPropagation();

            clearTimeout(timer);
            prevent = true;

            var thiss = $(this);
            var task_id = $(this).data('id');
            var image = $(this).html();
            var url = "/task/complete/" + task_id;

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: 'clear'
                },
                beforeSend: function () {
                    $(thiss).text('Clearing...');
                }
            }).done(function (response) {
                if (response.task.is_verified != null) {
                    $(thiss).html('<img src="/images/completed.png" />');
                } else if (response.task.is_completed != null) {
                    $(thiss).html('<img src="/images/completed-green.png" />');
                } else {
                    $(thiss).html('<img src="/images/incomplete.png" />');
                }
            }).fail(function (response) {
                $(thiss).html(image);

                alert('Could not clear the task!');

                console.log(response);
            });
        });

        $(document).on('click', '.resend-message', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('whatsapp') }}/" + id + "/resendMessage",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Sending...');
                }
            }).done(function (response) {
                $(thiss).html('<img src="/images/resend.png" />');
            }).fail(function (response) {
                $(thiss).html('<img src="/images/resend.png" />');

                console.log(response);

                alert('Could not resend message');
            });
        });

        $(document).on('click', '#addNoteButton', function () {
            var note_html = `<div class="form-group d-flex">
            <input type="text" class="form-control input-sm" name="note[]" placeholder="Note" value="">
            <button type="button" class="btn btn-image remove-note">x</button>
          </div>`;

            $('#note-container').append(note_html);
        });

        $(document).on('click', '.remove-note', function () {
            $(this).closest('.form-group').remove();
        });
        $(document).on('click', '.reminder-message', function () {
            var id = $(this).data('id');

            $('#reminderMessageModal').find('input[name="message_id"]').val(id);
        });

        $(document).on('click', '.convert-task-appointment', function () {
            var thiss = $(this);
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('task') }}/" + id + "/convertTask",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Converting...');
                }
            }).done(function (response) {
                $(thiss).closest('tr').addClass('row-highlight');
                $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/details.png" />');

                console.log(response);

                alert('Could not convert a task');
            });
        });

        $(document).on('click', '.flag-task', function () {
            var task_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('task.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_id: task_id
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
                if (response.is_flagged == 1) {
                    // var badge = $('<span class="badge badge-secondary">Flagged</span>');
                    //
                    // $(thiss).parent().append(badge);
                    $(thiss).html('<img src="/images/flagged.png" />');
                } else {
                    $(thiss).html('<img src="/images/unflagged.png" />');
                    // $(thiss).parent().find('.badge').remove();
                }

                // $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag task!');

                console.log(response);
            });
        });

        var selected_tasks = [];

        $(document).on('click', '.select_task_checkbox', function () {
            var checked = $(this).prop('checked');
            var id = $(this).data('id');

            if (checked) {
                selected_tasks.push(id);
            } else {
                var index = selected_tasks.indexOf(id);

                selected_tasks.splice(index, 1);
            }

            console.log(selected_tasks);
        });

        $('#view_tasks_button').on('click', function () {
            var selected = $(this).data('selected');

            // if (selected == 0) {
            //   $(this).text('View');
            //
            //   $('.select_task_checkbox').removeClass('hidden');
            //
            //   $(this).data('selected', 1);
            // } else if (selected == 1) {
            // $(this).text('Select for Viewing');

            // $('.select_task_checkbox').removeClass('hidden');

            $(this).data('selected', 0);
            console.log(JSON.stringify(selected_tasks));
            if (selected_tasks.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('task/loadView') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        selected_tasks: selected_tasks
                    }
                }).done(function (response) {
                    $('#task_view_body').html(response.view);

                    $('#taskViewModal').modal();
                }).fail(function (response) {
                    console.log(response);

                    alert('Could not load tasks view');
                });
            } else {
                alert('Please select atleast 1 task!');
            }
            // }
        });

        $('#taskCreateButton').on('click', function (e) {
            e.preventDefault();
            var form  = $(this).closest('form');
            var users = $('#multi_users').val();
            var contacts = $('#multi_contacts').val();
            var category = form.find('select[name="category"]').val();


            if ($('#taskCreateForm')[0].checkValidity()) {
                if (users.length == 0 && contacts.length == 0) {
                    alert('Please select atleast one user or contact');
                } else {
                    if (category == '1') {
                        alert('Category is required!');
                    } else {
                        $.ajax({
                            type: "POST",
                            beforeSend:function(){
                                $("#loading-image").show();
                            },
                            url: form.attr("action"),
                            data: form.serialize(),
                            dataType : "json"
                        }).done(function (response) {
                            $("#loading-image").hide();
                            if(response.code == 200) {
                                if(response.statutory == 1) {
                                    $(".statutory-row-render-view").prepend(response.raw);
                                }
                                else if(response.statutory == 3) {
                                    $(".discussion-row-render-view").prepend(response.raw);
                                }
                                else{
                                    $(".pending-row-render-view").prepend(response.raw);
                                    
                                }
                            }
                            //window.location.reload();
                        }).fail(function (response) {
                            console.log(response);
                        });
                        //$('#taskCreateForm').submit();
                    }
                }
            } else {
                $('#taskCreateForm')[0].reportValidity();
            }
        });

        $('#task_category_selection').on('change', function () {
            var category_id = $(this).val();
            var is_approved = $(this).find('option:selected').data('approved');
            var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
            console.log(is_approved, is_admin);
            if (is_admin == 1 && !is_approved) {
                $('#approveTaskCategoryButton').parent().removeClass('hidden');
            } else {
                $('#approveTaskCategoryButton').parent().addClass('hidden');
            }

            $('#deleteTaskCategoryButton').attr('data-id', category_id);
            $('#approveTaskCategoryButton').attr('data-id', category_id);
        });

        $('#deleteTaskCategoryButton').on('click', function () {
            var id = $(this).attr('data-id');

            if (id == '') {
                alert('Please select category first');
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ url('task_category') }}/" + id,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    }
                }).done(function () {
                    window.location.reload();
                }).fail(function (response) {
                    console.log(response);
                    alert('Could not delete a category');
                });
            }
        });

        $('#approveTaskCategoryButton').on('click', function () {
            var id = $(this).attr('data-id');

            if (id == '') {
                alert('Please select category first');
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ url('task_category') }}/" + id + '/approve',
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function () {
                    window.location.reload();
                }).fail(function (response) {
                    console.log(response);
                    alert('Could not approve a category');
                });
            }
        });


        $(document).on('click', '.whatsapp-group', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $("#task_id").val(id);
            $("#Preloader").show();
            $.ajax({
                type: "POST",
                async: false,
                url: "{{ route('task.add.whatsapp.group') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                }
            }).done(function (response) {
                console.log(response);
                $("#group_id").val(response.group_id);
                $("#Preloader").hide();

            })
        });
        $(document).on("keypress",".update_approximate",function(e) {
            var key = e.which;
            var thiss = $(this);
            if (key == 13) {
                e.preventDefault();
                var approximate = $(thiss).val();
                var task_id = $(thiss).data('id');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('task.update.approximate') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        approximate: approximate,
                        task_id: task_id
                    }
                }).done(function () {
                    $(thiss).closest("td").find(".apx-val").html(approximate);
                    $(thiss).closest('td').find('.update_approximate_msg').fadeIn(400);
                    setTimeout(function () {
                        $(thiss).closest('td').find('.update_approximate_msg').fadeOut(400);
                    }, 2000);

                }).fail(function (response) {
                    alert('Could not update!!');
                });
            }
        });


        $(document).on("keypress",".update_cost",function(e) {
            var key = e.which;
            var thiss = $(this);
            if (key == 13) {
                e.preventDefault();
                var cost = $(thiss).val();
                var task_id = $(thiss).data('id');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('task.update.cost') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        cost: cost,
                        task_id: task_id
                    }
                }).done(function () {
                    $(thiss).closest("td").find(".cost-val").html(cost);
                    $(thiss).closest('td').find('.update_cost_msg').fadeIn(400);
                    setTimeout(function () {
                        $(thiss).closest('td').find('.update_cost_msg').fadeOut(400);
                    }, 2000);

                }).fail(function (response) {
                    alert('Could not update!!');
                });
            }
        });


        $(document).on('keyup', '.save-milestone', function (event) {
            if (event.keyCode != 13) {
                return;
            }
            let id = $(this).attr('data-id');
            let total = $(this).val();

            $.ajax({
                url: "{{action('TaskModuleController@saveMilestone')}}",
                data: {
                    total: total,
                    task_id: id
                },
                success: function () {
                    toastr["success"]("Milestone updated successfully!", "Message")
                },   
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    console.log(error.responseJSON.message);
                    
                }
            });
        });

        $(document).on('click', '.show-time-history', function() {
            var data = $(this).data('history');
            var issueId = $(this).data('id');
            $('#time_history_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/time/history') }}",
                data: {id: issueId},
                success: function (data) {
                    if(data != 'error') {
                        $.each(data, function(i, item) {
                            $('#time_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
            $('#time_history_modal').modal('show');
        });

        $(document).on("change",".select2-task-disscussion",function() {
            var $this = $(this);
                if($this.val() != 0) {
                     $.ajax({
                        type: 'GET',
                        url: "{{ route('task.json.details') }}",
                        data: {task_id : $this.val()},
                        dataType : "json"
                    }).done(function (response) {
                        if(response.code == 200) {
                             $("#saveNewNotes").removeClass("dis-none");   
                        }else{
                            alert(response.message);
                            $("#saveNewNotes").addClass("dis-none");
                        }
                    }).fail(function (response) {
                        alert('Could not update!!');
                    });
                }else{
                    $("#saveNewNotes").addClass("dis-none");
                }
        });

        $(document).on("click","#saveNewNotes",function() {
            var $this = $(this);
            $.ajax({
                beforeSend : function() {
                    toastr['info']('Sending data!!', 'info');
                },
                type: 'POST',
                url: "{{ route('task.json.saveNotes') }}",
                data: $("#taskCreateForm").serialize(),
                dataType: "json"
            }).done(function (response) {
                if(response.code == 200) {
                    //toastr['success']('Success!!', 'success');
                    location.reload();
                }
            }).fail(function (response) {
                alert('Could not update!!');
            });
        });

        $(document).on("click",".delete-task-btn",function() {
            var $this = $(this);
            var taskId = $this.data("id");

            if(taskId > 0) {
                $.ajax({
                    beforeSend : function() {
                        $("#loading-image").show();
                    },
                    type: 'POST',
                    url: "/tasks/deleteTask",
                    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
                    data: {id : taskId},
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if(response.code == 200) {
                        $this.closest("td").remove();
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    alert('Could not update!!');
                });
            }

        });

        $(document).on("click",".show-finished-task",function(){
            var $this = $(this);
            if($this.is(":checked")) {
                $this.closest("table").find("tbody tr").hide();
                $this.closest("table").find("tbody tr").filter(function() {
                    return $(this).find('.task-complete img').attr('src') === "/images/completed-green.png";
                }).show();
            }else{
                $this.closest("table").find("tbody tr").show();
            }
        });


        $(document).on('change', '#is_milestone', function () {

            var is_milestone = $('#is_milestone').val();
            if(is_milestone == '1') {
                $('#no_of_milestone').attr('required', 'required');
            }
            else {
                $('#no_of_milestone').removeAttr('required');
            }
            });

    </script>
@endsection
