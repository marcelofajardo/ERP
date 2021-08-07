@extends('layouts.app')

@section('large_content')
    <div>
        <div>
            <h1>Instagram HashTags Listing</h1>
            <div class="row">
                <div class="col-md-4 offset-3">
                    <form method="get" action="{{ action('InstagramController@hashtagGrid') }}">
                        <input type="text" name="query" id="query" class="form-control" placeholder="Search Hashtag..." value="{{ $request->get('query') }}">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if (isset($hashtext))
                <h2 class="center">#{{ $hashtext }}</h2>
                <table class="table table-striped">
                    <tr>
                        <th>SN</th>
                        <th>Auther</th>
                        <th>Description</th>
                        <th>Visit Post</th>
                        <th>Comments</th>
                    </tr>
                    
                    @foreach($comments as $key=>$comment)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $comment->username }}</td>
                            <td>{{ $comment->description }}</td>
                            <td><a target="_new" href="{{ $comment->image_url }}"><img src="{{$comment->image_url}}" style="width: 100px;"></a></td>
                            <td>
                                @if ($comment->comments && is_array($comment->comments))
                                    @foreach($comment->comments as $c)
                                        @if(!isset($comment[2]))
                                            <li>{{ $c[0] }} => {{$c[1]}}</li>
                                        @elseif ($comment[2])
                                            <li>{{ $c[0] }} => {{$c[1]}}</li>
                                        @endif
                                    @endforeach
                                @else
                                    <strong>N/A</strong>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                @foreach($comments as $key=>$commentx)
                    <h2 class="center">#{{ $key }}</h2>
                    <table class="table table-striped">
                        <tr>
                            <th>Auther</th>
                            <th>Description</th>
                            <th>Visit Post</th>
                            <th>Comments</th>
                        </tr>
                         @foreach($commentx as $comment)
                            <tr>
                                <td>{{ $comment->username }}</td>
                                <td>{{ $comment->description }}</td>
                                <td><a target="_new" href="{{ $comment->image_url }}"><img src="{{$comment->image_url}}" style="width: 100px;"></a></td>
                                <td>
                                    @if ($comment->comments && is_array($comment->comments))
                                        @foreach($comment->comments as $c)
                                            @if(!isset($comment[2]))
                                                <li>{{ $c[0] }} => {{$c[1]}}</li>
                                            @elseif ($comment[2])
                                                <li>{{ $c[0] }} => {{$c[1]}}</li>
                                            @endif
                                        @endforeach
                                    @else
                                        <strong>N/A</strong>
                                    @endif
                                </td>
                            </tr>
                         @endforeach
                    </table>
                @endforeach
            @endif
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