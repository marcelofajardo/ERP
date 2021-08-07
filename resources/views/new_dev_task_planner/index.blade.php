@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">New Dev Task Planner</h2>
    </div>
</div>
<div class="col-lg-12 margin-tb">
    <form action="{{route('filteredNewDevTaskPlanner')}}" method="get" class="form-inline">
        <div class="col-md-4 col-lg-2 col-xl-2">
            <div class="form-group">
                {!! Form::text('search_term', (!empty(request()->search_term) ? request()->search_term : null) , ['class' => 'form-control', 'placeholder' => 'Search Term']) !!}
            </div>
        </div>
        <div class="col-md-4 col-lg-2 col-xl-2">
            <div class="form-group">
                {!! Form::select('module', (!empty($modules) ? $modules : array()), (!empty(request()->module) ? request()->module : null), ['class' => 'form-control', 'placeholder' => 'Select a module']) !!}
            </div>
        </div>
        <div class="col-md-4 col-lg-2 col-xl-2">
            {!! Form::select('user', (!empty($users) ? $users : array()), (!empty(request()->user) ? request()->user : null), ['class' => 'form-control', 'placeholder' => 'Select A User']) !!}
        </div>
        <div class="col-md-4 col-lg-2 col-xl-2">
            <div class="form-group">
                {!! Form::select('status', (!empty($statuses) ? $statuses : array()), (!empty(request()->status) ? request()->status : null), ['class' => 'form-control', 'placeholder' => 'Status']) !!}
            </div>
        </div>
        <div class="col-md-4 col-lg-2 col-xl-2">
            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
        </div>
    </form>
</div>
<div class="text-center">
    {!! $dev_task->links() !!}
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
            <th rowspan="2">Sr.No.</th>
            <th rowspan="2" class="text-center">Module</th>
            <th rowspan="2" class="text-left">Page</th>
            <th rowspan="2" class="text-center">Details</th>
            <th rowspan="2" class="text-center">Attachements</th>
            <th rowspan="2" class="text-center">Date Created</th>
            <th rowspan="2" class="text-center">User Assigned</th>
            <th rowspan="2" class="text-center">Date Assigned</th>
            <th rowspan="2" class="text-center">Status</th>
            <th rowspan="2" class="text-center">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dev_task as $key => $value)
                @php
                    $developer_task = \App\DeveloperTask::Find($value['id']);
                    $user = \App\User::Find($value['user_id']);
                @endphp
                <tr>
                    <td>{{$value['id']}}</td>
                    <td>{{$developer_task->developerModule->name ?? 'N/A'}}</td>
                    <td>N/A</td>
                    <td>{{$value['task']}}</td>
                    <td>N/A</td>
                    <td>{{\Carbon\Carbon::parse($value['created_at'])->format('d M, Y')}}</td>
                    <td>{{ ($user) ? $user->name : ""}}</td>
                    <td>{{!empty($value['start_time']) ? $value['start_time'] : 'N/A' }}</td>
                    <td>{{$value['status']}}</td>
                    <td width=20%>
                      <div class="d-flex">
                        <input type="text" class="form-control quick-message-field input-sm" name="message" placeholder="Message" value="">
                        <input type="hidden" class="form-control" id="number" name="number" value="{{ ($user) ? $user->whatsapp_number : '' }}">
                        <button class="btn btn-sm btn-image send-message" data-userid="{{ ($user) ? $user->id : 0 }}"><img src="/images/filled-sent.png" /></button>
                      </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {!! $dev_task->links() !!}
    </div>
</div>


@endsection 
@section('scripts')
<script>
  var cached_suggestions = localStorage['message_suggestions'];
  var suggestions = [];

$(document).on('click', '.send-message', function() {
    var thiss = $(this);
    var data = new FormData();
    var user_id = $(this).data('userid');
    var message = $(this).siblings('input').val();
    var number = $('#number').val();
    data.append("user_id", user_id);
    data.append("message", message);
    data.append("number", number);
    data.append("status", 1);

    if (message.length > 0) {
      if (!$(thiss).is(':disabled')) {
        $.ajax({
          url: '/whatsapp/sendMessage/user',
          type: 'POST',
         "dataType"    : 'json',           // what to expect back from the PHP script, if anything
         "cache"       : false,
         "contentType" : false,
         "processData" : false,
         "data": data,
         beforeSend: function() {
           $(thiss).attr('disabled', true);
         }
       }).done( function(response) {
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
</script>
@endsection