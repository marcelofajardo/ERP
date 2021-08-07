@extends('layouts.app')

@section('title', 'Larave Log List')

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
            <h2 class="page-heading">Live Laravel Logs</h2>
             <div class="pull-right">
                <a href="/logging/list-laravel-logs" type="button" class="btn btn-secondary">Laravel Logs</a>
            </div>

        </div>
    </div>

    @include('partials.flash_messages')

    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th width="10%">Filename</th>
                <th width="30%">Log</th>
            </tr>
            </thead>

            <tbody id="content_data">
                @foreach ($logs as $log)
                    <tr>
                         <td>{{ $filename }}</td>
                        <td class="expand-row table-hover-cell"><span class="td-mini-container">
                            {{ strlen( $log ) > 110 ? substr( $log , 0, 110).'...' :  $log }}
                            </span>
                            <span class="td-full-container hidden">
                                <pre><code><?php echo $log; ?></code></pre>
                            </span>
                        </td>
                    </tr>
                @endforeach
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
        src = "{{ route('logging.laravel.log') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            filename = $('#filename').val();
            log = $('#log').val();
            
           $.ajax({
                url: src,
                dataType: "json",
                data: {
                    filename : filename,
                    log : log,
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