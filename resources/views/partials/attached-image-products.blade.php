<div class="customer-count customer-list-{{ $suggested_products_id }} customer-{{ $suggested_products_id }}"
style="padding: 0px 10px;">
@foreach ($productsLists as $list)
    @if (count($list->products) > 0)
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <h5 class="product-attach-date" style="margin: 5px 0px;">{{ $list->date }}</h5>
                    <hr style="margin: 5px 0px;"> 
                </div>
            </div>
        @php
            $count = 0;
        @endphp
        @php
            $left = count($list->products);
        @endphp
        @foreach ($list->products as $kr => $pr)
            @php
                $left--;
                $product = \App\Product::find($pr->id);
            @endphp
            @if ($product->hasMedia(config('constants.attach_image_tag')))
                    @php
                        $imageDetails = $product->getMedia(config('constants.attach_image_tag'))->first();
                        $image_key = $imageDetails->getKey();
                        $selected_all = true;
                        $im = [
                            'abs' => $imageDetails->getAbsolutePath(),
                            'url' => $imageDetails->getUrl(),
                            'id' => $imageDetails->getKey(),
                        ];
                        if (!in_array($imageDetails->getKey(), $selected_products)) {
                            $selected_all = false;
                        }
                        $image_keys = json_encode($image_key);
                        if ($count == 6) {
                            $count = 0;
                        }
                    @endphp
                    @if($count == 0)
                        <div class="row parent-row">
                    @endif
                    <div class="col-md-2 col-xs-4 text-center product-list-card mb-4 single-image-{{ $pr->suggested_product_list_id }}-{{ $customer_id }}-{{ $product->id }}"
                    style="padding:0px 5px;margin-bottom:2px !important;">
                            <div style="border: 1px solid #bfc0bf;padding:0px 5px;">
                                <div data-interval="false" id="carousel_{{ $product->id }}" class="carousel slide"
                                    data-ride="carousel">
                                    <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip"
                                        data-html="true" data-placement="top"
                                        title="<strong>Supplier: </strong>{{ $product->supplier }} <strong>Status: </strong>{{ $product->purchase_status }}">
                                        <div class="carousel-inner maincarousel">
                                            <div class="item" style="display: block;"> <img
                                                    src="{{ urldecode($im['url']) }}"
                                                    style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input select-pr-list-chk"
                                            checked="checked" id="defaultUnchecked_{{ $product->id . $kr . $customer_id }}">
                                        <label class="custom-control-label"
                                            for="defaultUnchecked_{{ $product->id . $kr . $customer_id }}"></label>


                                    </div>

                                        <a href="{{ route('product-inventory.fetch.img.google', ['name' => $product->name, 'id' => $product->id]) }}"
                                            data-toggle="tooltip" title="Fetch image from google" class="btn btn-sm"><i
                                                class="fa fa-picture-o"></i></a>
                                        <a href="javascript:;" data-media="{{ $im['id'] }}"
                                            class="btn btn-sm btn-image {{ in_array($imageDetails->getKey(), $selected_products) ? 'btn-success' : '' }} attach-photo new-{{ $customer_id }}"
                                            data-image="{{ $model_type == 'purchase-replace' || $model_type == 'broadcast-images' || $model_type == 'landing-page' ? $product->id : $imageDetails->getKey() }}"
                                            data-product="{{ $pr->suggested_product_list_id }}"
                                            data-attached="{{ in_array($imageDetails->getKey(), $selected_products) ? 1 : 0 }}"><img
                                                src="{{ asset('images/attach.png') }}"></a>
                                        <a href="javascript:;" class="btn btn-sm select_row" title="Select Single Row"><i
                                                class="fa fa-arrows-h" aria-hidden="true"></i></a>
                                        <a href="javascript:;" class="btn btn-sm select_multiple_row"
                                            title="Select Multiple Row"><i class="fa fa-check" aria-hidden="true"></i></a>
                                        <a href="javascript:;" title="Remove" class="btn btn-sm delete-message"
                                            data-listed_id="{{ $pr->suggested_product_list_id }}"
                                            data-id="{{ $product->id }}" data-customer="{{ $customer_id }}"
                                            title="Remove"><i class="fa fa-trash" aria-hidden="true"></i></a>



                                            <a href="javascript:;" class="btn btn-sm create-product-lead-dimension"
                                            data-id="{{ $product->id }}" data-customer-id="{{ $customer->id }}"
                                            title="Dimensions"><i class="fa fa-delicious" aria-hidden="true"></i></a>
                                        <a href="javascript:;" class="btn btn-sm create-product-lead"
                                            data-id="{{ $product->id }}" data-customer-id="{{ $customer->id }}"
                                            title="Lead"><i class="fa fa-archive" aria-hidden="true"></i></a>


                                            <a href="javascript:;" class="btn btn-sm create-detail_image"
                                            data-id="{{ $product->id }}" data-customer-id="{{ $customer->id }}"
                                            title="Detailed Images"><i class="fa fa-file-image-o" aria-hidden="true"></i></a>

                                            <a href="javascript:;" class="btn btn-sm create-product-order"
                                            data-id="{{ $product->id }}" data-customer-id="{{ $customer->id }}"
                                            title="Order"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>
                                </div>


                            </div>
                    </div>


                    @php
                            $count++;
                            if ($left == 0) {
                                $count = 0;
                            }
                            $total = count($list->products);
                            if ($count == 6 || $left == 0) {
                                echo '</div>';
                            }
                    @endphp
                    @if ($count == 6)
                            <div class="row toggle-div-{{ $customer_id }}">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-image sendImageMessage pull-right"
                                        data-id="{{ $customer_id }}" style="padding:0px;margin:0px;"><img
                                            src="/images/filled-sent.png" /></button>
                                </div>
                            </div>
                    @endif
            @else
                    <div class="col-md-3 col-xs-6 text-center mb-5">
                        <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true"
                            title="{{ 'Nothing to show' }}">
                            <img src="" class="img-responsive grid-image" alt="" />
                            <p>Sku :
                                {{ strlen($product->sku) > 18 ? substr($product->sku, 0, 15) . '...' : $product->sku }}
                            </p>
                            <p>Id : {{ $product->id }}</p>
                            <p>Title : {{ $product->name }} </p>
                        </a>
                        <p>Category :
                            <select class="form-control select-multiple-cat-list update-product"
                                data-id={{ $product->id }}>
                                @if (!empty($categoryArray))
                                    @foreach ($categoryArray as $category)
                                        <option value="{{ $category['id'] }}" @if ($category['id'] == $product->category) selected @endif>{{ $category['value'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </p>
                        <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true"
                            data-placement="top"
                            title="<strong>Supplier: </strong>{{ $product->supplier }} <strong>Status: </strong>{{ $product->purchase_status }}">

                            <p>Size :
                                {{ strlen($product->size) > 17 ? substr($product->size, 0, 14) . '...' : $product->size }}
                            </p>
                            <p>Price EUR special: {{ $product->price_eur_special }}</p>
                            <p>Price INR special: {{ $product->price_inr_special }}</p>
                        </a>
                        <a href="#" class="btn btn-secondary attach-photo" data-image="" data-attached="0">Attach</a>
                    </div>

            @endif


        @endforeach
    @endif
@endforeach

                   
    <br>
</div>

