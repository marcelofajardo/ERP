@extends('layouts.app')

@section('favicon' , 'task.png')

@section('title', 'Learning')

@section('styles')
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">

    <style>
        .btn.btn-image {
            padding: 5px 3px;
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
        .pd-2 {
            padding:2px;
        }

        .status-selection .btn-group {
            padding: 0;
            width: 100%;
        }
        .status-selection .multiselect {
            width : 100%;
        }
    </style>
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="page-heading">{{$title}}</h2>
        </div>
    </div>
    <!--- Pre Loader -->
    <img src="/images/pre-loader.gif" id="Preloader" style="display:none;"/>
    {{-- @include('learning-module.partials.modal-contact') --}} 
    @include('learning-module.partials.modal-learning-category')
    {{-- @include('learning-module.partials.modal-learning-view') --}} 
    {{-- @include('learning-module.partials.modal-whatsapp-group') --}} 

    @include('partials.flash_messages')

    

    <?php
    if (\App\Helpers::getadminorsupervisor() && !empty($selected_user))
        $isAdmin = true;
    else
        $isAdmin = false;
    ?>
    
    <div class="row mb-2">
        <div class="col-xs-12">
            <form action="{{ action('LearningModuleController@createLearningFromSortcut') }}" method="POST" id="taskCreateForm">
                @csrf
              
                <div class="row">
                    <div class="col-xs-12 col-md-2 pd-2">
                        <div class="form-group cls_learning_user">
                                <!-- <strong>User :</strong> -->
                                <select class="globalSelect2 form-control"  data-ajax="{{ route('select2.uservendor') }}" data-live-search="true" data-size="15" name="learning_user" data-placeholder="Choose a User" id="learning_user" required>
                                   <option id="{{ $last_record_learning->learningUser->id }}" selected="selected">{{ $last_record_learning->learningUser->name }}</option>

                                {{-- @foreach ($quick_users_array as $index => $user)
                                <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}">{{ $user }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-2 pd-2">
                        <div class="form-group cls_learning_provider">
                                <!-- <strong>Provider :</strong> -->
                                <select class="globalSelect2 form-control"  data-ajax="{{ route('select2.uservendor') }}" data-live-search="true" data-size="15" name="learning_vendor" data-placeholder="Choose a Provider" id="learning_vendor" required>
                                
                                <option id="{{ $last_record_learning->learningVendor->id }}" selected="selected">{{ $last_record_learning->learningVendor->name }}</option>


                                {{-- @foreach ($quick_users_array as $index => $user)
                                <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}">{{ $user }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-group cls_learning_subject">
                            <!-- <strong>Subject :</strong> -->
                            <input type="text" class="form-control input-sm" name="learning_subject" placeholder="Subject" id="learning_subject" value="{{ old('task_subject') }}" required/>
                            @if ($errors->has('task_subject'))
                                <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
                            @endif
                        </div>
                    </div>

                    <!--   
                    <div class="col-xs-12 col-md-2 pd-2">
                        <div class="form-group">
                            <textarea rows="1" class="form-control input-sm cls_task_detailstextarea" name="task_detail" placeholder="Details" id="task_details" required>{{ old('task_detail') }}</textarea>
                            @if ($errors->has('task_detail'))
                                <div class="alert alert-danger">{{$errors->first('task_detail')}}</div>
                            @endif
                        </div>
                    </div> -->

                    
                    <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-inline">
                            <div class="cls_categoryfilter_box">
                                <div class="cls_categoryfilter_first">
                                    <div class="">
                                        <!-- <strong>Module :</strong> -->
                                        {{-- <strong>Category:</strong> --}}
                                        {!! $learning_module_dropdown !!}
                                    </div>
                                </div>
                                <div class="cls_categoryfilter_second">
                                    <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createTaskCategorytModal"><img src="{{asset('images/add.png')}}"/></button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-inline">
                            <div class="cls_submodule_box">
                                <div class="cls_submodule_first">
                                    <div class="submodule">
                                        <!-- <strong>Sub Module :</strong> -->
                                        <select name="learning_submodule" class="form-control input-sm submodule">
                                            <option value="">Select Submodule</option>
                                            @foreach($learning_submodule_dropdown as $options)
                                                <option value="{{ $options->id }}">{{ $options->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-inline">
                            <div class="cls_multi_contact">
                                <div class="cls_multi_contact_first">
                                    <div class="">

                                        <!-- <strong>Assignment :</strong> -->
                                        <select id="learning_assignment" style="width: 100%;" class="form-control input-sm js-example-basic-multiple" name="learning_assignment">
                                                <option>Select Assignment</option>
                                            @foreach (Auth::user()->contacts as $contact)
                                                <option value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('learning_assignment'))
                                            <div class="alert alert-danger">{{$errors->first('learning_assignment')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cls_multi_contact_second">
                                    <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createQuickContactModal"><img src="{{asset('images/add.png')}}"/></button>
                                </div>
                            </div>
                            

                            
                        </div>
                    </div> --}}
                    <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-group cls_learning_assignment">
                            <!-- <strong>Subject :</strong> -->
                            <input type="text" class="form-control input-sm" name="learning_assignment" maxlength="15" placeholder="Assignment" id="learning_assignment" value="{{ old('learning_assignment') }}" required/>
                            @if ($errors->has('learning_assignment'))
                                <div class="alert alert-danger">{{$errors->first('learning_assignment')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-2 pd-2" id="calendar-task" style="">
                        <div class="form-group">
                        <!-- <strong>Due Date :</strong> -->
                            <div class='input-group date' id='learning-due-datetime'>
                                
                                <input type='text' class="form-control input-sm" name="learning_duedate" id="learning_duedate" value="{{ date('Y-m-d') }}"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            @if ($errors->has('completion_date'))
                                <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                            @endif
                        </div>
                    </div>
                                    
                    <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-inline">
                            <div class="cls_categoryfilter_box">
                                <div class="cls_categoryfilter_first">
                                <!-- <strong>Status :</strong> -->
                                    <div class="">
                                    <select name="learning_status" class=" form-control"  required>
                                            <option>Select Status</option>
                                            @foreach ($task_statuses as $index => $user)
                                                <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                            @endforeach 
                                    </select>
                                    </div>
                                </div>
                                <div class="cls_categoryfilter_second">
                                
                                    <button type="button" class="btn btn-image" data-toggle="modal" data-target="#taskStatusModal"><img src="{{asset('images/add.png')}}"/></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-1 pd-2 ">
                        <div class="form-group mt-200">
                            
                            <button type="submit" class="btn btn-secondary cls_comm_btn" id="taskCreateButton">Create</button>
                        </div>
                   </div>
                    
                    <!-- <div class="col-xs-12 col-md-1 pd-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="no_of_milestone" name="no_of_milestone" value="{{ old('no_of_milestone') }}" placeholder="No of milestone" />

                            @if ($errors->has('no_of_milestone'))
                            <div class="alert alert-danger">{{$errors->first('no_of_milestone')}}</div>
                            @endif
                        </div>
                    </div> -->
                    <!-- <div class="col-xs-12 col-md-1 pd-2">
                        
                    </div> -->
                    @if(auth()->user()->isAdmin())
                    <!-- <div class="col-xs-12 col-md-2 pd-2">
                        <div class="form-group">
                            <select id="multi_users" class="form-control input-sm" name="task_asssigned_to[]" multiple>
                                @foreach ($data['users'] as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }} - {{ $user['email'] }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('task_asssigned_to'))
                                <div class="alert alert-danger">{{$errors->first('task_asssigned_to')}}</div>
                            @endif
                        </div>
                    </div> -->
                    @endif
                   <!-- <div class="col-xs-12 col-md-2 pd-2">
                   </div> -->
                </div>
            </form>
        </div>
    </div>
    {{-- @include('learning-module.partials.modal-reminder') --}}

    @if(auth()->user()->isAdmin())

    @include('learning-module.partials.modal-learning-status')

       
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


    <form action="" method="get">
        <div class="row">
            <div class="col-md-2 pd-sm">
                <select class="form-control" name="user_id" id="user_id">
                    <option value="">Select User</option>
                    @foreach($users as $id=>$user)
                        <option {{request()->get('user_id')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 pd-sm">
                <!-- <input type="text" name="subject" placeholder="Subject" class="form-control" value="{{ request()->get('subject') }}"> -->

                <select class="form-control" name="subject">
                    <option value="">Select Subject</option>
                    @foreach ($subjectList as $subject)
                        <option {{ request()->get('subject') == $subject ? 'selected' : '' }} value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                </select>

            </div>

            
            <div class="col-md-2 pd-sm status-selection">
                <?php echo Form::select("task_status[]",$statusList,request()->get('task_status', []),["class" => "form-control multiselect","multiple" => true]); ?>
            </div>

            <div class="col-md-2 pd-sm">
                <div class='input-group date' id="learning-overdue-datetime">
                    <input type='text' class="form-control input-sm" name="overduedate"  value="{{ request()->get('overduedate') }}"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

             <div class="col-md-2 pd-sm">
                <select class="form-control updateModule" name="module">
                    <option value="">Select Module</option>
                    @foreach(App\LearningModule::where('parent_id',0)->orderBy('title')->get() as $module)
                        <option value="{{ $module->id }}" {{ request()->get('module') == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 pd-sm">
                <select class="form-control" name="submodule">
                    <option value="">Select SubModule</option>
                    @foreach(App\LearningModule::where('parent_id','!=',0)->orderBy('title')->get() as $submodule)
                        <option class="submodule" {{ request()->get('submodule') == $submodule->id ? 'selected' : '' }} value="{{ $submodule->id }}">{{ $submodule->title }}</option>
                    @endforeach
                </select>
            </div> 



            
            

            <div class="col-md-1 pd-sm">
                <button type="submit" class="btn btn-image search">
                    <img src="{{ asset('images/search.png') }}" alt="Search">
                </button>
            </div>
        </div>
    </form>

    </br>    

    <div id="exTab2" style="overflow: auto">
        <div class="tab-content ">
            <!-- Pending task div start -->
            <div class="tab-pane active" id="1">
                <div class="row" style="margin:0px;"> 
                    <!-- <h4>List Of Pending Tasks</h4> -->
                    <div class="col-12">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th width="2%">ID</th>
                                <th width="4%">Date</th>
                                <th width="10%">User</th>
                                <th width="10%">Provider</th>
                                <th width="14%">Subject</th>
                                <th width="6%" class="category">Module</th>
                                <th width="6%" class="category">Sub Module</th>
                                <th width="14%">Assignment</th>
                                <th width="9%">Due date</th>
                                <th width="10%">Status</th>
                                <th width="33%">Communication</th>
                                <th width="5%">Action</th>
                            </tr>
                            </thead>
                            <tbody class="pending-row-render-view infinite-scroll-pending-inner">
                                @foreach ($learningsListing as $learning)
                                    
                                    @include('learning-module.learning-list')
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Pending task div end -->
           
           
        </div>
    </div>
    </div>

    <div id="duedate_history_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Status History</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="">
                    <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="duedate_history_div">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Old duedate</th>
                                        <th>New duedate</th>
                                        <th>Updated by</th>
                                        
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
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="status_history_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Status History</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="">
                    <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="status_history_div">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Old status</th>
                                        <th>New status</th>
                                        <th>Updated by</th>
                                        
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
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;"> <!-- Purpose - Add search message - DEVTAK-4020 -->
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

    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
              50% 50% no-repeat;display:none;">
    </div>

    <div id="file-upload-area-section" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
               <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="learning_id" id="hidden-task-id" value="">
                    <div class="modal-header">
                        <h4 class="modal-title">Upload File(s)</h4>
                    </div>
                    <div class="modal-body" style="background-color: #999999;">
                            @csrf
                            <div class="form-group">
                                <label for="document">Documents</label>
                                <div class="needsclick dropzone" id="document-dropzone">
    
                                </div>
                            </div>
                            <div class="form-group add-task-list">
                                
                            </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-save-documents">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- @include("development.partials.time-history-modal") --}}
    {{-- @include("learning-module.partials.tracked-time-history") --}}
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    
    <script src="/js/bootstrap-multiselect.min.js"></script>
    <script>
        $(document).ready(function () {

            $(".multiselect").multiselect({
                allSelectedText: 'All',
                includeSelectAllOption: true
            });

            $('#learning-overdue-datetime').datetimepicker({
                 format: 'YYYY-MM-DD'
            });

            $('.learning-overdue-datetime').datetimepicker({
                 format: 'YYYY-MM-DD'
            }).on('dp.change', function(e){ 
                var formatedValue = e.date.format(e.date._f);
                $.ajax({
                    url: "{{ route('learning-due-change') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    dataType:"json",
                    data: {
                        due_date : formatedValue,
                        learningid: $(this).data('id')
                    },
                    success: function () {
                        toastr["success"]("Duedate updated successfully!", "Message");
                    }
                });
            })

        });

        

        $(document).on('click', '.show-due-history', function() {
            
            var learningid = $(this).data('learningid');
            
            $('#duedate_history_div table tbody').html('');

            $.ajax({
                url: "{{ route('learning/duedate/history') }}",
                data: {learningid: learningid},
                success: function (data) {
                    if(data != 'error') {
                        $.each(data, function(i, item) {
                            $('#duedate_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ item['created_date'] +'</td>\
                                    <td>'+ item['old_duedate']  +'</td>\
                                    <td>'+ item['new_duedate']  +'</td>\
                                    <td>'+ item['update_by']+'</td>\
                                </tr>'
                            );  
                        });
                    }
                    $('#duedate_history_modal').modal('show');
                }
            }); 
        });


        $(document).on('click', '.show-time-history', function() {
            
            var learningid = $(this).data('learningid');
            
            $('#status_history_div table tbody').html('');

            $.ajax({
                url: "{{ route('learning/status/history') }}",
                data: {learningid: learningid},
                success: function (data) {
                    if(data != 'error') {
                        $.each(data, function(i, item) {
                            $('#status_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ item['created_date'] +'</td>\
                                    <td>'+ item['old_status']  +'</td>\
                                    <td>'+ item['new_status']  +'</td>\
                                    <td>'+ item['update_by']+'</td>\
                                </tr>'
                            );  
                        });
                    }
                    $('#status_history_modal').modal('show');
                }
            }); 
        });

    </script>    

    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route("task.upload-documents") }}',
            maxFilesize: 20, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove()
            },
            init: function () {

            }
        }

        $(document).on("click",".btn-save-documents",function(e){
            e.preventDefault();
            var $this = $(this);
            var formData = new FormData($this.closest("form")[0]);
            $.ajax({
                url: '/learning/save-documents',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType:"json",
                data: $this.closest("form").serialize(),
                beforeSend: function() {
                    $("#loading-image").show();
                }
            }).done(function (data) {
                $("#loading-image").hide();
                if(data.code == 500) {
                    toastr["error"](data.message);
                }
                else {
                    toastr["success"]("Document uploaded successfully");
                    location.reload();
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                toastr["error"](jqXHR.responseJSON.message);
                $("#loading-image").hide();
            });
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('change','.updateUser',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var user_id = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'user_id':user_id},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('change','.updateProvider',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var provider_id = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'provider_id':provider_id},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('click','.updateSubject',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var subject = $(this).parents('td').find("input").val();

            if(subject == '')
            {
                toastr.error("Subject Field is required");
                return false;
            }
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'subject':subject},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('change','.updateModule',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var module_id = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'module_id':module_id},
                success:function( response ){
                        $('.learning_and_activity[data-id="'+ response.learning_id +'"]').find('.updateSubmodule option.submodule').remove()
                    for (i = 0; i < response.submodule.length; i++) {
                        let elem = response.submodule[i];
                        var html = '<option value="'+elem.id+'" class="submodule">'+elem.title+'</option>';
                        var submodule_id = $(elem).data('id');
                        $('.learning_and_activity[data-id="'+ response.learning_id +'"]').find('.updateSubmodule').append(html)
                    }
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('change','.updateSubmodule',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var submodule_id = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'submodule_id':submodule_id},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('click','.updateAssignment',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var assignment = $(this).parents('td').find("input").val();

            if(assignment == '')
            {
                toastr.error("Assignment Field is required");
                return false;
            }

            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'assignment':assignment},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on('change','.updateStatus',function(){
            
            var id = $(this).parents('.learning_and_activity').attr('data-id');
            var status_id = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('learning-module.update') }}",
                data:{'id':id,'status_id':status_id},
                success:function( response ){
                    toastr.success(response.message);
                }
            })
        });

        $(document).on("click",".btn-file-upload",function() {
            var $this = $(this);
            var learning_id = $this.data("id");
            $("#file-upload-area-section").modal("show");
            $("#hidden-task-id").val(learning_id);
            $("#loading-image").hide();
        });
    </script>
    <script>
        $(document).ready(function () {

            $('#learning-due-datetime').datetimepicker({
                 format: 'YYYY-MM-DD'
            }); 

            $('.js-example-basic-multiple').select2();

            $('#priority_user_id').select2({
                    tags: true,
                    width: '100%'
            });

            var isLoading = false;
            var page = 1;

            // $(document).ready(function () {
            
                $(window).scroll(function() {
                    if ( ( $(window).scrollTop() + $(window).outerHeight() ) >= ( $(document).height() - 2500 ) ) {
                        loadMore();
                    }
                });

                function loadMore() {
                    if (isLoading)
                        return;
                    isLoading = true;
                    type = $("#tasktype").val();
                    var $loader = $('.infinite-scroll-products-loader');
                    page = page + 1;
                    $.ajax({
                        url: "/task?page="+page,
                        type: 'GET',
                        data: $('.form-search-data').serialize(),
                        beforeSend: function() {
                            $loader.show();
                        },
                        success: function (data) {
                            console.log(type);
                            $loader.hide();
                            if('' === data.trim())
                                return;
                            if(type == 'pending') {
                                $('.infinite-scroll-pending-inner').append(data);
                            }
                            if(type == 'completed') {
                                $('.infinite-scroll-completed-inner').append(data);
                            }
                            if(type == 'statutory_not_completed') {
                                $('.infinite-scroll-statutory-inner').append(data);
                            }
                            

                            isLoading = false;
                        },
                        error: function () {
                            $loader.hide();
                            isLoading = false;
                        }
                    });
                }            
            // });

            var hash = window.location.hash.substr(1);

            if (hash == '3') {
                $('a[href="#3"]').click();
            }


            // $('#multi_users').select2({
            //     placeholder: 'Select a User',
            // });

            $('#learning_assignment').select2({
                placeholder: 'Select a Assignment',
            });

            
        });


        $('.due-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

       
        let users = {!! json_encode( $data['users'] ) !!};

        let isAdmin = {{ $isAdmin ? 1 : 0}};

       
        // on status change

        $(document).on('change', '.change-task-status', function () {
         
            let id = $(this).attr('data-id');  
            let status=$(this).val();

            $.ajax({
              url: "{{route('task.change.status')}}",
              type: "POST",
             headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            dataType:"json",
            data: { 'task_id' : id , 'status': status},
                success: function (response) {
                    toastr["success"](response.message, "Message")
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });

        });
        $(document).on('change', '.parent-module', function () {
         
            let id = $(this).val();  

            $.ajax({
              url: "{{action('LearningCategoryController@getSubModule')}}",
              type: "POST",
             headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            dataType:"json",
            data: { 'module_id' : id},
                success: function (response) {
                    var $html = '';
                    $.each(response,function(i, item){
                        $html += '<option value="'+item.id+'">'+item.title+'</option>';
                    });
                    $('select.submodule').html($html);
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message")
                    
                }
            });

        });
        
        $(document).on('click', '.send-message-open', function (event) {
            var textBox = $(this).closest(".communication-td").find(".send-message-textbox");
            // var sendToStr  = $(this).closest(".communication-td").next().find(".send-message-number").val();
            let issueId = textBox.attr('data-id');
            let message = textBox.val();
            if (message == '') {
                toastr["error"]("Please Enter Message");
                return;
            }

            let self = textBox;

            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'learning')}}",
                type: 'POST',
                data: {
                    "issue_id": issueId,
                    "message": message,
                    // "sendTo" : sendToStr,
                    "_token": "{{csrf_token()}}",
                   "status": 2,
                   "learning": "learning",
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

    </script>
@endsection
