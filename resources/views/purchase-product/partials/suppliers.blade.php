@extends('layouts.app')

@section('title', 'Inventory suppliers')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
.ajax-loader{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1060;
}
.inner_loader {
	top: 30%;
    position: absolute;
    left: 40%;
    width: 100%;
    height: 100%;
}
.pd-5 {
  padding:5px !important;
}
.pd-3 {
  padding:3px !important;
}
.status-select-cls .multiselect {
  width:100%;
}
.btn-ht {
  height:30px;
}
.status-select-cls .btn-group {
  width:100%;
  padding: 0;
}
.table.table-bordered.order-table a{
color:black!important;
}
</style>
@endsection

@section('large_content')
	<div class="ajax-loader" style="display: none;">
		<div class="inner_loader">
		<img src="{{ asset('/images/loading2.gif') }}">
		</div>
	</div>

    <div class="row">
        <div class="col-12" style="padding:0px;">
            <h2 class="page-heading">Purchase Products | Suppliers</h2>
        </div>
           <div class="col-10" style="padding-left:0px;">
            <div >
            <form class="form-inline" action="/purchase-product/get-suppliers" method="GET">
                
                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="term" type="text" class="form-control"
                         value="{{ isset($term) ? $term : '' }}"
                         placeholder="Search">
                </div>

          

                   <div class="form-group col-md-1 pd-3">
                <button type="submit" class="btn btn-image ml-3"><img src="{{asset('images/filter.png')}}" /></button>
                  </div>
              </form>
               
            </div>
             </div>
        </div>	


<div class="row">
    <div class="infinite-scroll" style="width:100%;">
	<div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;table-layout:fixed">
        <thead>
        <tr>
            <th width="10%">Sl no</th>
            <th width="35%">Name</th>
            <th width="20%">Product Inquiry Count</th> <!-- Purpose : Product Inquiry Count -DEVTASK-4048 -->
            <th width="20%">Communication</th> <!-- Purpose : Add communication -DEVTASK-4236 -->
            <th width="15%">Action</th>
         </tr>
        </thead>

        <tbody>
			@foreach ($suppliers as $key => $supplier)
            <tr class="">
              <td>{{ ++$key }}</td>
              <td>{{ $supplier->supplier }}</td>
              <td>{{$supplier->inquiryproductdata_count}}</td><!-- Purpose : Product Inquiry Count -DEVTASK-4048 -->
              <!-- START - purpose : Add Communication -DEVTASK-4236 -->
              <td>
              @if($supplier->phone)
              <input type="text" name="message" id="message_{{$supplier->id}}" placeholder="whatsapp message..." class="form-control send-message" data-id="{{$supplier->id}}">
              

              <a type="button" class="btn btn-xs btn-image load-communication-modal"  data-object="supplier" data-load-type="text" data-all="1" title="Load messages" data-object="supplier" data-id="{{$supplier->id}}" ><img src="/images/chat.png" alt=""></a>
              @endif
              </td>
               <!-- END - DEVTASK-4236 -->
              <td>
              <a href="#"  data-type="order" data-id="{{$supplier->id}}" class="btn btn-xs btn-secondary product-list-btn" style="color:white !important;">
                Order
              </a>
              <a href="#"  data-type="inquiry" data-id="{{$supplier->id}}" class="btn btn-xs btn-secondary product-list-btn" style="color:white !important;">
                Inquiry
              </a>
              <button title="Select all products" type="button" class="btn btn-xs btn-secondary select-all-products btn-image no-pd" data-id="{{$supplier->id}}">
                <img src="/images/completed.png" style="cursor: default;"></button>
              
              <i class="fa fa-address-card view_excel_supplier_wise" data-id="{{$supplier->id}}" aria-hidden="true" style="cursor:pointer;"></i>
              </td>
            </tr>
            <tr class="expand-row-{{$supplier->id}} hidden">
                <td colspan="5" id="product-list-data-{{$supplier->id}}">
                
                </td>
            </tr>
           @endforeach
        </tbody>
      </table>
	</div>
    </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
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



    <!-- Modal -->
  <div class="modal fade" id="supplier_excel_data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Purchase Product Order Excel Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive mt-2">
              <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;table-layout:fixed">
                <thead>
                <tr>
                    <th width="35%">Excel</th>
                    <th width="20%">Version</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>

              <tbody class="supplier_excel_history">
               
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="{{ asset('/js/order-awb.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="{{asset('js/common-email-send.js')}}">//js for common mail</script> 
<script type="text/javascript">

$(document).on('click', '.product-list-btn', function(e) {
      e.preventDefault();
      let type = $(this).data('type');
      let supplier_id = $(this).data('id');
        $.ajax({
          url: '/purchase-product/get-products/'+type+'/'+supplier_id,
          type: 'GET',
          dataType: 'html',
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            $(".expand-row-"+supplier_id).toggleClass('hidden');
            $("#product-list-data-"+supplier_id).html(response);
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });

    var selectAllProductBtn = $(".select-all-products");
    selectAllProductBtn.on("click", function (e) {
                    var supplier_id = $(this).data('id');
                    var $this = $(this);
                    var custCls = '.supplier-'+supplier_id;
                    if ($this.hasClass("has-all-selected") === false) {
                        $(this).find('img').attr("src", "/images/completed-green.png");
                        $(custCls).find(".select-pr-list-chk").prop("checked", true).trigger('change');
                        $this.addClass("has-all-selected");
                    }else {
                        $(this).find('img').attr("src", "/images/completed.png");
                        $(custCls).find(".select-pr-list-chk").prop("checked", false).trigger('change');
                        $this.removeClass("has-all-selected");
                    }
    })
    function unique(list) {
            var result = [];
            $.each(list, function (i, e) {
                if ($.inArray(e, result) == -1) result.push(e);
            });
            return result;
        }
    var product_ids = [];
    var order_ids = [];//Purpose : array for Order id - DEVTASK-4236
    $(document).on('click', '.btn-send', function(e) {
      e.preventDefault();
      // product_ids = [];
      let type = $(this).data('type');
      let supplier_id = $(this).data('id');

        var cus_cls = ".supplier-"+supplier_id;
            var total = $(cus_cls).find(".select-pr-list-chk").length;
            for (i = 0; i < total; i++) {
             var supplier_cls = ".supplier-"+supplier_id+" .select-pr-list-chk";
             var $input = $(supplier_cls).eq(i);
             var product_id = $input.data('id');
             var order_id = $input.data('order-id');
             if ($input.is(":checked") === true) {
                    product_ids.push(product_id);
                    product_ids = unique(product_ids);

                    //START - Purpose : Add Order id - DEVTASK-4236
                    order_ids.push(order_id);
                    order_ids = unique(order_ids);
                    //END - DEVTASK-4236
                }
            }
    if(product_ids.length == 0)
    {
        alert("Please select some products");
        return;
    }
        $.ajax({
          url: '/purchase-product/send-products/'+type+'/'+supplier_id,
          type: 'GET',
          dataType: 'html',
          data: {
              product_ids:JSON.stringify(product_ids),
              order_ids:JSON.stringify(order_ids)
          },
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            toastr['success']("Message sent successfully!", "Success");
            setTimeout(function(){ location.reload(); }, 2000);
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });

    //START - purpose : Add Communication send msg -DEVTASK-4236
    $(document).on('keyup', '.send-message', function(event) {
        if (event.keyCode != 13) {
            return;
        }

        let supplierId = $(this).attr('data-id');
        let message = $(this).val();
        let self = this;

        if (message == '') {
            return;
        }

        $.ajax({
            url: "{{action('WhatsAppController@sendMessage', 'supplier')}}",
            type: 'post',
            data: {
                message: message,
                supplier_id: supplierId,
                _token: "{{csrf_token()}}",
                status: 2
            },
            success: function() {
              $("#loading-image").hide();
                $(self).removeAttr('disabled');
                $(self).val('');
                toastr['success']("Message sent successfully!", "Success");
            },
            beforeSend: function() {
                $(self).attr('disabled', true);
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                $(self).removeAttr('disabled');
            }
        });

    });
    //END - DEVTASK-4236

    
    //START - Purpose : set value in Modal - DEVTASK-4236
    $(document).on('click', '.btn_send_modal', function(event) {
        let type = $(this).data('type');
        let supplier_id = $(this).data('id');
        var product_ids = [];
        var order_ids = [];
        var cus_cls = ".supplier-"+supplier_id;
        var total = $(cus_cls).find(".select-pr-list-chk").length;

        
        for (i = 0; i < total; i++) {
          var supplier_cls = ".supplier-"+supplier_id+" .select-pr-list-chk";
          var $input = $(supplier_cls).eq(i);
          var product_id = $input.data('id');
          var order_id = $input.data('order-id');
          if ($input.is(":checked") === true) {
                product_ids.push(product_id);

                product_ids = unique(product_ids);

                order_ids.push(order_id);
                order_ids = unique(order_ids);
            }
        }

        $('.type').val(type);
        $('.supplier_id').val(supplier_id);
        $('.product_id').val(product_ids);
        $('.order_id').val(order_ids);

        if(product_ids == '')
        {
          $('.show_excel_send_data').css("display", "none");
          $('.send_excel_btn').css("display", "none");
          $('.select_product_error').css("display", "block");
        }else{
          $('.show_excel_send_data').css("display", "block");
          $('.send_excel_btn').css("display", "block");
          $('.select_product_error').css("display", "none");
        }

        $.ajax({
            url: '{{ route("purchase-product.getallproducts") }}',
            type: 'get',
            data: {
                type: type,
                supplier_id: supplier_id,
                // product_id:product_id,
                // order_id:order_id,
                product_id:JSON.stringify(product_ids),
                order_id:JSON.stringify(order_ids),
                _token: "{{csrf_token()}}",
                status: 2
            },
            success: function(response) {
              $("#loading-image").hide();
              
               if(response.code == 200)
               {
                  $('.additional_content').val(response.data);
               }
            },
            beforeSend: function() {
               
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });
        
    });

    $(document).on('click', '.edit_excel_file', function(event) {
      

      var type = $('.type').val();
      var supplier_id = $('.supplier_id').val();
      var product_id = $('.product_id').val();
      var order_id = $('.order_id').val();

      $.ajax({
          url: '{{ route("purchase-product.edit_excel_file") }}',
          type: 'POST',
          data: {
              type: type,
              supplier_id: supplier_id,
              product_id:product_id,
              order_id:order_id,
              _token: "{{csrf_token()}}",
              status: 2
          },
          success: function(response) {
            $("#loading-image").hide();
            
            if(response.code == 200) {
              window.open('/purchase-product/openfile/'+response.excel_id, '_blank');
            }
            
          },
          beforeSend: function() {
             
              $("#loading-image").show();
          },
          error: function() {
            $("#loading-image").hide();
              
          }
      });

  });

    $(document).on('click', '.send_excel_btn', function(event) {
      
        var send_options = '';
        if($("#send_option_email").is(':checked') == true){
            send_options = 'email';
        }

        if($("#send_option_whatsapp").is(':checked') == true){
            send_options = 'whatsapp';
        }

        if($("#send_option_whatsapp").is(':checked') == true && $("#send_option_email").is(':checked') == true)
        {
            send_options = 'both';
        }

        if(send_options == '')
        {
            alert("Please Select Send Option");
            return;
        }

        var type = $('.type').val();
        var supplier_id = $('.supplier_id').val();
        var product_id = $('.product_id').val();
        var order_id = $('.order_id').val();
        var content = $('.additional_content').val();

        $.ajax({
            url: '{{ route("purchase-product.send_Products_Data") }}',
            type: 'POST',
            data: {
                type: type,
                supplier_id: supplier_id,
                product_id:product_id,
                order_id:order_id,
                content:content,
                send_options : send_options,
                _token: "{{csrf_token()}}",
                status: 2
            },
            success: function(response) {
              $("#loading-image").hide();
              toastr['success']("Message sent successfully!", "Success");
              $('#send_supp_modal').modal('hide');
            },
            beforeSend: function() {
               
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });

    });

    $(document).on('click', '.download_excel_url', function(event) {
      var type = $('.type').val();
      var supplier_id = $('.supplier_id').val();
      var product_id = $('.product_id').val();
      var order_id = $('.order_id').val();

      window.location.href = '/purchase-product/getexcel?type='+type+'&supplier_id='+supplier_id+'&product_id='+product_id+'&order_id='+order_id;
    
    });

    $(document).on('click', '.btn_set_template', function(event) {
        let type = $(this).data('type');
        let supplier_id = $(this).data('id');
        $('.type_template').val(type);
        $('.supplier_id_template').val(supplier_id);

        $.ajax({
            url: '{{ route("purchase-product.get_template") }}',
            type: 'GET',
            data: {
                type: type,
                supplier_id: supplier_id,
                _token: "{{csrf_token()}}",
            },
            success: function(response) {
              $("#loading-image").hide();
              
              $('.template_data').val(response.template_data);
            },
            beforeSend: function() {
              
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });
    });

    $(document).on('click', '.send_template_btn', function(event) {
      
        var type = $('.type_template').val();
        var supplier_id = $('.supplier_id_template').val();
        var template_data = $('.template_data').val();
        template_data = template_data.trim();

        if(template_data == '') {
            alert("Please Enter Template Content");
            return;
        }

        $.ajax({
            url: '{{ route("purchase-product.set_template") }}',
            type: 'POST',
            data: {
                type: type,
                supplier_id: supplier_id,
                template_data: template_data,
                _token: "{{csrf_token()}}",
            },
            success: function(response) {
              $("#loading-image").hide();
              toastr['success']("Template Updated Successfully!", "Success");
              $('#set_template_modal').modal('hide');
            },
            beforeSend: function() {
              
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });

    });

    $(document).on('click', '.view_excel_supplier_wise', function(event) {
        let supplier_id = $(this).data('id');

        $.ajax({
            url: '{{ route("purchase-product.get_excel_data_supplier_wise") }}',
            type: 'GET',
            data: {
                supplier_id: supplier_id,
                _token: "{{csrf_token()}}",
            },
            success: function(response) {
              $("#loading-image").hide();
            
              if(response.code == 200)
              {
                $('#supplier_excel_data').modal('show');
                var html_data = '';

                $.each($(response.get_final_arr), function( kk, vv ) {

                  html_data += '<tr>';
                  var excel_sheet_no = kk +1 ;
                  html_data += '<td> Excel Sheet '+ excel_sheet_no +'</td>';
                  

                  if(vv.excel_version != undefined)
                  {
                      html_data += '<td>';
                      html_data += '<select name="version" class="version_'+vv.excel_name+'_select">';
                      html_data += ' <option value=""> Select Version </option>';
                      $.each($(vv.excel_version), function( k, v ) {
                        
                          html_data += '  <option value='+v+'> Version '+v+'</option>';
                          
                      });
                      html_data += '</select>';
                        html_data += '</td>';
                  }else{
                    html_data += '<td></td>';
                  }
                  html_data += '<td>';
                  html_data += '<a target="_blank" href="/purchase-product/openfile/'+vv.excel_name+'"><i style="cursor: pointer;" id="'+vv.excel_name+'" class="fa fa-pencil-square-o" aria-hidden="true "></i></a>';
                  html_data += '<button data-id='+vv.excel_name+' data-supplier_id='+supplier_id+' class="btn btn-xs btn-secondary ml-3 resend_excel" data-id="10">Send</button>';
                  html_data += '</td>';

                  html_data += '</tr>';
                });

                $('.supplier_excel_history').html(html_data);
              }
            },
            beforeSend: function() {
              
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });
    });


    $(document).on('click', '.resend_excel', function(event) {

      var excel_id = $(this).data('id');
      var supplier_id = $(this).data('supplier_id');
      var version = $('.version_'+excel_id+'_select').find(":selected").val();


      if(version == "" || version == undefined)
      { 
        version = 'no';
      }

      $.ajax({
            url: '{{ route("purchase-product.send_excel_file") }}',
            type: 'POST',
            data: {
                excel_id: excel_id,
                supplier_id: supplier_id,
                version: version,
                _token: "{{csrf_token()}}",
            },
            success: function(response) {
              $("#loading-image").hide();
              toastr['success']("Email Send Successfully!", "Success");
              
            },
            beforeSend: function() {
              
                $("#loading-image").show();
            },
            error: function() {
              $("#loading-image").hide();
                
            }
        });

    });
    //END - DEVTASK-4236
</script>
@endsection