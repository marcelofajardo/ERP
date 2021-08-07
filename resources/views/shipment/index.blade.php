@extends('layouts.app')

@section('title', 'Shipment List')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!-- Custom CSS 02/02/2021  -->
<style>
.table-res tbody td:last-child{display: flex; align-items: center; justify-content: flex-start; width: 100%; padding: 20px 5px; border-bottom: 0; border-left:0; } 
.table-res tbody tr:last-child td {border-bottom: 1px solid #ddd; } 
.table-res tbody tr:first-child td {border-top:0; } </style>
@endsection

@section('content')
@if(session()->has('success'))
    <div class="col-lg-12 alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('errors'))
    @if(is_array(session()->get('errors'))) 
        @foreach(session()->get('errors') as $err)
            <div class="col-lg-12 alert alert-danger">
                {{ $err }}
            </div>
        @endforeach
    @else
        <div class="col-lg-12 alert alert-danger">
            {{ session()->get('errors') }}
        </div>
    @endif
@endif
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Shipment List</h2>
    </div>
</div>
<div class="infinite-scroll">
    <div class="row col-md-12">
        
    </div>
    <form method="get" action="">
        <div class="row col-md-9">
             <div class="form-group">
                <input type="text" class="form-control" placeholder="AWB" name="awb" value="{{ @$_REQUEST['awb'] }}"/>
            </div>
            <div class="form-group ml-2">
                <input type="text" class="form-control" placeholder="Destination" name="destination" value="{{ @$_REQUEST['destination'] }}"/>
            </div>
            <div class="form-group ml-2">
                <input type="text" class="form-control" placeholder="Consignee" name="consignee" value="{{ @$_REQUEST['consignee'] }}"/>
            </div>
            <div class="form-group ml-2">
                <input type="text" class="form-control" placeholder="Order no" name="order_id" value="{{ @$_REQUEST['order_id'] }}"/>
            </div>
            <div class="form-group ml-2">
                <button class="btn btn-image">
                    <img src="https://erp.theluxuryunlimited.com/images/search.png" alt="Search" style="cursor: default;">
                </button>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary generate-awb">+</button>
        </div>
    </form>

	<div class="table-responsive mt-3 table-res">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>AWB</th>
            <th>Order</th>
            <th>Customer name</th>
            <th>Destination</th>
            <th>Shipped Date</th>
            <th>Current Status</th>
            <th>Weight of Shipment</th>
            <th>Cost of Shipment</th>
            <th>Duty Cost</th>
            <th>Invoice Number</th>
            <th>Invoice</th>
            <th>Due Date</th>
            <th>Paid Date</th>
            <th>Location</th>
            <th style="width: 120px">Action</th>
          </tr>
        </thead>

        <tbody>
            @forelse ($waybills_array as $key => $item)
                <tr>
                    <td>{{ @$item->awb }}</td>
                    <td>{{ @$item->order->id }}</td>
                    <td>{{ @$item->to_customer_name}}</td>
                    <td>{{ @$item->to_customer_address_1 }}</td>
                    <td>{{ ($item->created_at) ? date('d-m-Y', strtotime($item->created_at)) : '' }}</td>
                    <td>{{ @$item->order->order_status }}</td>
                    <td>{{ @$item->actual_weight }}</td>
                    <td>{{ @$item->cost_of_shipment?? 'N/A' }}</td>
                    <td>{{ @$item->duty_cost?? 'N/A' }}</td>
                    <td>{{ @$item->invoice_number?? 'N/A' }}</td>
                    <td>{{ @$item->invoice_amount ? $item->currency.$item->invoice_amount : 'N/A' }}</td>
                    <td>{{ @$item->due_date?? 'N/A' }}</td>
                    <td>{{ $item->paid_date ? date('d-m-Y', strtotime($item->paid_date)) :  'N/A' }}</td>

                    <td>{{ (@$item->waybill_track_histories->count() > 0)? @$item->waybill_track_histories->last()->location : "" }}</td>
                    <td>
                        <button type="button" class="btn btn-image" id="send_email_btn" data-order-id="{{ $item->order_id }}" title="Send Email"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                        <a class="btn" href="javascript:void(0);" id="view_mail_btn" title="View communication sent" data-order-id="{{ $item->order_id }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a class="btn" href="javascript:void(0);" id="create_pickup_request_btn" title="Create pickup request" data-waybillid="{{ $item->id }}">
                            <i class="fa fa-truck" aria-hidden="true"></i>
                        </a>
                        <a class="btn" href="javascript:void(0);" id="waybill_track_history_btn" title="Way Bill Track History" data-waybill-id="{{ $item->id }}">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                        <a class="btn" href="{{ route('order.download.package-slip', $item->id) }}" class="btn-link"><i class="fa fa-download" aria-hidden="true"></i></a>
                        <button data-editbox='{{ json_encode([
                        "box_length" => $item->box_length,
                        "box_width" => $item->box_width,
                        "box_height" => $item->box_height,
                        "actual_weight" => $item->actual_weight,
                        "volume_weight" => $item->volume_weight,
                        "id" => $item->id
                        ]) }}' type="button" class="btn btn-image edit-box-shipment edit-box-shipment-{{$item->id}}"><i class="fa fa-th" aria-hidden="true"></i></button>
                        <a class="btn btn-edit-shipment" href="javascript:void(0);" title="Edit icon" data-waybill-id="{{ $item->id }}">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        @if($item->invoice_number && $item->cost_of_shipment)
                            <a class="btn btn-payment" href="javascript:void(0);" title="Payment icon" data-waybill-id="{{ $item->id }}">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr></tr>
            @endforelse
        </tbody>
      </table>

	{!! $waybills_array->appends(Request::except('page'))->links() !!}
	</div>
</div>

<div id="box-update-partial" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Partial Box</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="shipment_id" id="shipment_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="box_length" class="col-form-label">Box length:</label>
                                    <input type="text" name="box_length" id="box_length" class="form-control" placeholder="Enter Box length">
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="box_width" class="col-form-label">Box Width:</label>
                                    <input type="text" name="box_width" id="box_width" class="form-control" placeholder="Enter Box Width">
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="box_height" class="col-form-label">Box height:</label>
                                    <input type="text" name="box_height" id="box_height" class="form-control" placeholder="Enter Box height">
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="actual_weight" class="col-form-label">Actual weight:</label>
                                    <input type="text" name="actual_weight" id="actual_weight" class="form-control" placeholder="Enter actual weight">
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="volume_weight" class="col-form-label">Volume weight:</label>
                                    <input type="text" name="volume_weight" id="volume_weight" class="form-control" placeholder="Enter volume weight">
                                 </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary btn-save-box-size">Save</button>
            </div>
        </div>
    </div>
</div>

<div id="edit-shipment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Shipement</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary btn-save-shipment">Save</button>
            </div>
        </div>
    </div>
</div>


<div id="payment-shipment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Payment For Shipement</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href class="btn btn-secondary btn-save-shipment-payment-info">Save</a>
            </div>
        </div>
    </div>
</div>

@include("partials.modals.generate-awb-modal")
@include('shipment.partial.modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script src="{{ asset('/js/order-awb.js') }}"></script>
<script type="text/javascript">
$(".to-email, .cc-email, .bcc-email").select2({
    tags: true,
    tokenSeparators: [',', ' '],
    placeholder: {
        id: '-1', // the value of the option
        text: 'Type Email'
    },
    allowClear: true,
    "language": {
       "noResults": function(){
           return "Type email";
       }
    },
    escapeMarkup: function (markup) {
        return markup;
    }
});

$(document).on("click",".generate-awb",function(e) {
      var customer = $(this).data("customer");

        if(typeof customer != "undefined" && customer != "") {
           /* $(".input_customer_name").val(customer.name);
           $(".input_customer_phone").val(customer.phone);
           $(".input_customer_address1").val(customer.address);
           $(".input_customer_address2").val(customer.city);
           $(".input_customer_city").val(customer.city);
           $(".input_customer_pincode").val(customer.pincode); */
           $("#customer_name").val(customer.name);
           $("#customer_phone").val(customer.phone);
           $("#customer_address1").val(customer.address);
           $("#customer_address2").val(customer.city);
           $("#customer_city").val(customer.city);
           $("#customer_pincode").val(customer.pincode);
        }
        $("#generateAWBMODAL").modal("show");
        e.preventDefault();
  });

$(document).on('click', '#view_mail_btn', function() {
    var orderId = $(this).data('order-id');
    $.ajax({
        url: "{{ route('shipment/view/sent/email') }}",
        type: 'GET',
        data: {'order_id': orderId},
        success: function(data) {
            $("#view_email_body").html(data);
            $('#view_sent_email_modal').modal('show');
        }
    });
});

$(document).on('click','#create_pickup_request_btn',function(){
    $("#waybill_id").val($(this).data('waybillid'));
    $("#pickup_request").modal("show");
});

$(document).on('click', '#waybill_track_history_btn', function() {
    var waybillId = $(this).data('waybill-id');
    $.ajax({
        url: "{{ route('shipment/view/sent/email') }}",
        type: 'GET',
        data: {'waybill_id': waybillId},
        success: function(data) {
            $("#view_track_body").html(data);
            $('#view_waybill_track_histories').modal('show');
        }
    });
});

$(document).on('click', '#send_email_btn', function() {
    var orderId = $(this).data('order-id');
    $("#order_id").val(orderId);
    $('#send_email_modal').modal('show');
});

// Fetch Data for shipment details and open popup
$(document).on('click', '.btn-payment', function() {
    var waybillId = $(this).data('waybill-id');
    $.ajax({
        url: "{{ route('shipment.get-payment-info') }}",
        type: 'GET',
        data: {'waybill_id': waybillId},
        success: function(data) {
            console.log(data.data);
            $("#payment-shipment .modal-body").html(data.data);
            $('#payment-shipment').modal('show');
        }
    });
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
            <div class="form-group col-md-1">
               <button class="btn btn-secondary btn-remove-item"><i class="fa fa-trash"></i></button>
            </div>
        </div>`;
        $("#generateAWBMODAL").find(".product-items-list").append(itemsHtml);

  });

  $(document).on("click",".btn-remove-item",function(){
      $(this).closest(".card-body").remove();
  });

$(document).on("change","#customer_id",function() {
    var cus_id = $(this).val();
    if(cus_id == ''){
        /* $('.input_customer_city').val('');
        $('.input_customer_phone').val('');
        $('.input_customer_address1').val('');
        $('.input_customer_pincode').val(''); */
        $('#customer_city').val('');
        $('#customer_phone').val('');
        $('#customer_address1').val('');
        $('#customer_address2').val('');
        $('#customer_pincode').val('');
    }
    $.ajax({
        url: "{{ url('shipment/customer-details') }}"+'/'+cus_id,
        type: "GET"
    }).done( function(response) {
        if(response.status == 1)
        {
            /* $('.input_customer_city').val(response.data.city);
            let countryField = $('.input_customer_country'); */
            $('#customer_city').val(response.data.city);
            let countryField = $('#customer_city');
            let countryOptionsField = countryField.find('option')
            if (countryOptionsField && countryOptionsField.length){
                for (let i in countryOptionsField){
                    if (countryOptionsField[i].innerText && countryOptionsField[i].innerText.toLowerCase() === response.data.country.toLowerCase()){
                        countryField.val(countryOptionsField[i].value)
                    }
                }
            }
            /* $('.input_customer_phone').val(response.data.phone);
            $('.input_customer_address1').val(response.data.address);
            $('.input_customer_pincode').val(response.data.pincode); */
            $('#customer_phone').val(response.data.phone);
            $('#customer_address1').val(response.data.address);
            $('#customer_pincode').val(response.data.pincode);
        }
    })
});

$(document).on("change","#from_customer_id",function() {
    var cus_id = $(this).val();
    if(cus_id == ''){
        /* $('.input_customer_city').val('');
        $('.input_customer_phone').val('');
        $('.input_customer_address1').val('');
        $('.input_customer_pincode').val(''); */
        $('#from_customer_city').val('');
        $('#from_customer_phone').val('');
        $('#from_customer_address1').val('');
        $('#from_customer_address2').val('');
        $('#from_customer_pincode').val('');
    }
    $.ajax({
        url: "{{ url('shipment/customer-details') }}"+'/'+cus_id,
        type: "GET"
    }).done( function(response) {
        if(response.status == 1)
        {
            /* $('.input_customer_city').val(response.data.city);
            let countryField = $('.input_customer_country'); */
            $('#from_customer_city').val(response.data.city);
            let countryField = $('#from_customer_city');
            let countryOptionsField = countryField.find('option')
            if (countryOptionsField && countryOptionsField.length){
                for (let i in countryOptionsField){
                    if (countryOptionsField[i].innerText && countryOptionsField[i].innerText.toLowerCase() === response.data.country.toLowerCase()){
                        countryField.val(countryOptionsField[i].value)
                    }
                }
            }
            /* $('.input_customer_phone').val(response.data.phone);
            $('.input_customer_address1').val(response.data.address);
            $('.input_customer_pincode').val(response.data.pincode); */
            $('#from_customer_phone').val(response.data.phone);
            $('#from_customer_address1').val(response.data.address);
            $('#from_customer_pincode').val(response.data.pincode);
        }
    })
});

$(document).on("change", '#email_name', function(){
   var template_name = $(this).val();
    $.ajax({
        url: "{{ url('shipment/get-templates-by-name/') }}"+'/'+template_name,
        type: "GET"
    }).done( function(response) {
        if(response.status == 1)
        {
            $('#templates').empty();
            for(var i = 0; i <response.data.length; i++){
                $('#templates').append('<option value="'+response.data[i]['id']+'">'+response.data[i]['mail_tpl']+'</option>');
            }
        }
    })
});
$(document).on("click", '.add-shipment', function(){
    $('#generate-shipment-form .form-error').html(''),
    $('.any-message').html('');
});

$(document).on("click",".edit-box-shipment",function(ele) {
    var $this = $(this);
    var records = $this.data("editbox");

    var body = $("#box-update-partial").find(".modal-body");
    
        body.find("#box_length").val(records.box_length);
    body.find("#box_width").val(records.box_width);
    body.find("#box_height").val(records.box_height);
    body.find("#actual_weight").val(records.actual_weight);
    body.find("#volume_weight").val(records.volume_weight);
    body.find("#shipment_id").val(records.id);

    $("#box-update-partial").modal("show");
});

$(document).on("click",".btn-save-box-size",function(e){
    e.preventDefault();
    var $this = $(this);
    var form = $("#box-update-partial").find("form");
    $.ajax({
        url: "{{ url('shipment/save-box-size') }}",
        type: "POST",
        data: form.serialize()
    }).done( function(response) {
        if(response.code == 200) {
            $(".edit-box-shipment-"+response.data.id).attr("data-editbox", JSON.stringify(response.data));
            toastr['success'](response.message, 'success');
            location.reload();
        }
        $("#box-update-partial").modal("hide");
    })
});

$(document).on("click",".btn-edit-shipment",function(e){
    e.preventDefault();
    var id = $(this).data("waybill-id");
    $.ajax({
        url: "/shipment/"+id+"/edit",
        type: "GET"
    }).done( function(response) {
         if(response.code == 200) {
            $("#edit-shipment").find(".modal-body").html(response.data.html);
            $("#edit-shipment").modal("show");
         }
    });
});

$(document).on("click",".btn-save-shipment",function(e){
    e.preventDefault();
    $this = $(this);
    var form = $("#edit-shipment").find("form");
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
    }).done( function(response) {
         if(response.code == 200) {
            toastr['success']('data updated successfully!');
            location.reload();
         }else{
            toastr['error']('Request Failed!');
            console.log("Response",response);
         }
    });
});

// Submit Shipment payment form
$(document).on("click",".btn-save-shipment-payment-info",function(e){
    e.preventDefault();
    $this = $(this);
    var form = $("#payment-shipment").find("form");
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
    }).done( function(response) {
         if(response.code == 200) {
            toastr['success']('data updated successfully!');
            location.reload();
         }else{
            toastr['error']('Request Failed!');
            console.log("Response",response);
         }
    });
});

</script>
@endsection