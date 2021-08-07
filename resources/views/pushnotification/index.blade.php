@extends('layouts.app')


@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="float-left">
      <h2>Notifications</h2>
    </div>

  </div>
</div>

<div class="row">
  <div class="col-lg-12 margin-tb">
    <form action="{{ route('pushNotification.index') }}" method="GET" class="form-inline align-items-start">
        <div class="form-group mr-3 mb-3">
          <input name="term" type="text" class="form-control" id="product-search"
                 value="{{ isset($term) ? $term : '' }}"
                 placeholder="name">
        </div>

        <div class="form-group mr-3 mb-3">
          @php $users = \App\Helpers::getUserArray(\App\User::all()); @endphp
          {!! Form::select('user[]',$users, (isset($user) ? $user : ''), ['placeholder' => 'Select a User','class' => 'form-control', 'multiple' => true]) !!}
        </div>

      <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
    </form>
  </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<div id="exTab2" class="container">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Lead</a>
    </li>
    <li>
      <a href="#2" data-toggle="tab">Order</a>
    </li>
    <li>
      <a href="#3" data-toggle="tab">Message</a>
    </li>
    <li>
      <a href="#4" data-toggle="tab">Task</a>
    </li>
  </ul>
  <div class="tab-content ">
    <!-- Pending task div start -->
    <div class="tab-pane active" id="1">
      @if (count($lead_notifications) > 0)
        <table class="table notification-table">
          @foreach ($lead_notifications as $notification)
          <tr>
            <td>
              <a class="notification-link" href="{{ route('leads.show', $notification->model_id) }}">
                {{ $notification->message }} at {{ Carbon\Carbon::parse($notification->created_at)->format('d-m H:i') }}
              </a>
            </td>
            @if ($notification->isread == 0)
              <td style="width: 20px"><button class="btn btn-link markReadPush" data-id="{{ $notification->id }}">Complete</button></td>
            @else
              <td style="width: 20px">{{ Carbon\Carbon::parse($notification->updated_at)->format('d-m H:i') }}</td>
            @endif
          </tr>
          @endforeach
        </table>

        {!! $lead_notifications->appends(Request::except('lead_page'))->links() !!}
      @else
        <span class="d-block mt-3">You are up to date with Lead Notifications</span>
      @endif
    </div>

    <div class="tab-pane" id="2">
      @if (count($order_notifications) > 0)
        <table class="table notification-table">
          @foreach ($order_notifications as $notification)
          <tr>
            <td>
              <a class="notification-link" href="{{ route('order.show', $notification->model_id) }}">
                {{ $notification->message }} at {{ Carbon\Carbon::parse($notification->created_at)->format('d-m H:i') }}
              </a>
            </td>
            @if ($notification->isread == 0)
              <td style="width: 20px"><button class="btn btn-link markReadPush" data-id="{{ $notification->id }}">Complete</button></td>
            @else
              <td style="width: 20px">{{ Carbon\Carbon::parse($notification->updated_at)->format('d-m H:i') }}</td>
            @endif
          </tr>
          @endforeach
        </table>

        {!! $order_notifications->appends(Request::except('order_page'))->links() !!}
      @else
        <span class="d-block mt-3">You are up to date with Order Notifications</span>
      @endif
    </div>

    <div class="tab-pane" id="3">
      @if (count($message_notifications) > 0)
        <table class="table notification-table">
          @foreach ($message_notifications as $index => $notification)
            @foreach ($notification as $item)
              @if ($loop->first)
                <tr>
                  <td>
                    @php
                      if ($item['model_type'] == 'leads') {
                        $link = route('leads.show', $item['model_id']);
                      } elseif ($item['model_type'] == 'order') {
                        $link = route('order.show', $item['model_id']);
                      } else {
                        $link = route('customer.show', $item['model_id']);
                      }
                    @endphp
                    <a class="notification-link" href="{{ $link }}">
                      @if (strpos($item['message'], '<br>') !== false)
                        {{ substr($item['message'], 0, strpos($item['message'], '<br>')) }}
                      @else
                        {{ $item['message'] }}
                      @endif
                       at {{ Carbon\Carbon::parse($item['created_at'])->format('d-m H:i') }}
                    </a>
                  </td>

                  @if ($item['isread'] == 0)
                    <td style="width: 20px"><button class="btn btn-link markReadPushReminder" data-id="{{ $item['id'] }}">Complete</button></td>
                  @else
                    <td style="width: 20px">{{ Carbon\Carbon::parse($item['updated_at'])->format('d-m H:i') }}</td>
                  @endif
                </tr>
              @endif
            @endforeach
          @endforeach
        </table>

        {!! $message_notifications->appends(Request::except('message_page'))->links() !!}
      @else
        <span class="d-block mt-3">You are up to date with Message Notifications</span>
      @endif
    </div>

    <div class="tab-pane" id="4">
      @if (count($task_notifications) > 0)
        <table class="table notification-table">
          @foreach ($task_notifications as $index => $notification)
            @foreach ($notification as $item)
              @if ($loop->first)
                <tr>
                  <td>
                    <a class="notification-link" href="{{ url('/#task_') . $item['model_id'] }}">
                      {{ $item['message'] }} at {{ Carbon\Carbon::parse($item['created_at'])->format('d-m H:i') }}
                    </a>
                  </td>

                  @if ($item['isread'] == 0)
                    <td style="width: 20px"><button class="btn btn-link markReadPushReminder" data-id="{{ $item['id'] }}">Complete</button></td>
                  @else
                    <td style="width: 20px">{{ Carbon\Carbon::parse($item['updated_at'])->format('d-m H:i') }}</td>
                  @endif
                </tr>
              @endif
            @endforeach
          @endforeach
        </table>

        {!! $task_notifications->appends(Request::except('task_page'))->links() !!}
      @else
        <span class="d-block mt-3">You are up to date with Task Notifications</span>
      @endif
    </div>
  </div>
</div>

{{-- {!! $notifications->appends(Request::except('page'))->links() !!} --}}

<script type="text/javascript">
  $(document).on('click', '.markReadPush', function() {
    var button = $(this);
    var id = $(this).data('id');
    var url = '/pushNotificationMarkRead/' + id;

    markRead(url, button);
  });

  $(document).on('click', '.markReadPushReminder', function() {
    var button = $(this);
    var id = $(this).data('id');
    var url = '/pushNotificationMarkReadReminder/' + id;

    markRead(url, button);
  });

  function markRead(url, button) {
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type:'POST',
      url: url,
      success: function(data) {
        if(data.msg === 'success'){
          button.parent().html(moment(data.updated_at).format('DD-MM H:m'));
          button.remove();
        }
      },
      error: function() {
        alert('Could not mark as complete');
      }
    });
  }
</script>

@endsection
