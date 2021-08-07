@if(!empty($inventory_data->items()))
  @foreach ($inventory_data as $row => $data)
    <tr>
    <td>{{ $data['id'] }}</td>
    <td>
      <span id="sku_long_string_{{$data['id']}}" style="display: none">{{ $data['sku'] }}</span>
      <span id="sku_small_string_{{$data['id']}}"><?php echo \Illuminate\Support\Str::substr($data['sku'],-10) ?> @if(strlen($data['sku'])>10) ...<a href="javascript:;" data-id="{{$data['id']}}" class="show_sku_long">More</a> @endif
      
    </td>
    <td>
    <span id="prod_long_string_{{$data['id']}}" style="display: none">{{ $data['product_name'] }}</span>
      <span id="prod_small_string_{{$data['id']}}"><?php echo \Illuminate\Support\Str::substr($data['product_name'],-10) ?> @if(strlen($data['product_name'])>10) ...<a href="javascript:;" data-id="{{$data['id']}}" class="show_prod_long">More</a> @endif


    </td>
    <td>{{ $data['brand_name'] }}</td>
    <td>{{ $data['supplier'] }}</td>
    <td>0</td>
    
    <td>{{ $data['created_at'] }}</td>
    <td>
      <a  title="show Inventory history" data-id="{{ $data['id'] }}" class="btn btn-image show-inventory-history-modal des-pd"><i class="fa fa-history" aria-hidden="true"></i></a>
    </td>
  </tr>
  @endforeach
@else
  <tr><td colspan="9">No Records</td></tr>
@endif
