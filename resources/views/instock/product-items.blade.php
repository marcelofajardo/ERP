{!! $products->links() !!}

<div class="table-responsive">
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Brand</th>
                <th>Transist Status</th>
                <th>Location</th>
                <th>Sku</th>
                <th>Id</th>
                <th>Size</th>
                <th>Price</th>
                <th>Status</th>
                <th width="10%">Created</th>
                <th width="10%">Updated</th>
                <th width="13%">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    <a href="{{ route('products.show', $product->id) }}">
                        <img style="width: 80px;height: 80px;"  src="{{ $product->getMedia(config('constants.attach_image_tag'))->first()
                            ? $product->getMedia(config('constants.attach_image_tag'))->first()->getUrl()
                            : ''
                          }}" class="img-responsive grid-image" alt=""/>
                    </a>
                </td>
                <td>
                    {{ isset($product->brands) ? $product->brands->name : "" }}
                </td>
                <td c   lass="transist_status_{{$product->id}} show-more-content-btn" data-text="{{$product->purchase_status}}">
                    {{ strlen($product->purchase_status) > 10 ? substr($product->purchase_status, 0, 10) . '...' : $product->purchase_status }}
                </td>
                <td class="location_{{$product->id}} show-more-content-btn" data-text="{{$product->location}}">
                    {{ strlen($product->location) > 5 ? substr($product->location, 0, 5) . '...' : $product->location }}
                </td>
                <td class="show-more-content-btn" data-text="{{$product->sku}}">{{ strlen($product->sku) > 5 ? substr($product->sku, 0, 5) . '...' : $product->sku }}</td>
                <td>{{ $product->id }}</td>
                <td class="show-more-content-btn" data-text="{{$product->size}}"><span class="text-editable" data-field-name="size"
                          data-product-id="{{ $product->id }}">{{ strlen($product->size) > 5 ? substr($product->size, 0, 5) . '...' : $product->size }}</span></td>
                <td><span class="text-editable" data-field-name="price_inr_special"
                          data-product-id="{{ $product->id }}">{{ ($product->price_inr_special > 0) ? $product->price_inr_special : "N/A" }}</span></td>
                <td><?php echo Form::select("stock_status", [null => "- Select --"] + \App\Product::STOCK_STATUS, $product->stock_status, ["class" => "form-control update-product-stock-status", "data-product-id" => $product->id]); ?></td>
                <td>{{ $product->created_at }}</td>
                <td>{{ $product->updated_at }}</td>
                <td>
                    <button type="button" data-product-id="{{ $product->id }}" class="btn btn-image crt-instruction"
                            title="Create Dispatch / Location Change"><img src="/images/support.png"></button>
                    <button type="button" data-product-id="{{ $product->id }}" class="btn btn-image crt-instruction-history"
                            title="Product Location History"><img src="/images/remark.png"></button>
                    <button type="button" data-product-id="{{ $product->id }}" class="btn btn-image crt-product-dispatch"
                            title="Create Dispatch"><img src="/images/resend.png"></button>
                    <?php
                    $getMedia = $product->getMedia(config('constants.attach_image_tag'));
                    $image = [];
                    foreach ($getMedia as $value) {
                        $image[] = $value->id;
                    }
                    ?>
                    <button type="button" data-media-ids="{{ implode(',', $image) }}" class="btn btn-image crt-attach-images"
                            title="Attach Images to Message"><img src="/images/attach.png"></button>
                    <span class="" style="padding:6px !important">
                            <input type="checkbox" class="select-product-edit" name="product_id" data-id="{{ $product->id }}">
                    </span>
                    @if ($type == 'private_viewing')
                        <a href="#" class="btn btn-secondary select-product" data-id="{{ $product->id }}" data-attached="0">Select</a>
                    @endif

                    {{--

                      {!! Form::open(['method' => 'POST','route' => ['products.archive', $product->id],'style'=>'display:inline']) !!}
                      <button type="submit" class="btn btn-image"><img src="/images/archive.png" /></button>
                      {!! Form::close() !!}

                    --}}
                    @if(auth()->user()->isAdmin())
                        {!! Form::open(['method' => 'DELETE','route' => ['products.destroy', $product->id],'style'=>'display:inline']) !!}
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}
                    @endif
                </a>
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>

@if ($type == 'private_viewing')
    <div class="row">
        <div class="col text-center">
            <button type="button" class="btn btn-secondary my-3" id="privateViewingButton">Set Up for Private Viewing
            </button>
        </div>
    </div>
@endif
<?php
request()->request->add(['instock' => 'yes']);
?>
{!! $products->appends(Request::except('page'))->links() !!}
