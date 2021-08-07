// OOP Way
fbChat = {
  bootChat: function() {
    var chatArea = $('#chatMsg'),
            that = this;

    // Load the messages every 5 seconds
  //  setInterval(this.getMessages, 5000);
   // setInterval(this.checknewmessages, 20000);
    checknewmessages();
    // Bind the keyboard event
    chatArea.on('keydown', function(event) {

      if (event.keyCode === 13 && event.shiftKey === false) {
        var message = chatArea.val();
        var userid = $('#sendid').val();
        var _token = $("input[name='_token']").val();
    
        if (message.length !== 0) {
          that.sendMessage(message,userid,_token);
          event.preventDefault();
        } else {
          alert('Provide a message to send!');
        }
      }
    });
  },
  sendMessage: function(message,userid) {
    var that = this;
    $.ajax({
        url: '/chat',
      method: 'post',
      data: {msg: message,userid: userid},
      success: function(data) {
        $('#chatMsg').val('');
        that.getMessages();
      }
    });
  },
  getMessages: function() {
    $.ajax({
      url: '/chat',
      method: 'GET',
      success: function(data) {
        $('.msg-wgt-body').html(data);
      }
    });
  }
};

// Initialize the chat
//fbChat.bootChat();

// Procedural way
/**
 * Add a new chat message
 * 
 * @param {string} message
 */
function send_message(message,userid,_token) {
  $.ajax({
     url: '/chat',
    method: 'post',
    data: {messages: message,userid: userid,_token: _token},
    success: function(data) {
      $('#chatMsg').val('');
      get_messages();
    }
  });
}

/**
 * Get's the chat messages.
 */
/*function get_messages() {
  $.ajax({
    url: '/chat',
    method: 'GET',
    success: function(data) {
      $('.msg-wgt-body').html(data);
    }
  });
}
*/
// Get one to one messages
function get_messages() {
  useriddest =localStorage.getItem("chatusrid");
  $.ajax({
    url: '/chat',
    method: 'GET',
    data:{sourceid:window.userid,userid: useriddest},
    success: function(data) {
      $('.msg-wgt-body').html(data);
    }
  });
}



function update_lastcheck() {
 
  $.ajax({
    url: '/chat/updatenew',
    method: 'GET',   
    success: function(data) {
      // the user last checked updated.
    }
  });
}

/**
 * Initializes the chat application
 */
function boot_chat() {
  var chatArea = $('#chatMsg');
  var selectuser = $('#sendid .li');

  // Load the messages every 5 seconds
  //setInterval(get_messages, 5000);
  //setInterval(checknewmessages, 20000);
  checknewmessages();
  // Binding the change event
  $('#sendid').on('click','li',function(event)
  {
  	
  	userid = $(this).attr("data-id")  
  	localStorage.setItem("chatusrid", userid);
  	 $("#sendid li").each(function () {
            $(this).removeClass("activeuser");
        });
  	$(this).addClass("activeuser");
  	get_messages();
    if ($(".msg-wgt-header").hasClass("blink")) {
      $(".msg-wgt-header").removeClass("blink");
     }
       update_lastcheck();
       checknewmessages();
  });

  // Bind the keyboard event
  chatArea.on('keydown', function(event) {
    // Check if enter is pressed without pressing the shiftKey
   
    if (event.keyCode === 13 && event.shiftKey === false) {
      var message = chatArea.val();
      // Check if the message is not empty
       var userid = localStorage.getItem("chatusrid", userid);
       //alert(userid);
       var _token = $("input[name='_token']").val();
     
      if (message.length !== 0) {

        send_message(message , userid, _token);
        event.preventDefault();
      } else {
        alert('Provide a message to send!');
        chatArea.val('');
      }
    }
  });
}

// Initialize the chat
$(document).ready(function(){
boot_chat();
  $(".chat-container").hide();
$(".chat-toggle").click(function(){
    $(".chat-container").toggle();
    if ($(".msg-wgt-header").hasClass("blink")) {
      $(".msg-wgt-header").removeClass("blink");
     }
});
});