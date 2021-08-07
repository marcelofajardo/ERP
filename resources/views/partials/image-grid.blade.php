@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style type="text/css">
        .select-multiple-cat-list .select2-container {
            position: relative;
            z-index: 2;
            float: left;
            width: 100%;
            margin-bottom: 0;
            display: table;
            table-layout: fixed;
        }
        /*.update-product + .select2-container--default{
            width: 60% !important;
        }*/

        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
        .carousel-control:focus, .carousel-control:hover {
            color: #0284b8 !important;
        }

        .product-slider { padding: 45px; }

        
          .product-slider #carousel { margin: 0; }

           .product-slider .maincarousel .item { height: 150px; } 

           .product-slider .carousel-inner img {object-fit: contain;}

          .product-slider .thumbcarousel { margin: 12px 0 0; padding: 0 45px; }

          .product-slider .thumbcarousel .item { text-align: center; }

          .product-slider .thumbcarousel .item .thumb {  width: 20%; margin: 0 2%; display: inline-block; vertical-align: middle; cursor: pointer; max-width: 35px; }

          .product-slider .maincarousel .item img { width: 100%; height: 150px; }

          .carousel-control { color: #0284b8; text-align: center; text-shadow: none; font-size: 30px; width: 30px; height: 30px; line-height: 20px; top: 23%; }
          .carousel-caption, .carousel-control .fa { font: normal normal normal 30px/26px FontAwesome; }
          .carousel-control { background-color: rgba(0, 0, 0, 0); bottom: auto; font-size: 20px; left: 0; position: absolute; top: 30%; width: auto; }

          .carousel-control.right, .carousel-control.left { background-color: rgba(0, 0, 0, 0); background-image: none; }

    </style>
@endsection

@section('content')
 <div id="myDiv">
       <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
   </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="">

                <!--roletype-->
                <h2 class="page-heading">Attach Images to Message (<span id="products_count">{{ $products_count }}</span>) @if($customerId != null)
                    @if(auth()->user()->isInCustomerService())
                        #{{ $customerId }} 
                    @else
                        @php $customer = \App\Customer::find($customerId)  @endphp
                        {{  ($customer) ? $customer->name : "" }} 
                    @endif
                @endif</h2>

                <!--pending products count-->
                @if(auth()->user()->isAdmin())
                    @if( $roletype != 'Selection' && $roletype != 'Sale' )
                        <div class="pt-2 pb-3">
                            <a href="{{ route('pending',$roletype) }}"><strong>Pending
                                    : </strong> {{ \App\Product::getPendingProductsCount($roletype) }}</a>
                        </div>
                    @endif
                @endif

            <!--attach Product-->
                @if( isset($doSelection) )
                    <p><strong> {{ strtoupper($model_type)  }} ID : {{ $model_id }} </strong></p>
            @endif

            <!--Product Search Input -->
                <form action="{{ url()->current() }}" method="GET" id="searchForm" class="form-inline align-items-start">
                    <input type="hidden" name="source_of_search" value="attach_media">
                    <input type="hidden" name="return_url" value="{{ request('return_url') }}">
                    <input type="hidden" name="from_account" value="{{ request('from_account') }}">
                    <input type="hidden" name="selected_products" id="selected_products" value="{{ request('selected_products') }}">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control" id="product-search"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="sku,brand,category,status,stage">
                        <input hidden name="roletype" type="text" value="{{ $roletype }}">
                        <input hidden name="model_type" type="text" value="{{ $model_type }}">
                        {{--@if( $roletype == 'Sale' )
                            <input hidden name="saleId" type="text" value="{{ $sale_id ?? '' }}">
                        @endif--}}
                        @if( isset($doSelection) )
                            <input hidden name="doSelection" type="text" value="true">
                            <input hidden name="model_id" type="text" value="{{ $model_id ?? '' }}">
                            <input hidden name="model_type" type="text" value="{{ $model_type ?? '' }}">
                            <input hidden name="assigned_user" type="text" value="{{ $assigned_user ?? '' }}">
                            <input hidden name="status" type="text" value="{{ $status ?? '' }}">
                        @endif
                    </div>
                    <div class="form-group mr-3">
                        {!! $category_selection !!}
                    </div>

                    <div class="form-group mr-3">
                        @php $brands = \App\Brand::where("magento_id",">",0)->pluck("name","id"); @endphp
                        {{-- {!! Form::select('brand[]',$brands, (isset($brand) ? $brand : ''), ['placeholder' => 'Select a Brand','class' => 'form-control select-multiple', 'multiple' => true]) !!} --}}
                        <select class="form-control select-multiple" name="brand[]" multiple data-placeholder="Brands...">
                            <optgroup label="Brands">
                                @foreach ($brands as $key => $name)
                                    <option value="{{ $key }}" {{ isset($brand) && is_array($brand) && in_array($key,$brand) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        {{-- <strong>Color</strong> --}}
                        @php 
                            $color  = request('color',[]);  
                            $colors = new \App\Colors(); 
                        @endphp
                        {{-- {!! Form::select('color[]',$colors->all(), (isset($color) ? $color : ''), ['placeholder' => 'Select a Color','class' => 'form-control select-multiple', 'multiple' => true]) !!} --}}
                        <select class="form-control select-multiple" name="color[]" multiple data-placeholder="Colors...">
                            <optgroup label="Colors">
                                @foreach ($colors->all() as $key => $col)
                                    <option value="{{ $key }}" {{ isset($color) && is_array($color) && in_array($key, $color) ? 'selected' : '' }}>{{ $col }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        @php 
                            $supplier  = request('supplier',[]);
                        @endphp
                        <select class="form-control select-multiple" name="supplier[]" multiple data-placeholder="Supplier...">
                            <optgroup label="Suppliers">
                                @foreach ($suppliers as $key => $supp)
                                    <option value="{{ $supp->id }}" {{ isset($supplier) && is_array($supplier) && in_array($supp->id, $supplier)  ? 'selected' : '' }}>{{ $supp->supplier }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    @if (Auth::user()->hasRole('Admin'))
                        @php
                            $location  = request('location',[]);  
                        @endphp
                        <div class="form-group mr-3">
                            <select class="form-control select-multiple" name="location[]" multiple data-placeholder="Location...">
                                <optgroup label="Locations">
                                    @foreach ($locations as $name)
                                        <option value="{{ $name }}" {{ isset($location) && is_array($location) && in_array($name,$location) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    @endif

                    <div class="form-group mr-3">
                        <input name="discounted_percentage_min" type="text" class="form-control"
                               value="{{ request('discounted_percentage_min') }}"
                               placeholder="Discount % min">
                    </div>
                    <div class="form-group mr-3">
                        <input name="discounted_percentage_max" type="text" class="form-control"
                               value="{{ request('discounted_percentage_max') }}"
                               placeholder="Discount % max">
                    </div>
                    <div class="form-group mr-3">
                        <input name="size" type="text" class="form-control"
                               value="{{ request('size') }}"
                               placeholder="Size">
                    </div>
                     <div class="form-group mr-3">
                        <select class="form-control select-multiple" name="quick_sell_groups[]" multiple data-placeholder="Quick Sell Groups...">
                            @foreach ($quick_sell_groups as $key => $quick_sell)
                                <option value="{{ $quick_sell->id }}" {{ in_array($quick_sell->id, request()->get('quick_sell_groups', [])) ? 'selected' : '' }}>{{ $quick_sell->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        {!! Form::select('per_page',[
                        "20" => "20 Images Per Page",
                        "30" => "30 Images Per Page",
                        "50" => "50 Images Per Page",
                        "100" => "100 Images Per Page",
                        ], request()->get("per_page",null), ['placeholder' => '-- Select Images Per Page --','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group mr-3">
                        <strong class="mr-3">Price</strong>
                        <?php 
                            $price =  explode(",",request('price'));
                            $min = 0;
                            $max = 400000;
                            if(isset($price[0])) {
                                $min = (float)$price[0];
                            }

                            if(isset($price[1])) {
                                $max = (float)$price[1];
                            }
                        ?>
                        <input type="text" name="price" data-provider="slider" data-slider-min="0" data-slider-max="400000" data-slider-step="1000" data-slider-value="[{{$min}},{{$max}}]"/>
                    </div>


                    <input type="hidden" name="message" value="{{ $model_type == 'customers' ? "$message_body" : 'Images attached from grid' }}" id="attach_all_message">
                    <input type="hidden" name="{{ $model_type == 'customer' ? 'customer_id' : 'nothing' }}" value="{{ $model_id }}" id="attach_all_model_id">
                    <input type="hidden" name="status" value="{{ $status }}" id="attach_all_status">
                    &nbsp;
                    <input type="checkbox" class="is_on_sale" {{ (request('is_on_sale')) == 'on' ? 'checked' : '' }} id="is_on_sale" name="is_on_sale"><label
                            for="is_on_sale">Sale Products</label>
                    <input type="checkbox" class="random" {{ (request('random')) == 'on' ? 'checked' : '' }} id="random" name="random"><label
                            for="random">Random</label>
                    <input type="checkbox" class="unsupported" id="unsupported" {{ (request('unsupported')) == 'on' ? 'checked' : '' }} name="unsupported"><label
                            for="unsupported">Unsupported Images</label>
                    <input type="checkbox" class="drafted_product" id="drafted_product" {{ (request('drafted_product')) == 'on' ? 'checked' : '' }} name="drafted_product">
                    <label for="drafted_product">Drafted Product</label>
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                </form>

                <form action="{{ url()->current() }}" method="GET" id="quickProducts" class="form-inline align-items-start my-3">
                    <input type="hidden" name="quick_product" value="true">
                    <button type="submit" class="btn btn-xs btn-secondary">Quick Sell</button>
                </form>
                <button type="button" class="btn btn-secondary select-all-product-btn" data-count="<?php echo (isset($products_count)) ? $products_count : 0; ?>">Select All</button>
                <button type="button" class="btn btn-secondary select-all-product-btn" data-count="20">Select 20</button>
                <button type="button" class="btn btn-secondary select-all-product-btn" data-count="30">Select 30</button>
                <button type="button" class="btn btn-secondary select-all-product-btn" data-count="50">Select 50</button>
                <button type="button" class="btn btn-secondary select-all-product-btn" data-count="100">Select 100</button>
                <button type="button" class="btn btn-secondary select-all-same-page-btn" data-count="100">Select All Current Page</button>
                <a class="btn btn-secondary" 
                data-toggle="collapse" href="#brandFilterCount" role="button" aria-expanded="false" aria-controls="brandFilterCount">
                   Show Brand(s) count 
                </a>
                <a class="btn btn-secondary" 
                data-toggle="collapse" href="#categoryFilterCount" role="button" aria-expanded="false" aria-controls="categoryFilterCount">
                   Show Categories count
                </a>
                <a class="btn btn-secondary" style="margin-top: 3px;" 
                data-toggle="collapse" href="#suppliersFilterCount" role="button" aria-expanded="false" aria-controls="suppliersFilterCount">
                   Show suppliers count
                </a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <?php
      $query = http_build_query( Request::except( 'page' ) );
      $query = url()->current() . ( ( $query == '' ) ? $query . '?page=' : '?' . $query . '&page=' );
    ?>

    <div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;">
        Goto :
        <select onchange="location.href = this.value;" class="form-control" id="page-goto">
            @for($i = 1 ; $i <= $products->lastPage() ; $i++ )
                <option data-value="{{ $i }}" value="{{ $query.$i }}" {{ ($i == $products->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    @include('partials.image-load-category-count')
    <div class="productGrid" id="productGrid">
        @include('partials.image-load')
    </div>
    @php
        $action = url('whatsapp/updateAndCreate/');

        if ($model_type == 'images') {
            $action =  route('image.grid.attach');
        } else if ($model_type == 'customers') {
            $action =  route('customer.whatsapp.send.all', 'false');
        } else if ($model_type == 'purchase-replace') {
            $action =  route('purchase.product.replace');
        } else if ($model_type == 'broadcast-images') {
            $action =  route('broadcast.images.link');
        } else if ($model_type == 'customer' || $model_type == 'livechat') {
            $action =  route('attachImages.queue');
        } else if ($model_type == 'selected_customer' || $model_type == 'selected_customer_token') {
            $action =  route('whatsapp.send_selected_customer');
        } else if ($model_type == 'product-templates') {
            $action =  route('product.templates');
        } else if ($model_type == 'landing-page') {
            $action =  route('landing-page.save');
        } else if($model_type == 'live-chat') {
            $action =  route('live-chat.attach.image');
        } else if ($model_type == 'direct'){
            $action =  route('direct.send.file');
        } else if ($model_type == 'newsletters'){
            $action =  route('newsletters.save');
        }
        else if($model_type=='instagram-post')
        {
            $action =route('instagram.post.images');
        }
    @endphp
    <form action="{{ $action }}" data-model-type="{{$model_type}}" method="POST" id="attachImageForm">
        @csrf
        <input type="hidden" id="send_pdf" name="send_pdf" value="0"/>
        <input type="hidden" id="pdf_file_name" name="pdf_file_name" value=""/>
        @if ($model_type == 'customers')
            <input type="hidden" name="sending_time" value="{{ $sending_time }}"/>
        @endif
        
        @if (request()->get('return_url'))
            <input type="hidden" name="return_url" value="{{ request()->get('return_url') }}"/>
        @endif
        @if (request()->get('from_account'))
            <input type="hidden" name="from_account" value="{{ request()->get('from_account') }}"/>
        @endif

        <input type="hidden" name="images" id="images" value="">
        <input type="hidden" name="image" value="">
        <input type="hidden" name="is_queue" value="0" id="is_queue_setting">
        <input type="hidden" name="screenshot_path" value="">
        <input type="hidden" name="message" value="{{ $model_type == 'customers' || $model_type == 'selected_customer' || $model_type == 'livechat' || $model_type == 'live-chat' ? "$message_body" : '' }}">
        <input type="hidden" name="{{ $model_type == 'customer' || $model_type == 'livechat' || $model_type == 'live-chat' ? 'customer_id' : ($model_type == 'purchase-replace' ? 'moduleid' : ($model_type == 'selected_customer' ? 'customers_id' : 'nothing')) }}" value="{{ $model_id }}">
        <input type="hidden" name="customer_token" value="<?php echo ($model_type == 'selected_customer_token') ? $model_id : '' ?>">
        {{-- <input type="hidden" name="moduletype" value="{{ $model_type }}">
        <input type="hidden" name="assigned_to" value="{{ $assigned_user }}" /> --}}
        <input type="hidden" name="status" value="{{ $status }}">
    </form>
    <div id="confirmPdf" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Choose the format for sending</p>
                    <div class="form-group mr-3">
                        <strong class="mr-3">Custom File Name</strong>
                        <input type="text" name="file_name" id="pdf-file-name" />
                    </div>
                    <div class="form-group mr-3">
                        <strong class="mr-3">Is Queue?</strong>
                        <select class="form-control" id="is_queue_option" name="is_queue_option">
                            <option>Select queue</option>
                            <option value="1">in Queue</option>
                            <option value="0">Send later</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-approve-pdf">PDF</button>
                    <button type="button" class="btn btn-secondary btn-ignore-pdf">Images</button>
                </div>
            </div>
        </div>
    </div>
    @include('partials.modals.category')
    <?php $stage = new \App\Stage(); ?>
    <script src="/js/bootstrap-multiselect.min.js"></script>
    <script src="/js/jquery.jscroll.min.js"></script>
    <script>

        var infinteScroll = function() {

            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                padding: 2500,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function () {
                   $('.lazy').Lazy({
                        effect: 'fadeIn'
                   });
                   $('ul.pagination:visible:first').remove();
                    var next_page = $('.pagination li.active + li a');
                    var page_number = next_page.attr('href').split('page=');
                    var current_page = page_number[1] - 1;
                    $('#page-goto option[data-value="' + current_page + '"]').attr('selected', 'selected');
                    categoryChange();
                }
            });

        };

        var categoryChange = function() 
        {   

            $("select.select-multiple-cat-list:not(.select2-hidden-accessible)").select2();
            $('select.select-multiple-cat-list:not(.select2-hidden-accessible)').on('select2:close', function (evt) {
                var uldiv = $(this).siblings('span.select2').find('ul')
                var count = uldiv.find('li').length - 1;
                if (count == 0) {
                } else {
                    uldiv.html('<li class="select2-selection__choice">' + count + ' item selected</li>');
                }
            });

        };

        categoryChange();

        $(".select-multiple2").select2();
        
        var image_array = [];
        //var all_product_ids = [<?= implode(',', $all_product_ids) ?>];
        $(document).ready(function () {
            
            infinteScroll();
            $(".select-multiple").select2();
            //$(".select-multiple-cat").multiselect();
            $("body").tooltip({selector: '[data-toggle=tooltip]'});
            $('.lazy').Lazy({
                effect: 'fadeIn'
            });
            $(document).on("click",".select-all-same-page-btn",function(e){
                e.preventDefault();
                var $this = $(this);
                if($this.hasClass("has-all-selected") === false) {
                    $this.html("Deselect All From Current Page");
                    $(".select-pr-list-chk").prop("checked", true).trigger('change');
                    $this.addClass("has-all-selected");
                }else{
                    $this.html("Select All Current Page");
                    $(".select-pr-list-chk").prop("checked", false).trigger('change');
                    $this.removeClass("has-all-selected");
                }
            });

            var selectAllBtn = $(".select-all-product-btn");
            selectAllBtn.on("click", function (e) {
                var $this = $(this);
                var vcount = 0;

                vcount = $this.data('count');
                if (vcount == 0) {
                    vcount = 'all';
                }
                var productCardCount = $(".product-list-card").length;

                if((vcount == "all" || 1 == 1) && $this.hasClass("has-all-selected") === false && (productCardCount < vcount || vcount == "all") ) {

                    e.preventDefault();

                    $('#selected_products').val(JSON.stringify(image_array));
                    /*var url = "";
                    var pageLink = $(".pagination").find('.page-link');
                        if(pageLink.length > 0) {
                            $.each(pageLink, function(k,v) {
                                var href = $(v).attr("href");
                                if(typeof href != "undefined") {
                                    url = href;
                                    return false;
                                }
                            });
                        }

                    
                    url = replaceUrlParam(url,'page','1');
                    */

                    $('#selected_products').val(JSON.stringify(image_array));
                    var formData = $('#searchForm').serializeArray();
                    formData.push({name: "limit", value: vcount}) ;
                    formData.push({name: "page", value: 1}) ;
                    
                    if (isQuickProductsFrom) {
                        formData.push({name: "quick_product", value: 'true'});
                    };
                    
                    var url = "{{ url()->current() }}";


                    $.ajax({
                        url: url,
                        data : formData,
                        beforeSend: function() {
                            $('#productGrid').html('<img id="loading-image" src="/images/pre-loader.gif"/>');
                        }
                    }).done(function (data) {
                        //all_product_ids = data.all_product_ids;
                        $('#productGrid').html(data.html);
                        $('#products_count').text(data.products_count);
                        $('.lazy').Lazy({
                            effect: 'fadeIn'
                        });

                        infinteScroll();

                        if ($this.hasClass("has-all-selected") === false) {
                            $this.html("Deselect " + vcount);
                            if (vcount == 'all') {
                                $(".select-pr-list-chk").prop("checked", true).trigger('change');
                            } else {
                                var boxes = $(".select-pr-list-chk");
                                for (i = 0; i < vcount; i++) {
                                    try {
                                        $(boxes[i]).prop("checked", true).trigger('change');
                                    } catch (err) {
                                    }
                                }
                            }
                            $this.addClass("has-all-selected");
                        } 
                    }).fail(function () {
                        alert('Error searching for products');
                    });

                }else {
                    if ($this.hasClass("has-all-selected") === false) {
                        $this.html("Deselect " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", true).trigger('change');
                        } else {
                            var boxes = $(".select-pr-list-chk");
                            for (i = 0; i < vcount; i++) {
                                try {
                                    $(boxes[i]).prop("checked", true).trigger('change');
                                } catch (err) {
                                }
                            }
                        }
                        $this.addClass("has-all-selected");
                    }else {
                        $this.html("Select " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", false).trigger('change');
                        } else {
                            var boxes = $(".select-pr-list-chk");
                            for (i = 0; i < vcount; i++) {
                                try {
                                    $(boxes[i]).prop("checked", false).trigger('change');
                                } catch (err) {
                                }
                            }
                        }
                        $this.removeClass("has-all-selected");
                    }
                }

                /*// Add all images to array
                image_array = [];
                console.log(all_product_ids.length);
                for (i = 0; i < all_product_ids.length && i < vcount; i++) {
                    image_array.push(all_product_ids[i]);
                }
                image_array = unique(image_array);
                console.log(image_array);*/
            })
        });

        function unique(list) {
            var result = [];
            $.each(list, function (i, e) {
                if ($.inArray(e, result) == -1) result.push(e);
            });
            return result;
        }

        $(document).on('change', '.select-pr-list-chk', function (e) {
            var $this = $(this);
            var productCard = $this.closest(".product-list-card").find(".attach-photo");
            if (productCard.length > 0) {
                var image = productCard.data("image");
                if ($this.is(":checked") === true) {
                    //Object.keys(image).forEach(function (index) {
                    image_array.push(image);
                    //});
                    image_array = unique(image_array);

                } else {
                    //Object.keys(image).forEach(function (key) {
                    var index = image_array.indexOf(image);
                    image_array.splice(index, 1);
                    //});
                    image_array = unique(image_array);
                }
            }
        });


        // $('#product-search').autocomplete({
        //   source: function(request, response) {
        //     var results = $.ui.autocomplete.filter(searchSuggestions, request.term);
        //
        //     response(results.slice(0, 10));
        //   }
        // });

        /*$(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var url = $(this).attr('href') + '&selected_products=' + JSON.stringify(image_array);

            getProducts(url);
        });*/

        /*function getProducts(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                console.log(data);
                $('#productGrid').html(data.html);
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
            }).fail(function () {
                alert('Error loading more products');
            });
        }*/

        $(document).on('click', '.attach-photo', function (e) {
            e.preventDefault();
            var image = $(this).data('image');

            if ($(this).attr('data-attached') == 0) {
                $(this).attr('data-attached', 1);
                image_array.push(image);
            } else {
                var index = image_array.indexOf(image);

                $(this).attr('data-attached', 0);
                image_array.splice(index, 1);
            }

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            console.log(image_array);
        });

        $(document).on('click', '.attach-photo-all', function (e) {
            e.preventDefault();
            var image = $(this).data('image');
            if ($(this).attr('data-attached') == 0) {
                $(this).attr('data-attached', 1);

                Object.keys(image).forEach(function (index) {
                    image_array.push(image[index]);
                });
            } else {
                Object.keys(image).forEach(function (key) {
                    var index = image_array.indexOf(image[key]);

                    image_array.splice(index, 1);
                });

                $(this).data('attached', 0);
            }

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            console.log(image_array);
        });

        // $('#attachImageForm').on('submit', function(e) {
        //   e.preventDefault();
        //
        //   if (image_array.length == 0) {
        //     alert('Please select some images');
        //   } else {
        //     $('#images').val(JSON.stringify(image_array));
        //     alert(JSON.stringify(image_array));
        //     // $('#attachImageForm')[0].submit();
        //   }
        // });

        $('#searchForm button[type="submit"]').on('click', function (e) {
            e.preventDefault();
            isQuickProductsFrom = false;
            $('#selected_products').val(JSON.stringify(image_array));

            var url = "{{ url()->current() }}";
            var formData = $('#searchForm').serialize();
            $('#searchForm').submit();

            /*$.ajax({
                url: url,
                data: formData
            }).done(function (data) {
                //all_product_ids = data.all_product_ids;
                $('#productGrid').html(data.html);
                $('#products_count').text(data.products_count);
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
                infinteScroll();

            }).fail(function () {
                alert('Error searching for products');
            });*/
        });
        var isQuickProductsFrom = false;
        $('#quickProducts').on('submit', function (e) {
            e.preventDefault();
            isQuickProductsFrom = true;
            var url = "{{ url()->current() }}?quick_product=true";
            var formData = $('#searchForm').serialize();

            $.ajax({
                url: url,
                data: formData
            }).done(function (data) {
                $('#productGrid').html(data.html);
                $('#products_count').text(data.products_count);
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
                infinteScroll();
            }).fail(function () {
                alert('Error searching for products');
            });
        });


        // $('#product-search').on('keyup', function() {
        //   alert('t');
        // });

        {{--@if($roletype == 'Supervisor')
         @if(auth()->user()->checkPermission('productsupervisor-edit'))
        attactApproveEvent();
        @endif
        @endif--}}

        jQuery('.btn-attach').click(function (e) {

            e.preventDefault();

            let btn = jQuery(this);
            let product_id = btn.attr('data-id');
            let model_id = btn.attr('model-id');
            let model_type = btn.attr('model-type');


            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/attachProductToModel/' + model_type + '/' + model_id + '/' + product_id,

                success: function (response) {

                    if (response.msg === 'success') {
                        btn.toggleClass('btn-success');
                        btn.html(response.action);
                    }
                }
            });
        });

        $(document).on('click', '#sendImageMessage', function () {
            @if ($model_type == 'purchase-replace')
            if (image_array.length > 1) {
                alert('Please select only one product');
                return;
            }
            @endif

            if (image_array.length == 0) {
                alert('Please select some images');
            } else {
                $('#images').val(JSON.stringify(image_array));
                var form = $('#attachImageForm');
                var modelType = form.data("model-type");
                if(modelType == "selected_customer" || modelType == "customer" || modelType == "customers" || modelType == "livechat") {
                    $("#confirmPdf").modal("show");
                }else{
                    $('#attachImageForm').submit();
                }
            }
        });

        $(".btn-approve-pdf").on("click",function() {
            $("#send_pdf").val("1");
            $("#is_queue_setting").val($("#is_queue_option").val());
            $("#pdf_file_name").val($("#pdf-file-name").val());
            $('#attachImageForm').submit();
        });

        $(".btn-ignore-pdf").on("click",function() {
            $("#send_pdf").val("0");
            $("#is_queue_setting").val($("#is_queue_option").val());
            $("#pdf_file_name").val($("#pdf-file-name").val());
            $('#attachImageForm').submit();
        });
        // });

        $('#attachAllButton').on('click', function () {
            var url = "{{ route('customer.attach.all') }}";

            $('#searchForm').attr('action', url);
            $('#searchForm').attr('method', 'POST');

            $('#searchForm').submit();
        });

        function replaceUrlParam(url, paramName, paramValue)
        {
            if (paramValue == null) {
                paramValue = '';
            }
            var pattern = new RegExp('\\b('+paramName+'=).*?(&|#|$)');
            if (url.search(pattern)>=0) {
                return url.replace(pattern,'$1' + paramValue + '$2');
            }
            url = url.replace(/[?#]$/,'');
            return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
        }

        $(document).on('change', '.update-product', function () {    
            product_id = $(this).attr('data-id');
            category = $(this).find('option:selected').text();
            category_id = $(this).val();
            //Getting Scrapped Category
            $.ajax({
                url: '/products/'+product_id+'/originalCategory',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                  $("#loading-image").show();
                },
                success: function(result){
                    $("#loading-image").hide();
                    $('#categoryUpdate').modal('show');
                    if(result[0] == 'success'){
                        $('#old_category').text(result[1]);
                        $('#changed_category').text(category);
                        $('#product_id').val(product_id);
                        $('#category_id').val(category_id);
                        if(typeof result[2] != "undefined") {
                            $("#no_of_product_will_affect").html(result[2]);
                        }
                    }else{
                        $('#old_category').text('No Scraped Product Present');
                        $('#changed_category').text(category);
                        $('#product_id').val(product_id);
                        $('#category_id').val(category_id);
                        $("#no_of_product_will_affect").html(0);
                    }
                },
                error: function (){
                    $("#loading-image").hide();
                    $('#categoryUpdate').modal('show');
                    $('#old_category').text('No Scraped Product Present');
                    $('#changed_category').text(category);
                    $('#product_id').val(product_id);
                    $('#category_id').val(category_id);
                    $("#no_of_product_will_affect").html(0);
                }
            });

            
            //$('#categoryUpdate').modal('show');
            
        });        
        
        function changeSelected(){
            product_id = $('#product_id').val();
            category = $('#category_id').val();
            $.ajax({
                url: '/products/'+product_id+'/updateCategory',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    category : category
                },
                beforeSend: function () {
                              $('#categoryUpdate').modal('hide');  
                              $("#loading-image").show();
                              $("#loading-image").hide();
                          },
                });
        
        }

        function changeAll(){
            product_id = $('#product_id').val();
            category = $('#category_id').val();
            $.ajax({
                url: '/products/'+product_id+'/changeCategorySupplier',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    category : category
                },
                beforeSend: function () {
                              $('#categoryUpdate').modal('hide');  
                              $("#loading-image").show();
                          },
                success: function(result){
                     $("#loading-image").hide();
             }
         });
        }


        $(document).on("click",".btn-template-status",function(e) {
            
            e.preventDefault();
            
            if(image_array.length <= 0) {
                alert("Please select products first ");
            return false;
            }

            if(tpl <= 0) {
                alert("Please select template first");
            return false;
            }

            $('#input_product_ids').val(image_array)
            var tpl = $('#form_mail_tpl').val();
            $('#product-mail').attr('action', "{{ route('viewTemplate','') }}/"+tpl);
            $('#product-mail').submit();
            return true;
        });

    </script>

@endsection

@section('scripts')
<script type="text/javascript">
    function myFunction(id){
        $('#description'+id).toggle();
        $('#description_full'+id).toggle();
    }

    $(document).on("click",".attach-thumb-created .item",function(e){
        e.preventDefault();
        var imageID = $(this).find(".thumb").data("image");
        var card = $(this).closest(".product-list-card");
            card.find(".attach-photo").attr("data-image",imageID);
    });

    
</script>

@endsection