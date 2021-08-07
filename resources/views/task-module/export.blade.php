@extends('layouts.app')


@section('content')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

    <div class="row">
        <div class="col-lg-12 text-center">
            <h2>Exported Tasks</h2>
        </div>
    </div>

    <div class="row">
      <div class="col">
        <table class="table">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Assigned From</th>
                <th>Assigned To</th>
                <th>Type</th>
                <th>Task Details</th>
                <th>Completion Date</th>
                <th>Remark</th>
                <th>Completed On</th>
                <th>Created On</th>
              </tr>
            </thead>
            <tbody>
                <?php $i = 1 ?>
              @foreach($tasks as $task)
            <tr>
                <td>{{ $task['SrNo'] }}</td>
                <td>{{ $task['assign_from'] }}</td>
                <td>{{ $task['assign_to'] }}</td>
                <td>{{ $task['type'] }}</td>
                <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">{{ $task['task_subject'] ? $task['task_subject'] : 'Task Details' }}
                <td>{{ Carbon\Carbon::parse($task['completion_date'])->format('d-m H:i') }}</td>
                <td>
                  <a href id="view-remark-list-btn" class="view-remark" data-toggle="modal" data-target="#view-remark-list" data-id="{{ $task['id'] }}">View</a>
                </td>
                <td>{{ Carbon\Carbon::parse($task['completed_on'])->format('d-m H:i') }}</td>
                <td>{{ Carbon\Carbon::parse($task['created_on'])->format('d-m H:i') }}</td>
            </tr>

            <!-- Modal -->
            <div id="view-remark-list" class="modal fade" role="dialog">
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
           @endforeach
            </tbody>
          </table>
      </div>
    </div>

    <script type="text/javascript">
      $(".view-remark").click(function () {

        var taskId = $(this).attr('data-id');

          $.ajax({
              type: 'GET',
              headers: {
                  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              url: '{{ route('task.gettaskremark') }}',
              data: {id:taskId},
          }).done(response => {
              console.log(response);

              var html='';

              $.each(response, function( index, value ) {

                html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
                html+"<hr>";
              });
              $("#view-remark-list").find('#remark-list').html(html);
              // getActivity();
              //
              // $('#loading_activty').hide();
          });
      });

      $(document).on('click', '.task-subject', function() {
        if ($(this).data('switch') == 0) {
          $(this).text($(this).data('details'));
          $(this).data('switch', 1);
        } else {
          $(this).text($(this).data('subject'));
          $(this).data('switch', 0);
        }
      });
    </script>

@endsection
