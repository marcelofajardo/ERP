<div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
        <thead>
        <tr>
            <th width="5%">Name</th>
            <th width="5%">Product Price</th>
            <th width="5%">Discount</th>
            <th width="5%">Final Price</th>
            <th width="5%">GMU</th>
            <th width="5%">Web</th>
            <th width="5%">Action</th>
         </tr>
        </thead>

        <tbody>
        @foreach($suppliers as $supplier)
        @php
          $order_product_id = 0; 
          if($order_product) {
            $order_product_id = $order_product->id;
          }

          $order_product = \App\OrderProduct::find($order_product_id);
          $supplier_discount_info = \App\SupplierDiscountInfo::where('product_id', $supplier->product_id)->where('supplier_id',$supplier->supplier_id)->first();
          
          $discount = null;
          $fixed_price = null;
          $supplier_discount_info_id = null;

          if($supplier_discount_info) {
            $discount = $supplier_discount_info->discount;
            $fixed_price = $supplier_discount_info->fixed_price;
            $supplier_discount_info_id = $supplier_discount_info->id;
          }

          $checked = 0;
          if($order_product && $supplier_discount_info_id) {
            if($order_product->supplier_discount_info_id == $supplier_discount_info_id) {
              $checked = 1;
            }
          }
        @endphp
            <tr>
            <td>{{$supplier->supplier}}</td>
            <td>{{$supplier->product_price}}</td>
            <td>
            <input style="min-width: 30px;" data-order-product="{{$order_product_id}}" data-product="{{$supplier->product_id}}" data-id="{{$supplier->supplier_id}}" placeholder="Discount" value="{{$discount}}" type="number" class="form-control supplier-discount" name="supplier_discount" id="supplier_discount-{{$supplier->supplier_id}}">
            </td>
            <td>
            <input style="min-width: 30px;" data-order-product="{{$order_product_id}}" data-product="{{$supplier->product_id}}" placeholder="Fixed Price" data-id="{{$supplier->supplier_id}}" value="{{$fixed_price}}" type="number" class="form-control supplier-fixed-price" name="supplier_fixed_price" id="supplier_fixed_price_{{$supplier->supplier_id}}">
            </td>
            <td>
            @php 
             
             if($supplier->product_price)  {
              if($discount) {
                $discount = $supplier->product_price*($discount/100);
                $final_price = $supplier->product_price - $discount;
              } else {
                if($fixed_price) {
                  $final_price = $fixed_price;
                }else {
                  $final_price = $supplier->product_price;
                }
              } 
            }else {
              $final_price = 0;
            }
            
            $gmu = 0;
            if($final_price) {
              $gmu = $final_price/1.22;
            }
            
            @endphp
            {{number_format($gmu,2)}}
            </td>
            <td><a href="{{ $supplier->supplier_link }}" target="__blank"><i class="fa fa-globe"></i></a></td>
            <td>
            <input data-order_product="{{$order_product_id}}" data-id="{{$supplier->supplier_id}}" data-product="{{$supplier->product_id}}"  type="radio" name="product_default_supplier" class="product_default_supplier" {{$checked ? 'checked' : ''}}>
            </td>
            </tr>
        @endforeach
        </tbody>
      </table>
	</div>