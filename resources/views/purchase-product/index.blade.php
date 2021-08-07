@extends('layouts.app')

@section('title', 'Purchase List')

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
.table.table-bordered.order-table tr th a{
  color:#000!important;
}
.table.table-bordered.order-table tr td a{
  /* color:black!important; */
  color:#757575!important;
}
.table.table-bordered.order-table tr td{
  font-size:14px;
  color:#757575;
}
.fa-user-plus{
  cursor:pointer;
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
            <h2 class="page-heading">Purchase Products ({{$totalOrders}})</h2>
        </div>
           <div class="col-10" style="padding-left:0px;">
            <div >
            <form class="form-inline" action="{{ route('purchase-product.index') }}" method="GET">
<!-- 
                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="term" type="text" class="form-control"
                         value="{{ isset($term) ? $term : '' }}"
                         placeholder="Search">
                </div> -->

                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_customer" type="text" class="form-control"
                         value="{{ isset($filter_customer) ? $filter_customer : '' }}"
                         placeholder="Customer">
                </div>

              
                
                <div class="form-group col-md-3 pd-3">
                <select class="form-control select-multiple2" style="width:100%" name="filter_supplier[]" data-placeholder="Search Supplier By Name.." multiple>
								@foreach($product_suppliers_list as $supplier)
									<option value="{{ $supplier->id }}" @if(is_array($filter_supplier) && in_array($supplier->id,$filter_supplier)) selected @endif>{{ $supplier->supplier }}</option>
                @endforeach
                
              </select>
              </div>


                <!-- <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_product" type="text" class="form-control"
                         value="{{ isset($filter_product) ? $filter_product : '' }}"
                         placeholder="Product">
                </div> -->

                <!-- <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_buying_price" type="text" class="form-control"
                         value="{{ isset($filter_buying_price) ? $filter_buying_price : '' }}"
                         placeholder="Buying Price">
                </div> -->

                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_selling_price" type="text" class="form-control"
                         value="{{ isset($filter_selling_price) ? $filter_selling_price : '' }}"
                         placeholder="Selling Price">
                </div>

                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_order_date" type="text" class="form-control"
                         value="{{ isset($filter_order_date) ? $filter_order_date : '' }}"
                         placeholder="Order Date">
                </div>
                
                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="filter_date_of_delivery" type="text" class="form-control"
                         value="{{ isset($filter_date_of_delivery) ? $filter_date_of_delivery : '' }}"
                         placeholder="Delivery Date">
                </div>

                <div class="form-group col-md-3 pd-3">
                <select name="filter_inventory_status_id" id="filter_inventory_status_id" class="form-control">
              <option value="">Select Inventory status</option>
              @foreach($inventoryStatus as $id => $status)
                <option value="{{$id}}" {{$id==$filter_inventory_status_id ? 'selected' : ''}}>{{$status}}</option>
              @endforeach
              </select>
                </div>

                   <div class="form-group col-md-1 pd-3">
                <button type="submit" class="btn btn-image ml-3"><img src="{{asset('images/filter.png')}}" /></button>
                  </div>
              </form>

            </div>
             </div>
        </div>
<div class="row">
@include('partials.flash_messages')

</div>
<div class="row">
        <div class="col-md-12" style="padding:0px;">
            <div class="pull-right">
              <a href="#" class="btn btn-xs btn-secondary create-status-btn">
                            Create status
              </a>
              <a href="/purchase-product/get-suppliers" class="btn btn-xs btn-secondary">
                            Suppliers
              </a>
              <!-- START - Purpose : Add Vutton - DEVTASK-19941 -->
              <a href="#" class="btn btn-xs btn-secondary not_mapping_supplier_list">
                            Not Mapping Suppliers
              </a>
              <!-- END - DEVTASK-19941 -->
            </div>
        </div>
</div>
<div class="row">
    <div class="infinite-scroll" style="width:100%;">
  <div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
        <thead>
        <tr>
            <th width="2%">select</th>
            <th width="5%"><a href="">ID</a></th>
            <th width="6%"><a href="">Customer</a></th>
            <th width="10%"><a href="">Supplier</a></th>
            <th width="10%">Product</th>
            <th width="10%">Buying Price</th>
            <th width="10%"><a href="">Selling price</a></th>
            <th width="8%"><a href="">Order Date</a></th>
           <th width="8%"><a href="">Del Date</a></th>
            <th style="width: 8%"><a href="">Inv Status</a></th>
            <th width="10%">Action</th>
         </tr>
        </thead>

        <tbody>
      @foreach ($orders_array as $key => $order)
                @php
                if($order->supplier_discount_info_id) {
                  $supplier = \App\SupplierDiscountInfo::join('suppliers','suppliers.id','supplier_discount_infos.supplier_id')->where('supplier_discount_infos.id',$order->supplier_discount_info_id)->select(['suppliers.*'])->get();
                }
                else {
                  $supplier = \App\ProductSupplier::join('suppliers','suppliers.id','product_suppliers.supplier_id')->where('product_suppliers.product_id',$order->product_id)->select(['suppliers.*','product_suppliers.supplier_link'])->get();
                }
                @endphp
                
            <tr class="{{ \App\Helpers::statusClass($order->assign_status ) }}">
              <td><span class="td-mini-container">
                  <input type="checkbox" class="selectedOrder" name="selectedOrder" value="{{$order->id}}">
                  </span>

                </td>
              <td  class="view-details" data-type="order" data-id="{{$order->id}}">
              <div class="form-inline">
                  @if ($order->is_priority == 1)
                    <strong class="text-danger mr-1">!!!</strong>
                  @endif
                  <span class="td-mini-container">
                  <span style="font-size:14px;">{{ $order->order_id }}</span>
                  </span>
                </div>
              </td>
              <td class="view-details" data-type="customer" data-id="{{$order->id}}">
              @if ($order->customer)
                  <span class="td-mini-container">
                    <a href="{{ route('customer.show', $order->customer->id) }}">{{ strlen($order->customer->name) > 15 ? substr($order->customer->name, 0, 13) . '...' : $order->customer->name }}</a>
                  </span>
                @endif
              </td>
                <td>
                    <p class="view-supplier-details" data-id="{{$order->order_product_id}}" >
                    @if(!$supplier->isEmpty())
                        @foreach($supplier as $s) 
                          @if(!empty($s->supplier_link))
                            <a target="_blank" href="{{$s->supplier_link}}">{{$s->supplier}}</a><br>
                          @else
                            <a target="_blank" href="javascript:;">{{$s->supplier}}</a><br>
                          @endif
                        @endforeach
                    @endif
                    </p>
                    @php
                    $order_product = \App\OrderProduct::find($order->order_product_id);
                    @endphp

                    

                </td>
              <td class="expand-row table-hover-cell">
                <div class="d-flex">
                  <div class="">
                  @php
                  $order_product = \App\OrderProduct::find($order->order_product_id);
                  @endphp
                      @if ($order_product && $order_product->product)
                      {{$order_product->product->name}}
                        @if ($order_product->product->hasMedia(config('constants.media_tags')))
                          <span class="td-mini-container">
                              <br/>
                              <a style="color:#000!important;" data-fancybox="gallery" href="{{ $order_product->product->getMedia(config('constants.media_tags'))->first()->getUrl() }}">View</a>
                          </span>
                        @endif
                      @endif
                  </div>
                </div>
              </td>
              <td class="expand-row table-hover-cell">
              @if ($order_product && $order_product->product)
                {{$order_product->product->price}}
              @endif
              </td>
              <td class="expand-row table-hover-cell">
              {{$order->product_price}}
              </td>
              <td>{{ $order->order_date }}</td>
              <td>{{$order->date_of_delivery}}</td>
              <td>
              <select name="inventory_status_id" id="" class="form-control change-inventory-status" data-id="{{$order->order_product_id}}">
              <option value="">Select Inventory status</option>
              @foreach($inventoryStatus as $id => $status)
          <option value="{{$id}}" {{$id==$order->inventory_status_id ? 'selected' : ''}}>{{$status}}</option>
              @endforeach
              </select>

              </td>
              <td>{{-- $order->balance_amount --}}
                @if ($order_product && $order_product->product)
              <i title="Add Supplier for this product" class="fa fa-user-plus add_supplier" aria-hidden="true" data-product_id="{{$order_product->product->id}}" data-product_name="{{$order_product->product->name}}"></i>
              @endif 
              @if(count($order->orderProducts)) 
              @php
                $image_array = [];                    
                foreach($order->orderProducts as $pid){
                  $product = $pid->product;
                  if($product){
                    if ($product->hasMedia(config('constants.media_tags'))){
                        $productImages = $product->getMedia(config('constants.media_tags'));
                        foreach($productImages as $img){
                            $image['product_id'] = $product->id;
                            $image['image_url'] = $img->getUrl();
                            $image_array[] = $image;
                        }
                    }
                  }
                }
              @endphp
              @endif
              @if(count($image_array))
              <button type="button" class="btn btn-xs image-button" style="cursor: pointer;" data-image-array="{{json_encode($image_array)}}" data-customer-id="{{$order->customer_id}}" data-order-id="{{$order->id}}" data-product-id="{{implode(',', $order->orderProducts->pluck('product_id')->toArray())}}" data-attached="1" data-limit="10" data-load-type="images" data-all="1" data-is_admin="1" data-is_hod_crm="" title="Load Auto Images attacheds"><img src="/images/archive.png" alt="" style="cursor: pointer; width: 16px;"></button>
              @endif
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>

  {!! $orders_array->appends(Request::except('page'))->links() !!}
  </div>
    </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
   </div>

   <div id="createStatusModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content ">
      <div class="modal-header">
                    <h4 class="modal-title">Create Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="createStatusForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <input type="text" class="form-control" name="status" placeholder="Status name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Create</button>
                    </div>
                </form>
      </div>
    </div>
</div>



<!-- Add Supplier for Product -->
<div class="modal fade" id="add_supplier_for_product" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supplier_name"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="add_supplier_for_product_form" method="POST">
      @csrf
          <div class="modal-body">
              <input type="hidden" name="product_id" id="product_id" value="" />
              <label>Select Supplier</label>
              <select class="form-control select-multiple2" style="width:100%" name="filter_supplier_pro" data-placeholder="Search Supplier By Name.." multiple>
                @foreach($product_suppliers_list as $supplier)
                  <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                @endforeach
                
              </select>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-secondary insert_supplier_pro">Add</button>
          </div>
      </form>
    </div>
  </div>
</div>




<div id="purchaseCommonModal" class="modal fade" role="dialog" style="padding-top: 0px !important;
    padding-right: 12px;
    padding-bottom: 0px !important;">
    <div class="modal-dialog" style="width: 100%;
    max-width: none;
    height: auto;
    margin: 0;">
      <div class="modal-content " style="
    border: 0;
    border-radius: 0;">
      <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body" id="common-contents">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
      </div>
    </div>
</div>
<div id="estdelhistoryresponse"></div>
<div id="order-product-images" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Product Images</h4>
          </div>
          <div class="modal-body" style="background-color: #999999;">
            <div style="overflow-x:auto;"><input type="text" id="click-to-clipboard-message" class="link" style="position: absolute; left: -5000px;">
            <table class="table table-bordered">
              <tbody style="background-color: #999999"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
@endsection
@include('common.commonEmailModal')
@include("partials.modals.update-delivery-date-modal")
@include("partials.modals.tracking-event-modal")
@include("partials.modals.generate-awb-modal")
@include("partials.modals.add-invoice-modal")
@include('partials.modals.return-exchange-modal')
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="{{ asset('/js/order-awb.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="{{asset('js/common-email-send.js')}}">//js for common mail</script>
  <script type="text/javascript">



$(document).on('click', '.image-button', function () {
    var customer_id = $(this).attr('data-customer-id'); 

    $("#order-product-images table tbody").html('');
    $("#order-product-images").find(".modal-dialog").css({"width":"700","max-width":"700"});
    $("#order-product-images").find(".modal-body").css({"background-color":"white"});
    let images = JSON.parse($(this).attr('data-image-array'));
    if(images.length){
      for(let i=0; i< images.length; i++){
        $("#order-product-images table tbody").append(`
          <td style="width:45%">
            <div class="speech-wrapper full-match-img">
              <div id="1786233" class="bubble alt">
                <div class="txt">
                  <p class="name alt"></p>
                  <p class="message" data-message=""></p>
                  <div style="margin-bottom:10px;">
                    <div class="row">
                      <div class="col-md-4">
                        <a href="${images[i].image_url}" class="show-product-info" target="_blank">
                          <img src="${images[i].image_url}" style="max-width: 100%; cursor: default;">
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td style="width:30%">&nbsp; 
            <button style="padding: 3px 6px !important;" title="Search Product Image" data-media-url="'${images[i].image_url}'" data-customer-id="${customer_id}" data-product-id="${images[i].product_id}" class="btn btn-xs btn-secondary search-product-image"><i class="fa fa-search" aria-hidden="true"></i></button>
          </td>
        `); 
      }
    }
    $("#order-product-images").modal("show"); 
});

$(document).on('click','.search-product-image',function(event){
  $.ajax({
      type: "GET", 
      url: "/erp-customer/search-image/"+$(this).attr('data-product-id')+'/'+$(this).attr('data-media-url').replaceAll('/', '|')+'?customer_id='+$(this).attr('data-customer-id'), 
      beforeSend : function() {
          toastr['success']('Image find process is started, you will get whatsapp notification as this process will be completed.');
      },
      success: function(response) { 

      },
      error: function() {
          toastr['error']('Error occured please try again later!');
      }
  });
});

$(document).on('click', '.view-details', function(e) {
      e.preventDefault();
      var order_id = $(this).data('id');
      var detailstype = $(this).data('type');
      var type = 'GET';
        $.ajax({
          url: '/purchase-product/customer-details/'+detailstype+'/'+order_id,
          type: type,
          dataType: 'html',
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            $("#purchaseCommonModal").modal("show");
            $("#common-contents").html(response);
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });


    $(document).on('click', '.create-status-btn', function(e) {
      $("#createStatusModal").modal("show");
    });
    $(document).on('submit', '#createStatusForm', function(e) {
      e.preventDefault();

      $.ajax({
          url: '/purchase-product/submit-status',
          type: 'POST',
          data:$(this).serialize(),
          dataType: 'json',
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            $("#createStatusModal").modal("hide");
            if(response.code == 200) {
                    toastr["success"](response.message, "Message");
                  }
                  else {
                    toastr["error"](response.message, "Message");
                  }
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });
    $(document).on('click', '.view-supplier-details', function(e) {
      e.preventDefault();
      var order_product_id = $(this).data('id');
      var type = 'GET';
        $.ajax({
          url: '/purchase-product/supplier-details/'+order_product_id,
          type: type,
          dataType: 'html',
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            $("#purchaseCommonModal").modal("show");
            $("#common-contents").html(response);
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });

    $(document).on('change', '.change-inventory-status', function(e) {
      e.preventDefault();
      var order_product_id = $(this).data('id');
      var status =  $(this).val();
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          url: '/purchase-product/change-status/'+order_product_id,
          type: 'POST',
          dataType: 'json',
          data: {
            status:status
          },
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
            $("#loading-image").hide();
            if(response.code == 200) {
                    toastr["success"](response.message, "Message");
                  }
                  else {
                    toastr["error"](response.message, "Message");
                  }
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
    });

    $(document).on('keyup', '.supplier-discount', function (event) {
            if (event.keyCode != 13) {
                return;
            }
            let id = $(this).data('id');
            let product_id = $(this).data('product');
            let discount = $("#supplier_discount-"+id).val();
            let orderProductId = $(this).data('order-product');
            $.ajax({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
                url: "{{action('PurchaseProductController@saveDiscount')}}",
                type: 'POST',
                data: {
                  discount: discount,
                  supplier_id: id,
                  product_id:product_id,
                  order_product_id:orderProductId
                },
                success: function (data) {
                    toastr["success"]("Discount updated successfully!", "Message");
                    $("#common-contents").html(data.html);
                }
            });

        });


        $(document).on('keyup', '.supplier-fixed-price', function (event) {
            if (event.keyCode != 13) {
                return;
            }
            let id = $(this).data('id');
            let fixed_price = $("#supplier_fixed_price_"+id).val();
            let product_id = $(this).data('product');
            let orderProductId = $(this).data('order-product');
            $.ajax({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
                url: "{{action('PurchaseProductController@saveFixedPrice')}}",
                type: 'POST',
                data: {
                  fixed_price: fixed_price,
                  supplier_id: id,
                  product_id:product_id,
                  order_product_id:orderProductId
                },
                success: function (data) {
                    toastr["success"]("Fixed price updated successfully!", "Message");
                    $("#common-contents").html(data.html);
                }
            });

        });

        $(document).on('click', '.product_default_supplier', function () {
            let supplier_id = $(this).data('id');
            let order_product = $(this).data('order_product');
            let product_id = $(this).data('product');
            $.ajax({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
                url: "{{action('PurchaseProductController@saveDefaultSupplier')}}",
                type: 'POST',
                data: {
                  supplier_id: supplier_id,
                  order_product:order_product,
                  product_id:product_id
                },
                success: function (res) {
                  if(res.code == 200) {
                    toastr["success"]("Supplier updated successfully!", "Message");
                  }
                  else {
                    toastr["error"](res.message, "Message");
                  }

                }
            });

        });



    $(document).ready(function() {
      $('#order-datetime').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#newdeldate').datetimepicker({
        minDate:new Date(),
        format: 'YYYY-MM-DD'
      });

      $(document).on("click",".generate-awb",function() {
          var customer = $(this).data("customer");
            if(typeof customer != "undefined" || customer != "") {
               $(".input_customer_name").val(customer.name);
               $(".input_customer_phone").val(customer.phone);
               $(".input_customer_address1").val(customer.address);
               $(".input_customer_address2").val(customer.city);
               $(".input_customer_city").val(customer.city);
               $(".input_customer_pincode").val(customer.pincode);
            }
            $("#generateAWBMODAL").modal("show");
      });

      $(document).on("change",".order-status-select",function() {
        $.ajax({
          url: "/order/change-status",
          type: "GET",
          async : false,
          data : {
            id : $(this).data("id"),
            status : $(this).val()
          }
        }).done( function(response) {

        }).fail(function(errObj) {
          alert("Could not change status");
        });
      });

      $(".select2").select2({tags:true});

      $(".select-multiple").multiselect({
        // buttonWidth: '100%',
        // includeSelectAllOption: true
      });


    $('ul.pagination').hide();
    $('.infinite-scroll').jscroll({
      autoTrigger: true,
      // debug: true,
      loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
      padding: 0,
      nextSelector: '.pagination li.active + li a',
      contentSelector: 'div.infinite-scroll',
      callback: function () {
        $('ul.pagination').first().remove();
        $('ul.pagination').hide();
      }
    });
    });

    $(document).on('click', '.change_message_status', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      var thiss = $(this);
      var type = 'GET';

      if ($(this).hasClass('approve-whatsapp')) {
        type = 'POST';
      }

        $.ajax({
          url: url,
          type: type,
          beforeSend: function() {
            $(thiss).text('Loading');
          }
        }).done( function(response) {
          $(thiss).closest('tr').removeClass('row-highlight');
          $(thiss).prev('span').text('Approved');
          $(thiss).remove();
        }).fail(function(errObj) {
          alert("Could not change status");
        });
    });

    $(document).on('click', '.expand-row', function() {
      var selection = window.getSelection();
      if (selection.toString().length === 0) {
        $(this).find('.td-mini-container').toggleClass('hidden');
        $(this).find('.td-full-container').toggleClass('hidden');
      }
    });

    $(document).on('click', '.expand-row', function() {
      var selection = window.getSelection();
      if (selection.toString().length === 0) {
        $(this).find('.td-mini-container').toggleClass('hidden');
        $(this).find('.td-full-container').toggleClass('hidden');
      }
    });
    $(document).on("click",".send-invoice-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/order/"+$this.data("id")+"/send-invoice",
          type: "get",
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done(function(response) {
           if(response.code == 200) {
             toastr['success'](response.message);
           }else{
             toastr['error'](response.message);
           }
           $("#loading-image").hide();
        }).fail(function(errObj) {
           $("#loading-image").hide();
        });
    });

    $(document).on("click",".send-order-email-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/order/"+$this.data("id")+"/send-order-email",
          type: "get",
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done(function(response) {
           if(response.code == 200) {
             toastr['success'](response.message);
           }else{
             toastr['error'](response.message);
           }
           $("#loading-image").hide();
        }).fail(function(errObj) {
           $("#loading-image").hide();
        });
    });

    $(document).on("click",".add-invoice-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/order/"+$this.data("id")+"/add-invoice",
          type: "get"
        }).done(function(response) {
          $('#addInvoice').modal('show');
           $("#add-invoice-content").html(response);
        }).fail(function(errObj) {
           $("#addInvoice").hide();
        });
    });





    var selected_orders = [];
         $(document).on('click', '.selectedOrder', function () {
            var checked = $(this).prop('checked');
            var id = $(this).val();
             if (checked) {
              selected_orders.push(id);
            } else {
                var index = selected_orders.indexOf(id);
                 selected_orders.splice(index, 1);
            }
        });
        $(document).on("click",".delete-orders",function(e){
          e.preventDefault();
          if(selected_orders.length < 1) {
            toastr['error']("Select some orders first");
            return;
          }
          var x = window.confirm("Are you sure, you want to delete ?");
          if(!x) {
            return;
          }
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: "/order/deleteBulkOrders",
              type: "post",
              data: {ids : selected_orders}
            }).done(function(response) {
              toastr['success'](response.message);
              window.location.reload();
            }).fail(function(errObj) {
            });
        });
        // $(document).on("click",".view-product",function(e){
        //   e.preventDefault();
        //   var id = $(this).data('id');
        //   $.ajax({
        //       headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //       },
        //       url: "/order/"+id+"/view-products",
        //       type: "GET"
        //     }).done(function(response) {
        //       $('#view-products').modal('show');
        //       $("#view-products-content").html(response);
        //     }).fail(function(errObj) {
        //     });
        // });
         $(document).on("click",".update-customer",function(e){
          e.preventDefault();
          if(selected_orders.length < 1) {
            toastr['error']("Select some orders first");
            return;
          }
          $('#updateCustomer').modal('show');
        });

        $(document).on('submit', '#customerUpdateForm', function (e) {
                e.preventDefault();
                var data = $(this).serializeArray();
                data.push({name: 'selected_orders', value: selected_orders});
                $.ajax({
                    url: "{{route('order.update.customer')}}",
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        toastr['success']('Successful', 'success');
                        $('#updateCustomer').modal('hide');
                        $("#customerUpdateForm").trigger("reset");
                        $(".order-table tr").find('.selectedOrder').each(function () {
                          if ($(this).prop("checked") == true) {
                            $(this).prop("checked", false);
                          }
                        });
                        selected_orders = [];
                    },
                    error: function () {
                        alert('There was error loading priority task list data');
                    }
                });
            });
             $(document).on('click', '.quick_return_exchange', function (e) {
            let $this       = $(this),
                $modelData  = $(document).find(".return-exchange-model-data");
             $('#return-exchange-modal').modal('show');
             $.ajax({
                type: "GET",
                url: "/return-exchange/getProducts/" + $this.data("id"),
            }).done(function (response) {
              $modelData.html(response.html);
              $('.due-date').datetimepicker({
                    minDate:new Date(),
                    format: 'YYYY-MM-DD'
                });
            }).fail(function (response) {});
        });

        $(document).on("click","#return-exchange-form input[name='type']",function() {
            if($(this).val() == "refund") {
                $("#return-exchange-form").find(".refund-section").show();
            }else{
                $("#return-exchange-form").find(".refund-section").hide();
            }
        });
         $(document).on("click","#btn-return-exchage-request",function(e) {
            e.preventDefault();
            var form = $("#return-exchange-form");
            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                data: form.serialize(),
                dataType:"json"
            }).done(function (response) {
                toastr[(response.code == 200) ?'success' : 'error'](response.message);
                $('#return-exchange-modal').modal('hide');
                document.getElementById("return-exchange-form").reset();
            }).fail(function (response) {
                console.log(response);
            });
        });
        $(document).on("click","#btn-return-exchage-request",function(e) {
            e.preventDefault();
            var form = $("#return-exchange-form");
            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                data: form.serialize(),
                dataType:"json"
            }).done(function (response) {
                toastr[(response.code == 200) ?'success' : 'error'](response.message);
                $('#return-exchange-modal').modal('hide');
                document.getElementById("return-exchange-form").reset();
            }).fail(function (response) {
                console.log(response);
            });
        });
        $(document).on('click','.show-est-del-date',function(e){
          e.preventDefault();
          var data_new_est = $(this).data('new-est');
          var order_id = $(this).data('id');
          $('#newdeldate').val(data_new_est);
          $('#orderid').val(order_id);
          $('#update-del-date-modal').modal('show');
        })
        $(document).on('click','.update-del-date',function(e){
          e.preventDefault();
          var newdeldate = $('#newdeldate').val();
          if(!newdeldate){
            toastr['error']('Estimate delivery date field cannot be empty !');
            return;
          }
            var form = $("#updateDelDateForm");
            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                data: form.serialize(),
                dataType:"json",
                beforeSend:function(data){
                  $('.ajax-loader').show();
                }
            }).done(function (response) {
              $('.ajax-loader').hide();
                toastr[(response.code == 200) ?'success' : 'error'](response.message);
                $('#update-del-date-modal').modal('hide');
                document.getElementById("updateDelDateForm").reset();
                location.reload();
            }).fail(function (response) {
              $('.ajax-loader').hide();
                console.log(response);
            });
        })
        $(document).on('click','.est-del-date-history',function(e){
          e.preventDefault();
          var order_id = $(this).data('id');
          $.ajax({
                type: "GET",
                url: "{{route('order.viewEstDelDateHistory')}}",
                data: {order_id:order_id},
                dataType:"json",
                beforeSend:function(data){
                  $('.ajax-loader').show();
                }
            }).done(function (response) {
              $('.ajax-loader').hide();
              $('#estdelhistoryresponse').empty().html(response.html);
              $('#estiate_del-history-modal').modal('show');
            }).fail(function (response) {
              $('.ajax-loader').hide();
                console.log(response);
            });

        })

        $('[data-fancybox="gallery"]').fancybox({
            // Options will go here
          });

          $(document).ready(function() {
          $(".select-multiple").multiselect();
          $(".select-multiple2").select2();
      });


      $(document).on('click', '.add_supplier', function(e) {
        e.preventDefault();
        var product_id = $(this).data('product_id');
        var product_name = $(this).data('product_name');

        $('#add_supplier_for_product_form #product_id').val(product_id);
        $('#supplier_name').html('<h4>Add Supplier for '+product_name+' Product</h4>');
        $('#add_supplier_for_product').modal('show');
      });

      $(document).on('click', '.insert_supplier_pro', function(e) {
        e.preventDefault();
        var product_id =  $('#add_supplier_for_product_form #product_id').val();
        var supplier_id = $("select[name='filter_supplier_pro']").val();
       
        if(supplier_id.length < 1)
        {
            toastr['error']('Please Select Suppliers');
            return;
        }

        var type = 'POST';
          $.ajax({
            url: "{{route('purchase-product.insert_suppliers_product')}}",
            data: {
              product_id:product_id,
              supplier_id:supplier_id,
              _token: "{{ csrf_token() }}",
            },
            type: type,
            dataType: 'json',
            beforeSend: function() {
              $("#loading-image").show();
            }
          }).done( function(response) {
              $("#loading-image").hide();
              
              if(response.code == 200)
              {
                $('#add_supplier_for_product').modal('hide');
                toastr["success"](response.message, "Message");
              }
              else if(response.code == 400)
              {
                toastr["error"](response.message, "Message");
              }
              
            
          }).fail(function(errObj) {
              $("#loading-image").hide();
          });
      });

    //START - Purpose : Get not mapping product with supplier list - DEVTASK-19941
    $(document).on('click', '.not_mapping_supplier_list', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{route('not_mapping_product_supplier_list')}}",
            data: {
              _token: "{{ csrf_token() }}",
            },
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
              $("#loading-image").show();
            }
        }).done( function(response) {
              $("#loading-image").hide();
              
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
              }
        }).fail(function(errObj) {
              $("#loading-image").hide();
        });
    });
    //END - DEVTASK-19941
  </script>
@endsection
