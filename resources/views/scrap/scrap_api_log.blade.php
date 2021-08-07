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
    <div id="myDiv" style="display:none;">
        <img id="loading-image" src="/images/pre-loader.gif" style="z-index:10;" />
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Scrap Api Logs</h2>

        </div>
    </div>

    <div class="mt-3 col-md-12">
        <form action="{{ route('log-scraper.api') }}">
            <div class="row row-no-gutters">
                <div class="col-sm-3"><input type="text" class="form-control" placeholder="Search By Name" name="scraper_name"></div>
                <div><h5>From</h5></div>
                <div class="form-group col-md-2 pd-3">
                    <div class='input-group date' id='order-start-datetime'>
                        <input type='text' class="form-control" name="start_date" id="start_search_date" placeholder="Start Date"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div><h5>To</h5></div>
                <div class="form-group col-md-2 pd-3">
                    <div class='input-group date' id='order-end-datetime'>
                        <input type='text' class="form-control" name="end_date" id="end_search_date" placeholder="End Date"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-image" type="submit">
                        <img src="/images/filter.png" style="cursor: nwse-resize;">
                    </button>
                </div>
            </div>
        </form>
        <br>
        <table class="table table-bordered table-striped" id="log-table" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="5%">Scraper</th>
                    <th width="5%">Server id</th>
                    <th width="10%">Date & Time</th>
                    <th width="80%">Logs</th>
                </tr>
                
            </thead>
            <tbody class="apiLogSearch">
                @foreach ($api_logs as  $key => $log)
                @php
                    $scraper = \App\Scraper::find($log->scraper_id);
                    $scraper_name = '';
                    if ($scraper) {
                        $scraper_name = $scraper->scraper_name;
                    }
                @endphp
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $scraper_name }}</td>
                        <td>{{ $log->server_id }}</td>
                        <td>{{ $log->created_at }}</td>
                        @if (strlen($log->log_messages) > 250)
                            <td style="word-break: break-word;" data-log_message="{{ $log->log_messages }}" class="log-message-popup">{{ substr($log->log_messages,0,250) }}...</td>    
                        @else
                            <td style="word-break: break-word;">{{ $log->log_messages }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <tr>{{ $api_logs->appends(Request::except('page'))->links() }}</tr>
    </div>

    <!--Log Messages Modal -->
    <div id="logMessageModel" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Scrap Api Log</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p style="word-break: break-word;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
        </div>
    </div>
 

@endsection
@section("scripts")
    <script>

    $(document).ready(function() {
        $('#order-start-datetime').datetimepicker({
            format: 'DD/MM/YYYY',
        });
        $('#order-end-datetime').datetimepicker({
            format: 'DD/MM/YYYY',
        });
        $('#newdeldate').datetimepicker({
            minDate:new Date(),
            format: 'DD/MM/YYYY',
        });
    });
        $(document).on('click','.log-message-popup',function(){
            $('#logMessageModel').modal('show');
            $('#logMessageModel p').text($(this).data('log_message'));
        });

    </script>
@endsection