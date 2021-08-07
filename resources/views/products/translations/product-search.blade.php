@foreach ($product_translations as $key => $product)
    <tr>
        <td>{{ $product->product_id }}</td>

        <td>
            <button type="button" class="btn-link quick-edit-description__"
                    data-id="{{ $product->id }}" data-target="#product_image_{{ $product->id }}" data-toggle="modal">View
            </button>
        </td>

        <td>{{strtoupper($product->locale)}}</td>

        <td>
        {!! ($product->title)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->title, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->title)?"<span class='alltext' style='display:none;'>".$product->title."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>

        <td>
        {!! ($product->description)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->description, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->description)?"<span class='alltext' style='display:none;'>".$product->description."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
        {!! ($product->composition)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->composition, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->composition)?"<span class='alltext' style='display:none;'>".$product->composition."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
        {!! ($product->color)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->color, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->color)?"<span class='alltext' style='display:none;'>".$product->color."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
        {!! ($product->size)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->size, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->size)?"<span class='alltext' style='display:none;'>".$product->size."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
        {!! ($product->country_of_manufacture)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->country_of_manufacture, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->country_of_manufacture)?"<span class='alltext' style='display:none;'>".$product->country_of_manufacture."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
        {!! ($product->dimension)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->dimension, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
        {!! ($product->dimension)?"<span class='alltext' style='display:none;'>".$product->dimension."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>
        <td>
            {!! ($product->site)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($product->site->title, 10, '<a href="javascript:void(0)" class="readmore">...<i class="fa fa-plus" aria-hidden="true"></i></a>'))."</span>":"-" !!}
            {!! ($product->site)?"<span class='alltext' style='display:none;'>".$product->site->title."<a href='javascript:void(0)' class='readless'>...<i class='fa fa-minus' aria-hidden='true'></i></a></span>":"-" !!}
        </td>

        <td>{{$product->created_at->format('d M Y')}}</td>

        <td>
{{--            <a class="btn btn-image view-btn" data-toggle="modal" data-target="#translationModal" data-id="{{$product->id}}"><img src="/images/view.png"/></a>--}}
            <i style="cursor: pointer; " class="fa fa-eye" data-toggle="modal" data-target="#translationModal" data-id="{{$product->id}}" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Product Edit"></i>
            <i style="cursor: pointer; " class="fa fa-history" data-toggle="modal" data-target="#showHistory_{{ $product->id }}" data-id="{{$product->id}}" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Product History"></i>
            @if($product->is_rejected == 0)
            <i style="cursor: pointer; " class="fa fa-close rejectProduct" data-toggle="modal" data-action="{{ route('product.translation.rejection') }}" data-value="1" data-id="{{$product->id}}" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Product status"></i>
        @endif
        </td>
    </tr>
@endforeach


