@extends('layouts.app')
@section('content')
<br>
<a style="color:white;" title="Add invoice" class="btn btn-secondary add-invoice-btn pd-5 pull-right" data-id='q'>
+ Add New
</a>
<a style="color:white;" title="+ Add New Invoice without Order" data-toggle="modal" data-target="#invoicewithoutordermoder890" class="btn btn-warning pull-right addInvoiceWithoutOrderBtn">
+ Add New Invoice without Order
</a>
<br>
<div class="table-responsive" style="margin-top:20px;">
   <table class="table table-bordered" style="border: 1px solid #ddd;">
      <thead>
         <tr>
            <th>Date</th>
            <th>Invoice Number</th>
            <th>Customer Name</th>
            <th>Invoice Value</th>
             <!-- <th>Price</th> -->
             <th>Shipping</th>
            <th>Duty</th>
            <th>Currency</th>
            <th>Website</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($invoices as $key => $invoice)
         <tr>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->invoice_number }}</td>
            <td>
               {{ $invoice->orders[0]->customer->name ?? '' }}
            </td>
            <td>
               @php
                  $final_price=0;
                  $orderProductPrice = 0;
                  $productQty = 0;

                  foreach ($invoice->orders as $ord):
                     if(!$ord->order_product->isEmpty())  {
                        foreach ($ord->order_product as $item):
                           $final_price +=$item->product_price;
                           $orderProductPrice = $item->product_price;
                        endforeach;

                        $productQty = count($ord->order_product);   
                     }  
                  endforeach;

               @endphp
               {{ $final_price}}
            </td>
             <!-- <td>{{$orderProductPrice * $productQty}}</td> -->
             <td>{{$duty_shipping[$invoice->id]['shipping']}}</td>
              <td>{{$duty_shipping[$invoice->id]['duty']}}</td>
            <td>
                {{ $invoice->orders[0]->currency ?? '--' }}
            </td>
            <td>
                {{ $invoice->orders[0]->customer->storeWebsite->website ?? '--' }}
            </td>
              
           
            <td>
               <a class="btn btn-image send-invoice-btn" data-id="{{ $invoice->id }}">
               <img title="Resend Invoice" src="/images/purchase.png" />
               </a>
               <a title="Edit Invoice" data-toggle="modal" data-target="#editInvoice" class="btn btn-image edit-invoice-btn" href="{{ route('order.edit.invoice',$invoice->id) }}">
               <i class="fa fa-edit"></i>
               </a>
               </a>
               <a title="Update Invoice Addresses"
                  data-address="{{$invoice->orders[0]->customer->address ?? ''}}"
                  data-city="{{$invoice->orders[0]->customer->city ?? ''}}"
                  data-country="{{$invoice->orders[0]->customer->country ?? ''}}"
                  data-pincode="{{$invoice->orders[0]->customer->pincode ?? ''}}"
                  data-codex="{{$invoice->orders[0]->customer->id ?? ''}}"
                  class="btn btn-image UpdateInvoiceAddresses"
                  data-id="{{$invoice->id}}"  
                  >
               <i class="fa fa-address-card-o"></i>
               </a>
               <a title="View Invoice" class="btn btn-image" href="{{ route('order.view.invoice',$invoice->id) }}">
               <img title="View Invoice" src="/images/view.png" />
               </a>
               <a title="Download Invoice" class="btn btn-image" href="{{ route('order.download.invoice',$invoice->id) }}">
               <i class="fa fa-download"></i>
               </a>
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
   {{$invoices->links()}}
</div>
<div id="addInvoice" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
         <div class="modal-header">
            <div class="row" style="width:100%;">
               <div class="form-group" style="width:100%;">
                  <label for="">Search :</label>
                  <select name="term" type="text" class="form-control" placeholder="Search" id="order-search" data-allow-clear="true">
                  <?php
                     if (request()->get('term')) {
                         echo '<option value="'.request()->get('term').'" selected>'.request()->get('term').'</option>';
                     }
                     ?>
                  </select>
               </div>
            </div>
         </div>
         <div id="add-invoice-content" style="min-height:200px;">
         </div>
      </div>
   </div>
</div>
<div id="updateInvoiceAddressesold" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
         <div class="modal-header">
            <h4>Update Invoice Addresses</h4>
         </div>
         <div class="modal-body">
            <div id="update-invoice-address">
               <form method="post" action="{{route('order.update.customer.address')}}">
                  @csrf
                  <div class="form-group">
                     <strong>Address:</strong>
                     <textarea id="address45" name="address" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                     <strong>City:</strong>
                     <input id="city45" name="city" class="form-control"/>
                     <input id="codex45" name="codex" type="hidden" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <strong>Country:</strong>
                     <input id="country45" name="country" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <strong>Pincode:</strong>
                     <input id="pincode45" name="pincode" class="form-control"/>
                  </div>
                  <button type="submit" name="update_details" class="btn btn-primary btn-sm">Update Address</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="updateInvoiceAddresses-modal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content ">
         <div class="modal-header">
            <h4>Update Invoice</h4>
         </div>
         <div class="modal-body">
            
         </div>
      </div>
   </div>
</div>

<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;"></div>
@include("partials.modals.edit-invoice-modal")
@include("partials.modals.invoice-without-order-model")
<script>
   $(document).on("click",".send-invoice-btn",function(e){
         e.preventDefault();
         var $this = $(this);
         $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/order/"+$this.data("id")+"/mail-invoice",
            type: "get",
            beforeSend: function() {
              $("#loading-image").show();
            }
          }).done(function(response) {
             if(response.code == 200) {
               toastr['success'](response.message);
             }else{
               toastr['error']('Something went wrong');
             }
             $("#loading-image").hide();
          }).fail(function(xhr, status, error) {
                $("#loading-image").hide();
                var err = eval("(" + xhr.responseText + ")");
                toastr['error']( err.message );
             
          });
      });
   
   
      $(document).on("click",".edit-invoice-btn",function(e){
         e.preventDefault();
         var $this = $(this);
         $.ajax({
            url: "/order/"+$this.data("id")+"/edit-invoice",
            type: "get"
          }).done(function(response) {
             $("#edit-invoice-content").html(response);
          }).fail(function(errObj) {
            // $("#editInvoice").hide();
          });
      });
   
      $(document).on("click",".add-invoice-btn",function(e){
         e.preventDefault();
         $('#addInvoice').modal('show');
      });
   
   //Invoice without order --START
   
      $(document).on("click",".addInvoiceWithoutOrderBtn",function(e){
   
         e.preventDefault();
         var $this = $(this);
         $.ajax({
            url: "/invoice/without-order/",
            type: "get"
          }).done(function(response) {
             $("#invoice-without-order-content").html(response);
          }).fail(function(errObj) {
            // $("#editInvoice").hide();
          });
      });
   //Invoice without order --END
      $('#order-search').select2({
              tags: true,
              width : '100%',
              ajax: {
                  url: '/order/order-search',
                  dataType: 'json',
                  delay: 750,
                  data: function (params) {
                      return {
                          q: params.term, // search term
                      };
                  },
                  processResults: function (data, params) {
                      for (var i in data) {
                          data[i].id = data[i].id ? data[i].id : data[i].order_id;
                      }
   
                      params.page = params.page || 1;
   
                      return {
                          results: data,
                          pagination: {
                              more: (params.page * 30) < data.total_count
                          }
                      };
                  },
              },
              placeholder: 'Search by Order id, customer id,name, no',
              escapeMarkup: function (markup) {
                  return markup;
              },
              minimumInputLength: 1,
              templateResult: function (order) {
                  if (order.loading) {
                      return order.order_id;
                  }
                  if (order.order_id) {
                      return "<p><b>Order id:</b> " + order.order_id + ": <b>Name:</b> " + order.name + " <b>Phone:</b> " + order.phone +"</p>";
                  }
              },
              templateSelection: (order) => order.text || order.order_id,
   
          });
   
          $('#order-search').on('select2:select', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                url: "/order/"+$this.val()+"/add-invoice",
                type: "get"
              }).done(function(response) {
                $("#add-invoice-content").html(response);
              }).fail(function(errObj) {
                toastr['error'](errObj.responseJSON.message);
              });
          });
   $('.UpdateInvoiceAddresses456').on('click',function(){
      var address = $(this).data('address')
      var country = $(this).data('country')
      var city = $(this).data('city')
      var pincode = $(this).data('pincode')
      var codex = $(this).data('codex')
     
      $('#country45').attr("value",country)
      $('#pincode45').attr("value",pincode)
      $('#city45').attr("value",city)
      $('#address45').text(address)
      $('#codex45').attr("value",codex)
   });

   $(document).on("click",".UpdateInvoiceAddresses",function() {
      var invoiceId = $(this).data("id");
      $.ajax({
        url : "/order/invoices/"+invoiceId+"/get-details",
        type: "get"
      }).done(function(response) {
         //$("#add-invoice-content").html(response);
          $("#updateInvoiceAddresses-modal").find(".modal-body").html(response);
          $("#updateInvoiceAddresses-modal").modal("show");
      }).fail(function(errObj) {
          //toastr['error'](errObj.responseJSON.message);
      });
   });

   $(document).on("click",".btn-update-invoice",function(e) {
      e.preventDefault();
      var invoiceId = $(this).data("id");
      var form = $(this).closest("form");
      $.ajax({
        url : "/order/invoices/"+invoiceId+"/update-details",
        type: "post",
        beforeSend: function() {
          $("#loading-image").show();
        },
        data: form.serialize(),
        dataType:"json"
      }).done(function(response) {
        $("#loading-image").hide();
        if(response.code == 200) {
          toastr['success'](response.message);
        }else{
          toastr['error'](response.message);
        }
      }).fail(function(errObj) {
        $("#loading-image").hide();
          toastr['error'](errObj.responseJSON.message);
      });
   });
</script>
@endsection