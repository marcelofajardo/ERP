<div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
        <thead>
        <tr>
            <th width="5%">Order No</th>
            <th width="5%">Site Name</th>
            <th width="5%">Est del date</th>
            <th width="5%">Brands</th>
            <th width="5%">Order status</th>
            <th width="5%">Advance</th>
            <th width="5%">Balance</th>
         </tr>
        </thead>

        <tbody>
            <tr>
            <td>{{$data->order_id}}</td>
            <td>
            @if ($data->storeWebsiteOrder)
                  @if ($data->storeWebsiteOrder->storeWebsite)
                    @php
                      $storeWebsite = $data->storeWebsiteOrder->storeWebsite;
                    @endphp
                    <span class="td-mini-container">
                        <a href="{{$storeWebsite->website}}" target="_blank">{{ strlen($storeWebsite->website) > 15 ? substr($storeWebsite->website, 0, 13) . '...' : $storeWebsite->website }}</a>
                    </span>
                    <span class="td-full-container hidden">
                        <a href="{{$storeWebsite->website}}" target="_blank">{{ $storeWebsite->website }}</a>
                    </span>
                  @endif
                @endif
            </td>
            <td>{{$data->estimated_delivery_date}}</td>
            <td>
            <?php 
                   $totalBrands = explode(",",$data->brand_name_list);
                    if(count($totalBrands) > 1) {
                      $str = 'Multi';
                    }
                    else {
                      $str = $data->brand_name_list;
                    }
                ?>
                <span style="font-size:14px;">{{$str}}</span>
            </td>
            <td>{{$data->order_status}}</td>
            <td>{{$data->advance_detail}}</td>
            <td>{{$data->balance_amount}}</td>
            </tr>
        </tbody>
      </table>
	</div>