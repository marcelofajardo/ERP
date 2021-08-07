@extends('layouts.app')

@section('content')
    @if(Session::has('message'))
        <div class="row mt-5">
            <div class="col-md-6 col-md-offset-3">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        </div>
    @endif
    <div class="mt-5">
        <h1><u>Edit Schedule: {{ $ad->name }}</u> (<a href="{{ action('SocialController@getSchedules') }}">All Schedules</a>)</h1>
        <h3>Scheduled For: {{ substr($ad->scheduled_for, 0, 10) }}</h3>
        <hr>
        <form>
            @csrf
            <div class="row">
                @if (count($images))
                    @foreach($images as $image)
                        <div class="col-md-3">
                            <img src="{{ $image['image'] }}" alt="Image" class="img-responsive">
                        </div>
                    @endforeach
                @else
                    <div class="mt-2 col-md-6 col-md-offset-3 alert alert-info">
                        <h2 class="text-center">No images yet!</h2>
                    </div>
                @endif
            </div>

        </form>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ action('SocialController@attachMedia', $ad->id) }}" method="post">
                    @csrf
                    <button class="btn btn-primary">Add Approved Images</button>
                </form>
                <form action="{{ action('SocialController@attachProducts', $ad->id) }}" method="post">
                    @csrf
                    <button class="btn btn-primary">Add Product Images</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script>
        var scheduling = false;
        $(document).ready(function() {
            $('.start-schedule').click(function() {
                scheduling = !scheduling;
                if (scheduling) {
                    $('.card-action, .schedule-to-be-shown-slide').slideDown();
                    $('.schedule-to-be-shown').show('fast');
                    $(this).html('Stop Scheduling');
                } else {
                    $('.card-action, .schedule-to-be-shown-slide').slideUp();
                    $('.schedule-to-be-shown').hide('fast');
                    $(this).html('Start Scheduling');
                }
            });

            $('#calendar').fullCalendar({
                header: {
                    right: "month,agendaWeek,agendaDay, today prev,next",
                },
                events: '{{ action('InstagramController@getScheduledEvents') }}',
                eventClick: function(calEvent, jsEvent, view) {
                    $('#image_container').empty();

                    let download_images = [];
                    let image = '';
                    calEvent.image_names.forEach(function(img) {
                        image += '<div class="col-md-6"><div class="card"><div class="card-image"><a><img src="' + img.name + '" style="width:100%" /></a></div></div></div>';
                        download_images.push(img.id);
                    });

                    $('#image_container').append($(image));

                    jQuery.noConflict();
                    $('#calendarModal').modal('toggle');
                },
                eventRender: function(event, eventElement) {
                    if (event.image_names) {
                        let imgHTML = '<div>';
                        event.image_names.forEach(function(image) {
                            imgHTML += ("<img src='" + image.name +"' width='50' height='50''>");
                        });
                        eventElement.find("div.fc-content").append(imgHTML+'</div>');
                    }
                }
            });

            $('.post-now').click(function() {
                let scheduleId = $(this).attr('data-schedule-id');
                let self = this;
                $.ajax({
                    url: '{{ action('InstagramController@postMediaNow', '') }}'+'/'+scheduleId,
                    type: 'get',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert("Successfully posted! We will reload the page for recent data.");
                            location.reload();
                        }
                    },
                    beforeSend: function() {
                        $(self).html('Posting... <i class="fa fa-spinner"></i>');
                    }
                });
            });

            $('.post-delete').click(function() {
                let scheduleId = $(this).attr('data-schedule-id');
                let self = this;
                $.ajax({
                    url: '{{ action('InstagramController@cancelSchedule', '') }}'+'/'+scheduleId,
                    type: 'get',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert("Successfully deleted! We will reload the page for recent data.");
                            location.reload();
                        }
                    },
                    beforeSend: function() {
                        $(self).html('<i class="fa fa-spinner"></i> Deleting... ');
                    }
                });
            });
        });
    </script>
@endsection