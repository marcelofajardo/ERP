<?php //dd($suggestedProducts); ?>
@foreach ($suggestedProducts as $sp => $suggested)
    <tr>
    <td>
    
    {{ \Carbon\Carbon::parse($suggested->last_attached)->format('d-m-y') }} 
    </td>
    <td>{{$suggested->id}}</td>
    <td>{{$suggested->customer->name}}</td>
    <td>{{$suggested->customer->phone}}</td>
    <td class="expand-row-msg" data-name="brand" data-id="{{$suggested->id}}">
    @php 
     $brandList = '';
     foreach($suggested->brdNames as $br) {
        $brandList = $brandList. ' '. $br->name.',';
     }
     @endphp

        <span class="show-short-brand-{{$suggested->id}}">{{ str_limit($brandList, 30, '...')}}</span>
            <span style="word-break:break-all;" class="show-full-brand-{{$suggested->id}} hidden">{{$brandList}},</span>
    </td>

    <td class="expand-row-msg" data-name="category" data-id="{{$suggested->id}}">
    @php 
     $catList = '';
     foreach($suggested->catNames as $cat) {
        $catList = $catList. ' '. $cat->title.',';
     }
     @endphp

        <span class="show-short-category-{{$suggested->id}}">{{ str_limit($catList, 30, '...')}}</span>
            <span style="word-break:break-all;" class="show-full-category-{{$suggested->id}} hidden">{{$catList}},</span>
    </td>
    <td>
    <button title="Open Images" type="button" class="btn preview-attached-img-btn btn-image no-pd" data-id="{{$suggested->customer_id}}" data-suggestedproductid="{{$suggested->id}}">
	<img src="/images/forward.png" style="cursor: default;">
	</button>
    <button title="Select all products" type="button" class="btn btn-xs btn-secondary btn-image no-pd select-customer-all-products mr-3" data-id="{{$suggested->customer_id}}" data-suggestedproductid="{{$suggested->id}}"> <img src="/images/completed.png" style="cursor: default;" data-suggestedproductid="{{$suggested->id}}"></button>
    <button title="Remove Multiple products" type="button" class="btn btn-xs btn-secondary remove-products mr-3" data-id="{{$suggested->id}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
    <button type="button" title="Forward multiple products" class="btn btn-xs btn-secondary forward-products mr-3" data-id="{{$suggested->customer_id}}" data-suggestedproductid="{{$suggested->id}}"><i class="fa fa-forward" aria-hidden="true"></i></button>
    <button type="button" title="Resent Multiple products" class="btn btn-xs btn-secondary resend-products mr-3" data-id="{{$suggested->customer_id}}" data-suggestedproductid="{{$suggested->id}}"><i class="fa fa-repeat" aria-hidden="true"></i></button>
    
    </td>
    </tr>
    <tr class="expand-{{$suggested->customer->id}} hidden">
    <td colspan="7" id="attach-image-list-{{$suggested->customer_id}}">
    
    </td>
    </tr>
@endforeach

{{--{{$suggestedProducts->appends(request()->except("page"))->links()}}--}}

