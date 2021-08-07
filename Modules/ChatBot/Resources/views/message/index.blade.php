@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Message List | Chatbot')

@section('content')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">
    <style type="text/css">
        .panel-img-shorts {
            width: 80px;
            height: 80px;
            display: inline-block;
        }

        .panel-img-shorts .remove-img {
            display: block;
            float: right;
            width: 15px;
            height: 15px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Message List | Chatbot</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;">
            <div class="pull-left">
                <div class="form-inline">
                    <form method="get">
                        <div class="row">


                            <div class="col pr-0">
                                <?php echo Form::text("search", request("search", null), ["class" => "form-control", "placeholder" => "Enter input here.."]); ?>
                            </div>
                            <div class="col">
                                <select name="status" class="chatboat-message-status form-control">
                                    <option value="">Select Status</option>
                                    <option value="1" {{request()->get('status') == '1' ? 'selected' : ''}}>
                                        Approved
                                    </option>
                                    <option value="0" {{request()->get('status') == '0' ? 'selected' : ''}}>
                                        Unapproved
                                    </option>
                                </select>
                            </div>

                            <!-- START - Purpose : Set unreplied messages - DEVATSK=4350 -->
                            <div style="display: flex;align-items: center">
                                
                                    @if(isset($_REQUEST['unreplied_msg']) && $_REQUEST['unreplied_msg']== true)
                                        @php $check_status = 'checked'; @endphp
                                    @else
                                        @php $check_status = ''; @endphp
                                    @endif
                               
                                <input class="mt-0 mr-2" type="checkbox" id="unreplied_msg" name="unreplied_msg" {{$check_status}} value="true"> Unreplied Messages
                            </div>
                            <div style="margin-left: 20px;display: flex;align-items: center">
                                    @if(request("unread_message") == "true")
                                        @php $check_status = 'checked'; @endphp
                                    @else
                                        @php $check_status = ''; @endphp
                                    @endif
                               
                                <input class="mt-0 mr-2" type="checkbox" id="unread_message" name="unread_message" {{$check_status}} value="true"> Unread Messages
                            </div>
                            <!-- END - DEVATSK=4350 -->

                            <button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
                                <img src="/images/search.png" style="cursor: default;">
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="pull-right">
                <div class="form-inline">
                    <form method="post">
                        <?php echo csrf_field(); ?>
                        <?php echo Form::select("customer_id[]", [], null, ["class" => "form-control customer-search-select-box", "multiple" => true, "style" => "width:250px;"]); ?>
                        <button type="submit" style="display: inline-block;width: 10%"
                                class="btn btn-sm btn-image btn-forward-images">
                            <i class="glyphicon glyphicon-send"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive-lg" id="page-view-result">
                @include("chatbot::message.partial.list")
            </div>
        </div>
    </div>
    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                    <input type="hidden" id="chat_obj_type" name="chat_obj_type">
                    <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                    <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif')
  50% 50% no-repeat;display:none;">
    </div>
    <script src="/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript" src="/js/jsrender.min.js"></script>
    <script type="text/javascript" src="/js/common-helper.js"></script>
    <script type="text/javascript">
        $(document).on("click", ".approve-message", function () {
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: "/chatbot/messages/approve",
                beforeSend: function () {
                    $("#loading-image").show();
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $this.data("id"),
                },
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    $this.remove();
                    toastr['success'](response.message, 'success');
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                console.log("Sorry, something went wrong");
            });
        });

        var getResults = function (href) {
            $.ajax({
                type: 'GET',
                url: href,
                beforeSend: function () {
                    $("#loading-image").show();
                },
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    var removePage = response.page;
                    if (removePage > 0) {
                        var pageList = $("#page-view-result").find(".page-template-" + removePage);
                        pageList.nextAll().remove();
                        pageList.remove();
                    }
                    if (removePage > 1) {
                        $("#page-view-result").find(".pagination").first().remove();
                    }
                    $("#page-view-result").append(response.tpl);
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                console.log("Sorry, something went wrong");
            });
        };

        $("#page-view-result").on("click", ".page-link", function (e) {
            e.preventDefault();

            var activePage = $(this).closest(".pagination").find(".active").text();
            var clickedPage = $(this).text();
            if (clickedPage == "‹" || clickedPage < activePage) {
                $('html, body').animate({scrollTop: ($(window).scrollTop() - 50) + "px"}, 200);
                getResults($(this).attr("href"));
            } else {
                getResults($(this).attr("href"));
            }

        });

        $(window).scroll(function () {
            if ($(window).scrollTop() > ($(document).height() - $(window).height() - 10)) {
                $("#page-view-result").find(".pagination").find(".active").next().find("a").click();
            }
        });

        $(document).on("click", ".delete-images", function () {

            var tr = $(this).closest("tr");
            var checkedImages = tr.find(".remove-img:checkbox:checked").closest(".panel-img-shorts");
            var form = tr.find('.remove-images-form');
            $.ajax({
                type: 'POST',
                url: form.attr("action"),
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading-image").show();
                },
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    $.each(checkedImages, function (k, e) {
                        $(e).remove();
                    });
                    toastr['success'](response.message, 'success');
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                console.log("Sorry, something went wrong");
            });
        });

        $(document).on("click", ".add-more-images", function () {
            var $this = $(this);
            var id = $this.data("id");

            $.ajax({
                type: 'GET',
                url: "{{ route('chatbot.messages.attach-images') }}",
                data: {chat_id: id},
                beforeSend: function () {
                    $("#loading-image").show();
                },
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    if (response.data.length > 0) {
                        var html = "";
                        $.each(response.data, function (k, img) {
                            html += '<div class="panel-img-shorts">';
                            html += '<input type="checkbox" name="delete_images[]" value="' + img.mediable_id + '_' + img.id + '" class="remove-img" data-media-id="' + img.id + '" data-mediable-id="' + img.mediable_id + '">';
                            html += '<img width="50px" heigh="50px" src="' + img.url + '">';
                            html += '</div>';
                        });
                        $this.closest("tr").find(".images-layout").find("form").append(html);
                    }
                    toastr['success'](response.message, 'success');
                } else {
                    toastr['error'](response.message, 'error');
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                console.log("Sorry, something went wrong");
            });
        });

        $(document).on("click", ".check-all", function () {
            var tr = $(this).closest("tr");
            tr.find(".remove-img").trigger("click");
        });

        $(document).on("click", ".btn-forward-images", function (e) {
            e.preventDefault();
            var selectedImages = $("#page-view-result").find(".remove-img:checkbox:checked");
            var imagesArr = [];
            $.each(selectedImages, function (k, v) {
                imagesArr.push($(v).data("media-id"));
            });
            $.ajax({
                type: "POST",
                url: "/chatbot/messages/forward-images",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'images': imagesArr,
                    'customer': $(".customer-search-select-box").val()
                }
            }).done(function (response) {
                if (response.code == 200) {
                    toastr['success'](response.message, 'success');
                } else {
                    toastr['error'](response.message, 'error');
                }
            });

        });

        $(document).on('click', '.send-message1', function () {
            console.log('*****************************');
            var thiss = $(this);
            var data = new FormData();
            
            var field = "customer_id";
            var tr  = $(this).closest("tr").find("td").first();
            var typeId = tr.data('customer-id');
            var chatMessageReplyId = tr.data('chat-message-reply-id')
            var type = tr.data("context");
            var data_chatbot_id = tr.data('chatbot-id');

            console.log(type);

            if(parseInt(tr.data("vendor-id")) > 0) {
                type = "vendor";
                typeId = tr.data("vendor-id");
                field = "vendor_id";

                //START - Purpose : Add vendor content - DEVTASK-4203
                var message = thiss.closest(".cls_textarea_subbox").find("textarea").val();
                data.append("vendor_id", typeId);
                data.append("message", message);
                data.append("status", 2);
                data.append("sendTo", 'to_developer');
                data.append("chat_reply_message_id", chatMessageReplyId)
                //END - DEVTASK-4203
            }
            
            var customer_id = typeId;
            var message = thiss.closest(".cls_textarea_subbox").find("textarea").val();

            if(type === 'customer'){

                data.append("customer_id", typeId);
                data.append("message", message);
                data.append("status", 1);

            }else if(type === 'issue'){

                data.append('issue_id', typeId);
                data.append("message", message);
                data.append("sendTo", 'to_developer');
                data.append("status", 2)
                data.append("chat_reply_message_id", chatMessageReplyId)

            }else if(type === 'issue'){
                data.append('issue_id', typeId);
                data.append("message", message);
                data.append("status", 1)
                data.append("chat_reply_message_id", chatMessageReplyId)
            }
            //START - Purpose : Task message - DEVTASK-4203
            else if(type === 'task'){
                data.append('task_id', typeId);
                data.append("message", message);
                data.append("status", 2)
                data.append("sendTo", 'to_developer');
                data.append("chat_reply_message_id", chatMessageReplyId)
            }
            //END - DEVTASK-4203

             //STRAT - Purpose : send message - DEVTASK-18280
            else if(type === 'chatbot'){
                data.append('customer_id', typeId);
                data.append("message", message);
                data.append("status", 1)
                data.append("chat_reply_message_id", data_chatbot_id)

                id = typeId;
                var scrolled=0;
                $.ajax({
                    url: "{{ route('livechat.send.message') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: { id : id ,
                        message : message,
                        from:'chatbot_replay',
                    _token: "{{ csrf_token() }}" 
                    },
                })
                .done(function(data) {
                    
                })
            }
            //END - DEVTASK-18280

            var add_autocomplete  = thiss.closest(".cls_textarea_subbox").find("[name=add_to_autocomplete]").is(':checked') ;
            data.append("add_autocomplete", add_autocomplete);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: BASE_URL+'/whatsapp/sendMessage/'+type,
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);

                        }
                    }).done(function (response) {
                        $(thiss).attr('disabled', false);
                        thiss.closest(".cls_textarea_subbox").find("textarea").val("");
                        toastr['success']("Message sent successfully", 'success');

                    }).fail(function (errObj) {
                       
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });

        var siteHelpers = {
            quickCategoryAdd : function(ele) {
                var textBox = ele.closest("div").find(".quick_category");
                if (textBox.val() == "") {
                    alert("Please Enter Category!!");
                    return false;
                }
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        name : textBox.val()
                    },
                    url: "/add-reply-category"
                };
                siteHelpers.sendAjax(params,"afterQuickCategoryAdd");
            },
            afterQuickCategoryAdd : function(response) {
                $(".quick_category").val('');
                $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
            },
            deleteQuickCategory : function(ele) {
                var quickCategory = ele.closest(".communication").find(".quickCategory");
                if (quickCategory.val() == "") {
                    alert("Please Select Category!!");
                    return false;
                }
                var quickCategoryId = quickCategory.children("option:selected").data('id');
                if (!confirm("Are sure you want to delete category?")) {
                    return false;
                }
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        id : quickCategoryId
                    },
                    url: "/destroy-reply-category"
                };
                siteHelpers.sendAjax(params,"pageReload");
            },
            deleteQuickComment : function(ele) {
                var quickComment = ele.closest(".communication").find(".quickComment");
                if (quickComment.val() == "") {
                    alert("Please Select Quick Comment!!");
                    return false;
                }
                var quickCommentId = quickComment.children("option:selected").data('id');
                if (!confirm("Are sure you want to delete comment?")) {
                    return false;
                }
                var params = {
                    method : 'DELETE',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/reply/" + quickCommentId,
                };
                siteHelpers.sendAjax(params,"pageReload");
            },
            quickCommentAdd : function(ele) {
                var textBox = ele.closest("div").find(".quick_comment");
                var quickCategory = ele.closest(".communication").find(".quickCategory");
                if (textBox.val() == "") {
                    alert("Please Enter New Quick Comment!!");
                    return false;
                }
                if (quickCategory.val() == "") {
                    alert("Please Select Category!!");
                    return false;
                }
                var quickCategoryId = quickCategory.children("option:selected").data('id');
                var formData = new FormData();
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("reply", textBox.val());
                formData.append("category_id", quickCategoryId);
                formData.append("model", 'Approval Lead');
                var params = {
                    method : 'post',
                    data : formData,
                    url: "/reply"
                };
                siteHelpers.sendFormDataAjax(params,"afterQuickCommentAdd");
            },
            afterQuickCommentAdd : function(reply) {
                $(".quick_comment").val('');
                $('.quickComment').append($('<option>', {
                    value: reply,
                    text: reply
                }));
            },
            changeQuickCategory : function (ele) {
                if (ele.val() != "") {
                    var replies = JSON.parse(ele.val());
                    ele.closest(".communication").find('.quickComment').empty();
                    ele.closest(".communication").find('.quickComment').append($('<option>', {
                        value: '',
                        text: 'Quick Reply'
                    }));
                    replies.forEach(function (reply) {
                        ele.closest(".communication").find('.quickComment').append($('<option>', {
                            value: reply.reply,
                            text: reply.reply,
                            'data-id': reply.id
                        }));
                    });
                }
            },
            changeQuickComment : function (ele) {
                ele.closest('.customer-raw-line').find('.quick-message-field').val(ele.val());
            }
        };
        $.extend(siteHelpers, common)

        $(document).on('click', '.quick_category_add', function () {
            siteHelpers.quickCategoryAdd($(this));
        });
        $(document).on('click', '.delete_category', function () {
            siteHelpers.deleteQuickCategory($(this));
        });
        $(document).on('click', '.delete_quick_comment', function () {
            siteHelpers.deleteQuickComment($(this));
        });
        $(document).on('click', '.quick_comment_add', function () {
            siteHelpers.quickCommentAdd($(this));
        });
        $(document).on('change', '.quickCategory', function () {
            siteHelpers.changeQuickCategory($(this));
        });
        $(document).on('change', '.quickComment', function () {
            siteHelpers.changeQuickComment($(this));
        });
    

    </script>
@endsection
