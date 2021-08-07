@extends('layouts.app')

@section('content')
    <h3 class="text-center">Post Images On Media</h3>
    <div class="row">
        <div class="col-md-12 m-5 text-center">
            <form class="form-inline" style="margin: 10px auto; width: 800px;" method="get" action="{{ action('InstagramController@showImagesToBePosted') }}">
                <div class="form-group">
                    <label for="category">Categories</label>
                    {!! $category_selection !!}
                </div>
                <div class="form-group">
                    <label for="brand">Select Brands</label>
                    <select class="form-control select-multiple" name="brand[]" id="brand" multiple style="display: none" placeholder="Select Brand...">
                        @foreach ($brands as $brand)
                            <option {{ in_array($brand->id, $selected_brands) ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group ml-5">
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" data-provide="slider" data-slider-min="0" data-slider-max="10000000" data-slider-step="10" data-slider-value="[{{ $price[0].','.$price[1] }}]"/>
                </div>

                <div class="form-group ml-5">
                    <button type="submit" class="btn">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {!! $images->links() !!}
        </div>
    </div>
    <div class="row">
        @foreach($images as $key => $image)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-image">
                        <img alt="Instagram Image" style="width: 100%;" src="{{ $image->filename ? (asset('uploads/social-media') . '/' . $image->filename) : ($image->getMedia(config('constants.media_tags'))->first() ? $image->getMedia(config('constants.media_tags'))->first()->getUrl() : '') }}">
                    </div><!-- card image -->

                    <div class="card-content">
                        <span class="card-title">
                            <span style="font-size: 14px;">
                                @if ($image->schedule)
                                    <span class="schedule-icon-{{$key}} font-weight-bold {{ $image->schedule->scheduled_for ? 'text-success' : 'text-danger' }}">
                                        <i class="fa fa-clock-o text-click"></i> <span>
                                            {{ $image->schedule->scheduled_for ? $image->schedule->scheduled_for->diffForHumans() : 'Not Scheduled' }}
                                        </span>
                                    </span>
                                    &nbsp;&nbsp;
                                    <span class="post-icon-{{$key}} font-weight-bold {{ $image->schedule->status ? 'text-success' : 'text-danger' }}">
                                        <i class="fa {{ $image->schedule->status ? 'fa-check' : 'fa-times' }} text-click"></i> <span>
                                            {{ $image->schedule->status ? 'Posted' : 'Not Posted' }}
                                        </span>
                                    </span>
                                    &nbsp;&nbsp;
                                    <span class="facebook-icon-{{$key}} font-weight-bold {{ $image->schedule->facebook ? 'text-success' : 'text-danger' }}">
                                        <i class="fa fa-facebook text-click"></i> <span>Facebook</span>
                                    </span>
                                    &nbsp;&nbsp;
                                    <span class="instagram-icon-{{$key}} font-weight-bold {{ $image->schedule->instagram ? 'text-success' : 'text-muted' }}">
                                        <i class="fa fa-instagram text-click"></i> <span>Instagram</span>
                                    </span>
                                @else
                                    <span class="schedule-icon-{{$key}} text-danger font-weight-bold"><i class="fa fa-clock-o text-click"></i> <span>Not Scheduled</span></span>
                                    &nbsp;&nbsp;
                                    <span class="post-icon-{{$key}} text-danger font-weight-bold"><i class="fa fa-times text-click"></i> <span>Not Posted</span></span>
                                    &nbsp;&nbsp;
                                    <span class="facebook-icon-{{$key}} text-danger font-weight-bold"><i class="fa fa-facebook text-click"></i> <span>Facebook</span></span>
                                    &nbsp;&nbsp;
                                    <span class="instagram-icon-{{$key}} text-muted font-weight-bold"><i class="fa fa-instagram text-click"></i> <span>Instagram</span></span>
                                @endif
                            </span>
                        </span>
                        <button type="button" class="btn btn-custom pull-right show-details s-d-{{$key}}" data-pid="{{ $key }}" data-media-id="{{ $image->id }}" aria-label="Left Align">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </div><!-- card content -->
                    {{--<div class="card-action">--}}
                        {{--<span class="text-muted" title="{{ $image->created_at }}">--}}
                          {{--<strong>--}}
                             {{--{{ $image->created_at->diffForHumans() }}--}}
                          {{--</strong>--}}
                        {{--</span>--}}
                    {{--</div><!-- card actions -->--}}
                    <div class="card-reveal reveal-{{ $key }}">
                        <span class="card-title">Post To FB/IG</span> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div data-pid="{{ $key }}" data-post-id="{{ $image->id }}" class="comments-content">
                            @if ($image->schedule)
                                <br>
                                <span class="font-weight-bold {{ $image->schedule->scheduled_for ? 'text-success' : 'text-danger' }}">
                                    <i class="fa fa-clock-o text-click"></i> <span class="schedule-status-{{$key}}">
                                        Schedule: {{ $image->schedule->scheduled_for ? $image->schedule->scheduled_for->diffForHumans() : 'Not Scheduled' }}
                                    </span>
                                </span>
                                <br>
                                <span class="font-weight-bold {{ $image->schedule->status ? 'text-success' : 'text-danger' }}">
                                    <i class="fa {{ $image->schedule->status ? 'fa-check' : 'fa-times' }} text-click"></i> <span class="post-status-{{$key}}">
                                        Status: {{ $image->schedule->status ? 'Posted' : 'Not Posted' }}
                                    </span>
                                </span>
                                <br>
                                <span class="font-weight-bold {{ $image->schedule->facebook ? 'text-success' : 'text-danger' }}">
                                    <i class="fa fa-facebook text-click"></i> <span class="post-status-{{$key}}">&nbsp;{{ $image->schedule->facebook ? 'Posting To' : 'Not Posting To' }} Facebook</span>
                                </span>
                                <br>
                                <span class="font-weight-bold {{ $image->schedule->instagram ? 'text-success' : 'text-muted' }}">
                                    <i class="fa fa-instagram text-click"></i> <span class="post-status-{{$key}}">{{ $image->schedule->instagram ? 'Posting To' : 'Not Posting To' }} Instagram</span>
                                </span>
                                <br>
                                <br>
                                <div>
                                    <strong>Description</strong>
                                    <br>
                                    {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a class="text-info" href="https://www.instagram.com/explore/tags/$1">#$1</a>', $image->schedule->description) !!}
                                </div>
                            @else
                                <form action="{{ action('InstagramController@postMedia') }}" method="post" class="post-submit" data-pid="{{$key}}">
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <div class="mt-4 form-group">
                                        <input type="checkbox" name="facebook" id="facebook_{{$key}}">
                                        <label for="facebook_{{$key}}">Facebook</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="instagram" id="instagram_{{$key}}">
                                        <label for="instagram_{{$key}}">Instagram</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="schedule-handle" data-pid="{{$key}}" type="checkbox" name="is_scheduled" id="is_scheduled_{{$key}}">
                                        <label data-pid="{{$key}}" for="is_scheduled_{{$key}}">Schedule Post</label>
                                    </div>
                                    <div class="form-group schedule-{{$key}}" style="display: none">
                                        <label for="date">Schedule Date & Time</label>
                                        <input type="text" class="form-control datepicker" name="date" value="{{ date('Y-m-d') }}">
                                        <div class="row">
                                            <div class="container">
                                                <input type="number" value="0" placeholder="Enter Hour" min="0" max="23" class="form-control mt-1 col-md-6" name="hour">
                                                <input type="number" value="0" min="0" max="59" class="form-control mt-1 col-md-6" name="minute">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description-{{$key}}">Description</label>
                                        <textarea class="form-control" id="description_{{$key}}" placeholder="About this post..." name="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-info form-control">
                                            <i class="fa fa-send"></i> Post
                                        </button>
                                    </div>
                                </form>
                                {!! Form::open(['method' => 'DELETE','route' => ['image.grid.delete', $image->id],'style'=>'display:inline']) !!}
                                <button type="submit" class="btn btn-image btn-danger btn-block"><img src="/images/delete.png" /></button>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div><!-- card reveal -->
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {!! $images->links() !!}
        </div>
    </div>
    <br>
    <br>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script>
        $('.card-reveal .close').on('click',function(){
            $(this).parent().slideToggle('slow');
        });

        $('.show-details').on('click',function() {
            let id = $(this).attr('data-pid');
            $('.reveal-' + id).slideToggle('slow');
        });
        $(document).ready(function() {
            $(".select-multiple").multiselect();
            $('.post-submit').submit(function (event) {
                let id = $(this).attr('data-pid');
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ action('InstagramController@postMedia') }}',
                    data: formData,
                    type: 'POST',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert("Task completed successfully! We will now refresh page for you.");
                            location.reload();
                        }
                    },
                    error: function () {
                        alert("We could not post or schedule the image at the moment.");
                    }
                });
            });
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $('.schedule-handle').on('click', function () {
            let value = $(this).is(':checked');
            let id = $(this).attr('data-pid');
            if (value) {
                $('.schedule-'+id).slideDown('fast');
            } else {
                $('.schedule-'+id).slideUp('fast');
            }
        });
    </script>
@endsection