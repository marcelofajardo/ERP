@extends('layouts.app')

@section("styles")
@endsection
<style type="text/css">
    .dis-none {
        display: none;
    }
</style>
@section('content')
    @include('partials.flash_messages')

    <div class="productGrid" id="productGrid">
        <form method="POST" action="{{route('google.search.details')}}">
            {{ csrf_field() }}
            <input id="search-product-url" type="hidden" name="url">
            <input id="search-product-url" type="hidden" name="product_id" value="{{$product_id}}">
        </form>
        <?php if(!empty($productImage)) { ?>
        <?php foreach($productImage as $i => $result) { ?>
        <div class="row">
            <h1>Result for : <img style="max-height: 500px; max-width: 500px;" src="<?php echo $i; ?>"></h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="well">
                    <p><a href="javascript:;">Guess Labels</a></p>
                    <p>
                        <?php if(!empty($result[ "labels" ])) { ?>
                        <?php foreach($result[ "labels" ] as $labels) { ?>
                        <span class="label label-default"><?php echo $labels; ?></span>
                        <?php } ?>
                        <?php } ?>
                    </p>
                </div>
                <div class="well">
                    <p><a href="javascript:;">Web Entities</a></p>
                    <p>
                        <?php if(!empty($result[ "entities" ])) { ?>
                        <?php foreach($result[ "entities" ] as $entities) { ?>
                        <span class="label label-default"><?php echo $entities; ?></span>
                        <?php } ?>
                        <?php } ?>
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            <h1>Best Matching Images in Site</h1>
                        </div>
                        <?php if(!empty($result[ "pages" ])){ ?>
                        <?php $i = 0; ?>
                        <div class="row">
                            <?php foreach($result[ "pages" ] as $pages) { ?>
                            <div class="col-md-4" style="float:left">
                                <div class="panel panel-primary">
                                    <div class="panel-footer" <?php if (stristr($pages, '.gucci.')) echo " style='background-color: lightgreen;'";?>>
                                        <?php echo isset($result[ 'pages_media' ][ $i ]) ? '<img src="' . $result[ 'pages_media' ][ $i ] . '" style="width: 100%; height: auto;">' : ''; ?>
                                        @php
                                            if ( isset($result[ 'pages_media' ][ $i ]) ) {
                                                $sku = \App\Helpers\ProductHelper::getSkuFromImage($result[ 'pages_media' ][ $i ]);
                                                if ( !empty($sku) ) {
                                        @endphp
                                        <input type="text" value="{!! $sku !!}" style="margin: 5px;"/> <a href="https://google.com/search?q=%22{!! $sku !!}%22" target="_blank">Search Online</a><br/>
                                        @php
                                            }
                                        }
                                        @endphp
                                        <a href="<?php echo $pages; ?>" target="__blank">
                                            <button title="<?php echo $pages; ?>" class="btn btn-secondary">Go To <?php echo substr($pages, 0, 30) ?>...</button>
                                            <br/>
                                        </a>
                                        <br/>
                                        <button data-id="{{ $product_id }}" data-url="{{ $pages }}" class="btn btn-secondary btn-scrape">Scrape this product</button>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                            @if (( $i % 3 ) == 0 && $i != count($result['pages']) )
                            </row>
                            @endif
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <div class="well">
                            <h1> Best Full matching Images</h1>
                        </div>
                        <?php if(!empty($result[ "matching_images" ])){ ?>
                        <?php foreach($result[ "matching_images" ] as $images) { ?>
                        <div class="col-md-4" style="float:left">
                            <div class="panel panel-primary">
                                <div class="panel-body"><img src="<?php echo $images; ?>" class="img-responsive" style="width:250px; height:250px;" alt="Image"></div>
                                <div class="panel-footer">
                                    <button data-href="<?php echo $images; ?>" class="btn btn-secondary btn-img-details">
                                        Get Details
                                    </button>
                                    <button class="btn btn-secondary add-product" data-product="{{$product_id}}" data-href="<?php echo $images; ?>">
                                        Add Product
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <div class="well">
                            <h1> Best Partial matching Images</h1>
                        </div>
                        <?php if(!empty($result[ "partial_matching" ])){ ?>
                        <?php foreach($result[ "partial_matching" ] as $images) { ?>
                        <div class="col-md-4" style="float:left;">
                            <div class="panel panel-primary">
                                <div class="panel-body"><img src="<?php echo $images; ?>" class="img-responsive" style="width:250px; height:250px;" alt="Image"></div>
                                <div class="panel-footer" <?php if (stristr($images, '.gucci.')) echo " style='background-color: lightgreen;'";?>>
                                    <button data-href="<?php echo $images; ?>" class="btn btn-secondary btn-img-details">
                                        Get Details
                                    </button>
                                    <button class="btn btn-secondary add-product" data-product="{{$product_id}}" data-href="<?php echo $images; ?>">
                                        Add Product
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <div class="well">
                            <h1> Best Similar matching Images</h1>
                        </div>
                        <?php if(!empty($result[ "similar_images" ])){ ?>
                        <?php foreach($result[ "similar_images" ] as $images) { ?>
                        <div class="col-md-4" style="float:left">
                            <div class="panel panel-primary">
                                <div class="panel-body"><img src="<?php echo $images; ?>" class="img-responsive" style="width:250px; height:250px;" alt="Image"></div>
                                <div class="panel-footer" <?php if (stristr($images, '.gucci.')) echo " style='background-color: lightgreen;'";?>>
                                    <button data-href="<?php echo $images; ?>" class="btn btn-secondary btn-img-details">
                                        Get Details
                                    </button>
                                    <button class="btn btn-secondary add-product" data-product="{{$product_id}}" data-href="<?php echo $images; ?>">
                                        Add Product
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
    <div class="row">
        <a class="btn btn-secondary" href="<?php echo url("google-search-image"); ?>">Back</a>
    </div>
    <div id="productModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Product</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" value="">
                    <div class="form-group">
                        <strong>Image:</strong>
                        <input type="hidden" class="form-control" name="image"
                               value="image" id="product-image"/>
                    </div>

                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" class="form-control" name="name" placeholder="Name"
                               value="{{ $product->name }}" id="product-name"/>
                        @if ($errors->has('name'))
                            <div class="alert alert-danger">{{$errors->first('name')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>SKU:</strong>
                        <input type="text" class="form-control" name="sku" placeholder="SKU"
                               value="" id="product-sku"/>
                        @if ($errors->has('sku'))
                            <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Color:</strong>
                        <input type="text" class="form-control" name="color" placeholder="Color"
                               value="{{ $product->color }}" id="product-color"/>
                        @if ($errors->has('color'))
                            <div class="alert alert-danger">{{$errors->first('color')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Brand:</strong>
                        <?php
                        $brands = \App\Brand::getAll();
                        echo Form::select('brand', $brands, $product->brand, ['placeholder' => 'Select a brand', 'class' => 'form-control', 'id' => 'product-brand']);?>
                        {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                        @if ($errors->has('brand'))
                            <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Price: (Euro)</strong>
                        <input type="number" class="form-control" name="price" placeholder="Price (Euro)"
                               value="{{ $product->price }}" step=".01" id="product-price"/>
                        @if ($errors->has('price'))
                            <div class="alert alert-danger">{{$errors->first('price')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Price:</strong>
                        <input type="number" class="form-control" name="price_special" placeholder="Price"
                               value="{ $product->price_special }}" step=".01" id="product-price-special"/>
                        @if ($errors->has('price_inr_special'))
                            <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Size:</strong>
                        <input type="text" class="form-control" name="size[]" placeholder="Size"
                               value="{{$product->size }}" id="product-size"/>
                        @if ($errors->has('size'))
                            <div class="alert alert-danger">{{$errors->first('size')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Quantity:</strong>
                        <input type="number" class="form-control" name="quantity" placeholder="Quantity"
                               value="{{ $product->quantity }}" id="product-quantity"/>
                        @if ($errors->has('quantity'))
                            <div class="alert alert-danger">{{$errors->first('quantity')}}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary createProduct">Create</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">

        var detailsBtn = $(".btn-img-details");
        detailsBtn.on("click", function () {
            var $this = $(this);
            $("#search-product-url").val($(this).data("href"));
            $("#search-product-url").closest("form").submit();
        });

        $('.add-product').on("click", function () {
            $('#product-image').val($(this).data("href"));
            $('#productModal').modal('show');
        });

        $('.btn-scrape').on("click", function () {
            var url = $(this).data('url');
            var id = $(this).data('id');
            console.log(url);
            console.log(id);
            var tmpForm = $('<form action="<?php echo route("google.search.queue"); ?>" method="post"><?php echo csrf_field(); ?><input type="hidden" name="product_id" value="' + id + '"><input type="hidden" name="url" value="' + url + '"></form>').appendTo('body').submit();
        });

        $('.createProduct').on('click', function () {
            var token = "{{ csrf_token() }}";
            var url = "{{ route('products.store') }}";
            // var order_id = $(this).data('orderid');
            var order_id = $('input[name="order_id"]').val();
            var image = $('#product-image').val();
            var name = $('#product-name').val();
            var sku = $('#product-sku').val();
            var color = $('#product-color').val();
            var brand = $('#product-brand').val();
            var price = $('#product-price').val();
            var price_special = $('#product-price-special').val();
            var size = $('#product-size').val();
            var quantity = $('#product-quantity').val();
            var thiss = $(this);
            if (name == '') {
                alert('Please Enter name!');
                return false;
            }

            if (sku == '') {
                alert('Please Enter sku!');
                return false;
            }

            var form_data = new FormData();
            form_data.append('_token', token);
            form_data.append('order_id', order_id);
            form_data.append('image', image);
            form_data.append('name', name);
            form_data.append('sku', sku);
            form_data.append('color', color);
            form_data.append('brand', brand);
            form_data.append('price', price);
            form_data.append('price_inr_special', price_inr_special);
            form_data.append('size', size);
            form_data.append('quantity', quantity);
            form_data.append('is_image_url', '1');

            $.ajax({
                type: 'POST',
                url: url,
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                data: form_data,
                beforeSend: function () {
                    $(thiss).text('Creating...');
                }
            }).done(function (response) {
                $('#productModal').find('.close').click();
            }).fail(function (response) {
                $(thiss).text('Create');

                console.log(response);
                alert('Could not create a product!');
            });
        });

    </script>


@endsection
