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
        .no-pd {
            padding:0px;
        }

        .select-multiple-cat-list + .select2-container {
            width:100% !important;
        }

        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }

        .row .btn-group .btn {
            margin: 0px;
        }
        .btn-group-actions{
            text-align: right;
        }

        .multiselect-supplier + .select2-container{
            width: 198px !important;
        }
        .size-input{
            width: 155px !important;
        }
        .quick-sell-multiple{
            width: 98px !important;
        }
        .image-filter-btn{
            padding: 10px;
            margin-top: -12px;
        }
        .update-product + .select2-container{
            width: 150px !important;
        }
        .product-list-card > .btn, .btn-sm {
            padding: 5px;
        }

        .select2-container {
            width:100% !important;
            min-width:200px !important;   
        }
        .no-pd {
            padding:3px;
        }
        .mr-3 {
            margin:3px;
        }
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
                <h2 class="page-heading">Image Approval to Message <span id="products_count"></span> @if($customerId != null) 
                    @if(auth()->user()->isInCustomerService())
                        #{{ $customerId }} 
                    @else
                        {{ \App\Customer::find($customerId)->name }} 
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
                    <div class="form-group col-md-2 mr-3 mb-3 no-pd">
                        <input name="term" type="text" class="form-control" id="product-search"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="Search by SKU or product id" style="width:100%;">
                        @if( isset($doSelection) )
                            <input hidden name="doSelection" type="text" value="true">
                            <input hidden name="model_id" type="text" value="{{ $model_id ?? '' }}">
                            <input hidden name="model_type" type="text" value="{{ $model_type ?? '' }}">
                            <input hidden name="assigned_user" type="text" value="{{ $assigned_user ?? '' }}">
                            <input hidden name="status" type="text" value="{{ $status ?? '' }}">
                        @endif
                    </div>
                    <div class="form-group col-md-2 mr-3 no-pd">
                        {!! $category_selection !!}
                    </div>

                    <div class="form-group col-md-3 mr-3 no-pd">
                        @php $brands = \App\Brand::pluck("name","id"); @endphp
                        {{-- {!! Form::select('brand[]',$brands, (isset($brand) ? $brand : ''), ['placeholder' => 'Select a Brand','class' => 'form-control select-multiple', 'multiple' => true]) !!} --}}
                        <select class="form-control select-multiple brands" name="brand[]" multiple data-placeholder="Brands...">
                            <optgroup label="Brands">
                                @foreach ($brands as $key => $name)
                                    <option value="{{ $key }}" {{ isset($brand) && is_array($brand) && in_array($key,$brand) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group col-md-3 mr-3 no-pd">
                        <!-- <select class="form-control customer-search" name="customer_id" data-placeholder="Customer..." data-allow-clear="true">
                                <option value="">Select customer...</option>
                                @foreach ($customers as $key => $customer)
                                    <option value="{{ $key }}" {{ isset($customerId) && $customerId == $key ? 'selected' : '' }}>{{ $customer }}</option>
                                @endforeach
                        </select> -->

                        <select name="customer_id" type="text" class="form-control" placeholder="Search" id="customer-search" data-allow-clear="true">
                            <?php 
                                if (request()->get('customer_id')) {
                                    echo '<option value="'.request()->get('customer_id').'" selected>'.request()->get('customer_id').'</option>';
                                }
                            ?>
                        </select>
                    </div>


                    <div class="col-md-1 no-pd">
                    <input type="hidden" name="message" value="{{ $model_type == 'customers' ? "$message_body" : 'Images attached from grid' }}" id="attach_all_message">
                    <input type="hidden" name="status" value="{{ $status }}" id="attach_all_status">
                    &nbsp;
                    <button type="submit" class="btn btn-image image-filter-btn"><img src="/images/filter.png"/></button>
                    
                    <button type="button" class="btn btn-xs btn-secondary forward-all-products mr-3" title="Attach images to new Customer"><i class="fa fa-paperclip" aria-hidden="true"></i></button>

                    </div>
                </form>
  
            <!-- <div class="row mt-3">
                <div class="col-11 btn-group-actions">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" id="select-all-product" data-count="<?php echo (isset($products_count)) ? $products_count : 0; ?>">Select All</button>
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" data-count="10">Select 10</button>
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" data-count="20">Select 20</button>
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" data-count="30">Select 30</button>
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" data-count="50">Select 50</button>
                        <button type="button" class="btn btn-xs btn-secondary select-all-product-btn" data-count="100">Select 100</button>
                    </div>
                </div>
            </div> -->
            
            </div>
        </div>
    </div>

    @include('partials.flash_messages')



    
    <div>
    </div>


    <div class="col-md-12 margin-tb">
        <div class="table-responsive">
            <table class="table table-bordered" style="table-layout:fixed;">
                <thead>
                <th style="width:7%">Date</th>
                <th style="width:8%">Id</th>
                <th style="width:15%">Name</th>
                <th style="width:10%">Phone</th>
                <th style="width:20%">Brand</th>
                <th style="width:20%">Category</th>
                <th style="width:20%">Action</th>
                </thead>
                <tbody class="infinite-scroll-data">
                    @include('partials.attached-image-load')
                </tbody>
            </table>
        </div>
    </div>






    @include('partials.image-load-category-count')
    <!-- <div class="productGrid" id="productGrid">
        @include('partials.attached-image-load')
    </div> -->
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
        }else if ($model_type == 'direct'){
            $action =  route('direct.send.file');
        }
    @endphp
    <form action="{{ $action }}" data-model-type="{{$model_type}}" method="POST" id="attachImageForm">
        @csrf
        <input type="hidden" id="send_pdf" name="send_pdf" value="0"/>
        <input type="hidden" id="pdf_file_name" name="pdf_file_name" value=""/>
        @if ($model_type == 'customers')
            <input type="hidden" name="sending_time" value="{{ $sending_time }}"/>
        @endif
            <input type="hidden" name="return_url" id="hidden-return-url" value="{{ request()->get('return_url') }}"/>

        <input type="hidden" name="images" id="images" value="">
        <input type="hidden" name="image" value="">
        <input type="hidden" name="is_queue" value="0" id="is_queue_setting">
        <input type="hidden" name="json" value="0" id="hidden-json">

        <input type="hidden" name="screenshot_path" value="">
        <input type="hidden" name="message" value="{{ $model_type == 'customers' || $model_type == 'selected_customer' || $model_type == 'livechat' || $model_type == 'live-chat' ? "$message_body" : '' }}">
        <input type="hidden" name="{{ $model_type == 'customer' || $model_type == 'livechat' || $model_type == 'live-chat' ? 'customer_id' : ($model_type == 'purchase-replace' ? 'moduleid' : ($model_type == 'selected_customer' ? 'customers_id' : 'nothing')) }}" value="{{ $model_id }}" id="hidden-customer-id">

        <input type="hidden" name="type" value="" id="hidden-type">


        <input type="hidden" name="customer_token" value="<?php echo ($model_type == 'selected_customer_token') ? $model_id : '' ?>">
        {{-- <input type="hidden" name="moduletype" value="{{ $model_type }}">
        <input type="hidden" name="assigned_to" value="{{ $assigned_user }}" /> --}}
        <input type="hidden" name="status" value="2">
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

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="moveToTmplModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form method="post" action="{{route('attach.cus.create.tpl')}}">
             @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="template" class="col-form-label">Template:</label>
                        <select class="form-control" name="template_no"> 
                            @foreach( $templateArr as $key )
                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                            @endforeach
                        </select>
                    </div>
                      <input type="hidden" name="product_media_id" id="product_ids_move_tmpl">
                      <div class="form-group col-md-6">
                        <label for="message-text" class="col-form-label">Text:</label>
                        <input type="text" class="form-control" name="text" required>
                      </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="template" class="col-form-label">Background:</label>
                        <input type="color" class="form-control" name="background" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="message-text" class="col-form-label">Color:</label>
                        <input type="color" class="form-control" name="color" required>
                      </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary">Submit</button>
            </div>
            </form>
        </div>
      </div>
    </div>
    <!-- End Modal -->

    @include('partials.modals.category')
    @include('partials.modals.forward-products')
    @include('partials.add-order-model')
    <?php $stage = new \App\Stage(); ?>
    <script src="/js/bootstrap-multiselect.min.js"></script>
    <script src="/js/jquery.jscroll.min.js"></script>
    <script>

            $('.customer-search').select2({
                width: "100%"
            });

            $(window).scroll(function() {
                if ( ( $(window).scrollTop() + $(window).outerHeight() ) >= ( $(document).height() - 2500 ) ) {
                    loadMore();
                }
            });



            var isLoading;
            function loadMore() {
                if (isLoading)
                    return;
                    isLoading = true;
                if(!$('.pagination li.active + li a').attr('href')) {
                    return;
                }
                

                var $loader = $('.infinite-scroll-products-loader');
                $.ajax({
                    url: $('.pagination li.active + li a').attr('href'),
                    type: 'GET',
                    beforeSend: function() {
                        $loader.show();
                        $('ul.pagination').remove();
                    }
                })
                .done(function(data) {
                    isLoading = false;

                    // if('' === data.trim())
                    //     return;

                    // $loader.hide();

                    console.log(data);
                    $('.infinite-scroll-data').append(data);

                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    isLoading = false;
                });
            }

            $(document).on("click", ".move-to-tmpl", function (event) {

                var customer_id = $(this).data("id");
                var suggestedproductid = $(this).data("suggestedproductid");
                $("#forward_suggestedproductid").val(suggestedproductid);
                /* alert(suggestedproductid); 
                return false; */
                var cus_cls = ".customer-"+suggestedproductid;
                var total = $(cus_cls).find(".select-pr-list-chk").length;
                image_array = [];
                for (i = 0; i < total; i++) {
                 var customer_cls = ".customer-"+suggestedproductid+" .select-pr-list-chk";
                 var $input = $(customer_cls).eq(i);
                var productCard = $input.parent().parent().find(".attach-photo");
                if (productCard.length > 0) {
                        var image = productCard.data("media");
                        if ($input.is(":checked") === true) {
                            image_array.push(image);
                            image_array = unique(image_array);
                        }
                    }
                }

                if (image_array.length == 0) {
                    alert('Please select some images');
                    return;
                }
                
                $('#product_ids_move_tmpl').val(image_array);
                $("#moveToTmplModel").modal('show');

            });
        // var infinteScroll = function() {
        //     $('.infinite-scroll').jscroll({
        //         autoTrigger: true,
        //         loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
        //         padding: 2500,
        //         nextSelector: '.pagination li.active + li a',
        //         contentSelector: 'div.infinite-scroll',
        //         callback: function () {
        //            $('.lazy').Lazy({
        //                 effect: 'fadeIn'
        //            });
        //            $('ul.pagination:visible:first').remove();
        //             var next_page = $('.pagination li.active + li a');
        //             var page_number = next_page.attr('href').split('page=');
        //             var current_page = page_number[1] - 1;
        //             $('#page-goto option[data-value="' + current_page + '"]').attr('selected', 'selected');
        //             categoryChange();
        //         }
        //     });

        // };

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
            
            // infinteScroll();
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
               
                // if((vcount == "all" || 1 == 1) && $this.hasClass("has-all-selected") === false && (productCardCount < vcount || vcount == "all") ) {
                    // console.log("if");
                    // e.preventDefault();

                    // $('#selected_products').val(JSON.stringify(image_array));
                    // var formData = $('#searchForm').serializeArray();
                    // formData.push({name: "limit", value: vcount}) ;
                    // formData.push({name: "page", value: 1}) ;
                    
                    // if (isQuickProductsFrom) {
                    //     formData.push({name: "quick_product", value: 'true'});
                    // };
                    
                    // var url = "{{ url()->current() }}";


                    // $.ajax({
                    //     url: url,
                    //     data : formData,
                    //     beforeSend: function() {
                    //         $('#productGrid').html('<img id="loading-image" src="/images/pre-loader.gif"/>');
                    //     }
                    // }).done(function (data) {
                    //     $('#productGrid').html(data.html);
                    //     $('#products_count').text(data.products_count);
                    //     $('.lazy').Lazy({
                    //         effect: 'fadeIn'
                    //     });

                    //     infinteScroll();

                    //     if ($this.hasClass("has-all-selected") === false) {
                    //         $this.html("Deselect " + vcount);
                    //         if (vcount == 'all') {
                    //             $(".select-pr-list-chk").prop("checked", true).trigger('change');
                    //         } else {
                    //             var boxes = $(".select-pr-list-chk");
                    //             for (i = 0; i < vcount; i++) {
                    //                 try {
                    //                     $(boxes[i]).prop("checked", true).trigger('change');
                    //                 } catch (err) {
                    //                 }
                    //             }
                    //         }
                    //         $this.addClass("has-all-selected");
                    //     } 
                    // }).fail(function () {
                    //     alert('Error searching for products');
                    // });

                // }else {
                    if ($this.hasClass("has-all-selected") === false) {
                        $this.html("Deselect " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", true).trigger('change');
                        } else {
                            var customers = $(".customer-count").length;
                            for (i = 0; i < customers; i++) {
                                var boxes = ".customer-list-"+i+" .select-pr-list-chk";
                                for (j = 0; j < vcount; j++) {
                                    try {
                                        $(boxes).eq(j).prop("checked", true).trigger('change');
                                    } catch (err) {
                                    }
                                }
                            }
                        }
                        $this.addClass("has-all-selected");
                    }else {
                        $this.html("Select " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", false).trigger('change');
                        } else {
                            // var boxes = $(".select-pr-list-chk");
                            // for (i = 0; i < vcount; i++) {
                            //     try {
                            //         $(boxes[i]).prop("checked", false).trigger('change');
                            //     } catch (err) {
                            //     }
                            // }
                            var customers = $(".customer-count").length;
                            for (i = 0; i < customers; i++) {
                                var boxes = ".customer-list-"+i+" .select-pr-list-chk";
                                for (j = 0; j < vcount; j++) {
                                    try {
                                        $(boxes).eq(j).prop("checked", false).trigger('change');
                                    } catch (err) {
                                    }
                                }
                            }
                        }
                        $this.removeClass("has-all-selected");
                    }
                // }
            })
        });

        function unique(list) {
            var result = [];
            $.each(list, function (i, e) {
                if ($.inArray(e, result) == -1) result.push(e);
            });
            return result;
        }

        // $(document).on('change', '.select-pr-list-chk', function (e) {
        //     var $this = $(this);
        //     var productCard = $this.closest(".product-list-card").find(".attach-photo");
        //     if (productCard.length > 0) {
        //         var image = productCard.data("image");
        //         if ($this.is(":checked") === true) {
        //             //Object.keys(image).forEach(function (index) {
        //             image_array.push(image);
        //             //});
        //             image_array = unique(image_array);

        //         } else {
        //             //Object.keys(image).forEach(function (key) {
        //             var index = image_array.indexOf(image);
        //             image_array.splice(index, 1);
        //             //});
        //             image_array = unique(image_array);
        //         }
        //     }
        // });
        

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

            if ($(this).data('attached') == 0) {
                $(this).data('attached', 1);
                image_array.push(image);
            } else {
                var index = image_array.indexOf(image);

                $(this).data('attached', 0);
                image_array.splice(index, 1);
            }

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            console.log(image_array);
        });


        $(document).on('click', '.preview-attached-img-btn', function (e) {     
            e.preventDefault();
            var customer_id = $(this).data('id');
            var suggestedproductid = $(this).data('suggestedproductid');
            $.ajax({
                url: '/attached-images-grid/get-products/attach/'+suggestedproductid+'/'+customer_id,
                data: $('#searchForm').serialize(),
                dataType: 'html',
            }).done(function (data) {
                $('#attach-image-list-'+suggestedproductid).html(data);
            }).fail(function () {
                alert('Error searching for products');
            });
            
            var expand = $('.expand-'+suggestedproductid);
            $(expand).toggleClass('hidden');

        });

        $(document).on('click', '.attach-photo-all', function (e) {
            e.preventDefault();
            var image = $(this).data('image');

            if ($(this).data('attached') == 0) {
                $(this).data('attached', 1);

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
                // infinteScroll();
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

        $(document).on('click', '.sendImageMessage', function () {
            var customer_id = $(this).data("id");
            var suggestedproductid = $(this).data("suggestedproductid");
            var cus_cls = ".customer-"+suggestedproductid;
            var total = $(cus_cls).find(".select-pr-list-chk").length;
            image_array = [];
            for (i = 0; i < total; i++) {
             var customer_cls = ".customer-"+suggestedproductid+" .select-pr-list-chk";
             var $input = $(customer_cls).eq(i);
            var productCard = $input.parent().parent().find(".attach-photo");
                if (productCard.length > 0) {
                    var image = productCard.data("image");
                    var product = productCard.data("product");
                    if ($input.is(":checked") === true) {
                        image_array.push(product);
                        image_array = unique(image_array);
                    }
                }
            }
            if (image_array.length == 0) {
                alert('Please select some images');
            } else {
                $('#images').val(JSON.stringify(image_array));
                var form = $('#attachImageForm');
                var modelType = form.data("model-type");
                if(modelType == "selected_customer" || modelType == "customer" || modelType == "customers" || modelType == "livechat") {
                    $("#confirmPdf").modal("show");
                    $("#hidden-customer-id").val(customer_id);
                    $("#hidden-type").val('customer-attach');
                    // if(modelType == "customer") {
                    //     $("#hidden-return-url").val('/attached-images-grid/sent-products?customer_id='+customer_id);
                    // }
                    
                }else{
                    $('#attachImageForm').submit();
                }
            }
        });

        $(".btn-approve-pdf").on("click",function() {
            $("#send_pdf").val("1");
            $("#is_queue_setting").val($("#is_queue_option").val());
            $("#pdf_file_name").val($("#pdf-file-name").val());
            $("#hidden-json").val(true);
            $('#attachImageForm').submit();
        });

        $(".btn-ignore-pdf").on("click",function() {
            $("#send_pdf").val("0");
            $("#is_queue_setting").val($("#is_queue_option").val());
            $("#pdf_file_name").val($("#pdf-file-name").val());
            $("#hidden-json").val(true);
            $('#attachImageForm').submit();
        });
        // });

        $("#attachImageForm").on("submit",function(e) {
            e.preventDefault();
            var url = $('#attachImageForm').attr('action');
            var data = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    beforeSend: function (success) {
                        console.log(success);
                                $("#loading-image").show();
                            },
                    success: function(result){
                        $("#loading-image").hide();
                        $("#confirmPdf").modal('hide');
                    toastr['success'](result.message, 'success');
                    $(".select-pr-list-chk").prop("checked", false).trigger('change');
                },
                error: function(error){
                        $("#loading-image").hide();
                }
            });
        });

       

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
        
        $('body').on("click",'.select_row', function (event) {
        $(".select-pr-list-chk").prop("checked", false).trigger('change');
           var $input = $(this);
           var checkBox = $input.parent().parent().parent().parent().find(".select-pr-list-chk");
           checkBox.prop("checked", true).trigger('change');
        });

        $('body').on("click",'.select_multiple_row', function (event) {
        // $(".select-pr-list-chk").prop("checked", false).trigger('change');
           var $input = $(this);
           var checkBox = $input.parent().parent().parent().parent().find(".select-pr-list-chk");
           checkBox.prop("checked", true).trigger('change');
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
    
            $('body').on('click', '.load-chat-images-actions', function (event) {
            if ($(this).parent().hasClass('open')) {
                $(this).parent().removeClass('open');
            } else {
                $('.load-chat-images-actions').parent().removeClass('open');
                $(this).parent().toggleClass('open');
            }
        });

        $(document).on("click", function (event) {
            var container = $(".load-chat-images-dropdown-menu");
            if (container.has(event.target).length === 0) {
                $('.load-chat-images-actions').parent().removeClass('open');
            }
        });

        $(document).on("click", ".add-more-products", function (event) {
            customer_id = $(this).data('id');
            suggested_products_id = $(this).data('suggestedproductid');
            $.ajax({
                url: '/attached-images-grid/add-products/'+suggested_products_id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {  
                              $("#loading-image").show();
                },
                success: function(result){
                     $("#loading-image").hide();
                     console.log(result.url);
                     location.reload();
                    //  window.location.href = result.url;
             }
         });
        });

        $(document).on("click", ".remove-products", function (event) {
            var suggested_products_id = $(this).data("id");
            var cus_cls = ".customer-"+suggested_products_id;
            var total = $(cus_cls).find(".select-pr-list-chk").length;
            product_array = [];
            for (i = 0; i < total; i++) {
             var customer_cls = ".customer-"+suggested_products_id+" .select-pr-list-chk";
             var $input = $(customer_cls).eq(i);
            var productCard = $input.parent().parent().find(".attach-photo");
                if (productCard.length > 0) {
                    var product = productCard.data("product");
                    if ($input.is(":checked") === true) {
                        product_array.push(product);
                    }
                }
            }
            if (product_array.length == 0) {
                alert('Please select some images');
                return;
            }

            console.log(product_array);
            var confirm = window.confirm('Are you sure ?');
            if(!confirm) {
                return;
            }
            $.ajax({
                url: '/attached-images-grid/remove-products/'+suggested_products_id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    products: JSON.stringify(product_array)
                },
                beforeSend: function () {  
                    $("#loading-image").show();
                },
                success: function(result){
                     $("#loading-image").hide();
                     location.reload();
             }
         });
        });

        $(document).on("click", ".delete-message", function (event) {
            var listed_id = $(this).data("listed_id");
            var customer_id = $(this).data("customer");
            var product_id = $(this).data("id");
            $.ajax({
                url: '/attached-images-grid/remove-single-product/'+customer_id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: listed_id
                },
                beforeSend: function () {  
                    $("#loading-image").show();
                },
                success: function(result){
                     $("#loading-image").hide();
                     toastr['success']("Successfull", 'success');
                     var cls = '.single-image-'+listed_id+'-'+customer_id+'-'+product_id;
                     $(cls).hide();
                    //  location.reload();
             }
         });
        });

        $(document).on("click", ".forward-all-products", function (event) {
            image_array = [];
            var products = $(".select-pr-list-chk:checked");
                if(products.length > 0) {
                    $.each(products,function(k,v) {
                        var p = $(v).parent().parent().find(".attach-photo")
                        if(p && p.data("product")) {
                            image_array.push(p.data("product"));
                        }
                    });
                }
            if (image_array.length == 0) {
                alert('Please select some images');
                return;
            }
            
            $('#forward-products-form').find('#product_lists').val(JSON.stringify(image_array));
            $('#forward-products-form').find('#forward_type').val('forward');
            $("#forwardProductsModal").modal('show');
            $('select.select2').select2({
                width: "100%"
            });    
        });

        $(document).on("click", ".forward-products", function (event) {
            var customer_id = $(this).data("id");
            var suggestedproductid = $(this).data("suggestedproductid");
            $("#forward_suggestedproductid").val(suggestedproductid);
            /* alert(suggestedproductid); 
            return false; */
            var cus_cls = ".customer-"+suggestedproductid;
            var total = $(cus_cls).find(".select-pr-list-chk").length;
            image_array = [];
            for (i = 0; i < total; i++) {
             var customer_cls = ".customer-"+suggestedproductid+" .select-pr-list-chk";
             var $input = $(customer_cls).eq(i);
            var productCard = $input.parent().parent().find(".attach-photo");
            if (productCard.length > 0) {
                    var image = productCard.data("product");
                    if ($input.is(":checked") === true) {
                        image_array.push(image);
                        image_array = unique(image_array);
                    }
                }
            }
            if (image_array.length == 0) {
                alert('Please select some images');
                return;
            }
            
            $('#forward-products-form').find('#product_lists').val(JSON.stringify(image_array));
            $('#forward-products-form').find('#forward_type').val('forward');
            $("#forwardProductsModal").modal('show');
            $('select.select2').select2({
                width: "100%"
            });
        });


        $(document).on("submit", "#forward-products-form", function (e) {
            e.preventDefault();
            $.ajax({
                url: '/attached-images-grid/forward-products',
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function () {  
                    $("#loading-image").show();
                },
                success: function(result){
                     $("#loading-image").hide();
                    toastr['success'](result.message, 'success');
                    $("#forwardProductsModal").modal('hide');
                     //location.reload();
             }
            });
        });

        $(document).on("click", ".expand-row-btn", function (e) {
            var id = $(this).data('id');
            console.log(id);
            console.log($('.toggle-div-'+id).length);
            $('.toggle-div-'+id).toggleClass('hidden');
        });

    
        $(document).on("click",".select-customer-all-products", function (e) {
                    var customer_id = $(this).data('id');
                    var suggestedproductid = $(this).data('suggestedproductid');
                    var $this = $(this);
                    var custCls = '.customer-'+suggestedproductid;
                    if ($this.hasClass("has-all-selected") === false) {
                        // $this.html("Deselect all");
                        $(this).find('img').attr("src", "/images/completed-green.png");
                        $(custCls).find(".select-pr-list-chk").prop("checked", true).trigger('change');
                        $this.addClass("has-all-selected");
                    }else {
                        // $this.html("Select all");
                        $(this).find('img').attr("src", "/images/completed.png");
                        $(custCls).find(".select-pr-list-chk").prop("checked", false).trigger('change');
                        $this.removeClass("has-all-selected");
                    }
    })

    $('#customer-search').select2({
            tags: true,
            width : '100%',
            ajax: {
                url: '/erp-leads/customer-search',
                dataType: 'json',
                delay: 750,
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data, params) {
                    for (var i in data) {
                        if(data[i].name) {
                            var combo = data[i].name+'/'+data[i].id;
                        }
                        else {
                            var combo = data[i].text;
                        }
                        data[i].id = combo;
                    }
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Customer by id, Name, No',
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: function (customer) {
                if (customer.loading) {
                    return customer.name;
                }
                if (customer.name) {
                    return "<p> " + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                }
            },
            templateSelection: (customer) => customer.text || customer.name,
        });


        $(document).on('click', '.expand-row-msg', function () {
            var name = $(this).data('name');
			var id = $(this).data('id');
            var full = '.expand-row-msg .show-short-'+name+'-'+id;
            var mini ='.expand-row-msg .show-full-'+name+'-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
        });

        $(document).on("click",".btn-event-order",function(e) {
            e.preventDefault();
            var form  = $(this).closest("form");
            $.ajax({
                type: "POST",
                url: "/erp-customer/move-order",
                data : form.serialize(),
                dataType : "json",  
                beforeSend : function() {
                    $(this).text('Loading...');
                    },
            }).done(function (response) {
                if(response.code == 1) {
                    window.location = "/order/create?key="+response.key;
                }
            }).fail(function (response) {
                console.log(response);
            });
        });
        
</script>

@endsection
