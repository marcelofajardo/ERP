@extends('layouts.app')

@section('title', 'Visitor Log List')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
     <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Visitor Logs ( {{ count($logs) }})</h2>
             <div class="pull-right">
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
            </div>

        </div>
    </div>

    @include('partials.flash_messages')

    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th width="10%">Ip</th>
                <th width="10%">Browser</th>
                <th width="10%">Location</th>
                <th width="10%">Current Page</th>
                <th width="10%">Visits</th>
                <th width="10%">Pages</th>
                <th width="10%">Chats</th>
                <th width="10%">Customer Name</th>
                <th width="10%">Last Visit</th>
            </tr>
            <tr>
                
                <th width="10%"><input type="text" class="search form-control" id="ip"></th>
                <th width="10%"><input type="text" class="search form-control" id="browser"></th>
                <th><input type="text" class="search form-control" id="location"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

                <!-- <th> <div class='input-group' id='log-created-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="log_created" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                </th>
                <th> <div class='input-group' id='created-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="created_date" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                </th>
                <th> <div class='input-group' id='updated-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="updated_date" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </th> -->
            </tr>
            </thead>

            <tbody id="content_data">
             @include('logging.partials.visitordata')
            </tbody>

            {!! $logs->render() !!}

        </table>
    </div>
 

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript">



    //Ajax Request For Search
    $(document).ready(function () {
          
        
        //Expand Row
         $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        //Filter by date
        count = 0;
        $('#created-date').datetimepicker(
            { format: 'YYYY/MM/DD' }).on('dp.change', 
            function (e) 
            {
            if(count > 0){    
             var formatedValue = e.date.format(e.date._f);
                created = $('#created_date').val();
                updated = $('#updated_date').val();
                filename = $('#filename').val();
                log = $('#log').val();

                src = "{{ route('logging.laravel.log') }}";
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        created : created,
                        updated : updated,
                        filename : filename,
                        log : log,

                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },

                }).done(function (data) {
                    $("#loading-image").hide();
                $("#content_data").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                    

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });  

            } 
            count++;       
            });

            
            count = 0;
        $('#updated-date').datetimepicker(
            { format: 'YYYY/MM/DD' }).on('dp.change', 
            function (e) 
            {
            if(count > 0){    
             var formatedValue = e.date.format(e.date._f);
                created = $('#created_date').val();
                updated = $('#updated_date').val();
                filename = $('#filename').val();
                log = $('#log').val();

                 src = "{{ route('logging.laravel.log') }}";
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        created : created,
                        updated : updated,
                        filename : filename,
                        log : log,

                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },

                }).done(function (data) {
                    $("#loading-image").hide();
                $("#content_data").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                    

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });  

            } 
            count++;       
            });

        logcount = 0;
        $('#log-created-date').datetimepicker(
            { format: 'YYYY/MM/DD' }).on('dp.change', 
            function (e) 
            {
            if(logcount > 0){    
             var formatedValue = e.date.format(e.date._f);
                created = $('#created_date').val();
                updated = $('#updated_date').val();
                log_created = $('#log_created').val();

                filename = $('#filename').val();
                log = $('#log').val();

                 src = "{{ route('logging.laravel.log') }}";
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        created : created,
                        updated : updated,
                        filename : filename,
                        log : log,
                        log_created : log_created,

                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },

                }).done(function (data) {
                    $("#loading-image").hide();
                $("#content_data").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                    

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });  

            } 
            logcount++;       
            });


        //Search    
        src = "{{ route('logging.visitor.log') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            ip = $('#ip').val();
            browser = $('#browser').val();
            location_get = $('#location').val();
            
           $.ajax({
                url: src,
                dataType: "json",
                data: {
                    ip : ip,
                    browser : browser,
                    'location' : location_get,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#log-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
         });
         src = "{{ route('logging.laravel.log') }}";
         function refreshPage() {
             blank = ''
             $.ajax({
                url: src,
                dataType: "json",
                data: {
                    blank : blank
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#log-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
         }

 

         function sortByDateCreated() {
            orderCreated = $('#header-created').val();
            filename = $('#filename').val();
            log = $('#log').val();

            src = "/scrap/scraped-urls";
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                   filename : filename,
                    log : log,
                    orderCreated : orderCreated,
                },
                beforeSend: function () {
                    if(orderCreated == 0){
                        $('#header-created').val('1');
                    }else{
                        $('#header-created').val('0');
                    }
                    $("#loading-image").show();
                },

            }).done(function (data) {
                $("#loading-image").hide();
            $("#content_data").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }
                

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });  

         }

         
         function sortByDateUpdated() {
            orderUpdated = $('#header-updated').val();
            filename = $('#filename').val();
            log = $('#log').val();

            src = "/scrap/scraped-urls";
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    filename : filename,
                    log : log,
                    orderUpdated : orderUpdated,
                },
                beforeSend: function () {
                    if(orderUpdated == 0){
                        $('#header-updated').val('1');
                    }else{
                        $('#header-updated').val('0');
                    }
                    $("#loading-image").show();
                },

            }).done(function (data) {
                $("#loading-image").hide();
            $("#content_data").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }
                

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });  

         }
    </script>
@endsection