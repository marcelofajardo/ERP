@extends('layouts.app')

@section('favicon' , 'listingpayments.png')

@section('title', 'Listing Payment')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Listing Payments</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="get" action="{{action('ListingPaymentsController@index')}}">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="">Select User</option>
                            @foreach($users as $key=>$user)
                                <option {{ $request->get('user_id')===$key? 'selected' : '' }} value="{{ $key }}">{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-image"><img src="{{asset('images/search.png')}}" alt="Search"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <table class="mt-5 table table-striped table-bordered">
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Value</th>
                <th colspan="2">Payment Date</th>
                <th colspan="2">Balance</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Date</th>
                <th>Amount</th>
                <th colspan="2"></th>
            </tr>
            @php $lastUser = null; $totalPayments = 0; $totalRemaining = 0; $ta = 0; $tr = 0; @endphp
            @foreach($histories as $key=>$history)
                @if($key==0)
                    @php $lastUser = $history->user_id; $balance = 0; $car = \App\User::find($history->user_id)->listing_approval_rate; $cjr = \App\User::find($history->user_id)->listing_rejection_rate;  @endphp
                @endif
                @if ($lastUser!=$history->user_id)
                        @php $totalRemaining = 0; $totalPayments = 0; @endphp
                        <tr style="background: #c8cbcf">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Appr: {{ $ta }}</td>
                        <td>Rjct: {{ $tr }}</td>
                            <form method="post" action="{{ action('ListingPaymentsController@store') }}">
                                <input type="hidden" name="user_id" value="{{ $lastUser }}">
                                @php $lastUser = $history->user_id; @endphp
                                @csrf
                                <td>
                                    <input type="date" name="date" placeholder="Date" value="{{ date('Y-m-d') }}">
                                </td>
                                <td>
                                    <input type="text" name="amount" placeholder="Amount" value="{{ number_format($balance, 2) }}">
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-secondary">
                                        Add Amount
                                    </button>
                                </td>
                            </form>
                    </tr>
                    @php $balance = 0; $ta = 0; $tr = 0;$car = \App\User::find($history->user_id)->listing_approval_rate; $cjr = \App\User::find($history->user_id)->listing_rejection_rate; @endphp
                @endif
                <tr>
                    <td>{{ $history->date }}</td>
                    <td>{{ $users[$history->user_id] }}</td>
                    <td>Attribute Approved - {{ $history->attribute_approved }}</td>
                    <td>{{ $car }}</td>
                    <td>{{ $history->attribute_approved * $car }}</td>
                    <td>-</td>
                    <td>-</td>
                    @php $balance += ($history->attribute_approved * $car); $ta += ($history->attribute_approved * $car) @endphp
                    <td colspan="2">{{ $balance }}</td>
                </tr>
                <tr>
                    <td>{{ $history->date }}</td>
                    <td>{{ $users[$history->user_id] }}</td>
                    <td>Attribute Rejected - {{ $history->attribute_rejected }}</td>
                    <td>{{ $cjr }}</td>
                    <td>{{ $history->attribute_rejected * $cjr }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td colspan="2">@php $balance += ($history->attribute_rejected * $cjr); $tr += ($history->attribute_rejected * $cjr) @endphp {{ $balance }}</td>
                </tr>
                    @php
                    $pays = \App\ListingPayments::where('user_id', $history->user_id)->where('paid_at', $history->date)->get();
                    @endphp
                    @foreach($pays as $pay)
                        @php $balance -= $pay->amount @endphp
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <th>{{ $pay->paid_at }}</th>
                            <th>{{ $pay->amount }}</th>
                            <th>{{ number_format($balance, 2) }}</th>
                        </tr>
                    @endforeach
                @endforeach
            @if ($request->get('user_id') > 0)
                @php $totalRemaining = 0; $totalPayments = 0; @endphp
                <tr style="background: #c8cbcf">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <form method="post" action="{{ action('ListingPaymentsController@store') }}">
                        <input type="hidden" name="user_id" value="{{ $lastUser }}">
                        @php $lastUser = $history->user_id; @endphp
                        @csrf
                        <td>
                            <input type="date" name="date" placeholder="Date" value="{{ date('Y-m-d') }}">
                        </td>
                        <td>
                            <input type="text" name="amount" placeholder="Amount" value="{{ number_format($balance, 2) }}">
                        </td>
                        <td>
                            <button class="btn btn-xs btn-secondary">
                                Add Amount
                            </button>
                        </td>
                    </form>
                </tr>
                @php $balance = 0; @endphp
            @endif
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select-multiple').select2();
        });
        $(document).on('click', '.save-corrections', function() {
            let pid = $(this).attr('data-id');
            let is_corrected = $("#corrected_"+pid).is(':checked') ? 1 : 0;
            let is_script_corrected = $("#script_corrected_"+pid).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ action('ProductController@updateProductListingStats') }}',
                data: {
                    is_corrected: is_corrected,
                    is_script_corrected: is_script_corrected,
                    product_id: pid
                },
                success: function(response) {
                    console.log(response);
                }
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

        $(document).on('click', '.relist-product', function() {
            let pid = $(this).attr('data-id');

            $.ajax({
                url: '{{ action('ProductController@relistProduct') }}',
                data: {
                    product_id: pid
                },
                success: function(response) {
                    $('.rec_'+pid).hide();
                    toastr['success']('Product relisted successfully!');
                }
            });
        });
    </script>
@endsection