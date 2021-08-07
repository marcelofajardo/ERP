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

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript" src="https://media.twiliocdn.com/sdk/js/client/v1.14/twilio.min.js"></script>

    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.0.5/dist/js/tabulator.min.js"></script>

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

    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.3.3/css/bootstrap-slider.min.css">

    <link href="https://unpkg.com/tabulator-tables@4.0.5/dist/css/tabulator.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ url('css/global_custom.css') }}">
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
        .dropdown.dots>a:after{
            display: none;
        }
        .dropdown.dots>a{
            line-height:30px;
        }
        #navbarSupportedContent{
            display: flex !important;
        }
        .nav-item.dropdown.dots {
            min-width: 35px;
            padding-right: 15px;
        }
        @media(max-width:1350px) {


            .navbar-nav > li {
                min-width: 94px;
                padding-right: 15px;
            }
        }
        .navbar{
            padding: 0.1rem 0.8rem;
            border-bottom: 1px solid #ddd;
            /*margin-bottom: 8px !important;*/
            border-radius:0px;
        }
        .navbar-brand{
            padding: 15px 4px;
            font-size: 20px;
            font-weight: 700;
            margin-right: 0;
        }
        @media(min-width:1700px){
            #navs{
                padding-left: 40px;
            }
        }
            .navbar-nav>li {
                min-width: 40px;
                /*padding-right: 30px;*/
            }
            /*.navbar-brand{*/
            /*    margin-right: 20px;*/
            /*}*/
    </style>
</head>

<body>

    <div class="modal fade" id="instructionAlertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Instruction Reminder</h3>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <a href="" id="instructionAlertUrl" class="btn btn-secondary mx-auto">OK</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="developerAlertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Developer Task Reminder</h3>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <a href="" id="developerAlertUrl" class="btn btn-secondary mx-auto">OK</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="masterControlAlertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Master Control Alert</h3>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <a href="" id="masterControlAlertUrl" class="btn btn-secondary mx-auto">OK</a>
                </div>
            </div>
        </div>
    </div>

    {{-- <div id="fb-root"></div> --}}


    <div class="notifications-container">

        <div class="stack-container stacked" id="leads-notification"></div>

        <div class="stack-container stacked" id="orders-notification"></div>

        {{-- <div class="stack-container stacked" id="messages-notification"></div> --}}

        <div class="stack-container stacked" id="tasks-notification"></div>

    </div>


    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">

            <!--<div class="container container-wide">-->

            <div class="container-fluid pr-0">

                <a class="navbar-brand pl-0" href="{{ url('/task') }}">

                    {{ config('app.name', 'Laravel') }}

                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">

                    <span class="navbar-toggler-icon"></span>

                </button>


                <div class="collapse navbar-collapse pr-0" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav mr-auto">


                    </ul>


                    <!-- Right Side Of Navbar -->

                    <ul id="navs" class="navbar-nav ml-auto " style="display:flex;text-align: center;flex-grow: 1;justify-content: space-between">

                        <!-- Authentication Links -->

                        @guest

                        <li class="nav-item">

                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>

                        </li>

                        {{--<li class="nav-item">

                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>

                        </li>--}}

                        @else

                        <?php

                        //getting count of unreach notification
                        $unread = 0;
                        if(!empty($notifications)){
                            foreach($notifications as $notification)
                            {
                                if(!$notification->isread)
                                {
                                    $unread++;
                                }

                            }
                        }



                        /* ?>
                        @include('partials.notifications')
                        <?php */ ?>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('pushNotification.index') }}">New Notifications</a>
                        </li> --}}


                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Product <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}

                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Listing<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Selection<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productselection.index') }}">Selections Grid</a>
                                                @if(auth()->user()->checkPermission('productselection-create'))
                                                <a class="dropdown-item" href="{{ route('productselection.create') }}">Add New</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ url('/excel-importer') }}">Excel Import </a>
                                                <a class="dropdown-item" href="{{ url('/excel-importer/mapping') }}">Add Mapping For Master </a>
                                                <a class="dropdown-item" href="{{ url('/excel-importer/tools-brand') }}">Add Mapping For Excel</a>
                                                <a class="dropdown-item" href="{{ url('/excel-importer/log') }}">Excel Importer Log</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Supervisor<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productsupervisor.index') }}">Supervisor Grid</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Image Cropper<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productimagecropper.index') }}">Image Cropper Grid</a>
                                                <a class="dropdown-item" href="{{ action('ProductCropperController@getApprovedImages') }}">Approved Crop grid</a>
                                                <a class="dropdown-item" href="{{ action('ProductCropperController@getListOfImagesToBeVerified') }}">Crop Approval Grid</a>
                                                <a class="dropdown-item" href="{{ action('ProductCropperController@cropIssuesPage') }}">Crop Issue Summary</a>
                                                <a class="dropdown-item" href="{{ action('ProductCropperController@showRejectedCrops') }}">Crop-Rejected Grid</a>
                                                <a class="dropdown-item" href="{{ action('ProductCropperController@showCropVerifiedForOrdering') }}">Crop-Sequencer</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Images<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('google.search.product') }}">Google Image Search</a>
                                                <a class="dropdown-item" href="{{ route('manual.image.upload') }}">Manual Image Upload</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Attribute<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                @if(auth()->user()->checkPermission('productlister-list'))
                                                <a class="dropdown-item" href="{{ route('products.listing') }}?cropped=on">Attribute edit page</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ action('ProductController@approvedListing') }}?cropped=on">Approved listing</a>
                                                <a class="dropdown-item" href="{{ action('ProductController@approvedListing') }}?cropped=on&status_id=2">Listings awaiting scraping</a>
                                                <a class="dropdown-item" href="{{ action('ProductController@approvedListing') }}?cropped=on&status_id=13">Listings unable to scrape</a>
                                                <a class="dropdown-item" href="{{ action('ProductController@showRejectedListedProducts') }}">Rejected Listings</a>
                                                <a class="dropdown-item" href="{{ action('AttributeReplacementController@index') }}">Attribute Replacement</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Approver<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productapprover.index') }}">Approver Grid</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>In Stock<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productinventory.instock') }}">In Stock</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>In Delivered<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productinventory.indelivered') }}">In Delivered</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Inventory<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('productinventory.index') }}">Inventory Grid</a>
                                                <a class="dropdown-item" href="{{ route('productinventory.list') }}">Inventory List</a>
                                                <a class="dropdown-item" href="{{ route('product-inventory.new') }}">New Inventory List</a>
                                                <a class="dropdown-item" href="{{ route('productinventory.inventory-list') }}">Inventory Data</a>
                                                <a class="dropdown-item" href="{{ route('product-inventory.new') }}">New Inventory List</a>
                                                <a class="dropdown-item" href="{{ route('listing.history.index') }}">Product Listing history</a>
                                                <a class="dropdown-item" href="{{ route('product.category.index.list') }}">Product Category</a>
                                                <a class="dropdown-item" href="{{ route('product.color.index.list') }}">Product Color history</a>
                                            </ul>
                                        </li>
                                        @if(auth()->user()->isAdmin())
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Quick Sell<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('quicksell.index') }}">Quick Sell</a>

                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a class="dropdown-item" href="/drafted-products">Quick Sell List</a>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a class="dropdown-item" href="{{ route('stock.index') }}">Inward Stock</a>
                                        </li>
                                        @endif
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Scraping<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                                <a class="dropdown-item" href="{{ url('scrap/statistics') }}">Statistics</a>
                                                <a class="dropdown-item" href="{{ url('scrap/statistics/server-history') }}">Server History</a>
                                                <a class="dropdown-item" href="{{ url('scrap/generic-scraper') }}">Generic Supplier Scraper</a>
                                                <a class="dropdown-item" href="{{ action('CategoryController@brandMinMaxPricing') }}">Min/Max Pricing</a>
                                                <a class="dropdown-item" href="{{ route('supplier.count') }}">Supplier Category Count</a>
                                                <a class="dropdown-item" href="{{ route('supplier.brand.count') }}">Supplier Brand Count</a>
                                                <a class="dropdown-item" href="{{ url('price-comparison-scraper') }}">Price comparison</a>
                                                <a class="dropdown-item" href="{{ url('scrap/servers/statistics') }}">Scrap server statistics</a>

                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>SKU<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ action('Logging\LogScraperController@logSKU') }}">SKU log</a>
                                                <a class="dropdown-item" href="{{ action('Logging\LogScraperController@logSKUErrors') }}">SKU warnings/errors</a>
                                                <a class="dropdown-item" href="{{ route('sku-format.index') }}">SKU Format</a>
                                                <a class="dropdown-item" href="{{ route('sku.color-codes') }}">SKU Color Codes</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('google.search.product') }}">Search Products by Text</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('google.search.multiple') }}">Multiple products by Text</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('google.search.image') }}">Search Products by Image</a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Purchase<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('purchase.index') }}">Purchase</a>
                                            <a class="dropdown-item" href="{{ route('purchase.grid') }}">Purchase Grid</a>
                                            <a class="dropdown-item" href="{{ route('purchase.calendar') }}">Purchase Calendar</a>
                                            <a class="dropdown-item" href="{{ route('purchase.grid', 'canceled-refunded') }}">Cancel/Refund Grid</a>
                                            <a class="dropdown-item" href="{{ route('purchase.grid', 'ordered') }}">Ordered Grid</a>
                                            <a class="dropdown-item" href="{{ route('purchase.grid', 'delivered') }}">Delivered Grid</a>
                                            <a class="dropdown-item" href="{{ route('purchase.grid', 'non_ordered') }}">Non Ordered Grid</a>
                                            <a class="dropdown-item" href="{{ route('purchaseproductorders.list') }}">Purchase Product Orders</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Supplier<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('supplier.index') }}">Supplier List</a></a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('supplier.product.history') }}">Supplier Product History</a></a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('supplier/category/permission') }}">Supplier Category <br> Permission</a></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Scraping<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('ProductController@productStats') }}">Product Statistics</a>
                                            <a class="dropdown-item" href="{{ action('ProductController@showAutoRejectedProducts') }}">Auto Reject Statistics</a>
                                            <a class="dropdown-item" href="{{ action('ListingPaymentsController@index') }}">Product Listing Payments</a>
                                            <a class="dropdown-item" href="{{ action('ScrapStatisticsController@index') }}">Scrap Statistics</a>
                                            <a class="dropdown-item" href="{{ route('statistics.quick') }}">Quick Scrap Statistics</a>
                                            <a class="dropdown-item" href="{{ action('ScrapController@scrapedUrls') }}">Scrap Urls</a>
                                            <a class="dropdown-item" href="{{ route('scrap.activity') }}">Scrap activity</a>
                                            <a class="dropdown-item" href="{{ route('scrap.scrap_server_status') }}">Scrapper Server Status</a>
                                            <a class="dropdown-item" href="{{ action('ScrapController@showProductStat') }}">Products Scrapped</a>
                                            <a class="dropdown-item" href="{{ action('SalesItemController@index') }}">Sale Items</a>
                                            <a class="dropdown-item" href="{{ action('DesignerController@index') }}">Designer List</a>
                                            <a class="dropdown-item" href="{{ action('GmailDataController@index') }}">Gmail Inbox</a>
                                            <a class="dropdown-item" href="{{ action('ScrapController@index') }}">Google Images</a>
                                            <a class="dropdown-item" href="{{ action('GoogleSearchImageController@searchImageList') }}">Image Search By Google</a>
                                            <a class="dropdown-item" href="{{ action('SocialTagsController@index') }}">Social Tags</a>
                                            <a class="dropdown-item" href="{{ action('DubbizleController@index') }}">Dubzzle</a>
                                            <a class="dropdown-item" href="{{ route('log-scraper.index') }}">Scraper log</a>
                                            <a class="dropdown-item" href="{{ route('log-scraper.api') }}">Scraper Api log</a>
                                            <a class="dropdown-item" href="{{ route('scrap-brand') }}">Scrap Brand</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Crop Reference<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('CroppedImageReferenceController@grid') }}">Crop Reference Grid</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Magento<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('Logging\LogListMagentoController@index') }}">Log List Magento</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="/magento/status">Order Status Mapping</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="/languages">Language</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Logs<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('ProductController@productScrapLog') }}">Status Logs</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('ScrapLogsController@index') }}">Scrap Logs</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('LaravelLogController@index') }}">Laravel Log</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('api-log-list') }}">Laravel API Log</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('LaravelLogController@liveLogs') }}">Live Laravel Log</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('LaravelLogController@scraperLiveLogs') }}">Live Scraper Log</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{action('ProductController@productDescription')}}">Product Description</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{route('products.product-translation')}}">Product translate</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{route('products.product-assign')}}">Assign Products</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{route('products.listing.approved.images')}}/images">Final Apporval Images</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CRM <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}
                                <li class="nav-item">
                                    <a class="dropdown-item" target="_blank" href="/web-message">Communication</a>
                                    <a class="dropdown-item" href="{{route('translation.list')}}">Translations</a>
                                    <a class="dropdown-item" href="{{route('pushfcmnotification.list')}}">FCM Notifications</a>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Customers<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ url('/erp-customer') }}">Customers - NEW</a>
                                            <a class="dropdown-item" href="{{ route('customer.index') }}?type=unread">Customers - unread</a>
                                            <a class="dropdown-item" href="{{ route('customer.index') }}?type=unapproved">Customers - unapproved</a>
                                            <a class="dropdown-item" href="{{ route('customer.index') }}?type=Refund+to+be+processed">Customers - refund</a>
                                            <a class="dropdown-item" href="{{ action('VisitorController@index') }}">Livechat Visitor Logs</a>
                                            <a class="dropdown-item" href="{{ action('ProductController@attachedImageGrid') }}">Attach Images</a>
                                            <a class="dropdown-item" href="{{ action('ProductController@suggestedProducts') }}">Sent Images</a>
                                            <a class="dropdown-item" href="{{ route('chat.dndList') }}">DND Manage</a>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Cold Leads<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ action('ColdLeadsController@index') }}?via=hashtags">Via Hashtags</a>
                                                <a class="dropdown-item" href="{{ action('ColdLeadsController@showImportedColdLeads') }}">Imported Cold leads</a>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Instructions<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Instructions<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('instruction.index') }}">Instructions</a>
                                                <a class="dropdown-item" href="{{ route('instruction.list') }}">Instructions List</a>
                                                <a class="dropdown-item" href="{{ action('KeywordInstructionController@index') }}">Instruction Keyword Instructions</a>
                                                <a class="dropdown-item" href="/instruction/quick-instruction">Quick instructions</a>
                                                <a class="dropdown-item" href="/instruction/quick-instruction?type=price">Quick instructions (price)</a>
                                                <a class="dropdown-item" href="/instruction/quick-instruction?type=image">Quick instructions (attach)</a>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Referral System<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Referral Programs<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('referralprograms.list') }}">List Referral Programs</a>
                                                <a class="dropdown-item" href="{{ route('referralprograms.add') }}">Add Referral Programs</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Friend Referral<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('referfriend.list') }}">List Friend Referral</a>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Leads<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('leads.index') }}">Leads</a>
                                        <a class="dropdown-item" href="{{ action('LeadsController@erpLeads') }}">Leads (new)</a>
                                        <a class="dropdown-item" href="{{ action('LeadsController@erpLeadsHistory') }}">Leads History</a>
                                        <a class="dropdown-item" href="{{ route('lead-queue.approve') }}">Leads Queue Approval</a>
                                        <a class="dropdown-item" href="{{ route('lead-queue.index') }}">Leads Queue (Approved)</a>
                                        <a class="dropdown-item" href="{{ route('leads.create') }}">Add new lead</a>
                                        <a class="dropdown-item" href="{{ route('leads.image.grid') }}">Leads Image grid</a>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Refunds<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('refund.index') }}">Refunds</a>
                                        </li>
                                    </ul>

                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('quick-replies') }}">Quick Replies</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('quick.customer.index') }}">Quick Customer</a>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Orders<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Orders<span class="caret"></span></a>

                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('order.index') }}">Orders</a>
                                                <a class="dropdown-item" href="{{ route('order.create') }}">Add Order</a>
                                                <a class="dropdown-item" href="{{ route('order.products') }}">Order Product List</a>
                                                <a class="dropdown-item" href="{{ route('return-exchange.list') }}">Return-Exchange</a>
                                                <a class="dropdown-item" href="{{ route('return-exchange.status') }}">Return-Exchange Status</a>
                                                <a class="dropdown-item" href="{{ route('order.status.messages') }}">Order Status Messages</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="{{ action('OrderController@viewAllInvoices') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>Invoices<span></span></a>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a class="" href="{{ route('store-website.all.status') }}" role="button" aria-haspopup="true" aria-expanded="false">Statuses<span></span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Customer<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('complaint.index') }}">Customer Complaints</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="{{ route('livechat.get.chats') }}">Live Chat</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="{{ route('livechat.get.tickets') }}">Live Chat Tickets</a>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Missed<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('order.missed-calls') }}">Missed Calls List</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Call<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('order.calls-history') }}">Call history</a>

                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Private<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('stock.private.viewing') }}">Private Viewing</a>

                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Bulk Customer Replies<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ action('BulkCustomerRepliesController@index') }}">Bulk Messages</a>
                                        <a class="dropdown-item" href="{{ action('CustomerCategoryController@index') }}">Categories</a>
                                        <a class="dropdown-item" href="{{ action('KeywordToCategoryController@index') }}">Keywords</a>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Delivery<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('deliveryapproval.index') }}">Delivery Approvals</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Broadcast<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('broadcast.index') }}">Broadcast Grid</a>
                                            <a class="dropdown-item" href="{{ route('broadcast.images') }}">Broadcast Images</a>
                                            <a class="dropdown-item" href="{{ route('broadcast.calendar') }}">Broadcast Calender</a>
                                            <a class="dropdown-item" href="/marketing/instagram-broadcast">Instagram Broadcast</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Marketing<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('whatsapp.config.index') }}">WhatsApp Config</a>
                                            <a class="dropdown-item" href="/marketing/accounts/instagram">Instagram Config</a>
                                            <a class="dropdown-item" href="/marketing/accounts/facebook">Facebook Config</a>
                                            <a class="dropdown-item" href="{{ route('platforms.index') }}">Platforms</a>
                                            <a class="dropdown-item" href="{{ route('broadcasts.index') }}">BroadCast</a>
                                            <a class="dropdown-item" href="/marketing/services">Mailing Service</a>
                                            <a class="dropdown-item" href="{{ route('mailingList') }}">Mailinglist</a>
                                            <a class="dropdown-item" href="{{ route('mailingList-template') }}">Mailinglist Templates</a>
                                            <a class="dropdown-item" href="{{ route('mailingList-emails') }}">Mailinglist Emails</a>
                                            <a class="dropdown-item" href="/mail-templates/mailables">Mailables</a>
                                            <a class="dropdown-item" href="{{ route('emailleads') }}">Email Leads</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Checkout<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('coupons.index') }}">Coupons</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a id="navbarDropdown" class="" href="{{ route('keywordassign.index') }}" role="button">Keyword Assign</a>
                                </li>
                                {{-- START - Purpose : Add new Menu Keyword Response Logs - DEVTASK-4233 --}}
                                <li class="nav-item">
                                    <a id="navbarDropdown" class="" href="{{ route('keywordreponse.logs') }}" role="button">Keyword Response Logs</a>
                                </li>
                                {{-- END - DEVTASK-4233 --}}
                                <li class="nav-item">
                                    <a id="navbarDropdown" class="" href="{{ route('purchase-product.index') }}" role="button">Purchase</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vendor <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('vendors.index') }}">Vendor Info</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('vendor-category.index') }}">Vendor Category</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('vendors.product.index') }}">Product Info</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('developer.vendor.form') }}">Vendor Form</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('developer.supplier.form') }}">Supplier Form</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('vendor-category.permission') }}">Vendor Category Permission</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Users <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>User Management<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('users.index') }}">List Users</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('users.create') }}">Add New</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('userlogs.index') }}">User Logs</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('users.login.index') }}">User Logins</a>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Roles<span class="caret"></span></a>

                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                                <a class="dropdown-item" href="{{ route('roles.index') }}">List Roles</a>
                                                <a class="dropdown-item" href="{{ route('roles.create') }}">Add New</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Permissions<span class="caret"></span></a>

                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('permissions.index') }}">List Permissions</a>
                                                <a class="dropdown-item" href="{{ route('permissions.create') }}">Add New</a>
                                                <a class="dropdown-item" href="{{ route('permissions.users') }}">User Permission List</a>
                                                <a class="dropdown-item" href="{{ route('users.login.ips') }}">User Login IP(s)</a>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('user-management.index') }}">New Management</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ url('api/documentation') }}">API Documentation</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Activity<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('activity') }}">View</a>
                                        </li>


                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('graph_user') }}">User Graph</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('benchmark.create') }}">Add Benchmark</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('ProductController@showListigByUsers') }}">User Product Assignment</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="/calendar">Calendar</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Platforms <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ action('PreAccountController@index') }}">Other Email Accounts
                                    </a>
                                </li>
                                @if(auth()->user()->isAdmin())
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Instagram<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramPostsController@grid') }}">Instagram Posts (Grid)</a>
                                            <a class="dropdown-item" href="{{ action('InstagramPostsController@index') }}">Instagram Posts</a>
                                            <a class="dropdown-item" href="{{ action('HashtagController@influencer') }}">Influencers</a>
                                            <a class="dropdown-item" href="/instagram/hashtag/comments/">Hashtag Comments</a>
                                            <a class="dropdown-item" href="/instagram/direct-message">Direct Message</a>
                                            <a class="dropdown-item" href="/instagram/post">Posts</a>
                                            <a class="dropdown-item" href="/instagram/post/create">Create Post</a>
                                            <a class="dropdown-item" href="/instagram/direct-message">Media</a>
                                            <a class="dropdown-item" href="/instagram/users">Get User Post</a>
                                        </li>

                                        <hr />

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@index') }}">Dashboard</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@accounts') }}">Accounts</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ url('instagram/hashtag') }}">Hashtags</a>
                                        </li>


                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('HashtagController@showGrid', 'sololuxury') }}">Hashtag monitoring & manual Commenting</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('HashtagController@showNotification') }}">Recent Comments (Notifications)</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@showPosts') }}">All Posts</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('TargetLocationController@index') }}">Target Location</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('KeywordsController@index') }}">Keywords For comments</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('HashtagController@showProcessedComments') }}">Processed Comments</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('CompetitorPageController@index') }}?via=instagram">All Competitors On Instagram</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramAutoCommentsController@index') }}">Quick Reply</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ action('UsersAutoCommentHistoriesController@index') }}">Bulk Commenting</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('AutoCommentHistoryController@index') }}">Auto Comments Statistics</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramProfileController@index') }}">Customers followers</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramProfileController@edit', 1) }}">#tags Used by top customers.</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@accounts') }}">Accounts</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.ads.schedules')}}">Ad Schedules</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.ad.create')}}">Create New Ad</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.ad.adset.create')}}">Create New Adset</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.ad.campaign.create')}}">Create New Campaign </a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.get-post.page')}}">See Posts</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.post.page')}}">Post to Page</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.report')}}">Ad Reports</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('social.adCreative.report')}}">Ad Creative Reports</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('complaint.index') }}">Customer Complaints</a>
                                        </li>

                                    </ul>
                                </li>
                                @endif

                                @if(auth()->user()->isAdmin())
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>LiveChat, Inc.<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('VisitorController@index') }}">LiveChat Visitor Log</a>
                                            <a class="dropdown-item" href="{{ action('LiveChatController@setting') }}">LiveChat Settings</a>
                                        </li>
                                    </ul>
                                </li>
                                @endif

                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Facebook<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@showImagesToBePosted') }}">Create Post</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('InstagramController@showSchedules') }}">Schedule A Post</a>
                                        </li>

                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Facebook<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ action('FacebookController@index') }}">Facebook Post</a>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Facebook Groups<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ action('FacebookController@show', 'group') }}">Facebook Groups</a>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Facebook Brand Fan Page<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ action('FacebookController@show', 'brand') }}">Facebook Brand Fan Page</a>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>All Adds<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{route('social.get-post.page')}}">See Posts</a>
                                                <a class="dropdown-item" href="{{route('social.post.page')}}">Post On pgae</a>
                                                <a class="dropdown-item" href="{{route('social.report')}}">Ad report</a>
                                                <a class="dropdown-item" href="{{route('social.adCreative.report')}}">Ad Creative Reports</a>
                                                <a class="dropdown-item" href="{{route('social.ad.campaign.create')}}">Create New Campaign</a>
                                                <a class="dropdown-item" href="{{route('social.ad.adset.create')}}">Create New adset</a>
                                                <a class="dropdown-item" href="{{route('social.ad.create')}}">Create New ad</a>
                                                <a class="dropdown-item" href="{{route('social.ads.schedules')}}">Ad Schedule</a>
                                            </ul>

                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('FacebookPostController@index') }}">Facebook Posts</a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Sitejabber<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('SitejabberQAController@accounts') }}">Account</a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('QuickReplyController@index') }}">Quick Reply</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Pinterest<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ action('PinterestAccountAcontroller@index') }}">Accounts</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Images<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('image.grid') }}">Lifestyle Image Grid</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('image.grid.new') }}">Lifestyle Image Grid New</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('image.grid.approved') }}">Final Images</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('image.grid.final.approval') }}">Final Approval</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('review.index') }}">Reviews
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Bloggers<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('blogger.index')}}">Bloggers</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="seoMenu" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">SEO<span class="caret">
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="seoMenu">
                                                <li class="nav-item dropdown dropdown-submenu">
                                                    <a class="dropdown-item" href="{{ action('BackLinkController@displayBackLinkDetails') }}">Back Link Details</a>
                                                    <a class="dropdown-item" href="{{ action('BrokenLinkCheckerController@displayBrokenLinkDetails') }}">Broken Link Details</a>
                                                    <a class="dropdown-item" href="{{ action('AnalyticsController@showData') }}">New Google Analytics</a>
                                                    <a class="dropdown-item" href="{{ action('AnalyticsController@customerBehaviourByPage') }}">Customer Behaviour By Page</a>
                                                    <a class="dropdown-item" href="{{ action('SERankingController@getSites') }}">SE Ranking</a>
                                                    <a class="dropdown-item" href="{{ action('ArticleController@index') }}">Article Approval</a>
                                                    <a class="dropdown-item" href="{{ action('ProductController@getSupplierScrappingInfo') }}">Supplier Scrapping Info</a>
                                                    <a class="dropdown-item" href="{{ action('NewDevTaskController@index') }}">New Dev Task Planner</a>
                                                </li>
                                            </ul>
                                </li>

                                <!-- mailchimp -->
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="seoMenu" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">MailChimp<span class="caret">
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="seoMenu">
                                                <li class="nav-item dropdown dropdown-submenu">
                                                    <a href="{{ route('manage.mailchimp') }}">Manage MailChimp</a>

                                                </li>
                                            </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Chatbot<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <!-- <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.keyword.list')}}">Entities</a>
                                        </li> -->
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.question.list')}}">Intents / Entities</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.dialog.list')}}">Dialog</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.dialog-grid.list')}}">Dialog Grid</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.mostUsedWords')}}">Most used words</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.mostUsedPhrases')}}">Most used phrases</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.mostUsedPhrasesDeleted')}}">Most used phrases Updated</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.analytics.list')}}">Analytics</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('chatbot.messages.list')}}">Messages</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Google<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Search<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('google.search.keyword')}}">Keywords</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('google.search.results')}}">Search Results</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Affiliate<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('affiliates.list')}}">Manual Affiliates</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('google.affiliate.keyword')}}">Keywords</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('google.affiliate.results')}}">Search Results</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                 <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Google Web Master<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('googlewebmaster.index')}}">Sites</a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a class="dropdown-item" href="{{ route('googleadsaccount.index') }}">Google AdWords</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="{{ route('digital-marketing.index') }}">Social Digital Marketing
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Plesk<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{route('plesk.domains')}}">Domains</a>
                                                </li>
                                            </ul>
                                        </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Social <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Product --}}
                                @if(auth()->user()->isAdmin())
                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Instagram<span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="/instagram/post">Posts</a>
                                            <a class="dropdown-item" href="/instagram/post/create">Create Post</a>
                                            <a class="dropdown-item" href="/instagram/direct-message">Media</a>
                                            <a class="dropdown-item" href="/instagram/direct">Direct</a>
                                        </li>
                                    </ul>
                                </li>
                                @endif
                            </ul>
                        </li>
                        <li id="developments" class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Development <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                {{-- Sub Menu Development --}}
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ action('NewDevTaskController@index') }}">Devtask Planner</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('development.overview') }}">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('development/list') }}">Tasks</a>
                                </li>
                                  <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('development/summarylist') }}">Quick Dev Task</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{url('task?daily_activity_date=&term=&selected_user=&is_statutory_query=3')}}">Discussion tasks</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('task-types.index') }}">Task Types</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('development.issue.create') }}">Submit Issue</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('deploy-node') }}">Deploy Node</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('master.dev.task') }}">Dev Master Control</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('database.index') }}">Database Size</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('database.states') }}">Database States</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('database-log') }}">Database Log</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('manage-modules.index') }}">Manage Module</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('manage-task-category.index') }}">Manage Task Category</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('erp-log') }}">ERP Log</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('whatsapp.log') }}">Whatsapp Log</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('horizon') }}">Jobs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ url('project-file-manager') }}">Project Directory manager</a>
                                </li>
                            </ul>
                        </li>
                        <li id="product-template" class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Product Templates <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('templates') }}">Templates</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('product.templates') }}">List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ route('templates.type') }}">New List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ action('ProductTemplatesController@imageIndex') }}">Processed Image</a>
                                </li>
                            </ul>
                        </li>


                            @if(auth()->user()->isAdmin())
                                <li id="queues" class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue<span class="caret"></span></a>
                                    <ul class="dropdown-menu multi-level">
                                        <li class="nav-item">
                                            <a class="dropdown-item" href="{{ route('message-queue.index') }}">Message Queue</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="dropdown-item" href="{{ route('message-queue.approve') }}">Message Queue Approval</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="dropdown-item" href="{{ route('message-queue-history.index') }}">Queue History</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif







                    </ul>
                    <div>
                        <div id="nav-dotes"  class="nav-item dropdown dots mr-3 ml-3">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <svg width="16" height="18" viewBox="0 0 16 4" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 2C4 0.9 3.1 -1.35505e-07 2 -8.74228e-08C0.9 -3.93402e-08 -1.35505e-07 0.9 -8.74228e-08 2C-3.93402e-08 3.1 0.9 4 2 4C3.1 4 4 3.1 4 2ZM6 2C6 3.1 6.9 4 8 4C9.1 4 10 3.1 10 2C10 0.9 9.1 -3.97774e-07 8 -3.49691e-07C6.9 -3.01609e-07 6 0.9 6 2ZM12 2C12 3.1 12.9 4 14 4C15.1 4 16 3.1 16 2C16 0.899999 15.1 -6.60042e-07 14 -6.11959e-07C12.9 -5.63877e-07 12 0.9 12 2Z" fill="#757575"></path></svg>
                            </a>

                            <ul id="nav_dots" class="dropdown-menu multi-level ">


                                @if(auth()->user()->isAdmin())
                                    <li class="nav-item dropdown dropdown-submenu">
                                        {{--                                            <a href="#" class="nav-link dropdown-items" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Multi Site<span class="caret"></span></a>--}}
                                        <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">Multi Site<span class="caret"></span></a>

                                        <ul class="dropdown-menu multi-level">
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('magento-productt-errors.index') }}">Magento product push errors</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.index') }}">Store Website</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('site-development-status.stats') }}">Multi Site status</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('content-management.index') }}">Content Management</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.brand.list') }}">Store Brand</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.category.list') }}">Store Category</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.color.list') }}">Store Color</a>
                                                <a class="dropdown-item" href="{{ route('size.index') }}">Size</a>
                                                <a class="dropdown-item" href="{{ route('system.size') }}">System Size</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('landing-page.index') }}">Landing Page</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('newsletters.index') }}">Newsletters</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.price-override.index') }}">Price Override</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('country.duty.list') }}">Country duty list</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('country.duty.index') }}">Country duty search</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.country-group.index') }}">Country Group</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.websites.index') }}">Website</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.website-stores.index') }}">Website Store</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.website-store-views.index') }}">Website Store View</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.page.index') }}">Website Page</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.page.histories') }}">Website Page History</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.product-attribute.index') }}">Product Attribute</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('scrapper.phyhon.index') }}">Site Scrapper Phyhon</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.site-attributes.index') }}">Site Attributes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.category-seo.index') }}">Category seo</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website.cancellation') }}">Cancellation Policy</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('logging.magento.product.api.call') }}">Magento API call</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('product.pricing') }}">Magento Product Pricing</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <li class="nav-item dropdown dropdown-submenu">
                                        {{--                                            <a href="#" class="nav-link dropdown-items" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>--}}

                                        <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">Admin<span class="caret"></span></a>

                                        <ul class="dropdown-menu multi-level">
                                            
                                            <li class="nav-item dropdown">
                                                <a href="{{ route('custom-chat-message.index') }}">Chat Messages</a>
                                            </li>    

                                            {{-- Sub Menu Product --}}
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Cash Flow<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('cashflow.index') }}">Cash Flow</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('voucher.index') }}">Convience Voucher</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('cashflow.mastercashflow') }}">Master Cash Flow</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('dailycashflow.index') }}">Daily Cash Flow</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('budget.index') }}">Budget</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('settings.index')}}">Settings</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('auto.refresh.index')}}">Auto Refresh page</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('budget.index') }}">Hubstaff</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('page-notes') }}">Page Notes</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('page-notes-categories') }}">Page Notes Categories</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="/totem">Cron Package</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('charity') }}">Charity</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            @if(auth()->user()->isAdmin())
                                                <li class="nav-item dropdown">
                                                    <a href="{{ route('twilio-manage-accounts') }}">Twilio Account Management</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a href="{{ route('watson-accounts') }}">Watson Account Management</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a href="{{ route('twilio-call-management') }}">Call Management</a>
                                                </li>
                                                <li class="nav-item dropdown dropdown-submenu">
                                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Legal<span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                        <li class="nav-item dropdown">
                                                            <a class="dropdown-item" href="{{route('lawyer.index')}}"> Lawyers</a>
                                                        </li>

                                                        <li class="nav-item dropdown">
                                                            <a class="dropdown-item" href="{{route('case.index')}}">Cases</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endif
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Old Issues<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/old/') }}">Old Info</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/old/?type=1') }}">Old Out going</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/old/?type=2') }}">Old Incoming</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Duty<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('simplyduty.category.index') }}">SimplyDuty Categories</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('simplyduty.currency.index') }}">SimplyDuty Currency</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('simplyduty.country.index') }}">SimplyDuty Country</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('simplyduty.calculation') }}">SimplyDuty Calculation</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('simplyduty.hscode.index') }}">HsCode</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ action('ProductController@hsCodeIndex') }}">HsCode Generator</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ action('HsCodeController@mostCommon') }}">Most Common</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ action('HsCodeController@mostCommonByCategory') }}">Most Common Category</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('assets-manager.index') }}">Assets Manager</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('email-addresses.index') }}">Email Addresses</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('api-response-message') }}">Api Response Messages</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('services') }}">Services</a>
                                            </li>

                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>System<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('jobs.list')}}">Laravel Queue</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('failedjobs.list')}}">Laravel Failed Queue</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('wetransfer.list')}}">Wetransfer Queue</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('cron.index')}}">Cron</a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <!-- Github -->
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="githubsubmenu" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Github<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="githubsubmenu">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/github/repos') }}">Repositories</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/github/users') }}">Users</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/github/groups') }}">Groups</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/github/pullRequests') }}">Pull requests</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('/github/sync') }}">Synchronise from online</a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <!-- hubstaff -->
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="hubstaffsubmenu" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Hubstaff<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="hubstaffsubmenu">
                                                    {{-- Sub Menu Product --}}

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff/members')  }}">Members</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff/projects') }}">Projects</a>
                                                    </li>

                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff/tasks') }}">Tasks</a>
                                                    </li>
                                                <!-- <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ url('hubstaff/payments') }}">Payments</a>
                                        </li> -->
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff-payment') }}">Payments Report</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff-activities/notification') }}">Activity Notofication</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ url('hubstaff-activities/activities') }}">Activities</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Database<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('database.index') }}">Historical Data</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{ route('database.states') }}">States</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Encryption<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('encryption.index')}}">Encryption Key</a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li class="nav-item dropdown dropdown-submenu">
                                                <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Courier<span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <a class="dropdown-item" href="{{ route('shipment.index') }}">Shipment</a>
                                                </ul>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('email.index') }}">Emails</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('activity') }}">Activity</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ url('env-manager') }}">Env Manager</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('routes.index') }}">Routes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ url('/store-website-analytics/index') }}">Store Website Analytics</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('store-website-country-shipping.index') }}">Store Website country shipping</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('googlefiletranslator.list') }}">Google File Translator</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ url('/google-traslation-settings') }}">Google Translator Setting</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('googlewebmaster.index') }}">Google webmaster</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="dropdown-item" href="{{ route('gt-metrix') }}">GTMetrix analysis</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="nav-item dropdown dropdown-submenu">
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">{{{ isset(Auth::user()->name) ? Auth::user()->name : 'Settings' }}} <span class="caret"></span></a>

                                    <ul class="dropdown-menu multi-level">
                                        {{-- Sub Menu Product --}}

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('mastercontrol.index') }}">Master Control</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('dailyplanner.index') }}">Daily Planner</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('task.list') }}">Tasks List</a>
                                        </li>
                                        @if(auth()->user()->isAdmin())
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{route('password.index')}}">Password Manager</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{route('password.manage')}}">Multiple User Passwords Manager</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{route('document.index')}}">Document manager</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('resourceimg.index') }}">Resource Center</a>
                                            </li>
                                        @endif
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Product<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('products.index')}}">Product</a>
                                                </li>
                                                <li class="nav-item dropdown">

                                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                        Development<span class="caret"></span>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="{{ route('development.index') }}">Tasks</a>
                                                        <a class="dropdown-item" href="{{ route('development.issue.index') }}">Issue List</a>
                                                        <a class="dropdown-item" href="{{ route('development.issue.create') }}">Submit Issue</a>
                                                        <a class="dropdown-item" href="{{ route('development.overview') }}">Overview</a>
                                                    </div>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('category-segment.index')}}">Category Segment</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('category')}}">Category</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{action('CategoryController@mapCategory')}}">Category Reference</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="/category/new-references">New Category Reference</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('brand.index')}}">Brands</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('missing-brands.index')}}">Missing Brands</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('brand/size/chart')}}">Brand Size Chart</a>
                                                </li>
                                                @if(auth()->user()->checkPermission('category-edit'))
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('color-reference.index')}}">Color Reference</a>
                                                    </li>
                                                @endif
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('compositions.index')}}">Composition</a>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="/descriptions">Description</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Customer<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                @if(auth()->user()->isAdmin())
                                                    <li class="nav-item dropdown">
                                                        <a class="dropdown-item" href="{{route('task_category.index')}}">Task Category</a>
                                                    </li>
                                                @endif
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('reply.index')}}">Quick Replies</a>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('autoreply.index')}}">Auto Reples</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('brand.index')}}">Brands</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('brand.logo_data')}}">Brand Logos</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('missing-brands.index')}}">Missing Brands</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('brand/size/chart')}}">Brand Size Chart</a>
                                        </li>
                                        @if(auth()->user()->checkPermission('category-edit'))
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('color-reference.index')}}">Color Reference</a>
                                        </li>
                                        @endif
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{url('/kb/')}}" target="_blank">Knowledge Base</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                                <!------    System Menu     !-------->
                                <li class="nav-item dropdown dropdown-submenu">
                                    {{--                                        <a href="#" class="nav-link dropdown-items" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">System <span class="caret"></span></a>--}}
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">System <span class="caret"></span></a>

                                    <ul class="dropdown-menu multi-level">
                                        {{-- Sub Menu Product --}}

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{route('jobs.list')}}">Queue</a>
                                        </li>
                                    </ul>
                                </li>

                              
                         <!------    System Menu     !-------->

                                <li class="nav-item dropdown dropdown-submenu">
                                    {{--                                        <a href="#" class="nav-link dropdown-item dropdown-items" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin Menu <span class="caret"></span></a>--}}
                                    <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">Admin Menu <span class="caret"></span></a>

                                    <ul class="dropdown-menu multi-level">
                                        {{-- Sub Menu Admin Menu --}}
                                        <li class="nav-item dropdown dropdown-submenu">
                                            <a id="navbarDropdown" class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Database Menu<span class="caret"></span></a>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{route('admin.databse.menu.direct.dbquery')}}">Direct DB Query</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    <div >
                        <div class="nav-item dropdown" id="search_li">
                            <input type="text" class="form-control nav-link" placeholder="Search" style="margin-top : 1%;" onkeyup="filterFunction()" id="search">
                            <ul class="dropdown-menu multi-level" id="search_container">
                            </ul>
                        </div>
                    </div>
                    @if(Auth::check())
                    <nav id="quick-sidebars">
                        <ul class="list-unstyled components mr-1">
                            <li>
                                <a class="notification-button quick-icon" href="#"><span><i class="fa fa-bell fa-2x"></i></span></a>
                            </li>
                            <li>
                                <a class="instruction-button quick-icon" href="#"><span><i class="fa fa-question-circle fa-2x" aria-hidden="true"></i></span></a>
                            </li>
                            <li>
                                <a class="daily-planner-button quick-icon" target="__blank" href="{{ route('dailyplanner.index') }}">
                                    <span><i class="fa fa-calendar-check-o fa-2x" aria-hidden="true"></i></span>
                                </a>
                            </li>
                     

                            <li>
                                <a id="message-chat-data-box" class="quick-icon">
                           <span class="p1 fa-stack has-badge" id="new_message" data-count="@if(isset($newMessageCount)) {{ $newMessageCount }} @else 0 @endif">
                                <i class="fa fa-comment fa-2x xfa-inverse" data-count="4b"></i>
                           </span>
                                </a>
                            </li>
                            <li>
                                <a class="create-zoom-meeting quick-icon" data-toggle="modal" data-target="#quick-zoomModal">
                                    <span><i class="fa fa-video-camera fa-2x" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            <li>
                                <a class="create-easy-task quick-icon" data-toggle="modal" data-target="#quick-create-task">
                                    <span><i class="fa fa-tasks fa-2x" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
                                <li>
                                    <a title="Manual Payment" class="manual-payment-btn quick-icon">
                                        <span><i class="fa fa-money fa-2x" aria-hidden="true"></i></span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a title="Manual Request" class="manual-request-btn quick-icon">
                                    <span><i class="fa fa-credit-card-alt fa-2x" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            <li>
                                <a title="Auto Refresh" class="auto-refresh-run-btn quick-icon">
                                    <span><i class="fa fa-refresh fa-2x" aria-hidden="true"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    @endif
                </div>

            </div>

        </nav>

        @if (Auth::check())

        @if(1 == 2 && auth()->user()->isAdmin())
        <div class="float-container developer-float hidden-xs hidden-sm">
            @php
            $lukas_pending_devtasks_count = \App\DeveloperTask::where('user_id', 3)->where('status', '!=', 'Done')->count();
            $lukas_completed_devtasks_count = \App\DeveloperTask::where('user_id', 3)->where('status', 'Done')->count();
            $rishab_pending_devtasks_count = \App\DeveloperTask::where('user_id', 65)->where('status', '!=', 'Done')->count();
            $rishab_completed_devtasks_count = \App\DeveloperTask::where('user_id', 65)->where('status', 'Done')->count();
            @endphp

            <a href="{{ route('development.index') }}">
                <span class="badge badge-task-pending">L-{{ $lukas_pending_devtasks_count }}</span>
            </a>

            <a href="{{ route('development.index') }}">
                <span class="badge badge-task-completed">L-{{ $lukas_completed_devtasks_count }}</span>
            </a>

            <a href="{{ route('development.index') }}">
                <span class="badge badge-task-other">R-{{ $rishab_pending_devtasks_count }}</span>
            </a>

            <a href="{{ route('development.index') }}">
                <span class="badge badge-task-other right completed">R-{{ $rishab_completed_devtasks_count }}</span>
            </a>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#quickDevelopmentModal">+ DEVELOPMENT</button>
        </div>

        <div class="float-container instruction-float hidden-xs hidden-sm">
            @php
            $pending_instructions_count = \App\Instruction::where('assigned_to', Auth::id())->whereNull('completed_at')->count();
            $completed_instructions_count = \App\Instruction::where('assigned_to', Auth::id())->whereNotNull('completed_at')->count();
            $sushil_pending_instructions_count = \App\Instruction::where('assigned_from', Auth::id())->where('assigned_to', 7)->whereNull('completed_at')->count();
            $andy_pending_instructions_count = \App\Instruction::where('assigned_from', Auth::id())->where('assigned_to', 56)->whereNull('completed_at')->count();
            @endphp

            <a href="{{ route('instruction.index') }}">
                <span class="badge badge-task-pending">{{ $pending_instructions_count }}</span>
            </a>

            <a href="{{ route('instruction.index') }}#verify-instructions">
                <span class="badge badge-task-completed">{{ $completed_instructions_count }}</span>
            </a>

            <a href="{{ route('instruction.list') }}">
                <span class="badge badge-task-other">S-{{ $sushil_pending_instructions_count }}</span>
            </a>

            <a href="{{ route('instruction.list') }}">
                <span class="badge badge-task-other right">A-{{ $andy_pending_instructions_count }}</span>
            </a>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#quickInstructionModal">+ INSTRUCTION</button>
        </div>

        <div class="float-container hidden-xs hidden-sm">
            @php
            $pending_tasks_count = \App\Task::where('is_statutory', 0)->where('assign_to', Auth::id())->whereNull('is_completed')->count();
            $completed_tasks_count = \App\Task::where('is_statutory', 0)->where('assign_to', Auth::id())->whereNotNull('is_completed')->count();
            $sushil_pending_tasks_count = \App\Task::where('is_statutory', 0)->where('assign_to', 7)->whereNull('is_completed')->count();
            $andy_pending_tasks_count = \App\Task::where('is_statutory', 0)->where('assign_to', 56)->whereNull('is_completed')->count();
            @endphp

            <a href="/#1">
                <span class="badge badge-task-pending">{{ $pending_tasks_count }}</span>
            </a>

            <a href="/#3">
                <span class="badge badge-task-completed">{{ $completed_tasks_count }}</span>
            </a>

            <a href="{{ route('task.list') }}">
                <span class="badge badge-task-other">S-{{ $sushil_pending_tasks_count }}</span>
            </a>

            <a href="{{ route('task.list') }}">
                <span class="badge badge-task-other right">A-{{ $andy_pending_tasks_count }}</span>
            </a>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#quickTaskModal">+ TASK</button>
        </div>
        @endif
        @include('twilio.receive_call_popup')
        @include('partials.modals.quick-task')
        @include('partials.modals.quick-instruction')
        @include('partials.modals.quick-development-task')
        @include('partials.modals.quick-instruction-notes')
        @include('partials.modals.quick-user-event-notification')

        @include('partials.modals.quick-zoom-meeting-window')
        @include('partials.modals.quick-create-task-window')
        @include('partials.modals.quick-notes') {{-- Purpose : Import notes modal - DEVTASK-4289 --}}
        @php
            $liveChatUsers = \App\LiveChatUser::where('user_id',Auth::id())->first();
            $key = \App\LivechatincSetting::first();
        @endphp
        @if($liveChatUsers != '' && $liveChatUsers != null)
        <input type="hidden" id="live_chat_key" value="@if(isset($key)){{ $key->key}}@else @endif">
        @include('partials.chat')
        @endif
        @include('partials.modals.quick-chatbox-window')
        @endif
{{--        @if(Auth::check())--}}
{{--            <!---start section for the sidebar toggle -->--}}
{{--            <nav id="quick-sidebar">--}}
{{--                <ul class="list-unstyled components">--}}
{{--                    <li>--}}
{{--                        <a class="notification-button quick-icon" href="#"><span><i class="fa fa-bell fa-2x"></i></span></a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="instruction-button quick-icon" href="#"><span><i class="fa fa-question-circle fa-2x" aria-hidden="true"></i></span></a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="daily-planner-button quick-icon" target="__blank" href="{{ route('dailyplanner.index') }}">--}}
{{--                            <span><i class="fa fa-calendar-check-o fa-2x" aria-hidden="true"></i></span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    --}}
{{--                    <li>--}}
{{--                        <a id="message-chat-data-box" class="quick-icon">--}}
{{--                           <span class="p1 fa-stack has-badge" id="new_message" data-count="@if(isset($newMessageCount)) {{ $newMessageCount }} @else 0 @endif">--}}
{{--                                <i class="fa fa-comment fa-2x xfa-inverse" data-count="4b"></i>--}}
{{--                           </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="create-zoom-meeting quick-icon" data-toggle="modal" data-target="#quick-zoomModal">--}}
{{--                            <span><i class="fa fa-video-camera fa-2x" aria-hidden="true"></i></span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="create-easy-task quick-icon" data-toggle="modal" data-target="#quick-create-task">--}}
{{--                            <span><i class="fa fa-tasks fa-2x" aria-hidden="true"></i></span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))--}}
{{--                        <li>--}}
{{--                            <a title="Manual Payment" class="manual-payment-btn quick-icon">--}}
{{--                                <span><i class="fa fa-money fa-2x" aria-hidden="true"></i></span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--                    <li>--}}
{{--                        <a title="Manual Request" class="manual-request-btn quick-icon">--}}
{{--                            <span><i class="fa fa-credit-card-alt fa-2x" aria-hidden="true"></i></span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a title="Auto Refresh" class="auto-refresh-run-btn quick-icon">--}}
{{--                            <span><i class="fa fa-refresh fa-2x" aria-hidden="true"></i></span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </nav>--}}
{{--            <!-- end section for sidebar toggle -->--}}
{{--        @endif--}}
        @if (trim($__env->yieldContent('large_content')))
            <div class="col-md-12">
                @yield('large_content')
            </div>
        @elseif (trim($__env->yieldContent('core_content')))
            @yield('core_content')
        @else
            <main class="container container-grow" style="display: inline-block;">
                <!-- Showing fb like page div to all pages  -->
                {{-- @if(Auth::check())
                <div class="fb-page" data-href="https://www.facebook.com/devsofts/" data-small-header="true" data-adapt-container-width="false" data-hide-cover="true" data-show-facepile="false"><blockquote cite="https://www.facebook.com/devsofts/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/devsofts/">Development</a></blockquote></div>

                @endif --}}
                @yield('content')
                <!-- End of fb page like  -->
            </main>
        @endif


         <a id="back-to-top" href="javascript:;" class="btn btn-light btn-lg back-to-top" role="button"><i class="fa fa-chevron-up"></i></a>
    </div>

    @if(Auth::check())


    <div class="chat-button-wrapper">
{{--        <div class="chat-button-float">--}}
{{--            <button class="chat-button">--}}
{{--                <img src="/images/chat.png" class="img-responsive"/>--}}
{{--                <span id="new_message_count">@if(isset($newMessageCount)) {{ $newMessageCount }} @else 0 @endif</span>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--        <div class="notification-badge">--}}
{{--            <button class="chat-button">--}}
{{--                <a href="{{route('notifications')}}">--}}
{{--                <img src="/images/notification-icon.png" class="img-responsive"/>--}}
{{--                <span id="notification_unread">@if(isset($unread)) {{ $unread }} @else 0 @endif</span>--}}
{{--                </a>--}}
{{--            </button>--}}
{{--        </div>--}}
        <div class="col-md-12 page-chat-list-rt dis-none">
            <div class="help-list well well-lg">
                <div class="row">
                    <div class="col-md-3 chat" style="margin-top : 0px !important;">
                        <div class="card_chat mb-sm-3 mb-md-0 contacts_card">
                            <div class="card-header">
                                <div class="input-group">
                                    {{-- <input type="text" placeholder="Search..." name="" class="form-control search">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text search_btn"><i class="fa fa-search"></i></span>
                                        </div> --}}
                                </div>
                            </div>
                            <div class="card-body contacts_body">
                                @php
                                $chatIds = \App\CustomerLiveChat::with('customer')->orderBy('seen','asc')
                                ->orderBy('status','desc')
                                ->get();
                                $newMessageCount = \App\CustomerLiveChat::where('seen',0)->count();
                                @endphp
                                <ul class="contacts" id="customer-list-chat">
                                    @foreach ($chatIds as $chatId)
                                        @php
                                        $customer = $chatId->customer;
                                        $customerInital = substr($customer->name, 0, 1);
                                        @endphp
                                    <li onclick="getChats('{{ $customer->id }}')" id="user{{ $customer->id }}" style="cursor: pointer;">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <soan class="rounded-circle user_inital">{{ $customerInital }}</soan>
                                                {{-- <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img"> --}}
                                                <span class="online_icon @if($chatId->status == 0) offline @endif "></span>
                                            </div>
                                            <div class="user_info">
                                                <span>{{ $customer->name }}</span>
                                                <p>{{ $customer->name }} is @if($chatId->status == 0) offline @else online @endif </p>
                                            </div>
                                            @if($chatId->seen == 0)<span class="new_message_icon"></span>@endif
                                        </div>
                                    </li>

                                    @endforeach


                                </ul>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <div class="col-md-6 chat">
                        <div class="card_chat">
                            <div class="card-header msg_head">
                                <div class="d-flex bd-highlight align-items-center justify-content-between">
                                    <div class="img_cont">
                                        <soan class="rounded-circle user_inital" id="user_inital"></soan>
                                        {{-- <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img"> --}}

                                    </div>
                                    <div class="user_info" id="user_name">
                                        {{-- <span>Chat with Khalid</span>
                                            <p>1767 Messages</p> --}}
                                    </div>
                                    <div class="video_cam">
                                        <span><i class="fa fa-video"></i></span>
                                        <span><i class="fa fa-phone"></i></span>
                                    </div>
                                    @php
                                        $path = storage_path('/');
                                        $content = File::get($path."languages.json");
                                        $language = json_decode($content, true);
                                    @endphp
                                    <div class="selectedValue">
                                         <select id="autoTranslate" class="form-control auto-translate">
                                            <option value="">Translation Language</option>
                                            @foreach ($language as $key => $value)
                                                <option value="{{$value}}">{{$key}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <span id="action_menu_btn"><i class="fa fa-ellipsis-v"></i></span>
                                <div class="action_menu">
                                    {{-- <ul>
                                            <li><i class="fa fa-user-circle"></i> View profile</li>
                                            <li><i class="fa fa-users"></i> Add to close friends</li>
                                            <li><i class="fa fa-plus"></i> Add to group</li>
                                            <li><i class="fa fa-ban"></i> Block</li>
                                        </ul> --}}
                                </div>
                            </div>
                            <div class="card-body msg_card_body" id="message-recieve">

                            </div>
                            <div class="typing-indicator" id="typing-indicator"></div>
                            <div class="card-footer">
                                <div class="input-group">
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text attach_btn" onclick="sendMessage()"><i class="fa fa-paperclip"></i></span>
                                        <input type="file" id="imgupload" style="display:none" />
                                    </div> --}}
                                    <div class="card-footer">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text attach_btn" onclick="sendImage()"><i class="fa fa-paperclip"></i></span>
                                                <input type="file" id="imgupload" style="display:none" />
                                            </div>
                                            <input type="hidden" id="message-id" name="message-id" />
                                            <textarea name="" class="form-control type_msg" placeholder="Type your message..." id="message"></textarea>
                                            <div class="input-group-append">
                                                <span class="input-group-text send_btn" onclick="sendMessage()"><i class="fa fa-location-arrow"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="col-md-3 customer-info">
                        <div class="chat-righbox">
                            <div class="title">General Info</div>
                            <div id="chatCustomerInfo"></div>

                        </div>
                        <div class="chat-righbox">
                            <div class="title">Visited Pages</div>
                            <div id="chatVisitedPages">

                            </div>
                        </div>
                        <div class="chat-righbox">
                            <div class="title">Additional info</div>
                            <div class="line-spacing" id="chatAdditionalInfo">

                            </div>
                        </div>
                        <div class="chat-righbox">
                            <div class="title">Technology</div>
                            <div class="line-spacing" id="chatTechnology">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="create-manual-payment" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" id="create-manual-payment-content">

            </div>
        </div>
    </div>

    @endif

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
    <script type="text/javascript" src="{{asset('js/jquery.richtext.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript" src="{{url('js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{url('js/custom_global_script.js')}}"></script>

    <script>
        $(document).ready(function() {
            //$.cookie('auto_refresh', '0', { path: '/{{ Request::path() }}' });

            var autoRefresh = $.cookie('auto_refresh');
                if(typeof autoRefresh == "undefined"  || autoRefresh == 1) {
                   $(".auto-refresh-run-btn").attr("title","Stop Auto Refresh");
                   $(".auto-refresh-run-btn").find("i").removeClass("refresh-btn-stop").addClass("refresh-btn-start");
                }else{
                   $(".auto-refresh-run-btn").attr("title","Start Auto Refresh");
                   $(".auto-refresh-run-btn").find("i").removeClass("refresh-btn-start").addClass("refresh-btn-stop");
                }
            //auto-refresh-run-btn

            $(document).on("click",".auto-refresh-run-btn",function() {
                let autoRefresh = $.cookie('auto_refresh');
                if(autoRefresh == 0) {
                   alert("Auto refresh has been enable for this page");
                   $.cookie('auto_refresh', '1', { path: '/{{ Request::path() }}' });
                   $(".auto-refresh-run-btn").find("i").removeClass("refresh-btn-stop").addClass("refresh-btn-start");
                }else{
                    alert("Auto refresh has been disable for this page");
                   $.cookie('auto_refresh', '0', { path: '/{{ Request::path() }}' });
                   $(".auto-refresh-run-btn").find("i").removeClass("refresh-btn-start").addClass("refresh-btn-stop");
                }
            });

            $('#editor-note-content').richText();
            $('#editor-instruction-content').richText();

            $('#editor-notes-content').richText();//Purpose : Add Text content - DEVTASK-4289

            $('#notification-date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#notification-time').datetimepicker({
                format: 'HH:mm'
            });

            $('#repeat_end').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $(".selectx-vendor").select2({tags :true});
            $(".selectx-users").select2({tags :true});
        });
        window.token = "{{ csrf_token() }}";

        var url = window.location;
        window.collectedData = [{
                type: 'key',
                data: ''
            },
            {
                type: 'mouse',
                data: []
            }
        ];

        $(document).keypress(function(event) {
            var x = event.charCode || event.keyCode; // Get the Unicode value
            var y = String.fromCharCode(x);
            collectedData[0].data += y;
        });

        // started for help button
        $('.help-button').on('click', function() {
            $('.help-button-wrapper').toggleClass('expanded');
            $('.page-notes-list-rt').toggleClass('dis-none');
        });

        $('.instruction-button').on('click', function() {
            $("#quick-instruction-modal").modal("show");
            //$('.help-button-wrapper').toggleClass('expanded');
            //$('.instruction-notes-list-rt').toggleClass('dis-none');
        });

        //START - Purpose : Open Modal - DEVTASK-4289
        $('.create_notes_btn').on('click', function() {
            $("#quick_notes_modal").modal("show");
        });

        $('.btn_save_notes').on('click', function(e) {
            e.preventDefault();
            var data = $('#editor-notes-content').val();

            if($(data).text() == ''){
                toastr['error']('Note Is Required');
                return false;
            }


            var url  = window.location.href;
            $.ajax({
                type: "POST",
                url: "{{ route('notesCreate') }}",
                data: {
                    data: data,
                    url : url,
                    _token: "{{ csrf_token() }}",
                },
                dataType: "json",
                success: function(data) {
                    if(data.code == 200)
                    {
                        toastr['success'](data.message, 'success');
                        $("#quick_notes_modal").modal("hide");
                    }

                },
                error : function(xhr, status, error) {

                }
            });
        });
        //END - DEVTASK-4289

        $('.notification-button').on('click', function() {
            $("#quick-user-event-notification-modal").modal("show");
        });

        $('select[name="repeat"]').on('change', function () {
            $(this).val() == 'weekly' ? $('#repeat_on').removeClass('hide') : $('#repeat_on').addClass('hide');
        });

        $('select[name="ends_on"]').on('change', function () {
            $(this).val() == 'on' ? $('#repeat_end_date').removeClass('hide') : $('#repeat_end_date').addClass('hide');
        });

        $('select[name="repeat"]').on('change', function () {
            $(this).val().length > 0 ? $('#ends_on').removeClass('hide') : $('#ends_on').addClass('hide');
        });

        $(document).on("submit","#notification-submit-form",function(e){
            e.preventDefault();
            var $form = $(this).closest("form");
            $.ajax({
                type: "POST",
                url: $form.attr("action"),
                data: $form.serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.code == 200) {
                        $form[0].reset();
                        $("#quick-user-event-notification-modal").modal("hide");
                        toastr['success'](data.message, 'Message');
                    }else{
                        toastr['error'](data.message, 'Message');
                    }
                },
                error : function(xhr, status, error) {
                    var errors = xhr.responseJSON;
                    $.each(errors, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }
            });
        });

        //setup before functions
        var typingTimer;                //timer identifier
        var doneTypingInterval = 5000;  //time in ms, 5 second for example
        var $input = $('#editor-instruction-content');
        //on keyup, start the countdown
        $input.on('keyup', function () {
          clearTimeout(typingTimer);
          typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown
        $input.on('keydown', function () {
          clearTimeout(typingTimer);
        });

        //user is "finished typing," do something
        function doneTyping () {
          //do something
        }

        // started for chat button
        // open chatbox now into popup

        var chatBoxOpen = false;

        $("#message-chat-data-box").on("click",function(e) {
            e.preventDefault();
           $("#quick-chatbox-window-modal").modal("show");
           chatBoxOpen = true;
           openChatBox(true);
        });

        $('#quick-chatbox-window-modal').on('hidden.bs.modal', function () {
           chatBoxOpen = false;
           openChatBox(false);
        });

        $('.chat_btn').on('click', function (e) {
            e.preventDefault();
           $("#quick-chatbox-window-modal").modal("show");
           chatBoxOpen = true;
           openChatBox(true);
        });

        // $('.chat-button').on('click', function () {
        //     $('.chat-button-wrapper').toggleClass('expanded');
        //     $('.page-chat-list-rt').toggleClass('dis-none');
        //     if($('.chat-button-wrapper').hasClass('expanded')){
        //         chatBoxOpen = true;
        //         openChatBox(true);
        //     }else{
        //         chatBoxOpen = false;
        //         openChatBox(false);
        //     }
        // });

        var notesBtn = $(".save-user-notes");

        notesBtn.on("click", function(e) {
            e.preventDefault();
            var $form = $(this).closest("form");
            $.ajax({
                type: "POST",
                url: $form.attr("action"),
                data: {
                    _token: window.token,
                    note: $form.find("#note").val(),
                    category_id: $form.find("#category_id").val(),
                    url: "<?php echo request()->url() ?>"
                },
                dataType: "json",
                success: function(data) {
                    if (data.code > 0) {
                        $form.find("#note").val("");
                        var listOfN = "<tr>";
                        listOfN += "<td scope='row'>" + data.notes.id + "</td>";
                        listOfN += "<td>" + data.notes.note + "</td>";
                        listOfN += "<td>" + data.notes.category_name + "</td>";
                        listOfN += "<td>" + data.notes.name + "</td>";
                        listOfN += "<td>" + data.notes.created_at + "</td>";
                        listOfN += "</tr>";

                        $(".page-notes-list").prepend(listOfN);
                    }
                },
            });
        });

        @if(session()->has('encrpyt'))

        var inactivityTime = function () {
            var time;
            window.onload = resetTimer;
            // DOM Events
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;

        function remove_key() {
            $.ajax({
            url: "{{ route('encryption.forget.key') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                private: '1',
                "_token": "{{ csrf_token() }}",
            },
            })
            .done(function() {
                alert('Please Insert Private Key');
                location.reload();
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(remove_key, 1200000);
            // 1000 milliseconds = 1 second
        }
        };

        window.onload = function() {
            inactivityTime();
        }

        @endif

        var getNotesList = function() {
            //$.ajax({
            //            type: "GET",
            //          url: "/page-notes/list",
            //        data: {
            //          _token: window.token,
            //        url: "<?php echo request()->url() ?>"
            //  },
            //            dataType: "json",
            //          success: function (data) {
            //            if (data.code > 0) {
            //              var listOfN = "";
            //            $.each(data.notes, function (k, v) {
            //              listOfN += "<tr>";
            //            listOfN += "<td scope='row'>" + v.id + "</td>";
            //          listOfN += "<td>" + v.note + "</td>";
            //        listOfN += "<td>" + v.category_name + "</td>";
            //      listOfN += "<td>" + v.name + "</td>";
            //    listOfN += "<td>" + v.created_at + "</td>";
            //  listOfN += "</tr>";
            //                    });
            //
            //                  $(".page-notes-list").prepend(listOfN);
            //            }
            //      },
            //});
        }

        if ($(".help-button-wrapper").length > 0) {
            getNotesList();
        }


        // $(document).click(function() {
        //     if (collectedData[0].data.length > 10) {
        //         let data_ = collectedData[0].data;
        //         let type_ = collectedData[0].type;
        //
        //         $.ajax({
        //             url: "/track",
        //             type: 'post',
        //             csrf: token,
        //             data: {
        //                 url: url,
        //                 item: type_,
        //                 data: data_
        //             }
        //         });
        //     }
        // });
        @if(Auth::check())
        $(document).ready(function() {
            var url = window.location.href;
            var user_id = "{{ Auth::id() }}";
            user_name = "{{ Auth::user()->name }}";
            $.ajax({
                type: "POST",
                url: "/api/userLogs",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "url": url,
                    "user_id": user_id,
                    "user_name": user_name
                },
                dataType: "json",
                success: function(message) {}
            });
        });
        @endif
    </script>
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
         <?php
            if(!\Auth::guest()) {
            $path = Request::path();
            $hasPage = \App\AutoRefreshPage::where("page",$path)->where("user_id",\Auth()->user()->id)->first();
            if($hasPage) {
         ?>

            var idleTime = 0;
            function reloadPageFun() {
                idleTime = idleTime + 1000;
                var autoRefresh = $.cookie('auto_refresh');
                if (idleTime > <?php echo $hasPage->time * 1000; ?> && (typeof autoRefresh == "undefined" || autoRefresh == 1)) {
                    window.location.reload();
                }
            }

            $(document).ready(function () {
                //Increment the idle time counter every minute.
                setInterval(function(){ reloadPageFun() }, 3000);
                //Zero the idle timer on mouse movement.
                $(this).mousemove(function (e) {
                    idleTime = 0;
                });
                $(this).keypress(function (e) {
                    idleTime = 0;
                });
            });

        <?php } } ?>

        function filterFunction() {
            var input, filter, ul, li, a, i;
            //getting search values
            input = document.getElementById("search");
            //String to upper for search
            filter = input.value.toUpperCase();
            //Getting Values From DOM
            a = document.querySelectorAll("#navbarSupportedContent a");
            //Class to open bar
            $("#search_li").addClass('open');
            //Close when search becomes zero
            if (a.length == 0) {
                $("#search_li").removeClass('open');
            }
            //Limiting Search Count
            count = 1;
            //Empty Existing Values
            $("#search_container").empty();

            //Getting All Values
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                href = a[i].href;
                //If value doesnt have link
                if (href == "#" || href == '' || href.indexOf('#') > -1) {
                    continue;
                }
                //Removing old search Result From DOM
                if (a[i].getAttribute('class') != null && a[i].getAttribute('class') != '') {
                    if (a[i].getAttribute('class').indexOf('old_search') > -1) {
                        continue;
                    }
                }
                //break when count goes above 30
                if (count > 30) {
                    break;
                }
                //Pusing values to DOM Search Input
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    $("#search_container").append('<li class="nav-item dropdown dropdown-submenu"><a class="dropdown-item old_search" href=' + href + '>' + txtValue + '</a></li>');
                    count++
                } else {}
            }
        }

        $(document).on('change', '#autoTranslate', function (e) {
             e.preventDefault();
            var customerId = $("input[name='message-id'").val();
            var language = $(".auto-translate").val();
            let self = $(this);
            $.ajax({
                url: "/customer/language-translate/"+customerId,
                method:"PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                data:{id:customerId, language:language },
                cache: true,
                success: function(res) {
                    $('.selectedValue option[value="' + language + '"]').prop('selected', true);
                    alert(res.success);
                }
            })
        });

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

        $(document).on('click', '.save-meeting-zoom', function () {
            var user_id = $('#quick_user_id').val();
            var meeting_topic = $('#quick_meeting_topic').val();
            var csrf_token = $('#quick_csrfToken').val();
            var meeting_url = $('#quick_meetingUrl').val();
            $.ajax({
                url: meeting_url,
                type: 'POST',
                success: function (response) {
                    var status = response.success;
                    if(false == status){
                        toastr['error'](response.data.msg);
                    }else{
                        $('#quick-zoomModal').modal('toggle');
                        window.open(response.data.meeting_link);
                        var html = '';
                        html += response.data.msg+'<br>';
                        html += 'Meeting URL: <a href="'+response.data.meeting_link+'" target="_blank">'+response.data.meeting_link+'</a><br><br>';
                        html += '<a class="btn btn-primary" target="_blank" href="'+response.data.start_meeting+'">Start Meeting</a>';
                        $('#qickZoomMeetingModal').modal('toggle');
                        $('.meeting_link').html(html);
                        toastr['success'](response.data.msg);
                    }
                },
                data: {
                    user_id: user_id,
                    meeting_topic: meeting_topic,
                    _token: csrf_token,
                    user_type : "vendor"
                },
                beforeSend: function () {
                    $(this).text('Loading...');
                }
            }).fail(function (response) {
                toastr['error'](response.responseJSON.message);

            });
        });

        $(document).on("click",".save-task-window",function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
                url: form.attr("action"),
                type: 'POST',
                data: form.serialize(),
                beforeSend: function () {
                    $(this).text('Loading...');
                },
                success: function (response) {
                    if(response.code == 200){
                        form[0].reset();
                        toastr['success'](response.message);
                        $("#quick-create-task").modal("hide");
                        $("#auto-reply-popup").modal("hide");
                        $("#auto-reply-popup-form").trigger('reset');
                        location.reload();
                    }else{
                        toastr['error'](response.message);
                    }
                }
            }).fail(function (response) {
                toastr['error'](response.responseJSON.message);
            });
        });
        $('select.select2-discussion').select2({tags: true});
        $(document).on("change",".type-on-change",function(e) {
            e.preventDefault();
            var task_type = $(this).val();
            console.log(task_type);
            if(task_type == 3) {
                // $('.normal-subject').hide();
                    // $('.discussion-task-subject').show();
                $.ajax({
                url: '/task/get-discussion-subjects',
                type: 'GET',
                success: function (response) {
                    $('select.select2-discussion').select2({tags: true});
                    var option = '<option value="" >Select</option>';
                    $.each(response.discussion_subjects, function(i, item) {
                    console.log(item);

                            option = option + '<option value="'+i+'">'+item+'</option>';
                        });
                        $('.add-discussion-subjects').html(option);
                    }
                }).fail(function (response) {
                    toastr['error'](response.responseJSON.message);
                });
            }
            else {
                // $('select.select2-discussion').select2({tags: true});
                $("select.select2-discussion").empty().trigger('change');
            }


        });

        $(document).on('change', '#keyword_category', function () {
            console.log("inside");
            if ($(this).val() != "") {
                var category_id = $(this).val();
                var store_website_id = $('#live_selected_customer_store').val();
                $.ajax({
                    url: "{{ url('get-store-wise-replies') }}"+'/'+category_id+'/'+store_website_id,
                    type: 'GET',
                    dataType: 'json'
                }).done(function(data){
                    console.log(data);
                    if(data.status == 1){
                        $('#live_quick_replies').empty().append('<option value="">Quick Reply</option>');
                        var replies = data.data;
                        replies.forEach(function (reply) {
                            $('#live_quick_replies').append($('<option>', {
                                value: reply.reply,
                                text: reply.reply,
                                'data-id': reply.id
                            }));
                        });
                    }
                });

            }
        });

        $('.quick_comment_add_live').on("click", function () {
            var textBox = $(".quick_comment_live").val();
            var quickCategory = $('#keyword_category').val();

            if (textBox == "") {
                alert("Please Enter New Quick Comment!!");
                return false;
            }

            if (quickCategory == "") {
                alert("Please Select Category!!");
                return false;
            }
            console.log("yes");

            $.ajax({
                type: 'POST',
                url: "{{ route('save-store-wise-reply') }}",
                dataType: 'json',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'category_id' : quickCategory,
                    'reply' : textBox,
                    'store_website_id' : $('#live_selected_customer_store').val()
                }
            }).done(function (data) {
                console.log(data);
                $(".live_quick_comment").val('');
                $('#live_quick_replies').append($('<option>', {
                    value: data.data,
                    text: data.data
                }));
            })
        });

        $('#live_quick_replies').on("change", function(){
            $('.type_msg').text($(this).val());
        });


        $(document).on('click','.show_sku_long',function(){
            $(this).hide();
            var id=$(this).attr('data-id');
            $('#sku_small_string_'+id).hide();
            $('#sku_long_string_'+id).css({'display':'block'});
        });
        $(document).on('click','.show_prod_long',function(){
            $(this).hide();
            var id=$(this).attr('data-id');
            $('#prod_small_string_'+id).hide();
            $('#prod_long_string_'+id).css({'display':'block'});
        });

        $(document).on('click', '.manual-payment-btn', function(e) {
          e.preventDefault();
          var thiss = $(this);
          var type = 'GET';
            $.ajax({
              url: '/voucher/manual-payment',
              type: type,
              beforeSend: function() {
                $("#loading-image").show();
              }
            }).done( function(response) {
              $("#loading-image").hide();
              $('#create-manual-payment').modal('show');
              $('#create-manual-payment-content').html(response);

              $('#date_of_payment').datetimepicker({
                format: 'YYYY-MM-DD'
              });
              $('.select-multiple').select2({width: '100%'});

              $(".currency-select2").select2({width: '100%',tags:true});
              $(".payment-method-select2").select2({width: '100%',tags:true});

            }).fail(function(errObj) {
              $("#loading-image").hide();
            });
        });

        $(document).on('click', '.manual-request-btn', function(e) {
          e.preventDefault();
          var thiss = $(this);
          var type = 'GET';
            $.ajax({
              url: '/voucher/payment/request',
              type: type,
              beforeSend: function() {
                $("#loading-image").show();
              }
            }).done( function(response) {
              $("#loading-image").hide();
              $('#create-manual-payment').modal('show');
              $('#create-manual-payment-content').html(response);

              $('#date_of_payment').datetimepicker({
                format: 'YYYY-MM-DD'
              });
              $('.select-multiple').select2({width: '100%'});

            }).fail(function(errObj) {
              $("#loading-image").hide();
            });
        });


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
