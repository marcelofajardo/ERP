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
            <h2 class="page-heading">Live Laravel Logs
                <a style="float: right;" href="{{ action('LaravelLogController@liveLogDownloads') }}" class="btn btn-success btn-xs">Download</a>
                <a style="float: right; padding-left: 10px;" href="{{ action('LaravelLogController@liveMagentoDownloads') }}" class="btn btn-success btn-xs">Magento Log</a>
            </h2>
            @if ($message = Session::get('message'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>
    </div>

    @include('partials.flash_messages')
    <div class="row">
        <div class="col-md-6">
            <form method="get" action="{{ url('logging/live-laravel-logs') }}" class="form-horizontal" role="form">
                <div class="col-md-3">
                    <select name="type" class="form-control select-multiple" id="error-select">
                        @foreach($errSelection as $key => $selection)
                            <option value="{{ $selection }}" {{ app('request')->input('type') == $selection ? ' selected' : '' }}>{{ $selection }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="channel" class="form-control select-multiple" id="error-select">
                        @foreach($filter_channel as $channel)
                            <option value="{{ $channel }}" {{ app('request')->input('channel') == $channel ? ' selected' : '' }}>{{ $channel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ app('request')->input('search') != '' ? app('request')->input('search') : '' }}" class="form-control" id="error-search" placeholder="Search...">
                </div>
                <div class="col-md-2">
                    <button type='submit' class="btn btn-default">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="conatainer">
        <div class="row mt-3">
            <form action="{{ action('LaravelLogController@LogKeyword') }}" class="form-horizontal logKeyword" role="form" method="post">
                <div class="col-md-3">
                    <input type="text" name="title" value="" class="form-control" placeholder="Keyword" required>
                </div>
                <div class="col-md-3">
                    <button type='submit' class="btn btn-default">Add Keyword</button>
                </div>
                <div class="col-md-3">
                    <button type='button' class="btn btn-default show-keywords">Show Keyword</button>
                </div>
                <div class="col-md-3">
                    <button type='button' class="btn btn-default load-messages" data-object="user" data-id="6">Show Messages</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th width="10%">Filename</th>
                <th width="10%">Channel</th>
                <th width="50%">Log</th>
                <th width="13%">Action</th>
            </tr>
            </thead>
            <tbody id="content_data">
                @include('logging.partials.livelaraveldata')
            </tbody>
            {!! $logs->render() !!}
        </table>
    </div>
 
 
    <div id="assign_task_model" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ url('logging/assign') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Assign Task</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="issue" id="issue">
                    <div class="form-group">
                        <strong>User:</strong>
                        <select class="form-control select-multiple" name="assign_to" id="user-select">
                            <option value="">Select User</option>
                            @foreach($users as $key => $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default close-setting" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-secondary">Assign</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    
    <div id="view_error" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ url('logging/assign') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Assign Task</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Index</th>
                            <th>Time</th>
                        </tr>
                        <tbody class="content">
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default close-setting" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
      </div>
    </div>

    <div id="show_keywords" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Keywords</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td>Index</td>
                                <td>Keyword</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logKeywords as $logKeyword)
                                <tr>
                                    <td>{{ $logKeyword->id }}</td>
                                    <td>{{ $logKeyword->text }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default close-setting" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="load_messages" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Keywords</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td>Index</td>
                                <td>Message</td>
                                <td>Sent On</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count=0; @endphp
                            @foreach($ChatMessages as $ChatMessage)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $ChatMessage->message }}</td>
                                    <td>{{ $ChatMessage->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default close-setting" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript">

    function encode(e){return e.replace(/[^]/g,function(e){return"&#"+e.charCodeAt(0)+";"})}



    //Ajax Request For Search
    $(document).ready(function () {
          
        $('.select-multiple').select2({width: '100%'}); 
        $(".assign_task").on('click', function () {
            var err = $(this).closest("tr").find("span.td-full-container").text();
            $("#issue").val(err.replace(/[^]/g,function(err){return "&#"+err.charCodeAt(0)+";"}));
        });
        $('.close-setting').on('click', function() {
            $("#issue").val('');
        });
        
        
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
        $(document).on('click','.show-keywords',function(event){
            event.preventDefault();
            $('#show_keywords').modal('show');
        });
        $(document).on('click','.load-messages',function(event){
            event.preventDefault();
            $('#load_messages').modal('show');
        });

        $(document).on('submit','.logKeyword',function(event){
            event.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                dataType: "json",
                data: {
                    title : $(this).find('input[name="title"]').val(),
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                toastr['success'](data.message, 'Keyword Added Successfully.');
                $("#loading-image").hide();
                window.location.reload();
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                toastr['error'](data.message, 'Error ocuured!');
                $("#loading-image").hide();
            });
        });
        $(document).on('click','.view_error',function(event){
            event.preventDefault();
            console.log($(this).parent('td').siblings('td.expand-row.table-hover-cell').find('.td-full-container').text());
            $.ajax({
                url: '{{ action("LaravelLogController@liveLogsSingle") }}',
                dataType: "json",
                data: {
                    msg : $(this).parent('td').siblings('td.expand-row.table-hover-cell').find('.td-full-container').text(),
                },
                beforeSend: function () {
                    //$("#loading-image").show();
                },
            }).done(function (data) {
                var $html = '';
                $.each(data, function(i, item) {
                    $html += '<tr>';
                    $html += '<td>'+parseInt(i+1)+'</td>';
                    $html += '<td>'+item+'</td>';
                    $html += '</tr>';
                });
                $('#view_error table tbody.content').html($html);
                $('#view_error').modal('show');
                console.log($html);
                //window.location.reload();
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                //toastr['error'](data.message, 'Error ocuured!');
                //$("#loading-image").hide();
            });
        });
    </script>
@endsection