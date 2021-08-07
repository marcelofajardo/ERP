@extends('layouts.app')

@section('favicon' , 'instagram.png')

@section('title', 'Instagram Info')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Grid For: #{{ $hashtag }} ({{ $media_count }} Posts)</h1>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif
        </div>

        <div class="col-md-12 text-center">
            @if ($maxId !== '' || $maxId = 'END')
                <a class="btn btn-info mb-4" href="{{ action('HashtagController@edit', $hashtag) }}">FIRST PAGE</a>
                <a class="btn btn-info mb-4" href="{{ action('HashtagController@edit', $hashtag) }}?maxId={{($maxId && $maxId != 'END') ? $maxId : ''}}">NEXT</a>
            @endif
        </div>

        <div class="col-md-12">
            <table class="table-striped table">
                <tr>
                    <th colspan="3">
                        <h2 class="text-center">Relavent Hashtags</h2>
                    </th>
                </tr>
                <tr>
                    <th>S.N</th>
                    <th>Hashtag</th>
                    <th>Actions</th>
                </tr>
                @php $k = 1 @endphp
                @foreach($relatedHashtags['related'] as $item)
                    <tr>
                        <td>{{$k}}</td>
                        @php $k++ @endphp
                        <th>
                            <a href="{{ action('HashtagController@showGrid', $item['name']) }}">
                                #{{ $item['name'] }}
                            </a>
                        </th>
                        <td>
                            <a class="btn btn-info" href="{{ action('HashtagController@showGrid', $item['name']) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a class="btn btn-info" href="{{ action('HashtagController@edit', $item['name']) }}">
                                <i class="fa fa-info"></i>
                            </a>

                            @if ($x = \App\HashTag::where('hashtag', $item['name'])->first())
                                <strong>{{ $x->rating }} STARS</strong>
                            @else
                                <form method="post" action="{{ action('HashtagController@store') }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $item['name'] }}">
                                    <div class="form-group mt-2" style="display: inline;">
                                        <select name="rating" id="rating" class="form-control" style="width: 150px !important;display: inline !important;">
                                            <option value="5">5 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="2">2 Stars</option>
                                            <option value="1">1 Stars</option>
                                        </select>
                                        <button class="btn btn-sm btn-success">Rate</button>
                                    </div>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table-striped table-bordered table">
                    <tr>
                        <th>S.N</th>
                        <th>User</th>
                        <th>Post URL</th>
                        <th>Image</th>
                        <th style="width: 400px;">Caption</th>
                        <th>Number of Likes</th>
                        <th>Number Of Comments</th>
                        <th>Location</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($medias as $key=>$post)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><a href="https://instagram.com/{{$post['username']}}">{{$post['username']}}</a></td>
                            <td><a href="https://instagram.com/p/{{$post['code']}}">Visit Post</a></td>
                            <td>
                                @if ($post['media_type'] === 1)
                                    <a href="{{$post['media']}}"><img src="{{ $post['media'] }}" style="width: 200px;"></a>
                                @elseif ($post['media_type'] === 2)
                                    <video controls src="{{ $post['media'] }}" style="width: 200px"></video>
                                @elseif ($post['media_type'] === 8)
                                    @foreach($post['media'] as $m)
                                        @if ($m['media_type'] === 1)
                                            <a href="{{$m['url']}}"><img src="{{ $m['url'] }}" style="width: 100px;"></a>
                                        @elseif($m['media_type'] === 2)
                                            <video controls src="{{ $m['url'] }}" style="width: 200px"></video>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td style="word-wrap: break-word">
                                <div style="width:390px;">
                                    {{ $post['caption'] }}
                                </div>
                            </td>
                            <td>{{ $post['like_count'] }}</td>
                            <td>{{ $post['comment_count'] }}</td>
                            <td>{!! ($post['location']['name'] ?? '') . '<br>' . ($post['location']['city'] ?? '')  !!}</td>
                            <td>{{ $post['created_at'] }}</td>
                            <td>
                                <button title="Reply via Instagram" type="button" class="btn btn-default btn-image" data-toggle="modal" data-target="#instagram-{{$key}}">
                                    <i class="fa fa-reply"></i>
                                </button>

                            {{--                            <form action="{{ action('CustomerController@store') }}" method="post">--}}
                            {{--                                <button title="Add To Customers" class="btn btn-success">--}}
                            {{--                                    <i class="fa fa-plus"></i>--}}
                            {{--                                </button>--}}
                            {{--                            </form>--}}

                            <!-- The Modal -->
                                <div class="modal" id="instagram-{{$key}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Reply To Comment On Post</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{ action('ReviewController@replyToPost') }}">
                                                    <input type="hidden" name="media_id" value="{{$post['media_id']}}">
                                                    <input type="hidden" name="username" value="{{$post['username']}}">
                                                    <div class="form-group">
                                                        <label for="id">Username</label>
                                                        <select name="id" id="id" class="form-control">
                                                            <option value="0">Select Username</option>
                                                            @foreach($accounts as $account)
                                                                <option value="{{$account->id}}">{{ $account->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message">Message</label>
                                                        <input type="text" name="message" id="message" placeholder="Type message..." class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-image">
                                                            <i class="da fa-reply"></i> Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="col-md-12 text-center">
            @if ($maxId !== '' || $maxId = 'END')
                <a class="btn btn-info mb-4" href="{{ action('HashtagController@edit', $hashtag) }}">FIRST PAGE</a>
                <a class="btn btn-info mb-4" href="{{ action('HashtagController@edit', $hashtag) }}?maxId={{($maxId && $maxId != 'END') ? $maxId : ''}}">NEXT</a>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
        });

        $(document).on('click', '.load-comment', function() {
            let mediaId = $(this).attr('data-media-id');
            let postCode = $(this).attr('data-post-code');
            $.ajax({
                url: '{{ action('HashtagController@loadComments', '') }}'+'/'+mediaId,
                success: function(response) {
                    let comments = response.comments;
                    if (response.has_more_comments==false) {
                        $('.load-more-'+mediaId).hide();
                    }
                    $('#comments-'+mediaId).html('');
                    comments.forEach(function(comment) {
                        let form = '<form action="{{ action('ReviewController@createFromInstagramHashtag') }}" method="post">@csrf<input type="hidden" name="date" value="'+comment.created_at_time+'"><input type="hidden" name="code" value="'+postCode+'"><input type="hidden" name="post" value="'+response.caption.text+'"><input type="hidden" name="comment" value="'+comment.text+'"><input type="hidden" name="poster" value="'+response.caption.user.username+'"><input type="hidden" name="commenter" value="'+comment.user.username+'"><input type="hidden" name="media_id" value="'+mediaId+'"><button class="btn btn-sm btn-success"><i class="fa fa-check"></i></button></form>';
                        let data = '<tr><td>'+comment.user.username+'</td><td>'+comment.text+'</td><td>'+comment.created_at+'</td><td>'+form+'</td></tr>';
                        $('#comments-'+mediaId).append(data);
                    });
                }
            });
        });

        function loadComments(postId) {

        }
    </script>
@endsection