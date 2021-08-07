@extends('layouts.app')
@section('large_content')

<?php 
$chatIds = \App\CustomerLiveChat::latest()->orderBy('seen','asc')->orderBy('status','desc')->get();
$newMessageCount = \App\CustomerLiveChat::where('seen',0)->count();
?>
    <style type="text/css">
        .chat-righbox a{
            color: #555 !important;
            font-size: 18px;
        }
        .type_msg.message_textarea {
            width: 90%;
            height: 60px;
        }
        .cls_remove_rightpadding{
            padding-right: 0px !important;
        }
        .cls_remove_leftpadding{
            padding-left: 0px !important;
        }
        .cls_remove_padding{
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
        .cls_quick_commentadd_box{
            padding-left: 5px !important;   
            margin-top: 3px;
        }
        .cls_quick_commentadd_box button{
            font-size: 12px;
            padding: 5px 9px;
            margin-left: -8px;
            background: none;
        }
        .send_btn {
            margin-left: -5px; 
        }
        .cls_message_textarea{
            height: 35px !important;
            width: 100% !important;
        }
        .cls_quick_reply_box{
            margin-top: 5px;
        }
        .cls_addition_info {
            padding: 0px 0px;
            margin-top: -8px;
        }
        .table-responsive{
            margin-left: 10px;
            margin-right: 10px;
        }
        .chat-righbox{
            border: none;
            background: transparent;
            padding: 0;
        }
        .typing-indicator{
            height: auto;
            padding: 0;
        }
        textarea{
            border: 1px solid #ddd !important;
        }
        .send_btn{
            background-color: transparent !important;

        }
        .send_btn i{
            color: #808080;
        }
    </style>
        <div class="row">
            <div class="col-lg-12 margin-tb p-0">
                <h2 class="page-heading">Live Chat</h2>
                <div class="pull-right">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="keywordassign_table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Sr. No.</th>
                            <th style="width: 5%;">Site Name</th>
                            <th style="width: 5%;">Visitor Name</th>
                            <th style="width: 5%;">Email</th>
                            <th style="width: 10%;">Phone Number</th>
                            <th style="width: 10%;">Translation Language</th>
                            <th style="width: 50%;">Communication</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $srno=1;
                        ?>
                         @if(isset($chatIds) && !empty($chatIds))
                            @foreach ($chatIds as $chatId)
                                @php
                                $customer = \App\Customer::where('id',$chatId->customer_id)->first();
                                $customerInital = substr($customer->name, 0, 1);
                                @endphp
                                   <tr>
                                    <td><?php echo $srno;?></td>
                                    <td><?php echo $chatId->website;?></td>
                                    <td><?php echo $customer->name;?></td>
                                    <td class="expand-row">
                                        <span class="td-mini-container">
                                          {{ strlen($customer->email) > 15 ? substr($customer->email, 0, 15) : $customer->email }}
                                        </span>
                                        <span class="td-full-container hidden">
                                          {{ $customer->email }}
                                        </span>
                                    </td>
                                    <td><?php echo $customer->phone;?></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="typing-indicator" id="typing-indicator"></div>
                                        <div class="row">
                                            <div class="col-md-11 cls_remove_rightpadding">
                                                <textarea name="" class="form-control type_msg message_textarea cls_message_textarea" placeholder="Type your message..." id="message"></textarea>
                                                <input type="hidden" id="message-id" name="message-id" />
                                            </div>
                                            <div class="col-md-1 cls_remove_padding">
                                                <div class="input-group-append">
                                                    <a href="/attachImages/live-chat/{{ @$customer->id }}" class="btn btn-image px-1">
                                                        <img src="{{asset('images/attach.png')}}"/>
                                                    </a>
                                                    <a class="btn btn-image px-1" href="javascript:;">
                                                        <span data-id="{{ @$customer->id }}" class="send_btn">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>                                          
                                        </div>
                                        <div class="row cls_quick_reply_box">
                                            <div class="col-md-4 cls_remove_rightpadding">
                                                <select class="form-control" id="quick_replies">
                                                    <option value="">Quick Reply</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 cls_remove_rightpadding pl-3">
                                                @php
                                                    $all_categories = \App\ReplyCategory::all();
                                                @endphp
                                                <select class="form-control auto-translate" id="categories">
                                                    <option value="">Select Category</option>
                                                    @if(isset($all_categories))
                                                        @foreach ($all_categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4 pl-3">
                                                <div class="row">
                                                    <div class="col-md-9 cls_remove_rightpadding">
                                                        <input type="text" name="quick_comment" placeholder="New Quick Comment" class="form-control quick_comment">
                                                    </div>
                                                    <div class="col-md-3 cls_quick_commentadd_box">
                                                        <button class="btn quick_comment_add"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div onclick="getLiveChats('{{ $customer->id }}')" class="card-body msg_card_body" style="display: none;" id="live-message-recieve">
                                            @if(isset($message) && !empty($message))
                                                @foreach($message as $msg)
                                                    {!! $msg !!}
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="chat-righbox pt-3">
                                            <a href="javascript:;" title="General Info" onclick="openPopupGeneralInfo(<?php echo $chatId->id;?>)" ><i class="fa fa-info" aria-hidden="true"></i></a>
                                            &nbsp;
                                            <a href="javascript:;" title="Visited Pages" onclick="openPopupVisitedPages(<?php echo $chatId->id;?>)" ><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                                            &nbsp;
                                            <a href="javascript:;" class="btn btn-image cls_addition_info" title="Additional info" onclick="openPopupAdditionalinfo(<?php echo $chatId->id;?>)" ><img src="{{asset('images/remark.png')}}"/></a>
                                            &nbsp;
                                            <a href="javascript:;" title="Technology" onclick="openPopupTechnology(<?php echo $chatId->id;?>)" ><i class="fa fa-lightbulb-o" aria-hidden="true"></i></a>


                                            
                                            <div class="modal fade" id="GeneralInfo<?php echo $chatId->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">General Info</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="liveChatCustomerInfo">Comming Soon General Info Data</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="VisitedPages<?php echo $chatId->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Visited Pages</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="liveChatVisitedPages">Comming Soon Visited Pages Data</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="AdditionalInfo<?php echo $chatId->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Additional info</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="line-spacing" id="liveChatAdditionalInfo">Comming Soon Additional info Data</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="Technology<?php echo $chatId->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Technology</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="line-spacing" id="liveChatTechnology">Comming Soon Technology Data</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                   </tr>
                                <?php $srno++;?>
                            @endforeach
                        @endif   
                    </tbody>
                </table>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        var openChatWindow = "<?php echo request('open_chat',false); ?>";
        if(openChatWindow == "true") {
            $("#quick-chatbox-window-modal").modal("show");
               chatBoxOpen = true;
               openChatBox(true);
        }

        $(document).on("click",".send_btn",function(){
            var $this = $(this);
            var customerID = $this.data("id");
            var message = $this.closest("td").find(".message_textarea");
            $.ajax({
                url: "{{ route('livechat.send.message') }}",
                type: 'POST',
                dataType: 'json',
                data: { 
                    id : customerID ,
                    message : message.val(),
                   _token: "{{ csrf_token() }}" 
                }
            }).done(function(data) {
                message.val('');
            }).fail(function() {
                alert('Chat Not Active');
            });
        });

        function openPopupGeneralInfo(id)
        {
            $('#GeneralInfo'+id).modal('show');
        }
        function openPopupVisitedPages(id)
        {
            $('#VisitedPages'+id).modal('show');   
        }
        function openPopupAdditionalinfo(id)
        {
            $('#AdditionalInfo'+id).modal('show');   
        }
        function openPopupTechnology(id)
        {
            $('#Technology'+id).modal('show');   
        }
        function getLiveChats(id){
            // Close the connection, if open.
            if (websocket.readyState === WebSocket.OPEN) {
                clearInterval(pingTimerObj);
                websocket.close();
            }

            $('#liveChatCustomerInfo').html('Fetching Details...');
            $('#liveChatVisitedPages').html('Fetching Details...');
            $('#liveChatAdditionalInfo').html('Fetching Details...');
            $('#liveChatTechnology').html('Fetching Details...');
            $.ajax({
                        url: "{{ route('livechat.get.message') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: { id : id ,   _token: "{{ csrf_token() }}" },
                    })
                    .done(function(data) {
                        console.log(data);
                        //if(typeof data.data.message != "undefined" && data.length > 0 && data.data.length > 0) {
                        $('#live-message-recieve').empty().html(data.data.message);
                        $('#message-id').val(data.data.id);
                        $('#new_message_count').text(data.data.count);
                        $('#user_name').text(data.data.name);
                        $("li.active").removeClass("active");
                        $("#user"+data.data.id).addClass("active");
                        $('#user_inital').text(data.data.customerInital);
                        $('#selected_customer_store').val(data.data.store_website_id);
                        var customerInfo = data.data.customerInfo;
                        if(customerInfo!=''){
                            customerInfoSetter(customerInfo);
                        }
                        else{
                            $('#liveChatCustomerInfo').html('');
                            $('#liveChatVisitedPages').html('');
                            $('#liveChatAdditionalInfo').html('');
                            $('#liveChatTechnology').html('');
                        }

                        currentChatId = data.data.threadId;

                        //open socket
                        runWebSocket(data.data.threadId);

                        //}
                        console.log("success");
                    })
                    .fail(function() {
                        console.log("error");
                        $('#chatCustomerInfo').html('');
                        $('#chatVisitedPages').html('');
                        $('#chatAdditionalInfo').html('');
                        $('#chatTechnology').html('');
                    });
        }

            $(document).on('click', '.expand-row', function () {
                var selection = window.getSelection();
                if (selection.toString().length === 0) {
                    $(this).find('.td-mini-container').toggleClass('hidden');
                    $(this).find('.td-full-container').toggleClass('hidden');
                }
            });
            $(document).on('change', '#categories', function () {
                if ($(this).val() != "") {
                    var category_id = $(this).val();

                    var store_website_id = $('#selected_customer_store').val();
                  /*  if(store_website_id == ''){
                        store_website_id = 0;
                    }*/
                    $.ajax({
                        url: "{{ url('get-store-wise-replies') }}"+'/'+category_id+'/'+store_website_id,
                        type: 'GET',
                        dataType: 'json'
                    }).done(function(data){
                        console.log(data);
                        if(data.status == 1){
                            $('#quick_replies').empty().append('<option value="">Quick Reply</option>');
                            var replies = data.data;
                            replies.forEach(function (reply) {
                                $('#quick_replies').append($('<option>', {
                                    value: reply.reply,
                                    text: reply.reply,
                                    'data-id': reply.id
                                }));
                            });
                        }
                    });

                }
            });

            $('.quick_comment_add').on("click", function () {
                var textBox = $(".quick_comment").val();
                var quickCategory = $('#categories').val();

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
                        'store_website_id' : $('#selected_customer_store').val()
                    }
                }).done(function (data) {
                    console.log(data);
                    $(".quick_comment").val('');
                    $('#quick_replies').append($('<option>', {
                        value: data.data,
                        text: data.data
                    }));
                })
            });

            $('#quick_replies').on("change", function(){
                $('.message_textarea').text($(this).val());
            });
    </script>
@endsection