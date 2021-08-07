@extends('layouts.app')

@section('favicon' , 'task.png')

@section('title', 'Tasks List')

@section("styles")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Tasks List</h2>
            {{-- <div class="pull-left">

            </div>
            <div class="pull-right">
              <a class="btn btn-secondary" href="{{ route('order.create') }}">+</a>
            </div> --}}
        </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-10 col-sm-12">
        <form action="{{ route('task.list') }}" method="GET" class="form-inline align-items-start" id="searchForm">
          <div class="row full-width" style="width: 100%;">
            <div class="col-md-4 col-sm-12">
              <div class="form-group mr-3">
                <select class="form-control select-multiple" name="user[]" multiple>
                  @foreach ($users as $index => $name)
                    <option value="{{ $index }}" {{ isset($user) && in_array($index, $user) ? 'selected' : '' }}>{{ $name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-4 col-sm-12">
              <div class='input-group date mr-3' id="date-datetime">
                <input type='text' class="form-control" name="date" value="{{ $date }}" />

                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-image"><img src="/images/search.png" /></button></div>
          </div>
        </form>
      </div>
    </div>

    @include('partials.flash_messages')

    @php
      $categories = \App\Http\Controllers\TaskCategoryController::getAllTaskCategory();
    @endphp

    <div id="exTab3" class="container">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#pending-tasks" data-toggle="tab">Pending Tasks</a>
        </li>
        <li>
          <a href="#completed-tasks" data-toggle="tab">Completed</a>
        </li>
      </ul>
    </div>

    <div class="tab-content ">
      <div class="tab-pane active mt-3" id="pending-tasks">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <tr>
              <th width="10%">Date</th>
              <th width="10%">Category</th>
              <th width="50%">Task</th>
              <th width="10%">Assigned To</th>
              <th width="10%">Status</th>
              <th width="10%">Action</th>
            </tr>
            @foreach ($pending_tasks as $task)
              <tr>
                <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d-m H:i') }}</td>
                <td>{{ $categories[$task->category] ?? '' }}</td>
                <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">
                  <span class="task-container">
                    {{ $task['task_subject'] ?? 'Task Details' }}
                  </span>
                  <br>

                  <span class="text-danger">
                    {{ $task->remarks()->first()->remark ?? '' }}
                  </span>
                </td>
                <td>{{ $users[$task->assign_to] ?? 'Unknown User' }}</td>
                <td>
                  @if ($task->is_completed)
                    {{ \Carbon\Carbon::parse($task->is_completed)->format('d-m H:i') }}
                  @else
                    <button type="button" class="btn btn-xs btn-secondary task-complete" data-id="{{ $task->id }}">Complete</button>
                  @endif
                </td>
                <td>
                  <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $task->id }}">Add</a>
                  <span> | </span>
                  <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $task->id }}">View</a>
                </td>
              </tr>
            @endforeach
          </table>
        </div>

        {!! $pending_tasks->appends(Request::except('page'))->links() !!}
      </div>

      <div class="tab-pane mt-3" id="completed-tasks">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <tr>
              <th width="10%">Date</th>
              <th width="10%">Category</th>
              <th width="50%">Task</th>
              <th width="10%">Assigned To</th>
              <th width="10%">Status</th>
              <th width="10%">Action</th>
            </tr>
            @foreach ($completed_tasks as $task)
              <tr>
                <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d-m H:i') }}</td>
                <td>{{ $categories[$task->category] ?? '' }}</td>
                <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">
                  <span class="task-container">
                    {{ $task['task_subject'] ?? 'Task Details' }}
                  </span>
                  <br>

                  <span class="text-danger">
                    {{ $task->remarks()->first()->remark ?? '' }}
                  </span>
                </td>
                <td>{{ $users[$task->assign_to] ?? 'Unknown User' }}</td>
                <td>
                  @if ($task->is_completed)
                    {{ \Carbon\Carbon::parse($task->is_completed)->format('d-m H:i') }}
                  @else
                    <button type="button" class="btn btn-xs btn-secondary task-complete" data-id="{{ $task->id }}">Complete</button>
                  @endif
                </td>
                <td>
                  <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $task->id }}">Add</a>
                  <span> | </span>
                  <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $task->id }}">View</a>
                </td>
              </tr>
            @endforeach
          </table>
        </div>

        {!! $completed_tasks->appends(Request::except('completed-page'))->links() !!}
      </div>
    </div>

    <!-- Modal -->
    <div id="addRemarkModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add New Remark</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>
          <div class="modal-body">
            <form id="add-remark">
              <input type="hidden" name="id" value="">
              <textarea rows="1" name="remark" class="form-control"></textarea>
              <button type="button" class="btn btn-secondary mt-2" id="addRemarkButton">Add Remark</button>
          </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div id="viewRemarkModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">View Remark</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>
          <div class="modal-body">
            <div id="remark-list">

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '.task-subject', function() {
      if ($(this).data('switch') == 0) {
        $(this).find('.task-container').text($(this).data('details'));
        $(this).data('switch', 1);
      } else {
        $(this).find('.task-container').text($(this).data('subject'));
        $(this).data('switch', 0);
      }
    });

    $('#date-datetime').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $(document).on('click', '.task-complete', function() {
      var thiss = $(this);
      var id = $(this).data('id');

      $.ajax({
        type: "GET",
        url: "{{ url('/task/complete') }}/" + id,
        beforeSend: function() {
          $(thiss).text('Loading...');
        }
      }).done(function() {
        $(thiss).closest('tr').remove();
      }).fail(function(response) {
        $(thiss).text('Complete');

        alert('Could not complete the task');
        console.log(response);
      });
    });

    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $('.instruction-start-time').datetimepicker({
         format: 'YYYY-MM-DD HH:mm'
       });
    });


    $('.add-task').on('click', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      $('#add-remark input[name="id"]').val(id);
    });

    $('#addRemarkButton').on('click', function() {
      var id = $('#add-remark input[name="id"]').val();
      var remark = $('#add-remark textarea[name="remark"]').val();

      $.ajax({
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('task.addRemark') }}',
          data: {
            id:id,
            remark:remark,
            module_type: 'task'
          },
      }).done(response => {
          alert('Remark Added Success!')
          window.location.reload();
      }).fail(function(response) {
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
              id:id,
              module_type: "task"
            },
        }).done(response => {
            var html='';

            $.each(response, function( index, value ) {
              html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
              html+"<hr>";
            });
            $("#viewRemarkModal").find('#remark-list').html(html);
        });
    });

    $(document).on('click', '.verify-btn', function(e) {
      e.preventDefault();

      var thiss = $(this);
      var id = $(this).data('id');

      $.ajax({
        type: "POST",
        url: "{{ route('instruction.verify') }}",
        data: {
          _token: "{{ csrf_token() }}",
          id: id
        },
        beforeSend: function() {
          $(thiss).text('Verifying...');
        }
      }).done(function(response) {
        $(thiss).parent().html('<span class="badge">Verified</span>');

        $(thiss).remove();
      }).fail(function(response) {
        $(thiss).text('Verify');
        console.log(response);
        alert('Could not verify the instruction!');
      });
    });

    $(document).on('click', '.instruction-edit-button', function() {
      var id = $(this).data('id');
      var start = $(this).data('start');
      var end = $(this).data('end');
      var url = "{{ url('instruction') }}/" + id;

      $('#instructionEditForm').attr('action', url);

      $('#instruction_start_time').val(start.length > 0 ? start : moment().format('YYYY-MM-DD HH:mm'));
      $('#instruction_end_time').val(end.length > 0 ? end : moment().format('YYYY-MM-DD HH:mm'));
    });

    var instructions_array = [];

    $(document).on('click', '.select-instruction', function() {
      var id = $(this).data('id');

      if ($(this).prop('checked')) {
        instructions_array.push(id);
      } else {
        instructions_array.splice(instructions_array.indexOf(id), 1);
      }

      console.log(instructions_array);
    });

    $(document).on('click', '#select-all-instructions', function() {
      if ($(this).prop('checked')) {
        $('.select-instruction').each(function(index, instruction) {
          $(instruction).prop('checked', true);
          var id = $(instruction).data('id');

          instructions_array.push(id);
        });
      } else {
        $('.select-instruction').each(function(index, instruction) {
          $(instruction).prop('checked', false);

          instructions_array = [];
        });
      }
    });

    $('#verifySelectedButton').on('click', function(e) {
      e.preventDefault();

      if (instructions_array.length > 0) {
        $('#selected_instructions').val(JSON.stringify(instructions_array));
      } else {
        alert('Please select atleast 1 instruction');

        return;
      }

      $('#verifySelectedForm').submit();
    });
  </script>
@endsection
