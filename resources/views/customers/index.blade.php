@extends('layouts.app')


@if($type == 'unread')
@section('favicon' , 'customerunread.png')
@section('title', 'Customer Unread')
@elseif($type == 'unapproved')
@section('favicon' , 'customerunapproved.png')
@section('title', 'Customer Unapproved')
@elseif($type == 'Refund to be processed')
@section('favicon' , 'customerrefund.png')
@section('title', 'Customer Refund')
@else
@section('favicon' , 'customer.png')
@section('title', 'Customer List')
@endif




@section('styles')
    <style>
        .results {
            background: #fff;
            border-radius: 2px;
            margin: 1rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            max-width: 250px !important;
            overflow: auto;
            /*height: 450px !important;*/
            max-height: 350px;
            display: none;
            position: absolute;
            z-index: 9;
        }

        .search .results li {
            display: block
        }

        .search .results li:first-child {
            margin-top: -1px
        }

        .search .results li:first-child:before, .search .results li:first-child:after {
            display: block;
            content: '';
            width: 0;
            height: 0;
            position: absolute;
            left: 50%;
            margin-left: -5px;
            border: 5px outset transparent;
        }

        .search .results li:first-child:before {
            border-bottom: 5px solid #c4c7d7;
            top: -11px;
        }

        .search .results li:first-child:after {
            border-bottom: 5px solid #fdfdfd;
            top: -10px;
        }

        .search .results li:first-child:hover:before, .search .results li:first-child:hover:after {
            display: none
        }

        .search .results li:last-child {
            margin-bottom: -1px
        }

        .search .results a {
            display: block;
            position: relative;
            margin: 0 -1px;
            padding: 6px 40px 6px 10px;
            color: #808394;
            font-weight: 500;
            text-shadow: 0 1px #fff;
            border: 1px solid transparent;
            border-radius: 3px;
        }

        .search .results a span {
            font-weight: 200
        }

        .search .results a:before {
            content: '';
            width: 18px;
            height: 18px;
            position: absolute;
            top: 50%;
            right: 10px;
            margin-top: -9px;
            background: url("https://cssdeck.com/uploads/media/items/7/7BNkBjd.png") 0 0 no-repeat;
        }

        .search .results a:hover {
            text-decoration: none;
            color: #fff;
            text-shadow: 0 -1px rgba(0, 0, 0, 0.3);
            border-color: #2380dd #2179d5 #1a60aa;
            background-color: #338cdf;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #59aaf4), color-stop(100%, #338cdf));
            background-image: -webkit-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -moz-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -ms-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -o-linear-gradient(top, #59aaf4, #338cdf);
            background-image: linear-gradient(top, #59aaf4, #338cdf);
            -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -moz-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -ms-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -o-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        }

        :-moz-placeholder {
            color: #a7aabc;
            font-weight: 200;
        }

        ::-webkit-input-placeholder {
            color: #a7aabc;
            font-weight: 200;
        }

        .lt-ie9 .search input {
            line-height: 26px
        }

        .numberSend {
            width: 160px;
            background-color: transparent;
            color: transparent;
            text-align: center;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            left: 10%;
            margin-left: -80px;
            display: none;
        }


    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('large_content')
    

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Customers List ({{ count(json_decode($customer_ids_list)) }})</h2>
            <div class="container">
                <form action="/customers" method="GET" class="form-inline">
                    <input name="term" type="text" class="form-control"
                           value="{{ isset($term) ? $term : '' }}"
                           placeholder="Search" id="customer-search">

                    <div class="form-group ml-3">
                        <select class="form-control" name="type">
                            <optgroup label="Type">
                                <option value="">Select</option>
                                <optgroup label="Messages">
                                    <option value="unread" {{ isset($type) && $type == 'unread' ? 'selected' : '' }}>Unread</option>
                                    <option value="unapproved" {{ isset($type) && $type == 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                                </optgroup>

                                <optgroup label="Leads">
                                    <option value="0" {{ isset($type) && $type == '0' ? 'selected' : '' }}>No lead</option>
                                    <option value="1" {{ isset($type) && $type == '1' ? 'selected' : '' }}>Cold</option>
                                    <option value="2" {{ isset($type) && $type == '2' ? 'selected' : '' }}>Cold / Important</option>
                                    <option value="3" {{ isset($type) && $type == '3' ? 'selected' : '' }}>Hot</option>
                                    <option value="4" {{ isset($type) && $type == '4' ? 'selected' : '' }}>Very Hot</option>
                                    <option value="5" {{ isset($type) && $type == '5' ? 'selected' : '' }}>Advance Follow Up</option>
                                    <option value="6" {{ isset($type) && $type == '6' ? 'selected' : '' }}>High Priority</option>
                                </optgroup>

                                <optgroup label="Old">
                                    <option value="new" {{ isset($type) && $type == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="delivery" {{ isset($type) && $type == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                    <option value="Refund to be processed" {{ isset($type) && $type == 'Refund to be processed' ? 'selected' : '' }}>Refund</option>
                                </optgroup>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group ml-3">
                        {{-- <strong>Date Range</strong> --}}
                        <input type="text" value="" name="range_start" hidden/>
                        <input type="text" value="" name="range_end" hidden/>
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="form-group ml-3">
                        <input placeholder="Shoe Size" type="text" name="shoe_size" value="{{request()->get('shoe_size')}}" class="form-control-sm form-control">
                    </div>
                    <div class="form-group ml-3">
                        <input placeholder="Clothing Size" type="text" name="clothing_size" value="{{request()->get('clothing_size')}}" class="form-control-sm form-control">
                    </div>
                    <div class="form-group ml-3">
                        <select class="form-control" name="shoe_size_group">
                            <option value="">Select</option>
                            <?php foreach ($shoe_size_group as $shoe_size => $customerCount) {
                                echo '<option value="' . $shoe_size . '" ' . ($shoe_size == request()->get('shoe_size_group') ? 'selected' : '') . '>(' . $shoe_size . ' Size) ' . $customerCount . ' Customers</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group ml-3">
                        <select class="form-control" name="clothing_size_group">
                            <option value="">Select</option>
                            <?php foreach ($clothing_size_group as $clothing_size => $customerCount) {
                                echo '<option value="' . $clothing_size . '" ' . ($shoe_size == request()->get('shoe_size_group') ? 'selected' : '') . '>(' . $clothing_size . ' Size) ' . $customerCount . ' Customers</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                </form>
            </div>

            <div class="col-md-2 search">
                <input placeholder="Search..." type="text" name="keyword" id="keyword" class="form-control-sm form-control">
                <ul class="results keyword-results">
                </ul>
            </div>

            <div class="pull-right mt-4">
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#importCustomersModal">Import Customers</button>
                    {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#sendAllModal">Send Message to All</button> --}}
                @endif
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#mergeModal">Merge Customers</button>
                <a class="btn btn-secondary" href="{{ route('customer.create') }}">+</a>
                <a class="btn btn-secondary create_broadcast" href="javascript:;">Create Broadcast</a>
            </div>
        </div>
    </div>

    @include('customers.partials.modal-merge')

    {{-- @include('customers.partials.modal-send-to-all') --}}

    @include('customers.partials.modal-import')

    @include('customers.partials.modal-shortcut')

    @include('customers.partials.modal-category-brand')

    @include('partials.flash_messages')

    <?php
    $query = http_build_query(Request::except('page'));
    $query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
    ?>

    <div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;">
        Goto :
        <select onchange="location.href = this.value;" class="form-control" id="page-goto">
            @for($i = 1 ; $i <= $customers->lastPage() ; $i++ )
                <option value="{{ $query.$i }}" {{ ($i == $customers->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="card activity-chart my-3">
        <canvas id="leadsChart" style="height: 100px;"></canvas>
    </div>
    <div class="card activity-chart mt-2 p-5">
        <div class="progress">
            @foreach($order_stats as $order_stat)
                <div data-toggle="title" title="{{$order_stat[0]}}" class="progress-bar" role="progressbar" style="width:{{$order_stat[2]}}%; background-color: {{$order_stat[3]}}">
                    <a href="?type={{$order_stat[0]}}">{{$order_stat[1]}}</a>
                </div>
            @endforeach
        </div>
        <div style="font-size: 12px;">
            @foreach($order_stats as $order_stat)
                <div style="border-left: 15px solid {{$order_stat[3]}}; display: inline-block;padding: 5px;" class="mt-1">
                    <a href="?type={{$order_stat[0]}}">{{$order_stat[0]}} ({{$order_stat[1]}})</a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="infinite-scroll">
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                <th width="20%"><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=name{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Name</a></th>
                {{-- @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
                  <th><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=email{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Email</a></th>
                  <th><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=phone{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Phone</a></th>
                  <th><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=instagram{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Instagram</a></th>
                @endif --}}
                {{-- <th width="10%"><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=rating{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Lead Rating</a></th> --}}
                {{-- <th width="10%">Lead/Order Status</th> --}}
                {{-- <th width="5%"><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=lead_created{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Lead Created at</a></th>
                <th width="5%"><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=order_created{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Order Created at</a></th> --}}
                <th width="15%">Instruction</th>
                <th width="15%">Message Status</th>
                <th>Order Status</th>
                <th>Purchase Status</th>
                <th width="20%"><a href="/customers{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=communication{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}">Communication</a></th>
                <th width="30%">Send Message</th>
                <th>Shortcuts</th>
                <th width="10%">Action</th>
                </thead>
                <tbody>
                @foreach ($customers as $key => $customer)
                    <tr class="
                {{ ((!empty($customer->message) && $customer->message_status == 0) || $customer->message_status == 1 || $customer->message_status == 5) ? 'row-highlight' : '' }}
                    {{--                {{ (!empty($customer->message) && $customer->message_status == 0) ? 'text-danger' : '' }}--}}
                    {{--                {{ ($customer->order_status && ($customer->order_status != 'Cancel' && $customer->order_status != 'Delivered')) ? 'text-success' : '' }}--}}
                    {{ $customer->order_status ? '' : 'text-primary' }}
                            ">
                        <td>
                            @php
                                if ($customer->lead_status == 1) {
                                  $customer_color = 'rgba(163,103,126,1)';
                                } else if ($customer->lead_status == 2) {
                                  $customer_color = 'rgba(63,203,226,1)';
                                } else if ($customer->lead_status == 3) {
                                  $customer_color = 'rgba(63,103,126,1)';
                                } else if ($customer->lead_status == 4) {
                                  $customer_color = 'rgba(94, 80, 226, 1)';
                                } else if ($customer->lead_status == 5) {
                                  $customer_color = 'rgba(58, 223, 140, 1)';
                                } else if ($customer->lead_status == 6) {
                                  $customer_color = 'rgba(187, 221, 49, 1)';
                                } else {
                                  $customer_color = 'rgba(207, 207, 211, 1)';
                                }
                            @endphp

                            <form class="d-inline" action="{{ route('customer.post.show', $customer->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_ids" value="{{ $customer_ids_list }}">

                                <button style="padding: 0" type="submit" class="btn-link">{{ $customer->name }}</button>
                            </form>

                            <br>

                            {{ $customer->phone }}
                            {{-- <a href="{{ route('customer.show', $customer->id) }}?customer_ids={{ $customer_ids_list }}">{{ $customer->name }}</a> --}}

                            <div>
                                <button type="button" class="btn btn-image call-select popup" data-context="customers" data-id="{{ $customer->id }}" data-phone="{{ $customer->phone }}"><img src="/images/call.png"/></button>

                                <div class="numberSend" id="show{{ $customer->id }}">
                                    <select class="form-control call-twilio" data-context="customers" data-id="{{ $customer->id }}" data-phone="{{ $customer->phone }}">
                                        <option disabled selected>Select Number</option>
                                        @foreach(\Config::get("twilio.caller_id") as $caller)
                                            <option value="{{ $caller }}">{{ $caller }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($customer->is_blocked == 1)
                                    <button type="button" class="btn btn-image block-twilio" data-id="{{ $customer->id }}"><img src="/images/blocked-twilio.png"/></button>
                                @else
                                    <button type="button" class="btn btn-image block-twilio" data-id="{{ $customer->id }}"><img src="/images/unblocked-twilio.png"/></button>
                                @endif


                                @if ($customer->is_flagged == 1)
                                    <button type="button" class="btn btn-image flag-customer" data-id="{{ $customer->id }}"><img src="/images/flagged.png"/></button>
                                @else
                                    <button type="button" class="btn btn-image flag-customer" data-id="{{ $customer->id }}"><img src="/images/unflagged.png"/></button>
                                @endif

                                @if ($customer->is_priority == 1)
                                    <button type="button" class="btn btn-image priority-customer" data-id="{{ $customer->id }}"><img src="/images/customer-priority.png"/></button>
                                @else
                                    <button type="button" class="btn btn-image priority-customer" data-id="{{ $customer->id }}"><img src="/images/customer-not-priority.png"/></button>
                                @endif

                                <button data-toggle="modal" data-target="#reminderModal" class="btn btn-image set-reminder" data-id="{{ $customer->id }}" data-frequency="{{ $customer->frequency }}" data-reminder_message="{{ $customer->reminder_message }}">
                                    <img src="{{ asset('images/alarm.png') }}" alt="" style="width: 18px;">
                                </button>

                                <button type="button" class="btn btn-image send-contact-modal-btn" data-id="{{ $customer->id }}"><img src="/images/details.png"/></button>

                            </div>

                            @php
                                $first_color = $customer_color == 'rgba(163,103,126,1)' ? 'active-bullet-status' : '';
                                $second_color = $customer_color == 'rgba(63,203,226,1)' ? 'active-bullet-status' : '';
                                $third_color = $customer_color == 'rgba(63,103,126,1)' ? 'active-bullet-status' : '';
                                $fourth_color = $customer_color == 'rgba(94, 80, 226, 1)' ? 'active-bullet-status' : '';
                                $fifth_color = $customer_color == 'rgba(58, 223, 140, 1)' ? 'active-bullet-status' : '';
                                $sixth_color = $customer_color == 'rgba(187, 221, 49, 1)' ? 'active-bullet-status' : '';
                                $seventh_color = $customer_color == 'rgba(207, 207, 211, 1)' ? 'active-bullet-status' : '';
                            @endphp

                            @if ($customer->lead_id != '')
                                <div class="">
                                    <span class="user-status {{ $seventh_color }}" style="background-color: rgba(207, 207, 211, 1);"></span>
                                    <span class="user-status change-lead-status {{ $first_color }}" data-toggle="tooltip" title="Cold Lead" data-id="1" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #CCCCCC;"></span>
                                    <span class="user-status change-lead-status {{ $second_color }}" data-toggle="tooltip" title="Cold / Important Lead" data-id="2" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #95a5a6;"></span>
                                    <span class="user-status change-lead-status {{ $third_color }}" data-toggle="tooltip" title="Hot Lead" data-id="3" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #b2b2b2;"></span>
                                    <span class="user-status change-lead-status {{ $fourth_color }}" data-toggle="tooltip" title="Very Hot Lead" data-id="4" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #999999;"></span>
                                    <span class="user-status change-lead-status {{ $fifth_color }}" data-toggle="tooltip" title="Advance Follow Up" data-id="5" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #2c3e50;"></span>
                                    <span class="user-status change-lead-status {{ $sixth_color }}" data-toggle="tooltip" title="High Priority" data-id="6" data-leadid="{{ $customer->lead_id }}" style="cursor:pointer; background-color: #7f7f7f;"></span>
                                </div>
                            @endif


                            @if (array_key_exists($customer->id, $orders))
                                @if(!empty($orders[$customer->id]))
                                    @foreach($orders[$customer->id] as $customerOrder)
                                        <div>
                                            <a target="_blank" href="/order/{{ $customerOrder['id'] }}"><b>#{{ $customerOrder['id'] }}</b></a>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$followUpForAdvance || $customerOrder['order_status'] == 'Follow up for advance') ? 'active-bullet-status' : '' }}" title="Follow up for advance" data-id="Follow up for advance" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #666666;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$advanceRecieved || $customerOrder['order_status'] == 'Advance received') ? 'active-bullet-status' : '' }}" title="Advance received" data-id="Advance received" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #4c4c4c;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$delivered || $customerOrder['order_status'] == 'Delivered') ? 'active-bullet-status' : '' }}" title="Delivered" data-id="Delivered" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #323232;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$cancel || $customerOrder['order_status'] == 'Cancel') ? 'active-bullet-status' : '' }}" title="Cancel" data-id="Cancel" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #191919;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$productShippedToClient || $customerOrder['order_status'] == 'Product shiped to Client')  ? 'active-bullet-status' : '' }}" title="Product shiped to Client" data-id="Product shiped to Client" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #414a4c;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$refundToBeProcessed || $customerOrder['order_status'] == 'Refund to be processed') ? 'active-bullet-status' : '' }}" title="Refund to be processed" data-id="Refund to be processed" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #CCCCCC;"></span>
                                            <span class="order-status change-order-status {{ ($customerOrder['order_status_id'] == \App\Helpers\OrderHelper::$refundCredited || $customerOrder['order_status'] == 'Refund Credited') ? 'active-bullet-status' : '' }}" title="Refund Credited" data-id="Refund Credited" data-orderid="{{ $customerOrder['id'] }}" style="cursor:pointer; background-color: #95a5a6;"></span>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                            <p>
                            <div class="form-group">
                                <select class="form-control change-whatsapp-no" data-customer-id="<?php echo $customer->id; ?>">
                                    <option value="">-No Selected-</option>
                                    @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
                                        @if($number != "0")
                                            <option {{ ($number == $customer->whatsapp_number && $customer->whatsapp_number != '') ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            </p>
                        </td>
                        {{-- @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
                          <td>{{ $customer['email'] }}</td>
                          <td>{{ $customer['phone'] }}</td>
                          <td>{{ $customer['instahandler'] }}</td>
                        @endif --}}
                        {{-- <td>
                            {{ $customer->rating ?? 'N/A' }}
                        </td> --}}
                        {{-- <td>
                            @if ($customer->lead_status)
                                @php $status = array_flip((new \App\Status)->all()); @endphp
                                {{ $status[$customer->lead_status] }}
                            @endif
                            {{ $customer->order_status ? ' / ' : '' }}
                            @if ($customer->order_status)
                                {{ $customer->order_status }}
                            @endif
                        </td> --}}
                        {{-- <td>
                            {{ $customer->lead_created }}
                        </td>
                        <td>
                            @if ($customer->order_status)
                                {{ $customer->order_created }}
                            @endif
                        </td> --}}
                        @php
                            $remark_last_time = '';
                            $remark_message = '';
                        @endphp

                        @if (array_key_exists($customer->id, $instructions))
                            @if (!empty($instructions[$customer->id][0]['remarks']))
                                @php
                                    $remark_last_time = $instructions[$customer->id][0]['remarks'][0]['created_at'];
                                    $remark_message = $instructions[$customer->id][0]['remarks'][0]['remark'];
                                @endphp
                            @endif
                            <td class="{{ $instructions[$customer->id][0]['completed_at'] ? 'text-secondary' : '' }}">
                                @if ($instructions[$customer->id][0]['assigned_to'])
                                    {{ array_key_exists($instructions[$customer->id][0]['assigned_to'], $users_array) ? $users_array[$instructions[$customer->id][0]['assigned_to']] : 'No User' }} -


                                    <div class="form-inline expand-row">
                                        @if ($instructions[$customer->id][0]['is_priority'] == 1)
                                            <strong class="text-danger mr-1">!</strong>
                                        @endif

                                        <div class="td-mini-container">
                                            {{ strlen($instructions[$customer->id][0]['instruction']) > 10 ? substr($instructions[$customer->id][0]['instruction'], 0, 10).'...' : $instructions[$customer->id][0]['instruction'] }}
                                        </div>
                                        <div class="td-full-container hidden">
                                            {{ $instructions[$customer->id][0]['instruction'] }}
                                        </div>

                                    </div>
                                    <br>
                                    @if ($instructions[$customer->id][0]['completed_at'])
                                        <span style="color: #5e5e5e">{{ Carbon\Carbon::parse($instructions[$customer->id][0]['completed_at'])->format('d-m H:i') }}</span>
                                        @if ($instructions[$customer->id][0]['verified'] == 0)
                                            <button data-instructionId="{{ $instructions[$customer->id][0]['id'] }}" id="instruction_{{ $instructions[$customer->id][0]['id'] }}" class="btn btn-image btn-xs verify-instruction" data-toggle="tooltip" title="Verify Instruction">
                                                <img src="{{ asset('images/3.png') }}" alt="Verify">
                                            </button>
                                        @endif
                                    @else
                                        <a href="#" class="btn btn-image complete-call" data-id="{{ $instructions[$customer->id][0]['id'] }}" data-toggle="tooltip" title="Complete Instruction">
                                            <img src="{{ asset('images/1.png') }}" alt="Complete">
                                        </a>
                                    @endif

                                    @if ($instructions[$customer->id][0]['completed_at'])
                                        {{--                                <strong style="color: #5e5e5e">Completed</strong>--}}
                                    @else
                                        {{--                            @if ($instructions[$customer->id][0]['pending'] == 0)--}}
                                        {{--                              <a href="#" class="btn-link pending-call" data-id="{{ $instructions[$customer->id][0]['id'] }}">Mark as Pending</a>--}}
                                        {{--                            @else--}}
                                        {{--                              Pending--}}
                                        {{--                            @endif--}}
                                    @endif
                                @endif

                                <textarea name="instruction" class="form-control quick-add-instruction-textarea hidden" rows="8" cols="80"></textarea>
                                <input title="Priority" class="hidden quick-priority-check" type="checkbox" name="instruction_priority" data-id="{{ $customer->id }}" id="instruction_priority_{{$customer->id}}">
                                <button type="button" class="btn-image btn quick-add-instruction" data-id="{{ $customer->id }}"><img
                                            src="{{ asset('images/add.png') }}" style="width: 14px !important;" alt="Add Instruction"></button>
                                <span style="color: #333; font-size: 12px;">
                             {{ print_r($instructions[$customer->id][0]['remarks'] ? $instructions[$customer->id][0]['remarks'][array_key_first($instructions[$customer->id][0]['remarks'])]['remark'] : 'No remark') }}
                        </span>
                                <br/>
                                <a href="{{ route('attachImages', ['customer', $customer->id, 1]) }}" class="btn btn-image px-1"><img src="/images/attach.png"/></a>
                            </td>
                        @else
                            <td>
                                <textarea name="instruction" class="form-control quick-add-instruction-textarea hidden" rows="8" cols="80"></textarea>
                                <input title="Priority" class="hidden quick-priority-check" type="checkbox" name="instruction_priority" data-id="{{ $customer->id }}" id="instruction_priority_{{$customer->id}}">
                                <button type="button" class="btn-image btn quick-add-instruction" data-id="{{ $customer->id }}"><img
                                            src="{{ asset('images/add.png') }}" style="width: 14px !important;" alt="Add Instruction"></button>
                            </td>
                        @endif
                        <td>
                            {{-- @if (!empty($customer->message)) --}}
                            @if ($customer->message_status == 5)
                                Read
                            @elseif ($customer->message_status == 6)
                                Replied
                            @elseif ($customer->message_status == 1)
                                <span>Waiting for Approval</span>
                                <button type="button" class="btn btn-xs btn-secondary approve-message" data-id="{{ $customer->message_id }}" data-type="{{ $customer->message_type }}">Approve</button>
                            @elseif ($customer->message_status == 2)
                                Approved
                            @elseif ($customer->message_status == 0)
                                Unread

                                <a href data-url="/whatsapp/updatestatus?status=5&id={{ $customer->message_id }}" class='change_message_status'>Mark as Read</a>
                            @endif
                            {{-- @endif --}}
                        </td>
                        <td>
                            @if (array_key_exists($customer->id, $orders))
                                @if (count($orders[$customer->id]) == 1)
                                    <div class="form-group">
                                        <strong>status:</strong>
                                        <select name="status" class="form-control change_status order_status" data-orderid="{{ $orders[$customer->id][0]['id'] }}">
                                            @php $order_status = (new \App\ReadOnly\OrderStatus)->all(); @endphp
                                            @foreach($order_status as $key => $value)
                                                <option value="{{$value}}" {{$value == $orders[$customer->id][0]['order_status'] ? 'selected' : '' }}>{{ $key }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>
                                    </div>
                                @else
                                    <strong>status:</strong>
                                    <select name="status" class="form-control change_status order_status" data-orderid="{{ $orders[$customer->id][0]['id'] }}">
                                        @php $order_status = (new \App\ReadOnly\OrderStatus)->all(); @endphp
                                        @foreach($order_status as $key => $value)
                                            <option value="{{$value}}" {{$value == $orders[$customer->id][0]['order_status'] ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                    @foreach($orders[$customer->id] as $order)
                                        <a href="{{route('purchase.grid')}}?order_id={{$order['id']}}" style="{{in_array($order['order_status'], ['Cancel', 'Refund to be processed', 'Delivered']) ? 'background-color: #808080;' : ''}}"><img style="display: inline; width: 15px;" src="{{ asset('images/customer-order.png') }}" alt=""></a>
                                    @endforeach
                                @endif
                            @else
                                No Orders
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($customer->id, $orders))
                                @if ($customer->purchase_status != null)
                                    <a target="_new" href="{{ route('purchase.grid') }}">{{ $customer->purchase_status }}</a>
                                    @php
                                        $orderProduct = App\Order::where('customer_id', $customer->id)->with('order_product.product')->get();
                                    @endphp
                                    @foreach($orderProduct as $orderStat)
                                        @if($orderStat['product'])
                                            <li>
                                                <a target="_new" href="{{ action('ProductController@show', $orderStat['product']['id'])  }}">{{ $orderStat['product']['id'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    No Purchase
                                @endif
                            @endif
                        </td>
                        <td>
                            {{-- @if ($remark_message == '' || $remark_last_time < $customer->last_communicated_at) --}}
                            @if ($customer->message != '')
                                @if (strpos($customer->message, '<br>') !== false)
                                    {{ substr($customer->message, 0, strpos($customer->message, '<br>')) }}
                                @else
                                    {{ strlen($customer->message) > 100 ? substr($customer->message, 0, 97) . '...' : $customer->message }}
                                @endif
                            @else
                                @php $image_message = \App\ChatMessage::find($customer->message_id); @endphp

                                @if ($image_message && $image_message->hasMedia(config('constants.media_tags')))
                                    <div class="image-container hidden">
                                        @foreach ($image_message->getMedia(config('constants.media_tags')) as $image)
                                            <div class="d-inline-block">
                                                <img src="{{ $image->getUrl() }}" class="img-responsive thumbnail-200" alt="">
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-xs btn-secondary show-images-button">Show Images</button>
                                @endif
                            @endif

                            @if ($customer->is_error_flagged == 1)
                                <span class="btn btn-image"><img src="/images/flagged.png"/></span>
                            @endif

                            <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='customer' data-id="{{ $customer->id }}" title="Load messages"><img src="/images/chat.png" alt=""></button>

                            <ul class="more-communication-container">
                            </ul>

                            <label class="form-control-label">Select Group</label>
                            <select class="form-control multiselect-2" name="group" id="group{{ $customer->id }}" multiple>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }}@endif</option>
                                @endforeach
                            </select>
                            <button style="display: inline;width: 20%" class="btn btn-sm btn-image send-group " data-customerid="{{ $customer->id }}"><img src="/images/filled-sent.png"></button>

                            @if(isset($complaints[$customer->id]))
                                <p style="cursor: pointer;" class="show-complaint" data-complaint="{{ $complaints[$customer->id] }}">
                                    <strong>Complaint: </strong> {{ substr($complaints[$customer->id], 0, 10 ) }}
                                </p>
                            @endif

                        </td>
                        <td>
                            <div class="d-inline form-inline">
                                <input style="width: 75%" type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="">
                                <button style="display: inline;width: 20%" class="btn btn-sm btn-image send-message" data-customerid="{{ $customer->id }}"><img src="/images/filled-sent.png"/></button>
                            </div>

                            <p class="pb-4 mt-3" style="display: block;">
                            <div class="d-inline form-inline">
                                <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                                <button class="btn btn-secondary quick_category_add">+</button>
                            </div>
                            <div>
                                <div style="float: left; width: 86%">
                                    <select name="quickCategory" class="form-control mb-3 quickCategory">
                                        <option value="">Select Category</option>
                                        @foreach($reply_categories as $category)
                                            <option value="{{ $category->approval_leads }}" data-id="{{$category->id}}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="float: right;">
                                    <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
                                </div>
                            </div>
                            <div class="d-inline form-inline">
                                <input style="width: 87%" type="text" name="quick_comment" placeholder="Enter New Quick Comment" class="form-control mb-3 quick_comment">
                                <button class="btn btn-secondary quick_comment_add">+</button>
                            </div>
                            <div>
                                <div style="float: left; width: 86%">
                                    <select name="quickComment" class="form-control quickComment">
                                        <option value="">Quick Reply</option>
                                    </select>
                                </div>
                                <div style="float: right;">
                                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                                </div>
                            </div>
                            </p>
                            <div>
                                <p>
                                    <?php
                                    if (!empty($broadcasts)) {
                                        foreach ($broadcasts as $broadcast) {
                                            echo "<a href='javascript:;' class='fetch-broad-cast-spn' data-id='" . $broadcast . "' data-customer-id='" . $customer->id . "'>#" . $broadcast . "</a> ";
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </td>
                        <td>
                            {{-- <button type="button" class="btn btn-image" data-id="{{ $customer->id }}" data-instruction="Send images"><img src="/images/attach.png" /></button> --}}
                            {{-- <button type="button" class="btn btn-image" data-id="{{ $customer->id }}" data-instruction="Send price">$</button> --}}
                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Send images">
                                <input type="hidden" name="category_id" value="6">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('image_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Images"><img src="/images/attach.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Send price">
                                <input type="hidden" name="category_id" value="3">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('price_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Price"><img src="/images/price.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="{{ @$users_array[\App\Setting::get('call_shortcut')] }} call this client">
                                <input type="hidden" name="category_id" value="10">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('call_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Call this Client"><img src="/images/call.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Attach image">
                                <input type="hidden" name="category_id" value="8">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('screenshot_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Images"><img src="/images/upload.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Attach screenshot">
                                <input type="hidden" name="category_id" value="12">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('screenshot_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Screenshot"><img src="/images/screenshot.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Give details">
                                <input type="hidden" name="category_id" value="14">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('details_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Give Details"><img src="/images/details.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Check for the Purchase">
                                <input type="hidden" name="category_id" value="7">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('purchase_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Check for the Purchase"><img src="/images/purchase.png"/></button>
                            </form>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Please Show Client Chat">
                                <input type="hidden" name="category_id" value="13">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('purchase_shortcut') }}">

                                <button type="submit" class="btn btn-image quick-shortcut-button" title="Show Client Chat"><img src="/images/chat.png"/></button>
                            </form>
                            <div class="d-inline">
                                <button type="button" class="btn btn-image btn-broadcast-send" data-id="{{ $customer->id }}">
                                    <img src="/images/broadcast-icon.png"/>
                                </button>
                            </div>

                            <div class="d-inline">
                                <button type="button" class="btn btn-image send-instock-shortcut" data-id="{{ $customer->id }}">Send In Stock</button>
                            </div>

                            <div class="d-inline">
                                <button type="button" class="btn btn-image latest-scraped-shortcut" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#categoryBrandModal">Send 20 Scraped</button>
                            </div>

                            <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="instruction" value="Please show client chat to Yogesh">
                                <input type="hidden" name="category_id" value="13">
                                <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('price_shortcut') }}">

                            </form>
                        </td>
                        <td>
                            <form class="d-inline" action="{{ route('customer.post.show', $customer->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_ids" value="{{ $customer_ids_list }}">

                                <button type="submit" class="btn btn-image" href=""><img src="/images/view.png"/></button>
                            </form>

                            <a class="btn btn-image" href="{{ route('customer.edit',$customer->id) }}" target="_blank"><img src="/images/edit.png"/></a>
                            <button data-toggle="modal" data-target="#zoomModal" class="btn btn-image set-meetings" data-id="{{ $customer->id }}" data-type="customer"><i class="fa fa-video-camera" aria-hidden="true"></i></button>
                            {!! Form::open(['method' => 'DELETE','route' => ['customer.destroy', $customer->id],'style'=>'display:inline']) !!}
                            <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                            {!! Form::close() !!}
                            <input type="checkbox" name="customer_message[]" class="d-inline customer_message" value="{{$customer->id}}">
                            <button type="button" class="btn send-email-common-btn" data-toemail="{{$customer->email}}" data-object="customer" data-id="{{$customer->id}}"><i class="fa fa-envelope-square"></i></button>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('attachImages', ['customers']) }}" id="attachImagesForm" method="GET">
            <input type="hidden" name="message" id="attach_message" value="">
            <input type="hidden" name="sending_time" id="attach_sending_time" value="">
        </form>

        {!! $customers->appends(Request::except('page'))->links() !!}
    </div>

    <div id="reminderModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Set/Edit Reminder</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="frequency">Frequency (in Minutes)</label>
                        <select class="form-control" name="frequency" id="frequency">
                            <option value="0">Disabled</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="35">35</option>
                            <option value="40">40</option>
                            <option value="45">45</option>
                            <option value="50">50</option>
                            <option value="55">55</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reminder_message">Reminder Message</label>
                        <textarea name="reminder_message" id="reminder_message" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary save-reminder">Save</button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    @include('customers.zoomMeeting')
    <div id="broadcast-list" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Broadcast Pending List</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default broadcast-list-create-lead">Create Lead</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="broadcast-list-approval" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Broadcast Pending List</h4>
                </div>
                <div class="modal-body">
                    do you want to create a lead and send price ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default broadcast-list-approval-btn">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="hidden" id="chat_obj_type" name="chat_obj_type">
                    <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                    <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="sendContacts" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel1">Select User for send contact data:</label>
                        <form method="post" id="send-contact-to-user">
                            {{ Form::open(array('url' => '', 'id' => 'send-contact-user-form')) }}
                            {!! Form::hidden('customer_id',0,['id' => 'customer_id_attr']) !!}
                            {!! Form::select('user_id', \App\User::all()->sortBy("name")->pluck("name","id"), 6, ['class' => 'form-control select-user-wha-list select2', 'style'=> 'width:100%']) !!}
                            {{ Form::close() }}
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default send-contact-user-btn"><img style="width: 17px;" src="/images/filled-sent.png"></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="create_broadcast" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Message to Customers</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="send_message" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <strong> Selected Product :</strong>
                            <select name="selected_product[]" class="ddl-select-product form-control" multiple="multiple"></select>

                            <strong>Schedule Date:</strong>
                            <div class='input-group date' id='schedule-datetime'>
                                <input type='text' class="form-control" name="sending_time" id="sending_time_field" value="{{ date('Y-m-d H:i') }}" required/>

                                <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <strong>Message</strong>
                            <textarea name="message" id="message_to_all_field" rows="8" cols="80" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Send Message</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
@include('common.commonEmailModal')
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>
    <script src="{{asset('js/zoom-meetings.js')}}"></script>
    <script src="{{asset('js/common-email-send.js')}}">//js for common mail</script> 
    <script type="text/javascript">
        $(document).on('click', '.quick_category_add', function () {
            var textBox = $(this).closest("div").find(".quick_category");

            if (textBox.val() == "") {
                alert("Please Enter Category!!");
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('add.reply.category') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'name': textBox.val()
                }
            }).done(function (response) {
                textBox.val('');
                $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
            })
        });

        $(document).on('click', '.delete_category', function () {
            var quickCategory = $(this).closest("td").find(".quickCategory");

            if (quickCategory.val() == "") {
                alert("Please Select Category!!");
                return false;
            }

            var quickCategoryId = quickCategory.children("option:selected").data('id');
            if (!confirm("Are sure you want to delete category?")) {
                return false;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('destroy.reply.category') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': quickCategoryId
                }
            }).done(function (response) {
                location.reload();
            })
        });

        $(document).on('click', '.delete_quick_comment', function () {
            var quickComment = $(this).closest("td").find(".quickComment");

            if (quickComment.val() == "") {
                alert("Please Select Quick Comment!!");
                return false;
            }

            var quickCommentId = quickComment.children("option:selected").data('id');
            if (!confirm("Are sure you want to delete comment?")) {
                return false;
            }
            $.ajax({
                type: "DELETE",
                url: "/reply/" + quickCommentId,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
            }).done(function (response) {
                location.reload();
            })
        });

        $(document).on('click', '.quick_comment_add', function () {
            var textBox = $(this).closest("div").find(".quick_comment");
            var quickCategory = $(this).closest("td").find(".quickCategory");

            if (textBox.val() == "") {
                alert("Please Enter New Quick Comment!!");
                return false;
            }

            if (quickCategory.val() == "") {
                alert("Please Select Category!!");
                return false;
            }

            var quickCategoryId = quickCategory.children("option:selected").data('id');

            var formData = new FormData();

            formData.append("_token", "{{ csrf_token() }}");
            formData.append("reply", textBox.val());
            formData.append("category_id", quickCategoryId);
            formData.append("model", 'Approval Lead');

            $.ajax({
                type: 'POST',
                url: "{{ route('reply.store') }}",
                data: formData,
                processData: false,
                contentType: false
            }).done(function (reply) {
                textBox.val('');
                $('.quickComment').append($('<option>', {
                    value: reply,
                    text: reply
                }));
            })
        });

        $(document).on('click', '.create_broadcast', function () {
            var customers = [];
            $(".customer_message").each(function () {
                if ($(this).prop("checked") == true) {
                    customers.push($(this).val());
                }
            });
            if (customers.length == 0) {
                alert('Please select costomer');
                return false;
            }
            $("#create_broadcast").modal("show");
        });

        $("#send_message").submit(function (e) {
            e.preventDefault();
            var customers = [];
            $(".customer_message").each(function () {
                if ($(this).prop("checked") == true) {
                    customers.push($(this).val());
                }
            });
            if (customers.length == 0) {
                alert('Please select costomer');
                return false;
            }

            if ($("#send_message").find("#message_to_all_field").val() == "") {
                alert('Please type message ');
                return false;
            }

            if ($("#send_message").find(".ddl-select-product").val() == "") {
                alert('Please select product');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('erp-leads-send-message') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    products: $("#send_message").find(".ddl-select-product").val(),
                    sending_time: $("#send_message").find("#sending_time_field").val(),
                    message: $("#send_message").find("#message_to_all_field").val(),
                    customers: customers
                }
            }).done(function () {
                window.location.reload();
            }).fail(function (response) {
                $(thiss).text('No');

                alert('Could not say No!');
                console.log(response);
            });
        });
        jQuery('.ddl-select-product').select2({
            ajax: {
                url: '/productSearch/',
                dataType: 'json',
                delay: 750,
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Product by id, Name, Sku',
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 5,
            width: '100%',
            templateResult: formatProduct,
            templateSelection: function (product) {
                return product.text || product.name;
            },

        });

        function formatProduct(product) {
            if (product.loading) {
                return product.sku;
            }

            if (product.sku) {
                return "<p> <b>Id:</b> " + product.id + (product.name ? " <b>Name:</b> " + product.name : "") + " <b>Sku:</b> " + product.sku + " </p>";
            }

        }

        var searchSuggestions = {!! json_encode($search_suggestions, true) !!};

        var cached_suggestions = localStorage['message_suggestions'];
        var suggestions = [];

        $(document).on('click', '.show-complaint', function () {
            let data = $(this).attr('data-complaint')
            alert(data);
        });

        var customerIdToRemind = null;

        $(document).on('click', '.set-reminder', function () {
            let customerId = $(this).data('id');
            let frequency = $(this).data('frequency');
            let message = $(this).data('reminder_message');

            $('#frequency').val(frequency);
            $('#reminder_message').val(message);
            customerIdToRemind = customerId;

        });

        $(document).on('click', '.save-reminder', function () {
            let frequency = $('#frequency').val();
            let message = $('#reminder_message').val();

            $.ajax({
                url: "{{action('CustomerController@updateReminder')}}",
                type: 'POST',
                success: function () {
                    toastr['success']('Reminder updated successfully!');
                },
                data: {
                    customer_id: customerIdToRemind,
                    frequency: frequency,
                    message: message,
                    _token: "{{ csrf_token() }}"
                }
            });
        });

        $(window).scroll(function () {
            // var top = $(window).scrollTop();
            // var document_height = $(document).height();
            // var window_height = $(window).height();
            //
            // if (top >= (document_height - window_height - 200)) {
            //   if (can_load_more) {
            //     var current_page = $('#load-more-messages').data('nextpage');
            //     $('#load-more-messages').data('nextpage', current_page + 1);
            //     var next_page = $('#load-more-messages').data('nextpage');
            //     console.log(next_page);
            //     $('#load-more-messages').text('Loading...');
            //
            //     can_load_more = false;
            //
            //     pollMessages(next_page, true);
            //   }
            // }
            var next_page = $('.pagination li.active + li a');
            var page_number = next_page.attr('href').split('?page=');
            console.log(page_number);
            var current_page = page_number[1] - 1;

            $('#page-goto option[value="' + page_number[0] + '?page=' + current_page + '"]').attr('selected', 'selected');
        });

        $(document).on('click', '.verify-instruction', function () {
            let instructionId = $(this).attr('data-instructionId');
            let self = this;
            $.ajax({
                url: '{{ action('InstructionController@verify') }}',
                type: 'post',
                data: {
                    id: instructionId
                },
                success: function () {
                    toastr['success']('Instruction verified successfully', 'success');
                    $(self).html('Verified');
                }
            });
        });

        $(document).on('click', '.change-order-status', function () {
            let orderId = $(this).attr('data-orderid');
            let status = $(this).attr('title');
            let url = '/order/' + orderId + '/changestatus';

            let thiss = $(this);

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function () {
                    toastr['success']('Status changed successfully!', 'Success');
                    $(thiss).siblings('.change-order-status').removeClass('active-bullet-status');
                    $(thiss).addClass('active-bullet-status');
                    if (status == 'Product shiped to Client') {
                        $('#tracking-wrapper-' + id).css({'display': 'block'});
                    }
                }
            });
        });

        $(document).ready(function () {

            $('[data-toggle="tooltip"]').tooltip();

            $(document).on('click', '.expand-row', function () {
                var selection = window.getSelection();
                if (selection.toString().length === 0) {
                    // if ($(this).data('switch') == 0) {
                    //   $(this).text($(this).data('details'));
                    //   $(this).data('switch', 1);
                    // } else {
                    //   $(this).text($(this).data('subject'));
                    //   $(this).data('switch', 0);
                    // }
                    $(this).find('.td-mini-container').toggleClass('hidden');
                    $(this).find('.td-full-container').toggleClass('hidden');
                }
            });

            $('ul.pagination').hide();
            $(function () {
                $('.infinite-scroll').jscroll({
                    autoTrigger: true,
                    loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                    padding: 2500,
                    nextSelector: '.pagination li.active + li a',
                    contentSelector: 'div.infinite-scroll',
                    callback: function () {
                       $('.multiselect-2').multiselect({
                        enableFiltering: true,
                        filterBehavior: 'value'
                        });
                    }
                });
            });
        });


        $(document).ready(function () {
            $('#customer-search').autocomplete({
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(searchSuggestions, request.term);

                    response(results.slice(0, 10));
                }
            });

            $('.quick-message-field').autocomplete({
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(JSON.parse(cached_suggestions), request.term);

                    response(results.slice(0, 10));
                }
            });
        });

        $('.load-customers').on('click', function () {
            var thiss = $(this);
            var first_customer = $('#first_customer').val();
            var second_customer = $('#second_customer').val();

            if (first_customer == second_customer) {
                alert('You selected the same customers');

                return;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('customer.load') }}",
                data: {
                    first_customer: first_customer,
                    second_customer: second_customer
                },
                beforeSend: function () {
                    $(thiss).text('Loading...');
                }
            }).done(function (response) {
                $('#first_customer_id').val(response.first_customer.id);
                $('#second_customer_id').val(response.second_customer.id);

                $('#first_customer_name').val(response.first_customer.name);
                $('#first_customer_email').val(response.first_customer.email);
                $('#first_customer_phone').val(response.first_customer.phone ? (response.first_customer.phone).replace(/[\s+]/g, '') : '');
                $('#first_customer_instahandler').val(response.first_customer.instahandler);
                $('#first_customer_rating').val(response.first_customer.rating);
                $('#first_customer_address').val(response.first_customer.address);
                $('#first_customer_city').val(response.first_customer.city);
                $('#first_customer_country').val(response.first_customer.country);
                $('#first_customer_pincode').val(response.first_customer.pincode);

                $('#second_customer_name').val(response.second_customer.name);
                $('#second_customer_email').val(response.second_customer.email);
                $('#second_customer_phone').val(response.second_customer.phone ? (response.second_customer.phone).replace(/[\s+]/g, '') : '');
                $('#second_customer_instahandler').val(response.second_customer.instahandler);
                $('#second_customer_rating').val(response.second_customer.rating);
                $('#second_customer_address').val(response.second_customer.address);
                $('#second_customer_city').val(response.second_customer.city);
                $('#second_customer_country').val(response.second_customer.country);
                $('#second_customer_pincode').val(response.second_customer.pincode);

                $('#customers-data').show();
                $('#mergeButton').prop('disabled', false);

                $(thiss).text('Load Data');
            }).fail(function (response) {
                console.log(response);
                alert('There was error loading customers data');
            });
        });

        $(document).on('click', '.attach-images-btn', function (e) {
            e.preventDefault();

            $('#attach_message').val($('#message_to_all_field').val());
            $('#attach_sending_time').val($('#sending_time_field').val());

            $('#attachImagesForm').submit();
        });

        $('#schedule-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $(document).on('click', '.approve-message', function () {
            var thiss = $(this);
            var id = $(this).data('id');
            var type = $(this).data('type');

            if (!$(thiss).is(':disabled')) {
                $.ajax({
                    type: "POST",
                    url: "/whatsapp/approve/customer",
                    data: {
                        _token: "{{ csrf_token() }}",
                        messageId: id
                    },
                    beforeSend: function () {
                        $(thiss).attr('disabled', true);
                        $(thiss).text('Approving...');
                    }
                }).done(function (data) {
                    $(thiss).parent().html('Approved');
                }).fail(function (response) {
                    $(thiss).attr('disabled', false);
                    $(thiss).text('Approve');

                    console.log(response);
                    alert(response.responseJSON.message);
                });
            }
        });

        $(document).on('click', '.create-shortcut', function () {
            var id = $(this).data('id');
            var instruction = $(this).data('instruction');

            $('#customer_id_field').val(id);
            $('#instruction_field').val(instruction);
        });

        $(document).on('click', '.complete-call', function (e) {
            e.preventDefault();
            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var url = "{{ route('instruction.complete') }}";
            var id = $(this).data('id');

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    id: id
                },
                beforeSend: function () {
                    $(thiss).text('Loading');
                }
            }).done(function (response) {
                $(thiss).parent().append(moment(response.time).format('DD-MM HH:mm'));
                $(thiss).remove();
            }).fail(function (errObj) {
                console.log(errObj);
                alert("Could not mark as completed");
            });
        });

        $(document).on('click', '.pending-call', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var url = "{{ route('instruction.pending') }}";
            var id = $(this).data('id');

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    id: id
                },
                beforeSend: function () {
                    $(thiss).text('Loading');
                }
            }).done(function (response) {
                $(thiss).parent().append('Pending');
                $(thiss).remove();
            }).fail(function (errObj) {
                console.log(errObj);
                alert("Could not mark as completed");
            });
        });

        $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var customer_id = $(this).data('customerid');
            var message = $(this).siblings('input').val();

            data.append("customer_id", customer_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/customer',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function (response) {
                        $(thiss).siblings('input').val('');

                        if (cached_suggestions) {
                            suggestions = JSON.parse(cached_suggestions);

                            if (suggestions.length == 10) {
                                suggestions.push(message);
                                suggestions.splice(0, 1);
                            } else {
                                suggestions.push(message);
                            }
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('EXISTING');
                            console.log(suggestions);
                        } else {
                            suggestions.push(message);
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('NOT');
                            console.log(suggestions);
                        }

                        // $.post( "/whatsapp/approve/customer", { messageId: response.message.id })
                        //   .done(function( data ) {
                        //
                        //   }).fail(function(response) {
                        //     console.log(response);
                        //     alert(response.responseJSON.message);
                        //   });

                        $(thiss).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });

        $(document).on('change', '.quickCategory', function () {
            if ($(this).val() != "") {
                var replies = JSON.parse($(this).val());
                var thiss = $(this);
                $(this).closest("td").find('.quickComment').empty();
                $(this).closest("td").find('.quickComment').append($('<option>', {
                    value: '',
                    text: 'Quick Reply'
                }));

                replies.forEach(function (reply) {
                    $(thiss).closest("td").find('.quickComment').append($('<option>', {
                        value: reply.reply,
                        text: reply.reply,
                        'data-id': reply.id
                    }));
                });
            }
        });

        $(document).on('change', '.quickComment', function () {
            $(this).closest('td').find('.quick-message-field').val($(this).val());
        });
        $('.change_status').on('change', function () {
            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var status = $(this).val();

            if ($(this).hasClass('order_status')) {
                var id = $(this).data('orderid');
                var url = '/order/' + id + '/changestatus';
            } else {
                var id = $(this).data('leadid');
                var url = '/leads/' + id + '/changestatus';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    status: status
                }
            }).done(function (response) {
                if ($(thiss).hasClass('order_status') && status == 'Product shiped to Client') {
                    $('#tracking-wrapper-' + id).css({'display': 'block'});
                }

                $(thiss).siblings('.change_status_message').fadeIn(400);

                setTimeout(function () {
                    $(thiss).siblings('.change_status_message').fadeOut(400);
                }, 2000);
            }).fail(function (errObj) {
                alert("Could not change status");
            });
        });

        $(document).on('click', '.block-twilio', function () {
            var customer_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('customer.block') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customer_id
                },
                beforeSend: function () {
                    $(thiss).text('Blocking...');
                }
            }).done(function (response) {
                if (response.is_blocked == 1) {
                    $(thiss).html('<img src="/images/blocked-twilio.png" />');
                } else {
                    $(thiss).html('<img src="/images/unblocked-twilio.png" />');
                }
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unblocked-twilio.png" />');

                alert('Could not block customer!');

                console.log(response);
            });
        });

        $(document).on('click', '.flag-customer', function () {
            var customer_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('customer.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customer_id
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
                if (response.is_flagged == 1) {
                    // var badge = $('<span class="badge badge-secondary">Flagged</span>');
                    //
                    // $(thiss).parent().append(badge);
                    $(thiss).html('<img src="/images/flagged.png" />');
                } else {
                    $(thiss).html('<img src="/images/unflagged.png" />');
                    // $(thiss).parent().find('.badge').remove();
                }

                // $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag customer!');

                console.log(response);
            });
        });

        $(document).on('click', '.priority-customer', function () {
            var customer_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('customer.priority') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customer_id
                },
                beforeSend: function () {
                    $(thiss).text('Prioritizing...');
                }
            }).done(function (response) {
                if (response.is_priority == 1) {
                    $(thiss).html('<img src="/images/customer-priority.png" />');
                } else {
                    $(thiss).html('<img src="/images/customer-not-priority.png" />');
                }

            }).fail(function (response) {
                $(thiss).html('<img src="/images/customer-not-priority.png" />');

                alert('Could not prioritize customer!');

                console.log(response);
            });
        });

        $(document).on('click', '.send-instock-shortcut', function () {
            var customer_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('customer.send.instock') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customer_id
                },
                beforeSend: function () {
                    $(thiss).text('Sending...');
                }
            }).done(function (response) {
                $(thiss).text('Send In Stock');
            }).fail(function (response) {
                $(thiss).text('Send In Stock');

                alert('Could not sent instock!');

                console.log(response);
            });
        });

        $(document).on('click', '.quick-shortcut-button', function (e) {
            e.preventDefault();

            var customer_id = $(this).closest('form').find('input[name="customer_id"]').val();
            var instruction = $(this).closest('form').find('input[name="instruction"]').val();
            var category_id = $(this).closest('form').find('input[name="category_id"]').val();
            var assigned_to = $(this).closest('form').find('input[name="assigned_to"]').val();

            $.ajax({
                type: "POST",
                url: "{{ route('instruction.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customer_id,
                    instruction: instruction,
                    category_id: category_id,
                    assigned_to: assigned_to,
                },
                beforeSend: function () {

                }
            }).done(function (response) {

            }).fail(function (response) {
                alert('Could not execute shortcut!');

                console.log(response);
            });
        });

        $(document).on('click', '.latest-scraped-shortcut', function () {
            var id = $(this).data('id');

            $('#categoryBrandModal').find('input[name="customer_id"]').val(id);
        });

        $('#sendScrapedButton').on('click', function (e) {
            e.preventDefault();

            var formData = $('#categoryBrandModal').find('form').serialize();
            var thiss = $(this);

            if (!$(this).is(':disabled')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.send.scraped') }}",
                    data: formData,
                    beforeSend: function () {
                        $(thiss).text('Sending...');
                        $(thiss).attr('disabled', true);
                    }
                }).done(function () {
                    $('#categoryBrandModal').find('.close').click();
                    $(thiss).text('Send');
                    $(thiss).attr('disabled', false);
                }).fail(function (response) {
                    $(thiss).text('Send');
                    $(thiss).attr('disabled', false);
                    console.log(response);

                    alert('Could not send 20 images');
                });
            }
        });

        $(document).on('click', '.quick-add-instruction', function (e) {
            var id = $(this).data('id');

            $(this).siblings('.quick-add-instruction-textarea').removeClass('hidden');
            $(this).siblings('.quick-priority-check').removeClass('hidden');

            $(this).siblings('.quick-add-instruction-textarea').keypress(function (e) {
                var key = e.which;
                var thiss = $(this);
                let priority = $('#instruction_priority_' + id).is(':checked') ? 'on' : '';

                if (key == 13) {
                    e.preventDefault();
                    var instruction = $(thiss).val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('instruction.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            instruction: instruction,
                            category_id: 1,
                            customer_id: id,
                            assigned_to: 7,
                            is_priority: priority
                        }
                    }).done(function () {
                        $(thiss).addClass('hidden');
                        $('#instruction_priority_' + id).addClass('hidden');
                        $(thiss).val('');
                    }).fail(function (response) {
                        console.log(response);

                        alert('Could not create instruction');
                    });
                }
            });
        });

        let r_s = "{{ $start_time }}";
        let r_e = "{{ $end_time }}";

        let start = r_s ? moment(r_s, 'YYYY-MM-DD') : moment().subtract(6, 'days');
        let end = r_e ? moment(r_e, 'YYYY-MM-DD') : moment();

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

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {

            jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

        });

        $(document).on('click', '.load-more-communication', function () {
            var thiss = $(this);
            var customer_id = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{ url('customers') }}/" + customer_id + '/loadMoreMessages',
                data: {
                    customer_id: customer_id,
                    limit: 30
                },
                beforeSend: function () {
                    //$(thiss).text('Loading...');
                }
            }).done(function (response) {
                var li = "<ul>";
                (response.messages).forEach(function (index) {
                    li += '<li>' + index + '</li>';

                    //$(thiss).closest('td').find('.more-communication-container').append(li);
                });

                li += "</ul>";

                $("#chat-list-history").find(".modal-body").html(li);
                4
                $(thiss).html("<img src='/images/chat.png' alt=''>");
                $("#chat-list-history").modal("show");

            }).fail(function (response) {
                $(thiss).text('Load More');

                alert('Could not load more messages');

                console.log(response);
            });
        });

        $(document).on('click', '.show-images-button', function () {
            $(this).siblings('.image-container').toggleClass('hidden');
        });

        $(document).on('click', '.change_message_status', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var thiss = $(this);

            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: function () {
                    $(thiss).text('Marking...');
                }
            }).done(function (response) {
                $(thiss).closest('tr').removeClass('text-danger');
                $(thiss).remove();
            }).fail(function (errObj) {
                $(thiss).text('Mark as Read');
                alert("Could not change status");
                console.log(errObj);
            });
        });


        let leadsChart = $('#leadsChart');

        var leadsChartExample = new Chart(leadsChart, {
            type: 'horizontalBar',
            data: {
                labels: [
                    'Status'
                ],
                datasets: [{
                    label: "No Lead ({{ isset($leads_data[0]->total) ? $leads_data[0]->total : 0 }})",
                    data: [{{ isset($leads_data[0]->total) ? $leads_data[0]->total : 0 }}],
                    backgroundColor: "rgba(207, 207, 211, 1)",
                    hoverBackgroundColor: "rgba(189, 188, 194, 1)"
                }, {
                    label: "Cold Lead ({{ isset($leads_data[1]->total) ? $leads_data[1]->total : 0 }})",
                    data: [{{ isset($leads_data[1]->total) ? $leads_data[1]->total : 0 }}],
                    backgroundColor: "rgba(163,103,126,1)",
                    hoverBackgroundColor: "rgba(140,85,100,1)"
                }, {
                    label: 'Cold / Important Lead ({{ isset($leads_data[2]->total) ? $leads_data[2]->total : 0 }})',
                    data: [{{ isset($leads_data[2]->total) ? $leads_data[2]->total : 0 }}],
                    backgroundColor: "rgba(63,203,226,1)",
                    hoverBackgroundColor: "rgba(46,185,235,1)"
                }, {
                    label: 'Hot Lead ({{ isset($leads_data[3]->total) ? $leads_data[3]->total : 0 }})',
                    data: [{{ isset($leads_data[3]->total) ? $leads_data[3]->total : 0 }}],
                    backgroundColor: "rgba(63,103,126,1)",
                    hoverBackgroundColor: "rgba(50,90,100,1)"
                }, {
                    label: 'Very Hot Lead ({{ isset($leads_data[4]->total) ? $leads_data[4]->total : 0 }})',
                    data: [{{ isset($leads_data[4]->total) ? $leads_data[4]->total : 0 }}],
                    backgroundColor: "rgba(94, 80, 226, 1)",
                    hoverBackgroundColor: "rgba(74, 58, 223, 1)"
                }, {
                    label: 'Advance Follow Up ({{ isset($leads_data[5]->total) ? $leads_data[5]->total : 0 }})',
                    data: [{{ isset($leads_data[5]->total) ? $leads_data[5]->total : 0 }}],
                    backgroundColor: "rgba(58, 223, 140, 1)",
                    hoverBackgroundColor: "rgba(34, 211, 122, 1)"
                }, {
                    label: 'HIGH PRIORITY ({{ isset($leads_data[6]->total) ? $leads_data[6]->total : 0 }})',
                    data: [{{ isset($leads_data[6]->total) ? $leads_data[6]->total : 0 }}],
                    backgroundColor: "rgba(187, 221, 49, 1)",
                    hoverBackgroundColor: "rgba(175, 211, 34, 1)"
                }]
            },
            options: {
                scaleShowValues: true,
                responsive: true,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontFamily: "'Open Sans Bold', sans-serif",
                            fontSize: 11
                        },
                        // display: true,
                        // scaleLabel: {
                        //   display: true,
                        //   labelString: 'Sets'
                        // }
                        stacked: true
                    }],
                    yAxes: [{
                        ticks: {
                            fontFamily: "'Open Sans Bold', sans-serif",
                            fontSize: 11
                        },
                        // display: true,
                        // scaleLabel: {
                        //   display: true,
                        //   labelString: 'Count'
                        // }
                        stacked: true
                    }]
                },
                tooltips: {
                    enabled: false
                },
                animation: {
                    onComplete: function () {
                        var chartInstance = this.chart;
                        var ctx = chartInstance.ctx;
                        ctx.textAlign = "left";
                        // ctx.font = this.scale.font;
                        ctx.fillStyle = "#fff";

                        // this.datasets.forEach(function (dataset) {
                        //   dataset.points.forEach(function (points) {
                        //     ctx.fillText(points.value, points.x, points.y - 10);
                        //   });
                        // })

                        Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            Chart.helpers.each(meta.data.forEach(function (bar, index) {
                                data = dataset.data[index];
                                if (i == 0) {
                                    ctx.fillText(data, 50, bar._model.y + 4);
                                } else {
                                    ctx.fillText(data, bar._model.x - 25, bar._model.y + 4);
                                }
                            }), this)
                        }), this);
                    }
                },
            }
        });

        $(document).on('keyup', 'add-new-note', function (event) {
            if (event.which != 13) {
                return;
            }


        });

        $(document).on('keyup', '#keyword', function (e) {
            let el = $('.keyword-results');
            el.html('');

            let keyword = $(this).val();
            if (keyword.length < 4) {
                $('.keyword-results').fadeOut('fast');
                return;
            }
            $.ajax({
                url: '{{ action('CustomerController@search') }}',
                data: {
                    keyword: keyword
                },
                success: function (response) {
                    let data = response;
                    el.fadeIn('fast');
                    el.html('');
                    data.forEach(function (item) {
                        $('.keyword-results').append(`<li><a href="/customers/${item.customer_id}/post-show?sm=${keyword}"><strong>${item.customer_name}</strong><br>${item.message}</a></li>`)
                    });
                },
                beforeSend: function () {
                    el.fadeIn('fast');
                    el.html('<p class="text-center">Loading messages...</p>');
                }
            });
        });

        $(document).on('click', '.change-lead-status', function () {
            var id = $(this).data('id');
            var lead_id = $(this).data('leadid');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('leads') }}/" + lead_id + "/changestatus",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: id
                }
            }).done(function () {
                $(thiss).parent('div').children().each(function (index) {
                    console.log(index);
                    $(this).removeClass('active-bullet-status');
                });

                $(thiss).addClass('active-bullet-status');
            });
        });

        $(document).on('click', '.fetch-broad-cast-spn', function () {
            var broadCastId = $(this).data("id");
            var customerId = $(this).data("customer-id");

            $.ajax({
                type: "GET",
                url: "{{ route('customer.broadcast.details') }}",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customerId,
                    broadcast_id: broadCastId
                }
            }).done(function (response) {
                var html = "Sorry, There is no available broadcast";
                if (response.code == 1) {
                    html = '<div class="row selection-broadcast-list" data-customer-id=' + customerId + '>';
                    if (response.data.length > 0) {
                        var res = 1;
                        $.each(response.data, function (k, v) {
                            $.each(v, function (r, d) {
                                html += '<div class="col-md-4">';
                                html += '<div class="thumbnail">';
                                html += '<img src="' + d.image + '" alt="Lights" style="width:100%">';
                                html += '<div class="caption">';
                                html += '<p>Product Id(s) : ' + d.products.join(",") + '</p>';
                                html += '<div class="custom-control custom-checkbox mb-4">';
                                html += '<input type="checkbox" checked="checked" name="selecte_products_lead[]" value="' + d.products.join(",") + '" class="custom-control-input select-pr-list-chk" id="defaultUnchecked_' + res + '">';
                                html += '<label class="custom-control-label" for="defaultUnchecked_' + res + '"></label>';
                                html += '</div>';
                                html += '</div>';
                                html += '</div>';
                                html += '</div>';
                                res++;
                            })
                        });
                    } else {
                        html = "Sorry, There is no available broadcast";
                    }

                    html += '</div>';
                }
                $("#broadcast-list").find(".modal-body").html(html);
                //if(needtoShowModel && typeof needtoShowModel != "undefined") {
                $("#broadcast-list").modal("show");
                //}
            });


        });


        var updateBroadCastList = function (customerId, needtoShowModel) {
            $.ajax({
                type: "GET",
                url: "{{ route('customer.broadcast.list') }}",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: customerId
                }
            }).done(function (response) {
                var html = "Sorry, There is no available broadcast";
                if (response.code == 1) {
                    html = "";
                    if (response.data.length > 0) {
                        $.each(response.data, function (k, v) {
                            html += '<button class="badge badge-default broadcast-list-rndr" data-customer-id="' + customerId + '" data-id="' + v.id + '">' + v.id + '</button>';
                        });
                    } else {
                        html = "Sorry, There is no available broadcast";
                    }
                }
                $("#broadcast-list").find(".modal-body").html(html);
                if (needtoShowModel && typeof needtoShowModel != "undefined") {
                    $("#broadcast-list").modal("show");
                }
            });
        }

        var broadCastIcon = $(".btn-broadcast-send");
        broadCastIcon.on("click", function () {
            updateBroadCastList($(this).data("id"), true);
        });

        $(document).on("click", ".broadcast-list-create-lead", function () {
            var $this = $(this);

            var checkedProducts = $("#broadcast-list").find("input[name='selecte_products_lead[]']:checked");
            var checkedProdctsArr = [];
            if (checkedProducts.length > 0) {
                $.each(checkedProducts, function (e, v) {
                    checkedProdctsArr += "," + $(v).val();
                })
            }

            var selectionLead = $("#broadcast-list").find(".selection-broadcast-list").first();

            //$("#broadcast-list-approval").find(".broadcast-list-approval-btn").data("broadcast", $this.data("id"));
            $("#broadcast-list-approval").find(".broadcast-list-approval-btn").data("customer-id", selectionLead.data("customer-id"));
            $("#broadcast-list-approval").modal("show");

            $(".broadcast-list-approval-btn").unbind().on("click", function () {
                var $this = $(this);
                $.ajax({
                    type: "GET",
                    url: "{{ route('customer.broadcast.run') }}",
                    beforeSend: function () {
                        // setting a timeout
                        $this.html('Sending Request...');
                    },
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        //broadcast_id: $this.data("broadcast"),
                        customer_id: $this.data("customer-id"),
                        product_to_be_run: checkedProdctsArr
                    }
                }).done(function (response) {
                    //updateBroadCastList(customerId, false);
                    $this.html('Yes');
                    $("#broadcast-list-approval").modal("hide");
                    $("#broadcast-list").modal("hide");
                }).fail(function (response) {
                    alert("Error occured, please try again later.");
                });
            });
        });

        $(document).on('change', '.change-whatsapp-no', function () {
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.change.whatsapp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: $this.data("customer-id"),
                    number: $this.val()
                }
            }).done(function () {
                alert('Number updated successfully!');
            }).fail(function (response) {
                console.log(response);
            });
        });

        $(document).on('click', '.send-contact-modal-btn', function () {
            var $this = $(this);
            $("#customer_id_attr").val($this.data("id"));
            $("#sendContacts").modal("show");
        });

        $(".select-user-wha-list").select2();

        $(document).on('click', '.send-contact-user-btn', function () {
            var $form = $("#send-contact-to-user");
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.send.contact') }}",
                data: $form.serialize(),
                beforeSend: function () {
                    $this.html("Sending message...");
                }
            }).done(function () {
                $this.html('<img style="width: 17px;" src="/images/filled-sent.png">');
                $("#sendContacts").modal("hide");
            }).fail(function (response) {
                console.log(response);
            });
        });

        $(function () {
            $('.multiselect-2').multiselect({
                    enableFiltering: true,
                    filterBehavior: 'value'
                });
        });



        $(document).on('click', '.send-group', function () {
            var thiss = $(this);
            var customerId = $(this).data('customerid');
            var groupId = $('#group' + customerId).val();
            $.ajax({
                url: "{{action('WhatsAppController@sendMessage', 'quicksell_group_send')}}",
                type: 'POST',
                data: {
                    groupId: groupId,
                    customerId: customerId,
                    _token: "{{csrf_token()}}",
                    status: 2
                },
                success: function () {
                    toastr["success"]("Group Message sent successfully!", "Message");
                 //   $("option:selected").prop("selected", false)
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    alert('There was an error sending group message...Please select group id properly');
                    $(self).removeAttr('disabled', true);
                }
            });
            console.log(customerId);
            console.log(groupId);

        });

        $(document).on('click', '.call-select', function () {
            var id = $(this).data('id');
            $('#show' + id).toggle();
            console.log('#show' + id);
        });

    </script>
@endsection