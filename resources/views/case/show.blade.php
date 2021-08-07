@extends('layouts.app')

@section('title', 'Case Page')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <style>
        #message-wrapper {
            height: 450px;
            overflow-y: scroll;
        }

        .show-images-wrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Case Page</h3>
            </div>
            <div class="pull-right mt-4">
                <a class="btn btn-xs btn-secondary" href="{{ route('case.index') }}">Back</a>
                <a href="{{route('case.receivable', $case->id)}}" class="btn btn-xs btn-secondary" title="Case Receivables" target="_blank">Receivables</a>
                {{-- <a class="btn btn-xs btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
                <a class="btn btn-xs btn-secondary" href="#" id="quick_add_order">+ Order</a>
                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button> --}}
            </div>
        </div>
    </div>

    {{-- @include('customers.partials.modal-private-viewing') --}}

    @include('partials.flash_messages')

    {{--@include('case.partials.product-modals')--}}

    <div id="exTab2" class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#info-tab" data-toggle="tab">Case Info</a>
            </li>
            {{-- <li>
               <a href="#cases-tab" data-toggle="tab">Cases</a>
             </li>--}}
        </ul>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4 border">
            <div class="tab-content">
                <div class="tab-pane active mt-3" id="info-tab">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                {!! Form::label('lawyer_id', 'Lawyer', ['class' => 'form-control-label']) !!}
                                {!! Form::select('lawyer_id', $lawyers, $case->lawyer_id, ['class'=>'form-control','placeholder' => 'Choose lawyer']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('case_number', 'Case Number', ['class' => 'form-control-label']) !!}
                                {!! Form::text('case_number', $case->case_number, ['class'=>'form-control ']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('for_against', 'For / Against', ['class' => 'form-control-label']) !!}
                                {!! Form::text('for_against', $case->for_against, ['class'=>'form-control ']) !!}
                            </div>
                            <div class="form-group">
                                <select name="whatsapp_number" id="case_whatsapp_number" class="form-control input-sm">
                                    <option value>Whatsapp Number</option>
                                    <option value="971569119192" {{ '971569119192' == $case->whatsapp_number ? ' selected' : '' }}>
                                        971569119192 Indian
                                    </option>
                                    <option value="971502609192" {{ '971502609192' == $case->whatsapp_number ? ' selected' : '' }}>
                                        971502609192 Dubai
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                {!! Form::label('court_detail', 'Court and other details of the case', ['class' => 'form-control-label']) !!}
                                {!! Form::textarea('court_detail', $case->court_detail, ['class'=>'form-control ','rows'=>3]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'form-control-label']) !!}
                                {!! Form::select('status', $statuses, $case->status, ['class'=>'form-control ','placeholder' => 'Choose status']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('resource', 'Resource or Case Laws', ['class' => 'form-control-label']) !!}
                                {!! Form::text('resource', $case->resource, ['class'=>'form-control ']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('last_date', 'Last Date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('last_date', $case->last_date, ['class'=>'form-control ']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('next_date', 'Next Date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('next_date', $case->next_date, ['class'=>'form-control ']) !!}
                            </div>

                            <div class="form-group">
                                <button type="button" id="updateCaseButton" class="btn btn-xs btn-secondary">Save
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4 mb-3">
            <div class="border">
                <form action="{{ route('whatsapp.send', 'case') }}" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                                <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                                <input type="file" name="image"/>

                                <button type="submit" class="btn btn-image px-1 send-communication received-customer">
                                    <img src="/images/filled-sent.png"/></button>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <button type="button" id="caseMessageButton" class="btn btn-image"><img
                                        src="/images/support.png"/></button>
                            <textarea class="form-control mb-3 hidden" style="height: 110px;" name="body"
                                      placeholder="Received from Case"></textarea>
                            <input type="hidden" name="status" value="0"/>
                        </div>
                    </div>

                </form>

                <form action="{{ route('whatsapp.send', 'case') }}" method="POST" enctype="multipart/form-data">
                    <div id="paste-container" style="width: 200px;">

                    </div>

                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class=" d-flex flex-column">
                                <div class="">
                                    <div class="upload-btn-wrapper btn-group px-0">
                                        <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                                        <input type="file" name="image"/>

                                    </div>
                                    <button type="submit" class="btn btn-image px-1 send-communication"><img
                                                src="/images/filled-sent.png"/></button>

                                </div>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <textarea id="message-body" class="form-control mb-3" style="height: 110px;" name="body"
                                      placeholder="Send for approval"></textarea>

                            <input type="hidden" name="screenshot_path" value="" id="screenshot_path"/>
                            <input type="hidden" name="status" value="1"/>

                            <div class="paste-container"></div>


                        </div>
                    </div>

                    <div class="pb-4 mt-3">
                        <div class="row">
                            <div class="col">
                                <select name="quickCategory" id="quickCategory" class="form-control input-sm mb-3">
                                    <option value="">Select Category</option>
                                    @foreach($reply_categories as $category)
                                        <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <select name="quickComment" id="quickComment" class="form-control input-sm">
                                    <option value="">Quick Reply</option>
                                </select>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal"
                                        data-target="#ReplyModal" id="approval_reply">Create Quick Reply
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

                <h4>Remarks</h4>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <textarea class="form-control" name="remark" rows="3" cols="10"
                                      placeholder="Remark"></textarea>
                        </div>

                        <div class="form-inline">
                            <button type="button" class="btn btn-xs btn-secondary" id="sendRemarkButton">Send</button>
                            <button type="button" class="btn btn-xs btn-secondary ml-1" id="hideRemarksButton">Show
                            </button>
                        </div>
                    </div>

                    <div class="col-xs-12">

                        <div id="remarks-container" class="hidden">
                            <ul>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="border">
                {{-- <h4>Messages</h4> --}}

                <div class="row">
                    <div class="col-12 my-3" id="message-wrapper">
                        <div id="message-container"></div>
                    </div>

                    <div class="col-xs-12 text-center hidden">
                        <button type="button" id="load-more-messages" data-nextpage="1" class="btn btn-secondary">Load
                            More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('customers.partials.modal-reply')

    <form action="" method="POST" id="product-remove-form">
        @csrf
    </form>

    {{-- @include('customers.partials.modal-forward') --}}

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

    <script type="text/javascript">

        $(document).on('click', ".collapsible-message", function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                var short_message = $(this).data('messageshort');
                var message = $(this).data('message');
                var status = $(this).data('expanded');

                if (status == false) {
                    $(this).addClass('expanded');
                    $(this).html(message);
                    $(this).data('expanded', true);
                    // $(this).siblings('.thumbnail-wrapper').remove();
                    $(this).closest('.talktext').find('.message-img').removeClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', 'auto');
                } else {
                    $(this).removeClass('expanded');
                    $(this).html(short_message);
                    $(this).data('expanded', false);
                    $(this).closest('.talktext').find('.message-img').addClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', '200px');
                }
            }
        });

        $(document).ready(function () {
            var container = $("div#message-container");
            // var sendBtn = $("#waMessageSend");
            var caseId = "{{ $case->id }}";
            var addElapse = false;

            function errorHandler(error) {
                console.error("error occured: ", error);
            }

            function approveMessage(element, message) {
                if (!$(element).attr('disabled')) {
                    $.ajax({
                        type: "POST",
                        url: "/whatsapp/approve/case",
                        data: {
                            _token: "{{ csrf_token() }}",
                            messageId: message.id
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).text('Approving...');
                        }
                    }).done(function (data) {
                        element.remove();
                    }).fail(function (response) {
                        $(element).attr('disabled', false);
                        $(element).text('Approve');

                        console.log(response);
                        alert(response.responseJSON.message);
                    });
                }
            }

            function renderMessage(message, tobottom = null) {
                var domId = "waMessage_" + message.id;
                var current = $("#" + domId);
                var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
                var is_hod_crm = "{{ Auth::user()->hasRole('HOD of CRM') }}";
                var users_array = {!! json_encode($users_array) !!};
                var leads_assigned_user = "";

                if (current.get(0)) {
                    return false;
                }

                // CHAT MESSAGES
                var row = $("<div class='talk-bubble' title='Lawyer : " + message.lawyer + "'></div>");
                var body = $("<span id='message_body_" + message.id + "'></span>");
                var text = $("<div class='talktext'></div>");
                var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.message + '</textarea>');
                var p = $("<p class='collapsible-message'></p>");

                var forward = $('<button class="btn btn-image forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><img src="/images/forward.png" /></button>');


                if (message.status == 0 || message.status == 5 || message.status == 6) {
                    var meta = $("<em>Case " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
                    var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
                    var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');

                    // row.attr("id", domId);
                    p.appendTo(text);

                    // $(images).appendTo(text);
                    meta.appendTo(text);

                    if (message.status == 0) {
                        mark_read.appendTo(meta);
                    }

                    if (message.status == 0 || message.status == 5) {
                        mark_replied.appendTo(meta);
                    }

                    text.appendTo(row);

                    if (tobottom) {
                        row.appendTo(container);
                    } else {
                        row.prependTo(container);
                    }

                    forward.appendTo(meta);

                } else if (message.status == 4) {
                    var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
                    var chat_friend = (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.user_id != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
                    var meta = $("<em>" + users_array[message.user_id] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");

                    // row.attr("id", domId);

                    p.appendTo(text);
                    $(images).appendTo(text);
                    meta.appendTo(text);

                    text.appendTo(row);
                    if (tobottom) {
                        row.appendTo(container);
                    } else {
                        row.prependTo(container);
                    }
                } else {
                    if (message.sent == 0) {
                        var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>";
                    } else {
                        var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /></em>";
                    }

                    var error_flag = '';
                    if (message.error_status == 1) {
                        error_flag = "<a href='#' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /></a><a href='#' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'>Resend</a>";
                    } else if (message.error_status == 2) {
                        error_flag = "<a href='#' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /><img src='/images/flagged.png' /></a><a href='#' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'>Resend</a>";
                    }


                    var meta = $(meta_content);

                    edit_field.appendTo(text);

                    if (!message.approved) {
                        var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
                        var editBtn = ' <a href="#" style="font-size: 9px" class="edit-message whatsapp-message ml-2" data-messageid="' + message.id + '">Edit</a>';
                        approveBtn.click(function () {
                            approveMessage(this, message);
                        });
                        if (is_admin || is_hod_crm) {
                            approveBtn.appendTo(meta);
                            $(editBtn).appendTo(meta);
                        }
                    }

                    forward.appendTo(meta);

                    $(error_flag).appendTo(meta);
                }


                // if (!message.received) {
                //   if (message.sent == 0) {
                //     var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>";
                //   } else {
                //     var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /></em>";
                //   }
                //
                //   var meta = $(meta_content);
                // } else {
                //   var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
                // }

                row.attr("id", domId);

                p.attr("data-messageshort", message.message);
                p.attr("data-message", message.message);
                p.attr("data-expanded", "true");
                p.attr("data-messageid", message.id);
                // console.log("renderMessage message is ", message);
                if (message.message) {
                    p.html(message.message);
                } else if (message.media_url) {
                    var splitted = message.content_type.split("/");
                    if (splitted[0] === "image" || splitted[0] === 'm') {
                        var a = $("<a></a>");
                        a.attr("target", "_blank");
                        a.attr("href", message.media_url);
                        var img = $("<img></img>");
                        img.attr("src", message.media_url);
                        img.attr("width", "100");
                        img.attr("height", "100");
                        img.appendTo(a);
                        a.appendTo(p);
                        // console.log("rendered image message ", a);
                    } else if (splitted[0] === "video") {
                        $("<a target='_blank' href='" + message.media_url + "'>" + message.media_url + "</a>").appendTo(p);
                    }
                }

                var has_product_image = false;

                if (message.images) {
                    var images = '';
                    message.images.forEach(function (image) {
                        images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '<br><strong>Supplier: </strong>' + image.supplier_initials + '">' : '';
                        images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete whatsapp-image" data-image="' + image.key + '">x</span></div>';
                        images += image.product_id !== '' ? '<input type="checkbox" name="product" style="width: 20px; height: 20px;" class="d-block mx-auto select-product-image" data-id="' + image.product_id + '" /></a>' : '';

                        if (image.product_id !== '') {
                            has_product_image = true;
                        }
                    });

                    images += '<br>';

                    if (has_product_image) {
                        var show_images_wrapper = $('<div class="show-images-wrapper hidden"></div>');
                        var show_images_button = $('<button type="button" class="btn btn-xs btn-secondary show-images-button">Show Images</button>');

                        $(images).appendTo(show_images_wrapper);
                        $(show_images_wrapper).appendTo(text);
                        $(show_images_button).appendTo(text);
                    } else {
                        $(images).appendTo(text);
                    }

                }

                p.appendTo(body);
                body.appendTo(text);

                // if (message.status == 0 || message.status == 5 || message.status == 6) {
                //
                // } else {
                //
                //
                // }

                meta.appendTo(text);


                // if (!message.received) {
                //   // if (!message.approved) {
                //   //     var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
                //   //     var editBtn = ' <a href="#" style="font-size: 9px" class="edit-message whatsapp-message ml-2" data-messageid="' + message.id + '">Edit</a>';
                //   //     approveBtn.click(function() {
                //   //         approveMessage( this, message );
                //   //     } );
                //   //     if (is_admin || is_hod_crm) {
                //   //       approveBtn.appendTo( text );
                //   //       $(editBtn).appendTo( text );
                //   //     }
                //   // }
                // } else {
                //   var moduleid = 0;
                //   var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "&moduleid=" + moduleid+ "&moduletype=leads' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
                //   var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '&moduleid=' + moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
                //
                //   if (message.status == 0) {
                //     mark_read.appendTo(meta);
                //   }
                //   if (message.status == 0 || message.status == 5) {
                //     mark_replied.appendTo(meta);
                //   }
                // }

                // var forward = $('<button class="btn btn-xs btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '">Forward >></button>');

                if (has_product_image) {
                    var create_lead = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-lead">+ Lead</a>');
                    var create_order = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-order">+ Order</a>');

                    create_lead.appendTo(meta);
                    create_order.appendTo(meta);
                }

                // forward.appendTo(meta);

                // if (has_product_image) {
                //
                // }

                text.appendTo(row);

                if (tobottom) {
                    row.appendTo(container);
                } else {
                    row.prependTo(container);
                }

                return true;
            }

            const socket = io("https://sololuxury.co/?realtime_id=case_{{ $case->id }}");

            socket.on("new-message", function (message) {
                console.log(message);
                renderMessage(message, null);
            });

            function pollMessages(page = null, tobottom = null, addElapse = null) {
                var qs = "";
                qs += "?caseId=" + caseId;
                if (page) {
                    qs += "&page=" + page;
                }
                if (addElapse) {
                    qs += "&elapse=3600";
                }
                var anyNewMessages = false;

                return new Promise(function (resolve, reject) {
                    $.getJSON("/whatsapp/pollMessagesCustomer" + qs, function (data) {

                        data.data.forEach(function (message) {
                            var rendered = renderMessage(message, tobottom);
                            if (!anyNewMessages && rendered) {
                                anyNewMessages = true;
                            }
                        });

                        if (page) {
                            $('#load-more-messages').text('Load More');
                            can_load_more = true;
                        }

                        if (anyNewMessages) {
                            scrollChatTop();
                            anyNewMessages = false;
                        }
                        if (!addElapse) {
                            addElapse = true; // load less messages now
                        }


                        resolve();
                    });

                });
            }

            function scrollChatTop() {
                // console.log("scrollChatTop called");
                // var el = $(".chat-frame");
                // el.scrollTop(el[0].scrollHeight - el[0].clientHeight);
            }

            pollMessages(null, null, addElapse);
            // function startPolling() {
            //   setTimeout( function() {
            //              pollMessages(null, null, addElapse).then(function() {
            //                  startPolling();
            //              }, errorHandler);
            //          }, 1000);
            // }
            // function sendWAMessage() {
            //   var data = createMessageArgs();
            //          //var data = new FormData();
            //          //data.append("message", $("#waNewMessage").val());
            //          //data.append("lead_id", leadId );
            //   $.ajax({
            //     url: '/whatsapp/sendMessage/customer',
            //     type: 'POST',
            //              "dataType"    : 'text',           // what to expect back from the PHP script, if anything
            //              "cache"       : false,
            //              "contentType" : false,
            //              "processData" : false,
            //              "data": data
            //   }).done( function(response) {
            //       $('#waNewMessage').val('');
            //       $('#waNewMessage').closest('.form-group').find('.dropify-clear').click();
            //       pollMessages();
            //     // console.log("message was sent");
            //   }).fail(function(errObj) {
            //     alert("Could not send message");
            //   });
            // }

            // sendBtn.click(function() {
            //   sendWAMessage();
            // } );
            // startPolling();

            $(document).on('click', '.send-communication', function (e) {
                e.preventDefault();

                var thiss = $(this);
                var url = $(this).closest('form').attr('action');
                var token = "{{ csrf_token() }}";
                var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
                var status = $(this).closest('form').find('input[name="status"]').val();
                var screenshot_path = $('#screenshot_path').val();
                var case_id = {{ $case->id }};
                var formData = new FormData();

                formData.append("_token", token);
                formData.append("image", file);
                formData.append("message", $(this).closest('form').find('textarea').val());
                // formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
                formData.append("case_id", case_id);
                formData.append("lawyer_id", {{$case->lawyer_id}});
                formData.append("assigned_to", $(this).closest('form').find('select[name="assigned_to"]').val());
                formData.append("status", status);
                formData.append("screenshot_path", screenshot_path);

                if ($(this).closest('form')[0].checkValidity()) {
                    if (!$(thiss).is(':disabled')) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                $(thiss).attr('disabled', true);
                            }
                        }).done(function (response) {
                            console.log(response);
                            pollMessages();
                            $(thiss).closest('form').find('textarea').val('');
                            $('#paste-container').empty();
                            $('#screenshot_path').val('');
                            $(thiss).closest('form').find('.dropify-clear').click();

                            if ($(thiss).hasClass('received-customer')) {
                                $(thiss).closest('form').find('#supplierMessageButton').removeClass('hidden');
                                $(thiss).closest('form').find('textarea').addClass('hidden');
                            }

                            $(thiss).attr('disabled', false);
                        }).fail(function (response) {
                            console.log(response);
                            alert('Error sending a message');

                            $(thiss).attr('disabled', false);
                        });
                    }
                } else {
                    $(this).closest('form')[0].reportValidity();
                }

            });

            var can_load_more = true;

            $('#message-wrapper').scroll(function () {
                var top = $('#message-wrapper').scrollTop();
                var document_height = $(document).height();
                var window_height = $('#message-container').height();

                console.log($('#message-wrapper').scrollTop());
                console.log($(document).height());
                console.log($('#message-container').height());

                // if (top >= (document_height - window_height - 200)) {
                if (top >= (window_height - 1500)) {
                    console.log('should load', can_load_more);
                    if (can_load_more) {
                        var current_page = $('#load-more-messages').data('nextpage');
                        $('#load-more-messages').data('nextpage', current_page + 1);
                        var next_page = $('#load-more-messages').data('nextpage');
                        console.log(next_page);
                        $('#load-more-messages').text('Loading...');

                        can_load_more = false;

                        pollMessages(next_page, true);
                    }
                }
            });

            $(document).on('click', '#load-more-messages', function () {
                var current_page = $(this).data('nextpage');
                $(this).data('nextpage', current_page + 1);
                var next_page = $(this).data('nextpage');
                $('#load-more-messages').text('Loading...');

                pollMessages(next_page, true);
            });
        });

        $(document).on('click', '.change_message_status', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var token = "{{ csrf_token() }}";
            var thiss = $(this);

            if ($(this).hasClass('wa_send_message')) {
                var message_id = $(this).data('messageid');
                var message = $('#message_body_' + message_id).find('p').data('message').toString().trim();

                $.ajax({
                    url: "{{ url('whatsapp/updateAndCreate') }}",
                    type: 'POST',
                    data: {
                        _token: token,
                        moduletype: "case",
                        message_id: message_id
                    },
                    beforeSend: function () {
                        $(thiss).text('Loading');
                    }
                }).done(function (response) {
                }).fail(function (errObj) {
                    console.log(errObj);
                    alert("Could not create whatsapp message");
                });
            }
            $.ajax({
                url: url,
                type: 'GET'
            }).done(function (response) {
                $(thiss).remove();
            }).fail(function (errObj) {
                alert("Could not change status");
            });


        });

        $(document).on('click', '.edit-message', function (e) {
            e.preventDefault();
            var thiss = $(this);
            var message_id = $(this).data('messageid');

            $('#message_body_' + message_id).css({'display': 'none'});
            $('#edit-message-textarea' + message_id).css({'display': 'block'});

            $('#edit-message-textarea' + message_id).keypress(function (e) {
                var key = e.which;

                if (key == 13) {
                    e.preventDefault();
                    var token = "{{ csrf_token() }}";
                    var url = "{{ url('message') }}/" + message_id;
                    var message = $('#edit-message-textarea' + message_id).val();

                    if ($(thiss).hasClass('whatsapp-message')) {
                        var type = 'whatsapp';
                    } else {
                        var type = 'message';
                    }

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            _token: token,
                            body: message,
                            type: type
                        },
                        success: function (data) {
                            $('#edit-message-textarea' + message_id).css({'display': 'none'});
                            $('#message_body_' + message_id).text(message);
                            $('#message_body_' + message_id).css({'display': 'block'});
                        }
                    });
                }
            });
        });

        $(document).on('click', '.thumbnail-delete', function (event) {
            event.preventDefault();
            var thiss = $(this);
            var image_id = $(this).data('image');
            var message_id = $(this).closest('.talk-bubble').find('.collapsible-message').data('messageid');
            // var message = $(this).closest('.talk-bubble').find('.collapsible-message').data('message');
            var token = "{{ csrf_token() }}";
            var url = "{{ url('message') }}/" + message_id + '/removeImage';
            var type = 'message';

            if ($(this).hasClass('whatsapp-image')) {
                type = "whatsapp";
            }

            // var image_container = '<div class="thumbnail-wrapper"><img src="' + image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image + '">x</span></div>';
            // var new_message = message.replace(image_container, '');

            // if (new_message.indexOf('message-img') != -1) {
            //   var short_new_message = new_message.substr(0, new_message.indexOf('<div class="thumbnail-wrapper">')).length > 150 ? (new_message.substr(0, 147)) : new_message;
            // } else {
            //   var short_new_message = new_message.length > 150 ? new_message.substr(0, 147) + '...' : new_message;
            // }

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    image_id: image_id,
                    message_id: message_id,
                    type: type
                },
                success: function (data) {
                    $(thiss).parent().remove();
                    // $('#message_body_' + message_id).children('.collapsible-message').data('messageshort', short_new_message);
                    // $('#message_body_' + message_id).children('.collapsible-message').data('message', new_message);
                }
            });
        });

        $(document).ready(function () {
            $("body").tooltip({selector: '[data-toggle=tooltip]'});
        });

        $('#approval_reply').on('click', function () {
            $('#model_field').val('Approval Lead');
        });

        $('#internal_reply').on('click', function () {
            $('#model_field').val('Internal Lead');
        });

        $('#approvalReplyForm').on('submit', function (e) {
            e.preventDefault();

            var url = "{{ route('reply.store') }}";
            var reply = $('#reply_field').val();
            var category_id = $('#category_id_field').val();
            var model = $('#model_field').val();

            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reply: reply,
                    category_id: category_id,
                    model: model
                },
                success: function (reply) {
                    // $('#ReplyModal').modal('hide');
                    $('#reply_field').val('');
                    if (model == 'Approval Lead') {
                        $('#quickComment').append($('<option>', {
                            value: reply,
                            text: reply
                        }));
                    } else {
                        $('#quickCommentInternal').append($('<option>', {
                            value: reply,
                            text: reply
                        }));
                    }

                }
            });
        });

        $(document).on('click', '.forward-btn', function () {
            var id = $(this).data('id');
            $('#forward_message_id').val(id);
        });

        $('#quickCategory').on('change', function () {
            var replies = JSON.parse($(this).val());
            $('#quickComment').empty();

            $('#quickComment').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));

            replies.forEach(function (reply) {
                $('#quickComment').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply
                }));
            });
        });

        $('#quickCategoryInternal').on('change', function () {
            var replies = JSON.parse($(this).val());
            $('#quickCommentInternal').empty();

            $('#quickCommentInternal').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));

            replies.forEach(function (reply) {
                $('#quickCommentInternal').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply
                }));
            });
        });

        $(document).on('click', '.collapse-fix', function () {
            if (!$(this).hasClass('collapsed')) {
                var target = $(this).data('target');
                var all = $('.collapse-element').not($(target));

                Array.from(all).forEach(function (element) {
                    $(element).removeClass('in');
                });
            }
        });

        // if ($(this).is(":focus")) {
        // Created by STRd6
        // MIT License
        // jquery.paste_image_reader.js
        (function ($) {
            var defaults;
            $.event.fix = (function (originalFix) {
                return function (event) {
                    event = originalFix.apply(this, arguments);
                    if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                        event.clipboardData = event.originalEvent.clipboardData;
                    }
                    return event;
                };
            })($.event.fix);
            defaults = {
                callback: $.noop,
                matchType: /image.*/
            };
            return $.fn.pasteImageReader = function (options) {
                if (typeof options === "function") {
                    options = {
                        callback: options
                    };
                }
                options = $.extend({}, defaults, options);
                return this.each(function () {
                    var $this, element;
                    element = this;
                    $this = $(this);
                    return $this.bind('paste', function (event) {
                        var clipboardData, found;
                        found = false;
                        clipboardData = event.clipboardData;
                        return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                            var file, reader;
                            if (found) {
                                return;
                            }
                            if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                                file = clipboardData.items[i].getAsFile();
                                reader = new FileReader();
                                reader.onload = function (evt) {
                                    return options.callback.call(element, {
                                        dataURL: evt.target.result,
                                        event: evt,
                                        file: file,
                                        name: file.name
                                    });
                                };
                                reader.readAsDataURL(file);
                                return found = true;
                            }
                        });
                    });
                });
            };
        })(jQuery);

        var dataURL, filename;
        $("html").pasteImageReader(function (results) {
            console.log(results);

            // $('#message-body').on('focus', function() {
            filename = results.filename, dataURL = results.dataURL;

            var img = $('<div class="image-wrapper position-relative"><img src="' + dataURL + '" class="img-responsive" /><button type="button" class="btn btn-xs btn-secondary remove-screenshot">x</button></div>');

            $('#paste-container').empty();
            $('#paste-container').append(img);
            $('#screenshot_path').val(dataURL);
            // });

        });

        $(document).on('click', '.remove-screenshot', function () {
            $(this).closest('.image-wrapper').remove();
            $('#screenshot_path').val('');
        });
        // }


        $(document).on('click', '.change-history-toggle', function () {
            $(this).siblings('.change-history-container').toggleClass('hidden');
        });

        $('#caseMessageButton').on('click', function () {
            $(this).siblings('textarea').removeClass('hidden');
            $(this).addClass('hidden');
        });

        $('#updateCaseButton').on('click', function () {
            var id = {{ $case->id }};
            var thiss = $(this);
            var case_number = $('#case_number').val();
            var for_against = $('#for_against').val();
            var lawyer_id = $('#lawyer_id').val();
            var default_phone = $('#case_default_phone').val();
            var whatsapp_number = $('#case_whatsapp_number').val();
            var status = $('#status').val();
            var resource = $('#resource').val();
            var last_date = $('#last_date').val();
            var next_date = $('#next_date').val();
            var cost_per_hearing = $('#cost_per_hearing').val();

            $.ajax({
                type: "POST",
                url: "{{ url('case') }}/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    lawyer_id: lawyer_id,
                    for_against: for_against,
                    case_number: case_number,
                    default_phone: default_phone,
                    whatsapp_number: whatsapp_number,
                    status: status,
                    resource: resource,
                    last_date: last_date,
                    next_date: next_date,
                },
                beforeSend: function () {
                    $(thiss).text('Saving...');
                }
            }).done(function () {
                $(thiss).text('Save');
                $(thiss).removeClass('btn-secondary');
                $(thiss).addClass('btn-success');

                setTimeout(function () {
                    $(thiss).addClass('btn-secondary');
                    $(thiss).removeClass('btn-success');
                }, 2000);
            }).fail(function (response) {
                $(thiss).text('Save');
                console.log(response);
                alert('Could not update case');
            });
        });

        $('#showActionsButton').on('click', function () {
            $('#actions-container').toggleClass('hidden');
        });

        $(document).on('click', '.show-images-button', function () {
            $(this).siblings('.show-images-wrapper').toggleClass('hidden');
        });

        $(document).on('click', '.fix-message-error', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('whatsapp') }}/" + id + "/fixMessageError",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Fixing...');
                }
            }).done(function () {
                $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/flagged.png" />');

                console.log(response);

                alert('Could not mark as fixed');
            });
        });

        $(document).on('click', '.resend-message', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('whatsapp') }}/" + id + "/resendMessage",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Sending...');
                }
            }).done(function () {
                $(thiss).remove();
            }).fail(function (response) {
                $(thiss).text('Resend');

                console.log(response);

                alert('Could not resend message');
            });
        });

        $(document).on('click', '.flag-supplier', function () {
            var supplier_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('supplier.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier_id: supplier_id
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
                if (response.is_flagged == 1) {
                    $(thiss).html('<img src="/images/flagged.png" />');
                } else {
                    $(thiss).html('<img src="/images/unflagged.png" />');
                }

            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag supplier!');

                console.log(response);
            });
        });

        $(document).on('click', '.edit-product', function () {
            var product = $(this).data('product');
            var url = "{{ url('case/product') }}/" + product.id;

            $('#productEditModal form').attr('action', url);
            $('#case_case_id').val(product.case_id);
            $('#case_date_of_order').val(product.date_of_order);
            $('#case_name').val(product.name);
            $('#case_qty').val(product.qty);
            $('#case_price').val(product.price);
            $('#case_payment_terms').val(product.payment_terms);
            $('#case_recurring_type option[value="' + product.recurring_type + '"]').prop('selected', true);
            $('#case_delivery_date').val(product.delivery_date);
            $('#case_received_by').val(product.received_by);
            $('#case_approved_by').val(product.approved_by);
            $('#case_payment_details').val(product.payment_details);
        });

        $('#date-of-order, #case-date-of-order, #delivery-date, #case-delivery-date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $('#sendRemarkButton').on('click', function () {
            var id = {{ $case->id }};
            var remark = $(this).parent('div').siblings('.form-group').find('textarea').val();
            var thiss = $(this);

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'case'
                },
            }).done(response => {
                $(thiss).parent('div').siblings('.form-group').find('textarea').val('');
                var comment = '<li> ' + remark + ' <br> <small>By updated on ' + moment().format('DD-M H:mm') + ' </small></li>';

                $('#remarks-container').find('ul').prepend(comment);
            }).fail(function (response) {
                console.log(response);
                alert('Could not add remark');
            });
        });

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.gettaskremark') }}',
            data: {
                id: "{{ $case->id }}",
                module_type: "case"
            },
        }).done(response => {
            var html = '';

            $.each(response, function (index, value) {
                html += ' <li> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></li>';
            });
            $("#remarks-container").find('ul').html(html);
        });

        $('#hideRemarksButton').on('click', function () {
            $('#remarks-container').toggleClass('hidden');
        });
    </script>
@endsection
