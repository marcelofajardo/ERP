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
        <h1><u>Edit Schedule</u> (<a href="{{ action('InstagramController@showSchedules') }}">All Schedules</a>)</h1>
        <form action="{{ action('InstagramController@updateSchedule', $schedule->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="approval">Approval Status &nbsp;</label>
                <input type="checkbox" name="approval" id="approval" {{ $schedule->status === 0 ? '' : 'checked' }}>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" rows="5" id="description" class="form-control">{{$schedule->description}}</textarea>
            </div>

            <br>
            <h3>Scheduled Images ({{ $schedule->images->count() }})</h3>

            <div class="row">
                @foreach($schedule->images->get() as $image)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-image">
                                <img alt="Instagram Image" style="width: 100%;" src="{!! file_exists(public_path().'/uploads/social-media/'.$image->filename) ? asset('uploads/social-media') . '/' . $image->filename : asset('uploads') .'/'. $image->filename !!}">
                            </div>
                            <div class="card-footer">
                                <div class="form-group">
                                    <label for="description_{{$image->id}}">Description</label>
                                    <textarea class="form-control" name="description_{{$image->id}}" id="description_{{$image->id}}" rows="4">{{ $image->schedule ? $image->schedule->description : '' }}</textarea>
                                </div>
                                <input type="hidden" name="images[]" value="{{$image->id}}">
                                <div class="form-group">
                                    <input type="checkbox" name="selected_images[]" {{ $image->schedule ? 'checked' : '' }} value="{{ $image->id }}" id="image_{{$image->id}}">
                                    <label for="image_{{$image->id}}">Included To Schedule</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <br>
                <br>
                <h4>Save Changes?</h4>
                <button class="btn btn-info btn-lg">Update Changes</button>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ action('InstagramController@attachMedia', $schedule->id) }}" method="post">
                    @csrf
                    <button href="{{ action('InstagramController@attachMedia', $schedule->id) }}" class="btn btn-primary">Add Product Images</button>
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