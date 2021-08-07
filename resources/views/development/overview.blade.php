@extends('layouts.app')

@section('content')
    <style>
        #devOverview {
            overflow-x: auto;
            padding: 20px 0;
        }

        .success {
            background: #00B961;
            color: #fff
        }

        .info {
            background: #2A92BF;
            color: #fff
        }

        .warning {
            background: #F4CE46;
            color: #fff
        }

        .error {
            background: #FB7D44;
            color: #fff
        }
    </style>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Development {{ $status }}
                    <a href="javascript:" class="btn btn-default"  id="newTaskModalBtn" data-toggle="modal" data-target="#newTaskModal" style="float: right;">Add New Task </a>
            </h2>
        </div>
    </div>



    @php
        $count = 0;
    @endphp

    <main class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-12">
                    <form action="{{ url("development/overview") }}" method="get">
                        <div class="row">
                            <div class="col-md-1">
                                <label>Filter By:</label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="task_type" id="task_type">
                                    <option value="">Please select Task Type</option>
                                    @foreach($taskTypes as $id=>$taskType)
                                        <option  value="{{$taskType->id}}" {{ (!empty(app('request')->input('task_type')) && app('request')->input('task_type') ==  $taskType->id ? 'selected' : '') }}>{{ $taskType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="task_status" id="task_status" class="form-control">
                                    <option value="">Please Select Status</option>
                                    <option value="Planned" {{ (!empty(app('request')->input('task_status')) && app('request')->input('task_status') ==  'Planned' ? 'selected' : '') }}>Planned</option>
                                    <option value="In Progress" {{ (!empty(app('request')->input('task_status')) && app('request')->input('task_status') ==  'In Progress' ? 'selected' : '') }}>In Progress</option>
                                    <option value="Done" {{ (!empty(app('request')->input('task_status')) && app('request')->input('task_status') ==   'Done' ? 'selected' : '') }}>Done</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-image">
                                    <img src="{{ asset('images/search.png') }}" alt="Search">
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="devOverview">
                        <div class="overview-container">
                            @foreach($users as $user)
                                @php
                                    $tasks = \App\Helpers\DevelopmentHelper::getDeveloperTasks($user->id, $status,$task_type);
                                @endphp
                                @if(!empty($tasks) && count($tasks)>0)
                                    <div style="width: 200px; display: inline-block;">
                                        <div class="card card-border-warning">
                                            <div class="card-header">
                                                <h5 class="card-title">{{ucwords($user->name)}}</h5>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($tasks as $task)
                                                    @if($task->user_id == $user->id)
                                                        @if($task->priority == 1)
                                                            <?php $border = 'border-left: 4px solid green;'; ?>
                                                        @elseif($task->priority == 2)
                                                            <?php $border = 'border-left: 4px solid orange;'; ?>
                                                        @elseif($task->priority == 3)
                                                            <?php $border = 'border-left: 4px solid red;'; ?>
                                                        @endif
                                                        <div class="card mb-3 bg-light" style=" {{$border}} ">
                                                            <div class="card-body p-3">
                                                                @if (1==2)
                                                                    <div class="float-right mr-n2">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" checked="">
                                                                            <span class="custom-control-label"></span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                                <h4><a href="task-detail/{{$task->task_id}}">{{ '#'.strtoupper($task->name).'-'.$task->task_id.' '.ucfirst($task->subject) }} </a></h4>
                                                                <p>{{ $task->task }}</p>
                                                                @if(1==2)
                                                                    <div class="float-right mt-n1">
                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar6.png" width="32" height="32" class="rounded-circle" alt="Avatar">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @php
                                                    $count++;
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.overview-container').width(<?= $count * 210 ?>);
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