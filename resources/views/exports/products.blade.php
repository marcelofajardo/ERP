<table>
    <thead>
	       <tr>
	           <th>ID</th>
               <th>SKU</th>
               <th>Brand</th>
               <th>Price</th>
               <th>Image</th>
	       </tr>
    </thead>
    <tbody>
        @foreach($data as $product)
        	<tr>
        	    <td>{{ $product->id }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ isset($brands[$product->brand]) ? $brands[$product->brand] : "" }}</td>
                <td>{{ ($product->price_inr_special > 0) ? $product->price_inr_special : $product->price_inr }}</td>
                <td>
                     @if ($product->hasMedia(config('constants.attach_image_tag')))
                        @php
                            $image = $product->getMedia(config('constants.attach_image_tag'))->first();
                        @endphp
                        <?php 
                        if($image && file_exists(urldecode($image->getAbsolutePath())) ===true){ ?>
                            <img width="250" height="250" src="{{ $image->getAbsolutePath() }}">
                        <?php } else { ?>
                            <img width="250" height="250" src="{{ public_path() }}/images/no-image.jpg">
                        <?php } ?>
                     @else
                            <img width="250" height="250" src="{{ public_path() }}/images/no-image.jpg">
                     @endif
                </td>        
            </tr>
        @endforeach
    </tbody>
</table>