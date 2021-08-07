@extends('layouts.app')


@if($cropped == 'on')
    @section('favicon' , 'approvedproductlisting.png')
@section('title', 'Approved Listing - ERP Sololuxury')
@endif
@section('favicon' , 'attributeedit.png')
@section('title', 'Approved Product Listing - ERP Sololuxury')

@section('title', 'Product Listing')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    <style>
        .quick-edit-color {
            transition: 1s ease-in-out;
        }
        /*thead th {*/
        /*    font-size: 0.6em;*/
        /*    padding: 1px !important;*/
        /*    height: 15px;*/
        /*}*/
        .thumbnail-pic {
            position: relative;
            display: inline-block;
        }
        .thumbnail-pic:hover .thumbnail-edit {
            display: block;
        }
        .thumbnail-edit {
            padding-top: 12px;
            padding-right: 7px;
            position: absolute;
            left: 0;
            top: 0;
            display: none;
        }
        .thumbnail-edit a {
            color: #FF0000;
        }
        .thumbnail-pic {
            position: relative;
            padding-top: 10px;
            display: inline-block;
        }
        .notify-badge {
            position: absolute;
            top: 10px;
            text-align: center;
            border-radius: 30px 30px 30px 30px;
            color: white;
            padding: 5px 10px;
            font-size: 10px;
        }
        .notify-red-badge {
            background: red;
        }
        .notify-green-badge {
            background: green;
        }
        .cropme-container {
            margin-left: 35px !important;
            top: 0px !important;
            width: 300px !important;
            height: 300px !important;
            display: inline-block  !important;
            vertical-align: middle !important;
        }

        .cropme-slider {
            margin-top : 0px !important;
            transform: translate3d(550px, 155px, 0px) rotate(-90deg) !important;
            transform-origin:unset !important;
        }
        .product_filter .row > div:not(:first-child):not(:last-child) {
            padding-left: 10px;
            padding-right: 10px;
        }
        .product_filter .row > div:first-child {
            padding-right: 10px;
        }
        .product_filter .row > div:last-child {
            padding-left: 10px;
        }
        /* Select2 changes */
        .select2-container .select2-selection--single {
            height: 34px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px;
            right: 5px;
        }
        .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
            border: 1px solid #ccc;
        }
        .select2-container .select2-selection--multiple {
            min-height: 34px;
        }
        .select2-selection select2-selection--multiple {
            padding: 0 5px;
        }
        .select2-container .select2-search--inline .select2-search__field {
            padding: 0 5px;
        }
        td.action > div, td.action > button {
            margin-top: 8px;
        }
        .lmeasurement-container, .dmeasurement-container, .hmeasurement-container {
            display: block;
            margin-bottom: 10px;
        }
        .quick-name {
            display: block;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 90px;
            height: 1.2em;
            white-space: nowrap;
        }
        .quick-description {
            display: block;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 100%;
            max-width: 140px;
            height: 1.2em;
            white-space: nowrap;
        }
        td {
            padding:3px !important;
        }

        .quick-edit-category ,.quick-edit-composition-select, .quick-edit-color,.post-remark, .approved_by {
            height: 26px;
            padding: 2px 12px;
            font-size: 12px; 
        }
        .lmeasurement-container input {
           height: 26px;
            padding: 2px 12px;
            font-size: 12px;  
        }

        .infinite-scroll-data .badge {
            display: inline-block;
            min-width: 5px;
            padding: 0px 4px;
        }
        .quick-edit-category ,.quick-edit-composition-select, .quick-edit-color,.post-remark, .approved_by {
            height: 26px;
            padding: 2px 12px;
            font-size: 12px; 
        }
        .lmeasurement-container input {
           height: 26px;
            padding: 2px 12px;
            font-size: 12px;  
        }
        .infinite-scroll-data .badge {
            display: inline-block;
            min-width: 5px;
            padding: 0px 4px;
        }
    </style>
@endsection

@section('large_content')
<div style="position:fixed;z-index:1"><button class="btn btn-secondary hide start-again" onclick="callinterval();" disabled>Start Scroll</button>
<button class="btn btn-secondary stopfunc hide pause" id="clearInt">Stop Scroll</button></div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Approved Product Listing ({{ $products_count }}) 
                <a href="{{ route('sop.index') }}?type=ListingApproved" class="pull-right">SOP</a>
            </h2>
            <form class="product_filter" action="{{ action('ProductController@approvedListing') }}/{{ $pageType }}" method="GET">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <input name="term" type="text" class="form-control" value="{{ isset($term) ? $term : '' }}" placeholder="sku,brand,category,status,stage">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="category[]" data-placeholder="Category..">
                                <option></option>
                                @foreach ($category_array as $data)
                                    <option value="{{ $data['id'] }}" {{ in_array($data['id'], $selected_categories) ? 'selected' : '' }}>{{ $data['title'] }}</option>
                                    @if ($data['title'] == 'Men')
                                        @php
                                            $color = "#D6EAF8";
                                        @endphp
                                    @elseif ($data['title'] == 'Women')
                                        @php
                                            $color = "#FADBD8";
                                        @endphp
                                    @else
                                        @php
                                            $color = "";
                                        @endphp
                                    @endif
                                    @foreach ($data['child'] as $children)
                                        <option style="background-color: {{ $color }};"
                                                value="{{ $children['id'] }}" {{ in_array($children['id'], $selected_categories) ? 'selected' : '' }}>
                                            &nbsp;&nbsp;{{ $children['title'] }}</option>
                                        @foreach ($children['child'] as $child)
                                            <option style="background-color: {{ $color }};"
                                                    value="{{ $child['id'] }}" {{ in_array($child['id'], $selected_categories) ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $child['title'] }}</option>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="brand[]" multiple
                                    data-placeholder="Brand..">
                                @foreach ($brands as $key => $name)
                                    <option value="{{ $key }}" {{ !empty(request()->get('brand')) && in_array($key, request()->get('brand', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="color[]" multiple
                                    data-placeholder="Color..">
                                @foreach ($colors as $key => $col)
                                    <option value="{{ $key }}" {{ !empty(request()->get('color')) && in_array($key, request()->get('color', [])) ? 'selected' : '' }}>{{ $col }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="supplier[]" multiple
                                    data-placeholder="Supplier..">
                                @foreach ($suppliers as $key => $item)
                                    <option value="{{ $item->id }}" {{ !empty(request()->get('supplier')) && in_array($item->id, request()->get('supplier', [])) ? 'selected' : '' }}>{{ $item->supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <select class="form-control  select-multiple" name="type" data-placeholder="Select type">
                                <option></option>
                                <option value="Not Listed" {{ isset($type) && $type == "Not Listed" ? 'selected' : ''  }}>
                                    Not Listed
                                </option>
                                <option value="Listed" {{ isset($type) && $type == "Listed" ? 'selected' : ''  }}>
                                    Listed
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="user_id" id="user_id"
                                    data-placeholder="Select user">
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <select class="form-control select-multiple" multiple="" name="status_id[]" id="status_id"
                                    data-placeholder="Select Status">
                                <option></option>
                                @foreach(\App\Helpers\StatusHelper::getStatus() as $i => $v)
                                    <option @if(in_array($i,request('status_id',[]))) selected="selected" @endif value="{{$i}}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <select class="form-control select-multiple" name="crop_status"
                                    data-placeholder="Select cropped images">
                                <option></option>
                                <option value="Matched" {{app('request')->crop_status == "Matched" ? 'selected' : ''}}>Matched</option>
                                <option value="Not Matched" {{app('request')->crop_status == "Not Matched" ? 'selected' : ''}}>Not Matched</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            @if(auth()->user()->isReviwerLikeAdmin('final_listing'))
                                <?php echo Form::checkbox("submit_for_approval", "on", (bool)(request('submit_for_approval') == "on"), ["class" => ""]); ?>
                                <lable for="submit_for_approval pr-3">Submit For approval ?</lable>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                           <?php echo Form::checkbox("without_title", "on", (bool)(request('without_title') == "on"), ["class" => ""]); ?>
                                <lable for="without_title pr-3">No title</lable>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                           <?php echo Form::checkbox("without_size", "on", (bool)(request('without_size') == "on"), ["class" => ""]); ?>
                                <lable for="without_size pr-3">No size</lable>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                           <?php echo Form::checkbox("without_composition", "on", (bool)(request('without_composition') == "on"), ["class" => ""]); ?>
                                <lable for="without_composition pr-3">No Composition</lable>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" title="Filter">
                                <i type="submit" class="fa fa-filter" aria-hidden="true"></i>
                            </button>
                            <a href="{{url()->current()}}" class="btn  btn-secondary" title="Clear">
                                <i type="submit" class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <input type="button" onclick="pushProduct()" class="btn btn-secondary" value="Push product"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">  
                        <div class="form-group">
                            <input type="text" class="form-control" id="scrolltime" placeholder="scroll interval in second"/>
                        </div>
                    </div>
                    <div class="col-sm-1">  
                        <div class="form-group">
                        <input type="button" onclick="callinterval()" class="btn btn-secondary" value="Start"/>
                        </div>
                    </div>
                </div>
            </form>
            <input type="button" value="Auto push product - {{$auto_push_product == 0 ? 'Not Active' : 'Active'}}" class="btn btn-{{$auto_push_product == 0 ? 'secondary' : 'primary'}} active autopushproduct" auto_push_value="{{$auto_push_product}}">

        </div>
    </div>

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-md-12">
            <div class="infinite-scroll table-responsive mt-5 infinite-scroll-data">
                @if($pageType == "images")
                    @include("products.final_listing_image_ajax")
                @else
                    @include("products.final_listing_ajax")
                @endif
            </div>
            <img class="infinite-scroll-products-loader center-block" src="/images/loading.gif" alt="Loading..." style="display: none" />
        </div>
    </div>
    
    @include('partials.modals.remarks')
    @include('partials.modals.image-expand')
    @include('partials.modals.set-description-site-wise')

    <div class="common-modal modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Edit Value</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               </div>
               <div class="modal-body edited-field-value">
                    
                </div>
            </div>
        </div>  
    </div>

    <div class="common-modal-crop modal " role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Edit Value</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               </div>
               <div class="modal-body edited-field-value">
                    
                </div>
            </div>
        </div>  
    </div>

@endsection

@section('scripts')
    <script>
        function pushProduct() {
            $.ajax({
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{csrf_token()}}"
                },
                cache: false,
                contentType: false,
                processData: false,
                url: "{{ url('products/listing/final/pushproduct') }}",
                success: function (html) {
                    swal(html.message);
                }
            })
            return false;
        }
    </script>
    <style>
        .same-color {
            color: #898989;
            font-size: 14px;
        }
        .sololuxury-button {
            display: inline-block;
            color: #898989;
            font-size: 14px;
            border: 1px solid #898989;
            background: #FFF;
            padding: 5px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
        var categoryJson = <?php echo json_encode($category_array); ?>;
        $(document).on('change', '.category_level_1', function () {
            var this_ = $(this);
            var category_id = $(this).val();
            categoryJson.forEach(function (category, index) {
                if (category.id == category_id) {
                    var html = "";
                    category.child.forEach(function (child, i) {
                        html += '<option value="' + child.id + '">' + child.title + '</option>';
                    });
                    this_.closest('tr').find('.category_level_2').html(html);
                }
            })
        });
        $(document).on('change', '.category_level_2', function () {
            var this_ = $(this);
            var category_id = $(this).val();
            categoryJson.forEach(function (category, index) {
                category.child.forEach(function (children, i) {
                    if (children.id == category_id) {
                        var html = "";
                        children.child.forEach(function (child, i) {
                            html += '<option value="' + child.id + '">' + child.title + '</option>';
                        });
                        this_.closest('tr').find('.quick-edit-category').html(html);
                    }
                });
            })
        });
        var productIds = [
            @foreach ( $products as $product )
            {{ $product->id }},
            @endforeach
        ];
        function removeIdFromArray(id) {
            for (var i = 0; i < productIds.length; i++) {
                if (productIds[i] === id) {
                    productIds.splice(i, 1);
                    $('#product' + id).hide();
                }
            }
            console.log(productIds);
        }
        $(document).on('keyup', '.send-message', function (event) {
            let userId = $(this).data('id');
            let message = $(this).val();
            let sku = $(this).data('sku');
            let self = this;
            if (event.which != 13) {
                return;
            }
            $.ajax({
                url: '{{ action('WhatsAppController@sendMessage', 'vendor') }}',
                type: 'POST',
                data: {
                    vendor_id: userId,
                    message: 'SKU - ' + sku + '-' + message,
                    is_vendor_user: 'yes',
                    status: 1
                },
                success: function () {
                    $(self).val('');
                    toastr['success']('Message sent successfully', 'Success')
                }
            });
        });
       
        $(document).on('click','.autopushproduct',function(){
            $.ajax({
                url: "{{ url('products') }}/changeautopushvalue",
                type: 'POST',
                data: {
                    auto_push_value: $(this).attr("auto_push_value"),
                    _token: "{{csrf_token()}}",
                },
                success: function (data) {
                    $(self).val('');
                    toastr['success']('value changed successfully', 'Success')
                    console.log(data.data)
                    $(".autopushproduct").attr("auto_push_value",data.data);
                    if(data.data == 0){
                        $(".autopushproduct").removeClass("btn-primary").addClass("btn-secondary");
                        $(".autopushproduct").val("Auto push product - Not Active")
                        $(".fa-upload").removeClass("hide");
                    }else{
                        $(".autopushproduct").removeClass("btn-secondary").addClass("btn-primary");
                        $(".autopushproduct").val("Auto push product - Active")
                        $(".fa-upload").addClass("hide");
                    }
                }
            });
        });
        $(document).on('click', '.edit-product-show', function () {
            let id = $(this).data('id');
            $('#product_' + id).toggleClass('hidden');
        });
        $(document).on('click', '.reject-sequence', function (event) {
            let pid = $(this).data('id');
            $.ajax({
                url: '/reject-sequence/' + pid,
                data: {
                    senior: 1
                },
                success: function () {
                    toastr['success']('Sequence rejected successfully!', 'Success');
                    removeIdFromArray(pid);
                },
                error: function () {
                    toastr['error']('Error rejecting sequence', 'Success');
                }
            });
        });
        $(document).on('click', '.crop-approval-confirmation', function (event) {
            let pid = $(this).data('id');
            $.ajax({
                url: '/products/auto-cropped/' + pid + '/crop-approval-confirmation',
                data: {
                    _token: "{{csrf_token()}}",
                },
                type: 'GET',
                success: function () {
                    toastr['success']('Crop approval successfully confirmed!', 'Success');
                    $('#approve_cropping_' + pid).hide();
                },
                error: function () {
                    $(self).removeAttr('disabled');
                },
                beforeSend: function () {
                    $(self).attr('disabled');
                }
            });
        });
        $(document).on('change', '.reject-cropping', function (event) {
            let pid = $(this).data('id');
            let remark = $(this).val();
            if (remark == 0 || remark == '0') {
                return;
            }
            let self = this;
            $.ajax({
                url: '/products/auto-cropped/' + pid + '/reject',
                data: {
                    remark: remark,
                    _token: "{{csrf_token()}}",
                    senior: 1
                },
                type: 'GET',
                success: function () {
                    toastr['success']('Crop rejected successfully!', 'Success');
                    removeIdFromArray(pid);
                    $(self).removeAttr('disabled');
                },
                error: function () {
                    $(self).removeAttr('disabled');
                },
                beforeSend: function () {
                    $(self).attr('disabled');
                }
            });
        });
        {{--$(document).on('change', '.reject-listing', function (event) {--}}
        {{--    let pid = $(this).data('id');--}}
        {{--    let remark = $(this).val();--}}
        {{--    if (remark == 0 || remark == '0') {--}}
        {{--        return;--}}
        {{--    }--}}
        {{--    let self = this;--}}
        {{--    $.ajax({--}}
        {{--        url: '{{action('ProductController@addListingRemarkToProduct')}}',--}}
        {{--        data: {--}}
        {{--            product_id: pid,--}}
        {{--            remark: remark,--}}
        {{--            rejected: 1,--}}
        {{--            senior: 1--}}
        {{--        },--}}
        {{--        success: function (response) {--}}
        {{--            toastr['success']('Product rejected successfully!', 'Rejected');--}}
        {{--            $(self).removeAttr('disabled');--}}
        {{--            $(self).val();--}}
        {{--            removeIdFromArray(pid);--}}
        {{--        },--}}
        {{--        beforeSend: function () {--}}
        {{--            $(self).attr('disabled');--}}
        {{--        }, error: function () {--}}
        {{--            $(self).removeAttr('disabled');--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
        var page = 1;
        var isLoadingProducts;
        $(document).ready(function () {
            // $('ul.pagination').hide();
            // $(function () {
                // $('.infinite-scroll').jscroll({
                //     autoTrigger: true,
                //     loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                //     padding: 2500,
                //     nextSelector: '.pagination li.active + li a',
                //     contentSelector: 'div.infinite-scroll',
                //     callback: function () {
                        // $('ul.pagination').remove();
                        // $('.dropify').dropify();
                        // $('.quick-edit-category').each(function (item) {
                        //     product_id = $(this).siblings('input[name="product_id"]').val();
                        //     category_id = $(this).siblings('input[name="category_id"]').val();
                        //     sizes = $(this).siblings('input[name="sizes"]').val();
                        //     selected_sizes = sizes.split(',');
                        //
                        //     $(this).attr('data-id', product_id);
                        //     var this_ = $(this);
                        //     categoryJson.forEach(function (category, index) {
                        //         if (category.id == category_id) {
                        //             this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true)
                        //             this_.closest('tr').find('.category_level_1').trigger("change");
                        //         }
                        //
                        //         category.child.forEach(function (children, i) {
                        //             if (children.id == category_id) {
                        //                 this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true);
                        //                 this_.closest('tr').find('.category_level_1').trigger("change");
                        //                 this_.closest('tr').find('.category_level_2').find('option[value="' + category_id + '"]').prop('selected', true);
                        //                 this_.closest('tr').find('.category_level_2').trigger("change");
                        //             }
                        //
                        //             children.child.forEach(function (child, i) {
                        //                 if (child.id == category_id) {
                        //                     this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true);
                        //                     this_.closest('tr').find('.category_level_1').trigger("change");
                        //                     this_.closest('tr').find('.category_level_2').find('option[value="' + children.id + '"]').prop('selected', true);
                        //                     this_.closest('tr').find('.category_level_2').trigger("change");
                        //                 }
                        //             });
                        //         });
                        //     });
                        //
                        //     $(this).find('option[value="' + category_id + '"]').prop('selected', true);
                        //
                        //     updateSizes(this, category_id);
                        //
                        //     for (var i = 0; i < selected_sizes.length; i++) {
                        //         console.log(selected_sizes[i]);
                        //         // $(this).closest('tr').find('.quick-edit-size option[value="' + selected_sizes[i] + '"]').attr('selected', 'selected');
                        //         $(this).closest('tr').find(".quick-edit-size option[value='" + selected_sizes[i] + "']").attr('selected', 'selected');
                        //     }
                        // });
                    // }
                // });
            // });
            $(window).scroll(function() {
                if ( ( $(window).scrollTop() + $(window).outerHeight() ) >= ( $(document).height() - 2500 ) ) {
                    loadMoreProducts();
                }
            });

            function loadMoreProducts() {
                if (isLoadingProducts)
                    return;
                isLoadingProducts = true;
                if(!$('.pagination li.active + li a').attr('href'))
                return;

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
                    if('' === data.trim())
                        return;

                    $loader.hide();

                    $('.infinite-scroll-data').append(data);

                    isLoadingProducts = false;
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.error('something went wrong');

                    isLoadingProducts = false;
                });
            }
            $('.dropify').dropify();
            // $(".select-multiple").multiselect();
            $(".select-multiple").select2({
                minimumResultsForSearch: -1
            });
            $("body").tooltip({selector: '[data-toggle=tooltip]'});
        });
        var category_tree = {!! json_encode($category_tree) !!};
        var categories_array = {!! json_encode($categories_array) !!};
        var id_list = {
            41: ['34', '34.5', '35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44'], // Women Shoes
            5: ['34', '34.5', '35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44'], // Men Shoes
            40: ['36-36S', '38-38S', '40-40S', '42-42S', '44-44S', '46-46S', '48-48S', '50-50S'], // Women Clothing
            12: ['36-36S', '38-38S', '40-40S', '42-42S', '44-44S', '46-46S', '48-48S', '50-50S'], // Men Clothing
            63: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXL'], // Women T-Shirt
            31: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXL'], // Men T-Shirt
            120: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Sweat Pants
            123: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Pants
            128: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Denim
            130: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Men Denim
            131: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Men Sweat Pants
            42: ['60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120'], // Women Belts
            14: ['60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120'], // Men Belts
        };
        var product_id = '';
        var category_id = '';
        var sizes = '';
        var selected_sizes = [];
        // $('.quick-edit-category').each(function (item) {
        //     product_id = $(this).siblings('input[name="product_id"]').val();
        //     category_id = $(this).siblings('input[name="category_id"]').val();
        //     sizes = $(this).siblings('input[name="sizes"]').val();
        //     selected_sizes = sizes.split(',');
        //
        //     $(this).attr('data-id', product_id);
        //
        //     var this_ = $(this);
        //     categoryJson.forEach(function (category, index) {
        //         if (category.id == category_id) {
        //             this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true)
        //             this_.closest('tr').find('.category_level_1').trigger("change");
        //         }
        //
        //         category.child.forEach(function (children, i) {
        //             if (children.id == category_id) {
        //                 this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true);
        //                 this_.closest('tr').find('.category_level_1').trigger("change");
        //                 this_.closest('tr').find('.category_level_2').find('option[value="' + category_id + '"]').prop('selected', true);
        //                 this_.closest('tr').find('.category_level_2').trigger("change");
        //             }
        //
        //             children.child.forEach(function (child, i) {
        //                 if (child.id == category_id) {
        //                     this_.closest('tr').find('.category_level_1').find('option[value="' + category.id + '"]').prop('selected', true);
        //                     this_.closest('tr').find('.category_level_1').trigger("change");
        //                     this_.closest('tr').find('.category_level_2').find('option[value="' + children.id + '"]').prop('selected', true);
        //                     this_.closest('tr').find('.category_level_2').trigger("change");
        //                 }
        //             });
        //         });
        //     });
        //
        //     $(this).find('option[value="' + category_id + '"]').prop('selected', true);
        //
        //     updateSizes(this, category_id);
        //
        //     for (var i = 0; i < selected_sizes.length; i++) {
        //         $(this).closest('tr').find(".quick-edit-size option[value='" + selected_sizes[i] + "']").attr('selected', 'selected');
        //     }
        // });
        $(document).on('click', '.edit-task-button', function () {
            var task = $(this).data('task');
            var url = "{{ url('development') }}/" + task.id + "/edit";
            @if(auth()->user()->checkPermission('development-list'))
            $('#user_field').val(task.user_id);
            @endif
            $('#priority_field').val(task.priority);
            $('#task_field').val(task.task);
            $('#task_subject').val(task.subject);
            $('#cost_field').val(task.cost);
            $('#status_field').val(task.status);
            $('#estimate_time_field').val(task.estimate_time);
            $('#start_time_field').val(task.start_time);
            $('#end_time_field').val(task.end_time);
            $('#editTaskForm').attr('action', url);
        });

        $(document).on("click",".update-product-icn", function() {
            var id = $(this).data("id");
            var field = $(this).closest(".edited-field-value").find(".edited-field");
            var field_name = field.attr("name");
            var field_value = field.val();
            var main_row = $(".quick-edit-name-"+id);
            var data = {}
                data["_token"] = "{{ csrf_token() }}";
                data[field_name] = field_value;

            var formurl =  "{{ url('products') }}/" + id + '/updateName';
            if(field_name =="description") {
                var formurl =  "{{ url('products') }}/" + id + '/updateDescription';
                var main_row = $(".quick-edit-description-"+id);
            }
            $.ajax({
                type: 'POST',
                url: formurl,
                data: data
            }).done(function () {
                main_row.find("span").html(field_value);
                $(".common-modal").modal("hide");
            }).fail(function (response) {
                console.log(response);
                alert('Could not update name');
                $(".common-modal").modal("hide");
            });
        });


        $(document).on('click', '.quick-edit-name', function () {
            var id = $(this).data('id');
            var value = $(this).find('.quick-name').text();
            var commandModel = $(".common-modal");
            var body = commandModel.find(".edited-field-value");
            var html = `<div class="row">
                            <div class="col-md-11">
                                <input type="text" class="form-control edited-field" name="name" value="`+value+`" placeholder="Enter name">
                            </div>
                            <div class="col-md-1">
                                <i style="cursor: pointer;" class="fa fa-check update-product-icn" title="save" data-id="`+id+`" data-type="approve" aria-hidden="true">
                                </i>
                            </div>
                        </div>`;
                body.html(html);
                commandModel.modal("show");
            return false;
        });

        $(document).on('click', '.quick-edit-description', function () {
            var id = $(this).data('id');
            var value = $(this).find('span.quick-description').text();
            console.log(value);
            var commandModel = $(".common-modal");
            var body = commandModel.find(".edited-field-value");
            var html = `<div class="row">
                            <div class="col-md-11">
                                <textarea class="form-control edited-field" name="description" placeholder="Enter description">`+value+`</textarea>
                            </div>
                            <div class="col-md-1">
                                <i style="cursor: pointer;" class="fa fa-check update-product-icn" title="save" data-id="`+id+`" data-type="approve" aria-hidden="true">
                                </i>
                            </div>
                        </div>`;
                body.html(html);
                commandModel.modal("show");
            return false;
        });

        $(document).on('click', '.btn-composition', function () {
            var id = $(this).data('id');
            var composition = $(this).data('value');
            var thiss = $(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('products') }}/" + id + '/updateComposition',
                data: {
                    _token: "{{ csrf_token() }}",
                    composition: composition,
                }
            }).done(function () {
                $(thiss).addClass('hidden');
                $(thiss).siblings('.quick-composition').text(composition);
                $(thiss).siblings('.quick-composition').removeClass('hidden');
            }).fail(function (response) {
                console.log(response);
                alert('Could not update composition');
            });
        });
        $(document).on('change', '.quick-edit-composition-select', function () {
            var id = $(this).data('id');
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('products') }}/" + id + '/updateComposition',
                data: {
                    _token: "{{ csrf_token() }}",
                    composition: $(this).val(),
                }
            }).done(function () {
                $this.addClass('hidden');
                $this.siblings('.quick-composition').text(composition);
                $this.siblings('.quick-composition').removeClass('hidden');
            }).fail(function (response) {
                alert('Could not update composition');
            });
        });
        $(document).on('click', '.quick-edit-composition', function () {
            var id = $(this).data('id');
            $(this).closest('td').find('.quick-composition').addClass('hidden');
            $(this).closest('td').find('.quick-edit-composition-input').removeClass('hidden');
            $(this).closest('td').find('.quick-edit-composition-input').focus();
            $(this).closest('td').find('.quick-edit-composition-input').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);
                if (key == 13) {
                    e.preventDefault();
                    var composition = $(thiss).val();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('products') }}/" + id + '/updateComposition',
                        data: {
                            _token: "{{ csrf_token() }}",
                            composition: composition,
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.quick-composition').text(composition);
                        $(thiss).siblings('.quick-composition').removeClass('hidden');
                    }).fail(function (response) {
                        console.log(response);
                        alert('Could not update composition');
                    });
                }
            });
        });
        $(document).on('change', '.quick-edit-color', function () {
            var color = $(this).val();
            var id = $(this).data('id');
            var thiss = $(this);
            $.ajax({
                type: "POST",
                url: "{{ url('products') }}/" + id + '/updateColor',
                data: {
                    _token: "{{ csrf_token() }}",
                    color: color
                }
            }).done(function () {
                $(thiss).css({border: "2px solid green"});
                setTimeout(function () {
                    $(thiss).css({border: "1px solid #ccc"});
                }, 2000);
            }).fail(function (response) {
                alert('Could not update the color');
                console.log(response);
            });
        });
        $(document).on('click', '.ai-btn-color', function () {
            var color = $(this).data('value');
            var id = $(this).data('id');
            var btnclicked = $(this);
            $.ajax({
                type: "POST",
                url: "{{ url('products') }}/" + id + '/updateColor',
                data: {
                    _token: "{{ csrf_token() }}",
                    color: color
                }
            }).done(function () {
                $(btnclicked).css({border: "2px solid green"});
                $('#quick-edit-color-' + id).val(color);
                setTimeout(function () {
                    $(btnclicked).css({border: "1px solid #ccc"});
                }, 3000);
            }).fail(function (response) {
                alert('Could not update the color');
                console.log(response);
            });
        });
        $(document).on('change', '.quick-edit-category', function () {
            var category = $(this).val();
            var id = $(this).data('id');
            var thiss = $(this);
            $.ajax({
                type: "POST",
                url: "{{ url('products') }}/" + id + '/updateCategory',
                data: {
                    _token: "{{ csrf_token() }}",
                    category: category
                }
            }).done(function () {
                $(thiss).css({border: "2px solid green"});
                setTimeout(function () {
                    $(thiss).css({border: "1px solid #ccc"});
                }, 2000);
            }).fail(function (response) {
                alert('Could not update the category');
                console.log(response);
            });
            updateSizes(thiss, $(thiss).val());
        });
        $(document).on('click', '.ai-btn-category', function () {
            var category = $(this).data('category');
            var id = $(this).data('id');
            var btnclicked = $(this);
            $.ajax({
                type: "POST",
                url: "{{ url('products') }}/" + id + '/updateCategory',
                data: {
                    _token: "{{ csrf_token() }}",
                    category: category
                }
            }).done(function () {
                $(btnclicked).css({border: "2px solid green"});
                $('#quick-edit-category-' + id).val(category);
                setTimeout(function () {
                    $(btnclicked).css({border: "1px solid #ccc"});
                }, 3000);
            }).fail(function (response) {
                alert('Could not update the category');
                console.log(response);
            });
            updateSizes(thiss, $(thiss).val());
        });
        $(document).on('click', '.quick-edit-size-button', function () {
            var size = $(this).siblings('.quick-edit-size').val();
            // var other_size = $(this).siblings('input[name="other_size"]').val();
            var data_ = $(this).closest('td').find('input[name="measurement"]').val();
            data_ = data_.slice('x');
            var lmeasurement = data_[0];
            var hmeasurement = data_[1];
            var dmeasurement = data_[2];
            var id = $(this).data('id');
            var thiss = $(this);
            console.log(size);
            $.ajax({
                type: "POST",
                url: "{{ url('products') }}/" + id + '/updateSize',
                data: {
                    _token: "{{ csrf_token() }}",
                    size: size,
                    lmeasurement: lmeasurement,
                    hmeasurement: hmeasurement,
                    dmeasurement: dmeasurement
                },
                beforeSend: function () {
                    $(thiss).text('Saving...');
                }
            }).done(function () {
                $(thiss).text('Save');
                $(thiss).css({color: "green"});
                setTimeout(function () {
                    $(thiss).css({color: "inherit"});
                }, 2000);
            }).fail(function (response) {
                $(thiss).text('Save');
                alert('Could not update the category');
                console.log(response);
            });
        });
        $(document).on('dblclick', '.quick-edit-price', function () {
            var id = $(this).data('id');
            $(this).find('.quick-price').addClass('hidden');
            $(this).find('.quick-edit-price-input').removeClass('hidden');
            $(this).find('.quick-edit-price-input').focus();
            $(this).find('.quick-edit-price-input').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);
                if (key == 13) {
                    e.preventDefault();
                    var price = $(thiss).val();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('products') }}/" + id + '/updatePrice',
                        data: {
                            _token: "{{ csrf_token() }}",
                            price: price,
                        }
                    }).done(function (response) {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.quick-price').text(price);
                        $(thiss).siblings('.quick-price').removeClass('hidden');
                        $(thiss).siblings('.quick-price-inr').text(response.price_inr);
                        $(thiss).siblings('.quick-price-special').text(response.price_special);
                    }).fail(function (response) {
                        console.log(response);
                        alert('Could not update price');
                    });
                }
            });
        });
        $(document).on('click', '.quick-images-upload', function () {
            var id = $(this).data('id');
            var thiss = $(this);
            var images = $(this).closest('td').find('input[type="file"]').prop('files');
            var images_array = [];
            var form_data = new FormData();
            console.log(images);
            console.log($(this).closest('td').find('input[type="file"]'));
            form_data.append('_token', "{{ csrf_token() }}");
            Object.keys(images).forEach(function (index) {
                form_data.append('images[]', images[index]);
            });
            $.ajax({
                type: 'POST',
                url: "{{ url('products') }}/" + id + '/quickUpload',
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                data: form_data
            }).done(function (response) {
                $(thiss).closest('tr').find('.quick-image-container').attr('src', response.image_url);
                $(thiss).closest('td').find('.dropify-clear').click();
                $(thiss).parent('div').find('img').remove();
                $(thiss).parent('div').append('<img src="/images/1.png" class="ml-1" alt="">');
            }).fail(function (response) {
                console.log(response);
                alert('Could not upload images');
            });
        });
        $(document).on('click', '.read-more-button', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.short-description-container').toggleClass('hidden');
                $(this).find('.long-description-container').toggleClass('hidden');
            }
        });
        $(document).on('click', '.quick-description-edit-textarea', function (e) {
            e.stopPropagation();
        });
        /*$(document).on('click', '.quick-edit-description', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            $(this).siblings('.long-description-container').removeClass('hidden');
            $(this).siblings('.short-description-container').addClass('hidden');
            $(this).siblings('.long-description-container').find('.description-container').addClass('hidden');
            $(this).siblings('.long-description-container').find('.quick-description-edit-textarea').removeClass('hidden');
            $(this).siblings('.long-description-container').find('.quick-description-edit-textarea').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);
                if (key == 13) {
                    e.preventDefault();
                    var description = $(thiss).val();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('products') }}/" + id + '/updateDescription',
                        data: {
                            _token: "{{ csrf_token() }}",
                            description: description,
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.description-container').text(description);
                        $(thiss).siblings('.description-container').removeClass('hidden');
                        $(thiss).siblings('.quick-description-edit-textarea').addClass('hidden');
                        $('#description' + id).hide();
                        $('#description' + id).html(description);
                        $('#description' + id).show(1000);
                        var short_description = description.substr(0, 100);
                        $(thiss).closest('.long-description-container').siblings('.short-description-container').text(short_description);
                    }).fail(function (response) {
                        console.log(response);
                        alert('Could not update description');
                    });
                }
            });
        });*/
        function updateSizes(element, category_value) {
            var found_id = 0;
            var found_final = false;
            var found_everything = false;
            var category_id = category_value;
            $(element).closest('tr').find('.quick-edit-size').empty();
            $(element).closest('tr').find('.quick-edit-size').append($('<option>', {
                value: '',
                text: 'Select Category'
            }));
            console.log('PARENT ID', categories_array[category_id]);
            if (categories_array[category_id] != 0) {
                Object.keys(id_list).forEach(function (id) {
                    if (id == category_id) {
                        $(element).closest('tr').find('.quick-edit-size').empty();
                        $(element).closest('tr').find('.quick-edit-size').append($('<option>', {
                            value: '',
                            text: 'Select Category'
                        }));
                        id_list[id].forEach(function (value) {
                            $(element).closest('tr').find('.quick-edit-size').append($('<option>', {
                                value: value,
                                text: value
                            }));
                        });
                        found_everything = true;
                        // $(element).closest('tr').find('.quick-edit-size').removeClass('hidden');
                        $(element).closest('tr').find('.lmeasurement-container').addClass('hidden');
                        $(element).closest('tr').find('.hmeasurement-container').addClass('hidden');
                        $(element).closest('tr').find('.dmeasurement-container').addClass('hidden');
                    }
                });
                if (!found_everything) {
                    Object.keys(category_tree).forEach(function (key) {
                        Object.keys(category_tree[key]).forEach(function (index) {
                            if (index == categories_array[category_id]) {
                                found_id = index;
                                return;
                            }
                        });
                    });
                    console.log('FOUND ID', found_id);
                    if (found_id != 0) {
                        Object.keys(id_list).forEach(function (id) {
                            if (id == found_id) {
                                $(element).closest('tr').find('.quick-edit-size').empty();
                                $(element).closest('tr').find('.quick-edit-size').append($('<option>', {
                                    value: '',
                                    text: 'Select Category'
                                }));
                                id_list[id].forEach(function (value) {
                                    $(element).closest('tr').find('.quick-edit-size').append($('<option>', {
                                        value: value,
                                        text: value
                                    }));
                                });
                                // $(element).closest('tr').find('input[name="other_size"]').addClass('hidden');
                                // $(element).closest('tr').find('.quick-edit-size').removeClass('hidden');
                                $(element).closest('tr').find('.lmeasurement-container').addClass('hidden');
                                $(element).closest('tr').find('.hmeasurement-container').addClass('hidden');
                                $(element).closest('tr').find('.dmeasurement-container').addClass('hidden');
                                found_final = true;
                            }
                        });
                    }
                }
                if (!found_final) {
                    // $(element).closest('tr').find('input[name="other_size"]').removeClass('hidden');
                    // $(element).closest('tr').find('.quick-edit-size').addClass('hidden');
                    $(element).closest('tr').find('.lmeasurement-container').removeClass('hidden');
                    $(element).closest('tr').find('.hmeasurement-container').removeClass('hidden');
                    $(element).closest('tr').find('.dmeasurement-container').removeClass('hidden');
                }
            }
        }
        $(document).on('click', '.use-description', function () {
            var id = $(this).data('id');
            var description = $(this).data('description');
            url = "{{ url('products') }}/" + id + '/updateDescription';
            $('#description' + id).hide();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    description: description,
                    _token: "{{ csrf_token() }}",
                }
            }).done(function (response) {
                $('#description' + id).html(description);
                $('#span_description_' + id).html(description);
                $('#textarea_description_' + id).text(description);
                $('#description' + id).show(1000);
            });
        });
        $(document).on('click', '#upload-all', function () {
            $(self).hide();
            var ajaxes = [];
            for (var i = 0; i < productIds.length; i++) {
                url = "{{ url('products') }}/" + productIds[i] + '/listMagento';
                ajaxes.push($.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function (response) {
                    $('#product' + productIds[i]).hide();
                }));
            }
            $.when.apply($, ajaxes)
                .done(function () {
                    //location.reload();
                });
        });
        $(document).on('click', '.upload-magento', function () {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var thiss = $(this);
            var url = '';
            if (type == 'approve') {
                url = "{{ url('products') }}/" + id + '/approveProduct';
            } else if (type == 'list') {
                url = "{{ url('products') }}/" + id + '/listMagento';
            } else if (type == 'enable') {
                url = "{{ url('products') }}/" + id + '/approveMagento';
            } else if (type == 'submit_for_approval') {
                url = "{{ url('products') }}/" + id + '/submitForApproval';
            } else {
                url = "{{ url('products') }}/" + id + '/updateMagento';
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    // $(thiss).text('Loading...');
                    $(thiss).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
                }
            }).done(function (response) {
                if (response.result != false && response.status == 'is_approved') {
                    $(thiss).closest('tr').remove();
                } else if (response.result != false && response.status == 'listed') {
                    // $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                } else if (response.result != false && response.status == 'approved') {
                    // $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                } else {
                    // $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                }
            }).fail(function (response) {
                console.log(response);
                if (type == 'approve') {
                    // $(thiss).text('Approve');
                } else if (type == 'list') {
                    // $(thiss).text('List');
                } else if (type == 'enable') {
                    // $(thiss).text('Enable');
                } else {
                    // $(thiss).text('Update');
                }
                alert('Could not update product on magento');
            });
        });
        $(document).on('click', '.make-remark', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);
            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "productlistings"
                },
            }).done(response => {
                var html = '';
                $.each(response, function (index, value) {
                    html += ' <p> ' + value.remark + ' <br/> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#makeRemarkModal").find('#remark-list').html(html);
            });
        });
        $('#addRemarkButton').on('click', function () {
            alert('adding remark...');
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark').find('textarea[name="remark"]').val();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'productlistings'
                },
            }).done(response => {
                $('#add-remark').find('textarea[name="remark"]').val('');
                var html = ' <p> ' + remark + ' <br/> <small>By You updated on ' + moment().format('DD-M H:mm') + ' </small></p>';
                $("#makeRemarkModal").find('#remark-list').append(html);
            }).fail(function (response) {
                console.log(response);
                alert('Could not fetch remarks');
            });
        });
        $(document).on('click', '.delete-thumbail-img', function (e) {
            e.preventDefault();
            var conf = confirm("Are you sure you want to delete this image ?");
            if (conf == true) {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('product.deleteImages') }}',
                    data: {
                        product_id: $this.data("product-id"),
                        media_id: $this.data("media-id"),
                        media_type: $this.data("media-type")
                    },
                }).done(response => {
                    if (response.code == 1) {
                        $this.closest(".thumbnail-pic").remove();
                        $this.closest(".product-list-card").remove();
                    }
                });
            }
        });
        function bigImg(img) {
            $('#large-image').attr("src", img);
            $('#imageExpand').modal('show');
        }
        function normalImg() {
            $('#imageExpand').modal('hide');
        }

        function bigImageModal(img)
        {
            $(".large-image-map").attr("src", img);
        }

        function shortCrop(img, id, site_id, gridImage)
        {
            var commandModel = $(".common-modal-crop");
            var body = commandModel.find(".edited-field-value");
            var html = `<div style="position:relative;" id="image`+id+``+site_id+`">
                            <div style=" margin-bottom: 5px; width: 300px;height: 300px; background-image: url('`+img+`'); background-size: 300px; display:inline-block; vertical-align:middle;">
                            <img style="width: 300px;" src="`+gridImage+`"
                                 class="quick-image-container img-responive" style="width: 100%;"
                                 alt="" data-toggle="tooltip" data-placement="top"
                                 title="ID: `+id+`" id="image-tag`+id+``+site_id+`"></div> 
                        </div>
                        <div><button onclick="cropPopup('`+img+`',`+id+`)" class="btn btn-secondary">Crop</button></div>`;
            body.html(html);
            commandModel.modal("show");
            var example = $('#image' + id+site_id).cropme({
                customClass : 'crp-me-container'
            });
            example.cropme('bind', {
                url: img,
                customClass : 'crp-me-container'
            });
            example.cropme('reload', {
                zoom: {
                    min: 0.01,
                    max: 1,
                    enable: true,
                    mouseWheel: true,
                    slider: true,
                }
            });
        }

        function cropImage(img, id, site_id) {
            $('#image-tag' + id+site_id).hide();
            $('#image' + id+site_id).removeAttr("style");
            $('#image' + id+site_id).prop("onclick", null).off("click");
            $('#image' + id+site_id).height('336');
            console.log(img);
            console.log(id);
            console.log(site_id);
            var example = $('#image' + id+site_id).cropme();
            example.cropme('bind', {
                url: img,
            });
            example.cropme('reload', {
                zoom: {
                    min: 0.01,
                    max: 1,
                    enable: true,
                    mouseWheel: true,
                    slider: true,
                }
            });
        }

        function cropPopup(img, id) {
            style = $('.cropme-container img').attr("style");
            $.ajax({
                url: '/products/listing/final-crop-image',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    style: style,
                    img, img,
                    id, id,
                },
            })
                .done(function () {
                    toastr["success"]('Image Cropped and Saved Successfully');
                    $(".common-modal-crop").modal("hide");
                })
                .fail(function () {
                    console.log("error");
                });
        }

        function crop(img, id, gridImage,site_id) {
            style = $('.cropme-container img').attr("style");
            $.ajax({
                url: '/products/listing/final-crop-image',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    style: style,
                    img, img,
                    id, id,
                },
            })
                .done(function () {
                    var d = new Date();
                    var n = d.toLocaleTimeString();
                    newurl = img + '?version=' + n;
                    html = '<div onclick="bigImg(\'' + url + '\')" style=" margin-bottom: 5px; width: 300px;height: 300px; background-image: url(\'' + newurl + '\'); background-size: 300px" id="image' + id +site_id+ '"><img style="width: 300px;" src="/images/' + gridImage + '" class="quick-image-container img-responive" alt="" data-toggle="tooltip" data-placement="top" title="ID: ' + id + '" id="image-tag' + id +site_id + '"></div><button onclick="cropImage(\'' + img + '\',' + id + ',' + site_id + ')" class="btn btn-secondary">Crop Image</button><button onclick="crop(\'' + img + '\',' + id + ',\'' + gridImage + '\','+ site_id +')" class="btn btn-secondary">Crop</button>';
                    $('#col-large-image' + id+site_id).empty().append(html);
                    alert('Image Cropped and Saved Successfully');
                })
                .fail(function () {
                    console.log("error");
                });
        }
        function replaceThumbnail(id, url, gridImage,site_id) {
            html = '<div onclick="bigImg(\'' + url + '\')" style=" margin-bottom: 5px; width: 300px;height: 300px; background-image: url(\'' + url + '\'); background-size: 300px" id="image' + id +site_id+ '"><img style="width: 300px;" src="/images/' + gridImage + '" class="quick-image-container img-responive" alt="" data-toggle="tooltip" data-placement="top" title="ID: ' + id + '" id="image-tag' + id +site_id + '"></div><button onclick="cropImage(\'' + url + '\',' + id + ',' + site_id + ')" class="btn btn-secondary">Crop Image</button><button onclick="crop(\'' + url + '\',' + id + ',\'' + gridImage + '\', '+ site_id +')" class="btn btn-secondary">Crop</button>';
            $('#col-large-image' + id+site_id).empty().append(html);
        }
        $(document).on("click", ".set-description-site", function () {
            var $this = $(this);
            var modal = $("#set-description-site-wise");
            modal.find("#store-product-id").val($this.data("id"));
            modal.find("#store-product-description").val($this.data("description"));
            modal.find("#show-description-summery").html($this.data("description"));
            modal.modal("show");
        });
        $(document).on("click", ".btn-save-store", function (e) {
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
                url: '/product/store-website-description',
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    $("#loading-image-preview").show();
                },
                data: form.serialize(),
                dataType: "json"
            }).done(function (response) {
                $("#loading-image-preview").hide();
                if (response.code == 200) {
                    $("#set-description-site-wise").modal("hide");
                    toastr["success"](response.message);
                } else {
                    toastr["error"](response.message);
                }
            }).fail(function () {
                $("#loading-image-preview").hide();
                console.log("error");
            });
        });
        
        $('#main_checkbox').on('click', function (e) {
            if ($(this).is(':checked', true)) {
                $(".affected_checkbox").prop('checked', true);
            } else {
                $(".affected_checkbox").prop('checked', false);
            }
        });
        $('.mass_action').on('click', function (e) {
            var allVals = [];
            $(".affected_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            if (allVals.length <= 0) {
                alert("Please select row.");
            } else {
                if (this.className == 'btn btn-secondary text-left mass_action delete_checked_products') {
                    var check = confirm("Are you sure you want to delete this row?");
                    var final_url = '{{route('products.mass.delete')}}';
                } else {
                    var check = confirm("Are you sure you want to approve this row?");
                    var final_url = '{{route('products.mass.approve')}}';
                }
                if (check == true) {
                    var join_selected_values = allVals.join(",");
                    $.ajax({
                        url: final_url,
                        type: 'get',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids=' + join_selected_values,
                        success: function (data) {
                            console.log(data);
                            if (data['status']) {
                                alert(data['success']);
                                $(".affected_checkbox:checked").each(function () {
                                    if (data['result']) {
                                        console.log((data['status']));
                                        $(".affected_checkbox:checked").prop('checked', false);
                                    } else {
                                        $(this).parents("tr").remove();
                                    }
                                });
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            // alert(data.responseText);
                            console.log(data);
                        }
                    });
                } else {
                    $(".affected_checkbox:checked").prop('checked', false);
                    $("#main_checkbox:checked").prop('checked', false);
                }
            }
        });
        /*$(document).on('click', '.quick-description', function () {
            var id = $(this).data('id');
            $(this).closest('td').find('.quick-description').addClass('hidden');
            $(this).closest('td').find('.quick-edit-description-textarea').removeClass('hidden');
            $(this).closest('td').find('.quick-edit-description-textarea').focus();
        });*/
        /*$(document).on('keypress', '.quick-edit-description-textarea', function (e) {
            var id = $(this).parents('.quick-edit-description').data('id');
            var key = e.which;
            var thiss = $(this);
            if (key == 13) {
                e.preventDefault();
                var description = $(thiss).val();
                $(thiss).addClass('hidden');
                $(thiss).siblings('.quick-description').text(description.substring(0, 20) + (description.length > 20 ? '...' : ''));
                $(thiss).siblings('.quick-description').removeClass('hidden');
                $.ajax({
                    type: 'POST',
                    url: "{{ url('products') }}/" + id + '/updateDescription',
                    data: {
                        _token: "{{ csrf_token() }}",
                        description: description,
                    }
                }).done(function () {
                }).fail(function (response) {
                    alert('Could not update description');
                });
            }
        });*/
        $(document).on('change', '.post-remark', function () {
            const data = {
                _token: "{{ csrf_token() }}",
                rejected: 1,
                product_id: $(this).data('id'),
                remark: $(this).val(),
                senior: 1
            };
            $.ajax({
                type: 'POST',
                url: "{{ url('products') }}/" + $(this).data('id') + '/addListingRemarkToProduct',
                data: data
            }).done(function () {
            }).fail(function (response) {
                alert('Could not update status');
            });
        });
        $(document).on('change', '.approved_by', function () {
            const data = {
                _token: "{{ csrf_token() }}",
                product_id: $(this).data('id'),
                user_id: $(this).val(),
            };
            $.ajax({
                type: 'POST',
                url: "{{ url('products') }}/" + $(this).data('id') + '/updateApprovedBy',
                data: data
            }).done(function () {
            }).fail(function (response) {
                alert('Could not update status');
            });
        });
        $(document).on('click', '.upload-single', function () {
            $(self).hide();
            $this = $(this);
            var ajaxes = [];
            // for (var i = 0; i < productIds.length; i++) {
            var id = $(this).data('id');
            var thiss = $(this);
            $(this).addClass('fa-spinner').removeClass('fa-upload')
            url = "{{ url('products') }}/" + id + '/listMagento';
            ajaxes.push($.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    // $(thiss).text('Loading...');
                    // $(thiss).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
                }
            }).done(function (response) {
                thiss.removeClass('fa-spinner').addClass('fa-upload')
                toastr['success']('Request Send successfully', 'Success')
                $('#product' + id).hide();
            }).fail(function (response) {
                console.log(response);
                thiss.removeClass('fa-spinner').addClass('fa-upload')
                toastr['error']('Internal server error', 'Failure')
                $('#product' + id).hide();
                //alert('Could not update product on magento');
            }));

            // }
            $.when.apply($, ajaxes)
                .done(function () {
                    //location.reload();
                });
        });
        $(document).on('click', '.product-slider-arrow', function () {
            var active_ele = $(this).parents('.modal-body').find('.product-slider.d-block');
            if (active_ele.length !== 0) {
                if (active_ele.next().length !== 0 && active_ele.next().hasClass('product-slider')) {
                    active_ele.addClass('d-none').removeClass('d-block');
                    active_ele.next().addClass('d-block').removeClass('d-none');
                    console.log(active_ele.next().hasClass('.product-slider'), 'next');
                }
            }
        })
        $(document).on('click', '.product-slider-arrow-left', function () {
            var active_ele = $(this).parents('.modal-body').find('.product-slider.d-block');
            if (active_ele.length !== 0) {
                if (active_ele.prev().length !== 0 && active_ele.prev().hasClass('product-slider')) {
                    active_ele.addClass('d-none').removeClass('d-block');
                    active_ele.prev().addClass('d-block').removeClass('d-none');
                    console.log(active_ele.prev().hasClass('product-slider'), 'prev');
                }
            }
        })
        $(document).on('click', '.reject-product-cropping', function(){
            var product_id = $(this).data('product_id');
            var site_id = $(this).data('site_id');
            const data = {
                _token: "{{ csrf_token() }}",
                product_id: $(this).data('product_id'),
                site_id: $(this).data('site_id'),
                status: $(this).val(),
            };
            $.ajax({
                type: 'POST',
                url: "/product/crop_rejected_status",
                data: data
            }).done(function (response) {
                var cssId = '#reject-product-cropping'+site_id+product_id;
                $(cssId).text('Rejected');
                $(cssId).html('Rejected');
                if(response.code == 200) {
                    toastr['success'](response.message, 'Success')
                }
            }).fail(function (response) {
                alert('Could not update status');
            });
        })

        $(document).on('click', '.reject-all-cropping', function(){
            var product_id = $(this).data('product_id');
            const data = {
                _token: "{{ csrf_token() }}",
                product_id: $(this).data('product_id'),
                status: $(this).val(),
            };
            $.ajax({
                type: 'POST',
                url: "/product/all_crop_rejected_status",
                data: data
            }).done(function (response) {
                var cssId = '#reject-all-cropping'+product_id;
                $(cssId).text('All Rejected');
                if(response.code == 200) {
                    toastr['success'](response.message, 'Success')
                }
            }).fail(function (response) {
                alert('Could not update status');
            });
        })

       
    </script>
    <script>
    var i=0;
        var scroll = true;
        var old_product;
        function start_scroll_down() { 
            if(scroll){
                console.log("in scroll")
                let product_id = $(".infinite-scroll-data table tbody .col-md-12").eq(i-1).attr("productid");
                console.log(product_id)
              if($(".autopushproduct").attr("auto_push_value") == "1" && product_id != undefined && old_product != product_id){
                old_product = product_id
              //  alert("auto update");
                var ajaxes = [];
                var id = product_id;
                url = "{{ url('products') }}/" + id + '/listMagento';
                ajaxes.push($.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        // $(thiss).text('Loading...');
                        // $(thiss).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
                    }
                }).done(function (response) {
                    toastr['success']('Request Send successfully', 'Success')
                }).fail(function (response) {
                    console.log(response);
                    toastr['error']('Internal server error', 'Failure')
                   
                    //alert('Could not update product on magento');
                }));

            // }
                $.when.apply($, ajaxes)
                    .done(function () {
                        //location.reload();
                    });
              }
                //check if 
                 // scroll =  setInterval(function() {
           // $(".infinite-scroll-data table thead").each(function(i, e) {
            $("html, body").animate({
                scrollTop: $(".infinite-scroll-data table tbody .col-md-12").eq(i).offset().top
                }, 500).delay(500); // First value is a speed of scroll, and second time break
          //  });
          i++;
          //  }(), 500);
            }else{
                console.log("no scroll")
            }
          

         
        }
	    var stop;
        function callinterval(){
            if($("#scrolltime").val() == ""){
                toastr["error"]("please add time interval for scroll");
                return;
            }
                
            $(".start-again").removeClass("hide")
            $(".pause").removeClass("hide")

            $(".start-again").attr("disabled","disabled")
            $(".pause").attr("disabled",false)
            $("html, body").animate({
                scrollTop: $(".infinite-scroll-data table tbody .col-md-12").eq(i).offset().top
                }, 500).delay(500); // First value is a speed of scroll, and second time break
          //  });
          i++;
            stop = setInterval(function(){ console.log("Running");start_scroll_down() }, $("#scrolltime").val()*1000);
        }

	    
	    $('#clearInt').click(function(){ 
            $(".start-again").attr("disabled",false)
            $(".pause").attr("disabled","disabled")
	        clearInterval(stop);
	        console.log("Stopped");
	    });
	</script>
@endsection
