var currentChatParams = {};
var workingOn =  null;
var load_type =  null;
var is_admin =  null;
var is_hod_crm =  null;

var getMoreChatConvo = function(params) {
    var AllMessages = [];
    workingOn = $.ajax({
        type: "GET",
        url: params.url,
        data: params.data,
        beforeSend: function () {
            var loadingIcon = '<div id="loading-image" style="position: relative;left: 0px;top: 0px;width: 100%;height: 120px;z-index: 9999;background: url(/images/pre-loader.gif)50% 50% no-repeat;"></div>';
            if ($('#chat-list-history').length > 0) {
                $("#chat-list-history").find(".modal-body").append(loadingIcon);
            } else {
                $("#chat-history").append(loadingIcon);
            }
        }
    }).done(function (response) {
        workingOn = null;
        if(response.messages.length > 0) {
            AllMessages = AllMessages.concat(response.messages);
            var li = getHtml(response);
            if ($('#chat-list-history').length > 0) {
                $("#chat-list-history").find(".modal-body").find("#loading-image").remove();
                $("#chat-list-history").find(".modal-body").append(li);
                //$("#chat-list-history").modal("show");
            } else {
                $("#chat-history").find("#loading-image").remove();
                $("#chat-history").append(li);
            }
            var searchterm = $('.search_chat_pop').val();
            if(searchterm && searchterm != '') {
                var value = searchterm.toLowerCase();
                $(".filter-message").each(function () {
                    if ($(this).text().search(new RegExp(value, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                });
            }
        }else{
            $("#chat-list-history").find(".modal-body").find("#loading-image").remove();
            $("#chat-history").find("#loading-image").remove();
            currentChatParams.data.hasMore = false;
        }

    }).fail(function (response) {
        workingOn = null;
    });

};

var getHtml = function(response) {
    var j = 0;
    
       
    var classMaster = (load_type == "broadcast" || load_type == "images") ? "full-match-img" : "";
    

    // if(load_type == "broadcast" || load_type == "images") {
    //     $("#chat-list-history").find(".modal-dialog").addClass("modal-lg");
    // }else{
    //     $("#chat-list-history").find(".modal-dialog").removeClass("modal-lg");
    // }
    // <input type="text" id="click-to-clipboard-message" class="form-control link hidden" style="position: absolute; left: -1000px;"></input>
    
    var fullHtml = '<div style="overflow-x:auto;"><input type="text" id="click-to-clipboard-message" class="link" style="position: absolute; left: -5000px;"></input><table class="table table-bordered">';

    (response.messages).forEach(function (message) {
        // Set empty image var
        var media = '';
        var imgSrc = '';
        var parentMedia = '';
        var parentImgSrc = '';
        var li = '<div class="speech-wrapper '+classMaster+'">';
        if(message.is_reviewed == 1) {
            var reviewed_msg = 'reviewed_msg';
        }
        else {
            var reviewed_msg = '';
        }
        if(message.inout == 'out') {
            fullHtml = fullHtml + '<tr class="out-background filter-message '+ reviewed_msg+'" data-message="'+message.message+'">';
        }
        else {
            fullHtml = fullHtml + '<tr class="in-background filter-message reviewed_msg" data-message="'+message.message+'">'; 
        }
        fullHtml = fullHtml + '<td style="width:5%"><input data-id="'+message.id+'" data-message="'+message.message+'" type="checkbox" class="click-to-clipboard" /></td>';
        var fromMsg = '';
        // Check for attached media (ERP attached media)
        if (currentChatParams.data.load_attached == 1 && message.mediaWithDetails && message.mediaWithDetails.length > 0) {
            for (var i = 0; i < message.mediaWithDetails.length; i++) {
                // Get image to display
                imgSrc = getImageToDisplay(message.mediaWithDetails[i].image);
                var productId = message.mediaWithDetails[i].product_id;

                // Set media
                if (imgSrc != '') {
                    media = media + '<div class="col-4"><a href="' + message.mediaWithDetails[i].image + '" target="_blank" class=""><input type="checkbox" name="product" value="' + productId + '" id="cb1_' + i + '" /><label class="label-attached-img" for="cb1_' + i + '"><img src="' + imgSrc + '" style="max-width: 100%;"></label></a></div>';
                }
            }
        }

        // check for media with details
        var classFive = (load_type == "broadcast" || load_type == "images") ? "col-md-4" : "col-12";
        if (currentChatParams.data.load_attached == 1 && message.media && message.media.length > 0) {
            for (var i = 0; i < message.media.length; i++) {
                // Get image to display
                imgSrc = getImageToDisplay(message.media[i].image);

                //console.log(message.media[i], message.product_id);

                // Set media
                if (imgSrc != '') {
                    media = media + '<div class="'+classFive+'">';
                    var imageType = (message.media[i].image).substr( (message.media[i].image).length - 4).toLowerCase();
                    if (message.media[i].product_id) {

                        if (imageType == '.jpg' || imageType == 'jpeg' || imageType == '.png' || imageType == '.gif') {
                            media = media + '<a href="javascript:;" data-id="' + message.media[i].product_id + '" class="show-product-info "><img src="' + imgSrc + '" style="max-width: 100%;"></a>';
                        } else {
                            media = media + '<a class="show-thumbnail-image has-pdf" href="' + message.media[i].image + '" target="_blank"><img src="' + imgSrc + '" style="max-width: 100%;"></a>';
                        }
                    } else {
                        if (imageType == '.jpg' || imageType == 'jpeg' || imageType == '.png' || imageType == '.gif') {
                            media = media + '<a class="" href="' + message.media[i].image + '" target="_blank"><img src="' + imgSrc + '" style="max-width: 100%;"></a>';
                        }else{
                            media = media + '<a class="show-thumbnail-image has-pdf" href="' + message.media[i].image + '" target="_blank"><img src="' + imgSrc + '" style="max-width: 100%;"></a>';
                        }
                    }

                    if (message.media[i].product_id > 0 && message.customer_id > 0) {
                        media = media + '<br />';
                        media = media + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-lead-dimension" data-id="' + message.media[i].product_id + '" data-customer-id="'+message.customer_id+'">+ Dimensions</a>';
                        media = media + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-lead" data-id="' + message.media[i].product_id + '" data-customer-id="'+message.customer_id+'">+ Lead</a>';
                        media = media + '<a href="#" class="btn btn-xs btn-default ml-1 create-detail_image" data-id="' + message.media[i].product_id + '" data-customer-id="'+message.customer_id+'">Detailed Images</a>';
                        media = media + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-order" data-id="' + message.media[i].product_id + '" data-customer-id="'+message.customer_id+'">+ Order</a>';
                        media = media + '<a href="#" class="btn btn-xs btn-default ml-1 create-kyc-customer" data-media-key="'+message.media[i].key+'" data-customer-id="'+message.customer_id+'">+ KYC</a>';
                    }
                    media = media + '</div>';
                }
            }
        }


        // Do we have media sent with the message?
        if (media != '') {
            media = '<div style="max-width: 100%; margin-bottom: 10px;"><div class="row">' + media + '</div></div>';
        }

        // Check for media URL
        if (message.media_url != null) {
            // Get image to display
            imgSrc = getImageToDisplay(message.media_url);

            // Display media in chat
            if (message.type == "supplier") {
                media = '<input type="checkbox" class="form-control" name="checkbox[]" value="' + imgSrc + '" id="cb1_m_' + j + '" style="border: 3px solid black;"/><a href="' + message.media_url + '" target="_blank"><img src="' + imgSrc + '" style="max-width: 100%;"></a>';
                j++;
            } else {
                media =  '<div style="margin-bottom:10px;">';
                media += '<div class="row">';
                media += '<div class="'+classFive+'">';
                media += '<a href="' + message.media_url + '" class="show-product-info" target="_blank"><img src="' + imgSrc + '" style="max-width: 100%;"></a>'; // + media;
                media += '</div>';
                media += '</div>';
                media += '</div>';
            }

        }
        //here

        // Set empty button var
        var button = "";

        if (message.error_status == 1) {
            button += '<i title="'+message.error_info+'" class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
        }
        if (message.type == "task" || message.type == "customer" || message.type == "vendor" || message.type == "user") {

            if (message.status == 0 || message.status == 5 || message.status == 6) {
                if (message.status == 0) {
                    button += "<a title='Mark as Read' href='javascript:;' data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "' class='btn btn-xs btn-secondary ml-1 change_message_status'><i class='fa fa-check' aria-hidden='true'></i></a>";
                }

                if (message.status == 0 || message.status == 5) {
                  button += '<a href="javascript:;" title="Mark as Replied" data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '" class="btn btn-xs btn-secondary ml-1 change_message_status"> <img src="/images/2.png" /> </a>';
                }
                button += '&nbsp;<button title="forward"  class="btn btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>&nbsp;<button title="Resend" data-id="'+message.id+'" class="btn btn-xs btn-secondary resend-message-js"><i class="fa fa-repeat" aria-hidden="true"></i></button>';
            } else if (message.status == 4) {
            } else {
                if ( message.type == "customer") {
                    if (message.error_status == 1) {
                        button +="<a href='javascript:;' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /></a><a href='#' title='Resend' class='btn btn-xs btn-secondary ml-1 resend-message-js' data-id='" + message.id + "'><i class='fa fa-repeat' aria-hidden='true'></i></a>";
                     } else if (message.error_status == 2) {
                       button += "<a href='javascript:;' class='btn btn-image fix-message-error' data-id='" + message.id + "'><img src='/images/flagged.png' /><img src='/images/flagged.png' /></a><a title='Resend' href='#' class='btn btn-xs btn-secondary ml-1 resend-message-js' data-id='" + message.id + "'><i class='fa fa-repeat' aria-hidden='true'></i></a>";
                     }

                     if (!message.approved) {
                         if (is_admin || is_hod_crm) {
                            approveBtn = "<button title='Approve' class='btn btn-xs btn-secondary btn-approve ml-3' data-messageid='" + message.id + "'><i class='fa fa-thumbs-up' aria-hidden='true'></i></button>";
                            button += approveBtn;
                            button += '<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.message + '</textarea>';
                            button += ' <a title="Edit" href="#" style="font-size: 13px" class="btn btn-secondary edit-message whatsapp-message ml-2" data-messageid="' + message.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                         }
                     }
                     button += '&nbsp;<button title="Forward" class="btn btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>&nbsp;<button title="Resend" data-id="'+message.id+'" class="btn btn-xs btn-secondary resend-message"><i class="fa fa-repeat" aria-hidden="true"></i></button>';
                     
                }


                if (message.type == "task" || message.type == "vendor") {
                    button += "<a href='#' title='Resend' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'><i class='fa fa-repeat' aria-hidden='true'></i> (" + message.resent + ")</a>";
                    if(message.type != "vendor") {
                        button += "<a href='#' class='btn btn-image ml-1 reminder-message' data-id='" + message.id + "' data-toggle='modal' data-target='#reminderMessageModal'><img src='/images/reminder.png' /></a>";
                    }
                }

                if (message.type == "vendor") {
                    button += '&nbsp;<button title="Forward" class="btn btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>&nbsp;';
                }
            }

            var image_url = message.media_url;
            if(image_url == null){
               if(message.media.length != 0){
                   image_url = message.media[0].image;
               }
            }
            button += '&nbsp;<button title="Search Product Image" data-media-url="\''+image_url+'\'" data-id="'+message.id+'" class="btn btn-xs btn-secondary search-image"><i class="fa fa-search" aria-hidden="true"></i></button>';

        }
        if(message.type == "developer_task" ) {
            if (message.status == 0) {
                button += "<a title='Mark as Read' href='javascript:;' data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "' class='btn btn-xs btn-secondary ml-1 change_message_status'><i class='fa fa-check' aria-hidden='true'></i></a>";
            }
            button += "<a href='#' title='Resend' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'><i class='fa fa-repeat' aria-hidden='true'></i> (" + message.resent + ")</a>";
        }
        button += '<a title="Remove" href="javascript:;" class="btn btn-xs btn-secondary ml-1 delete-message" data-id="' + message.id + '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
        //START - Purpose : Add resend button - DEVTASK-4236
        if(message.type == "supplier")
        {
            button += "<a href='#' title='Resend' class='btn btn-xs btn-secondary ml-1 resend-message' data-id='" + message.id + "'><i class='fa fa-repeat' aria-hidden='true'></i> (" + message.resent + ")</a>";

            if(message.additional_data != '' && message.additional_data != null)
            { 
                button += "<a href='/purchase-product/download_excel_file/?filename="+ message.additional_data +"' title='Download Excel' class='btn btn-xs btn-secondary ml-1 download_excel' data-id='" + message.id + "'><i class='fa fa-file-excel-o' aria-hidden='true'></i></a>";
            }
        }
        //END - DEVTASK-4236
        if(message.is_queue == 1) {
           button += '<a href="javascript:;" class="btn btn-xs btn-default ml-1">In Queue</a>';
        }
        if(message.is_reviewed != 1) {
            button += '&nbsp;<button title="Mark as reviewed" class="btn btn-secondary review-btn" data-id="' + message.id + '"><i class="fa fa-check" aria-hidden="true"></i></button>&nbsp;';
        }
        
        if (message.inout == 'out' || message.inout == 'in') {
            button += '<a title="Dialog" href="javascript:;" class="btn btn-xs btn-secondary ml-1 create-dialog"><i class="fa fa-plus" aria-hidden="true"></i></a>';
        }


        //check parent media details
        if (message.parentMediaWithDetails && message.parentMediaWithDetails.length > 0) {
                // Get image to display
                parentImgSrc = getImageToDisplay(message.parentMediaWithDetails[0].image);
                var parentProductId = message.parentMediaWithDetails[0].parentProductId;

                // Set media
                if (parentImgSrc != '') {
                    parentMedia = parentMedia + '<div class="col-4"><a href="' + message.parentMediaWithDetails[0].image + '" target="_blank" class=""><input type="checkbox" name="product" value="' + parentProductId + '" id="cb1_' + 0 + '" /><label class="label-attached-img" for="cb1_' + 0 + '"><img src="' + parentImgSrc + '" style="max-width: 100%;"></label></a></div>';
                }
        }


        if (message.parentMedia && message.parentMedia.length > 0) {
            // for (var i = 0; i < message.media.length; i++) {
                // Get image to display
                parentImgSrc = getImageToDisplay(message.parentMedia[0].image);
                
        
                // Set media
                if (parentImgSrc != '') {
                    parentMedia = parentMedia + '<div class="'+classFive+'">';
                    var imageType = (message.parentMedia[0].image).substr( (message.parentMedia[0].image).length - 4).toLowerCase();
                    if (message.parentMedia[0].product_id) {
        
                        if (imageType == '.jpg' || imageType == 'jpeg' || imageType == '.png' || imageType == '.gif') {
                            parentMedia = parentMedia + '<a href="javascript:;" data-id="' + message.parentMedia[0].product_id + '" class="show-product-info "><img style="height:100px;" src="' + parentImgSrc + '" style="max-width: 100%;"></a>';
                        } else {
                            parentMedia = parentMedia + '<a class="show-thumbnail-image has-pdf" href="' + message.parentMedia[0].image + '" target="_blank"><img style="height:100px;" src="' + parentImgSrc + '" style="max-width: 100%;"></a>';
                        }
                    } else {
                        if (imageType == '.jpg' || imageType == 'jpeg' || imageType == '.png' || imageType == '.gif') {
                            parentMedia = parentMedia + '<a class="" href="' + message.parentMedia[0].image + '" target="_blank"><img style="height:100px;" src="' + parentImgSrc + '" style="max-width: 100%;"></a>';
                        }else{
                            parentMedia = parentMedia + '<a class="show-thumbnail-image has-pdf" href="' + message.parentMedia[0].image + '" target="_blank"><img style="height:100px;" src="' + parentImgSrc + '" style="max-width: 100%;"></a>';
                        }
                    }
        
                    if (message.parentMedia[0].product_id > 0 && message.customer_id > 0) {
                        parentMedia = parentMedia + '<br />';
                        parentMedia = parentMedia + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-lead-dimension" data-id="' + message.parentMedia[0].product_id + '" data-customer-id="'+message.customer_id+'">+ Dimensions</a>';
                        parentMedia = parentMedia + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-lead" data-id="' + message.parentMedia[0].product_id + '" data-customer-id="'+message.customer_id+'">+ Lead</a>';
                        parentMedia = parentMedia + '<a href="#" class="btn btn-xs btn-default ml-1 create-detail_image" data-id="' + message.parentMedia[0].product_id + '" data-customer-id="'+message.customer_id+'">Detailed Images</a>';
                        parentMedia = parentMedia + '<a href="#" class="btn btn-xs btn-default ml-1 create-product-order" data-id="' + message.parentMedia[0].product_id + '" data-customer-id="'+message.customer_id+'">+ Order</a>';
                        parentMedia = parentMedia + '<a href="#" class="btn btn-xs btn-default ml-1 create-kyc-customer" data-media-key="'+message.parentMedia[0].key+'" data-customer-id="'+message.customer_id+'">+ KYC</a>';
                    }
                    parentMedia = parentMedia + '</div>';
                }
            // }
        }

        if(message.datetime) {
            var datetime = message.datetime.substr(0, 19);
        }
        else {
            var datetime = message.datetime;
        }
       

        if (message.inout == 'in') {
            if (message.quoted_message_id) {
                if (parentMedia != '') {
                    parentMedia = '<div style="max-width: 100%; margin-bottom: 10px;"><div class="row">' + parentMedia + '</div></div>';
                }

                li = li + '<div data-target="#'+message.quoted_message_id+'" class="chat-reply-div">'+ parentMedia + message.parentMessage + '</div>';
            }

            li += '<div id="'+message.id+'" class="bubble"><div class="txt"><p class="name"></p><p class="message" data-message="'+message.message+'">' + media + message.message + '</p></div></div>';
            fromMsg = fromMsg + '<span class="timestamp" style="color:black; text-transform: capitalize;font-size: 14px;">From ' + message.sendBy + ' to ' + message.sendTo + ' on ' + datetime + '</span>';


        } else if (message.inout == 'out') {
            if (message.quoted_message_id) {
                if (parentMedia != '') {
                    parentMedia = '<div style="max-width: 100%; margin-bottom: 10px;"><div class="row">' + parentMedia + '</div></div>';
                }
                li = li + '<div data-target="#'+message.quoted_message_id+'" class="chat-reply-div">'+ parentMedia + message.parentMessage + '</div>';
            }

            li += '<div id="'+message.id+'" class="bubble alt"><div class="txt"><p class="name alt"></p><p class="message"  data-message="'+message.message+'">' + media + message.message + '</p></div></div>';
            fromMsg = fromMsg + '<span class="timestamp" style="color:black; text-transform: capitalize;font-size: 14px;">From ' + message.sendBy + ' to ' + message.sendTo + ' on '  + datetime + '</span>';
        } else {
            li += '<div>' + index + '</div>';
        }
        li += '</div>';
        fullHtml = fullHtml + '<td style="width:45%">' + li + '</td>';
        fullHtml = fullHtml + '<td style="width:30%">' + button + '</td>'; 
        fullHtml = fullHtml + '<td style="width:20%">' + fromMsg + '</td></tr>'; 
    });
    fullHtml = fullHtml + '</table></div>';
    return fullHtml;
}

$(document).on('click', '.load-communication-modal', function () {
    var feedback_category_id = null;
    if ($(this).data('feedback_cat_id')) {
        var feedback_category_id = $(this).data('feedback_cat_id');
    }

    var thiss = $(this);
    var object_type = $(this).data('object');
    var object_id = $(this).data('id');
    var load_attached = $(this).data('attached');
    var load_all = $(this).data('all');
        load_type = $(this).data('load-type');
    var is_admin = $(this).data('is_admin');
    var is_hod_crm = $(this).data('is_hod_crm');
    var limit = 1000;
    if(typeof $(this).data('limit') != "undefined") {
        limit = $(this).data('limit');
    }
    thiss.parent().find('.td-full-container').toggleClass('hidden');
	currentChatParams.url = "/chat-messages/" + object_type + "/" + object_id + "/loadMoreMessages";
    currentChatParams.data = {
        limit: limit,
        load_all: load_all,
        load_attached: load_attached,
        load_type: load_type,
        page : 1,
        hasMore : true,
        object_name:object_type,
        object_val:object_id
    }


    $.ajax({
        type: "GET",
        url: "/chat-messages/" + object_type + "/" + object_id + "/loadMoreMessages",
        data: {
            limit: limit,
            load_all: load_all,
            load_attached: load_attached,
            load_type: load_type,
            feedback_category_id: feedback_category_id,
        },
        beforeSend: function () {
            //$(thiss).text('Loading...');
        }
    }).done(function (response) {
        var li = getHtml(response);
        if ($('#chat-list-history').length > 0) {
            $("#chat-list-history").find(".modal-dialog").css({"width":"1000px","max-width":"1000px"});
            $("#chat-list-history").find(".modal-body").css({"background-color":"white"});
            $("#chat-list-history").find(".modal-body").html(li);
            $("#chat-list-history").find('#chat_obj_type').val(object_type);
            $("#chat-list-history").find('#chat_obj_id').val(object_id);
            $("#chat-list-history").modal("show");
        } else {
            $("#chat-list-history").find(".modal-dialog").css({"width":"1000px","max-width":"1000px"});
            $("#chat-list-history").find(".modal-body").css({"background-color":"white"});
            $("#chat-list-history").find('#chat_obj_type').val(object_type);
            $("#chat-list-history").find('#chat_obj_id').val(object_id);
            $("#chat-history").html(li);
        }

        var searchterm = $('.search_chat_pop').val();
        if(searchterm && searchterm != '') {
            var value = searchterm.toLowerCase();
            $(".filter-message").each(function () {
                if ($(this).text().search(new RegExp(value, "i")) < 0) {
                    $(this).hide();
                } else {
                    $(this).show()
                }
            });
        }

    }).fail(function (response) {
        //$(thiss).text('Load More');

        alert('Could not load messages');

    });
});
//for download chat messages
$(document).on('click', '.downloadChatMessages', function () {
    var thiss = $(this);
    var object_type = $(this).parentsUntil('#chat-list-history').find('#chat_obj_type').val();
    var object_id = $(this).parentsUntil('#chat-list-history').find('#chat_obj_id').val();
    var limit = 10000;
    thiss.parent().find('.td-full-container').toggleClass('hidden');
    $.ajax({
        type: "GET",
        url: "/chat-messages/" + object_type + "/" + object_id + "/loadMoreMessages",
        data: {
            limit: limit,
            downloadMessages:'1',
            object:object_type,
            object_id:object_id,
        },
        beforeSend: function () {
            //$(thiss).text('Loading...');
        }
    }).done(function (response) {
        $('#chatHiddenForm').remove();
        if(response.downloadUrl){
        var form = $("<form/>", 
                 { action:"/chat-messages/downloadChatMessages",
                    method:"POST",
                    target:'_blank',
                    id:"chatHiddenForm",
                     }
            );
        form.append( 
            $("<input>", 
                { type:'hidden',  
                name:'filename', 
                value:response.downloadUrl }
            )
        );
        form.append( 
            $("<input>", 
                { type:'hidden',  
                name:'_token', 
                value:$('meta[name="csrf-token"]').attr('content') }
            )
        );
        $("body").append(form);
        $('#chatHiddenForm').submit();
     }else{
         console.log('no message found !')
     }
    }).fail(function (response) {

        alert('Could not load messages');

    });
});
$(document).on('click', '.btn-approve', function (e) {
   var element = this;
   if (!$(element).attr('disabled')) {
     $.ajax({
       type: "POST",
       url: "/whatsapp/approve/customer",
       data: {
         _token: $('meta[name="csrf-token"]').attr('content'),
         messageId: $(this).data('messageid')
       },
       beforeSend: function() {
         $(element).attr('disabled', true);
         $(element).text('Approving...');
       }
     }).done(function( data ) {
       element.remove();
       console.log(data);
     }).fail(function(response) {
       $(element).attr('disabled', false);
       $(element).text('Approve');

       console.log(response);
       alert(response.responseJSON.message);
     });
   }
});


// $(document).on('click', '.chat-reply-div', function (e) {
//     e.preventDefault();
//     var target = $(this).attr('data-target');
//     $('html, body').animate({
//       scrollTop: ($(target).offset().top)
//     }, 2000);
    
//  });

function getImageToDisplay(imageUrl) {
    // Trim imageUrl
    imageUrl = imageUrl.trim();

    // Set empty imgSrc
    var imgSrc = '';

    // Set image type
    var imageType = imageUrl.substr(imageUrl.length - 4).toLowerCase();

    // Set correct icon/image
    if (imageType == '.jpg' || imageType == 'jpeg') {
        imgSrc = imageUrl;
    } else if (imageType == '.png') {
        imgSrc = imageUrl;
    } else if (imageType == '.gif') {
        imgSrc = imageUrl;
    } else if (imageType == 'docx' || imageType == '.doc') {
        imgSrc = '/images/icon-word.svg';
    } else if (imageType == '.xlsx' || imageType == '.xls' || imageType == '.csv') {
        imgSrc = '/images/icon-excel.svg';
    } else if (imageType == '.pdf') {
        imgSrc = '/images/icon-pdf.svg';
    } else if (imageType == '.zip' || imageType == '.tgz' || imageType == 'r.gz') {
        imgSrc = '/images/icon-zip.svg';
    } else {
        imgSrc = '/images/icon-file-unknown.svg';
    }

    // Return imgSrc
    return imgSrc;
}

$(document).on('click', '.complete-call', function (e) {
    e.preventDefault();

    var thiss = $(this);
    var token = $('meta[name="csrf-token"]').attr('content');
    var url = route.instruction_complete;
    var id = $(this).data('id');
    var assigned_from = $(this).data('assignedfrom');
    var current_user = current_user;

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            _token: token,
            id: id
        },
        beforeSend: function () {
            $(thiss).text('Loading');
        }
    }).done(function (response) {
        // $(thiss).parent().html(moment(response.time).format('DD-MM HH:mm'));
        $(thiss).parent().html('Completed');
        location.reload();
    }).fail(function (errObj) {
        console.log(errObj);
        alert("Could not mark as completed");
    });
});

$(document).on('click', '.pending-call', function (e) {
    e.preventDefault();

    var thiss = $(this);
    var url = route.instruction_pending;
    var id = $(this).data('id');

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            _token: token,
            id: id
        },
        beforeSend: function () {
            $(thiss).text('Loading');
        }
    }).done(function (response) {
        $(thiss).parent().html('Pending');
        $(thiss).remove();
        location.reload();
    }).fail(function (errObj) {
        console.log(errObj);
        alert("Could not mark as completed");
    });
});

$(document).on('click', '.create-product-lead', function (e) {
    e.preventDefault();

    createLead (this,{auto_approve: 1});
});

$(document).on('click','.delete-message',function(e) {
    var $this = $(this);
    $.ajax({
        type: 'GET',
        url: "/message/delete",
        data: {id:$(this).data("id")}
    }).done(response => {
        $this.closest(".bubble").remove();
    }).fail(function(response) {

    });
})

$('#addRemarkButton').on('click', function() {
    var id = $('#add-remark input[name="id"]').val();
    var remark = $('#add-remark textarea[name="remark"]').val();

    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: route.task_add_remark,
        data: {
        id:id,
            remark:remark,
            module_type: 'instruction'
    },
}).done(response => {
        alert('Remark Added Success!')
        window.location.reload();
    }).fail(function(response) {
        console.log(response);
    });
});

$(".view-remark").click(function () {
    var id = $(this).attr('data-id');

    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: route.task_get_remark,
        data: {
        id:id,
            module_type: "instruction"
    },
}).done(response => {
        var html='';

        $.each(response, function( index, value ) {
            html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
            html+"<hr>";
        });
        $("#viewRemarkModal").find('#remark-list').html(html);
    });
});

var token = $('meta[name="csrf-token"]').attr('content');

function createLead (thiss,dataSending) {
    var selected_product_images = [];
    var product_id = $(thiss).data('id');

    if (product_id > 0) {
        selected_product_images.push(product_id);
    }

    var customer_id = $(thiss).data('customer-id');

    if (!customer_id) {
        alert('customer not found');
        return false;
    }

    var text = $(thiss).text();
    var html = $(thiss).html();

    if (selected_product_images.length > 0) {
        if ($('#add_lead').length > 0 && $(thiss).hasClass('create-product-lead')) {
            $.ajax({
                url: "/lead-auto-fill-info",
                data:{
                    product_id : product_id,
                    customer_id : customer_id
                }
            }).done(function(response) {
                $('#add_lead').modal('show');
                $('#add_lead').find('input[name="customer_id"]').val(customer_id);
                $('#add_lead').find('input[name="rating"]').val(1);
                $('#add_lead').find('select[name="brand_id"]').val(response.brand).trigger('change');;
                $('#add_lead').find('select[name="category_id"]').val(response.category).trigger('change');
                $('#add_lead').find('select[name="brand_segment[]"]').val(response.brand_segment).trigger('change');
                $('#add_lead').find('select[name="lead_status_id"]').val('3').trigger('change');
                $('#add_lead').find('select[name="gender"]').val(response.gender).trigger('change');
                $('#add_lead').find('input[name="size"]').val(response.shoe_size);

                var showImageHtml = "";
                if(typeof response.price != "undefined" && response.price > 0) {
                    showImageHtml += '<div class="col-sm-12" style="padding-bottom: 10px;"><span>Price : '+response.price+'</span></div>';
                }

                for (var i in response.media) {
                    showImageHtml += '<div class="col-sm-3" style="padding-bottom: 10px;">';
                    showImageHtml += '<div style="height:100px;"> <img src="'+response.media[i].url+'" width="100%" height="100%"></div>';
                    showImageHtml += '<label class="btn btn-primary"> <input type="checkbox" name="product_media_list[]" value="'+response.media[i].id+'" checked> Select </label>';
                    showImageHtml += '</div>';
                }

                $('#add_lead').find('.show-product-image').html(showImageHtml);

                if (!dataSending) {
                    dataSending = {};
                }

                if ($('#add_lead').find('input[name="product_id"]').length > 0) {
                    $('#add_lead').find('input[name="product_id"]').val(product_id);
                    $('#add_lead').find('input[name="product_id"]').data('object', JSON.stringify(dataSending));
                } else {
                    $('#add_lead').find('form').append($('<input>', {
                        value: product_id,
                        name: 'product_id',
                        type: 'hidden',
                        'data-object' : JSON.stringify(dataSending),
                    }));
                }
            })
            return false;
        }

        var created_at = moment().format('YYYY-MM-DD HH:mm');
        $.ajax({
            type: 'POST',
            url: "/leads",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id,
                rating: 1,
                status: 3,
                assigned_user: 6,
                selected_product: selected_product_images,
                type: "product-lead",
                created_at: created_at
            },
            beforeSend: function() {
                $(thiss).text('Creating...');
            },
            success: function(response) {
                $.ajax({
                    type: "POST",
                    url: "/leads/sendPrices",
                    data: $.extend({
                        _token:  $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id,
                        lead_id: response.lead.id,
                        selected_product: selected_product_images
                    },dataSending)
                }).done(function() {
                    if (text != '') {
                        $(thiss).text(text);
                    } else {
                        $(thiss).html(html);
                    }
                }).fail(function(response) {
                    if (text != '') {
                        $(thiss).text(text);
                    } else {
                        $(thiss).html(html);
                    }
                    alert('Could not send product dimension to customer!');
                });
            }
        }).fail(function(error) {
            if (text != '') {
                $(thiss).text(text);
            } else {
                $(thiss).html(html);
            }
            alert('There was an error creating a lead');
        });
    } else {
        if (text != '') {
            $(thiss).text(text);
        } else {
            $(thiss).html(html);
        }
        alert('Please select at least 1 product first');
    }
}

var observeModelOpen = function () {
    if($("#chat-list-history").is(":visible")) {
        $("body").addClass("modal-open");
    }
};
var clipboradMsg = [];
var clipboardFinalMsg = '';
$(document).on('click','.click-to-clipboard', function () {
    clipboardFinalMsg = '';
    $("#click-to-clipboard-message").val('');
    if ($(this).prop("checked") == true) { 
        clipboradMsg.push({
            id: $(this).data('id'),
            message:  $(this).data('message')
        });
      } else { 
        for (var i = 0; i < clipboradMsg.length; i++) {
            if (clipboradMsg[i].id && clipboradMsg[i].id === $(this).data('id')) { 
                clipboradMsg.splice(i, 1);
                break;
            }
        } 
      }
      for (var i = 0; i < clipboradMsg.length; i++) {
        clipboardFinalMsg = clipboardFinalMsg + clipboradMsg[i].message;
    }
    $("#click-to-clipboard-message").val(clipboardFinalMsg);
    var copyText = document.getElementById("click-to-clipboard-message");
    copyText.select();
    document.execCommand("copy");
});

$(document).on('hidden.bs.modal','#add_lead', function () {
    observeModelOpen();
});

$(document).on('hidden.bs.modal','#preview-image-model', function () {
    observeModelOpen();
});

$(document).on('hidden.bs.modal','#add_order', function () {
    observeModelOpen();
});

$(document).on('hidden.bs.modal','#forwardModal', function () {
    observeModelOpen();
});



$(document).on('click', '.create-product-lead-dimension', function(e) {
        e.preventDefault();
        createLead (this,{dimension: true , auto_approve: 1});
});

$(document).on('click', '.create-detail_image', function(e) {
    e.preventDefault();
    createLead (this,{detailed: true , auto_approve: 1});
});

$(document).on('click', '.resend-message-js', function(e) {
    e.preventDefault();
     let messageId = $(this).attr('data-id');
      $.ajax({
          url: "/message/resend",
          data: {
                message_id: messageId
          },
          success: function() {
                toastr['success']('Message resent successfully!')
          }
      });
});

$(document).on('click', '.resend-message', function () {
    var id = $(this).data('id');
    var thiss = $(this);
var text = $(thiss).text();
var html = $(thiss).html();
    $.ajax({
        type: "POST",
        url: "/whatsapp/" + id + "/resendMessage",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        beforeSend: function () {
            $(thiss).text('Sending...');
        }
    }).done(function () {
        $(thiss).remove();
    }).fail(function (response) {
        if (text != '') {
            $(thiss).text('Resend');
        } else {
            $(thiss).html(html);
        }
        
        console.log(response);
        alert('Could not resend message');
    });
});

$(document).on('click', '.review-btn', function () {
    var id = $(this).data('id');
    var thiss = $(this);
    var text = $(thiss).text();
    var html = $(thiss).html();
    $.ajax({
        type: "POST",
        url: "/chat-messages/" + id + "/set-reviewed",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id:id
        },
        beforeSend: function () {
            $(thiss).text('Reviewing...');
        }
    }).done(function (response) {
        toastr["success"](response.message);
        $(thiss).hide();
    }).fail(function (response) {
        toastr["error"](response.message);
        $(thiss).text('');
    });
});

$(document).on('click', '.create-product-order', function(e) {
    e.preventDefault();

    var selected_product_images = [];
    var product_id = $(this).data('id');

    if (product_id > 0) {
        selected_product_images.push(product_id);
    }

    var customer_id = $(this).data('customer-id');

    if (!customer_id) {
        alert('customer not found');
        return false;
    }

    var text = $(this).text();
    var html = $(this).html();
    var thiss = this;
    if (selected_product_images.length > 0) {
        if ($('#add_order').length > 0) {
            $('#add_order').find('input[name="customer_id"]').val(customer_id);
            $('#add_order').find('input[name="date_of_delivery"]').val('');
            $('#add_order').find('input[name="advance_detail"]').val('');
            $('#add_order').find('input[name="advance_date"]').val('');
            $('#add_order').find('input[name="balance_amount"]').val('');
            $('#add_order').find('input[name="received_by"]').val('');
            $('#add_order').find('input[name="note_if_any"]').val('');
            $('#add_order').find('select[name="payment_mode"]').val('');
            $('#add_order').find('select[name="order_status"]').val('Follow up for advance');

            if ($('#add_order').find('input[name="selected_product[]"]').length > 0) {
                $('#add_order').find('input[name="selected_product[]"]').val(product_id);
            } else {
                $('#add_order').find('.add_order_frm').append($('<input>', {
                    value: product_id,
                    name: 'selected_product[]',
                    type: 'hidden'
                }));
            }

            if ($('#add_order').find('input[name="convert_order"]').length > 0) {
                $('#add_order').find('input[name="convert_order"]').val('convert_order');
            } else {
                $('#add_order').find('.add_order_frm').append($('<input>', {
                    value: 'convert_order',
                    name: 'convert_order',
                    type: 'hidden'
                }));
            }

            $('#add_order').modal('show');
            return false;
        }

      $.ajax({
        type: 'POST',
        url: "/order",
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          customer_id: customer_id,
          order_type: "offline",
          convert_order: 'convert_order',
          selected_product: selected_product_images,
          order_status: "Follow up for advance"
        },
        beforeSend: function() {
          $(thiss).text('Creating...');
        },
        success: function(response) {
          $.ajax({
            type: "POST",
            url: "/order/send/Delivery",
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              customer_id: customer_id,
              order_id: response.order.id,
              selected_product: selected_product_images
            }
          }).done(function() {
                    if (text != '') {
                        $(thiss).text(text);
                    } else {
                        $(thiss).html(html);
                    }
          }).fail(function(response) {
                    if (text != '') {
                        $(thiss).text(text);
                    } else {
                        $(thiss).html(html);
                    }
            alert('Could not send delivery message to customer!');
          });
        }
      }).fail(function(error) {
            if (text != '') {
                $(thiss).text(text);
            } else {
                $(thiss).html(html);
            }
        alert('There was an error creating a order');
      });
    } else {
        if (text != '') {
            $(thiss).text(text);
        } else {
            $(thiss).html(html);
        }
         alert('Please select at least 1 product first');
    }
});

$(document).on('click', '.forward-btn', function() {
var id = $(this).data('id');
$('#forward_message_id').val(id);
});

$(document).on("keyup", '.search_chat_pop', function() {
    var value = $(this).val().toLowerCase();
    exampleFunction() ;//your function call
    // $(".speech-wrapper .bubble").filter(function() {
    //     $(this).toggle($(this).find('.message').data('message').toLowerCase().indexOf(value) > -1)
    // });
});

$(".search_chat_pop_time").datetimepicker({
    format: 'YYYY-MM-DD',
    useCurrent: false
}).on('dp.change', function (ev) {
    currentChatParams.data.page =1;
    exampleFunction() ;//your function call
});

/*$('body').on('focus',".search_chat_pop_time", function(){
    if($(this).data("DateTimePicker") == null){
        
       // datepicker initialized
    }
});*/
function exampleFunction(){
    var date = $('.search_chat_pop_time').val();
    var keyword = $('.search_chat_pop').val();
    /*$(".filter-message").each(function () {
        if ($(this).text().search(new RegExp(value, "i")) < 0) {
            $(this).hide();
        } else {
            $(this).show()
        }
    });*/
    currentChatParams.data.date = date;
    currentChatParams.data.keyword = keyword;
    //console.log(currentChatParams.data);
    $.ajax({
        type: "GET",
        url: currentChatParams.url,
        data: currentChatParams.data
    }).done(function (response) {
        var li = getHtml(response);
        if ($('#chat-list-history').length > 0) {
            $("#chat-list-history").find(".modal-dialog").css({"width":"1000px","max-width":"1000px"});
            $("#chat-list-history").find(".modal-body").css({"background-color":"white"});
            $("#chat-list-history").find(".modal-body").html(li);
            $("#chat-list-history").find('#chat_obj_type').val(currentChatParams.object_name);
            $("#chat-list-history").find('#chat_obj_id').val(currentChatParams.object_val);
            $("#chat-list-history").modal("show");
        } else {
            $("#chat-list-history").find(".modal-dialog").css({"width":"1000px","max-width":"1000px"});
            $("#chat-list-history").find(".modal-body").css({"background-color":"white"});
            $("#chat-list-history").find('#chat_obj_type').val(currentChatParams.object_name);
            $("#chat-list-history").find('#chat_obj_id').val(currentChatParams.object_val);
            $("#chat-history").html(li);
        }
    }).fail(function (response) {
        alert('Could not load messages');
    });
}

$(document).on("click", '.show-product-info', function() {
    if ($('#show_product_info_model').length == 0) {
        var show_product_info_model =   '<div id="show_product_info_model" class="modal fade" role="dialog">'+
                                    '      <div class="modal-dialog modal-lg">'+
                                    '        <div class="modal-content">'+
                                    '            <div class="modal-body">'+
                                    '                <div class="embed-responsive embed-responsive-16by9 z-depth-1-half product_page">'+
                                    '                </div>'+
                                    '            </div>'+
                                    '            <div class="modal-footer">'+
                                    '              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
                                    '            </div>'+
                                    '        </div>'+
                                    '      </div>'+
                                    '    </div>';
        $('body').append(show_product_info_model);
    }

    $('#show_product_info_model').find('.product_page').html('<iframe class="embed-responsive-item" src="/products/'+$(this).data('id')+'"></iframe>');
    $('#show_product_info_model').modal('show');

});


$(document).on("mouseover", '.show-thumbnail-image', function() {
    if ($('#preview-image-model').length == 0) {
        var $hasPdf = $(this).hasClass("has-pdf");

        if($hasPdf) {
            var pdfSrc = $(this).attr("href");
            var preview_image_model =   '<div id="preview-image-model" class="modal col-6" data-backdrop="false">'+
                                    '  <span class="close">×</span>'+
                                    '  <div class="row">'+
                                    '    <div class="col-12"><embed src="'+pdfSrc+'" height="500px;" id="img01"></embed></div>'+
                                    '  </div>'+
                                    '</div>';
            $('body').append(preview_image_model);
            $('#preview-image-model').modal('show');

        }else{

            var preview_image_model =   '<div id="preview-image-model" class="modal col-6" data-backdrop="false">'+
                                    '  <span class="close">×</span>'+
                                    '  <div class="row">'+
                                    '    <div class="col-12"><img class="modal-content" height="500px;" id="img01"></div>'+
                                    '  </div>'+
                                    '</div>';

            $('body').append(preview_image_model);
            $('#preview-image-model').find(".modal-content").attr("src",$(this).find("img").attr("src"));
            $('#preview-image-model').modal('show');
        }
    }

});

$(document).on('mouseout', '.show-thumbnail-image', function(e) {
    $('#preview-image-model').modal('hide');
    $('#preview-image-model').remove();
});

$('#chat-list-history').on("scroll", function() {

    var $this = $(this);

    var modal_scrollTop = $this.scrollTop();
    var modal_scrollHeight = $this.find('.modal-body').prop('scrollHeight');


    // Bottom reached:
    //console.log([modal_scrollTop,(modal_scrollHeight - 500), workingOn , currentChatParams.data.hasMore]);
    if (modal_scrollTop > (modal_scrollHeight - 1000) && workingOn == null) {
        if(currentChatParams.data.hasMore && workingOn == null) {
            workingOn = true;
            currentChatParams.data.page++;
            getMoreChatConvo(currentChatParams);
        }
    }

});

$(document).on("click",".create-kyc-customer",function(e) {
    e.preventDefault();
    var customerId = $(this).data("customer-id");
    var mediaId    = $(this).data("media-key");
    var thiss = $(this);
    var text = $(thiss).text();
    var html = $(thiss).html();
    console.log(text);
    console.log(html);

    $.ajax({
        type: 'POST',
        url: "/customer/create-kyc",
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          customer_id: customerId,
          media_id: mediaId
        },
        beforeSend: function() {
          $(thiss).text('Creating...');
        },
        success: function(response) {
            toastr["success"]("File added into kyc", 'Message');
            if (text != '') {
                $(thiss).text("+ KYC");
            } else {
                $(thiss).html(html);
            }
        }
      }).fail(function(error) {
        if (text != '') {
            $(thiss).text("+ KYC");
        } else {
            $(thiss).html(html);
        }
        toastr["error"]("There was an error creating KYC", 'Message');
      });
});
