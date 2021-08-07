@extends('layouts.app')

@section('title', 'Master Control')

@section('styles')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <style type="text/css">
   .sub-table{
    padding-top: 0 !important;
    padding-bottom: 0 !important;
   }
  </style>
  
@endsection

@section('content')

  <div class="row mb-5">
      <div class="col-lg-12 margin-tb">
          <h2 class="page-heading">Master Control - {{ date('Y-m-d') }}</h2>

          <div class="pull-left">
          </div>

          <div class="pull-right mt-4">
          </div>
      </div>
  </div>

    @include('partials.flash_messages')

    @include('mastercontrol.partials.data')

  
<div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="email-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Email Communication</h4>
                    <input type="text" name="search_email_pop"  class="form-control search_email_pop" placeholder="Search Email" style="width: 200px;">
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

     @include('customers.zoomMeeting');

@endsection

@section('scripts')
  

  <script type="text/javascript">
  
    $(document).on('click', '.send-message', function () {
      var thiss = $(this);
      var data = new FormData();
      var type = $(this).attr('data-type');
      if(type == 'vendor'){
        var vendor_id = $(this).data('vendorid');
        send_url = '/whatsapp/sendMessage/vendor';
        data.append("vendor_id", vendor_id);
      }else if(type == 'customer'){
        send_url = '/whatsapp/sendMessage/customer';
        var customerId = $(this).attr('data-customerid');
        data.append("customer_id", customerId); 
      }else if(type == 'supplier'){
        send_url = '/whatsapp/sendMessage/supplier';
        var supplierId = $(this).attr('data-supplierid');
        data.append("supplier_id", supplierId);  
      }

      var message = $(this).siblings('input').val();
      data.append("message", message);
      data.append("status", 1);
      if (message.length > 0) {
        if (!$(thiss).is(':disabled')) {
          $.ajax({
            url: send_url,
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
                        thiss.closest('tr').find('.chat_messages').html(thiss.siblings('input').val());
                        $(thiss).siblings('input').val('');
                        $(thiss).attr('disabled', false);
                        refestContent();
                      }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);
                        alert("Could not send message");
                        console.log(errObj);
                      });
                    }
                  } else {
                    alert('Please enter a message first');
                  }
                });

    $(document).ready(function() {
      $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });
    $(document).ready(function() {
      $(".sub-table").find(".table").hide();
      $(".table").click(function() {
        var $target = $(event.target);
        if ( $target.closest("td").attr("colspan") > 1 ) {
          $target.slideUp();
        } else {
          $target.closest("tr").next().find(".table").slideToggle();
        }                    
      });
    });
    $('.quickCategory').on('change', function () {
      var type = $(this).attr('data-type');
      var id = $(this).attr('data-id');
      if(type == 'customer'){
        name = 'Customer';
      }else if(type == 'vendor'){
        name = 'Vendor';
      }else if(type == 'supplier'){
        name = 'Supplier';
      }

      var replies = JSON.parse($(this).val());
      $('#quickComment'+name+id).empty();
      $('#quickComment'+name+id).append($('<option>', {
        value: '',
        text: 'Quick Reply'
      }));
      replies.forEach(function (reply) {
        $('#quickComment'+name+id).append($('<option>', {
          value: reply.reply,
          text: reply.reply
        }));
      });
    });
    function messageToTextArea(text,type,id){
      if(type == 'customer'){
        name = 'Customer';
      }else if(type == 'vendor'){
        name = 'Vendor';
      }else if(type == 'supplier'){
        name = 'Supplier';
      }
      $('#textArea'+name+id).val(text.value);
    }
    $(document).on('click', '.expand-row', function () {
      var selection = window.getSelection();
      if (selection.toString().length === 0) {
        $(this).find('.chat-mini-container').toggleClass('hidden');
        $(this).find('.chat-full-container').toggleClass('hidden');
      }
    });
    function refestContent(){
      $.ajax({
        url: '/',
        type: 'GET',
        dataType: 'json',
        data: {
          blank: ''
        },
        beforeSend: function () {
          $("#loading-image").show();
        }
      }).done(function (data) {
            $("#loading-image").hide();
            $("#master-table tbody").empty().html(data.tbody);
            $(".sub-table").find(".table").hide();
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }



</script>


@endsection

