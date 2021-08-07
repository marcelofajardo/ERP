@extends('layouts.app')

@section('favicon' , 'cropapprovalgrid.png')
@section('title', 'Crop Rejected Grid - ERP Sololuxury')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/rcrop.min.css')}}">
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
            <form  method="POST" action="{{route('google.search.crop.post')}}" id="cropImageSend">
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
        <div class="col-md-12">
            <h2 class="page-heading">
                Rejected Cropped Images ({{ $products->total() }})
            </h2>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $products->links() !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="{{action('ProductCropperController@showRejectedCrops')}}">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <input value="{{$reason}}" type="text" name="reason" id="reason" placeholder="Reason..." class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <select name="user_id" id="user_id" class="form-control select2" placeholder="Select user...">
                                    <option value="">Select user...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{request()->get('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                {!! $category_array !!}
                            </div>
                            <div class="form-group col-md-2">
                                <select class="form-control select2" name="supplier[]" multiple placeholder="Suppliers">
                                    @foreach ($suppliers as $key => $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, request()->get('supplier', [])) ? 'selected' : '' }}>{{ $item->supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-3">
                                @php $brands = \App\Brand::getAll(); @endphp
                                <select class="form-control select2" name="brand[]" multiple placeholder="Brands...">
                                    @foreach ($brands as $key => $name)
                                        <option value="{{ $key }}" {{ in_array($key, request()->get('brand', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mr-3">
                                @php $colors = new \App\Colors(); @endphp
                                <select class="form-control select2" name="color[]" multiple placeholder="Colors...">
                                    <@foreach ($colors->all() as $key => $col)
                                        <option value="{{ $key }}" {{ in_array($key, request()->get('color', [])) ? 'selected' : '' }}>{{ $col }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (Auth::user()->hasRole('Admin'))
                                @php $locations = \App\ProductLocation::pluck("name","name"); @endphp
                                <div class="form-group mr-3">
                                    <select class="form-control select2" name="location[]" multiple placeholder="Location...">
                                        @foreach ($locations as $name)
                                            <option value="{{ $name }}" {{ in_array($name, request()->get('location', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group mr-3">
                                <input name="size" type="text" class="form-control"
                                       value="{{ request()->get('size') }}"
                                       placeholder="Size">
                            </div>
                            <div class="form-group col-md-1">
                                <button class="btn btn-image"><img src="{{asset('images/search.png')}}" alt="Search"></button>
                                <a href="{{url()->current()}}" class="btn btn-image" style="position: absolute;"><img src="/images/clear-filters.png"/></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered mt-5">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Remark</th>
                            <th>Rejected By</th>
                        </tr>
                        @foreach($products as $product)
                            <tr class="rec_{{$product->id}}">
                                <td>
                                    <img id="img-{{$product->id}}" style="width: 120px;" src="{{ $product->imageurl }}" alt="Image" data-media="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->id : ''}}">
                                    <button type="button" class="btn btn-image my-3" onclick="sendImage({{$product->id}})"><img src="/images/filled-sent.png"/></button>
                                </td>
                                <td>
                                    {{ $product->name }}
                                </td>
                                <td>
                                    {{ $product->sku }}
                                </td>
                                <td>
                                    {{ $product->supplier ?? '-' }}
                                </td>
                                <td>
                                    {{ $product->product_category ? $product->product_category->title : '-'}}
                                </td>
                                <td>{{ $product->crop_remark ?? '-' }}</td>
                                <td>
                                    {{ $product->cropRejector ? $product->cropRejector->name : 'N/A' }}
                                </td>
                            </tr>
                            <tr class="rec_{{$product->id}}">
                                <td colspan="2">

                                </td>
                                <td colspan="2">
                                    <strong>Remark</strong><br>
                                    {{ $product->crop_remark ?? '-' }}
                                </td>
                                <td colspan="3">
                                    <strong>Actions</strong><br>
                                    {{--                                    <a target="_new" href="{{ action('ProductCropperController@showImageToBeVerified', $product->id) }}" class="btn btn-sm btn-secondary">Show Grid</a>--}}
                                    <a target="_new" href="{{ action('ProductCropperController@showRejectedImageToBeverified', $product->id) }}" class="btn btn-sm btn-secondary">Check Cropping</a>
                                    <a target="_new" href="{{ action('ProductController@show', $product->id) }}" class="btn btn-default btn-sm">Show Product</a>
                                    <a data-id="{{$product->id}}" class="btn btn-danger btn-sm text-light delete-product btn-sm">Delete</a>&nbsp;
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{asset('js/rcrop.min.js')}}"></script>
    <script>

        $(".select2").each(function(){
            $(this).select2({
                placeholder : $(this).attr('placeholder'),
            });
        });

        $(document).on('click', '.delete-product', function() {
            let pid = $(this).attr('data-id');

            $.ajax({
                url: '{{ action('ProductController@deleteProduct') }}',
                data: {
                    product_id: pid
                },
                success: function(response) {
                    $('.rec_'+pid).hide();
                }
            });
        });

        function sendImage(product_id) {
            var url = $("#img-"+product_id).attr('src');
            var media_id = $("#img-"+product_id).attr('data-media');
            $("#image_crop").attr("src", url);
            $('#product-id').val(product_id);
            $('#media_id').val(media_id);
            $('#image_crop').rcrop({full : true});
            $('#crop-image').show();
        }

        function hideCrop(){
            $('#crop-image').hide();
        }

        function sendImageMessageCrop(){
            var crop = $('#crop-type').val();
            if(crop == 0){
                document.getElementById("cropImageSend").submit();
            } else {
                var id = $('#product-id').val();
                var sequence = crop;
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