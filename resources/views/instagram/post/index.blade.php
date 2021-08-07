@extends('layouts.app')

@section('title', __('Posts'))

@section('content')

    @if($accounts->count())

        <div class="page-header">
            <h1 class="page-title">
                @lang('Posts')
            </h1>
            

        @if($data->count() > 0)
        <div class="row row-cards">
            @foreach($data as $post)
            <?php $detail = json_decode($post->ig); $media = \Plank\Mediable\Media::whereIn('id',$detail->media)->first(); ?>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    @if($media)
                        <a href="https://www.instagram.com/p/{{ $detail->code ?? null }}" target="_blank">
                            <img src="{{ $media->getUrl() }}" alt="{{ $post->caption }}" height="250px" width="250px">
                        </a>
                    @else
                        
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1200px-No_image_available.svg.png" alt="{{ $post->caption }}" height="250px" width="250px">
                        
                    @endif

                    <div class="card-body d-flex flex-column p-3">
                        <div><a href="https://www.instagram.com/{{ $post->account->last_name }}" target="_blank" class="text-default"><strong>{{ $post->account->first_name }}</strong></a></div>

                        <small class="d-block dm-show-more">{{ $post->caption }}</small>

                        <div class="d-flex align-items-center pt-2">
                            <div>
                                <small class="text-muted">
                                    {{ $post->posted_at }}
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($data->count() == 0)
            <div class="alert alert-primary text-center">
                <i class="fe fe-alert-triangle mr-2"></i> @lang('No posts found')
            </div>
        @endif

        {{ $data->appends( Request::all() )->links() }}

    @endif

@stop