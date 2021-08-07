@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Bulk Customer Replies</h2>
    </div>
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Keywords, Phrases & Sentences</strong>
            </div>
            <div class="panel-body">
                <form method="post" action="{{ action('BulkCustomerRepliesController@storeKeyword') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="text" name="keyword" id="keyword" placeholder="Keyword, phrase or sentence..." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-secondary btn-block">Add New</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="alert alert-warning">
                    <strong>Note: Click any tag below, and it will show the customer with the keywords used.</strong>
                </div>
                <div>
                    <strong>Manually Added</strong><br>
                    @foreach($keywords as $keyword)
                        <a href="{{ action('BulkCustomerRepliesController@index', ['keyword_filter' => $keyword->value]) }}" style="font-size: 14px;" class="label label-default">{{$keyword->value}}</a>
                    @endforeach
                </div>
                <div class="mt-2">
                    <strong>Auto Generated</strong><br>
                    @foreach($autoKeywords as $keyword)
                        <a href="{{ action('BulkCustomerRepliesController@index', ['keyword_filter' => $keyword->value]) }}" style="font-size: 14px; margin-bottom: 2px; display:inline-block;" class="label label-default">{{$keyword->value}}({{$keyword->count}})</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        

        <form class="form-inline">
            <div class="form-group mb-2">
                <a class="btn btn-secondary change-whatsapp" href="javascript:;">Change Whatsapp</a>
            </div>
            <div class="form-group mb-2">

        <select name="dnd_enabled">
            <option value="all" {{ app('request')->dnd_enabled === 'all' ? 'selected' : '' }} >DND: ALL</option>
            
            <option value="0" {{ app('request')->dnd_enabled === null || app('request')->dnd_enabled === '0' ? 'selected' : '' }} >DND: Disabled</option>
            <option value="1" {{ app('request')->dnd_enabled === null || app('request')->dnd_enabled === '1' ? 'selected' : '' }} >DND: Enabled</option>
        </select>
         
        <input name="keyword_filter" type="hidden" value="{{ app('request')->keyword_filter }}">
            </div>
            <div class="form-group mb-2">
            <button type="submit" class="btn btn-image"><img src="/images/filter.png" style="cursor: nwse-resize;"></button>
            </div>
        </form>
        @if($searchedKeyword)
            @if($searchedKeyword->customers)
                
                <form id="send-messages-by-Keyword" action="{{ action('BulkCustomerRepliesController@sendMessagesByKeyword') }}" method="post">
                    @csrf
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th style="width:1%">Pick</th>
                            <th style="width:2%">S.N</th>
                            <th style="width:6%">Customer ({{count($customers)}})</th>
                            <th style="width:12%">Whatsapp num</th>
                            <th style="width:23%">Shortcuts</th>
                            <th style="width:23%">Next Action</th>
                            <th style="width:25%" >Communication</th>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div class="row">
                                    <div class="col-md-11">
                                        <textarea name="message_bulk" id="message" rows="1" class="form-control" placeholder="Common message.."></textarea>
                                        <input type="hidden" name="keyword_id" value="{{ $searchedKeyword->id }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-secondary btn-block">Send</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
{{--                        @php--}}
{{--                            $searchWithPagination = $customers;--}}
{{--                        @endphp--}}
                        @foreach($customers as $key => $customer)
<!--                            --><?php //dump($customer->dnd); ?>
                            <tr data-customer_id="{{ $customer->id }}" class="customer-id-remove-class">
                                <td><input type="checkbox" name="customers[]" value="{{ $customer->id }}" class="customer_message"></td>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $customer->name }}<br>
                                    @if(count($customer->dnd) == 0)
                                    <span data-customer_id="{{$customer->id}}" class="add_to_dnd" style="cursor:pointer;font-size:12px">
                                        Add to DND
                                    </span>
                                    @else
                                    <span data-customer_id="{{$customer->id}}" class="remove_from_dnd" style="cursor:pointer;font-size:12px">
                                         Remove from DND
                                    </span>
                                    @endif
                                </td>
                                <td>

                                    <select class="form-control change-whatsapp-no-bulk" data-customer-id="<?php echo $customer->id; ?>" data-type="whatsapp_number">
                                        <option value="">-No Selected-</option>
                                        @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
                                            @if($number != "0")
                                                <option {{ ($number == $customer->whatsapp_number && $customer->whatsapp_number != '') ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>@include('bulk-customer-replies.partials.shortcuts')</td>
                                <td>@include('bulk-customer-replies.partials.next_actions')</td>
                                <td class="communication-td">@include('bulk-customer-replies.partials.communication')</td>
                            </tr>
                        @endforeach
                    </table>
                    {!! $customers->appends(request()->query())->links() !!}

                </form>



            @else
            @endif
        @endif
    </div>
    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                    <input type="text" name="search_chat_pop_time"  class="form-control search_chat_pop_time" placeholder="Search Time" style="width: 200px;">
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-change-whatsapp" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('bulk-messages.whatsapp-no') }}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Whatsapp no?</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <?php echo Form::select("whatsapp_no",$whatsappNos,null,["class" => "form-control select2 whatsapp_no" , "style" => "width:100%"]); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default modal-change-whatsapp-btn">Change ?</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include("partials.customer-new-ticket")
    
    
@endsection

@section('scripts')
    <!-- <script type="text/javascript" src="/js/site-helper.js"></script> -->
    <script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>       

    <script>

        autosize(document.getElementById("message"));

        $(document).on('click', '.add_to_dnd', function(){

            const urlSearchParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(urlSearchParams.entries());
            $this = $(this);
            $.ajax({
                url: "/category-messages/bulk-messages/addToDND",
                type: 'POST',
                data: {
                    "customer_id": $(this).data('customer_id'),
                    "filter": params,
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    $this.html('<img src="/images/do-disturb.png"/>');
                    $this.removeClass('add_to_dnd').addClass('remove_from_dnd')
                },
                error: function () {
                    
                }
            });

        });

        $(document).on('click', '.remove_from_dnd', function(){

            const urlSearchParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(urlSearchParams.entries());
            $this = $(this);
            $.ajax({
                url: "/category-messages/bulk-messages/removeFromDND",
                type: 'POST',
                data: {
                    "customer_id": $(this).data('customer_id'),
                    "filter": params,
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    $this.html('<img src="/images/do-not-disturb.png"/>');
                    $this.removeClass('remove_from_dnd').addClass('add_to_dnd')
                },
                error: function () {

                }
            });

        });

        $(document).on('click', '.add_next_action', function (event) {
            event.preventDefault();
            $.ajax({
                url: "/erp-customer/add-next-actions",
                type: 'POST',
                data: {
                    "name": $('input[name="add_next_action"]').val(),
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Action added successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                    location.reload();
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });
        $(document).on("click",".send-with-audio-message",function(event) {
            event.preventDefault();
            if($(this).hasClass("mic-active") == false) {
                if($(".mic-button-input").hasClass("mic-active") == false) {
                    $(".mic-button-input").trigger("click");
                }else{
                    $(".mic-button-input").trigger("click");
                    $(".mic-button-input").trigger("click");
                }
                $(".message-strong").removeClass("message-strong");
                $(this).closest(".infinite-scroll").find(".mic-active").removeClass("mic-active");
                $(this).closest(".communication").find(".quick-message-field").addClass("message-strong");
                $(this).addClass("mic-active");
            }else{
                if($(".mic-button-input").hasClass("mic-active") == false) {
                }else{
                    $(".mic-button-input").trigger("click");
                }
                $(".message-strong").removeClass("message-strong");
                $(this).removeClass("mic-active");
            }
        });
        $(document).on('click', '.delete_category', function (event) {
            event.preventDefault();
            var quickCategory  = $(this).parents("div").siblings().children(".quickCategory");
            console.log(quickCategory.val());
            let quickCategoryId = quickCategory.children("option:selected").data('id');
            if (quickCategory.val() == '') {
                alert('Please select category to delete!')
                return false;
            }
            $.ajax({
                url: "/destroy-reply-category",
                type: 'POST',
                data: {
                    "_token": "{{csrf_token()}}",
                    id : quickCategoryId
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Category deleted successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                    location.reload();
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });
        $(document).on('click', '.delete_quick_comment', function (event) {
            event.preventDefault();
            var quickComment  = $(this).parents("div").siblings().children(".quickCategory");
            let quickCommentId = quickComment.children("option:selected").data('id');
            if (quickComment.val() == '') {
                alert('Please select comment to delete!')
                return false;
            }
            $.ajax({
                url: "/reply/" + quickCommentId,
                type: 'POST',
                data: {
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Category deleted successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                    location.reload();
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });
        $(document).on('click', '.send-message-open', function (event) {
            event.preventDefault();
            var textBox = $(this).closest(".communication-td").find(".send-message-textbox");
            var sendToStr  = $(this).closest(".communication-td").next().find(".send-message-number").val();
            let issueId = textBox.attr('data-customerid');
            let message = textBox.val();
            if (message == '') {
                alert('Please enter message!')
                return false;
            }
            let self = textBox;
            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'customer')}}",
                type: 'POST',
                data: {
                    "customer_id": issueId,
                    "message": message,
                    //"sendTo" : sendToStr,
                    "_token": "{{csrf_token()}}",
                   "status": 2
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Message sent successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                   // location.reload();
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });
        $(document).on('click', '.quick_category_add', function () {
            event.preventDefault();
            if($('input[name="category_name"]').val() == ''){
                alert("Please Enter category name!");
                return false;
            }
            $.ajax({
                url: "/add-reply-category",
                type: 'POST',
                data: {
                    name : $('input[name="category_name"]').val(),
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Category added successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(this).removeAttr('disabled');
                    $('input[name="category_name"]').removeAttr('disabled');
                    $('input[name="category_name"]').val('');
                    location.reload();
                },
                beforeSend: function () {
                    $('input[name="category_name"]').attr('disabled', true);
                    $('input[name="category_name"]').attr('disabled',true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(this).removeAttr('disabled');
                    $('input[name="category_name"]').removeAttr('disabled');
                    $('input[name="category_name"]').val('');
                }
            });
            //siteHelpers.quickCategoryAdd($(this));
        });
        $(document).on('click', '.quick_comment_add', function () {
            event.preventDefault();
            if ($('input[name="quick_comment"]').val() == "") {
                alert("Please Enter New Quick Comment!!");
                return false;
            }
            if ($('select[name="quickCategory"]').val() == "") {
                alert("Please Select Category!!");
                return false;
            }
            var quickCategoryId = $('select[name="quickCategory"]').children("option:selected").data('id');
            var formData = new FormData();
            formData.append("_token", "{{csrf_token()}}");
            formData.append("reply", $('input[name="quick_comment"]').val());
            formData.append("category_id", quickCategoryId);
            formData.append("model", 'Approval Lead');
            $.ajax({
                url: "/reply",
                type: 'POST',
                //data : formData,
                data: {
                    "reply":  $('input[name="quick_comment"]').val(),
                    "category_id": quickCategoryId,
                    "model": 'Approval Lead',
                    "_token": "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    toastr["success"]("Category added successfully!", "Message");
                    //$('#message_list_' + issueId).append('<li>' + response.message.created_at + " : " + response.message.message + '</li>');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                    location.reload();
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending the message...');
                    $(self).removeAttr('disabled', true);
                }
            });
        });

        $(document).on("click",".change-whatsapp",function(){
            $("#modal-change-whatsapp").modal("show");
        });
        $(document).on("click",".modal-change-whatsapp-btn",function(){
            var customers = [];
            var all_customers = [];
            $(".customer_message").each(function () {
                if ($(this).prop("checked") == true) {
                    customers.push($(this).val());
                }
            });
            if (all_customers.length != 0) {
                customers = all_customers;
            }
            if (customers.length == 0) {
                alert('Please select Customer');
                return false;
            }
            var form = $(this).closest("form");
            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                dataType : "json",
                data : {
                    _token : $('meta[name="csrf-token"]').attr('content'),
                    customers: customers.join(),
                    whatsapp_no: form.find(".whatsapp_no").val()
                },
                success: function(data) {
                    toastr['success'](data.total + ' record has been update successfully', 'success');
                    //location.reload();
                }
            });
        });
    $.extend($.expr[':'], {
      'containsi': function(elem, i, match, array) {
        return (elem.textContent || elem.innerText || '').toLowerCase()
            .indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });
     $(document).on('keyup','.search_chat_pop',function(event){
        event.preventDefault();
        if($('.search_chat_pop').val().toLowerCase() != ''){
            $(".message").css("background-color", "#999999");
            page = $('.message').text().toLowerCase();
            searchedText = $('.search_chat_pop').val().toLowerCase();
            console.log(searchedText);
            $("p.message:containsi('"+searchedText+"')").css("background-color", "yellow");
        }
    });

    $("#send-messages-by-Keyword").submit(function(e) {

        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData($(this)[0]),
            contentType: false,
            processData: false,
            success: function(response)
            {
                toastr.success(response.message);
                // cu_id = response.c_id;
                // for (i = 0; i < cu_id.length; i++) {
                //     $('.customer-id-remove-class[data-customer_id="' + cu_id[i] + '"]').hide();
                // }
            }
        });
    });

    $(document).on('change', '.change-whatsapp-no-bulk', function () {
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.change.whatsapp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: $this.data("customer-id"),
                    number: $this.val(),
                    type : $this.data("type")
                }
            }).done(function () {
                alert('Number updated successfully!');
            }).fail(function (response) {
                console.log(response);
            });
        });


    $(document).on("change",".quickComment",function() {
        var $this = $(this);
        $this.closest("tr").find(".send-message-textbox").val($this.val());
    });

    $(document).on('click', '.add_to_customer_dnd', function() {
            var id = $(this).data('id');
            var thiss = $(this);
            $.ajax({
                type: "POST",
                url: "/customer/" + id + '/updateDND',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $("#loading-image").show();
                }
            }).done(function(response) {
                $("#loading-image").hide();
              if (response.do_not_disturb == 1) {
                $(thiss).html('<img src="/images/do-not-disturb.png" />');
              } else {
                $(thiss).html('<img src="/images/do-disturb.png" />');
              }
            }).fail(function(response) {
               $("#loading-image").hide();
              alert('Could not update DND status');
              console.log(response);
            })
      });


    </script>
@endsection