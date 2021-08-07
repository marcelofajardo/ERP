var channel = pusher.subscribe('solo-chat-channel');
        channel.bind('chat', function(data) {
         // alert(JSON.stringify(data));
          // we receive the message
              useriddest =localStorage.getItem("chatusrid");
              if(data.userid == loggedinuser)
              {
				  $.ajax({
				    url: '/chat',
				    method: 'GET',
				    data:{sourceid:window.userid,userid: useriddest},
				    success: function(data) {
				      $('.msg-wgt-body').html(data);
				    }
				  });
				  checknewmessages();
			   }
        });

function checknewmessages()
{

	 	userid = $(this).attr("data-id");

        $.ajax({
          url: '/chat/getnew',
          method: 'GET',
          data:{userid: userid},
          context: this,
          success: function(data) {
           // $('.msg-wgt-body').html(data);
           var obj = jQuery.parseJSON( data );
           for(i=0;i<obj.length;i++)
            {
              if(obj[i].new == 'true')
              {
                 //alert(obj[i].new );
                 $("#sendid").find('#user'+ obj[i].userid).html("-- New");
                 $("#sendid").find('#user'+ obj[i].userid).parent().addClass('new-message');
                 $(".msg-wgt-header").addClass("blink");
              }
              else
              {
                 $("#sendid").find('#user' + obj[i].userid).html("");
                 $("#sendid").find('#user'+ obj[i].userid).parent().removeClass('new-message');
              }
            }
          }
        });
	 	 }
