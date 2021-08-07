@extends('layouts.app')

@section('title', 'Old Vendor Page')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <style>
        #chat-history {
            background-color: #EEEEEE;
            height: 450px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .speech-wrapper .bubble.alt {
            margin: 0 0 25px 20% !important;
        }

        .show-images-wrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        input[type="checkbox"][id^="cb"] {
            display: none;
        }

        .label-attached-img {
            border: 1px solid #fff;
            display: block;
            position: relative;
            cursor: pointer;
        }

        .label-attached-img:before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        :checked + .label-attached-img {
            border-color: #ddd;
        }

        :checked + .label-attached-img:before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        :checked + .label-attached-img img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }
    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Old Vendor Page</h3>
            </div>
            <div class="pull-right mt-4">
                <a class="btn btn-xs btn-secondary" href="{{ route('old.index') }}">Back</a>
                <a href="{{route('old.payments', $old->serial_no )}}" class="btn btn-secondary btn-xs" title="Old Vendor Payments" target="_blank">Payments </a>
                
            </div>
        </div>
    </div>

    

    @include('partials.flash_messages')

   

    <div id="exTab2" class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#info-tab" data-toggle="tab">Old Vendor Info</a>
            </li>
            <!-- <li>
                <a href="#agents-tab" data-toggle="tab">Agents</a>
            </li> -->
            <li>
                <a href="#email-tab" data-toggle="tab" data-id="{{ $old->serial_no }}" data-type="inbox">Emails</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4 border">
            <div class="tab-content">
                <div class="tab-pane active mt-3" id="info-tab">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <select class="form-control input-sm" name="category_id" id="old_category">
                                    <option value="">Select a Category</option>

                                    @foreach ($old_categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $old->category_id ? 'selected' : '' }}>{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <input type="text" name="name" id="old_name_name" class="form-control input-sm" placeholder="Vendor" value="{{ $old->name }}">

                               
                            </div>

                            <div class="form-group form-inline">
                                <input type="number" id="old_phone" name="phone" class="form-control input-sm" placeholder="910000000000" value="{{ $old->phone }}">
                            </div>

                            
                            <div class="form-group">
                                <textarea name="address" id="old_address" class="form-control input-sm" rows="3" cols="80" placeholder="Address">{{ $old->address }}</textarea>
                            </div>

                            {{-- @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM')) --}}
                            <div class="form-group">
                                <input type="email" name="email" id="old_email" class="form-control input-sm" placeholder="Email" value="{{ $old->email }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="gst" id="old_gst" class="form-control input-sm" placeholder="GST" value="{{ $old->gst }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_name" id="old_account_number" class="form-control input-sm" placeholder="Account Name" value="{{ $old->account_number }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_iban" id="old_account_iban" class="form-control input-sm" placeholder="IBAN" value="{{ $old->account_iban }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_swift" id="old_account_swift" class="form-control input-sm" placeholder="SWIFT" value="{{ $old->account_swift }}">
                            </div>

                            <div class="form-group">
                                <button type="button" id="updateOldButton" class="btn btn-xs btn-secondary">Save</button>
                            </div>
                        </div>

                    </div>
                </div>

                @include('old.partials.agent-modals')


                <div class="tab-pane mt-3" id="agents-tab">
                    <button type="button" class="btn btn-xs btn-secondary mb-3 create-agent" data-toggle="modal" data-target="#createAgentModal" data-id="{{ $old->serial_no }}">Add Agent</button>

                  
                </div>

                <div class="tab-pane mt-3" id="email-tab">
                    <div id="exTab3" class="mb-3">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#email-inbox" data-toggle="tab" id="email-inbox-tab" data-id="{{ $old->serial_no }}" data-type="inbox">Inbox</a>
                            </li>
                            <li>
                                <a href="#email-sent" data-toggle="tab" id="email-sent-tab" data-id="{{ $old->serial_no }}" data-type="sent">Sent</a>
                            </li>
                            <li class="nav-item ml-auto">
                                <button type="button" class="btn btn-image" data-toggle="modal" data-target="#emailSendModal"><img src="{{ asset('images/filled-sent.png') }}"/></button>
                            </li>
                        </ul>
                    </div>

                    <div id="email-container">
                        @include('purchase.partials.email')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4 mb-3">
            <div class="border">
                <form action="{{ route('whatsapp.send', 'old') }}" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                                <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                                <input type="file" name="image"/>

                                <button type="submit" class="btn btn-image px-1 send-communication received-customer"><img src="/images/filled-sent.png"/></button>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <button type="button" id="vendorMessageButton" class="btn btn-image"><img src="/images/support.png"/></button>
                            <textarea class="form-control mb-3 hidden" style="height: 110px;" name="body" placeholder="Received from Vendor"></textarea>
                            <input type="hidden" name="status" value="0"/>
                        </div>
                    </div>

                </form>

                <form action="{{ route('whatsapp.send', 'old') }}" method="POST" enctype="multipart/form-data">
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
                                    <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png"/></button>

                                </div>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <textarea id="message-body" class="form-control mb-3" style="height: 110px;" name="body" placeholder="Send for approval"></textarea>

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
                                    @php $reply_categories = \App\ReplyCategory::all(); @endphp
                                    @foreach($reply_categories as $category)
                                        <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <select name="quickComment" id="quickComment" class="form-control input-sm">
                                    <option value="">Quick Reply</option>
                                </select>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
                            </div>
                        </div>
                    </div>

                </form>

                <h4>Remarks</h4>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <textarea class="form-control" name="remark" rows="3" cols="10" placeholder="Remark"></textarea>
                        </div>

                        <div class="form-inline">
                            <button type="button" class="btn btn-xs btn-secondary" id="sendRemarkButton">Send</button>
                            <button type="button" class="btn btn-xs btn-secondary ml-1" id="hideRemarksButton">Show</button>
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
                <div class="row">
                    <div class="col-12 load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" id="chat-history" data-object="old" data-attached="1" data-id="{{ $old->serial_no }}"></div>
                </div>
            </div>
        </div>
    </div>

   
    


    @include('customers.partials.modal-reply')

    <form action="" method="POST" id="product-remove-form">
        @csrf
    </form>

    {{-- @include('customers.partials.modal-forward') --}}
    @include('old.partials.modal-email')

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.load-communication-modal').trigger('click');
        });
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
            // var sendBtn = $("#waMessageSend");
            var oldId = "{{ $old->serial_no }}";
            var addElapse = false;

            function errorHandler(error) {
                console.error("error occured: ", error);
            }

            function approveMessage(element, message) {
                if (!$(element).attr('disabled')) {
                    $.ajax({
                        type: "POST",
                        url: "/whatsapp/approve/old",
                        data: {
                            _token: "{{ csrf_token() }}",
                            messageId: message.id,
                            old_id : oldId,
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

            if ( current.get( 0 ) ) {
              return false;
            }

               // CHAT MESSAGES
               var row = $("<div class='talk-bubble'></div>");
               var body = $("<span id='message_body_" + message.id + "'></span>");
               var text = $("<div class='talktext'></div>");
               var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.message + '</textarea>');
               var p = $("<p class='collapsible-message'></p>");

               var forward = $('<button class="btn btn-image forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><img src="/images/forward.png" /></button>');



               if (message.status == 0 || message.status == 5 || message.status == 6) {
                 var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
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
                 var chat_friend =  (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.user_id != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
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
                     approveBtn.click(function() {
                         approveMessage( this, message );
                     } );
                     if (is_admin || is_hod_crm) {
                       approveBtn.appendTo( meta );
                       $(editBtn).appendTo( meta );
                     }
                 }

                 forward.appendTo(meta);

                 $(error_flag).appendTo(meta);
               }



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
                   if (splitted[0]==="image" || splitted[0] === 'm') {
                       var a = $("<a></a>");
                       a.attr("target", "_blank");
                       a.attr("href", message.media_url);
                       var img = $("<img></img>");
                       img.attr("src", message.media_url);
                       img.attr("width", "100");
                       img.attr("height", "100");
                       img.appendTo( a );
                       a.appendTo( p );
                       // console.log("rendered image message ", a);
                   } else if (splitted[0]==="video") {
                       $("<a target='_blank' href='" + message.media_url+"'>"+ message.media_url + "</a>").appendTo(p);
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

              

               meta.appendTo(text);



               if (has_product_image) {
                 var create_lead = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-lead">+ Lead</a>');
                 var create_order = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-order">+ Order</a>');

                 create_lead.appendTo(meta);
                 create_order.appendTo(meta);
               }

               

               text.appendTo( row );

               if (tobottom) {
                 row.appendTo(container);
               } else {
                 row.prependTo(container);
               }

                     return true;
        }

        

        function pollMessages(page = null, tobottom = null, addElapse = null) {
                 var qs = "";
                 qs += "?oldID=" + {{ $old->serial_no }};
                 if (page) {
                   qs += "&page=" + page;
                 }
                 if (addElapse) {
                     qs += "&elapse=3600";
                 }
                 var anyNewMessages = false;

                 return new Promise(function(resolve, reject) {
                     $.getJSON("/whatsapp/pollMessagesCustomer" + qs, function( data ) {

                         data.data.forEach(function( message ) {
                             var rendered = renderMessage( message, tobottom );
                             if ( !anyNewMessages && rendered ) {
                                 anyNewMessages = true;
                             }
                         } );

                         if (page) {
                           $('#load-more-messages').text('Load More');
                           can_load_more = true;
                         }

                         if ( anyNewMessages ) {
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
             }
             pollMessages(null, null, addElapse);

            $(document).on('click', '.send-communication', function (e) {
                e.preventDefault();

                var thiss = $(this);
                var url = $(this).closest('form').attr('action');
                var token = "{{ csrf_token() }}";
                var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
                var status = $(this).closest('form').find('input[name="status"]').val();
                var screenshot_path = $('#screenshot_path').val();
                var old_id = {{ $old->serial_no }};
                var formData = new FormData();
                console.log(url);
                formData.append("_token", token);
                formData.append("image", file);
                formData.append("message", $(this).closest('form').find('textarea').val());
                formData.append("old_id", old_id);
                formData.append("assigned_to", $(this).closest('form').find('select[name="assigned_to"]').val());
                formData.append("status", status);
                formData.append("screenshot_path", screenshot_path);
                console.log(formData);
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
                        moduletype: "old",
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

        $('#vendorMessageButton').on('click', function () {
            $(this).siblings('textarea').removeClass('hidden');
            $(this).addClass('hidden');
        });

        $('#updateOldButton').on('click', function () {
            var id = {{ $old->serial_no }};
            var thiss = $(this);
            var name = $('#old_name_name').val();
            var category = $('#old_category').val();
            var phone = $('#old_phone').val();
            var address = $('#old_address').val();
            var email = $('#old_email').val();
            var gst = $('#old_gst').val();
            var account_number = $('#old_account_number').val();
            var account_iban = $('#old_account_iban').val();
            var account_swift = $('#old_account_swift').val();

            $.ajax({
                type: "POST",
                url: "{{ url('old') }}/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    category_id: category,
                    name: name,
                    phone: phone,
                    address: address,
                    email: email,
                    gst: gst,
                    account_number: account_number,
                    account_swift: account_swift,
                    account_iban: account_iban,
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
                alert('Could not update old vendor');
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
            var url = "{{ url('vendors/product') }}/" + product.id;

            $('#productEditModal form').attr('action', url);
            $('#vendor_vendor_id').val(product.vendor_id);
            $('#vendor_date_of_order').val(product.date_of_order);
            $('#vendor_name').val(product.name);
            $('#vendor_qty').val(product.qty);
            $('#vendor_price').val(product.price);
            $('#vendor_payment_terms').val(product.payment_terms);
            $('#vendor_recurring_type option[value="' + product.recurring_type + '"]').prop('selected', true);
            $('#vendor_delivery_date').val(product.delivery_date);
            $('#vendor_received_by').val(product.received_by);
            $('#vendor_approved_by').val(product.approved_by);
            $('#vendor_payment_details').val(product.payment_details);
        });

        $('#date-of-order, #vendor-date-of-order, #delivery-date, #vendor-delivery-date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $('#sendRemarkButton').on('click', function () {
            var id = {{ $old->serial_no }};
            var remark = $(this).parent('div').siblings('.form-group').find('textarea').val();
            var thiss = $(this);
            //console.log(remark);
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('old.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
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
            url: '{{ route('old.gettaskremark') }}',
            data: {
                id: "{{ $old->serial_no }}",
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

        $('a[href="#email-tab"], #email-inbox-tab, #email-sent-tab').on('click', function () {
            var old_id = $(this).data('id');
            var type = $(this).data('type');

            $.ajax({
                url: "{{ route('old.email.inbox') }}",
                type: "GET",
                data: {
                    old_id : old_id,
                    type: type
                },
                beforeSend: function () {
                    $('#email-tab #email-container .card').html('Loading emails');
                }
            }).done(function (response) {
                console.log(response);
                $('#email-tab #email-container').html(response.emails);
            }).fail(function (response) {
                $('#email-tab #email-container .card').html();

                alert('Could not fetch emails');
                console.log(response);
            });
        });

          // cc

        $(document).on('click', '.add-cc', function (e) {
            e.preventDefault();

            if ($('#cc-label').is(':hidden')) {
                $('#cc-label').fadeIn();
            }

            var el = `<div class="row cc-input">
            <div class="col-md-10">
                <input type="text" name="cc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image cc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#cc-list').append(el);
        });

        $(document).on('click', '.cc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.cc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#cc-label').fadeOut();
                }
            });
        });

        // bcc

        $(document).on('click', '.add-bcc', function (e) {
            e.preventDefault();

            if ($('#bcc-label').is(':hidden')) {
                $('#bcc-label').fadeIn();
            }

            var el = `<div class="row bcc-input">
            <div class="col-md-10">
                <input type="text" name="bcc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image bcc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#bcc-list').append(el);
        });

        $(document).on('click', '.bcc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.bcc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#bcc-label').fadeOut();
                }
            });
        });


    </script>
@endsection
