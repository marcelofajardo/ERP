@extends('layouts.app')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center" style="background: #CCCCCC;padding: 20px">Competitor's Followers</h1>
        </div>
        <div class="col-md-12 text-center">
            {!! $followers->links() !!}
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>User Information</th>
                    <th>Action</th>
                </tr>
                @foreach($processedFollowers as $key=>$item)
                    @if ($item)
                        <tr id="rec_{{$item['uid']}}">
                            <td style="vertical-align: top">
                                {{ $key+1 }}
                            </td>
                            <td style="vertical-align: top">
                                <table class="table table-striped">
                                    <tr><td>IG Name</td> <th> {{ $item['name'] }}</th></tr>
                                    <tr><td>IG Username</td> <th> {{ $item['username'] }}</th></tr>
                                    <tr><td>Followers</td> <td> {{ $item['followers_count'] }}</td></tr>
                                    <tr><td>Following</td> <td> {{ $item['following_count'] }}</td></tr>
                                    <tr><td>Bio</td> <td> {{ $item['bio'] }}</tr>
                                </table>
                            </td>
                            <td>
                                <button data-id="{{$item['uid']}}" class="btn btn-info reject-lead">Reject/Hide Lead</button>
                                <button data-id="{{$item['uid']}}" class="btn btn-info approve-lead">Approve Lead For DM</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="col-md-12 text-center">
            {!! $followers->links() !!}
        </div>
        <div class="card-modal">
            <div id="content"></div>
            <div class="text-center" id="loading">
                <h3 class="text-center">Loading Customer Profile...</h3>
                <img style="width: 100px;" src="{{ asset('images/loading_new.gif') }}" />
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>

        .card-modal {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            width: 600px;
            background-image: linear-gradient(180deg,#21c8f6,#637bff);
            border-radius: 20px;
        }
        .card {
            box-shadow: 0px 0px 16px -8px rgba(0,0,0,1);
            border-radius:20px;
            display: flex;
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

        .show-overview {
            color: #2ab27b !important;
            font-weight: bolder;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection

@section('scripts')
    <script>
        var nextPage = null;
        $(document).ready(function() {
            $(document).mousemove(function(e) {
                window.x = e.pageX;
                window.y = e.pageY;
            });

            $('.reject-lead').click(function (event) {
                $(this).attr('disabled', 'true');
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ action('CompetitorPageController@hideLead', '') }}" + '/' + id,
                    success: function() {
                        $('#rec_'+id).hide('slow');
                    }
                });
            });

            $('.accept-lead').click(function (event) {
                $(this).attr('disabled', 'true');
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ action('CompetitorPageController@approveLead', '') }}" + '/' + id,
                    success: function() {
                        $('#rec_'+id).hide('slow');
                        alert("Lead Approved!");
                    }
                });
            });

            $('.add').click(function() {
                let nme = $(this).attr('data-name');
                let username = $(this).attr('data-username');
                let image = $(this).attr('data-imageurl');
                let bio = $(this).attr('data-bio');
                let type = $(this).attr('data-type');
                let id = $(this).attr('data-id');

                let rating = prompt("Give this user a rating. 1 to 10");
                let self = this;

                $.ajax({
                    url: "{{ action('InstagramProfileController@add') }}",
                    type: 'post',
                    data: {
                        name: nme,
                        username: username,
                        image: image,
                        bio: bio,
                        type: type,
                        _token: "{{csrf_token()}}",
                        id: id,
                        rating: rating
                    },
                    success: function() {
                        alert("Added to " + type + " database successfully!");
                        $(self).attr('disabled', 'true');
                        $(self).html('<i class="fa fa-check"></i>');
                    }
                });
            });

            $(document).mouseup(function(e)
            {
                var container = $(".card-modal");
                if (!container.is(e.target) && container.has(e.target).length === 0)
                {
                    container.hide();
                }
            });

            $(document).on('click', '.show-overview', function() {
                let username = $(this).attr('data-username');
                $("#content").html('');
                $.ajax({
                    url: '{{action('InstagramProfileController@show', '')}}/'+username,
                    success: function(response) {
                        $("#content").html('');
                        let item = response;
                        $(".username-"+item.username).attr('data-name', item.name);
                        $(".username-"+item.username).attr('data-bio', item.bio);
                        let data = '<div class="card"><div class="card-left"> <a href="https://instagram.com/'+item.username+'" class="card-link">'+item.username+'</a><img src="'+item.profile_pic_url+'" /> </div> <div class="card-right"> <h3 class="card-title">'+item.name+'</h3> <p>'+item.bio+'</p> <div class="card-meta"> <span>Fling <i class="fa fa-users"></i> '+item.following_count+'</span> &nbsp; <span>Flwrs <i class="fa fa-users"></i> '+item.followers_count+'</span> &nbsp;<span>Posts <i class="fa fa-image"></i> '+item.media+'</span></div> </div></div>';
                        $("#content").append(data);
                        $("#loading").hide();
                    }, beforeSend: function() {
                        $("#loading").show();
                        $(".card-modal").fadeIn("slow");
                    }
                });
            });
        });
    </script>
@endsection