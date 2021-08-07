@if(!empty($inventory_data->items()))
    @foreach ($inventory_data as $row => $data)
      <tr>
          <td><input type="checkbox" class="selected-product-ids" name="selected_product_ids[]" value="{{ $data['id'] }}"></td>
          <td >   <a  title="show status history" class="btn  show-scraped-product des-pd" style="color:#337ab7;">{{ $data['id'] }}</a></td>
          <td>
            <span id="sku_long_string_{{$data['id']}}" style="display: none">{{ $data['sku'] }}</span>
            <a href="/products/{{ $data['id'] }}">
            <span id="sku_small_string_{{$data['id']}}"><?php echo \Illuminate\Support\Str::substr($data['sku'],-10) ?> </span></a> @if(strlen($data['sku'])>10) ...<!-- <a href="javascript:;" data-id="{{$data['id']}}" class="show_sku_long">More</a> --> @endif
          </td>
          <td><a  title="show suppliers" data-id="{{ $data['id'] }}" class="btn btn-image show-supplier-modal des-pd">{{$data['total_product']}}</a></td>
          <td>
          <span id="prod_long_string_{{$data['id']}}" style="display: none">{{ $data['product_name'] }}</span>
            <span id="prod_small_string_{{$data['id']}}"><?php echo \Illuminate\Support\Str::substr($data['product_name'],-10) ?> @if(strlen($data['product_name'])>10) ...<!-- <a href="javascript:;" data-id="{{$data['id']}}" class="show_prod_long">More</a> --> @endif


          </td>
          <td> {{ isset($data['category_name']) ?   $data['category_name'] : "-" }}  / {{ isset($data['brand_name']) ?   $data['brand_name'] : "-" }}</td>
          <td>{{ isset($data['suppliers_info']) ?  $data['suppliers_info'][0]->price : ''}}</td>
          <td>{{ $data['discounted_percentage'] }}%</td>
          <!-- <td>{{ $data['brand_name'] }}</td> --><!--  Purpose : merge Category/ Brand  Column - DEVTASK-4138 -->
          <td>{{ $data['supplier'] }}</td>
          <td>{{ $data['color'] }}
          {{ isset($data['suggested_color']) ? 'S.Color : '.$data['suggested_color'] : "" }}
          </td>
          <!-- <td>{{ isset($data['suggested_color']) ? $data['suggested_color'] : "-" }}</td> --> <!--  Purpose : merge color /S.color  Column - DEVTASK-4138 -->
          <td>{{ $data['composition'] }}</td>
          <td>{{ $data['size_system'] }}</td>
          <td>{{ $data['size'] }}</td>
          <td>{{ $data['size_eu'] }}</td>
          <td>
            @foreach(\App\Helpers\StatusHelper::getStatus() as $key => $status)
              @if($key==$data['status_id'])
                {{ $status }}
              @endif
            @endforeach
          </td>
          <td>
            @foreach(\App\Helpers\StatusHelper::getStatus() as $key => $status)
              @if($key==$data['sub_status_id'])
                {{ $status }}
              @endif
            @endforeach
          </td>
          <td>{{ isset($data['history_date']) ? $data['history_date'] : $data['created_at'] }}</td>
          <td>
            <a  title="show medias" class="btn btn-image show-medias-modal des-pd" data-id="{{ $data['id'] }}" aria-expanded="false"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            <a  title="show status history" class="btn btn-image show-status-history-modal des-pd"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
            <a  title="show Inventory history" data-id="{{ $data['id'] }}" class="btn btn-image show-inventory-history-modal des-pd"><i class="fa fa-history" aria-hidden="true"></i></a>
            @if(empty($data['size_eu']))
              <a title="add-size" data-id="{{ $data['id'] }}" data-size-system="{{ $data['size_system'] }}" data-category-id="{{ $data['category'] }}" data-sizes='{{ json_encode(explode(",",$data["size"])) }}' class="btn btn-image add-size-btn"><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
          </td>
          <td class="medias-data" data='@if(isset($data['medias']))@json($data['medias'])@endif' style="display:none"></td>
          <td class="status-history" data='@if(isset($data['status_history']))@json($data['status_history'])@endif' style="display:none"></td>

          <td class="product-inventory" data='@if(isset($data->many_scraped_products))@json($data->many_scraped_products)@endif' style="display:none"></td>
          <!-- <td class="inventory-history" data='@if(isset($data['inventory_history']))@json($data['inventory_history'])@endif' style="display:none"></td> -->
      </tr>
    @endforeach
@else
  <tr><td colspan="16"><h2>No Records</h2></td></tr>
@endif
