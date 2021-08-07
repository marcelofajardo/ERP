@extends('layouts.app')

@section('favicon' , 'development-issue.png')

@if($title == "devtask")
    @section('title', 'Development Issue')
@else
    @section('title', 'Development Task')
@endif

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style type="text/css">

    </style>
@endsection

<style> 
    .status-selection .btn-group {
        padding: 0;
        width: 100%;
    }
    .status-selection .multiselect {
        width : 100%;
    }
    .pd-sm {
        padding: 0px 8px !important;
    }
    tr {
        background-color: #f9f9f9;
    }
    .mr-t-5 {
        margin-top:5px !important;
    }
    /* START - Pupose : Set Loader image - DEVTASK-4359*/
    #myDiv{
        position: fixed;
        z-index: 99;
        text-align: center;
    }
    #myDiv img{
        position: fixed;
        top: 50%;
        left: 50%;
        right: 50%;
        bottom: 50%;
    }
    /* END - DEVTASK-4359*/
</style>


@section('large_content')
    <div class="row">
        <div class="col-lg-12 margin-tb">            
            <h2 class="page-heading">{{ ucfirst($title) }} ({{$issues->total()}})</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $priorities = [
          '1' => 'Critical',
          '2' => 'Urgent',
          '3' => 'Normal'
        ];
    @endphp
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;">
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            @include("development.partials.task-issue-search")
            <div class="pull-right mt-4">

                <a class="btn btn-secondary" href="{{ action('DevelopmentController@exportTask',request()->all()) }}" role="link"> Download Tasks </a>

            <a class="btn btn-secondary" 
                        data-toggle="collapse" href="#plannedFilterCount" role="button" aria-expanded="false" aria-controls="plannedFilterCount">
                           Show Planned count
            </a>
            <a class="btn btn-secondary" 
                        data-toggle="collapse" href="#inProgressFilterCount" role="button" aria-expanded="false" aria-controls="inProgressFilterCount">
                           Show In Progress count
            </a>
            <a style="color:white;" class="btn btn-secondary  priority_model_btn">Priority</a>
            @if(auth()->user()->isReviwerLikeAdmin())
                <a href="javascript:" class="btn btn-secondary" id="newTaskModalBtn" data-toggle="modal" data-target="#newTaskModal">Add New Dev Task </a>
             @endif
             @if (auth()->user()->isAdmin())
             <a class="btn btn-secondary" style="color:white;" data-toggle="modal" data-target="#newStatusModal">Create Status</a>
            @endif
            @if (auth()->user()->isAdmin())
             <a class="btn btn-secondary" style="color:white;" id="make_delete_button">Delete Tasks</a>
            @endif

        </div>


         
        </div>
    </div>
    @include("development.partials.task-issue-counter")

    <?php
        $query = http_build_query(Request::except('page'));
        $query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
    ?>

    <div class="form-group position-fixed" style="top: 50px; left: 20px;">
        Goto :
        <select onchange="location.href = this.value;" class="form-control" id="page-goto">
            @for($i = 1 ; $i <= $issues->lastPage() ; $i++ )
                <option data-value="{{$i}}" value="{{ $query.$i }}" {{ ($i == $issues->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="infinite-scroll">
        <div >
        @if($title == 'issue' && auth()->user()->isReviwerLikeAdmin())
        <table class="table table-bordered table-striped">
            <tr class="add-new-issue">
                @include("development.partials.add-new-issue")
            </tr>
        </table>
        @endif
        <div class="infinite-scroll-products-inner">
            @include("development.partials.task-master")
        </div>
            <?php echo $issues->appends(request()->except("page"))->links(); ?>

            <img class="infinite-scroll-products-loader center-block" src="/images/loading.gif" alt="Loading..." style="display: none" />
        </div>
    </div>
    @include("development.partials.create-new-module")
    @include("development.partials.assign-issue-modal")
    @include("development.partials.assign-priority-modal")
    @include("development.partials.chat-list-history-modal")
    @include("development.partials.upload-document-modal")
    @include("partials.plain-modal")
    @include("development.partials.time-history-modal")
    @include("development.partials.meeting-time-modal")
    @include("development.partials.time-tracked-modal")
    @include("development.partials.add-status-modal")
    @include("development.partials.user_history_modal")
    @include("development.partials.lead_time-history-modal")
    @include("development.partials.development-reminder-modal")
@endsection
@section('scripts')
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/jquery.jscroll.min.js"></script>
    <script src="/js/bootstrap-multiselect.min.js"></script>
    <script src="/js/bootstrap-filestyle.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#development_reminder_from').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });

            var developmentToRemind = null
            $(document).on('click', '.development-set-reminder', function () {
                let developmentId = $(this).data('id');
                let frequency = $(this).data('frequency');
                let message = $(this).data('reminder_message');
                let reminder_from = $(this).data('reminder_from');
                let reminder_last_reply = $(this).data('reminder_last_reply');

                $('#frequency').val(frequency);
                $('#reminder_message').val(message);
                $("#developmentReminderModal").find("#development_reminder_from").val(reminder_from);
                if(reminder_last_reply == 1) {
                    $("#developmentReminderModal").find("#reminder_last_reply").prop("checked",true);
                }else{
                    $("#developmentReminderModal").find("#reminder_last_reply_no").prop("checked",true);
                }
                developmentToRemind = developmentId;
            });

            $(document).on('click', '.development-submit-reminder', function () {
                var developmentReminderModal = $("#developmentReminderModal");
                let frequency = $('#frequency').val();
                let message = $('#reminder_message').val();
                let development_reminder_from = developmentReminderModal.find("#development_reminder_from").val();
                let reminder_last_reply = (developmentReminderModal.find('#reminder_last_reply').is(":checked")) ? 1 : 0;

                $.ajax({
                    url: "{{action('DevelopmentController@updateDevelopmentReminder')}}",
                    type: 'POST',
                    success: function () {
                        toastr['success']('Reminder updated successfully!');
                        $(".set-reminder img").css("background-color", "");
                        if(frequency > 0)
                        {
                            $(".development-set-reminder img").css("background-color", "red");
                        }
                    },
                    data: {
                        development_id: developmentToRemind,
                        frequency: frequency,
                        message: message,
                        reminder_from: development_reminder_from,
                        reminder_last_reply: reminder_last_reply,
                        _token: "{{ csrf_token() }}"
                    }
                });
            });


            var isLoadingProducts = false;
            $(document).on('click', '.assign-issue-button', function () {
                var issue_id = $(this).data('id');
                var url = "{{ url('development') }}/" + issue_id + "/assignIssue";

                $('#assignIssueForm').attr('action', url);
            });

            $(".multiselect").multiselect({
                allSelectedText: 'All',
                includeSelectAllOption: true
            });

            $(window).scroll(function() {
                if ( ( $(window).scrollTop() + $(window).outerHeight() ) >= ( $(document).height() - 2500 ) ) {
                    loadMoreProducts();
                }
            });

            function loadMoreProducts() {
                if (isLoadingProducts)
                    return;
                isLoadingProducts = true;
                if(!$('.pagination li.active + li a').attr('href'))
                return;

                var $loader = $('.infinite-scroll-products-loader');
                $.ajax({
                    url: $('.pagination li.active + li a').attr('href'),
                    type: 'GET',
                    beforeSend: function() {
                        $loader.show();
                        $('ul.pagination').remove();
                    }
                })
                .done(function(data) {
                    // console.log(data);
                    if('' === data.trim())
                        return;

                    $loader.hide();

                    $('.infinite-scroll-products-inner').append(data);

                    isLoadingProducts = false;
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.error('something went wrong');

                    isLoadingProducts = false;
                });
            }

            // $('.infinite-scroll').jscroll({
            //     debug: false,
            //     autoTrigger: true,
            //     loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            //     padding: 20,
            //     nextSelector: '.pagination li.active + li a',
            //     contentSelector: '.infinite-scroll',
            //     callback: function () {
            //         $('ul.pagination:visible:first').remove();
            //         var next_page = $('.pagination li.active');
            //         if (next_page.length > 0) {
            //             var current_page = next_page.find("span").html();
            //             $('#page-goto option[data-value="' + current_page + '"]').attr('selected', 'selected');
            //         }
            //         $.each($("select.resolve-issue"),function(k,v){
            //             if (!$(v).hasClass("select2-hidden-accessible")) {
            //                 $(v).select2({width:"100%", tags:true});
            //             }
            //         });
            //         $('select.select2').select2({
            //             tags: true,
            //             width: "100%"
            //         });
            //     }
            // });
            
            $('select.select2').select2({
                tags: true,
                width: "100%"
            });


            $('.assign-team-lead.select2').select2({
                width: "100%"
            });

            $('.assign-tester.select2').select2({
                width: "100%"
            });

            $('.assign-master-user.select2').select2({
                width: "100%"
            });

            $('.assign-user.select2').select2({
                width: "100%"
            });

            $.each($(".resolve-issue"),function(k,v){
                if (!$(v).hasClass("select2-hidden-accessible")) {
                    $(v).select2({width:"100%"});
                }
            });

            $('select#priority_user_id').select2({
                tags: true,
                width: '100%'
            });

            $('.estimate-time').datetimepicker({
                format: 'HH:mm'
            });

            $(".estimate-date").each(function() {
                // ...
                $(this).datepicker({
                    dateformat: 'yyyy-mm-dd'
                });
            });
      
            $('#estimate_date_picker').datepicker({
                dateformat: 'yyyy-mm-dd'
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
                url: "{{route('development.issue.list.by.user.id')}}",
                type: 'POST',
                data: {
                    user_id: id,
                    _token: "{{csrf_token()}}",
                    selected_issue: selected_issue,
                },
                success: function (response) {
                    var html = '';
                    response.forEach(function (issue) {
                        html += '<tr>';
                        html += '<td><input type="hidden" name="priority[]" value="' + issue.id + '">' + issue.id + '</td>';
                        html += '<td>' + issue.module + '</td>';
                        html += '<td>' + issue.subject + '</td>';
                        html += '<td>' + issue.task + '</td>';
                        html += '<td>' + issue.submitted_by + '</td>';
                        html += '<td><a href="javascript:;" class="delete_priority" data-id="' + issue.id + '">Remove<a></td>';
                        html += '</tr>';
                    });
                    $(".show_issue_priority").html(html);
                    <?php if (auth()->user()->isAdmin()) { ?>
                    $(".show_issue_priority").sortable();
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
            $("#priority_user_id").val('');
            $(".show_task_priority").html('');
            <?php if (auth()->user()->isAdmin()) { ?>
                $("#priority_user_id").show();
                getPriorityTaskList($('#priority_user_id').val());
            <?php } else { ?>
                $("#priority_user_id").hide();
                getPriorityTaskList('{{auth()->user()->id}}');
            <?php } ?>
            $('#priority_model').modal('show');
        });

        $('#priority_user_id').change(function () {
            getPriorityTaskList($(this).val())
        });

        $(document).on('submit', '#priorityForm', function (e) {
            e.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
            $.ajax({
                url: "{{route('development.issue.set.priority')}}",
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
        
        $(document).on('click', '.send-message', function (event) {

            var textBox = $(this).closest(".panel-footer").find(".send-message-textbox");
            var sendToStr  = $(this).closest(".panel-footer").find(".send-message-number").val();


            let issueId = textBox.attr('data-id');
            let message = textBox.val();
            if (message == '') {
                return;
            }

            let self = textBox;

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'issue')}}",
                type: 'POST',
                data: {
                    "issue_id": issueId,
                    "message": message,
                    "sendTo" : sendToStr,
                    "_token": "{{csrf_token()}}",
                   "status": 2
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Message sent successfully!", "Message");
                    $('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
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



        $(document).on('click', '.send-message-open', function (event) {
            var textBox = $(this).closest(".expand-row").find(".send-message-textbox");
            var sendToStr  = $(this).closest(".expand-row").find(".send-message-number").val();
            var add_autocomplete  = $(this).closest(".expand-row").find("[name=add_to_autocomplete]").is(':checked') ;
            
            let issueId = textBox.attr('data-id');
            let message = textBox.val();
            if (message == '') {
                return;
            }

            let self = textBox;

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'issue')}}",
                type: 'POST',
                data: {
                    "issue_id": issueId,
                    "message": message,
                    "sendTo" : sendToStr,
                    "_token": "{{csrf_token()}}",
                   "status": 2,
                   "add_autocomplete": add_autocomplete
                },
                dataType: "json",
                success: function (response) {
                    $("#loading-image").hide();//Purpose : Hide loader - DEVTASK-4359
                    toastr["success"]("Message sent successfully!", "Message");
                    if(response.message) {
                        var created_at = response.message.created_at;
                        var message = response.message.message;
                    }
                    else {
                        var created_at = '';
                        var message = '';
                    }
                    $('#message_list_' + issueId).append('<li>' + created_at + " : " + message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                },
                beforeSend: function () {
                    $("#loading-image").show();//Purpose : Show loader - DEVTASK-4359
                    $(self).attr('disabled', true);
                },
                error: function () {
                    $("#loading-image").hide();//Purpose : Hide loader - DEVTASK-4359
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });

        $(document).on('change', '.set-responsible-user', function () {
            let id = $(this).attr('data-id');
            let userId = $(this).val();

            if (userId == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@assignResponsibleUser')}}",
                data: {
                    responsible_user_id: userId,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("User assigned successfully!", "Message")
                }
            });
        });
        $(document).on('change', '.assign-user', function () {
            let id = $(this).attr('data-id');
            let userId = $(this).val();

            if (userId == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@assignUser')}}",
                data: {
                    assigned_to: userId,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("User assigned successfully!", "Message")
                },   
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });

        });

        $(document).on('change', '.task-module', function () {
            let id = $(this).attr('data-id');
            let moduleID = $(this).val();

            if (moduleID == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@changeModule')}}",
                data: {
                    module_id: moduleID,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Module assigned successfully!", "Message")
                }
            });

        });

        $(document).on('change', '.assign-master-user', function () {
            let id = $(this).attr('data-id');
            let userId = $(this).val();

            if (userId == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@assignMasterUser')}}",
                data: {
                    master_user_id: userId,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Master User assigned successfully!", "Message")
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });

        });

        

        $(document).on('keyup', '.save-cost', function (event) {
            if (event.keyCode != 13) {
                return;
            }
            let id = $(this).attr('data-id');
            let amount = $(this).val();

            $.ajax({
                url: "{{action('DevelopmentController@saveAmount')}}",
                data: {
                    cost: amount,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Price updated successfully!", "Message")
                }
            });
        });



        $(document).on('keyup', '.save-milestone', function (event) {
            if (event.keyCode != 13) {
                return;
            }
            let id = $(this).attr('data-id');
            let total = $(this).val();

            $.ajax({
                url: "{{action('DevelopmentController@saveMilestone')}}",
                data: {
                    total: total,
                    issue_id: id
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

        $(document).on('change', '.save-language', function (event) {
            
            let id = $(this).attr('data-id');
            let language = $(this).val();

            $.ajax({
                url: "{{action('DevelopmentController@saveLanguage')}}",
                data: {
                    language: language,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Language updated successfully!", "Message")
                }
            });
        });

        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        $(document).on('keyup', '.estimate-time-change', function () {
            if (event.keyCode != 13) {
                return;
            }
            let issueId = $(this).data('id');
            let estimate_minutes = $("#estimate_minutes_" + issueId).val();
            $.ajax({
                url: "{{action('DevelopmentController@saveEstimateMinutes')}}",
                data: {
                    estimate_minutes: estimate_minutes,
                    issue_id: issueId
                },
                success: function () {
                    toastr["success"]("Estimate Minutes updated successfully!", "Message");
                }
            });

        });
        $(document).on('keyup', '.lead-estimate-time-change', function (event) {
            if (event.keyCode == 13) {
                return;
            }
            let issueId = $(this).data('id');
            let lead_estimate_minutes = $("#lead_estimate_minutes_" + issueId).val();
            $.ajax({
                url: "{{action('DevelopmentController@saveLeadEstimateTime')}}",
                data: {
                    lead_estimate_minutes: lead_estimate_minutes,
                    issue_id: issueId
                },
                success: function () {
                    toastr["success"]("Lead estimate Minutes updated successfully!", "Message");
                }
            });

        });

        $(document).on('keyup', '.estimate-date-update', function () {
            if (event.keyCode != 13) {
                return;
            }
            let issueId = $(this).data('id');
            alert(issueId);
            let estimate_date_ = $("#estimate_date_" + issueId).val();
            $.ajax({
                url: "{{action('DevelopmentController@saveEstimateDate')}}",
                data: {
                    estimate_date : estimate_date_,
                    issue_id: issueId
                },
                success: function () {
                    toastr["success"]("Estimate Date updated successfully!", "Message");
                }
            });

        });

        $(document).on('click', '.show-time-history', function() {
            var data = $(this).data('history');
            var userId = $(this).data('userid'); 
            var issueId = $(this).data('id');
            $('#time_history_div table tbody').html('');

            const hasText = $(this).siblings('input').val()

            if(!hasText){
                $('#time_history_modal .revise_btn').prop('disabled', true);
                $('#time_history_modal .remind_btn').prop('disabled', false);
            }

            $.ajax({
                url: "{{ route('development/time/history') }}",
                data: {id: issueId, user_id: userId},
                success: function (data) {
                    if(data != 'error') {
                        $('input[name="developer_task_id"]').val(issueId);
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#time_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td>\<td>'+item['name']+'</td>\
                                    <td><input type="radio" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );  
                        });
                        $('#time_history_div table tbody').append(
                            '<input type="hidden" name="user_id" value="'+userId+'" class=" "/>'
                        );
                    }
                    $('#time_history_modal').modal('show');
                }
            }); 
        });

        $(document).on('click', '.remind_btn', function() {
            var issueId = $('#approve-time-btn input[name="developer_task_id"]').val(); 
            var userId = $('#approve-time-btn input[name="user_id"]').val();  

            $('#time_history_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/time/history/approve/sendRemindMessage') }}",
                type: 'POST',
                data: {id: issueId, user_id: userId, _token: '{{csrf_token()}}' },
                success: function (data) {
                    toastr['success'](data.message, 'success');
                }
            });
            $('#time_history_modal').modal('hide');
        });

        $(document).on('click', '.revise_btn', function() {
            var issueId = $('#approve-time-btn input[name="developer_task_id"]').val(); 
            var userId = $('#approve-time-btn input[name="user_id"]').val();  

            $('#time_history_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/time/history/approve/sendMessage') }}",
                type: 'POST',
                data: {id: issueId, user_id: userId, _token: '{{csrf_token()}}' },
                success: function (data) {
                    toastr['success'](data.message, 'success');
                }
            });
            $('#time_history_modal').modal('hide');
        });

        $(document).on('click', '.show-lead-time-history', function() {
            var data = $(this).data('history');
            var issueId = $(this).data('id');
            $('#lead_time_history_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/lead/time/history') }}",
                data: {id: issueId},
                success: function (data) {

                    if(data != 'error') {
                        $("#lead_developer_task_id").val(issueId);
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = '';
                            }
                            $('#lead_time_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td>\<td>'+item['name']+'</td><td><input type="radio" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
            $('#lead_time-history-modal').modal('show');
        });

        $(document).on('click', '.show-date-history', function() {
            var data = $(this).data('history');
            var issueId = $(this).data('id');
            $('#date_history_modal table tbody').html('');
            $.ajax({
                url: "{{ route('development/date/history') }}",
                data: {id: issueId},
                success: function (data) {
                    console.log(data);
                    if(data != 'error') {
                        $("#developer_task_id").val(issueId);
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#date_history_modal table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td>\<td>'+item['name']+'</td><td><input type="radio" name="approve_date" value="'+item['id']+'" '+checked+' class="approve_date"/></td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
            $('#date_history_modal').modal('show');
        });

        $(document).on('click', '.show-status-history', function() {
            var data = $(this).data('history');
            var issueId = $(this).data('id');
            $('#status_history_modal table tbody').html('');
            $.ajax({
                url: "{{ route('development/status/history') }}",
                data: {id: issueId},
                success: function (data) {
                    if(data != 'error') {
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#status_history_modal table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td>\
                                    <td>'+item['name']+'</td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
            $('#status_history_modal').modal('show');
        });



        $(document).on('submit', '#approve-time-btn', function(event) {
            event.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
            $.ajax({
                url: "{{route('development/time/history/approve')}}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#time_history_modal').modal('hide');
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message);
                }
            });
            <?php } ?>
       
        });

        $(document).on('submit', '#approve-date-btn', function(event) {
            event.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
            $.ajax({
                url: "{{route('development/date/history/approve')}}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#time_history_modal').modal('hide');
                },
                error: function () {
                    toastr["error"](error.responseJSON.message);
                }
            });
            <?php } ?>
       
        });

        $(document).on('submit', '#approve-lead-date-btn', function(event) {
            event.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
            $.ajax({
                url: "{{route('development/lead/time/history/approve')}}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#lead_time-history-modal').modal('hide');
                },
                error: function () {
                    toastr["error"](error.responseJSON.message);
                }
            });
            <?php } ?>

        });

        

        function humanizeDuration(input, units ) { 
            // units is a string with possible values of y, M, w, d, h, m, s, ms
            var duration = moment().startOf('day').add(units, input),
                format = "";

            if(duration.hour() > 0){ format += "H:"; }

            if(duration.minute() > 0){ format += "m:"; }

            format += "s";

            return duration.format(format);
        }


        $(document).on('click', '.show-tracked-history', function() {
            var issueId = $(this).data('id');
            var type = $(this).data('type');
            $('#time_tracked_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/tracked/history') }}",
                data: {id: issueId,type:type},
                success: function (data) {
                    if(data != 'error') {
                        $.each(data.histories, function(i, item) {
                            var sec = parseInt(item['total_tracked']);
                            $('#time_tracked_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD-MM-YYYY') +'</td>\
                                    <td>'+ ((item['name'] != null) ? item['name'] : '') +'</td>\
                                    <td>'+humanizeDuration(sec,'s')+'</td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
            $('#time_tracked_modal').modal('show');
        });
        $(document).on('click','.download',function(event){
            event.preventDefault();
            document.getElementById('download').value = 2;
            $('form.search').submit();
            
        });

        $(document).on('click', '.create-hubstaff-task', function() {
            var issueId = $(this).data('id');
            var type = $(this).data('type');
            $(this).css('display','none');
            $.ajax({
                url: "{{ route('development/create/hubstaff_task') }}",
                type: 'POST',
                data: {id: issueId,type:type,_token: "{{csrf_token()}}"},
                beforeSend: function () {
                    $("#loading-image").show();
                },
                success: function (data) {
                    
                    toastr['success']('created successfully!');
                    $("#loading-image").hide();
                },
                error: function (error) {
                    $("#loading-image").hide();
                    toastr["error"](error.responseJSON.message);
                }
            });
        });

        $(document).on('change', '.change-task-status', function () {
            var taskId = $(this).data("id");
            var status = $(this).val();
            $.ajax({
                url: "{{ action('DevelopmentController@changeTaskStatus') }}",
                type: 'POST',
                data: {
                    task_id: taskId,
                    _token: "{{csrf_token()}}",
                    status: status
                },
                success: function () {
                    toastr['success']('Status Changed successfully!')
                }
            });
        });

        function sendImage(id) {

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'issue')}}",
                type: 'POST',
                data: {
                    issue_id: id,
                    type: 1,
                    message: '',
                    _token: "{{csrf_token()}}",
                    status: 2
                },
                success: function () {
                    toastr["success"]("Message sent successfully!", "Message");

                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });

        }

        function sendUploadImage(id) {

            $('#file-input' + id).trigger('click');

            $('#file-input' + id).change(function () {
                event.preventDefault();
                let image_upload = new FormData();
                let TotalImages = $(this)[0].files.length;  //Total Images
                let images = $(this)[0];

                for (let i = 0; i < TotalImages; i++) {
                    image_upload.append('images[]', images.files[i]);
                }
                image_upload.append('TotalImages', TotalImages);
                image_upload.append('status', 2);
                image_upload.append('type', 2);
                image_upload.append('issue_id', id);
                if (TotalImages != 0) {

                    $.ajax({
                        method: 'POST',
                        url: "{{action('WhatsAppController@sendMessage', 'issue')}}",
                        data: image_upload,
                        async: true,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#loading-image").show();
                        },
                        success: function (images) {
                            $("#loading-image").hide();
                            alert('Images send successfully');
                        },
                        error: function () {
                            console.log(`Failed`)
                        }
                    })
                }
            })
        }

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
                    console.log(resp);
                    if (resp.status == 'ok') {
                        $("body").append(resp.html);
                        $('#newTaskModal').modal('show');
                        $('select.select2').select2({tags: true});
                    }
                }
            });
        });

        function resolveIssue(obj, task_id) {
            let id = task_id;
            let status = $(obj).val();
            let self = this;

            $.ajax({
                url: "{{action('DevelopmentController@resolveIssue')}}",
                data: {
                    issue_id: id,
                    is_resolved: status,
                },
                success: function () {
                    toastr["success"]("Status updated!", "Message")
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message);
                }
            });
        }

        console.log($('#filecount'));

        $('#filecount').filestyle({htmlIcon: '<span class="oi oi-random"></span>',badge: true, badgeName: "badge-danger"});

        $(document).on("click",".upload-document-btn",function() {
            var id = $(this).data("id");
            $("#upload-document-modal").find("#hidden-identifier").val(id);    
            $("#upload-document-modal").modal("show");
        });

        $(document).on("submit","#upload-task-documents",function(e) {
            e.preventDefault();
            var form = $(this);
            var postData = new FormData(form[0]);
            $.ajax({
                method : "post",
                url: "{{action('DevelopmentController@uploadDocument')}}",
                data: postData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if(response.code == 200) {
                        toastr["success"]("Status updated!", "Message")
                        $("#upload-document-modal").modal("hide");
                    }else{
                        toastr["error"](response.error, "Message");
                    }
                }
            });
        });

        $(document).on("click",".list-document-btn",function() {
            var id = $(this).data("id");
            $.ajax({
                method : "GET",
                url: "{{action('DevelopmentController@getDocument')}}",
                data: {id : id},
                dataType: "json",
                success: function (response) {
                    if(response.code == 200) {
                        $("#blank-modal").find(".modal-title").html("Document List");
                        $("#blank-modal").find(".modal-body").html(response.data);
                        $("#blank-modal").modal("show");
                    }else{
                        toastr["error"](response.error, "Message");
                    }
                },
                error: function (error) {
                    toastr["error"]('Unauthorized permission development-get-document', "Message")
                    
                }
            });
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

        $(document).on("click","#make_delete_button",function() {
            if (selected_tasks.length > 0) {
                var x = window.confirm("Are you sure you want to bin these tasks");
                if(!x) {
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "{{action('DevelopmentController@deleteBulkTasks')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        selected_tasks: selected_tasks
                    }
                }).done(function (response) {
                    location.reload();
                }).fail(function (response) {
                    console.log(response);

                    alert('Could not delete tasks');
                });
            } else {
                alert('Please select atleast 1 task!');
            }
        });

        $(document).on('change', '.assign-team-lead', function () {
            let id = $(this).attr('data-id');
            let userId = $(this).val();
            console.log(id);
            console.log(userId);

            if (userId == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@assignTeamlead')}}",
                data: {
                    team_lead_id: userId,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Team lead assigned successfully!", "Message")
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });
        });

        $(document).on('change', '.assign-tester', function () {
            let id = $(this).attr('data-id');
            let userId = $(this).val();
            console.log(id);
            console.log(userId);

            if (userId == '') {
                return;
            }

            $.ajax({
                url: "{{action('DevelopmentController@assignTester')}}",
                data: {
                    tester_id: userId,
                    issue_id: id
                },
                success: function () {
                    toastr["success"]("Tester assigned successfully!", "Message")
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });
        });
        var task_id = 0;
        $(document).on('click', '.meeting-timing-popup', function () {
            let id = $(this).attr('data-id');
            let type = $(this).attr('data-type');
            $('#meeting_time_div table tbody').html('');
            $.ajax({
                url: "{{action('DevelopmentController@getMeetingTimings')}}",
                data: {
                    type: type,
                    issue_id: id
                },
                success: function (response) {
                    task_id = response.issue_id;
                    var developerTime = response.developerTime;
                    var master_devTime = response.master_devTime;
                    var testerTime = response.testerTime;
                    $("#hidden_issue_id").val(task_id);
                    $("#developer_task_id").val(task_id);
                    $("#developer_approved_time").html(developerTime);
                    $("#master_approved_time").html(master_devTime);
                    $("#tester_approved_time").html(testerTime);

                    $.each(response.timings, function(i, item) {
                            if(item['approve'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#meeting_time_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ item['type'] +'</td>\
                                    <td>'+ item['name'] +'</td>\
                                    <td>'+ ((item['old_time'] != null) ? item['old_time'] : '-') +'</td>\
                                    <td>'+ ((item['time'] != null) ? item['time'] : '-') +'</td>\
                                    <td>'+ item['updated_by'] +'</td>\
                                    <td>'+ item['note'] +'</td>\
                                    </td><td><input type="checkbox" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );
                        });
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });
            $("#meeting_time_modal").modal("show");
            $("#meeting_hidden_task_id").val(id);
            $("#hidden_type").val(type);
        });
        $(document).on('submit', '#search-time-form', function () {
            event.preventDefault();
            var type = $("#user_type_id").val();
            var timing_type = $("#timing_type_id").val();
            $('#meeting_time_div table tbody').html('');
            console.log(task_id);
            $.ajax({
                url: "{{action('DevelopmentController@getMeetingTimings')}}",
                data: {
                    type: type,
                    issue_id: task_id,
                    timing_type : timing_type
                },
                success: function (response) {
                    task_id = response.issue_id;
                    var developerTime = response.developerTime;
                    var master_devTime = response.master_devTime;
                    var testerTime = response.testerTime;
                    $("#hidden_issue_id").val(task_id);
                    $("#developer_task_id").val(task_id);
                    $("#developer_approved_time").val(developerTime);
                    $("#master_approved_time").val(master_devTime);
                    $("#tester_approved_time").val(testerTime);
                    $.each(response.timings, function(i, item) {
                            if(item['approve'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#meeting_time_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ item['type'] +'</td>\
                                    <td>'+ item['name'] +'</td>\
                                    <td>'+ ((item['old_time'] != null) ? item['old_time'] : '-') +'</td>\
                                    <td>'+ ((item['time'] != null) ? item['time'] : '-') +'</td>\
                                    <td>'+ item['updated_by'] +'</td>\
                                    <td>'+ item['note'] +'</td>\
                                    </td><td><input type="checkbox" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );
                        });
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });
            $("#meeting_time_modal").modal("show");
            $("#hidden_type").val(type);
        });

        

        $(document).on('submit', '#add-time-form', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{route('development/time/meeting/store')}}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully done', 'success');
                    $('#meeting_time_modal').modal('hide');
                    $("#add-time-form").trigger('reset');
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message);
                }
            });
       
        });

        $(document).on('submit', '#approve-meeting-time-btn', function(event) {
            event.preventDefault();
            <?php if (auth()->user()->isAdmin()) { ?>
            $.ajax({
                url: "/development/time/meeting/approve/"+task_id,
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#meeting_time_modal').modal('hide');
                },
                error: function () {
                    toastr["error"](error.responseJSON.message);
                }
            });
            <?php } ?>
        });


        $(document).on('click', '.show-user-history', function() {
            var issueId = $(this).data('id');
            $('#user_history_div table tbody').html('');
            $.ajax({
                url: "{{ route('development/user/history') }}",
                data: {id: issueId},
                success: function (data) {
                    
                    $.each(data.users, function(i, item) {
                            $('#user_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['user_type'] != null) ? item['user_type'] : '-') +'</td>\
                                    <td>'+ ((item['old_name'] != null) ? item['old_name'] : '-') +'</td>\
                                    <td>'+ ((item['new_name'] != null) ? item['new_name'] : '-') +'</td>\
                                    <td>'+ item['updated_by']  +'</td>\
                                </tr>'
                            );
                        });
                }
            });
            $('#user_history_modal').modal('show');
        });
    </script>
@endsection
