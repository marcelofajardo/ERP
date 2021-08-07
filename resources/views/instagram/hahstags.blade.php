@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Instagram HashTags</h1>
            <p>This is the list of the hashtags with their posts</p>
        </div>
        <div class="col-md-12">
            <div class="row">
                @foreach($posts as $key=>$post)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-image">
                                <img style="width: 100%;" src="{!! $post->image_url !!}">
                            </div><!-- card image -->

                            <div class="card-content">
                                <span class="card-title">
                                    #{{ $post->hashtag }}
                                </span>
                                <button type="button" class="btn btn-custom pull-right show-details s-d-{{$key}}" data-pid="{{ $key }}" data-media-id="{{ $post->id }}" aria-label="Left Align">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                            </div><!-- card content -->
                            <div class="card-action">
                                <span class="text-muted" title="{{ $post['created_time'] ?? 'N/A' }}">
                                  <strong>
                                     {{ \Carbon\Carbon::createFromTimestamp(strtotime($post->created_at))->diffForHumans() }}
                                  </strong>
                                </span>
                                <p>
                                    {!! $post->description ? preg_replace('/(?:^|\s)#(\w+)/', ' <a class="text-info" href="https://www.instagram.com/explore/tags/$1">#$1</a>', $post->description) : '' !!}
                                </p>
                            </div><!-- card actions -->
                            <div class="card-reveal reveal-{{ $key }}">
                                <span class="card-title">Comments (<span class="count-for-{{$key}}">{{ $post->comments ? count($post->comments) : '0' }}</span>)</span> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <div data-pid="{{ $key }}" data-post-id="{{ $post->id }}" class="comments-content">
                                    @if ($post->comments && count($post->comments))
                                        @foreach($post->comments as $commentKey=>$comment)
                                            @if (!isset($comment[2]))
                                                <p id="comment-{{$post->id}}-{{$commentKey}}">
                                                    <strong>{{ $comment[0] }}</strong>
                                                    <span>{{ $comment[1] }}</span>
                                                    <span class="delete-comment" data-comment-key="{{$commentKey}}" data-post-id="{{$post->id}}">DELETE</span>
                                                </p>
                                            @elseif ($comment[2])
                                                <p id="comment-{{$post->id}}-{{$commentKey}}">
                                                    <strong>{{ $comment[0] }}</strong>
                                                    <span>{{ $comment[1] }}</span>
                                                    <span style="float: right; background: #FF0000;color:#fff;font-weight:bolder;border-radius: 50%;padding: 2px 10px;" class="delete-comment" data-comment-key="{{$commentKey}}" data-post-id="{{$post->id}}">X</span>
                                                </p>
                                            @endif
                                        @endforeach
                                    @else
                                        <p><strong>There are no comments loaded at this moment.</strong></p>
                                    @endif
                                </div>
                            </div><!-- card reveal -->
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script>
        var cid = null;
        $(function(){

            $('.show-details').on('click',function() {
                let id = $(this).attr('data-pid');
                $('.reveal-'+id).slideToggle('slow');
            });

            $('.card-reveal .close').on('click',function(){
                $(this).parent().slideToggle('slow');
            });

            $('.delete-comment').click(function(e) {
                e.preventDefault();
                let postId = $(this).attr('data-post-id');
                let commentKey = $(this).attr('data-comment-key');
                $.ajax({
                    url: '{{action('InstagramController@deleteComment')}}',
                    data: {
                        post_id: postId,
                        comment_key: commentKey
                    },
                    type: 'get',
                    success: function(response) {
                        $("#comment-"+postId+'-'+commentKey).hide('slow');
                    },
                    error: function() {
                        alert("Error deleting the product!")
                    }
                });
            });
        });


    </script>
@endsection