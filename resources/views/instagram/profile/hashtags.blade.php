@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center" style="background: #CCCCCC;padding: 20px">Hahstag Used by Sololuxury Customers</h1>
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>SN</th>
                    <th>Tag</th>
                    <th>Action</th>
                </tr>

                @foreach($hashtags as $key=>$hashtag)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            <a href="{{ action('HashtagController@showGrid', substr($hashtag, 1)) }}">
                                {{ $hashtag }}
                            </a>
                        </td>
                        <td>
                            @if(in_array(substr($hashtag, 1), $hashlist))
                                <form method="post" action="{{ action('HashtagController@destroy', substr($hashtag, 1)) }}">
                                    <a class="btn btn-info" href="{{ action('HashtagController@showGrid', substr($hashtag, 1)) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-info" href="{{ action('HashtagController@edit', substr($hashtag, 1)) }}">
                                        <i class="fa fa-info"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form method="post" action="{{action('HashtagController@store')}}">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ substr($hashtag, 1) }}">
                                    <button class="btn btn-sm btn-success">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            box-shadow: 0px 0px 16px -8px rgba(0,0,0,1);
            border-radius:20px;
            display: flex;
            margin-bottom: 20px;
        }

        .card-left {
            background-image: linear-gradient(180deg,#21c8f6,#637bff);
            padding: 20px;
            border-radius: 20px 21px 0px 0px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .card-right {
            padding: 0 20px;
            text-align: justify;
            display:flex;
            flex-direction: column;
        }

        .card-meta {
            display:flex;
            padding-bottom: 20px;
            font-size: 12px;
            font-weight: bold;
            flex-direction: row;
        }

        a {
            color: inherit;
            text-decoration: none;
            font-weight: bold;
        }

        .card-left img {
            margin: 20px 0;
            border-radius: 100%;
        }
        .card-left span {
            text-align: center;
            font-size: 12px;
        }

        .card-link {
            background: rgba(0,0,0,0.3);
            padding: 10px;
            border-radius: 25px;
            font-size: 12px;
        }
    </style>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection

@section('scripts')
    <script>
        // var nextPage = null;
        {{--$(document).ready(function() {--}}
        {{--    $.ajax({--}}
        {{--        url: '{{action('InstagramProfileController@show', 1)}}?page=1',--}}
        {{--        success: function(response) {--}}
        {{--            console.log(response);--}}
        {{--            let instagramData = response[0];--}}
        {{--            instagramData.forEach(function(item) {--}}
        {{--            if (item.username != undefined) {--}}
        {{--                let data = '<div class="col-md-4"><div class="card"><div class="card-left"> <a href="https://instagram.com/'+item.username+'" class="card-link">'+item.username+'</a><img src="'+item.profile_pic_url+'" /><button class="btn btn-sm btn-warning">Message</button> </div> <div class="card-right"> <h3 class="card-title">'+item.name+'</h3> <p>'+item.bio+'</p> <div class="card-meta"> <span>Fling <i class="fa fa-users"></i> '+item.following+'</span> &nbsp; <span>Flwrs <i class="fa fa-users"></i> '+item.followers+'</span> &nbsp;<span>Posts <i class="fa fa-image"></i> '+item.media+'</span></div> </div></div></div>';--}}
        {{--                $("#cards").append(data);--}}
        {{--            }--}}
        {{--            nextPage = response[1].next_page_url;--}}
        {{--            });--}}
        {{--        }--}}
        {{--    });--}}

        {{--    $(window).scroll(function() {--}}
        {{--        if($(window).scrollTop() + $(window).height() == $(document).height()) {--}}
        {{--            if (nextPage == null) {--}}
        {{--                $("#loading").hide();--}}
        {{--                return;--}}
        {{--            }--}}

        {{--            $("#loading").show();--}}

        {{--            $.ajax({--}}
        {{--                url: nextPage,--}}
        {{--                success: function(response) {--}}
        {{--                    console.log(response);--}}
        {{--                    let instagramData = response[0];--}}
        {{--                    instagramData.forEach(function(item) {--}}
        {{--                        if (item.username != undefined) {--}}
        {{--                            let data = '<div class="col-md-4"><div class="card"><div class="card-left"> <a href="https://instagram.com/'+item.username+'" class="card-link">'+item.username+'</a><img src="'+item.profile_pic_url+'" /><button class="btn btn-sm btn-warning">Message</button> </div> <div class="card-right"> <h3 class="card-title">'+item.name+'</h3> <p>'+item.bio+'</p> <div class="card-meta"> <span>Fling <i class="fa fa-users"></i> '+item.following+'</span> &nbsp; <span>Flwrs <i class="fa fa-users"></i> '+item.followers+'</span> &nbsp;<span>Posts <i class="fa fa-image"></i> '+item.media+'</span></div> </div></div></div>';--}}
        {{--                            $("#cards").append(data);--}}
        {{--                        }--}}
        {{--                        nextPage = response[1].next_page_url;--}}
        {{--                    });--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
    </script>
@endsection