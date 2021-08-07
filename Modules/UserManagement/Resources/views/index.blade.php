<!--jQuery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{{-- <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"> --}}


<!--prettyTag JS-->
{{-- <script src="js/jquery.prettytag.js"></script> --}}

<!--prettyTag CSS-->
{{-- <link rel="stylesheet" href="css/prertytag.css" /> --}}
{{-- <link rel="stylesheet" href="../fancymetags.css"> --}}
@extends('layouts.app')
@section('favicon', 'user-management.png')

@section('large_content')
    <style type="text/css">
        .preview-category input.form-control {
            width: auto;
        }

    </style>

    <style>
        #payment-table_filter {
            text-align: right;
        }

        .activity-container {
            margin-top: 3px;
        }

        .elastic {
            transition: height 0.5s;
        }

        .activity-table-wrapper {
            position: absolute;
            width: calc(100% - 50px);
            max-height: 500px;
            overflow-y: auto;
        }

        .dropdown-wrapper {
            position: relative;
        }

        .dropdown-wrapper.hidden {
            display: none;
        }

        .dropdown-wrapper>ul {
            margin: 0px;
            padding: 5px;
            list-style: none;
            position: absolute;
            width: 100%;
            box-shadow: 3px 3px 10px 0px;
            background: white;
        }

        .dropdown input {
            width: calc(100% - 120px);
            line-height: 2;
            outline: none;
            border: none;
        }

        .payment-method-option:hover {
            background: #d4d4d4;
        }

        .payment-method-option.selected {
            font-weight: bold;
        }

        .payment-dropdown-header {
            padding: 2px;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
        }

        .payment-overlay {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0px;
        }

        .error {
            color: red;
            font-size: 10pt;
        }

        .pd-5 {
            padding: 5px;
        }
        .feedback_model .modal-dialog{
           max-width:1024px;
           width:100%;
        }
        .quick_feedback, #feedback-status{
            border: 1px solid #ddd;
            border-radius: 4px;
            height: 35px;
            outline: none;
        }
        .quick_feedback:focus, #feedback-status:focus{
            outline: none;
        }
        .communication-td input{
            width: calc(100% - 25px) !important;
        }
        .communication-td button{
            width:20px;
        }
    </style>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    @include('partials.flash_messages')
    <div class="row" id="common-page-layout">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">{{ $title }} <span class="count-text"></span></h2>
        </div>
        <br>
        <input type="hidden" name="page_no" class="page_no" />
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="col">
                    <div class="h" style="margin-bottom:10px;">
                        <div class="row">
                            <form class="form-inline message-search-handler" method="post">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="keyword">Keyword:</label>
                                        <?php echo Form::text('keyword', request('keyword'), ['class' =>
                                        'form-control data-keyword', 'placeholder' => 'Enter keyword']); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="keyword">Active:</label>
                                        <select name="is_active" class="form-control">
                                            <option value="0" {{ request('is_active') == 0 ? 'selected' : '' }}>All</option>
                                            <option value="1" {{ request('is_active') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="2" {{ request('is_active') == 2 ? 'selected' : '' }}>In active
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="button">&nbsp;</label>
                                        <button style="display: inline-block;width: 10%"
                                            class="btn btn-sm btn-image btn-search-action">
                                            <img src="/images/search.png" style="cursor: default;">
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if (auth()->user()->isAdmin())

                            <button class="btn btn-secondary btn-xs pull-right mt-0 mr-2 permission-request">Permission
                                request ( {{ $permissionRequest }} )</button>

                            <button class="btn btn-secondary btn-xs pull-right mt-0 mr-2 erp-request">ERP IPs</button>

                            <button class="btn btn-secondary btn-xs pull-right ml-2 mt-0 mr-2 system-request" data-toggle="modal"
                                data-target="#system-request">System IPs</button>

                            <button class="btn btn-secondary btn-xs pull-right today-history"> All user task </button>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-12 margin-tb" id="page-view-result">

            </div>
        </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
              50% 50% no-repeat;display:none;">
    </div>
    <div class="common-modal modal" role="dialog">
        <div class="modal-dialog modal-lg" role="document" id="modalDialog">
        </div>
    </div>

    <div class="common-modal modal" role="adminj">
        <div class="modal-dialog modal-lg" role="dialog" id="exampleModal">
        </div>
    </div>

    {{-- //feeback model --}}

    <div id="exampleModal123" class="modal fade feedback_model" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content" id="success">
                <div class="modal-header">
                    <h5 class="modal-title">User Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 p-0" id="permission-request">
                        <table class="table table-bordered" id="feedback_tbl">
                            <thead>
                                <tr>
                                    <th width="20%">Category</th>
                                    <th width="25%">Admin Response</th>
                                    <th width="25%">User Response</th>
                                    <th width="20%">Status</th>
                                    <th width="10%">History</th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" style="width:calc(100% - 25px)" class="quick_feedback" id="addcategory" name="category">
                                        <button style="width: 20px" type="button" class="btn btn-image add-feedback" id="btn-save"><img src="/images/add.png" style="cursor: nwse-resize; width: 0px;"></button>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td><input type="textbox" style="width:calc(100% - 25px)" id="feedback-status">
                                        <button style="width: 20px" type="button" class="btn btn-image user-feedback-status"><img src="/images/add.png" style="cursor: nwse-resize; width: 0px;"></button></td>
                                    <td></td>
                                </tr>
                            </thead>
                            
                            <tbody class="show-list-records user-feedback-data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            
        </div>
    </div>



    <div id="permission-request-model" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Permission request list</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-default permission-delete-grant">Delete All</button>
                    <div class="col-md-12" id="permission-request">
                        <table class="table fixed_header">
                            <thead>
                                <tr>
                                    <th>User name</th>
                                    <th>Permission name</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="show-list-records">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="status-history" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Status History</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" id="permission-request">
                        <table class="table fixed_header">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Actioned By</th>
                                    <th>Change Status To</th>
                                </tr>
                            </thead>
                            <tbody class="show-list-records">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div id="erp-request" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Login IPs</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" id="permission-request">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User Email</th>
                                    <th>IP</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="show-list-records">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <div id="system-request" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">System IPs</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" id="permission-request">

                        @php
                            $shell_list = shell_exec('bash ' . getenv('DEPLOYMENT_SCRIPTS_PATH') . '/webaccess-firewall.sh -f list');
                            
                            $final_array = [];
                            if ($shell_list != '') {
                                $lines = explode(PHP_EOL, $shell_list);
                                $final_array = [];
                                foreach ($lines as $line) {
                                    $values = [];
                                    $values = explode(' ', $line);
                                    array_push($final_array, $values);
                                }
                            }
                        @endphp
                        <input type="text" name="add-ip" class="form-control col-md-3" placeholder="Add IP here...">

                        <div>
                            <select class="form-control col-md-3 ml-3" name="user_id" id="ipusers">
                                <option>Select user</option>
                                @foreach ($userlist as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>

                            <input type="text" name="other_user_name" id="other_user_name"
                                class="form-control col-md-3 ml-3" style="display:none;" placeholder="other name">
                        </div>

                        <input type="text" name="ip_comment" class="form-control col-md-3 ml-3"
                            placeholder="Add comment...">

                        <button class="btn-success btn addIp ml-3 mb-5">Add</button>

                        <table class="table table-bordered">
                            <tr>
                                <th>Index</th>
                                <th>IP</th>
                                <th>User</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                            <!-- @if (!empty($final_array)) @foreach (array_reverse($final_array) as $values)
                                    <tr>
                                        <td>{{ isset($values[0]) ? $values[0] : '' }}</td>
                                        <td>{{ isset($values[1]) ? $values[1] : '' }}</td>
                                        <td>{{ isset($values[2]) ? $values[2] : '' }}</td>
                                        <td><button class="btn-warning btn deleteIp" data-index="{{ $values[0] }}">Delete</button></td>
                                    </tr> @endforeach
                            @endif -->

                            @foreach ($usersystemips as $row)
                                <tr>
                                    <td>{{ $row->index_txt }}</td>

                                    <td>{{ $row->ip }}</td>

                                    <td>{{ $row->user_id ? $row->user->name : $row->other_user_name }}</td>

                                    <td>{{ $row->notes }}</td>

                                    <td>
                                        <button class="btn-warning btn deleteIp"
                                            data-usersystemid="{{ $row->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div id="user-task-activity" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User task activity ( Last week )</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" id="user-task-activity">
                        <table class="table fixed_header">
                            <thead>
                                <tr>
                                    <th>User name</th>
                                    <th>Task</th>
                                    <th>Tracked time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="show-list-records">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="time_history_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Estimated Time History</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="approve-time-btn" method="POST">
                    @csrf
                    <input type="hidden" name="hidden_task_type" id="hidden_task_type">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="developer_task_id" id="developer_task_id">

                            <div class="col-md-12" id="time_history_div">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                            <th>Updated by</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        @if (auth()->user()->isReviwerLikeAdmin())
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
                    <input type="hidden" id="chat_obj_type" name="chat_obj_type">
                    <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                    <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Feedback Modal -->

    <div id="today-history" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Today task history</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="time_history_div">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Task id</th>
                                        <th>Description</th>
                                        <th>From time</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody class="show-list-records">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="logMessageModel" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Task description</h4>
                </div>
                <div class="modal-body">
                    <p style="word-break: break-word;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    @include('common.commonEmailModal')
    @include("usermanagement::templates.list-template")
    @include("usermanagement::templates.create-solution-template")
    @include("usermanagement::templates.load-communication-history")
    @include("usermanagement::templates.add-role")
    @include("usermanagement::templates.add-permission")
    @include("usermanagement::templates.load-task-history")
    @include("usermanagement::templates.add-team")
    @include("usermanagement::templates.edit-team")
    @include("usermanagement::templates.add-time")
    @include("usermanagement::templates.user-avaibility")
    @include("usermanagement::templates.show-task-hours")
    @include("usermanagement::templates.show-user-details")


    <script type="text/javascript" src="/js/jsrender.min.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script type="text/javascript" src="/js/common-helper.js"></script>
    <script type="text/javascript" src="/js/user-management-list.js"></script>


    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    {{-- <script src="{{ asset('js/common-email-send.js') }}"> --}}
    {{-- </script> --}}
    {{-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnp-sp-taxonomy/1.3.11/sp-taxonomy.es5.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"
        integrity="sha512-wTIaZJCW/mkalkyQnuSiBodnM5SRT8tXJ3LkIUA/3vBJ01vWe5Ene7Fynicupjt4xqxZKXA97VgNBHvIf5WTvg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        //for feedback model
        $(document).on("click", ".feedback_btn", function() {
            $('#exampleModal123').modal('show');

        });


        //for category tag
        // $(document).on("click", ".add-feedback", function() {
        //     var textBox = $(".quick_feedback").val();
        //     const text_val = $('#addcategory').val();
        //     console.log(text_val);

        //addcategory
        //btn-save

        // });

        //for user admin chat hisrty
        $(document).on("click", "#histry", function() {
            alert('helloooo');
        });




        $(document).ready(function() {
            $('#ipusers').change(function() {
                var selected = $(this).val();
                if (selected == 'other') {
                    $('#other_user_name').show();
                } else {
                    $('#other_user_name').hide();
                }
            });
        });


        $('#due-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });



        // $(document).on("click",".permission-request",function() {
        //    $('#permission-request').modal();
        // });
        $(document).on("click", ".today-history", function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: '/user-management/today-task-history',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        console.log(result.data[0]);
                        var t = '';
                        $.each(result.data[0], function(k, v) {
                            t += `<tr><td>` + v.user_name + `</td>`;
                            t += `<td>` + v.devtaskId + `</td>`;
                            t += `<td>` + v.task + `</td>`;
                            t += `<td>` + v.date + `</td>`;
                            t += `<td>` + v.tracked + `</td></tr>`;
                        });
                        if (t == '') {
                            t = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
                        }
                        $("#today-history").find(".show-list-records").html(t);
                        $("#today-history").modal("show");
                    } else {
                        
                        toastr["error"]('No record found');
                    }
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });


        $(document).on("click", ".task-activity", function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: '/user-management/task-activity',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        console.log(result.data);
                        var t = '';
                        $.each(result.data, function(k, v) {
                            t += `<tr><td>` + v.user_name + `</td>`;
                            t += `<td>` + v.task + `</td>`;
                            t += `<td>` + v.tracked + `</td>`;
                            t += `<td>` + v.date + `</td></tr>`;
                        });
                        if (t == '') {
                            t = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
                        }
                        $("#user-task-activity").find(".show-list-records").html(t);
                        $("#user-task-activity").modal("show");
                    } else {
                        toastr["error"]('No record found');
                    }
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });

        $(document).on("click", ".permission-request", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/user-management/request-list',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        var t = '';
                        $.each(result.data, function(k, v) {
                            t += `<tr><td>` + v.name + `</td>`;
                            t += `<td>` + v.permission_name + `</td>`;
                            t += `<td>` + v.request_date + `</td>`;
                            t += `<td><button class="btn btn-secondary btn-xs permission-grant" data-type="accept" data-id="` +
                                v.permission_id + `" data-user="` + v.user_id +
                                `">Accept</button>
                                 <button class="btn btn-secondary btn-xs permission-grant" data-type="reject" data-id="` + v.permission_id + `" data-user="` + v
                                .user_id + `">Reject</button>
                              </td></tr>`;
                        });
                        if (t == '') {
                            t = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
                        }
                    }
                    $("#permission-request-model").find(".show-list-records").html(t);
                    $("#permission-request-model").modal("show");
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });

        $(document).on("click", ".erp-request", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/users/loginips',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        var t = '';
                        $.each(result.data, function(k, v) {
                            button = status = '';
                            if (v.is_active) {
                                status = 'Active';
                                button =
                                    '<button type="button" class="btn btn-warning ml-3 statusChange" data-status="Inactive" data-id="' +
                                    v.id + '">Inactive</button>';
                            } else {
                                status = 'Inactive';
                                button =
                                    '<button type="button" class="btn btn-success ml-3 statusChange" data-status="Active" data-id="' +
                                    v.id + '">Active</button>';
                            }
                            t += `<tr><td>` + v.created_at + `</td>`;
                            t += `<td>` + v.email + `</td>`;
                            t += `<td>` + v.ip + `</td>`;
                            t += `<td>` + status + `</td>`;
                            t += `<td>` + button + `</td>`;
                        });
                        if (t == '') {
                            t = '<tr><td colspan="5" class="text-center">No data found</td></tr>';
                        }
                    }
                    $("#erp-request").find(".show-list-records").html(t);
                    $("#erp-request").modal("show");
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });

        $(document).on("click", ".addIp", function(e) {
            e.preventDefault();
            if ($('input[name="add-ip"]').val() != '') {
                $.ajax({
                    url: '/users/add-system-ip',
                    type: 'GET',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ip: $('input[name="add-ip"]').val(),
                        user_id: $('#ipusers').val(),
                        other_user_name: $('input[name="other_user_name"]').val(),
                        comment: $('input[name="ip_comment"]').val()
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $("#loading-image").show();
                    },
                    success: function(result) {
                        $("#loading-image").hide();
                        toastr["success"]("IP added successfully");
                        location.reload();
                    },
                    error: function() {
                        $("#loading-image").hide();
                        toastr["Error"]("An error occured!");
                    }
                });
            } else {
                alert('please enter IP');
            }
        });
        $(document).on("click", ".deleteIp", function(e) {
            e.preventDefault();
            var btn = $(this);
            $.ajax({
                url: '/users/delete-system-ip',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    usersystemid: $(this).data('usersystemid')
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    btn.parents('tr').remove();
                    $("#loading-image").hide();
                    toastr["success"]("IP Deteted successfully");
                },
                error: function() {
                    $("#loading-image").hide();
                    toastr["Error"]("An error occured!");
                }
            });
        });

        $(document).on("click", ".permission-grant", function(e) {
            e.preventDefault();
            var permission = $(this).data('id');
            var user = $(this).data('user');
            var type = $(this).data('type');

            $.ajax({
                url: '/user-management/modifiy-permission',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    permission: permission,
                    user: user,
                    type: type
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        toastr["success"](result.data, "");
                    } else {
                        toastr["error"](result.data, "");
                    }
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });

        $(document).on("click", ".permission-delete-grant", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/user-management/request-delete',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        $("#permission-request").find(".show-list-records").html('');
                        toastr["success"](result.data, "");
                    } else {
                        toastr["error"](result.data, "");
                    }
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });

        $('.due-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        page.init({
            bodyView: $("#common-page-layout"),
            baseUrl: "<?php echo url('/'); ?>"
        });


        function editUser(id) {
            $.ajax({
                url: "/user-management/edit/" + id,
                type: "get"
            }).done(function(response) {
                $('.common-modal').modal('show');
                console.log($(".modal-dialog"));
                $(".modal-dialog").html(response);
            }).fail(function(errObj) {
                $('.common-modal').modal('hide');
            });
        }

        function payuser(id) {
            $.ajax({
                url: "/user-management/paymentInfo/" + id,
                type: "get"
            }).done(function(response) {
                if (response.code == 500) {
                    toastr['error'](response.message, 'error');
                } else {
                    $('.common-modal').modal('show');
                    console.log($(".modal-dialog"));
                    $(".modal-dialog").html(response);
                }
            }).fail(function(errObj) {
                $('.common-modal').modal('hide');
            });
        }

        $(".common-modal").on("click", ".open-payment-method", function() {
            if ($('.common-modal #permission-from').hasClass('hidden')) {
                $('.common-modal #permission-from').removeClass('hidden');
            } else {
                $('.common-modal #permission-from').addClass('hidden');
            }
        });

        $(".common-modal").on("click", ".add-payment-method", function() {
            var name = $('.common-modal #payment-method-input').val();
            console.log(name);
            if (!name) {
                return;
            }

            $.ajax({
                url: "/user-management/add-new-method",
                type: "post",
                data: {
                    name: name,
                    "_token": "{{ csrf_token() }}"
                }
            }).done(function(response) {
                $(".common-modal #payment_method").html(response);
                $('.common-modal #permission-from').addClass('hidden');
                $('.common-modal #payment-method-input').val('');
            }).fail(function(errObj) {});
        });




        let paymentMethods;

        function makePayment(userId, defaultMethod = null) {
            $('input[name="user_id"]').val(userId);

            if (defaultMethod) {
                $('#payment_method').val(defaultMethod);
            }
            filterMethods('');
            $('.dropdown input').val('');

            $("#paymentModal").modal();
        }

        function setPaymentMethods() {
            paymentMethods = $('.payment-method-option');
            console.log(paymentMethods);
        }

        $(document).ready(function() {

            adjustHeight();

            $('#payment-table').DataTable({
                "ordering": true,
                "info": false
            });

            setPaymentMethods();

            $('#payment-dropdown-wrapper').click(function() {
                event.stopPropagation();
            })

            $("#paymentModal").click(function() {
                closeDropdown();
            })
        });

        function adjustHeight() {
            $('.activity-container').each(function(index, element) {
                const childElement = $($(element).children()[0]);
                $(element).attr('data-expanded-height', childElement.height());
                $(element).height(0);
                childElement.height(0);

                setTimeout(
                    function() {
                        $(element).addClass('elastic');
                        childElement.addClass('elastic');
                        $('#payment-table').css('visibility', 'visible');
                    },
                    1
                )
            })
        }

        function toggle(id) {
            const expandableElement = $('#elastic-' + id);

            const isExpanded = expandableElement.attr('data-expanded') === 'true';


            if (isExpanded) {
                console.log('true1');
                expandableElement.height(0);
                $($(expandableElement).children()[0]).height(0);
                expandableElement.attr('data-expanded', 'false');
            } else {
                console.log('false1');
                const expandedHeight = expandableElement.attr('data-expanded-height');
                expandableElement.height(expandedHeight);
                $($(expandableElement).children()[0]).height(expandedHeight);
                expandableElement.attr('data-expanded', 'true');
            }



        }



        function filterMethods(needle) {
            console.log(needle);
            $('#payment-method-dropdown .payment-method-option').remove();

            let filteredElements = paymentMethods.filter(
                function(index, element) {
                    const optionValue = $(element).text();
                    return optionValue.toLowerCase().includes(needle.toLowerCase());
                }
            )

            filteredElements.each(function(index, element) {
                const value = $(element).text();
                if (value == $('#payment_method').val()) {
                    $(element).addClass('selected');
                } else {
                    $(element).removeClass('selected');
                }
            });

            $('#payment-method-dropdown').append(filteredElements);
        }

        function selectOption(element) {
            selectOptionWithText($(element).text());
        }

        function selectOptionWithText(text) {
            $('#payment_method').val(text);
            closeDropdown();
        }


        function toggleDropdown() {
            if ($('#payment-dropdown-wrapper').hasClass('hidden')) {
                filterMethods('');
                $('.dropdown input').val('');
                $('#payment-dropdown-wrapper').css('display', 'block !important');
                $('#payment-dropdown-wrapper').removeClass('hidden');
            } else {
                $('#payment-dropdown-wrapper').addClass('hidden');
            }
            event.stopPropagation();
        }

        function closeDropdown() {
            $('#payment-dropdown-wrapper').addClass('hidden');
        }

        function addPaymentMethod() {

            console.log('here');

            const newPaymentMethod = $('#payment-method-input').val();

            let paymentExists = false;
            $('#payment-method-dropdown .payment-method-option')
                .each(function(index, element) {
                    if ($(element).text() == newPaymentMethod) {
                        paymentExists = true;
                    }
                });

            if (paymentExists) {
                alert('Payment method exits');
                return;
            } else if (!newPaymentMethod || newPaymentMethod.trim() == '') {
                alert('Payment method required');
                return;
            }

            filterMethods('');

            $('#payment-method-dropdown').append(
                '<li onclick="selectOption(this)" class="payment-method-option">' + newPaymentMethod + '</li>'
            );

            $('#payment_method').append(
                '<option value="' + newPaymentMethod + '">' + newPaymentMethod + '</option>'
            );

            setPaymentMethods();



            selectOptionWithText(newPaymentMethod);
            event.stopPropagation();
            event.preventDefault();

            return true;
        }




        $(document).on("change", ".quickComment", function(e) {

            var message = $(this).val();

            if ($.isNumeric(message) == false) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/user-management/reply/add",
                    dataType: "json",
                    method: "POST",
                    data: {
                        reply: message
                    }
                }).done(function(data) {

                }).fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
            $(this).closest("td").find(".quick-message-field").val($(this).find("option:selected").text());

        });

        $(".select2-quick-reply").select2({
            tags: true
        });

        $(document).on("click", ".delete_quick_comment", function(e) {
            var deleteAuto = $(this).closest(".d-flex").find(".quickComment").find("option:selected").val();
            if (typeof deleteAuto != "undefined") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/user-management/reply/delete",
                    dataType: "json",
                    method: "GET",
                    data: {
                        id: deleteAuto
                    }
                }).done(function(data) {
                    if (data.code == 200) {
                        $(".quickComment").empty();
                        $.each(data.data, function(k, v) {
                            $(".quickComment").append("<option value='" + k + "'>" + v +
                                "</option>");
                        });
                        $(".quickComment").select2({
                            tags: true
                        });
                    }

                }).fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
        });

$(document).on('click','.statusChange',function(event){
        event.preventDefault();
        $.ajax({
           type: "post",
           url: '{{ action("UserController@statusChange") }}',
           data: {
             _token: "{{ csrf_token() }}",
             status: $(this).attr('data-status'),
             id: $(this).attr('data-id')
           },
           beforeSend: function() {
             $(this).attr('disabled', true);
             // $(element).text('Approving...');
           }
        }).done(function( data ) {
          toastr["success"]("Status updated!", "Message")
          window.location.reload();
        }).fail(function(response) {
           alert(response.responseJSON.message);
           toastr["error"](error.responseJSON.message);
        });
      });

        $(document).on('click', '.send-message', function() {
            var thiss = $(this);
            var data = new FormData();
            var user_id = $(this).data('userid');
            var message = $(this).siblings('input').val();

            data.append("user_id", user_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/user',
                        type: 'POST',
                        "dataType": 'json', // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function() {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function(response) {
                        // thiss.closest('tr').find('.chat_messages').html(thiss.siblings('input').val());
                        $(thiss).siblings('input').val('');

                        $(thiss).attr('disabled', false);
                    }).fail(function(errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });
        $(document).on("click", ".status-history", function(e) {
            $.ajax({
                url: '/user-management/' + $(this).data('id') + '/status-history',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(result) {
                    $("#loading-image").hide();
                    if (result.code == 200) {
                        //console.log( result.data );
                        var t = '';
                        var count = 0;
                        $.each(result.data, function(k, v) {
                            t += `<tr><td>` + parseInt(count + 1) + `</td>`;
                            t += `<td>` + v.status + `</td>`;
                            t += `<td>` + v.name + `</td>`;
                            t += `</tr>`;
                        });
                        if (t == '') {
                            t = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
                        }
                        $("#status-history").find(".show-list-records").html(t);
                        $("#status-history").modal("show");
                        //toastr["success"]('No record found');
                        //show-list-records
                        //
                    } else {
                        toastr["error"]('No record found');
                    }
                },
                error: function() {
                    $("#loading-image").hide();
                }
            });
        });
        $(document).on('click', '.flag-task', function() {
            var task_id = $(this).data('id');
            var task_type = $(this).data('task_type');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('task.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_id: task_id,
                    task_type: task_type
                },
                beforeSend: function() {
                    $(thiss).text('Flagging...');
                }
            }).done(function(response) {
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
            }).fail(function(response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag task!');

                console.log(response);
            });
        });
        $(document).on('click', '.task-send-message-btn', function() {
            var thiss = $(this);
            var data = new FormData();
            var task_id = $(this).data('id');
            // var message = $(this).siblings('input').val();
            var message = $('#getMsg' + task_id).val();
            data.append("task_id", task_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/task',
                        type: 'POST',
                        "dataType": 'json', // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function() {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function(response) {
                        $(thiss).siblings('input').val('');
                        $('#getMsg' + task_id).val('');

                        // if (cached_suggestions) {
                        //     suggestions = JSON.parse(cached_suggestions);

                        //     if (suggestions.length == 10) {
                        //         suggestions.push(message);
                        //         suggestions.splice(0, 1);
                        //     } else {
                        //         suggestions.push(message);
                        //     }
                        //     localStorage['message_suggestions'] = JSON.stringify(suggestions);
                        //     cached_suggestions = localStorage['message_suggestions'];

                        //     console.log('EXISTING');
                        //     console.log(suggestions);
                        // } else {
                        //     suggestions.push(message);
                        //     localStorage['message_suggestions'] = JSON.stringify(suggestions);
                        //     cached_suggestions = localStorage['message_suggestions'];

                        //     console.log('NOT');
                        //     console.log(suggestions);
                        // }

                        // $.post( "/whatsapp/approve/customer", { messageId: response.message.id })
                        //   .done(function( data ) {
                        //
                        //   }).fail(function(response) {
                        //     console.log(response);
                        //     alert(response.responseJSON.message);
                        //   });

                        $(thiss).attr('disabled', false);
                        toastr["success"]('Message sent successfully.');
                    }).fail(function(errObj) {
                        $(thiss).attr('disabled', false);
                        toastr["error"]('An erro occured! please try again later.');
                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });
        //
        $(document).on('click', '.load-task-modal', function() {
            setTimeout(function() {
                $('.task-modal-userid').attr('data-id', $(this).data('id'));
                $.each($('.data-status'), function(i, item) {
                    var value = $(this).data('status');
                    $.each($(this).children('option'), function(is, items) {
                        if ($(this).attr('value') == value || $(this).data('id') == value) {
                            $(this).attr('selected', 'selected');
                        }
                    })
                    //console.log($(this).data('status'));
                });
            }, 3000);

        });

        $(document).on('click', '.statusChange', function(event) {
            event.preventDefault();
            $.ajax({
                type: "post",
                url: '{{ action('UserController@statusChange') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: $(this).attr('data-status'),
                    id: $(this).attr('data-id')
                },
                beforeSend: function() {
                    $(this).attr('disabled', true);
                    // $(element).text('Approving...');
                }
            }).done(function(data) {
                toastr["success"]("Status updated!", "Message")
                window.location.reload();
            }).fail(function(response) {
                alert(response.responseJSON.message);
                toastr["error"](error.responseJSON.message);
            });
        });

        $(document).on('click','.user-feedback-status',function(){
            var status = $('#feedback-status').val();
            $('.user_feedback_status').text('');

            $.ajax({
                type: "get",
                url: '{{ route("user.feedback-status") }}',
                data: {'status':status},
                success:function(response){
                    //
                    if (response.status == true) {
                        var all_status = response.feedback_status;
                        for (let i = 0; i < all_status.length; i++) {
                            var html = '<option>'+all_status[i].status+'</option>';    
                            $('.user_feedback_status').append(html);
                        }
                    }
                }
            });
        })

        $(document).on('click','.user-feedback-modal', function(){
          var user_id = $(this).data('user_id');
            $('.user-feedback-data').data('user_id',user_id);
            $('#btn-save').data('user_id',user_id);
            
            $('.user-feedback-data').text('');

            $.ajax({
                type: "get",
                url: '{{ route("user.feedback-table-data") }}',
                data: {'user_id':user_id},
                success:function(response){
                    $('.user-feedback-data').append(response);

                }
            });

        })

        $(document).on("click", "#btn-save",function(e){
            // $('#btn-save').attr("disabled", "disabled");
            e.preventDefault();
             var category = $('#addcategory').val();
             var user_id = $(this).data('user_id');
            if(category!=""){
                console.log(category);
                 $.ajax({
                    url:"{{ route('user.feedback-category') }}",
                    type:"get",
                    data:{
                        category:category,
                        user_id:user_id,
                    },
                    cashe:false,
                    success:function(response){
                        $(document).find('.user-feedback-data').append(response);
                    }
                });
            }else{
               alert("error");
            }
         });

         $(document).on('click', '.send-message-open', function (event) {
            var textBox = $(this).closest(".communication-td").find(".send-message-textbox");
            let user_id = textBox.attr('data-id');
            let message = textBox.val();
            var feedback_cat_id = $(this).data('feedback_cat_id');
            if (message == '') {
                return;
            }

            let self = textBox;

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'user-feedback')}}",
                type: 'POST',
                data: {
                    "feedback_cat_id": feedback_cat_id,
                    "user_id": user_id,
                    "message": message,
                    "_token": "{{csrf_token()}}",
                   "status": 2
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Message sent successfully!", "Message");
                    $('#message_list_' + user_id).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });
    </script>

@endsection
