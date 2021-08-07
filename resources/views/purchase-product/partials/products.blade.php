<center><p>{{strtoupper($type)}}</p></center>
@if($type == 'inquiry')
<button class="btn btn-secondary btn-xs pull-right btn-send" data-type="{{$type}}" data-id="{{$supplier_id}}">Send</button>
@elseif($type == 'order')
<button class="btn btn-secondary btn-xs btn-secondary pull-right btn_send_modal" data-toggle="modal" data-target="#send_supp_modal" data-type="{{$type}}" data-id="{{$supplier_id}}">Send</button>
<button class="btn btn-secondary btn-xs btn-secondary pull-right btn_set_template mr-1" data-toggle="modal" data-target="#set_template_modal" data-type="{{$type}}" data-id="{{$supplier_id}}">Template</button>
@endif
<div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;table-layout:fixed">
        <thead>
        <tr>
            <th width="2%"></th>
            <th width="8%">#</th>
            <th width="20%">View</th>
            <th width="20%">Name</th>
            @if($type == 'inquiry')<th width="20%">Is Owned?</th>@endif
            <th width="20%">SKU</th>
            <th width="10%">Price</th>
            <th width="10%">Discount</th>
            <th width="10%">Fixed Price</th>
            <th width="10%">Final Price</th>
            <th width="10%">Action</th>
         </tr>
        </thead>

        <tbody>
			@foreach ($products as $key => $product)
            <tr class="supplier-{{$supplier_id}}">
              <td><input type="checkbox" class="select-pr-list-chk" data-id="{{$product->id}}" data-order-id="{{ $product->order_product_id ?? 0}}"></td><!-- Purpose : Add Order id - DEVATSK-4236 -->
              <td>{{ ++$key }}</td>
              <td>
              {{-- START - Purpose : Replace $product to $product_data - DEVTASK-4048 --}}
              @php
                $product_data = \App\Product::find($product->id);
              @endphp

              {{-- Purpose : Add If Condition - DEVTASK-4048 --}}
              @if($product_data != null)
                @if ($product_data->hasMedia(config('constants.media_tags')))
                  <span class="td-mini-container">
                      <a data-fancybox="gallery" href="{{ $product_data->getMedia(config('constants.media_tags'))->first()->getUrl() }}">View</a>
                  </span>
                @endif
              @endif
              {{-- END - DEVTASK-4048 --}}
              </td>
              <td>{{ $product->name }}</td>
              @if($type == 'inquiry')<td>{{ $product->sup_id == $supplier_id ? 'Yes' : 'No'}}</td>@endif
              <td>{{$product->sku}}</td>
              <td>{{$product->product_price}}</td>
              <td>{{$product->discount}}</td>
              <td>{{$product->fixed_price}}</td>
              <td>
              @php 
            if($product->product_price)  {
              if($product->discount) {
                $discount = $product->product_price*($product->discount/100);
                $final_price = $product->product_price - $discount;
              }
              else {
                if($product->fixed_price) {
                  $final_price = $fixed_price;
                }
                else {
                  $final_price = $product->product_price;
                }
              } 
            }
            else {
              $final_price = 0;
            }
            @endphp
            {{number_format($final_price,2)}}
              
              
              </td>
              <td></td>
            </tr>
           @endforeach
        </tbody>
      </table>
	</div>


<!-- START - purpose : Open modal - DEVTASK-4236 -->
<div class="modal fade" id="send_supp_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form> 
      <div class="modal-body">
             
              <input type="hidden" name="type" class="type" />
              <input type="hidden" name="supplier_id" class="supplier_id" />
              <input type="hidden" name="product_id" class="product_id" />
              <input type="hidden" name="order_id" class="order_id" />
              <div class="show_excel_send_data">
                <a class="download_excel_url" style="cursor: pointer;" target="_blank" ><i class="fa fa-download" aria-hidden="true"></i> Download Excel File</a>
                <br/><br/>
                <a class="edit_excel_file" style="cursor: pointer;" target="_blank" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Excel File</a>
                <br/><br/>

                <textarea class="form-control additional_content" id="additional_content" rows="7" placeholder="Additional Content"></textarea><br/>

                <input type="checkbox" id="send_option_email" name="email" value="email"> Email<br>
                <input type="checkbox" id="send_option_whatsapp" name="whatsapp" value="whatsapp"> WhatsApp<br>
              </div>
              <div class="alert alert-danger select_product_error">Please Select Products</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary send_excel_btn">Send</button>
      </div>
      </form>
    </div>
  </div>
</div>



<div class="modal fade" id="set_template_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form> 
      <div class="modal-body">
             
              <input type="hidden" name="type" class="type_template" />
              <input type="hidden" name="supplier_id" class="supplier_id_template" />
             
              <textarea class="form-control template_data" id="template_data" rows="7" placeholder="Template">{product_data}</textarea><br/>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary send_template_btn">save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END - DEVTASK-4236 -->