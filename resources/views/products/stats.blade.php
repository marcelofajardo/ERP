@extends('layouts.app')


@section('favicon' , 'productstats.png')


@section('title', 'Product Stats')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Product Stats ({{$products->total()}})</h2>
        </div>
    </div>
    <form action="{{ action('ProductController@productStats') }}" method="get">
        <div class="row mb-5">
            <div class="col-md-2">
                <input value="{{$sku}}" type="text" name="sku" id="sku" placeholder="Sku" class="form-control">
            </div>
            <div class="col-md-2">
                <select name="status" id="status" class="form-control">
                    <option value="">Any</option>
                    <option {{$request->get('status')=='approved' ? 'selected' : ''}} value="approved">Approved</option>
                    <option {{$request->get('status')=='unapproved' ? 'selected' : ''}} value="unapproved">Unapproved</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="user_id" id="user_id">
                    <option value="">All Users...</option>
                    @foreach($users as $user)
                        <option {{ $request->get('user_id')==$user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" value="" name="range_start" hidden/>
                    <input type="text" value="" name="range_end" hidden/>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-image btn-default">
                    <img src="{{ asset('images/search.png') }}" alt="Search">
                </button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $products->appends($request->except('page'))->links() }}.
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Title</th>
                    <th>Cropped Approved</th>
                    <th>Crop Approved Date</th>
                    <th>Cropped Rejected</th>
                    <th>Cropped Rejected Date</th>
                    <th>Cropped Sequenced</th>
                    <th>Cropped Sequenced Date</th>
                    <th>Attribute Edit</th>
                    <th>Attribute Edit Approval Date</th>
                    <th>Attribute Edit Rejected</th>
                    <th>Attribute Edit Rejected Date</th>
                </tr>
                <?php
                    $totalCropApproved = 0;
                    $totalCropRejected = 0;
                    $totalCropSequenced = 0;
                    $totalListingApproved = 0;
                    $totalListingRejected = 0;
                ?>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="{{ action('ProductController@show', $product->id) }}">{{$product->name}}</a>
                        </td>
                        <td>
                            @if($product->is_crop_approved)
                                <?php ++$totalCropApproved; ?>
                                Yes by {{$product->cropApprover  ? $product->cropApprover->name : 'N/A'}}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $product->crop_approved_at ?? 'N/A' }}
                        </td>
                        <td>
                            @if($product->is_crop_rejected)
                                <?php ++$totalCropRejected; ?>
                                Yes by {{$product->cropRejector  ? $product->cropRejector->name : 'N/A'}}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $product->crop_rejected_at ?? 'N/A' }}
                        </td>
                        <td>
                            @if($product->is_crop_ordered)
                                <?php ++$totalCropSequenced; ?>
                                Yes by {{$product->cropOrderer  ? $product->cropOrderer->name : 'N/A'}}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $product->crop_ordered_at ?? 'N/A' }}
                        </td>
                        <td>
                            @if($product->is_approved)
                                <?php ++$totalListingApproved; ?>
                                Yes by {{$product->approver  ? $product->approver->name : 'N/A'}}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $product->listing_approved_at ?? 'N/A' }}
                        </td>
                        <td>
                            @if($product->is_listing_rejected)
                                <?php ++$totalListingRejected; ?>
                                Yes by {{$product->rejector  ? $product->rejector->name : 'N/A'}}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $product->listing_rejected_on ?? 'N/A' }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>Total</th>
                    <td colspan="2">{{ $totalCropApproved }}</td>
                    <td colspan="2">{{ $totalCropRejected }}</td>
                    <td colspan="2">{{ $totalCropSequenced }}</td>
                    <td colspan="2">{{ $totalListingApproved }}</td>
                    <td colspan="2">{{ $totalListingRejected }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $products->appends($request->except('page'))->links() }}.
        </div>
    </div>
@endsection


@section('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        let r_s = "{{ $request->get('range_start') }}";
        let r_e = "{{ $request->get('range_end') }}";

        let start = r_s ? moment(r_s,'YYYY-MM-DD') : moment().subtract(6, 'days');
        let end =   r_e ? moment(r_e,'YYYY-MM-DD') : moment();

        jQuery('input[name="range_start"]').val();
        jQuery('input[name="range_end"]').val();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            maxYear: 1,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {

            jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

        });
    </script>
@endsection