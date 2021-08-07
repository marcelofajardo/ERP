@extends('layouts.app')

@section('title', 'Orders List')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<style>
  table {
    font-size: 14px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
  }
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
.table.table-bordered.order-table th a{
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
            <h2 class="page-heading">Orders List ({{$totalOrders}})</h2>
        </div>
           <div class="col-10" style="padding-left:0px;">
            <div >
            <form class="form-inline" action="{{ route('order.index') }}" method="GET">
                
                <div class="form-group col-md-3 pd-3">
                  <input style="width:100%;" name="term" type="text" class="form-control"
                         value="{{ isset($term) ? $term : '' }}"
                         placeholder="Search">
                </div>

                 <div class="form-group col-md-2 pd-3 status-select-cls">
                  <select class="form-control select-multiple" name="status[]" multiple>
                    <option value="">Select a Status</option>


                     @foreach ($order_status_list as $id => $order_st)
                      <option value="{{ $id }}" {{ isset($order_status) && in_array($id, $order_status) ? 'selected' : '' }}>{{ $order_st }}</option>
                    @endforeach
                  </select>
                </div>


                 <!-- <div class="form-group col-md-2 pd-3">
                  <?php echo Form::select("brand_id[]",["" => "-- Select Brands --"]+$brandList,request('brand_id',[]),["class" => "form-control select2"]); ?>
                </div> -->


                 <div class="form-group col-md-2 pd-3">
                  <div class='input-group date' id='order-datetime'>
                    <input type='text' class="form-control" name="date" value="{{ isset($date) ? $date : '' }}" />


                     <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>

                   <div class="form-group col-md-2 pd-3">
                  <div class="form-group ml-3">	
                      <select class="form-control select2" name="store_website_id">
                      <option value="">Select Site Name</option>
                      @forelse ($registerSiteList as $key => $item)
                          <option value="{{ $key }}" {{ isset($store_site) && $store_site == $key ? 'selected' : '' }}>{{ $item }}</option>
                      @empty
                      @endforelse
                      </select>
                  </div>
                  </div>

                   <div class="form-group col-md-1 pd-3">
                <button type="submit" class="btn btn-image ml-3"><img src="{{asset('images/filter.png')}}" /></button>
                  </div>
              </form>
               
            </div>
             </div>
          <div class="col-md-2" style="padding:0px;">
                  <a class="btn btn-xs btn-secondary" href="{{ route('order.create') }}">+</a>
                  <a href="{{ action('OrderController@downloadOrderInPdf', Request::all()) }}" class="btn btn-secondary btn-xs">Download</a>
              </div>
        </div>	
<div class="row">
@include('partials.flash_messages')
    <?php if(!empty($statusFilterList)) { ?>
      <div class="row col-md-12" style="font-size: smaller;">
          <?php foreach($statusFilterList as $listFilter) { ?>
            <div class="card">
                <div class="card-header">
                <?php echo ucwords($listFilter["order_status"]); ?> (<?php echo $listFilter["total"]; ?>)
                </div>
                <!-- <div class="card-body">
                    <?php echo $listFilter["total"]; ?>
                </div> -->
            </div>
        <?php } ?>
      </div>
    <?php } ?>  
</div>


<div class="row">
        <div class="col-md-12" style="padding:0px;">
            <div class="pull-right">
              <a href="#" class="btn btn-xs btn-secondary magento-order-status">Magento Order Status Mapping</a>
              <a href="#" class="btn btn-xs btn-secondary delete-orders">
                            Archive
              </a>
              <a href="#" class="btn btn-xs update-customer btn-secondary">
                            Update
              </a>
            </div>
        </div>
    </div>
<div class="row">
    <div class="infinite-scroll" style="width:100%;">
	<div class="table-responsive mt-2">
      <table class="table table-bordered order-table table-condensed" style="border: 1px solid #5A6268 !important; color:black;">
        <thead>
        <tr>
            <th width="1%" style="padding:0;" >Select</th>
            <th width="5%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=id{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">ID</a></th>
            <th width="6%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=date{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Date</a></th>
            <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=client_name{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Client</a></th>
            <th width="10%">Site Name</th>
            <th width="10%">Products</th>
            <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=estdeldate{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Est. Delivery Date</a></th>
            <th>Brands</th>
            <th width="14%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=status{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Order Status</a></th>
            <th width="8%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=advance{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Advance</a></th>
            <th width="8%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}{{ isset($order_status) ? implode('&', array_map(function($item) {return 'status[]='. $item;}, $order_status)) . '&' : '&' }}sortby=balance{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Balance</a></th>
            {{-- <th style="width: 5%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=action{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Action Status</a></th>
            <th style="width: 8%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=due{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Due</a></th> --}}
            {{-- <th style="width: 8%">Message Status</th> --}}
            {{-- <th style="width: 20%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=communication{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Communication</a></th> --}}
            <th>Waybill</th>
            <th>Price</th>
            <th>Shipping</th>
            <th>Duty</th>
            <th width="10%">Action</th>
         </tr>
        </thead>

        <tbody style="color:font-size: small;">
			@foreach ($orders_array as $key => $order)
      
             @php
               $extraProducts = [];
               $orderProductPrice = 0;
               $productQty = 0;
               
               if(!$order->order_product->isEmpty())  {
                  foreach($order->order_product as $orderProduct) {
                    $extraProducts[] = [
                      "sku" => $orderProduct->sku,
                      "qty" => $orderProduct->qty,
                      "product_price" => $orderProduct->product_price,
                      "name" => ($orderProduct->product) ? $orderProduct->product->name : ""
                    ];

                    $orderProductPrice = $orderProduct->product_price;

                  }
               }
             @endphp

             
            <tr style="background:#f1f1f1;" class="{{ \App\Helpers::statusClass($order->assign_status ) }}">
              <td><span class="td-mini-container">
                  <input type="checkbox" class="selectedOrder" name="selectedOrder" value="{{$order->id}}">
                  </span>
                </td>
              <td class="table-hover-cell">
              <div class="form-inline">
                  @if ($order->is_priority == 1)
                    <strong class="text-danger mr-1">!!!</strong>
                  @endif
                  <span class="td-mini-container">
                  <span style="font-size:14px;" class="toggle-title-box has-small" data-small-title="<?php echo ($order->order_id) ? substr($order->order_id, 0,3) : '' ?>" data-full-title="<?php echo ($order->order_id) ? $order->order_id : 
                  '' ?>">
                        <?php                            echo (strlen($order->order_id) > 3) ? substr($order->order_id, 0,3).".." : $order->order_id;
                        ?>
                     </span>
                  </span>
                </div>
              </td>
              <td>{{ Carbon\Carbon::parse($order->order_date)->format('d-m') }}</td>
              <td class="expand-row table-hover-cell" style="color:grey;">
                @if ($order->customer)
                  <span class="td-mini-container">
                    <a style="color: #6c757d;" href="{{ route('customer.show', $order->customer->id) }}">{{ strlen($order->customer->name) > 15 ? substr($order->customer->name, 0, 13) . '...' : $order->customer->name }}</a>
                  </span>

                  <span class="td-full-container hidden">
                    <a style="color: #6c757d;" href="{{ route('customer.show', $order->customer->id) }}">{{ $order->customer->name }}</a>
                  </span>
                @endif
              </td>
              <td class="expand-row table-hover-cell">
                @if ($order->storeWebsiteOrder)
                  @if ($order->storeWebsiteOrder->storeWebsite)
                    @php
                      $storeWebsite = $order->storeWebsiteOrder->storeWebsite;
                    @endphp
                    <span class="td-mini-container">
                        <a style="color: #6c757d;" href="{{$storeWebsite->website}}" target="_blank">{{ strlen($storeWebsite->website) > 15 ? substr($storeWebsite->website, 0, 13) . '...' : $storeWebsite->website }}</a>
                    </span>
                    <span class="td-full-container hidden">
                        <a style="color: #6c757d;" href="{{$storeWebsite->website}}" target="_blank">{{ $storeWebsite->website }}</a>
                    </span>
                  @endif
                @endif
              </td>
              
              <td class="expand-row table-hover-cell">	              
                @php $count = 0; @endphp	               
                <div class="d-flex">	               
                  <div class="">
                    @foreach ($order->order_product as $order_product)	                        
                      @if ($order_product->product)	                      
                        @if ($order_product->product->hasMedia(config('constants.attach_image_tag')))	                       
                          <span class="td-mini-container">	                         
                            @if ($count == 0)	                          
                              <?php foreach($order_product->product->getMedia(config('constants.attach_image_tag')) as $media) { ?> 
                                <a data-fancybox="gallery" href="{{ $media->getUrl() }}">VIEW #{{$order_product->product->id}}</a>
                              <?php break; } ?>
                              @php ++$count; @endphp	                        
                            @endif	                     
                          </span>	                        
                          <span class="td-full-container hidden">	                        
                            @if ($count >= 1)	   
                              <?php foreach($order_product->product->getMedia(config('constants.attach_image_tag')) as $media) { ?> 
                              <a data-fancybox="gallery" href="{{ $media->getUrl() }}">VIEW 
                               <?php break; } ?>
                              #{{$order_product->product->id}}</a>
                              @php $count++; @endphp	      
                            @endif	                     
                          </span>	                 
                        @endif	                 
                      @endif	             
                    @endforeach	   

                    @php
                      $productQty = count($order->order_product);     
                    @endphp    
                  </div>	    
                  @if (($count - 1) > 1)	           
                    <span class="ml-1">	         
                      ({{ ($count - 1) }})	       
                    </span>	           
                  @endif	        
                </div>	        
              </td>
              <td>
                <div style="display:inline;">{{($order->estimated_delivery_date)?$order->estimated_delivery_date:'---'}}</div>
               
              <i style="color:#6c757d;" class="fa fa-pencil-square-o show-est-del-date" data-id="{{$order->id}}" data-new-est="{{($order->estimated_delivery_date)?$order->estimated_delivery_date:''}}" aria-hidden="true"></i>
              <i style="color:#6c757d;" class="fa fa-info-circle est-del-date-history" data-id="{{$order->id}}"  aria-hidden="true"></i>
       
              </td>
              <td>
                <?php 
                   $totalBrands = explode(",",$order->brand_name_list);
                    if(count($totalBrands) > 1) {
                      $str = 'Multi';
                    }
                    else {
                      $str = $order->brand_name_list;
                    }
                ?>
                <span style="font-size:14px;">{{$str}}</span>
              </td>
              <td class="expand-row table-hover-cell">
                <div class="form-group" style="margin-bottom:0px;">
                  <select data-placeholder="Order Status" class="form-control order-status-select" id="supplier" data-id={{$order->id}} >
                            <optgroup label="Order Status">
                              <option value="">Select Order Status</option>
                                @foreach ($order_status_list as $id => $status)
                                    <option value="{{ $id }}" {{ $order->order_status_id == $id ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </optgroup>
                    </select>
                </div>
              </td>
              <td>{{ $order->advance_detail }}</td>
              <td>{{ $order->balance_amount }}</td>
              {{-- <td></td>
              <td></td> --}}
              {{-- <td>{{ $order->action->status }}</td>
              <td>{{ $order->action->completion_date ? Carbon\Carbon::parse($order->action->completion_date)->format('d-m') : '' }}</td> --}}
              <td>
                @if ($order->waybill)
                  {{ $order->waybill->awb }}
                @else
                  -
                @endif
              </td>
              <td>{{$orderProductPrice * $productQty}}</td>
              <td>{{$duty_shipping[$order->id]['shipping']}}</td>
              <td>{{$duty_shipping[$order->id]['duty']}}</td>
              <td>
                <div class="d-flex">
                  <a class="btn btn-image pd-5 btn-ht" href="{{route('purchase.grid')}}?order_id={{$order->id}}">
                    <img title="Purchase Grid" style="display: inline; width: 15px;" src="{{ asset('images/customer-order.png') }}" alt="">
                  </a>
                  <a class="btn btn-image pd-5 btn-ht" href="{{ route('order.show',$order->id) }}"><img title="View order" src="{{asset('images/view.png')}}" /></a>
                  <a class="btn btn-image send-invoice-btn pd-5 btn-ht" data-id="{{ $order->id }}" href="{{ route('order.show',$order->id) }}">
                    <img title="Send Invoice" src="{{asset('images/purchase.png')}}" />
                  </a>
                  <a title="Preview Order" class="btn btn-image preview-invoice-btn pd-5 btn-ht" href="{{ route('order.perview.invoice',$order->id) }}">
                    <i class="fa fa-hourglass"></i>
                  </a>
                  @if ($order->waybill)
                    <a title="Download Package Slip pd-5 btn-ht" href="{{ route('order.download.package-slip', $order->waybill->id) }}" class="btn btn-image" href="javascript:;">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                    <a title="Track Package Slip pd-5 btn-ht" href="javascript:;" data-id="{{ $order->waybill->id }}" data-awb="{{ $order->waybill->awb }}" class="btn btn-image track-package-slip">
                        <i class="fa fa fa-globe" aria-hidden="true"></i>
                    </a>
                  @endif
                  <a title="Generate AWB" data-order-id="<?php echo $order->id; ?>" data-items='<?php echo json_encode($extraProducts); ?>'  data-customer='<?php echo ($order->customer) ? json_encode($order->customer) : json_encode([]); ?>' class="btn btn-image generate-awb pd-5 btn-ht" href="javascript:;"  >
                    <i class="fa fa-truck" aria-hidden="true"></i>
                  </a>

                  <a title="View customer address" data-order-id="<?php echo $order->id; ?>"  class="btn btn-image customer-address-view pd-5 btn-ht" href="javascript:;"  >
                    <i class="fa fa-address-card" aria-hidden="true"></i>
                  </a>
                  {{-- @can('order-edit')
                  <a class="btn btn-image pd-5 btn-ht" href="{{ route('order.edit',$order['id']) }}"><img src="{{asset('images/edit.png')}}" /></a>
                  @endcan --}}

                  {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline;margin-bottom:0px;height:30px;']) !!}
                  <button type="submit" class="btn btn-image pd-5 btn-ht"><img title="Archive Order" src="{{asset('images/archive.png')}}" /></button>
                  {!! Form::close() !!}
                  <?php
                  if($order->auto_emailed)
                  {
                    $title_msg = "Resend Email";
                  }
                  else
                  {
                    $title_msg = "Send Email"; 
                  }
                  ?>
                  <a title="<?php echo $title_msg;?>" class="btn btn-image send-order-email-btn pd-5 btn-ht" data-id="{{ $order->id }}" href="javascript:;">
                      <i class="fa fa-paper-plane" aria-hidden="true"></i>
                  </a>
                  @if(auth()->user()->checkPermission('order-delete'))
                    {!! Form::open(['method' => 'DELETE','route' => ['order.permanentDelete', $order->id],'style'=>'display:inline;margin-bottom:0px;height:30px;']) !!}
                    <button type="submit" class="btn btn-image pd-5 btn-ht"><img title="Delete Order" src="{{asset('images/delete.png')}}" /></button>
                    {!! Form::close() !!}
                  @endif
                  @if(!$order->invoice_id)
                <a title="Add invoice" class="btn btn-image add-invoice-btn pd-5 btn-ht" data-id='{{$order->id}}'>
                     +
                </a>
                @endif
                <a title="Return / Exchange" data-id="{{$order->id}}" class="btn btn-image quick_return_exchange pd-5 btn-ht">
                     <i class="fa fa-product-hunt" aria-hidden="true"></i>
                </a>
                <button type="button" class="btn send-email-common-btn" data-toemail="{{$order->cust_email}}" data-object="order" data-id="{{$order->customer_id}}"><i class="fa fa-envelope-square"></i></button>
                <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="order" data-id="{{$order->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="{{asset('images/chat.png')}}" alt=""></button>
                @if($order->cust_email)
                <a class="btn btn-image pd-5 btn-ht" href="{{route('order.generate.order-mail.pdf', ['order_id' => $order->id])}}">
                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i>      
                </a>
                @endif

                @if($order->invoice_id)
                <a title="Download Invoice" class="btn btn-image" href="{{ route('order.download.invoice',$order->invoice_id) }}">
                  <i class="fa fa-download"></i>
               </a>
                @endif
                </div>
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

    <div id="order-status-map" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Magento Order Status Mapping</h4>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th style="width: 20%;">Status</th>
                          <th style="width: 20%;">Magento Status</th>
                          <th>Message Text Template</th>
                        </tr>
                      </thead>

                      <tbody>
                       @foreach($orderStatusList as $orderStatus)
                          <tr>
                            <td>{{ $orderStatus->id }}</td>
                            <td>{{ $orderStatus->status }}</td>
                            <td><input type="text" value="{{ $orderStatus->magento_status }}" class="form-control" onfocusout="updateStatus({{ $orderStatus->id }})" id="status{{ $orderStatus->id }}"></td>
                            <td>
                              <textarea class="form-control message-text-tpl" name="message_text_tpl">{{ !empty($orderStatus->message_text_tpl) ? $orderStatus->message_text_tpl : \App\Order::ORDER_STATUS_TEMPLATE }}</textarea>
                              <button type="button" class="btn btn-image edit-vendor" onclick="updateStatus({{ $orderStatus->id }})"><i class="fa fa-arrow-circle-right fa-lg"></i></button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

   <div id="updateCustomer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
      <div class="modal-header">
                    <h4 class="modal-title">Update Customers</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="customerUpdateForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                      <select data-placeholder="Order Status" name="order_status" class="form-control select2" >
                                              <optgroup label="Order Status">
                                                <option value="">Select Order Status</option>
                                                  @foreach ($order_status_list as $id => $status)
                                                      <option value="{{ $id }}" {{ (isset($order->order_status_id) && $order->order_status_id == $id) ? 'selected' : '' }}>{{ $status }}</option>
                                                  @endforeach
                                              </optgroup>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Add New Reply:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <input type="text" class="addnewreply" placeholder="add new reply">
                                  <button class="btn btn-secondary addnewreplybtn">+</button>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Quick Reply:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <select class="quickreply">
                                  <option value="">Select quick reply</option>
                                  @if($quickreply)
                                    @foreach($quickreply as $quickrep)
                                      <option value="{{$quickrep->id}}">{{$quickrep->reply}}</option>
                                    @endforeach
                                 @endif
                                </select>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Message:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <textarea cols="45" class="form-control" name="customer_message"></textarea>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Update type:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <select name="update_type" class="form-control">
                                    <option value="1">Only send message</option>
                                    <option value="2">Send message and update status</option>
                                  </select>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </form>
      </div>
    </div>
</div>

<div id="update-status-message-tpl" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div class="modal-header">
            <h4 class="modal-title">Change Status</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="" id="update-status-message-tpl-frm" method="POST">
            @csrf
            <input type="hidden" name="order_id" id="order-id-status-tpl" value="">
            <input type="hidden" name="order_status_id" id="order-status-id-status-tpl" value="">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <strong>Message:</strong>
                        </div>
                        <div class="col-md-8">
                        <div class="form-group">
                          <textarea cols="45" class="form-control" id="order-template-status-tpl" name="message"></textarea>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary update-status-with-message">With Message</button>
                <button type="button" class="btn btn-secondary update-status-without-message">Without Message</button>
            </div>
        </form>
      </div>
    </div>
</div>


<div id="estdelhistoryresponse"></div>
@endsection
@include('common.commonEmailModal')
@include("partials.modals.update-delivery-date-modal")
@include("partials.modals.tracking-event-modal")
@include("partials.modals.generate-awb-modal")
@include("partials.modals.customer-address-modal")
@include("partials.modals.add-invoice-modal")
@include('partials.modals.return-exchange-modal')
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="{{ asset('/js/order-awb.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="{{asset('js/common-email-send.js')}}">//js for common mail</script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript">
    $(document).on('click','.magento-order-status',function(event){ 
      event.preventDefault();
      $('#order-status-map').modal('show');
    });
    $(document).on("click",".toggle-title-box",function(ele) {
        var $this = $(this);
        if($this.hasClass("has-small")){
            $this.html($this.data("full-title"));
            $this.removeClass("has-small")
        }else{
            $this.addClass("has-small")
            $this.html($this.data("small-title"));
        }
    });

    $(document).ready(function() {
      $('#order-datetime').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#newdeldate').datetimepicker({
        minDate:new Date(),
        format: 'YYYY-MM-DD'
      });

      $(document).on("click",".btn-add-items",function(e) {
          var index = $("#generateAWBMODAL").find(".product-items-list").find(".card-body").length;
          var next  = index+1;
          var itemsHtml = `<div class="card-body">
                <div class="form-group col-md-5">
                   <strong>Name:</strong>
                   <input type="text" id="name" name="items[`+next+`][name]" class="form-control" value="">
                </div>
                <div class="form-group col-md-3">
                   <strong>Qty:</strong>
                   <input type="text" id="qty" name="items[`+next+`][qty]" class="form-control" value="">
                </div>
                <div class="form-group col-md-3">
                   <strong>Unit Price:</strong>
                   <input type="text" id="unit_price" name="items[`+next+`][unit_price]" class="form-control" value="">
                </div>
                <div class="form-group col-md-3">
                   <strong>Net Weight:</strong>
                   <input type="text" id="net_weight" name="items[`+next+`][net_weight]" class="form-control" value="1">
                </div>
                <div class="form-group col-md-3">
                   <strong>Gross Weight:</strong>
                   <input type="text" id="gross_weight" name="items[`+next+`][gross_weight]" class="form-control" value="1">
                </div>
                <div class="form-group col-md-3">
                   <strong>HS Code:</strong>
                   <input type="text" id="hs_code" name="items[`+next+`][hs_code]" class="form-control" value="">
                </div>
                <div class="form-group col-md-5">
                   <strong>Manufacturing Country Code:</strong>
                   <input type="text" id="manufacturing_country_code" name="items[`+next+`][manufacturing_country_code]" class="form-control" value="">
                </div>
                <div class="form-group col-md-1" style="margin-top:20px;">
                   <button class="btn btn-secondary btn-remove-item"><i class="fa fa-trash"></i></button>
                </div>
            </div>`;
            $("#generateAWBMODAL").find(".product-items-list").append(itemsHtml);

      });

      $(document).on("click",".btn-remove-item",function(){
          $(this).closest(".card-body").remove();
      });
      
      	$(document).on("click",".customer-address-view",function() {
			  console.log(this);
			var order_id = $(this).data("order-id");
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: "order/get-customer-address",
				type: "post",
				data : { order_id: order_id },
				beforeSend: function() {
					$("loading-image").show();
				}
			}).done( function(response) {
				if(response.code == 200) {
					 var t = '';
					$.each(response.data,function(k,v) {
						t += `<tr><td>`+v.address_type+`</td>`;
						t += `<td>`+v.city+`</td>`;
						t += `<td>`+v.country_id+`</td>`;
						t += `<td>`+v.email+`</td>`;
						t += `<td>`+v.firstname+`</td>`;
						t += `<td>`+v.lastname+`</td>`;
						t += `<td>`+v.postcode+`</td>`;
						t += `<td>`+v.street+`</td>`;
						t += `<td>`+v.telephone+`</td></tr>`;
					});

					$("#customer-address-modal").find(".show-list-records").html(t);
					$('#customer-address-modal').modal("show");
					$("loading-image").hide();
				}
				
			}).fail(function(errObj) {
				alert("Could not find any data");
			});
		});

      $(document).on("click",".generate-awb",function() {
          var customer = $(this).data("customer");
          var order_id = $(this).data("order-id");
          var items    = $(this).data("items");

            if(typeof customer != "undefined" || customer != "") {
               /* $(".input_customer_name").val(customer.name);
               $(".input_customer_phone").val(customer.phone);
               $(".input_customer_address1").val(customer.address);
               $(".input_customer_address2").val(customer.city);
               $(".input_customer_city").val(customer.city);
               $(".input_customer_pincode").val(customer.pincode); */
               $("#customer_name").val(customer.name);
               $("#customer_phone").val(customer.phone);
               $("#customer_address1").val(customer.address.substring(0, 44));
               $("#customer_address2").val(customer.city);
               $("#customer_city").val(customer.city);
               $("#customer_pincode").val(customer.pincode);
               $("#customer_email").val(customer.email);
            }

            if(items.length > 0) {
              var itemsHtml = '';
              $.each(items, function(k,v) {
                  itemsHtml += `<div class="card-body">
                              <div class="form-group col-md-5">
                                 <strong>Name:</strong>
                                 <input type="text" id="name" name="items[`+k+`][name]" class="form-control" value="`+v.name+`">
                              </div>
                              <div class="form-group col-md-3">
                                 <strong>Qty:</strong>
                                 <input type="text" id="qty" name="items[`+k+`][qty]" class="form-control" value="`+v.qty+`">
                              </div>
                              <div class="form-group col-md-3">
                                 <strong>Unit Price:</strong>
                                 <input type="text" id="unit_price" name="items[`+k+`][unit_price]" class="form-control" value="`+v.product_price+`">
                              </div>
                              <div class="form-group col-md-3">
                                 <strong>Net Weight:</strong>
                                 <input type="text" id="net_weight" name="items[`+k+`][net_weight]" class="form-control" value="1">
                              </div>
                              <div class="form-group col-md-3">
                                 <strong>Gross Weight:</strong>
                                 <input type="text" id="gross_weight" name="items[`+k+`][gross_weight]" class="form-control" value="1">
                              </div>
                              <div class="form-group col-md-3">
                                 <strong>HS Code:</strong>
                                 <input type="text" id="hs_code" name="items[`+k+`][hs_code]" class="form-control" value="">
                              </div>
                              <div class="form-group col-md-5">
                                 <strong>Manufacturing Country Code:</strong>
                                 <input type="text" id="manufacturing_country_code" name="items[`+k+`][manufacturing_country_code]" class="form-control" value="">
                              </div>
                              <div class="form-group col-md-1" style="margin-top:20px;">
                                 <button class="btn btn-secondary btn-remove-item"><i class="fa fa-trash"></i></button>
                              </div>
                          </div>`;
              });
              
              $("#generateAWBMODAL").find(".product-items-list").html(itemsHtml);
            }

            $("#generateAWBMODAL").find("[name='order_id']").val(order_id);
            $("#generateAWBMODAL").modal("show");
      });


    function ConfirmDialog(message,id,status) {
      $('<div></div>').appendTo('body')
        .html('<div><h5>' + message + '?</h5></div>')
        .dialog({
          modal: true,
          title: 'Confirm Send',
          zIndex: 10000,
          autoOpen: true,
          width: 'auto',
          resizable: false,
          buttons: {
            Yes: function() {
              $.ajax({
                url: "/order/change-status",
                type: "GET",
                async : false,
                data : {
                  id : id,
                  status : status,
                  sendmessage:'1',
                }
              }).done( function(response) {
              
              }).fail(function(errObj) {
                alert("Could not change status");
              });    
            },
            No: function() {
              $.ajax({
              url: "/order/change-status",
              type: "GET",
              async : false,
              data : {
                id : id,
                status : status
              }
            }).done( function(response) {
              return true;
            }).fail(function(errObj) {
              alert("Could not change status");
            });
            }
          },
          close: function(event, ui) {
            $(this).remove();
          }
        });
    };
      /*$(document).on("change",".order-status-select",function() {
          var id = $(this).data("id");
          var status = $(this).val();
          var message = 'Do you want to send message to customer for status change';
          ConfirmDialog(message,id,status);
      });*/

      $(document).on("change",".order-status-select",function() {
          var id = $(this).data("id");
          var status = $(this).val();

          $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "order/"+id+"/change-status-template",
            type: "post",
            data : {
              order_id: id, 
              order_status_id : status
            },
            beforeSend: function() {
              $("loading-image").show();
            }
          }).done( function(response) {
            $("loading-image").hide();
            if(response.code == 200) {
              $("#order-id-status-tpl").val(id);
              $("#order-status-id-status-tpl").val(status);
              $("#order-template-status-tpl").val(response.template);
              $("#update-status-message-tpl").modal("show");
            }
            
          }).fail(function(errObj) {
              alert("Could not change status");
          });
      });

      $(document).on("click",".update-status-with-message",function(e) {
          e.preventDefault();
          $.ajax({
            url: "/order/change-status",
            type: "GET",
            async : false,
            data : {
              id : $("#order-id-status-tpl").val(),
              status : $("#order-status-id-status-tpl").val(),
              sendmessage:'1',
              message:$("#order-template-status-tpl").val(),
            }
          }).done( function(response) {
              $("#update-status-message-tpl").modal("hide");
          }).fail(function(errObj) {
            alert("Could not change status");
          });
      });

      $(document).on("click",".update-status-without-message",function() {
          e.preventDefault();
          $.ajax({
            url: "/order/change-status",
            type: "GET",
            async : false,
            data : {
              id : $("#order-id-status-tpl").val(),
              status : $("#order-status-id-status-tpl").val(),
              sendmessage:'0',
              message:$("#order-template-status-tpl").html(),
            }
          }).done( function(response) {
            $("#update-status-message-tpl").modal("hide");
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
        $(document).on('click','.addnewreplybtn',function(e){
          e.preventDefault();
          var replybox  = $(this).parentsUntil('#customerUpdateForm').find('.addnewreply');
          var selectreplybox = $(this).parentsUntil('#customerUpdateForm').find('.quickreply');
          var reply = $(this).parentsUntil('#customerUpdateForm').find('.addnewreply').val();
          if(!reply){
            alert('please add reply to input box !');
            return false;
          }
          $.ajax({
                type: "POST",
                url: "{{route('order.addNewReply')}}",
                data: {reply:reply},
                headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:"json",
                beforeSend:function(data){
                  $('.ajax-loader').show();
                }
            }).done(function (response) {
              $('.ajax-loader').hide();
              if(response.status==200){
                replybox.val('');
                selectreplybox.html(response.html);
              }
            }).fail(function (response) {
              $('.ajax-loader').hide();
                console.log(response);
            });
          
        })
        $('.quickreply').on('change',function(){
          var reply = $(this).find('option:selected').text();
          var replyval = $(this).find('option:selected').attr('value');
          if(replyval!=''){
            $(this).parentsUntil('#customerUpdateForm').find('textarea[name="customer_message"]').val(reply);
          }else{
            $(this).parentsUntil('#customerUpdateForm').find('textarea[name="customer_message"]').val('');
          }
        });
        
        $('[data-fancybox="gallery"]').fancybox({
            // Options will go here
          });

          $('#swtichForm').on('click',function(e){
    
        var from_customer_id=$("#from_customer_name");
        var from_customer_city=$("#from_customer_city");
        var from_customer_country=$("#from_customer_country");
        var from_customer_phone=$("#from_customer_phone");
        var from_customer_address1=$("#from_customer_address1");
        var from_customer_address2=$("#from_customer_address2");
        var from_customer_pincode=$("#from_customer_pincode");
        var from_company_name=$("#from_company_name");
        /* var from_actual_weight=$("#from_actual_weight");
        var from_box_length=$("#from_box_length");
        var from_box_width=$("#from_box_width");
        var from_box_height=$("#from_box_height");
        var from_amount=$("#from_amount");
        var from_currency=$("#from_currency");
        var from_pickup_time=$("#from_pickup_time");
        var from_service_type=$("#from_service_type"); */
        //"TO" section
        var customer_id=$("#customer_name");
        var customer_city=$("#customer_city");
        var customer_country=$("#customer_country");
        var customer_phone=$("#customer_phone");
        var customer_address1=$("#customer_address1");
        var customer_address2=$("#customer_address2");
        var customer_pincode=$("#customer_pincode");
        var company_name=$("#company_name");
        var actual_weight=$("#actual_weight");
        var box_length=$("#box_length");
        var box_width=$("#box_width");
        var box_height=$("#box_height");
        var amount=$("#amount");
        var currency=$("#currency");
        var pickup_time=$("#pickup_time");
        var service_type=$("#service_type");

        var pre_from_customer_id=from_customer_id.val();
        from_customer_id.val(customer_id.val());
        customer_id.val(pre_from_customer_id);
       /*  var pre_from_customer_id_name=from_customer_id.attr('name');
        var pre_from_customer_id_id=from_customer_id.attr('name');
        from_customer_id.attr('name',customer_id.attr('name'));
        from_customer_id.attr('id',customer_id.attr('id'));
        customer_id.attr('name',pre_from_customer_id_name);
        customer_id.attr('id',pre_from_customer_id_id); */
        
        var pre_from_customer_name=$("#div_from_customer_name").html();
        $("#div_from_customer_name").html($("#div_to_customer_name").html());
        $("#div_to_customer_name").html(pre_from_customer_name);
        
        var pre_from_customer_city=from_customer_city.val();
        from_customer_city.val(customer_city.val());
        customer_city.val(pre_from_customer_city);

        var pre_from_customer_country=from_customer_country.val();
        from_customer_country.val(customer_country.val());
        customer_country.val(pre_from_customer_country);

        var pre_from_customer_phone=from_customer_phone.val();
        from_customer_phone.val(customer_phone.val());
        customer_phone.val(pre_from_customer_phone);

        var pre_from_customer_address1=from_customer_address1.val();
        from_customer_address1.val(customer_address1.val());
        customer_address1.val(pre_from_customer_address1);

        var pre_from_customer_address2=from_customer_address2.val();
        from_customer_address2.val(customer_address2.val());
        customer_address2.val(pre_from_customer_address2);

        var pre_from_customer_pincode=from_customer_pincode.val();
        from_customer_pincode.val(customer_pincode.val());
        customer_pincode.val(pre_from_customer_pincode);

        var pre_from_company_name=from_company_name.val();
        from_company_name.val(company_name.val());
        company_name.val(pre_from_company_name);

        /* var pre_from_actual_weight=from_actual_weight.val();
        from_actual_weight.val(actual_weight.val());
        actual_weight.val(pre_from_actual_weight);

        var pre_from_box_length=from_box_length.val();
        from_box_length.val(box_length.val());
        box_length.val(pre_from_box_length);

        var pre_from_box_width=from_box_width.val();
        from_box_width.val(box_width.val());
        box_width.val(pre_from_box_width);

        var pre_from_box_height=from_box_height.val();
        from_box_height.val(box_height.val());
        box_height.val(pre_from_box_height);
        
        var pre_from_amount=from_amount.val();
        from_amount.val(amount.val());
        amount.val(pre_from_amount);

        var pre_from_currency=from_currency.val();
        from_currency.val(currency.val());
        currency.val(pre_from_currency);
        
        var pre_from_pickup_time=from_pickup_time.val();
        from_pickup_time.val(pickup_time.val());
        pickup_time.val(pre_from_pickup_time);

        var pre_from_service_type=from_service_type.val();
        from_service_type.val(service_type.val());
        service_type.val(pre_from_service_type); */
    
    });
  </script>
@endsection
