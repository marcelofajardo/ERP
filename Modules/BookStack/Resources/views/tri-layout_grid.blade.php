@php
$currentRoutes = \Route::current();
//$metaData = \App\Routes::where(['url' => $currentRoutes->uri])->first();
$metaData = '';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
        if(isset($metaData->page_title) && $metaData->page_title!='') {
            $title = $metaData->page_title;
        }else{
            $title = trim($__env->yieldContent('title'));
        }
    ?>
    @if (trim($__env->yieldContent('favicon')))
        <link rel="shortcut icon" type="image/png" href="/favicon/@yield ('favicon')" />
    @elseif (!\Auth::guest())
        <link rel="shortcut icon" type="image/png" href="/generate-favicon?title={{$title}}" />
    @endif
    <title>{{$title}}</title>
    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @if(isset($metaData->page_description) && $metaData->page_description!='')
        <meta name="description" content="{{ $metaData->page_description }}">
    @else
        <meta name="description" content="{{ config('app.name') }}">
    @endif
    

    {{-- <title>{{ config('app.name', 'ERP for Sololuxury') }}</title> --}}

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/richtext.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{asset('js/readmore.js')}}" defer></script>
    <script src="{{asset('/js/generic.js')}}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        .select2-container--open{
            z-index:9999999
        }
        #message-chat-data-box .p1[data-count]:after{
          position:absolute;
          right:10%;
          top:8%;
          content: attr(data-count);
          font-size:90%;
          padding:.1em;
          border-radius:50%;
          line-height:1em;
          color: white;
          background:rgba(255,0,0,.85);
          text-align:center;
          min-width: 1em;
          //font-weight:bold;
        }
        #quick-sidebar {
            padding-top: 35px;
        }
        #notification_unread{
            color:#fff;
        }

        .refresh-btn-stop {
            color:  red
        }

        .refresh-btn-start {
            color:  green
        }

    </style>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>--}}

    @yield('link-css')
    <script>
        let Laravel = {};
        Laravel.csrfToken = "{{csrf_token()}}";
        window.Laravel = Laravel;
    </script>
    {{--I/m geting error in console thats why commented--}}

    {{-- <script>--}}
    {{-- $('.readmore').readmore({--}}
    {{-- speed: 75,--}}
    {{-- moreLink: '<a href="#">Read more</a>',--}}
    {{-- lessLink: '<a href="#">Read less</a>'--}}
    {{-- });--}}
    {{-- </script>--}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}

    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}

    {{-- When jQuery UI is included tooltip doesn't work --}}
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
 

    <script src="{{ asset('js/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('js/calls.js') }}"></script>

    @if (Auth::id() == 3 || Auth::id() == 6 || Auth::id() == 23 || Auth::id() == 56)
    @endif

    <script src="{{ asset('js/custom.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.3.3/bootstrap-slider.min.js"></script>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

    @if(Auth::user())
    {{--<link href="{{ url('/css/chat.css') }}" rel="stylesheet">--}}
    <script>
        window.userid = "{{Auth::user()->id}}";

        window.username = "{{Auth::user()->name}}";

        loggedinuser = "{{Auth::user()->id}}";
    </script>
    @endif
    <script type="text/javascript">
        var BASE_URL = '{{ config('app.url') }}';
    </script>


    <!-- Fonts -->

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Styles -->

    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">


    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.3.3/css/bootstrap-slider.min.css">



    @yield("styles")

    <script>
        window.Laravel = '{{!!json_encode(['
        csrfToken '=>csrf_token(),'
        user '=>['
        authenticated '=>auth()->check(),'
        id '=>auth()->check() ? auth()->user()->id : null,'
        name '=>auth()->check() ? auth()->user()-> name : null,]])!!}';
    </script>


    {{-- <script src="https://js.pusher.com/4.3/pusher.min.js"></script>

    <script>
      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      var pusher = new Pusher('df4fad9e0f54a365c85c', {
          cluster: 'ap2',
          forceTLS: true
      });
    </script> --}}

    <script>
        initializeTwilio();
    </script>
    @if (Auth::id() == 3 || Auth::id() == 6 || Auth::id() == 23 || Auth::id() == 56)


    @endif

    {{-- <script src="{{ asset('js/pusher.chat.js') }}"></script>

    <script src="{{ asset('js/chat.js') }}"></script> --}}

    <style type="text/css">
        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
        }
    </style>
</head>

<body>


    <div id="app">

 
        @if(Auth::check())
            <!---start section for the sidebar toggle -->
            <nav id="quick-sidebar">
            </nav>
            <!-- end section for sidebar toggle -->
        @endif
        @if (trim($__env->yieldContent('large_content')))
            <div class="col-md-11">
                @yield('large_content')
            </div> 
        @endif


         <a id="back-to-top" href="javascript:;" class="btn btn-light btn-lg back-to-top" role="button"><i class="fa fa-chevron-up"></i></a>
    </div>
 

    @php

        $url = strtolower(str_replace(array('https://', 'http://'),array('', ''),config('app.url')));
        $url = str_replace('/','',$url);
        $site_account_id = App\StoreWebsiteAnalytic::where('website',$url)->first();
        $account_id = "";
        if(!empty($site_account_id)){
            $account_id = $site_account_id->account_id;
        }
    @endphp


    <!-- Scripts -->

   {{--  @include('partials.chat')--}}
    <div id="loading-image-preview" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif')50% 50% no-repeat;display:none;">
    </div>


    <!-- Like page plugin script  -->

    @yield('models')

    {{-- <script>(function(d, s, id) {

  var js, fjs = d.getElementsByTagName(s)[0];

  if (d.getElementById(id)) return;

  js = d.createElement(s); js.id = id;

  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2&appId=2045896142387545&autoLogAppEvents=1';

  fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));</script> --}}

    @yield('scripts')  
    @if ( !empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REMOTE_ADDR'])  && $_SERVER['REMOTE_ADDR'] != "127.0.0.1" && !stristr($_SERVER['HTTP_HOST'], '.mac') )
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $account_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        //gtag('config', 'UA-171553493-1');
    </script>
    @endif
    <script> 

        
 
        $(document).ready(function(){
            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });
            // scroll body to 0px on click
            $('#back-to-top').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 400);
                return false;
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            $(".select2-vendor").select2({});
        });

         
 
        $('select.select2-discussion').select2({tags: true});
       
     


    </script>
    @if ($message = Session::get('actSuccess'))
        <script>
            toastr['success']('<?php echo $message; ?>', 'success');
        </script>
    @endif
    @if ($message = Session::get('actError'))
        <script>
            toastr['error']('<?php echo $message; ?>', 'error');
        </script>
    @endif

</body>

</html>
