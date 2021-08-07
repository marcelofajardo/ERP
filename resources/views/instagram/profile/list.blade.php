@extends('layouts.app')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center" style="background: #CCCCCC;padding: 20px">Customer Instagram Profiles</h1>
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>User Information</th>
                    <th>Followers</th>
                    <th>Following</th>
                </tr>
                @foreach($instagramProfiles as $key=>$item)
                    @if ($item)
                        <tr>
                            <td style="vertical-align: top">
                                {{ $key+1 }}
                            </td>
                            <td style="vertical-align: top">
                                <table class="table table-striped">
                                    <tr><td>Customer Name</td> <th> {{ $item['customer']['name'] ?? 'N/A' }}</th></tr>
                                    <tr><td>Customer IG Name</td> <th> {{ $item['name'] }}</th></tr>
                                    <tr><td>Customer IG Username</td> <th> {{ $item['username'] }}</th></tr>
                                    <tr><td>Followers</td> <td> {{ $item['followers_count'] }}</td></tr>
                                    <tr><td>Following</td> <td> {{ $item['following_count'] }}</td></tr>
                                    <tr><td>Bio</td> <td> {{ $item['bio'] }}</tr>
                                </table>
                            </td>
                            <td style="vertical-align: top">
                                <table class="table table-striped">
                                    @foreach($item['followers'] as $follower)
                                        <tr>
                                            <td>
                                                <a class="show-overview" data-username="{{$follower['username']}}" data-uid="{{$follower['pk']}}">{{ '@' . $follower['username'] }}</a>
                                            </td>
                                            <td>
                                                @if(!\App\ColdLeads::where('username', $follower['username'])->first() && !\App\Customer::where('instahandler', $follower['username'])->first())
                                                    <button  class="btn btn-info btn-sm add add-to-cold-leads username-{{$follower['username']}}" data-type="cold" data-username="{{$follower['username']}}" data-id="{{$follower['pk']}}" data-name="{{$follower['name'] ?? $follower['username']}}" data-bio="{{$follower['bio'] ?? ''}}" data-imageurl="{{$follower['profile_pic_url']}}" class="btn btn-sm btn-info" title="Add To Cold Leads">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                                @if(!\App\Customer::where('instahandler', $follower['username'])->first())
                                                    <button class="btn btn-sm btn-success add add-to-customers username-{{$follower['username']}}" data-type="customer" data-username="{{$follower['username']}}" data-id="{{$follower['pk']}}" data-name="{{$follower['name'] ?? $follower['username']}}" data-bio="{{$follower['bio'] ?? ''}}" data-imageurl="{{$follower['profile_pic_url']}}" class="btn btn-sm btn-success" title="Add to Customers">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                            <td style="vertical-align: top">
                                <table class="table">
                                    @foreach($item['following'] as $follower)
                                        <tr>
                                            <td>
                                                <a class="show-overview" data-username="{{$follower['username']}}" data-uid="{{$follower['pk']}}">{{ '@' . $follower['username'] }}</a>
                                            </td>
                                            <td>
                                            <td>
                                                @if(!\App\ColdLeads::where('username', $follower['username'])->first() && !\App\Customer::where('instahandler', $follower['username'])->first())
                                                    <button  class="btn btn-info btn-sm add add-to-cold-leads username-{{$follower['username']}}" data-type="cold" data-username="{{$follower['username']}}" data-id="{{$follower['pk']}}" data-name="{{$follower['name'] ?? $follower['username']}}" data-bio="{{$follower['bio'] ?? ''}}" data-imageurl="{{$follower['profile_pic_url']}}" class="btn btn-sm btn-info" title="Add To Cold Leads">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                                @if(!\App\Customer::where('instahandler', $follower['username'])->first())
                                                    <button class="btn btn-sm btn-success add add-to-customers username-{{$follower['username']}}" data-type="customer" data-username="{{$follower['username']}}" data-id="{{$follower['pk']}}" data-name="{{$follower['name'] ?? $follower['username']}}" data-bio="{{$follower['bio'] ?? ''}}" data-imageurl="{{$follower['profile_pic_url']}}" class="btn btn-sm btn-success" title="Add to Customers">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
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