@extends('layouts.app')

@section('title', 'Product Listing')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style>
        .quick-edit-color {
            transition: 1s ease-in-out;
        }
    </style>
@endsection

@section('large_content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Approved Products in Magento ({{ $products_count }} of which {{ $queueSize }} are still in queue)</h2>

            <div class="pull-left">
                <form class="form-inline" action="{{ action('ProductController@approvedMagento') }}" method="GET">

                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="sku,brand,category,status,stage">
                    </div>

                    <div class="form-group mr-3 mb-3">
                        {{-- {!! $category_search !!} --}}
                        <select class="form-control" name="category[]">
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
                                    <option style="background-color: {{ $color }};" value="{{ $children['id'] }}" {{ in_array($children['id'], $selected_categories) ? 'selected' : '' }}>&nbsp;&nbsp;{{ $children['title'] }}</option>

                                    @foreach ($children['child'] as $child)
                                        <option style="background-color: {{ $color }};" value="{{ $child['id'] }}" {{ in_array($child['id'], $selected_categories) ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child['title'] }}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select class="form-control select-multiple" name="brand[]" multiple data-placeholder="Brand..">
                            <optgroup label="Brands">
                                @foreach ($brands as $key => $name)
                                    <option value="{{ $key }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select class="form-control select-multiple" name="color[]" multiple data-placeholder="Color..">
                            <optgroup label="Colors">
                                @foreach ($colors as $key => $col)
                                    <option value="{{ $key }}" {{ isset($color) && $color == $key ? 'selected' : '' }}>{{ $col }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select class="form-control select-multiple" name="supplier[]" multiple data-placeholder="Supplier..">
                            <optgroup label="Suppliers">
                                @foreach ($suppliers as $key => $item)
                                    <option value="{{ $item->id }}" {{ isset($supplier) && in_array($item->id, $supplier) ? 'selected' : '' }}>{{ $item->supplier }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select class="form-control" name="type">
                            <option value="">Select Type</option>
                            <option value="Not Listed" {{ isset($type) && $type == "Not Listed" ? 'selected' : ''  }}>Not Listed</option>
                            <option value="Listed" {{ isset($type) && $type == "Listed" ? 'selected' : ''  }}>Listed</option>
                            {{--              <option value="Approved" {{ isset($type) && $type == "Approved" ? 'selected' : ''  }}>Approved</option>--}}
                            {{--              <option value="Image Cropped" {{ isset($type) && $type == "Image Cropped" ? 'selected' : ''  }}>Image Cropped</option>--}}
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select class="form-control" name="user_id" id="user_id">
                            @foreach($users as $user)
                                <option value="">Select user...</option>
                                <option value="{{$user->id}}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                </form>
            </div>
        </div>
    </div>

    {{-- @include('development.partials.modal-task')
    @include('development.partials.modal-quick-task') --}}

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-md-12">
            <div class="infinite-scroll mt-5">
                <table class="table table-bordered table-striped" style="max-width: 100% !important;">
                    <tr>
                        <th width="10%" style="max-width: 100px;">Thumbnail</th>
                        <th width="10%">Name</th>
                        <th width="10%">Description</th>
                        <th width="10%">Category</th>
                        <th width="2%">Sizes</th>
                        <th width="5%">Composition</th>
                        <th width="10%">Color</th>
                        <th width="5%">Price</th>
                        {{-- <th width="5%">Cropper</th> --}}
                        <th width="10%">Action</th>
                        <th width="20%">Remarks</th>
                    </tr>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td colspan="10">
                                <div class="row">
                                    <div class="col-md-1">
                                        @php $product = \App\Product::find($product->id) @endphp
                                        @if ($product->hasMedia(config('constants.media_tags')))
                                            @foreach($product->getMedia(config('constants.media_tags')) as $media)
                                                @if(stripos($media->filename, 'crop') !== false)
                                                    <img style="display:block; width: 70px; height: 80px; margin-top: 5px;" src="{{ $media->getUrl() }}" class="quick-image-container img-responive" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}">
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        @if ($product->hasMedia(config('constants.media_tags')))
                                            <img src="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" class="quick-image-container img-responive" style="width: 100%;" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}">
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <strong class="same-color">{{ $product->brands ? $product->brands->name : 'N/A' }}</strong>
                                        <p class="same-color">{{ $product->name }}</p>
                                        <br>
                                        <p class="same-color" style="font-size: 18px;">
                                            <span style="text-decoration: line-through">Rs. {{ number_format($product->price_inr) }}</span> Rs. {{ number_format($product->price_special) }}
                                        </p>
                                        <br>
                                        <p>
                                            <strong class="same-color" style="text-decoration: underline">Description</strong>
                                            <br>
                                            <span class="same-color">
                        {{ $product->short_description }}
                      </span>
                                        </p>
                                        <p>
                                            <strong class="same-color" style="text-decoration: underline">Composition</strong>
                                            <br>
                                            <span class="same-color">
                        {{ $product->composition }}
                      </span>
                                        </p>
                                        <p>
                                            <strong>Color</strong>: {{ $product->color }}<br>
                                            <strong>Sizes</strong>: {{ $product->size }}<br>
                                            <strong>Dimension</strong>: {{ $product->lmeasurement }} x {{ $product->hmeasurement }} x {{ $product->dmeasurement }}<br>
                                        </p>
                                        <p>
                                            <span class="sololuxury-button">ADD TO BAG</span>
                                            <span class="sololuxury-button"><i class="fa fa-heart"></i> ADD TO WISHLIST</span>
                                        </p>
                                        <p class="same-color">
                                            View All: <strong>{{ $product->product_category->title }}</strong>
                                            <br>
                                            View All: <strong>{{ $product->brands ? $product->brands->name : 'N/A' }}</strong>
                                        </p>
                                        <p class="same-color">
                                            <strong>Style ID</strong>: {{ $product->sku }}
                                            <br>
                                            <strong class="text-danger">{{ $product->is_on_sale ? 'On Sale' : '' }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <h2 class="page-heading">
                                            <a target="_new" href="{{ action('ProductController@show', $product->id) }}">{{ $product->id }}</a>
                                        </h2>
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <th>Activity</th>
                                                <th>Date</th>
                                                <th>User Name</th>
                                            </tr>
                                            <tr>
                                                <th>Cropping</th>
                                                <td>{{ $product->crop_approved_at ?? 'N/A' }}</td>
                                                <td>{{ $product->cropApprover ? $product->cropApprover->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sequencing</th>
                                                <td>{{ $product->crop_ordered_at ?? 'N/A' }}</td>
                                                <td>{{ $product->cropOrderer ? $product->cropOrderer->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Approval</th>
                                                <td>{{ $product->listing_approved_at ?? 'N/A' }}</td>
                                                <td>{{ $product->approver ? $product->approver->name : 'N/A' }}</td>
                                            </tr>
                                        </table>
                                        <p class="text-right mt-5">
                                            @if ($product->is_approved == 0)
                                                <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="approve">Approve</button>
                                            @elseif ($product->is_approved == 1 && $product->isUploaded == 0)
                                                <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="list">List</button>
                                            @elseif ($product->is_approved == 1 && $product->isUploaded == 1 && $product->isFinal == 0)
                                                <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="enable">Enable</button>
                                            @else
                                                <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="update">Update</button>
                                                <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="unlist">Unlist</button>
                                            @endif
                                        </p>
                                        <div>
                                            <input class="form-control send-message" data-sku="{{$product->sku}}" type="text" placeholder="Message..." id="message_{{$product->approved_by}}" data-id="{{$product->approved_by}}">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>

                {!! $products->appends(Request::except('page'))->links() !!}
            </div>

        </div>
    </div>
    @include('partials.modals.remarks')

@endsection

@section('scripts')
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
    <script type="text/javascript">

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

        $(document).on('click', '.reject-sequence', function (event) {
            let pid = $(this).data('id');

            $.ajax({
                url: '/reject-sequence/' + pid,
                data: {
                    senior: 1
                },
                success: function () {
                    toastr['success']('Sequence rejected successfully!', 'Success');
                },
                error: function () {
                    toastr['error']('Error rejecting sequence', 'Success');
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

        $(document).on('change', '.reject-listing', function (event) {
            let pid = $(this).data('id');
            let remark = $(this).val();

            if (remark == 0 || remark == '0') {
                return;
            }

            let self = this;

            $.ajax({
                url: '{{action('ProductController@addListingRemarkToProduct')}}',
                data: {
                    product_id: pid,
                    remark: remark,
                    rejected: 1,
                    senior: 1
                },
                success: function (response) {
                    toastr['success']('Product rejected successfully!', 'Rejected');
                    $(self).removeAttr('disabled');
                    $(self).val();
                },
                beforeSend: function () {
                    $(self).attr('disabled');
                }, error: function () {
                    $(self).removeAttr('disabled');
                }
            });

        });
        $(document).ready(function () {

            $('ul.pagination').hide();
            $(function () {
                $('.infinite-scroll').jscroll({
                    autoTrigger: true,
                    loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                    padding: 2500,
                    nextSelector: '.pagination li.active + li a',
                    contentSelector: 'div.infinite-scroll',
                    callback: function () {
                        // $('ul.pagination').remove();
                        $('.dropify').dropify();

                        $('.quick-edit-category').each(function (item) {
                            product_id = $(this).siblings('input[name="product_id"]').val();
                            category_id = $(this).siblings('input[name="category_id"]').val();
                            sizes = $(this).siblings('input[name="sizes"]').val();
                            selected_sizes = sizes.split(',');

                            $(this).attr('data-id', product_id);
                            $(this).find('option[value="' + category_id + '"]').prop('selected', true);

                            updateSizes(this, category_id);

                            for (var i = 0; i < selected_sizes.length; i++) {
                                console.log(selected_sizes[i]);
                                // $(this).closest('tr').find('.quick-edit-size option[value="' + selected_sizes[i] + '"]').attr('selected', 'selected');
                                $(this).closest('tr').find(".quick-edit-size option[value='" + selected_sizes[i] + "']").attr('selected', 'selected');
                            }
                        });
                    }
                });
            });

            $('.dropify').dropify();
            // $(".select-multiple").multiselect();
            $(".select-multiple").select2();
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

        $('.quick-edit-category').each(function (item) {
            product_id = $(this).siblings('input[name="product_id"]').val();
            category_id = $(this).siblings('input[name="category_id"]').val();
            sizes = $(this).siblings('input[name="sizes"]').val();
            selected_sizes = sizes.split(',');

            $(this).attr('data-id', product_id);
            $(this).find('option[value="' + category_id + '"]').prop('selected', true);

            updateSizes(this, category_id);

            for (var i = 0; i < selected_sizes.length; i++) {
                $(this).closest('tr').find(".quick-edit-size option[value='" + selected_sizes[i] + "']").attr('selected', 'selected');
            }
        });

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

        $(document).on('click', '.quick-edit-name', function () {
            var id = $(this).data('id');

            $(this).closest('td').find('.quick-name').addClass('hidden');
            $(this).closest('td').find('.quick-edit-name-input').removeClass('hidden');
            $(this).closest('td').find('.quick-edit-name-input').focus();

            $(this).closest('td').find('.quick-edit-name-input').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);

                if (key == 13) {
                    e.preventDefault();
                    var name = $(thiss).val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ url('products') }}/" + id + '/updateName',
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name,
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $(thiss).siblings('.quick-name').text(name);
                        $(thiss).siblings('.quick-name').removeClass('hidden');
                    }).fail(function (response) {
                        console.log(response);

                        alert('Could not update name');
                    });
                }
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

        $(document).on('click', '.quick-edit-size-button', function () {
            var size = $(this).siblings('.quick-edit-size').val();
            // var other_size = $(this).siblings('input[name="other_size"]').val();
            var lmeasurement = $(this).closest('td').find('input[name="lmeasurement"]').val();
            var hmeasurement = $(this).closest('td').find('input[name="hmeasurement"]').val();
            var dmeasurement = $(this).closest('td').find('input[name="dmeasurement"]').val();
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

        $(document).on('click', '.quick-edit-description', function (e) {
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

                        var short_description = description.substr(0, 100);

                        $(thiss).closest('.long-description-container').siblings('.short-description-container').text(short_description);
                    }).fail(function (response) {
                        console.log(response);

                        alert('Could not update description');
                    });
                }
            });
        });

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

        $(document).on('click', '.upload-magento', function () {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var thiss = $(this);
            var url = '';

            if (type == 'approve') {
                url = "{{ url('products') }}/" + id + '/approveProduct';
            } else if (type == 'list') {
                url = "{{ url('products') }}/" + id + '/listMagento';
            } else if (type == 'unlist') {
                url = "{{ url('products') }}/" + id + '/unlistMagento';
            } else if (type == 'enable') {
                url = "{{ url('products') }}/" + id + '/approveMagento';
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
                    $(thiss).text('Loading...');
                }
            }).done(function (response) {
                if (response.result != false && response.status == 'is_approved') {
                    $(thiss).closest('tr').remove();
                } else if (response.result != false && response.status == 'listed') {
                    $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                } else if (response.result != false && response.status == 'unlisted') {
                    $(thiss).text('Approve');
                    $(thiss).attr('data-type', 'approve');
                } else if (response.result != false && response.status == 'approved') {
                    $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                } else {
                    $(thiss).text('Update');
                    $(thiss).attr('data-type', 'update');
                }
            }).fail(function (response) {
                console.log(response);

                if (type == 'approve') {
                    $(thiss).text('Approve');
                } else if (type == 'list') {
                    $(thiss).text('List');
                } else if (type == 'unlist') {
                    $(thiss).text('Unlist');
                } else if (type == 'enable') {
                    $(thiss).text('Enable');
                } else {
                    $(thiss).text('Update');
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
                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
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

                var html = ' <p> ' + remark + ' <br> <small>By You updated on ' + moment().format('DD-M H:mm') + ' </small></p>';

                $("#makeRemarkModal").find('#remark-list').append(html);
            }).fail(function (response) {
                console.log(response);

                alert('Could not fetch remarks');
            });
        });
    </script>
@endsection
