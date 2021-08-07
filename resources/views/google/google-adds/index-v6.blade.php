@extends('layouts.app')

@section('favicon' , 'task.png')

@section('title', 'Tasks')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <style>
        #message-wrapper {
            height: 450px;
            overflow-y: scroll;
        }
        .dis-none {
            display: none;
        }
        .pd-5 {
            padding:3px;
        }
        .cls_task_detailstextarea{
            height: 30px !important;
        }
        .cls_remove_allpadding{
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
        .cls_right_allpadding{
            padding-right: 0px !important;
        }
        .cls_left_allpadding{
            padding-left: 0px !important;
        }
        #addNoteButton{
            margin-top: 2px;
        }
        #saveNewNotes{
            margin-top: 2px;
        }
        .col-xs-12.col-md-2{
            padding-left:5px !important; 
            padding-right:5px !important;
            height: 38px;
        }
        .cls_task_subject{
            padding-left: 9px;
        }
        #recurring-task .col-xs-12.col-md-6{
            padding-left:5px !important; 
            padding-right:5px !important;
        }
        #appointment-container .col-xs-12.col-md-6{
            padding-left:5px !important; 
            padding-right:5px !important;
        }
        #taskCreateForm .form-group{
            margin-bottom: 0px;
        }
        .cls_action_box .btn-image img{
            width: 12px !important;
        }
        .cls_action_box .btn.btn-image {
            padding: 2px;
        }
        .btn.btn-image {
            padding: 5px 3px;
        }
        .td-mini-container {
            margin-top: 9px;
        }
        .td-full-container{
            margin-top: 9px;   
        }
        .cls_textbox_notes{
            width: 100% !important;
        }
        .cls_multi_contact .btn-image img {
            width: 12px !important;
        }
        .cls_multi_contact{
            width: 100%;
        }
        .cls_multi_contact_first{
            width: 80%;
            display: inline-block;
        }
        .cls_multi_contact_second{
            width: 7%;
            display: inline-block;
        }
        .cls_categoryfilter_box .btn-image img {
            width: 12px !important;
        }
        .cls_categoryfilter_box{
            width: 100%;
        }
        .cls_categoryfilter_first{
            width: 80%;
            display: inline-block;
        }
        .cls_categoryfilter_second{
            width: 7%;
            display: inline-block;
        }
        .cls_comm_btn {
            margin-left: 3px;
            padding: 4px 8px;
        }
        .btn.btn-image.btn-call-data {
            margin-top: -9px;
        }
        .dis-none {
            display: none;
        }
        .no-due-date {
            background-color: #f1f1f1 !important;
        }
        .over-due-date {
            background-color: #777 !important;
            color:white;
        }
        .over-due-date .btn {
            background-color: #777 !important;
        }
        .over-due-date .btn .fa {
            color: black !important;
        }
        .no-due-date .btn {
            background-color: #f1f1f1 !important;
        }
        .pd-2 {
            padding:2px;
        }
        .zoom-img:hover {
            -ms-transform: scale(1.5); /* IE 9 */
            -webkit-transform: scale(1.5); /* Safari 3-8 */
            transform: scale(1.5); 
        }
    </style>
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="page-heading">Google Keyword Search</h2>
        </div>
    </div>
    <!--- Pre Loader -->
    <img src="/images/pre-loader.gif" id="Preloader" style="display:none;"/>

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-xs-12">
            <form class="form-search-data">
                <input type="hidden" name="daily_activity_date" value="">
                <input type="hidden" name="type" id="tasktype" value="pending">
                <div class="row">
                    <div class="col-xs-12 col-md-3 pd-2">
                        <div class="form-group cls_task_subject">
                            <input type="text" name="term" placeholder="Search Keyword" id="keyword_search" class="form-control input-sm" value="">
                        </div>
                    </div>
                    <!-- Location for which we are searching keyword for  -->
                    <div class="col-xs-12 col-md-3 pd-2">
                        <div class="form-group cls_task_subject">
                            <select name="keyword_location" id="keyword_location" class="form-control input-sm">
                                <option value="">Search by Location</option>
                                @foreach ($locations ?? [] as $location)
                                    <option value="{{ $location['code'] }}">{{ $location['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Language Selection -->
                    <div class="col-xs-12 col-md-3 pd-2">
                        <div class="form-group">
                            <select name="keyword_language" id="keyword_language" class="form-control input-sm">
                                <option value="">Search by Language</option>
                                @foreach ($languages ?? [] as $language)
                                    <option value="{{ $language['criterion_id'] }}">{{ $language['language_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Search Network -->
                </div>
                <!-- FILTERS -->
            </form>
        </div>
        <div class="col-md-4 pb-2">
            <a href="?reauth=true" class="btn btn-info "> Refresh Auth token </a>
        </div>
    </div>

    <?php
    if (\App\Helpers::getadminorsupervisor() && !empty($selected_user))
        $isAdmin = true;
    else
        $isAdmin = false;
    ?>
    

    @if(auth()->user()->isAdmin())
        <div class="row">
            <div class="col-md-12">
                <div class="collapse" id="openFilterCount">
                    <div class="card card-body">
                    </div>
                </div>
            </div>    
        </div>
    @endif    

    <div id="exTab2" style="overflow: auto">
        <ul class="nav nav-tabs">
            <!-- <li class="active"><a href="#1" data-toggle="tab" class="btn-call-data" data-type="pending">Pending Task</a></li>
            <li><a href="#2" data-toggle="tab" class="btn-call-data" data-type="statutory_not_completed">Statutory Activity</a></li>
            <li><a href="#3" data-toggle="tab" class="btn-call-data" data-type="completed">Completed Task</a></li>
            <li><a href="#unassigned-tab" data-toggle="tab">Unassigned Messages</a></li>
            <li><button type="button" class="btn btn-xs btn-secondary my-3" id="view_tasks_button" data-selected="0">View Tasks</button></li>&nbsp;
            <li><button type="button" class="btn btn-xs btn-secondary my-3" id="view_categories_button">Categories</button></li>&nbsp;
            <li><button type="button" class="btn btn-xs btn-secondary my-3" id="make_complete_button">Complete Tasks</button></li>&nbsp;
            <li><button type="button" class="btn btn-xs btn-secondary my-3" id="make_delete_button">Delete Tasks</button></li>&nbsp; -->
        </ul>
        <div class="tab-content ">
            <!-- Pending task div start -->
            <div class="tab-pane active" id="1">
                <div class="row" style="margin:0px;"> 
                    <!-- <h4>List Of Pending Tasks</h4> -->
                    <div class="col-12">
                        <table class="table table-sm table-bordered" id="keyword_table">
                            <thead>
                            <tr>
                                <th>Kewords</th>
                                <th>Avg. monthly searches</th>
                                <th>Competition</th>
                                <th>Top of page bid (low range)</th>
                                <th>Top of page bid (high range)</th>
                                <!-- <th>Category Ids</th> -->
                            </tr>
                            </thead>
                            <tbody class="pending-row-render-view infinite-scroll-pending-inner" id="keyword_table_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
              50% 50% no-repeat;display:none;">
    </div>
    @include("development.partials.time-history-modal")
    @include("task-module.partials.tracked-time-history")
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script>
        $(document).ready(function(){
            var keywordSearch = null;
            $(document).on('keyup','#keyword_search',function(){
                var networkCheck = true;

                keywordSearch = $.ajax({
                    url: "{{route('google-keyword-search-v6')}}",
                    type: 'GET',
                    data: {
                        keyword:$('#keyword_search').val(),
                        location:$('#keyword_location').val(),
                        language:$('#keyword_language').val(),
                    },
                    beforeSend : function(){
                        if(keywordSearch != null) {
                            keywordSearch.abort();
                        }
                    },
                    success: function (response) {
                        var tbaleData = '';
                        if (response.length > 0) {

                            $.each(response,function(index,data){
                                tbaleData += `<tr>
                                    <td>${data.keyword}</td>
                                    <td>${data.avg_monthly_searches}</td>
                                    <td>${data.competition}</td>
                                    <td>${data.low_top}</td>
                                    <td>${data.high_top}</td>
                                </tr>`;
                            });
                            $('#keyword_table_data').html(tbaleData);
                        }else{

                        }
                        // toastr['success']('Priority successfully update!!', 'success');
                        // $('#priority_model').modal('hide');
                    },
                    error: function () {
                        // alert('There was error loading priority task list data');
                    }
                });
            });

            $('#keyword_language, #keyword_location, #google_search, #search_network, #content_network, #partner_search_network, #filter_by_gender').change(function(){

                if ($('#keyword_search').val() == '' || $('#keyword_search').val() == null) {
                    // error
                    toastr['error']('Keyword not be empty!!', 'Error');
                    return false;
                }

               

                keywordSearch = $.ajax({
                    url: "{{route('google-keyword-search-v6')}}",
                    type: 'GET',
                    data: {
                        keyword:$('#keyword_search').val(),
                        location:$('#keyword_location').val(),
                        language:$('#keyword_language').val(),
                    },
                    beforeSend : function(){
                        if(keywordSearch != null) {
                            keywordSearch.abort();
                        }
                    },
                    success: function (response) {
                        var tbaleData = '';
                        if (response.length > 0) {

                            $.each(response,function(index,data){
                                tbaleData += `<tr>
                                    <td>${data.keyword}</td>
                                    <td>${data.avg_monthly_searches}</td>
                                    <td>${data.competition}</td>
                                    <td>${data.low_top}</td>
                                    <td>${data.high_top}</td>
                                </tr>`;
                            });
                            $('#keyword_table_data').html(tbaleData);
                        }else{

                        }
                        // toastr['success']('Priority successfully update!!', 'success');
                        // $('#priority_model').modal('hide');
                    },
                    error: function () {
                        // alert('There was error loading priority task list data');
                    }
                });
            });
        });
    </script>
@endsection
