@extends('layouts.app')

@section('content')
    <style>
        .task {
            border-bottom: 1px solid #e4e5e6;
            margin-bottom: 1px;
            position: relative;
        }
        .task .desc {
            display: inline-block;
            width: 75%;
            padding: 10px 10px;
            font-size: 16px;
        }
        .task .time {
            display: inline-block;
            width: 15%;
            padding: 10px 10px 10px 0;
            font-size: 12px;
            text-align: right;
            position: absolute;
            top: 0;
            right: 0;
        }

        /*.img-hover {*/
            /*position: relative;*/
            /*margin-top: 10px;*/
            /*width: 300px;*/
            /*height: 300px;*/
        /*}*/

        .overlay {
            position: absolute;
            top: 0;
            left: 15px;
            width: 100px;
            height: 100px;
            background: rgba(0, 0, 0, 0);
            transition: background 0.5s ease;
        }


        .img-hover:hover .overlay {
            display: block;
            background: rgba(0, 0, 0, .3);
        }

        .button {
            position: absolute;
            width: 100px;
            left:15px;
            top: 60px;
            text-align: center;
            opacity: 0;
            transition: opacity .35s ease;
        }

        .button a {
            width: 100px;
            padding: 7px 7px;
            text-align: center;
            color: white;
            border: solid 2px white;
            z-index: 1;
        }

        .img-hover:hover .button {
            opacity: 1;
        }

        .img-wh{
            width: 100px;
            height: 100px;
        }
    </style>

    @if($task->priority == 1)
        <?php 
            $task_type = 'Normal';
            $bg_color = 'background: green;'; 
            $priority_clr = 'color:green';?>
    @elseif($task->priority == 2)
        <?php 
        $task_type = 'Urgent'; 
        $bg_color = 'background: orange;'; 
        $priority_clr = 'color:orange';?>

    @elseif($task->priority == 3)
        <?php 
        $task_type = 'Critical';
        $bg_color = 'background: red;'; 
        $priority_clr = 'color:red'; ?>
    @else
        <?php 
         $task_type = 'Normal';
         $bg_color = 'background: green;'; 
         $priority_clr = 'color:green';?>
    @endif
<div class="content" style="margin-top: 10px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card-box task-detail">
                    <div class="media mt-0 m-b-30">
                        <div class="media-body">
                            <p style="width:50%;display: inline-block;">
                                <a href="{{ route('development.overview') }}?status={{$task->status}}">Back to {{$task->status}}</a>
                            </p>
                            <p style="width:50%;display: inline-block; float:right;display: none;">
                                <a href="javascript:" class="btn btn-default"  id="newTaskModalBtn" data-toggle="modal" data-target="#newTaskModal" style="float: right;">Add New Task </a>
                            </p>
                            <h4 class="media-heading mb-0 mt-0">{{$task->task_type .'-'.$task->id}}
                                <span class="badge badge-danger" style="{{$bg_color}}">{{$task_type}}</span>
                            </h4>
                        </div>
                    </div>
                    <h2 class="m-b-20">{{ucfirst($task->subject)}}</h2>
                    <p >{{$task->task}}</p>

                    <div class="clearfix"></div>


                    <!-- Attachments -->
                    <div class="attachment" style="background: #ccc;padding: 8px;border-radius: 5px;margin-top: 12px;">
                        <h3>Attachments
                            <label class="btn btn-default pull-right" style="display: none;">
                                Choose File <input id="browse" type="file" name="upload_file[]" onchange="previewFiles()" multiple hidden>
                            </label>
                        </h3>

                        <div class="col-md-12">
                            @if(!empty($attachments))
                                @foreach($attachments as $attachment)
                                    <div class="col-md-3 img-hover" style="    height: 130px;">
                                        <?php $ext = substr($attachment->name, strrpos($attachment->name, '.') + 1);
                                        if($ext == 'pdf'){ ?>
                                            <img src="{{ asset("images/pdf_icon.png") }}" class="img-responsive img-wh">
                                        <?php
                                        }else if ($ext == 'doc' || $ext == 'docx'){
                                        ?>
                                            <img src="{{ asset("images/docs_icon.png") }}" class="img-responsive img-wh">
                                        <?php
                                        }else{
                                        ?>
                                            <img src="{{ asset("images/task_files/$attachment->name") }}" class="img-responsive img-wh">
                                        <?php } ?>
                                        <div class="overlay"></div>
                                        <div class="button"><a href="{{ route('download.file').'?file_name='.$attachment->name }}"> Download </a></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <form action="{{ route('development.upload.files') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            Choose File <input id="browse" type="file" name="attached_document[]" onchange="previewFiles()" multiple >
                            <input type="hidden" name="task_id" id="task_id" value="{{$task->id}}">
                            <input type="submit" name='submit_image' id="uplaod_images" value="Upload Image" />
                        </form>

                        {{--<div id="image_preview"></div>--}}

                        <div id="preview"></div>
                    </div>

                    <h3>Activity</h3>
                    <div class="dev_comments">
                        @if(!empty($comments))
                            @foreach($comments as $comment)
                                <div class="media m-b-20"><div class="d-flex mr-3">
                                    <a href="#"><img class="media-object rounded-circle thumb-sm" alt="64x64" src="https://bootdey.com/img/Content/avatar/avatar1.png"></a>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-0">{{ $comment->name }}</h5>
                                    <p class="font-13 text-muted mb-0">{{ $comment->comment }}</p></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="media m-b-20">
                        <div class="d-flex mr-3"> <a href="#"><img class="media-object rounded-circle thumb-sm" alt="64x64" src="https://bootdey.com/img/Content/avatar/avatar1.png"></a></div>
                        <div class="media-body">
                            <textarea name="comment" id="comment" class="form-control input-sm" placeholder="Some text value..."></textarea>
                            {{--<input type="text" name="comment" id="comment" class="form-control input-sm" placeholder="Some text value...">--}}
                            <div class="mt-2 text-right"> <button type="button" class="btn btn-sm btn-custom waves-effect waves-light" id="add_comment">Send</button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                <div class="card-box">
                    <h4 class="header-title m-b-30">Status</h4>
                    <div class="task_status">
                        <select name="task_status" class="form-control change_task_status">
                            <option value="Planned" {{ ($task->status == 'Planned') ? 'selected' : '' }}>Planned</option>
                            <option value="In Progress" {{ ($task->status == 'In Progress') ? 'selected' : '' }}>In Progress</option>
                            <option value="Done" {{ ($task->status == 'Done') ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>
                    <div class="assignee">
                        <br />
                        <h4 class="header-title m-b-30">Assignee</h4>
                        <div class="media m-b-20">
                            @if(1==2)
                            <div class="d-flex mr-3">
                                <img class="media-object rounded-circle thumb-sm" alt="64x64" src="https://bootdey.com/img/Content/avatar/avatar2.png">
                            </div>
                            @endif
                            <div class="media-body" style="margin-top: 7px;">
                                <h4 class="mt-0 assignee_name">&nbsp; {{$task->username}}</h4>
                                <div class="developer_section" style="display: none;">
                                    @if(!empty($developers))
                                         <select name="task_status" class="form-control change-assignee"  data-id="{{$task->id}}">
                                                @foreach($developers as $id=>$name)
                                                    <option value="{{$id}}">{{$name}}</option>
                                                @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="assignee">
                        <br />
                        <h4 class="header-title m-b-30">Reporter</h4>
                        <div class="media m-b-20">
                            @if(1==2)
                            <div class="d-flex mr-3">
                                <img class="media-object rounded-circle thumb-sm" alt="64x64" src="https://bootdey.com/img/Content/avatar/avatar2.png">
                            </div>
                            @endif
                            <div class="media-body" style="margin-top: 7px;">
                                <h4 class="mt-0">&nbsp; {{$task->reporter}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="priority">
                        <br />
                        <h4 class="header-title m-b-30">Priority</h4>
                        <h4 style="{{$priority_clr}}">&nbsp; {{$task_type}}</h4>
                    </div>

                    <div class="sub_tasks" style="border: 1px solid #ccc;padding: 8px;border-radius: 5px;">
                        <div class="task-list">
                            <h3>Subtasks <span style="float: right;font-size: 16px;"><a href="javascript:" id="create_subtask_link">+ Create SubTasks</a></span></h3>
                            <div class="add_subtask_div" style="display: none;">
                                <input type="text" id="subtask_detail" name="subtask" class="form-control input-sm" placeholder="What needs to be done?" style="padding: 20px;">
                                <button type="button" id="subtask_create">Create</button>
                                <input type="hidden" id="task_id" value="{{$task->id}}">
                            </div>

                            @if(!empty($subtasks) && count($subtasks) > 0)
                                @foreach($subtasks as $subtask)
                                    <div class="task high" style="background: white;">
                                        <div class="desc">
                                            <div class="title">{{$subtask->task}}</div>
                                        </div>
                                        <div class="time">
                                            <div class="date">Todo</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div style="text-align: center;"> No Subtask yet</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- container -->
</div>

@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).on('click', '#create_subtask_link', function () {
            $(".add_subtask_div").toggle();
        });

        $(document).on('click', '#subtask_create', function () {
            var task_detail = $("#subtask_detail").val();
            var taskId      = $("#task_id").val();
            if(task_detail != '') {
                $.ajax({
                    url: "{{ action('DevelopmentController@store') }}",
                    type: 'POST',
                    data: {
                        parent_id: taskId,
                        _token: "{{csrf_token()}}",
                        task: task_detail,
                        priority:'1',
                        status : 'Planned',


                    },
                    success: function () {
                        $("#subtask_detail").val('');
                        $(".add_subtask_div").hide();
                        toastr['success']('Subtask Added successfully!')
                    }
                });
            }else{
                toastr['error']('Task Detail is empty','Error');
            }
        });

        $(document).on('click', '#add_comment', function () {

            var comment     = $("#comment").val();
            var taskId      = $("#task_id").val();
            if(comment != '') {
                $.ajax({
                    url: "{{ action('DevelopmentController@taskComment') }}",
                    type: 'POST',
                    data: {
                        task_id: taskId,
                        _token: "{{csrf_token()}}",
                        comment: comment,
                    },
                    success: function () {
                        var html = '<div class="media m-b-20">'+
                                        '<div class="d-flex mr-3">'+
                                            '<a href="#"><img class="media-object rounded-circle thumb-sm" alt="64x64" src="https://bootdey.com/img/Content/avatar/avatar1.png"></a>'+
                                        '</div>'+
                                        '<div class="media-body">'+
                                            '<h5 class="mt-0">{{Auth::user()->name}}</h5>'+
                                            '<p class="font-13 text-muted mb-0">'+comment+'</p>'+
                                        '</div>'+
                                    '</div>';
                        $(".dev_comments").append(html);
                        $("#comment").val('');
                        sendMessageWhatsapp("{{$task->user_id}}",comment,'user',"{{csrf_token()}}");
                        toastr['success']('Comment Added successfully!')
                    }
                });
            }else{
                toastr['error']('Comment is empty','Error');
            }
        });

        $(document).on('click', '.assignee_name', function () {
            $(".assignee_name").hide();
            $(".developer_section").show();

        });

        $(document).on('change', '.change-assignee', function () {
            var taskId = $(this).attr('data-id');
            var user_id = $(this).val();

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

        $(document).on('change', '.change_task_status', function () {
           var taskId       = $("#task_id").val();
           var status      = $(this).val();
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


        function previewFiles() {

            var preview = document.querySelector('#preview');
            var files   = document.querySelector('input[type=file]').files;
            //console.log(files['0'].name);
            function readAndPreview(file) {
                //console.log(file.name);

                // Make sure `file.name` matches our extensions criteria
                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
                    var reader = new FileReader();

                    reader.addEventListener("load", function () {
                        var image = new Image();
                        image.height = 100;
                        image.title = file.name;
                        image.src = this.result;
                        preview.appendChild( image );
                    }, false);

                    reader.readAsDataURL(file);
                }

            }

            if (files) {
                [].forEach.call(files, readAndPreview);
            }

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
                    if(resp.status == 'ok') {
                        $("body").append(resp.html);
                        $('#newTaskModal').modal('show');
                    }
                }
            });
        });

    </script>
@endsection
