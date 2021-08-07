
jQuery(document).ready(function () {

  $('#quickTaskSubmit').on('click', function(e) {
    e.preventDefault();

    var thiss = $(this);
    var form = $('#quickTaskForm');
    var data = form.serialize();

    if ($(form)[0].checkValidity()) {
      if (!$(thiss).attr('disabled')) {
        $.ajax({
          type: "POST",
          url: "/task",
          data: data,
          beforeSend: function () {
            $(thiss).attr('disabled', true);
            $(thiss).text('Adding...');
          }
        }).done(function() {
          $('#quick_task_subject').val('');
          $('#quick_task_details').val('');

          $(thiss).attr('disabled', false);
          $(thiss).text('Add');
          // $('#quick_task_assign_to').val('');
          // $('#quick_task_assign_to_contacts').val('');

          form.find('.close').click();
        }).fail(function(response) {
          $(thiss).attr('disabled', false);
          $(thiss).text('Add');

          alert('Could not create quick task!');
          console.log(response);
        });
      }
    } else {
      $('#quickTaskForm')[0].reportValidity()
    }


  });

  $('#quickInstructionSubmit').on('click', function(e) {
    e.preventDefault();

    var form = $('#quickInstructionForm');
    var data = form.serialize();

    $.ajax({
      type: "POST",
      url: "/instruction",
      data: data
    }).done(function() {
      $('#quick_instruction_body').val('');
      $('#quick_instruction_assiged_to select[value=""]');
      $('#quick_instruction_assiged_to select[value=""]');

      form.find('.close').click();
    }).fail(function(response) {
      alert('Could not create quick instruction!');
      console.log(response);
    });
  });

  $('#quickDevelopmentSubmit').on('click', function(e) {
    e.preventDefault();

    var form = $('#quickDevelopmentForm');
    var data = form.serialize();

    $.ajax({
      type: "POST",
      url: "/development/create",
      data: data
    }).done(function() {
      $('#quick_development_task_textarea').val('');
      // $('#quick_instruction_assiged_to select[value=""]');
      // $('#quick_instruction_assiged_to select[value=""]');

      $('#quickDevelopmentModal').find('.close').click();
    }).fail(function(response) {
      alert('Could not create quick development task!');
      console.log(response);
    });
  });

    jQuery('li.notification-dropdown .dropdown-toggle').on('click', function (event) {
        event.preventDefault();
        jQuery(this).parent().toggleClass('show');
        jQuery(this).next().toggleClass('show');
    });

    jQuery('body').on('click', function (e) {

        let dropdown = jQuery('li.dropdown.notification-dropdown');

        if (!dropdown.is(e.target)
            && dropdown.has(e.target).length === 0
            && jQuery('.show').has(e.target).length === 0
        ) {
            dropdown.removeClass('show');
            jQuery('li.dropdown.notification-dropdown ul').removeClass('show');
        }
    });

    jQuery('.btn-notify').click(function () {

        let btnNotify = jQuery(this);
        let id = btnNotify.attr('data-id');

        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'/notificationMarkRead/'+id,
            success: function (data) {

                if(data.msg === 'success'){
                    btnNotify.parent().parent().addClass('isread');
                }
            }
        });

    });

    jQuery(document).on('click', '.removeOldImage', function (e) {
        e.preventDefault();
        let id = jQuery(this).attr('data-id');
        let image_id = jQuery(this).attr('media-id');
        // alert(image_id);

        jQuery('input[name="oldImage'+id+'"]').val( image_id);
       jQuery('input[name="oldImage['+id+']"]').val( image_id);
       // jQuery(this).siblings('input').val(image_id);
       // console.log(jQuery('input[name="oldImage['+id+']"]'));
       jQuery('.old-image'+id).hide();
       jQuery('.new-image'+id).show();
       // $(this).parent().parent().hide();

    });

    jQuery('input[name="measurement_size_type"]').on('change',function () {

        let checked_value = jQuery('input[name="measurement_size_type"]:checked').val();

        if( checked_value === 'measurement' )
        {
            jQuery('#measurement_row').show();
            jQuery('#size_row').hide();
        }
        else if( checked_value === 'size' ) {
            jQuery('#measurement_row').hide();
            jQuery('#size_row').show();
        }
        else {
            jQuery('#measurement_row').hide();
            jQuery('#size_row').hide();
        }

    });

    jQuery('input[name="measurement_size_type"]').trigger('change');

    jQuery('#btn-edit-cat').click(function (e) {
        e.preventDefault();
        location.href = '/category/'+( jQuery('select[name="edit_cat"]').val() )+'/edit';

    });

    jQuery('.preventDefault').click((e) => e.preventDefault());

     jQuery("#tasktype").change(function() {
        if(jQuery('option:selected', this).text() == 'Meeting')
        {
           jQuery(".minutes").show();
        }
        else
        {
           jQuery(".minutes").hide();
        }
    });

    $(document).ready(function() {
      $('#quickComment').on('change', function () {
          $('#message-body').val($(this).val());
      });

      $('#quickCommentInternal').on('change', function () {
          $('#internal-message-body').val($(this).val());
      });

      $('#instructionComment').on('change', function () {
          $('#instruction-body').val($(this).val());
      });
    });


    jQuery('#leadsource').change(function () {
        if(jQuery('#leadsource').val() == 'database')
        {
            jQuery("#leadsourcetxt").attr("placeholder", "Comments").val("").focus().blur();
        }
        if(jQuery('#leadsource').val() == 'instagram')
        {
            jQuery("#leadsourcetxt").attr("placeholder", "Instagram handler").val("").focus().blur();
        }
        if(jQuery('#leadsource').val() == 'facebook')
        {
            jQuery("#leadsourcetxt").attr("placeholder", "Facebook handler").val("").focus().blur();
        }
        if(jQuery('#leadsource').val() == 'new')
        {
            jQuery("#leadsourcetxt").attr("placeholder", "Comments on New Lead").val("").focus().blur();
        }
    });
});

function getTodayYesterdayDate(date) {

    let a = moment(new Date());
    let b = moment(date);

    if( a.diff(b, 'days') === 0){
        return 'Today';
    }
    else if( a.diff(b, 'days') === 1){
        return 'Yesterday';
    }

    return moment(date).format('DD/MM/YYYY');
}

function attactApproveEvent() {

    jQuery('.btn-approve').click(function (e) {

        e.preventDefault();

        let btnApprove = jQuery(this);
        let id = btnApprove.attr('data-id');

        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'/productsupervisor/approve/'+id,
            success: function (data) {

                if(data.msg === 'success'){

                    if( data.isApproved ) {
                        btnApprove.addClass('btn-success');
                        btnApprove.html('Approved');
                    }
                    else {
                        btnApprove.removeClass('btn-success');
                        btnApprove.html('Approve');
                    }
                }
            }
        });

    });

}
function attachRejectEvent() {

    jQuery('.btn-reject').click(function (e) {

        jQuery('#rejectWhom').show();
        jQuery('html,body').animate({ scrollTop: jQuery(document).height() }, 'fast');

    });
}

$(document).ready(function(){

    $(document).on('click','.copy-button',function(e){

        // let msg = jQuery('#'+jQuery(this).attr('data-id')).html();
        // location.href = '/message/updatestatus?status=3&id=19&moduleid=13&moduletype=leads';

        // let id = $(this).attr('data-id');

        // copyTextToClipboard( jQuery('#message_body_'+id).html() );
        copyTextToClipboard($(this).data('message'));

        // $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        //     },
        //     url : '/message/updatestatus',
        //     type : 'GET',
        //     data : {
        //         id : id,
        //         moduleid : $(this).attr('moduleid'),
        //         moduletype : $(this).attr('moduletype'),
        //         status : 3,
        //     },
        //     success : response => {
        //
        //         $('#status_img_'+id).attr('src','/images/3.png');
        //     }
        // });

        e.preventDefault();
    });

    $('.edit').click(function(){
        $(this).hide();
        $(this).prev().hide();
        $(this).next().show();
        $(this).next().select();
        $('.editablearea .save').show();
    });


    $('.editablearea textarea').blur(function() {
         if ($.trim(this.value) == ''){
             this.value = (this.defaultValue ? this.defaultValue : '');
         }
         else{
             $(this).prev().prev().html(this.value);
         }

         $(this).hide();
         $(this).prev().show();
         $(this).prev().prev().show();
         //$('.editablearea .save').hide();
     });

      $('.editablearea textarea').keypress(function(event) {
          if (event.keyCode == '13') {
              if ($.trim(this.value) == ''){
                 this.value = (this.defaultValue ? this.defaultValue : '');
             }
             else
             {
                 $(this).prev().prev().html(this.value);
             }

             $(this).hide();
             $(this).prev().show();
             $(this).prev().prev().show();
             //$('.editablearea .save').hide();
          }
      });

});


$(document).ready(function(){

        customerSearch();

        $(document).on('click','.talk-bubble',function(){
            $('#editmessage').show();
            var message = $(this).find('p').html();
            //alert($(this).attr("data-messageid"));
           $("input[name~='messageid']").val($(this).attr("data-messageid"));
            $('#editmessage textarea').val(message);

        });


    $(document).on('click','.show_more',function(){
        var ID = $(this).attr('id');
        var moduleid = $(this).attr('data-moduleid');
        var moduletype = $(this).attr('data-moduletype');
        $('.show_more').hide();
        $('.loding').show();
        $.ajax({
            type:'GET',
            url:'/message/loadmore',
            data: {messageid: ID, moduleid: moduleid, moduletype: moduletype},
            success:function(html){
                $('#show_more_main'+ID).remove();
                $('#message-container').append(html);
            }
        });
    });

     $(document).on('click','.close_remark',function(){
              $(this).closest('tr').remove();
              $("#feedback").html("");
     });

    /*$(".remarks").click(function(event){
            $(this).closest('tr').after('<tr><td colspan="5"><textarea name="remarks" placeholder="Remarks" id="remark"></textarea><input type="hidden" name="id" value="" id="remark_task_id" /><br><button class="submit_remark">Update</button>&nbsp;&nbsp;<button class="close_remark">Close</button><div id="feedback" style="color: green"></div></td></tr>');
            $("#positiontext").show();
            let id = $(this).attr('data-id');
            getremark(id);
            $("#remark_task_id").val(id);
             $("#feedback").html("");
    });*/

    $('.update-remark').click(function (e) {

        e.preventDefault();
        let id = jQuery(this).attr('data-id');

        let remark = jQuery('#remark-text-'+id).val();

        jQuery('#remark-load-'+id).show();


        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data: { id : id, remark: remark},
            url:'/task/addremark',
            success: function (data) {
                jQuery('#remark-load-'+id).hide();
               jQuery('#remarks-'+id ).prepend('<p>'+data['remark']+'<br /> <small>updated now</small></p><hr>');

            }
        });


    });

    $('.update-remark-s').click(function (e) {

        e.preventDefault();
        let id = jQuery(this).attr('data-id');

        let remark = jQuery('#remark-text-s-'+id).val();

        jQuery('#remark-load-s-'+id).show();

        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data: { id : id, remark: remark},
            url:'/task/addRemarkStatutory',
            success: function (data) {
                jQuery('#remark-load-s-'+id).hide();
               jQuery('#remarks-'+id ).prepend('<p>'+data['remark']+'<br /> <small>updated now</small></p><hr>');

            }
        });
    });


    $(document).on('click','.close_delete',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    $(".delete-task").click(function(event){

        $('#delete-task-row').remove();
        let id = $(this).attr('data-id');
        $(this).closest('tr').after(`
                    <tr id="delete-task-row">
                        <td colspan="5">
                            <form id="task-delete-form" method="POST">
                                <textarea name="comment" placeholder="Comment"></textarea>
                                <input type="hidden" name="id" value="${id}"/>
                                <br>
                                <input type="submit" name="Delete"/>
                                            &nbsp;&nbsp;
                                <button class="close_delete">Close</button>
                            </form>
                        </td>
                    </tr>
        `);
    });

    $(document).on( 'submit','#task-delete-form' ,(e) => {
        let data = $('#task-delete-form').serializeArray();
        e.preventDefault();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url : 'tasks/deleteTask',
            data : data,
            type : 'POST',
            success : (response) => {

                location.reload();
            }
        });
    });

    $(document).on('click','.submit_remark',function(){

        let id = jQuery("#remark_task_id").val();
        let remark =  jQuery("#remark").val();
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{remark: remark,id: id},
            url:'/task/addremark',
            success: function (data) {

                 $("#feedback").html("Remark Added");
                 $(this).closest('.remarks').addClass("red");
            }
        });
      });

    $(document).on('click','.n-status',function (e) {

        e.preventDefault();

        let btn = $(this);
        let status = $(this).val();
        let id = $(this).parent().attr('data-id');

        let remark = '';

        if(status !== '1')
            remark = prompt("Please enter remark", "remark");

        if(status === '2'){
            notificationQueue.postPoneNotification(parseInt(id));
        }

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                data:{
                    status : status,
                    remark : remark,
                },
                url:'/pushNotification/status/'+id,
                success: function (data) {

                },
                complete : function () {

                  // btn.parent().parent().parent().parent().remove();
                    btn.parent().parent().parent().remove();
                    if(status != '2')
                        nextNotification(id);

                }
        });
    });

    $('select[name="recurring_type"]').change(function () {

        let recurring_day = $('#recurring_day');
        let days = '';

        switch ($(this).val()){
            case 'EveryDay':
                recurring_day.html('');
            break;

            case 'EveryWeek':
                days = '';
                let weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                for(let i = 0 ; i < weekdays.length ; i++)
                    days += `<option value="${weekdays[i]}">${weekdays[i]}</option>`;

                recurring_day.html(`<select name="recurring_day"  id="recurring_week" class="form-control">${days}</select>`);
            break;

            case 'EveryMonth':
                days = '';
                for(let i = 1 ; i <= 30 ; i++)
                    days += `<option value="${i}">${i}</option>`;

                recurring_day.html(`<select name="recurring_day"  id="recurring_week" class="form-control">${days}</select>`);
            break;

            case 'EveryYear':
                recurring_day.html(`<input name="recurring_day" id="recurring_date" class="form-control" type="date"/>`);
            break;
        }
    });

});

function getremark(id)
{
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type:'GET',
            data:{id: id},
            url:'/tasks/getremark',
            success: function (data) {

                 $("#remark").val(data);
            }
        });
 }

function copyTextToClipboard(text) {
    let textArea = document.createElement("textarea");

    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = 0;
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
    } catch (err) {
        console.log('Oops, unable to copy');
    }
    document.body.removeChild(textArea);
}


function sendMessageWhatsapp(developer_task_id,message,context,token){
    var app_url = 'http://localhost/sololux-erp/public/';
    $.ajax({
        url: app_url+ "whatsapp/sendMessage/"+context,
        type: 'POST',
        data: {
            _token: token,
            message: message,
            user_id: developer_task_id,
            status: 2
        },
        success: function () {
            $(self).removeAttr('disabled');
            $("#message_" + developer_task_id).removeAttr('disabled');
            $(self).val('');
            $("#message_" + developer_task_id).val('');
            toastr['success']('Message sent successfully!', 'Message');
        },
        error: function () {
            $(self).removeAttr('disabled');
        }
    });
}

var customerSearch = function() {
      if($(".customer-search-select-box").length == 0) {
        return false;
      }
      $(".customer-search-select-box").select2({
        tags : true,
        ajax: {
            url: '/erp-leads/customer-search',
            dataType: 'json',
            delay: 750,
            data: function (params) {
                return {
                    q: params.term, // search term
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        },
        placeholder: 'Search for Customer by id, Name, No',
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 2,
        templateResult: formatCustomer,
        templateSelection: (customer) => customer.text || customer.name,
    });
  };

  function formatCustomer (customer) {
      if (customer.loading) {
          return customer.name;
      }
      if(customer.name) {
          return "<p> <b>Id:</b> " +customer.id  + (customer.name ? " <b>Name:</b> "+customer.name : "" ) +  (customer.phone ? " <b>Phone:</b> "+customer.phone : "" ) + "</p>";
      }

  }

  let checkQueueIsStucked = () => {
    /*fetch("/message-queue/status", { headers: { "Content-Type": "application/json; charset=utf-8" }}
    )
    .then(res => res.json()) // parse response as JSON (can be res.text() for plain response)
    .then(response => {
      if(response.code == 500) {
        toastr['error'](response.message, 'Message');
      }
      setTimeout(checkQueueIsStucked, 10000);
    })
    .catch(err => {
        console.log(err)
    });*/
  };

  $( document ).ready(function() {
    //checkQueueIsStucked();
  });
$(window).on('load', function(){

setTimeout(function(){


$(window).on('resize', function data(){
    var win = $(this);
    if (win.width() <= 1370) {
        var element3 = document.getElementById("developments");
        document.getElementById('nav_dots').appendChild(element3);
        $(element3).addClass('dropdown-submenu')
    }else{
        var element123 = document.getElementById("developments");
        document.getElementById('navs').appendChild(element123);
        $(element123).removeClass('dropdown-submenu')
    }


    if (win.width() <= 1545) {
        var element2 = document.getElementById("product-template");
        document.getElementById('nav_dots').appendChild(element2);
        $(element2).addClass('dropdown-submenu')
    }else{

        var element122 = document.getElementById("product-template");
        document.getElementById('navs').appendChild(element122);
        $(element122).removeClass('dropdown-submenu')
    }
    if (win.innerWidth() <= 1590 ) {

        var element = document.getElementById("queues");
        document.getElementById('nav_dots').appendChild(element);
        $(element).addClass('dropdown-submenu')
    }else{
        var element12 = document.getElementById("queues");
        document.getElementById('navs').appendChild(element12);
        $(element12).removeClass('dropdown-submenu')

    }

});
}, 150);
});
// window.onload = function() {
//     data();
// };
