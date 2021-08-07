<div id="update-invoice-address">
   <form method="post" action="{{route('order.update.customer.address')}}">
       @csrf
       @foreach($invoice->orders as $order) 
          <div class="card">
            <div class="card-header">{{$order->order_id}}</div>
            <div class="card-body">
              @php
                  $shipping  = $order->shippingAddress();
              @endphp
               @if ($loop->first)
                  <div class="col-md-12">
                    <div class="form-group">
                       <strong>Shipping Address:</strong>
                       <textarea name="order[{{$order->id}}][street]" class="form-control">@if($shipping){{$shipping->street}}@endif</textarea>
                    </div>
                    <div class="form-group">
                       <strong>City:</strong>
                       <input name="order[{{$order->id}}][city]" class="form-control" value="@if($shipping){{$shipping->city}}@endif" />
                    </div>
                    <div class="form-group">
                       <strong>Country:</strong>
                       <input name="order[{{$order->id}}][country_id]" class="form-control" value="@if($shipping){{$shipping->country_id}}@endif"/>
                    </div>
                    <div class="form-group">
                       <strong>Pincode:</strong>
                       <input name="order[{{$order->id}}][postcode]" class="form-control" value="@if($shipping){{$shipping->postcode}}@endif"/>
                    </div>
                  </div>
              @endif
              @foreach($order->order_product as $orderProduct) 
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">Order Product #{{$orderProduct->id}}</div>
                    <div class="card-body">
                       <div class="row">
                          <div class="col">
                              <div class="form-group">
                                  <strong>SKU&nbsp;:&nbsp;</strong>
                                  <input name="order_product[{{$orderProduct->id}}][sku]" class="form-control" value="{{$orderProduct->sku}}" />
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                  <strong>Price&nbsp;:&nbsp;</strong>
                                  <input name="order_product[{{$orderProduct->id}}][product_price]" class="form-control" value="{{$orderProduct->product_price}}" />
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                  <strong>Size&nbsp;:&nbsp;</strong>
                                  <input name="order_product[{{$orderProduct->id}}][size]" class="form-control" value="{{$orderProduct->size}}" />
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                  <strong>Color&nbsp;:&nbsp;</strong>
                                  <input name="order_product[{{$orderProduct->id}}][color]" class="form-control" value="{{$orderProduct->color}}" />
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                  <strong>QTY&nbsp;:&nbsp;</strong>
                                  <input name="order_product[{{$orderProduct->id}}][qty]" class="form-control" value="{{$orderProduct->qty}}" />
                              </div>
                          </div>
                      </div>
                     </div> 
                  </div>
                </div>  
              @endforeach
               @if ($loop->last)
                <button class="btn btn-secondary btn-xs add-new-product" data-order="{{$order->id}}">Add new </button>
                <div class="row add-new-product-form order-cls-{{$order->id}} d-none">
                      <div class="col">
                          <div class="form-group">
                              <strong>SKU&nbsp;:&nbsp;</strong>
                              <input name="sku{{$order->id}}" class="form-control" value="" /><br>
                              <button class="btn btn-secondary btn-xs search-product" data-order="{{$order->id}}" type="button"> Search SKU </button>
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group">
                              <strong>Price&nbsp;:&nbsp;</strong>
                              <input name="price{{$order->id}}" class="form-control" value="" />
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group">
                              <strong>Size&nbsp;:&nbsp;</strong>
                              <input name="size{{$order->id}}" class="form-control" value="" />
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group">
                              <strong>Color&nbsp;:&nbsp;</strong>
                              <input name="color{{$order->id}}" class="form-control" value="" />
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group">
                              <strong>QTY&nbsp;:&nbsp;</strong>
                              <input name="qty{{$order->id}}" class="form-control" value="" />
                          </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                            <button class="btn btn-secondary btn-xs save-new-product" data-order="{{$order->id}}">save</button>
                        </div>
                      </div>
                  </div>
              @endif
            </div>
          </div>
       @endforeach
      <button type="submit" name="update_details" data-id="{{$invoice->id}}" class="btn btn-primary btn-sm btn-update-invoice">Update Invoice</button>
   </form>
</div>
<script type="text/javascript">

    $(".add-new-product").click(function(){
        event.preventDefault();
        var order = $(this).data('order');
        $(".order-cls-"+order).toggleClass("d-none");
    });

    $('.search-product').on('click',function(){
        event.preventDefault();
        var order = $(this).data('order');
        var sku   = $('input[name="sku'+order+'"]').val();
      
          if( sku == ''){
            alert('Please fill up SKU field');
            return false;
          };

    $.ajax({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/order/invoices/search-product",
        type: "post",
        data:{ sku:sku }

        }).done(function(response) {
            console.log( response );
            if( response.code == 200 ){
                console.log(response.data.sku);
                $('input[name="sku'+order+'"]').val( response.data.sku );
                $('input[name="price'+order+'"]').val( response.data.price );
                $('input[name="size'+order+'"]').val( response.data.size );
                $('input[name="color'+order+'"]').val( response.data.color );
            }else{
                toastr['error']('Product not found');
            }
        }).fail(function(errObj) {
          toastr['error']('Something went wrong');
        });
    });

    $('.save-new-product').on('click',function(){
        event.preventDefault();
        var order = $(this).data('order');
        var price = $('input[name="price'+order+'"]').val();
        var size  = $('input[name="size'+order+'"]').val();
        var color = $('input[name="color'+order+'"]').val();
        var sku   = $('input[name="sku'+order+'"]').val();
        var qty   = $('input[name="qty'+order+'"]').val();
      
          if( price == '' || size == '' || color == '' || sku == '' || qty == '' || order == '' ){
            alert('Please fill up details');
            return false;
          };

    $.ajax({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/order/invoices/add-product",
        type: "post",
        data:{ sku:sku, price:price, size:size, color:color, qty:qty, order_id:order }

        }).done(function(response) {
          alert(response.message);
        }).fail(function(errObj) {
          console.log(errObj)
        });
    });
</script>