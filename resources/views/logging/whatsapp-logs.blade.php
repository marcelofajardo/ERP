@extends('layouts.app')

@section('title', 'SKU log')

@section("styles")
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 48;
        }

        input {
            width: 100px;
        }
        .log-text-style{
            word-wrap: break-word;
            max-width: 600px;
        }
        .infinite-scroll-products-loader svg {
            -webkit-animation: spin 3s linear infinite;
            animation: spin 3s linear infinite;
            /*transform: rotate(180deg);*/
        }
        .infinite-scroll-products-loader {
            position: relative;
            width: 100%;
            text-align: center;
            padding: 40px 0;
        }
        .fa-refresh{
            cursor: pointer;
            color:#000;
        }

    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading"> Whatsapp Logs (<span id="count">{{ count($array) }}</span>)</h2>

            <div class="pull-right" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="sendMulti()" style="display: none;" id="nulti">
                    Send Selected
                </button>
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png"/>
                </button>
            </div>

        </div>
        <div class="col-10" style="padding-left:0px;">
            <div >
                <form class="form-inline" action="" method="GET">
                    <div class="form-group col-md-2 pd-3">
                        <div class='input-group date' id='filter-date'>
                            <input type='text' class="form-control search_date" name="date" value="{{ isset($_REQUEST['date']) ? $_REQUEST['date'] : '' }}" placeholder="Date" />

                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group col-md-2 pd-3">
                        <select name="message_sent" id="message_sent" class="form-control">
                            <option value="">Message Sent Status</option>
                            @if(isset($_REQUEST['message_sent']) && $_REQUEST['message_sent'] == 'Yes')
                                <option selected value="Yes">Yes</option>
                            @else
                                <option value="Yes">Yes</option>
                            @endif

                            @if(isset($_REQUEST['message_sent']) && $_REQUEST['message_sent'] == 'No')
                                <option selected value="No">No</option>
                            @else
                                <option value="No">No</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-md-1 pd-3">
                        <button type="submit" class="btn btn-image ml-3"><img src="{{asset('images/filter.png')}}" /></button>

                        <a href="{{ route('whatsapp.log') }}" class="fa fa-refresh" aria-hidden="true"></a>
                    </div>
                </form>
               
            </div>
        </div>

    </div>

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Pending Issues</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="pull-right">
                                <form action="{{ route('broadcasts.index') }}" method="GET">
                                    <div class="form-group">
                                        <div class="row">

                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>User</th>
                                    <!-- <th>Count</th> -->

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($array as $row)
                                    <tr>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th style="width: 1% !important;">Sr.No</th>
                <th style="width: 1% !important;">Date</th>
                <th style="width: 1% !important;">Sent ?</th>
                <th style="width: 3% !important;">Sender Number</th>
                <th style="width: 3% !important;">Receiver Number</th>
                <th style="width: 40% !important;">Text</th>
                <th style="width: 3% !important;">Action</th>
            </tr>
            
            </thead>
            <tbody id="content_data" class="infinite-scroll-pending-inner">
                @php
                    $sr_no = 1;
                @endphp
            @foreach($array as $row)
            @php
                $row_array = explode(",",$row['error_message1']);
                foreach ($row_array as $key => $value) {
                    if(strpos($value,'message"')){
                        unset($row_array[$key]);
                    }
                }
                $message = implode(',',$row_array);
                // $message = strpos($row['error_message1'],'"message');
                // $message_str = strtok(substr($row['error_message1'],$message), ',');
             

                if(isset($row['file']) && $row['file'] == 'chatapi')
                {
                    $message1 = strpos($row['error_message2'],'whatsapp_number');
                    $number = strpos($row['error_message2'],'"number":');
                    $receiver_number = (is_numeric(substr($row['error_message2'],$number+10,12)) ? substr($row['error_message2'],$number+10,12) : '');
                    $sender_number = (is_numeric(substr($row['error_message2'],$message1+18,12)) ? substr($row['error_message2'],$message1+18,12) : '');
                    $null = substr($row['error_message2'],$message1+17,4);
                }else{
                    $message1 = strpos($row['error_message1'],'whatsapp_number');
                    $number = strpos($row['error_message1'],'"number":');
                    $receiver_number = (is_numeric(substr($row['error_message1'],$number+10,12)) ? substr($row['error_message1'],$number+10,12) : '');
                    $sender_number = (is_numeric(substr($row['error_message1'],$message1+18,12)) ? substr($row['error_message1'],$message1+18,12) : '');
                    $null = substr($row['error_message1'],$message1+17,4);
                }

                $sent_message = strpos($row['error_message1'],'"sent":true');

                if($sent_message)
                    $sent_message_status = 1;
                else
                    $sent_message_status = 0;

            @endphp
                <tr>
                    <td>{{ $sr_no++ }}</td>
                    <td>{{ $row['date'] }}</td>

                    @if($sent_message_status == 1)
                        <td>Yes</td>     
                    @else
                        <td>No</td>
                    @endif

                    @if ($message1 == '' || $null == "null")
                        <td></td>
                    @else
                        <td>{{ $sender_number }}</td>
                    @endif
                    @if ($number == '' )
                        <td></td>
                    @else
                        <td>{{ $receiver_number }}</td>
                    @endif
                    <td class="errorLog">
                        <div class="log-text-style">
                            @if ($isAdmin)
                            Message1 : {{$row['error_message1']}} <br>
                        @else
                            Message1 : {{ $message }} <br>
                            {{-- @if ($message)
                                Message1 : {{str_replace($message_str,"",$row['error_message1'])}} <br>    
                            @else
                                Message1 : {{$row['error_message1']}} <br>
                            @endif --}}
                        @endif
                        @php
                            $str_msg = string_convert($row['error_message2'])
                        @endphp
                                <br/>
                        Message2 : 
                            @foreach($str_msg as $key => $val)
                                {{$val}}<br/>
                            @endforeach
                    
                        </div>
                    </td>
                    <td>

                        @if((isset($row['error_message1']) && getStr($row['error_message1'])) || (isset($row['error_message2']) && getStr($row['error_message2'])))
                            @if ($isAdmin)
                                <button class="btn btn-success sentMessage text-center" >
                                    Resend
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    <img class="infinite-scroll-products-loader center-block" src="/images/loading.gif" alt="Loading..." style="cursor: nwse-resize; width: 20px; margin-top: -50px; display: none;">

    </div>
    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('partials.modals.task-module')
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });


        $(document).on('click', '.sentMessage', function () {

            var msg = $(this).parents('tr').find('.errorLog').text();


            var myStr = msg;
            var matches = myStr.match(/\[(.*?)\]/);
            var submatch = '';
            if (matches) {
                submatch = matches[1];
            }

            var chat_id = null;
            // submatch = '{"number":"$number","whatsapp_number":"$sendNumber","message":"$text","validation":"$validation","chat_message_id":"1786391"}'
            if (submatch !== '') {
                var json = JSON.parse(submatch)
                if (typeof json.chat_message_id !== 'undefined') {
                    chat_id = json.chat_message_id;
                }
            }


            // console.log(json.chat_message_id);

            if (chat_id !== null) {

                $.ajax({
                    url: 'whatsapp/' + chat_id + '/resendMessage',
                    dataType: "json",
                    type: 'post',
                }).done(function (data) {

                    console.log(data);

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                });

            }


        })


        $(document).ready(function () {
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });


    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
    });


    $(".sentMessage").click(function() {
        console.log($(this).attr('data-details'));
    });

        var page = 1;
        function getScrollTop() {
            return (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
        }
        window.onscroll = function() {
            if (getScrollTop() < getDocumentHeight() - window.innerHeight) return;
            loadMore(++page);
        };

        function getDocumentHeight() {
            const body = document.body;
            const html = document.documentElement;

            return Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );
        };


        function loadMore(page) {

            var date = $('.search_date').val();
            var message_sent =  $('#message_sent').attr('selected', true).val();
           

            if(date != '')
                var url = "/whatsapp-log?page="+page+"&date="+date;
            else if(message_sent != '')
                var url = "/whatsapp-log?page="+page+"&date="+date+"&message_sent="+message_sent;
            else
                var url = "/whatsapp-log?page="+page;

            page = page + 1;
            $.ajax({
                url: url,
                type: 'GET',
                data: $('.form-search-data').serialize(),
                beforeSend:function(){
                        $('.infinite-scroll-products-loader').show();
                },
                success: function (data) {
                    if (data == '') {
                        $('.infinite-scroll-products-loader').hide();
                    }
                    $('.infinite-scroll-products-loader').hide();
                    $('.infinite-scroll-pending-inner').append(data);
                },
                error: function () {
                    $('.infinite-scroll-products-loader').hide();
                }
            });
        }




    </script>
@endsection