@extends('layouts.app')

@section('title', __('Edit post'))

@section('content')

    <div class="row">
        <div class="col-md-6 col-lg-4">
            @if($post->comment)
                <div class="alert alert-danger">
                    <i class="fe fe-alert-triangle mr-2"></i> {{ $post->comment }}
                </div>
            @endif

            <div class="card media-manager">
                <div class="card-header pl-3">
                    <h3 class="card-title">@lang('Media')</h3>
                    <div class="card-options">
                        <form method="post" action="{{ route('post.destroy', $post) }}" onsubmit="return confirm('@lang('Confirm delete?')');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fe fe-trash"></i> @lang('Delete post')
                            </button>
                        </form>
                    </div>
                </div>
                <div class="pl-3 pr-3 pb-3">
                    @if($post->all_media)
                        <div class="row gutters-sm dm-viewer-container">
                        @foreach($post->all_media as $media)
                            <div class="col-6 col-sm-4 mt-3">
                                <img src="{{ asset($media->getUrl('preview')) }}" data-original="{{ asset($media->getUrl()) }}" alt="{{ $media->name }}" class="rounded">
                            </div>
                        @endforeach
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fe fe-alert-triangle mr-2"></i> @lang('No media files found')
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <form method="post" action="{{ route('post.update', $post) }}">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header pl-3">
                        <h3 class="card-title">@lang('Edit post')</h3>
                        <div class="card-options">
                            <strong class="text-default">{{ $post->account->username }}</strong>
                        </div>
                    </div>
                    <div class="p-3">


                        @if($post->type != 'story')
                        <div class="form-group">
                            <label class="form-label">@lang('Location')</label>
                            <select name="location" class="form-control" disabled>
                                <option>{{ $post->location }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Caption')</label>
                            <textarea rows="3" name="caption" class="form-control caption-text" placeholder="@lang('Compose a post caption')" data-emojiable="true">{{ $post->caption }}</textarea>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input is-scheduled" name="scheduled" value="1" {{ $post->isScheduled ? 'checked' : '' }}>
                                <span class="custom-control-label">@lang('Schedule')</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="fe fe-calendar"></i></span>
                                <input type="text" name="scheduled_at" value="{{ $post->scheduled_at }}" class="form-control dm-date-time-picker scheduled-at" placeholder="@lang('Schedule at')">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer p-3">
                        <button type="submit" class="btn btn-primary btn-block btn-schedule d-none">
                            <i class="fe fe-clock"></i> @lang('Schedule post')
                        </button>
                        <button type="submit" class="btn btn-success btn-block btn-publish mt-0">
                            <i class="fe fe-check"></i> @lang('Publish now')
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            @if($post->type == 'story')
                <div class="card preview-story">
                    <div class="image" style="background-image: url('{{ $post->previewImage }}');"></div>
                </div>
            @else
                <div class="card preview-timeline">
                    <div class="pt-5 pb-2 text-center">
                        <img src="{{ asset('public/img/ig-logo.png') }}" alt="Instagram">
                    </div>
                    <div class="p-3 d-flex align-items-center px-2">
                        <div class="avatar avatar-md mr-3 dm-load-avatar" data-username="{{ $post->account->username }}"></div>
                        <div>
                            <div><strong>{{ $post->account->username }}</strong></div>
                            @if($post->location)
                                <small class="d-block text-muted">
                                    {{ $post->location }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="image-preview">
                        <div id="carousel" class="carousel slide">
                            <ol class="carousel-indicators">
                                @foreach($post->all_media as $media)
                                <li data-target="#carousel" data-slide-to="{{ $loop->index }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach($post->all_media as $media)
                                <div class="carousel-item"><img src="{{ asset($media->getUrl()) }}" alt="" class="d-block w-100" data-holder-rendered="true"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="preview-caption">
                            {{ $post->caption }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop