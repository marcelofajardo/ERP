@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Broadcast Calendar</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
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

    <div class="row">
      <div class="col-12">
        <div id="calendar"></div>
      </div>
    </div>

@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function() {
       $('#calendar').fullCalendar({
         // editable: true,
         header: {
           right: "month,agendaWeek,agendaDay, today prev,next",
         },
          events: [
            @foreach ($message_queues as $group_id => $message_queue)
              {
                title: "Message group {{ $group_id }}",
                start: "{{ $message_queue->sending_time }}",
              },
            @endforeach
          ],
          // eventClick: function(calEvent, jsEvent, view) {
          //   $('#image_container').empty();
          //
          //   var download_images = [];
          //   var image = '<div class="row">';
          //   calEvent.image_names.forEach(function(img) {
          //     image += '<div class="col-md-4"><img src="' + img.name + '" class="img-responsive" /></div>';
          //     download_images.push(img.id);
          //   });
          //   image += '</div>';
          //
          //   $('#image_container').append($(image));
          //   $('#download_images_field').val(JSON.stringify(download_images));
          //
          //   // jQuery.noConflict();
          //   $('#calendarModal').modal('toggle');
          // },
          eventRender: function(event, eventElement) {
            if (event.image_names) {
              event.image_names.forEach(function(image) {
                eventElement.find("div.fc-content").prepend("<img src='" + image.name +"' width='50' height='50'>");
              });
            }
          },
          // eventDrop: function(event, delta, revertFunc) {
          //   $.ajax({
          //     type: "POST",
          //     url: "{{ route('image.grid.update.schedule') }}",
          //     data: {
          //       _token: "{{ csrf_token() }}",
          //       images: event.image_names,
          //       date: event.start.format('Y-MM-DD H:mm')
          //     }
          //   }).done(function(response) {
          //
          //   }).fail(function(response) {
          //     alert('Could not update schedule');
          //     console.log(response);
          //   });
          // }
        });
    });
  </script>
@endsection
