<!-- Modal -->
<style>
    #quick-chatbox-window-modal .card_chat{
        border-radius: 8px !important;
    }
    #quick-chatbox-window-modal .contacts_body{
        padding:0 !important;
    }
    #quick-chatbox-window-modal .contacts li {
        margin: 0 !important;
        padding:10px !important;
    }
    #quick-chatbox-window-modal .card-footer{
        border-radius: 0 !important;
        /*background: transparent !important;*/
    }
    #quick-chatbox-window-modal .chat li:last-child{
        border-bottom:none !important;
    }
    #quick-chatbox-window-modal .chat-righbox{
        padding: 11px 17px 4px;
        margin-bottom: 10px;
    }
    #quick-chatbox-window-modal .chat-righbox .title {
        font-size: 17px;
        font-weight: 400;
    }
    #quick-chatbox-window-modal h5{
        margin-top:0 !important;
    }
    #customer_order_details{
        padding: 10px 0 !important;
    }
    #quick-chatbox-window-modal .card {
        margin-bottom: 1rem;
    }
    #quick-chatbox-window-modal .card-header {
        padding: 0.5rem 1.25rem;
    }
    .msg_time {
        font-size: 9px;
        color: #757575;
    }
    .chat-rightbox.mt-4{
        display: flex;
    }
    .chat-rightbox.mt-4 button{
        margin-left:10px;
    }
    #quick-chatbox-window-modal .btn-link{
        color: gray;
    }
    .remove-bottom-scroll .user_inital{
        height: 35px;
        width: 35px;
        line-height: 35px;
        margin-top: 4px;
    }
    .button-round{
        border-radius: 50% !important;
        background-color: rgba(0,0,0,0.3) !important;
        border: 0 !important;
        color: white !important;
        cursor: pointer;
        width: 25px;
        height: 25px;
        margin-bottom:5px;
        margin-left: 7px;
    }
    #quick-chatbox-window-modal .button-round i{
        font-size: 15px;
    }
    #quick-chatbox-window-modal .card-footer{
        padding:8px 5px !important;
    }
    .selectedValue{
        flex-grow: 1;
    }
    #autoTranslate{
        width: 220px !important;
        justify-content: flex-end;
        margin: 0 0 0 auto;
    }
    #quick-chatbox-window-modal .card-header.msg_head{
        background: #f1f1f1;
    }
    #quick-chatbox-window-modal .msg_card_body {
        background: #ffffff;
    }
    #quick-chatbox-window-modal .typing-indicator{
        display: none;
    }
    #quick-chatbox-window-modal  .card-header {
        border-radius: 9px 9px 0 0 !important;
    }
    .io.action{
        padding-left: 10px;
        display: flex;
        align-items: center;
    }
    .video_cam{
        margin-left: 0;
    }
    .video_cam i{
        color: #757575;
    }
    .video_cam span{
        margin-right: 10px;
    }
</style>
<div id="quick-chatbox-window-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width:90%; max-width: 90%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 chat pr-0" style="margin-top : 0px !important;">
                        <div class="card_chat mb-sm-3 mb-md-0 contacts_card">
                            <div class="card-header">
                                <h3>Chats</h3>
                                <!-- <div class="input-group">
                                    <input type="text" placeholder="Search..." name="" class="form-control search">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text search_btn"><i class="fa fa-search"></i></span>
                                        </div>
                                </div> -->
                            </div>
                            <div class="card-body contacts_body">
                                @php
                                $chatIds = \App\CustomerLiveChat::with('customer')->orderBy('seen','asc')->orderBy('status','desc')->get();
                                $newMessageCount = \App\CustomerLiveChat::where('seen',0)->count();
                                @endphp
                                <ul class="contacts" id="customer-list-chat">
                                    @foreach ($chatIds as $chatId)
                                    @php
                                    $customer = $chatId->customer;
                                    $customerInital = substr($customer->name, 0, 1);
                                    @endphp
                                        <input type="hidden" id="live_selected_customer_store" value="{{ $customer->store_website_id }}" />
                                        <li onclick="getChats('{{ $customer->id }}')" id="user{{ $customer->id }}" style="cursor: pointer;">

                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <soan class="rounded-circle user_inital">{{ $customerInital }}</soan>
                                                {{-- <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img"> --}}
                                                <span class="online_icon @if($chatId->status == 0) offline @endif "></span>
                                            </div>
                                            <div class="user_info">
                                                <span>{{ $customer->name }}</span>
                                                <p>{{ $customer->name }} is @if($chatId->status == 0) offline @else online @endif </p>
                                            </div>
                                            @if($chatId->seen == 0)<span class="new_message_icon"></span>@endif
                                        </div>
                                    </li>

                                    @endforeach

                                </ul>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <div class="col-md-6 chat">
                        <div class="card_chat">
                            <div class="card-header msg_head" style="display: flex">
                                <div class="d-flex bd-highlight align-items-center " style="flex-grow: 1">
                                    <div class="img_cont">
                                        <soan class="rounded-circle user_inital" id="user_inital"></soan>
                                        {{-- <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img"> --}}

                                    </div>
                                    <div class="user_info" id="user_name">
                                        {{-- <span>Chat with Khalid</span>
                                            <p>1767 Messages</p> --}}
                                    </div>

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
                                </div>
                                <div class="video_cam">
                                    <span><i class="fa fa-video"></i></span>
                                    <span><i class="fa fa-phone"></i></span>
                                </div>
                                <span class="io action" id="action_menu_btn "><i style="font-size: 17px" class="fa fa-ellipsis-v"></i></span>
                                <div class="action_menu">
                                    {{-- <ul>
                                            <li><i class="fa fa-user-circle"></i> View profile</li>
                                            <li><i class="fa fa-users"></i> Add to close friends</li>
                                            <li><i class="fa fa-plus"></i> Add to group</li>
                                            <li><i class="fa fa-ban"></i> Block</li>
                                        </ul> --}}
                                </div>
                            </div>
                            <div class="card-body msg_card_body" id="message-recieve">

                            </div>
                            <div class="typing-indicator" id="typing-indicator"></div>
                            <div class="card-footer">
                                <div class="input-group">
                                    <div class="card-footer">
                                        <div class="input-group">

                                            <input type="hidden" id="message-id" name="message-id" />

                                           <span style="display: flex">
                                                <div style="flex-grow: 1">
                                                <textarea name="" class="form-control type_msg" placeholder="Type your message..." id="message"></textarea>

                                            </div>
                                            <div>
                                            @if(isset($customer))
                                                <!-- <div class="input-group-append">
                                                    <a class="btn btn-image px-1 send-attached-images">
                                                        <img src="/images/attach.png"/>
                                                    </a>
                                                </div> -->
                                                @endif
                                                <div class="input-group-append">
                                                    <span class="input-group-text attach_btn button-round" onclick="sendImage()"><i class="fa fa-paperclip"></i></span>
                                                    <input type="file" id="imgupload" style="display:none" />
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text send_btn button-round" onclick="sendMessage()"><i class="fa fa-location-arrow"></i></span>
                                                </div>
                                            </div>
                                           </span>




                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body " id="customer_order_details">

                        </div>
                    </div>
                    <div class="col-md-3 customer-info pl-0">
                        <div class="chat-righbox">
                            <div class="title">General Info</div>
                            <div id="chatCustomerInfo"></div>

                        </div>
                        <div class="chat-righbox">
                            <div class="title">Visited Pages</div>
                            <div id="chatVisitedPages">
                                
                            </div>
                        </div>
                        <div class="chat-righbox">
                            <div class="title">Additional info</div>
                            <div class="line-spacing" id="chatAdditionalInfo">
                                
                            </div>
                        </div>
                        <div class="chat-righbox">
                            <div class="title">Technology</div>
                            <div class="line-spacing" id="chatTechnology">
                            </div>
                        </div>
                        <div class="chat-rightbox">
                            @php
                            $all_categories = \App\ReplyCategory::all();
                            @endphp
                            <select class="form-control" id="keyword_category">
                                <option value="">Select Category</option>
                                @if(isset($all_categories))
                                    @foreach ($all_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="chat-rightbox mt-4">
                            <input type="text" name="quick_comment_live" placeholder="New Quick Comment" class="form-control quick_comment_live">
                            <button class="btn btn-secondary quick_comment_add_live">+</button>
                        </div>
                        <div class="chat-rightbox mt-4">
                            <select class="form-control" id="live_quick_replies">
                                <option value="">Quick Reply</option>
                            </select>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.livechatinc.com/accounts/accounts-sdk.min.js"></script>
<script>
$(document).on("click",".quick_approve_add_live",function() {
    var messageId = $(this).attr('id');
    var chatId = $(this).closest("div.msg_cotainer").find("input[name='message-id']").val();
    var message_val = $(this).closest("div.msg_cotainer").find("input[name='message-value']").val();
    $.ajax({
        url: "{{ route('livechat.send.message') }}",
        type: 'POST',
        dataType: 'json',
        data: { 
            id : chatId,
            messageId : messageId ,
            message : message_val,
           _token: "{{ csrf_token() }}" 
        }
    }).done(function(data) {
        console.log(data);
    }).fail(function() {
        alert('Chat Not Active');
    });
});

console.log($('#live_chat_key').val());

var accessToken = '';
var websocket = false;
var client_id = $('#live_chat_key').val();
var wsUri = "wss://api.livechatinc.com/v3.1/agent/rtm/ws";
const instance = AccountsSDK.init({
    client_id: client_id,
    response_type: "token",
    onIdentityFetched: (error, data) => {
        if (error){
            console.log('++++ error >>>');
            console.log(error);
        } 
        if (data) {
            //console.log("User authorized!");
            accessToken = data.access_token;
            console.log(accessToken)
            setTimeout(instance, data.expires_in);
            try{
                $.ajax({
                    url: '/livechat/save-token',
                    type: 'POST',
                    dataType: 'json',
                    data: {accessToken: accessToken ,'seconds' : data.expires_in, "_token": "{{ csrf_token() }}"},
                })
                .done(function() {
                    console.log("AccessToken Saved In Session");
                })
                .fail(function() {
                    console.log("Cannot Save AccessToken In Session");
                })
            }catch{

            }   
            
            
            //console.log("License number: " + data.license);
        }
    }
});

function runWebSocket(chatId) {
    websocket = new WebSocket(wsUri);

    websocket.onopen = function(evt) {
        pingSock();
        websocket.send('{ "action": "login", "payload": { "token": "Bearer ' + accessToken + '" }}');
    };

    websocket.onmessage = function(evt) {
        var evtDat = JSON.parse(evt.data);
 
        if(evtDat.action == 'login' && evtDat.type == 'response' && evtDat.success == true){
            websocket.send('{ "action": "send_typing_indicator", "payload": { "chat_id": "' +chatId + '", "is_typing": true }}');
        }
        if(evtDat.action == 'send_typing_indicator' && evtDat.type == 'response' && evtDat.success == true){
            //user is typing.. show status in chat box
            //$('#typing-indicator').html('typing...').delay(1000).html('');
        }
        if(evtDat.action == 'incoming_sneak_peek' && evtDat.type == 'push'){
            //user is typing.. show status in chat box
            $('#typing-indicator').html('typing...');
            setTimeout( function() { $('#typing-indicator').html('') }, 1000);
        }
    };
}

var pingTimerObj = false;
function pingSock() {
    if (!websocket) return;
    if (websocket.readyState !== 1) return;
    websocket.send('{ "action": "ping" }');
    pingTimerObj = setTimeout(pingSock, 15000); //ping every 15 seconds
}

var currentChatId = 0;
var chatTimerObj = false;
function openChatBox(show){
    if(show){
        //open socket
        if(currentChatId != 0){
            runWebSocket(currentChatId);
        }
        getUserList();
        getChatsWithoutRefresh();
    }
    else{
        clearTimeout(chatTimerObj);
        clearTimeout(pingTimerObj);
        // Close the connection, if open.
        if (websocket.readyState === WebSocket.OPEN) {
            websocket.close();
        }
    }
}

$(window).on("blur focus", function(e) {
    var prevType = $(this).data("prevType");
    if (prevType != e.type) {
        switch (e.type) {
            case "blur":
                if(chatBoxOpen){
                    openChatBox(false);
                }
                break;
            case "focus":
                if(chatBoxOpen){
                    openChatBox(true);
                }
                break;
        }
    }

    $(this).data("prevType", e.type);
})

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function customerInfoSetter(customerInfo){
    var customerLocation = [];
    var lastVisited = customerInfo.last_visit;
    if(lastVisited.geolocation.city != ''){
        customerLocation.push(lastVisited.geolocation.city);
    }
    if(lastVisited.geolocation.region != ''){
        customerLocation.push(lastVisited.geolocation.region);
    }
    if(lastVisited.geolocation.country != ''){
        customerLocation.push(lastVisited.geolocation.country);
    }
    var customerInfoHTML = '<div class="name"><div class="inital-wrapper"><span class="inital">L</span></div><div class="fname-wrapper"><div class="fullname">' + customerInfo.name + '</div><div class="email">' + customerInfo.email + '</div></div></div>';

    var atSeenAtUTC = new Date(customerInfo.customer_last_event_created_at);

    customerInfoHTML += '<div class="time"><svg fill="#9c999d" width="16px" height="16px" viewBox="0 0 14 14"><g><path fill="inherit" d="M6.993.333A6.663 6.663 0 0 0 .333 7c0 3.68 2.98 6.667 6.66 6.667A6.67 6.67 0 0 0 13.667 7 6.67 6.67 0 0 0 6.993.333zm.007 12A5.332 5.332 0 0 1 1.667 7 5.332 5.332 0 0 1 7 1.667 5.332 5.332 0 0 1 12.333 7 5.332 5.332 0 0 1 7 12.333z"></path><path fill="inherit" d="M7.333 3.667h-1v4l3.5 2.1.5-.82-3-1.78z"></path></g></svg> ' + formatAMPM(atSeenAtUTC) + ' local time</div>';
    customerInfoHTML += '<div class="location"><svg fill="#9c999d" width="16px" height="16px" viewBox="0 0 10 14"><path fill="inherit" d="M5 .333A4.663 4.663 0 0 0 .333 5C.333 8.5 5 13.667 5 13.667S9.667 8.5 9.667 5A4.663 4.663 0 0 0 5 .333zm0 6.334a1.667 1.667 0 1 1 .001-3.335A1.667 1.667 0 0 1 5 6.667z"></path></svg> ' + customerLocation.join(', ') + '</div>';
    customerInfoHTML += '<div class="mapouter"><div class="gmap_canvas" id="gmap"><iframe width="100%" height="250" id="gmap_canvas" src="https://maps.google.com/maps?q=' + customerLocation.join(', ') + '&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div>';

    $('#chatCustomerInfo').html(customerInfoHTML);

    var lastVisitedPages = lastVisited.last_pages;
    var lastVisitedPageHTML = '';
    if(lastVisitedPages.length > 0){
        $.each(lastVisitedPages, function(k,v){
            lastVisitedPageHTML += '<li>' + v.title + '<br><a href="' + v.url + '" target="_blank" style="color: #337ab7">' + v.url + '</a></li>';
        });
        lastVisitedPageHTML = '<ul>' + lastVisitedPageHTML + '</ul>';
    }
    $('#chatVisitedPages').html(lastVisitedPageHTML);
    $('#chatAdditionalInfo').html('Visits: ' + customerInfo.statistics.visits_count + '<br>Chats: ' + customerInfo.statistics.threads_count);
    $('#chatTechnology').html('IP address: ' + lastVisited.ip + '<br>User agent: ' + lastVisited.user_agent);
}

function getCustomerInfoOnLoad(){
    $('#chatCustomerInfo').html('Fetcing Details...');
    $('#chatVisitedPages').html('Fetcing Details...');
    $('#chatAdditionalInfo').html('Fetcing Details...');
    $('#chatTechnology').html('Fetcing Details...');
    $.ajax({
        url: "{{ route('livechat.customer.info') }}",
        type: 'GET',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}" },
    })
    .done(function(data) {
        var customerInfo = data.customerInfo;
        if(customerInfo!=''){
            customerInfoSetter(customerInfo);
        }
        else{
            $('#chatCustomerInfo').html('');
            $('#chatVisitedPages').html('');
            $('#chatAdditionalInfo').html('');
            $('#chatTechnology').html('');
        }

        if(data.threadId != ''){
            currentChatId = data.threadId;
        }
    })
    .fail(function() {
        $('#chatCustomerInfo').html('');
        $('#chatVisitedPages').html('');
        $('#chatAdditionalInfo').html('');
        $('#chatTechnology').html('');
    });
}

getCustomerInfoOnLoad();

function getChats(id){
$('#quick-chatbox-window-modal #message-recieve').html('');
$('#quick-chatbox-window-modal #message-recieve').empty().html("");
    // Close the connection, if open.
    if (websocket.readyState === WebSocket.OPEN) {
        clearInterval(pingTimerObj);
        websocket.close();
    }

    $('#chatCustomerInfo').html('Fetcing Details...');
    $('#chatVisitedPages').html('Fetcing Details...');
    $('#chatAdditionalInfo').html('Fetcing Details...');
    $('#chatTechnology').html('Fetcing Details...');
    $.ajax({
        url: "{{ route('livechat.get.message') }}",
        type: 'POST',
        dataType: 'json',
        data: { id : id ,   _token: "{{ csrf_token() }}" },
    })
    .done(function(data) {
        console.log(data);

        //if(typeof data.data.message != "undefined" && data.length > 0 && data.data.length > 0) {
            $('#quick-chatbox-window-modal #message-recieve').empty().html(data.data.message);
            $('#quick-chatbox-window-modal #message-id').val(data.data.id);
            $('#quick-chatbox-window-modal #new_message_count').text(data.data.count);
            $('#quick-chatbox-window-modal #user_name').text(data.data.name);
            $("#quick-chatbox-window-modal li.active").removeClass("active");
            $("#quick-chatbox-window-modal #user"+data.data.id).addClass("active");
            $('#quick-chatbox-window-modal #user_inital').text(data.data.customerInital);

            var customerInfo = data.data.customerInfo;
            if(customerInfo!=''){
                customerInfoSetter(customerInfo);
            }
            else{
                $('#chatCustomerInfo').html('');
                $('#chatVisitedPages').html('');
                $('#chatAdditionalInfo').html('');
                $('#chatTechnology').html('');
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

function getChatsWithoutRefresh(){
    var scrolled=0;
    var lastMsgId = $("#message-recieve").find(".d-flex").last().data("chat-id");
    $.ajax({
        url: "{{ route('livechat.message.withoutrefresh') }}",
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}", last_msg_id : lastMsgId },
    })
    .done(function(data) {
         if(typeof lastMsgId != undefined) {
            $('#quick-chatbox-window-modal #message-recieve').empty().html(data.data.message);
            $("#quick-chatbox-window-modal #message-recieve").addClass("remove-bottom-scroll");
         }else{
            console.log('asf');
            $('#quick-chatbox-window-modal #message-recieve').append(data.data.message);
            $("#quick-chatbox-window-modal #message-recieve").addClass("remove-bottom-scroll");
            var objDiv = document.getElementById("message-recieve");
                objDiv.scrollTop = objDiv.scrollHeight;
         }
         $('#message-id').val(data.data.id);
         getLanguage(data.data.id);
         $('#new_message_count').text(data.data.count);
         $('#user_name').text(data.data.name);
         $("li .active").removeClass("active");
         $("#user"+data.data.id).addClass("active");
         $('#user_inital').text(data.data.customerInital);
         scrolled=scrolled+300;
         $(".cover").animate({
            scrollTop:  scrolled
         });
         chatTimerObj = setTimeout(function(){
            getUserList();
            getChatsWithoutRefresh();
        }, 3000);
    })
    .fail(function() {
        console.log("error");
        chatTimerObj = setTimeout(function(){
            getUserList();
            getChatsWithoutRefresh();
        }, 3000);
    });
}

function getLanguage(customerId) {
    $.ajax({
        url: "{{ route('livechat.customer.language') }}",
        type: 'GET',
        dataType: 'json',
        data: { id:customerId, _token: "{{ csrf_token() }}" },
    })
    .done(function(res) {
        if(res.data.language) {
            $('.selectedValue option[value="' + res.data.language + '"]').prop('selected', true);
        } else {
            $('.selectedValue option[value=""]').prop('selected', true);
        }
    })
}

function getUserList(){
    $.ajax({
        url: "{{ route('livechat.get.userlist') }}",
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}" },
    })
    .done(function(data) {
         $('#customer-list-chat').empty().html(data.data.message);
         $('#new_message').text(data.data.count);
    })
    .fail(function() {
        console.log("error");
    });

}

function sendMessage(){
    console.log("REst");
    id = $('#message-id').val();
    message = $('#message').val();
    var scrolled=0;
    $.ajax({
        url: "{{ route('livechat.send.message') }}",
        type: 'POST',
        dataType: 'json',
        data: { id : id ,
            message : message,
           _token: "{{ csrf_token() }}" 
           },
    })
    .done(function(data) {
        //chat_message = '<div class="d-flex justify-content-end mb-4"><div class="msg_cotainer">'+message+'<span class="msg_time"></span></div></div>'; //<div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
        //$('#message-recieve').append(chat_message);
        $('#message').val('');
        scrolled=scrolled+300;
        $(".cover").animate({
            scrollTop:  scrolled
        });
    })
    .fail(function() {
        alert('Chat Not Active');
    });
}

function sendImage() {
    $('#imgupload').trigger('click');
}

$(document).ready(function() {
    $('#imgupload').on('change', function(){
        id = $('#message-id').val();
        file = $('#imgupload').prop('files')[0];
        var fd = new FormData();
            fd.append("id", id);
            fd.append("file", file);
            fd.append("_token", "{{ csrf_token() }}" );
        var scrolled=0;
        $.ajax({
            url: "{{ route('livechat.upload.file') }}",
            type: 'POST',
            dataType: 'json',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                //alert(data.fileCDNPath);
                // chat_message = '<div class="d-flex justify-content-end mb-4"><div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div><div class="msg_cotainer">'+data.filename+' uploaded<br><a href="'+data.fileCDNPath+'" target="_blank">'+data.fileCDNPath+'</a><span class="msg_time"></span></div></div>';
                chat_message = '<div class="msg_cotainer_send"><img src="'+data.fileCDNPath+'" class="rounded-circle user_img_msg"></div>';
                $('#message-recieve').append(chat_message);
                $('#message').val('');
                scrolled=scrolled+300;
                $(".cover").animate({
                    scrollTop:  scrolled
                });             
            }
        });
    });
});


function checkChatCount(){
    $.ajax({
        url: "{{ route('livechat.new.chat') }}",
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}" },
    })
    .done(function(data) {
        $('#new_message').attr('data-count', data.data.count);
    })
    .fail(function() {
        console.log("error");
    });

}

/*$( document ).ready(function() {
    setInterval(function(){
        checkChatCount();
    }, 5000);
});*/

checkChatCount();

$(document).on("click",".send-attached-images",function() {
    var messageId = $('#message-id').val();
    location.href = "/attachImages/live-chat/"+messageId;
}); -->

</script>
