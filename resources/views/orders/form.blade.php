@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit Order' : 'Create Order' }}</h2>
            </div>
            <div class="pull-right">
            <br>
                <a class="btn btn-secondary" href="{{ route('order.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
<br>
    <form id="createOrderForm" name="createOrderForm" action="{{ $modify ? route('order.update',$id) : route('order.store')  }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($modify)
            @method('PUT')
        @endif
        <div class="row">
            <div class="col">
                <input type="hidden" name="key" value="{{ request('key') }}">
                <div class="form-group">
                     <strong>Client:</strong>
                     <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" id="customer_id" title="Choose a Customer" required>
                       @foreach ($customers as $customer)
                        <option <?php echo isset($defaultSelected["customer_id"]) && $defaultSelected["customer_id"] == $customer->id  ? "selected=selected"  : "";  ?>
                        data-tokens="{{ $customer->name }} {{ $customer->email }}  {{ $customer->phone }} {{ $customer->instahandler }}" data-credit="{{$customer->credit}}" value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                      @endforeach
                    </select>

                     @if ($errors->has('customer_id'))
                         <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
                     @endif
                 </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong> Order Type :</strong>
                    <?php

                    $order_types = [
                        'offline' => 'offline',
                        'online' => 'online'
                    ];

                    if(isset($defaultSelected["order_typer"])) {
                        $order_type = $defaultSelected["order_typer"];
                    }

                    echo Form::select('order_type',$order_types, ( old('order_type') ? old('order_type') : $order_type ), ['class' => 'form-control']);?>
                    @if ($errors->has('order_type'))
                        <div class="alert alert-danger">{{$errors->first('order_type')}}</div>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong>Order Date:</strong>
                    <?php
                        if(isset($defaultSelected["order_date"])) {
                            $order_date = $defaultSelected["order_date"];
                        }
                        else {
                            $order_date = date('Y-m-d');
                        }
                    ?>
                    <input type="date" class="form-control datepicker-block" name="order_date" placeholder="Order Date"
                           value="{{ old('order_date') ? old('order_date') : $order_date }}"/>
                    @if ($errors->has('order_date'))
                        <div class="alert alert-danger">{{$errors->first('order_date')}}</div>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong>Date of Delivery:</strong>
                    <?php
                        if(isset($defaultSelected["date_of_delivery"])) {
                            $date_of_delivery = $defaultSelected["date_of_delivery"];
                        }
                    ?>
                    <input type="date" class="form-control datepicker-block" name="date_of_delivery" placeholder="Date of Delivery"
                           value="{{ old('date_of_delivery') ? old('date_of_delivery') : $date_of_delivery }}"/>
                    @if ($errors->has('date_of_delivery'))
                        <div class="alert alert-danger">{{$errors->first('date_of_delivery')}}</div>
                    @endif
                </div>
            </div>
            <div class="col">
                 <div class="form-group">
                    <strong>Size:</strong>
                    <input type="text" class="form-control" name="shoe_size" placeholder="Size" value="{{ old('shoe_size') ? old('shoe_size') : '' }}"/>
                </div>
            </div>    
        </div>  
        <div class="row">
                {{-- @if($modify == 1) --}}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong> Products Attached:</strong>
                        <table class="table table-bordered" id="products-table">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Sku</th>
                                <th>Color</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Size</th>
                                <th style="width: 30px">Qty</th>
                                <th style="width: 160px">Action</th>
                            </tr>
                            @foreach($order_products  as $order_product)
                                <tr>
                                    @if(isset($order_product['product']))
                                        <th><img width="200" src="{{ $order_product['product']['image'] }}" /></th>
                                        <th>{{ $order_product['product']['name'] }}</th>
                                        <th>{{ $order_product['product']['sku'] }}</th>
                                        <th>{{ $order_product['product']['color'] }}</th>
                                        <th>{{ \App\Http\Controllers\BrandController::getBrandName($order_product['product']['brand']) }}</th>
                                    @else
                                        <th></th>
                                        <th></th>
                                        <th>{{$order_product['sku']}}</th>
                                        <th></th>
                                        <th></th>
                                    @endif
                                    <th>
                                        <input class="table-input" type="text" value="{{ $order_product['product_price'] }}" name="order_products[{{ $order_product['id'] }}][product_price]">
                                    </th>
                                    <th>
                                        @if(!empty($order_product['product']['size']))
					                        <?php

					                        $sizes = \App\Helpers::explodeToArray($order_product['product']['size']);
					                        $size_name = 'order_products['.$order_product['id'].'][size]';

					                        echo Form::select($size_name,$sizes,( $order_product['size'] ), ['placeholder' => 'Select a size'])
					                        ?>
                                        @else
                                            <select hidden class="form-control" name="order_products[{{ $order_product['id'] }}][size]">
                                                <option selected="selected" value=""></option>
                                            </select>
                                            nil
                                        @endif
                                    </th>
                                    <th>
                                        <input class="table-input" type="number" value="{{ $order_product['qty'] }}" name="order_products[{{ $order_product['id'] }}][qty]">
                                    </th>
                                    @if(isset($order_product['product']))
                                        <th>
                                            <a class="btn btn-image" href="{{ route('products.show',$order_product['product']['id']) }}"><img src="/images/view.png" /></a>
                                            <a class="btn btn-image remove-product" href="#" data-product="{{ $order_product['id'] }}"><img src="/images/delete.png" /></a>
                                        </th>
                                    @else
                                        <th></th>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                {{-- {{dd($data)}} --}}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group btn-group pull-right">
                        <a href="{{ route('attachProducts',['order',$id]) }}?key={{$key}}" class="btn btn-image"><img src="/images/attach.png" /></a>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">+</button>
                    </div>
                </div>
                <div id="productModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Create Product</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                            <strong>Image:</strong>
                            <input type="file" class="form-control" name="image"
                                   value="{{ old('image') }}" id="product-image"/>
                            @if ($errors->has('image'))
                                <div class="alert alert-danger">{{$errors->first('image')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ old('name') }}"  id="product-name"/>
                            @if ($errors->has('name'))
                                <div class="alert alert-danger">{{$errors->first('name')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>SKU:</strong>
                            <input type="text" class="form-control" name="sku" placeholder="SKU"
                                   value="{{ old('sku') }}"  id="product-sku"/>
                            @if ($errors->has('sku'))
                                <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Color:</strong>
                            <input type="text" class="form-control" name="color" placeholder="Color"
                                   value="{{ old('color') }}"  id="product-color"/>
                            @if ($errors->has('color'))
                                <div class="alert alert-danger">{{$errors->first('color')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Brand:</strong>
                            <?php
                            $brands = \App\Brand::getAll();
                            echo Form::select('brand',$brands, ( old('brand') ? old('brand') : '' ), ['placeholder' => 'Select a brand','class' => 'form-control', 'id'  => 'product-brand']);?>
                              {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                              @if ($errors->has('brand'))
                                  <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                              @endif
                        </div>

                        <div class="form-group">
                            <strong>Price Inr special:</strong>
                            <input type="number" class="form-control" name="price_inr_special" placeholder="Price Inr special"
                                   value="{{ old('price_inr_special') }}" step=".01"  id="product-price"/>
                            @if ($errors->has('price_inr_special'))
                                <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Size:</strong>
                            <input type="text" class="form-control" name="size" placeholder="Size"
                                   value="{{ old('size') }}"  id="product-size"/>
                            @if ($errors->has('size'))
                                <div class="alert alert-danger">{{$errors->first('size')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Quantity:</strong>
                            <input type="number" class="form-control" name="quantity" placeholder="Quantity"
                                   value="{{ old('quantity') }}"  id="product-quantity"/>
                            @if ($errors->has('quantity'))
                                <div class="alert alert-danger">{{$errors->first('quantity')}}</div>
                            @endif
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="createProduct">Create</button>
                      </div>
                    </div>

                  </div>
                </div>
            {{-- @endif --}}
        </div>
                
        <div class="row">
            <div class="col">
                 <div class="form-group">
                    <strong>Advance Amount:</strong>
                    <?php
                        if(isset($defaultSelected["advance_detail"])) {
                            $advance_detail = $defaultSelected["advance_detail"];
                        }
                    ?>
                    <input type="text" class="form-control" name="advance_detail" id="advance_detail" placeholder="Advance Detail"
                           value="{{ old('advance_detail') ? old('advance_detail') : $advance_detail }}"/>
                    @if ($errors->has('advance_detail'))
                        <div class="alert alert-danger">{{$errors->first('advance_detail')}}</div>
                    @endif
                </div>
            </div>
            <!-- start-DEVTASK-3291 -->
            <div class="col">
                 <div class="form-group">
                    <strong>Customer Credit:</strong>
                    <?php $customer_credit = 0; ?>
                    <input type="text" class="form-control" name="customer_credit" id="customer_credit" placeholder="Customer Credit"
                           value="{{ old('customer_credit') ? old('customer_credit') : $customer_credit }}"/>
                </div>
            </div>
            <!-- end-DEVTASK-3291 -->

            <div class="col">
                 <div class="form-group">
                    <strong>Advance Date:</strong>
                    <?php
                        if(isset($defaultSelected["advance_date"])) {
                            $advance_date = $defaultSelected["advance_date"];
                        }
                    ?>
                    <input type="date" class="form-control datepicker-block" name="advance_date" placeholder="Advance Date"
                           value="{{ old('advance_date') ? old('advance_date') : $advance_date }}"/>
                    @if ($errors->has('advance_date'))
                        <div class="alert alert-danger">{{$errors->first('advance_date')}}</div>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong> Status :</strong>
                    <?php
                        if(isset($defaultSelected["order_status_id"])) {
                            $order_status = $defaultSelected["order_status_id"];
                        }
                    ?>
                    <?php
                    $orderStatus = new \App\ReadOnly\OrderStatus;

                    echo Form::select('order_status_id',$orderStatus->all(), ( old('order_status_id') ? old('order_status_id') : $order_status ), ['placeholder' => 'Select a status','class' => 'form-control']);?>

                    @if ($errors->has('order_status'))
                        <div class="alert alert-danger">{{$errors->first('order_status')}}</div>
                    @endif
                </div>

            </div>    
        </div> 
        <div class="row">
            <!-- <div class="col">
                <div class="form-group">
                    <strong>Estimated Delivery Date:</strong>
                    <?php
                        if(isset($defaultSelected["estimated_delivery_date"])) {
                            $estimated_delivery_date = $defaultSelected["estimated_delivery_date"];
                        }
                    ?>
                    <input type="date" class="form-control datepicker-block" name="estimated_delivery_date" placeholder="Advance Date"
                           value="{{ old('estimated_delivery_date') ? old('estimated_delivery_date') : $estimated_delivery_date }}"/>
                    @if ($errors->has('estimated_delivery_date'))
                        <div class="alert alert-danger">{{$errors->first('estimated_delivery_date')}}</div>
                    @endif
                </div>
            </div> -->
            <div class="col">
                <div class="form-group">
                    <strong>Received By:</strong>
                    <?php
                        if(isset($defaultSelected["received_by"])) {
                            $received_by = $defaultSelected["received_by"];
                        }
                    ?>
                    <input type="text" class="form-control" name="received_by" placeholder="Received By"
                           value="{{ old('received_by') ? old('received_by') : $received_by }}"/>
                    @if ($errors->has('received_by'))
                        <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
                    @endif
                </div> 
            </div> 
            <div class="col">
                <div class="form-group">
                    <strong> Payment Mode :</strong>
                    <?php
                        if(isset($defaultSelected["payment_mode"])) {
                            $payment_mode = $defaultSelected["payment_mode"];
                        }
                    ?>
                    <?php
                    $paymentModes = new \App\ReadOnly\PaymentModes();

                    echo Form::select('payment_mode',$paymentModes->all(), ( old('payment_mode') ? old('payment_mode') : $payment_mode ), ['placeholder' => 'Select a mode','class' => 'form-control']);?>

                    @if ($errors->has('payment_mode'))
                        <div class="alert alert-danger">{{$errors->first('payment_mode')}}</div>
                    @endif
                </div>
            </div>
            <div class="col"> 
                <div class="form-group">
                    <strong>Note if any:</strong>
                    <?php
                        if(isset($defaultSelected["note_if_any"])) {
                            $note_if_any = $defaultSelected["note_if_any"];
                        }
                    ?>
                    <input type="text" class="form-control" name="note_if_any" placeholder="Note if any"
                           value="{{ old('note_if_any') ? old('note_if_any') : $note_if_any }}"/>
                    @if ($errors->has('note_if_any'))
                        <div class="alert alert-danger">{{$errors->first('note_if_any')}}</div>
                    @endif
                </div>
            </div>

            <div class="col"> 
                <div class="form-group">
                <br>
                <input type="hidden" name="hdn_order_mail_status" id="hdn_order_mail_status" value="" />
                <button type="submit" class="btn btn-secondary" id="btn_saveorder">+</button>
                </div>
            </div>  
        </div>   
        <!-- <div class="row"> 
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <input type="hidden" name="hdn_order_mail_status" id="hdn_order_mail_status" value="" />
                <button type="submit" class="btn btn-secondary" id="btn_saveorder">+</button>
            </div>
        </div> -->
    </form>

    <form action="" method="POST" id="product-remove-form">
      @csrf
    </form>

    <div id="myModalOrderConfirmation" class="myModalOrderConfirmation modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Order Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-form">
                        Are you sure to send order confirmation email?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="orderconfirmation(1)" class="btn btn-primary" id="orderconfirmationYes">Yes</button>
                    <button type="button" onclick="orderconfirmation(0)" class="btn" id="orderconfirmationNo">No</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">

    //DEVTASK-3291
        $("#customer_id").on('change',function(){
             var selectedCustomerCredit=$(this).find(':selected').data('credit');
             if(selectedCustomerCredit=="undefined" || selectedCustomerCredit==""){
                selectedCustomerCredit=0;
             }
             $("#customer_credit").val(selectedCustomerCredit);
        });
          
        function balanceCalc(){
            var advance_detail =$("#advance_detail").val();
            var customer_credit =$("#customer_credit").val();
            var calc=0;
                if(advance_detail==undefined){
                    advance_detail=0;
                }

                if(customer_credit==undefined){
                    customer_credit=0;
                }
            calc=parseFloat(advance_detail)+parseFloat(customer_credit);
            $("#balance_amount").val(calc);
        }

        function openConformattionMailBox()
        {
            jQuery("#myModalOrderConfirmation").modal('show');
        }
        function orderconfirmation(mail_status)
        {
            if(mail_status == 1)
            {
                $("#hdn_order_mail_status").val("1");
            }
            else
            {
                $("#hdn_order_mail_status").val("0");
            }
            jQuery("#myModalOrderConfirmation").modal('hide');
            var form$ = jQuery("#createOrderForm");
            form$.get(0).submit();  
        }
      $(document).ready(function() {
        $('.datepicker-block').datetimepicker({
          format: 'YYYY-MM-DD'
        });
        jQuery("#createOrderForm").submit(function() {
            openConformattionMailBox();
            // Submit from callback
            return false;
        });
        $('#createProduct').on('click', function() {
          var token = "{{ csrf_token() }}";
          var url = "{{ route('products.store') }}";
          var order_id = {{ $id }};
          var image = $('#product-image').prop('files')[0];
          var name = $('#product-name').val();
          var sku = $('#product-sku').val();
          var color = $('#product-color').val();
          var brand = $('#product-brand').val();
          var price = $('#product-price').val();
          var size = $('#product-size').val();
          var quantity = $('#product-quantity').val();

          var form_data = new FormData();
          form_data.append('_token', token);
          form_data.append('order_id', order_id);
          form_data.append('image', image);
          form_data.append('name', name);
          form_data.append('sku', sku);
          form_data.append('color', color);
          form_data.append('brand', brand);
          form_data.append('price_inr_special', price);
          form_data.append('size', size);
          form_data.append('quantity', quantity);

          $.ajax({
            type: 'POST',
            url: url,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            data: form_data,
            success: function(response) {
              var brands_array = {!! json_encode(\App\Helpers::getUserArray(\App\Brand::all())) !!};
              var show_url = "{{ url('products') }}/" + response.product.id;
              var delete_url = "{{ url('deleteOrderProduct') }}/" + response.order.id;
              var product_row = '<tr><th><img width="200" src="' + response.product_image + '" /></th>';
                  product_row += '<th>' + response.product.name + '</th>';
                  product_row += '<th>' + response.product.sku + '</th>';
                  product_row += '<th>' + response.product.color + '</th>';
                  product_row += '<th>' + brands_array[response.product.brand] + '</th>';
                  product_row += '<th><input class="table-input" type="text" value="' + response.product.price_inr_special + '" name="order_products[' + response.order.id + '][product_price]"></th>';
                  // product_row += '<th>' + response.product.size + '</th>';

                  if (response.product.size != null) {
                    var exploded = response.product.size.split(',');

                    product_row += '<th><select class="form-control" name="order_products[' + response.order.id + '][size]">';
                    product_row += '<option selected="selected" value="">Select</option>';

                    $(exploded).each(function(index, value) {
                      product_row += '<option value="' + value + '">' + value + '</option>';
                    });

                    product_row += '</select></th>';

                  } else {
                      product_row += '<th><select hidden class="form-control" name="order_products[' + response.order.id + '][size]"><option selected="selected" value=""></option></select>nil</th>';
                  }

                  product_row += '<th><input class="table-input" type="number" value="' + response.order.qty + '" name="order_products[' + response.order.id + '][qty]"></th>';
                  product_row += '<th><a class="btn btn-image" href="' + show_url + '"><img src="/images/view.png" /></a>';
                  product_row += '<a class="btn btn-image remove-product" href="#" data-product="' + response.order.id + '"><img src="/images/delete.png" /></a></th>';
                  product_row += '</tr>';

              $('#products-table').append(product_row);
              $("#productModal").modal("hide");
            },
            error:function(data) {
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    var errStr = "";
                    $.each(errors, function (key, value) {
                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {                       
                                errStr += value+"</br>";
                            });
                        }else{
                            errStr += value+"</br>";
                        }
                    });
                    toastr['error'](errStr, 'error');
                }
            }
          });
        });

        $(document).on('click', '.remove-product', function(e) {
          e.preventDefault();

          var product_id = $(this).data('product');
          var url = "{{ url('deleteOrderProduct') }}/" + product_id+"?key={{ request('key') }}";
          // var token = "{{ csrf_token() }}";

          $('#product-remove-form').attr('action', url);
          $('#product-remove-form').submit();
        });

        var searchSuggestions = {!! json_encode($customer_suggestions) !!};

	      $('#customer_suggestions').autocomplete({
	        source: function(request, response) {
	          var results = $.ui.autocomplete.filter(searchSuggestions, request.term);

	          response(results.slice(0, 10));
	        }
          });


          


      });
    </script>
@endsection
