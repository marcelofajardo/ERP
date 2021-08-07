@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rcrop.min.css') }}">
    <style type="text/css">
        .dis-none {
            display: none;
        }

        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
        .clayfy-box{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        #crop-image{
            display: none;
            position: fixed;
            top: 25%;
            left: 38%;
            right: 35%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
        .cropper{
            padding: 30px;
            border: 1px solid;
            margin: 10px;
            background: #f1f1f1;
        }


    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div id="crop-image">
        <div class="cropper">
            <form id="cropImageSend">
                <img id="image_crop" width="100%">
                {{ csrf_field() }}
                <div class="col text-center">
                    <select name="type" id="crop-type" class="form-control">
                        <option value="0">Select Crop Type</option>
                        <option value="8">8</option>
                    </select>
                    <input type="hidden" name="product_id" id="product-id">
                    <input type="hidden" name="media_id" id="media_id">
                    <button type="button" class="btn btn-default" onclick="sendImageMessageCrop()">Crop Image</button>
                    <button type="button" class="btn btn-default" onclick="hideCrop()">Close</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="">
                <h2 class="page-heading">Google Search by Image ({{ $count_system }})</h2>

                <!--Product Search Input -->
                <form method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3">
                        <select data-placeholder="Select status" class="form-control select-multiple2" name="status_id[]" multiple>
                            @foreach (\App\Helpers\StatusHelper::getStatus() as $id=>$name)
                                <option value="{{ $id }}" {{ isset($status_id) && in_array($id, $status_id)  ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3 mb-3">
                        {!! $category_selection !!}
                    </div>


                    <div class="form-group mr-3">
                        @php $brands = \App\Brand::getAll();
                        @endphp
                        <select data-placeholder="Select brands" class="form-control select-multiple2" name="brand[]" multiple>
                            <optgroup label="Brands">
                                @foreach ($brands as $id => $name)
                                    <option value="{{ $id }}" {{ isset($brand) && $brand == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group mr-3 mb-3">
                        <input placeholder="Shoe Size" type="text" name="shoe_size" value="{{request()->get('shoe_size')}}" class="form-control-sm form-control">
                    </div>
                    <div class="form-group mr-3">
                        @php $colors = new \App\Colors();
                        @endphp
                        <select data-placeholder="Select color" class="form-control select-multiple2" name="color[]" multiple>
                            <optgroup label="Colors">
                                @foreach ($colors->all() as $id => $name)
                                    <option value="{{ $id }}" {{ isset($color) && $color == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    @if (Auth::user()->hasRole('Admin'))
                        @if(!empty($locations))
                            <div class="form-group mr-3">
                                <select data-placeholder="Select location" class="form-control select-multiple2" name="location[]" multiple>
                                    <optgroup label="Locations">
                                        @foreach ($locations as $name)
                                            <option value="{{ $name }}" {{ isset($location) && $location == $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        @endif
                        <div class="form-group mr-3">
                            <input type="checkbox" name="no_locations" id="no_locations" {{ isset($no_locations) ? 'checked' : '' }}>
                            <label for="no_locations">With no Locations</label>
                        </div>
                    @endif
                    <div class="form-group mr-3">
                        <input type="checkbox" name="quick_product" id="quick_product" {{ $quick_product == 'true' ? 'checked' : '' }}  value="true">
                        <label for="quick_product">Quick Sell</label>
                    </div>
                    <div class="form-group mr-3">
                        <select class="form-control select-multiple2" name="quick_sell_groups[]" multiple data-placeholder="Quick Sell Groups...">
                            @foreach ($quick_sell_groups as $key => $quick_sell)
                                <option value="{{ $quick_sell->id }}" {{ in_array($quick_sell->id, request()->get('quick_sell_groups', [])) ? 'selected' : '' }}>{{ $quick_sell->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3 mb-3">
                        <strong class="mr-3">Price</strong>
                        <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="10000000" data-slider-step="10" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '10000000' }}]"/>
                    </div>
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                </form>


            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <button type="button" class="btn btn-secondary select-all-system-btn" data-count="0">Send All In System</button>
    <button type="button" class="btn btn-secondary select-all-page-btn" data-count="0">Send All On Page</button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#product-image">Get Product By Image</button>

    <div class="productGrid" id="productGrid">
        {!! $products->appends(Request::except('page'))->links() !!}
        <form method="POST" action="{{route('google.search.crop')}}" id="theForm">
            {{ csrf_field() }}
            <div class="row">


                <div class="col-md-12 margin-tb">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="table-layout:fixed;">
                            <thead>
                            <th style="width:8%">Id</th>
                            <th style="width:7% ; height: 10%">Image</th>
                            <th style="width:10%">Sku</th>
                            <th style="width:20%">Price</th>
                            <th style="width:20%">Status</th>
                            <th style="width:20%">Action</th>
                            </thead>
                            <tbody class="infinite-scroll-data">

                            @foreach ($products as $product)

                                <tr>


                                    <td>{{ $product->id }}</td>
                                    <td>
                                        <img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive grid-image" alt="" id="img{{ $product->id }}" data-media="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->id : ''}}"/>
                                    </td>
                                    <td>
                                        {{ $product->sku }}
                                    </td>
                                    <td>
                                        {{ $product->price }}
                                    </td>
                                    <td>
                                        {{ ucwords(\App\Helpers\StatusHelper::getStatus()[$product->status_id]) }}
                                    </td>
                                    <td>
                                        <input type="checkbox" class="select-product-edit" name="product_id" value="{{ $product->id }}" style="margin: 10px !important;">
                                        @if($product->status_id == 26)<a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-secondary">Verify</a>@endif
                                        <button type="button" class="btn btn-image my-3" id="sendImageMessage" onclick="sendImage()"><img src="/images/filled-sent.png"/></button>

                                    </td>

                                </tr>


                            @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>



                {{--                @foreach ($products as $product)--}}

                {{--                    --}}
                {{--                    <div class="col-md-3 col-xs-6 text-left" style="border: 1px solid #cccccc;">--}}
                {{--                        <img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive grid-image" alt="" id="img{{ $product->id }}" data-media="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->id : ''}}"/>--}}
                {{--                        <p>Status : {{ ucwords(\App\Helpers\StatusHelper::getStatus()[$product->status_id]) }}</p>--}}
                {{--                        <p>Brand : {{ isset($product->brands) ? $product->brands->name : "" }}</p>--}}
                {{--                        <p>Transit Status : {{ $product->purchase_status }}</p>--}}
                {{--                        <p>Location : {{ ($product->location) ? $product->location : "" }}</p>--}}
                {{--                        <p>Sku : {{ $product->sku }}</p>--}}
                {{--                        <p>Id : {{ $product->id }}</p>--}}
                {{--                        <p>Size : {{ $product->size}}</p>--}}
                {{--                        <p>Price ({{ $product->currency }}) : {{ $product->price }}</p>--}}
                {{--                        <p>Price (INR) : {{ $product->price_inr }}</p>--}}
                {{--                        <p>Price Special (INR) : {{ $product->price_special }}</p>--}}
                {{--                        <input type="checkbox" class="select-product-edit" name="product_id" value="{{ $product->id }}" style="margin: 10px !important;">--}}
                {{--                        @if($product->status_id == 26)<a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-secondary">Verify</a>@endif--}}
                {{--                    </div>--}}
                {{--                    --}}
                {{--                    --}}
                {{--                    --}}
                {{--                    --}}
                {{--                @endforeach--}}



            </div>
        </form>



        {!! $products->appends(Request::except('page'))->links() !!}
    </div>

    @include('google_search_image.partials.get-products-by-image')

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{ asset('js/rcrop.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var ids = <?php echo json_encode($all_products_system); ?>;

            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();


            $('.select-all-page-btn').on('click', function () {
                var result = confirm("Do you want to send " + $("input[name='product_id']").length + " images to Google?");
                var ids = [];
                $.each($("input[name='product_id']"), function () {
                    ids.push($(this).val());
                });
                if (result) {
                    for (i = 0; i < ids.length && i < 1000; i++) {
                        $.ajax({
                            url: "{{ route('google.product.queue') }}",
                            type: 'POST',
                            beforeSend: function () {
                                $("#loading-image").show();
                            },
                            success: function (response) {
                                //   $("#loading-image").hide();
                            },
                            data: {
                                id: ids[i],
                                _token: "{{ csrf_token() }}",
                            }
                        });
                    }
                    setTimeout(function () {
                        $("#loading-image").hide();
                    }, 60000);
                }
            });

            $('.select-all-system-btn').on('click', function () {
                var result = confirm("Do you want to send " + ids.length + " images to Google?");
                if (result) {
                    for (i = 0; i < ids.length && i < 1000; i++) {
                        $.ajax({
                            url: "{{ route('google.product.queue') }}",
                            type: 'POST',
                            beforeSend: function () {
                                $("#loading-image").show();
                            },
                            success: function (response) {
                                // $("#loading-image").hide();
                            },
                            data: {
                                id: ids[i],
                                _token: "{{ csrf_token() }}",
                            }
                        });
                    }
                    setTimeout(function () {
                        $("#loading-image").hide();
                    }, 200000);
                }
            });

            function sendAllSystem() {
                //
            }
        });

        function sendImage() {

            var clicked = [];
            $.each($("input[name='product_id']:checked"), function () {
                clicked.push($(this).val());
            });

            if (clicked.length == 0) {
                alert('Please Select Product');
            } else if (clicked.length == 1) {
                url = $("#img"+clicked).attr('src');
                media_id = $("#img"+clicked).attr('data-media');
                $("#image_crop").attr("src", url);
                $('#product-id').val(clicked);
                $('#media_id').val(media_id);
                $('#image_crop').rcrop({full : true});
                $('#crop-image').show();
            } else {
                $.each($("input[name='product_id']:checked"), function () {
                    id = $(this).val();
                    $.ajax({
                        url: "{{ route('google.product.queue') }}",
                        type: 'POST',
                        beforeSend: function () {
                            $("#loading-image").show();
                        },
                        success: function (response) {
                            $("#loading-image").hide();
                        },
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        }
                    });
                });
                location.reload();
            }
        }

        function getProductsFromImage() {

            file = $('#imgupload').prop('files')[0];
            var fd = new FormData();
            fd.append("file", file);
            fd.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('google.product.image') }}",
                type: 'POST',
                dataType: 'json',
                data: fd,
                beforeSend: function () {
                    $('#product-image').modal('hide');
                    $("#loading-image").show();
                },
                success: function (data) {
                    $("#loading-image").hide();
                    alert('Product Queued For Scraping');
                },
                error: function (response) {
                    $("#loading-image").hide();
                    alert('Product Not Found');
                },
                cache: false,
                contentType: false,
                processData: false
            });

        }

        function hideCrop(){
            $('#crop-image').hide();
        }

        function sendImageMessageCrop(){
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            crop = $('#crop-type').val();
            if(crop == 0){
                var formData = $('#cropImageSend').serialize();

                $.ajax({
                    url: "{{route('google.search.crop.post')}}",
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        hideCrop();
                        $("#loading-image").show();
                        // toastr.error("please wait");

                    },
                    success: function (response) {
                        $("#loading-image").hide();
                        if (response.status == true) {
                            toastr.success(response.message);
                            hideCrop();
                        }else{
                            toastr.error(response.message);
                        }

                    },
                });

                // document.getElementById("cropImageSend").submit();
            }
            else{
                id = $('#product-id').val();
                sequence = crop;
                $.ajax({
                    url: "{{ route('google.crop.sequence') }}",
                    type: 'POST',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    success: function (response) {
                        $("#loading-image").hide();
                        history.back();
                    },
                    data: {
                        id: id,
                        sequence : sequence,
                        _token: "{{ csrf_token() }}",
                    }
                });
            }
        }
    </script>
@endsection
