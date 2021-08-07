@extends('layouts.app')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">MASTER CASH FLOW</h2>
            <form action="{{route('cashflow.mastercashflow')}}" method="GET">
                <div class="row">
                    {{-- <div class="col-md-4 col-lg-4">
                         <br>
                         <input name="term" type="text" class="form-control"
                                value="{{ isset($term) ? $term : '' }}"
                                placeholder="Search">
                     </div>--}}
                    <div class="col-md-8 col-lg-8">
                        <div class="form-group">
                            <strong>Select Transaction Date</strong>
                            <input type="text" value="{{$start_date}}" name="range_start" hidden/>
                            <input type="text" value="{{$end_date}}" name="range_end" hidden/>
                            <div id="reportrange"
                                 style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <br>
                        <button type="submit" class="btn btn-primary">View Cash Flow</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <table class="table table-stripe table-bordered">
            <thead>
            <tr>
                <th colspan="23" class="text-center">Master Cash Flow</th>
            </tr>
            <tr>
                <th colspan="11" class="text-center">Expected Cash Flow</th>
                <th colspan="11" class="text-center">Actual Cash Flow</th>
                <th></th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Date</th>
                <th colspan="2">Amount</th>
                <th>Location</th>
                <th>Converted Amount</th>
                <th colspan="4">Balance</th>
                <th>Date</th>
                <th>Description</th>
                <th>Date</th>
                <th colspan="2">Amount</th>
                <th>Location</th>
                <th>Converted Amount</th>
                <th colspan="4">Balance</th>
                <th>Remarks</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Dr.</th>
                <th>Cr.</th>
                <th></th>
                <th></th>
                @foreach($currencies as $currency)
                    <th>{{ $currency }}</th>
                @endforeach
                <th></th>
                <th></th>
                <th></th>
                <th>Dr.</th>
                <th>Cr.</th>
                <th></th>
                <th></th>
                @foreach($currencies as $currency)
                    <th>{{ $currency }}</th>
                @endforeach
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                {{-- opening balance at the start of selected date range --}}
                <td>{{$start_date}}</td>
                <td>Opening Balance</td>
                <td></td>
                <td></td>
                <td>{{ $opening_balance['total'] }}</td>
                <td></td>
                <td></td>
                @foreach($currencies as $currency)
                    <td>{{ $opening_balance[$currency] }}</td>
                @endforeach
                <td>{{$start_date}}</td>
                <td>Opening Balance</td>
                <td></td>
                <td></td>
                <td>{{ $opening_balance['total'] }}</td>
                <td></td>
                <td></td>
                @foreach($currencies as $currency)
                    <td>{{ $opening_balance[$currency] }}</td>
                    <?php
                    $closing_balance[$currency] = $opening_balance[$currency];
                    $closing_actual[$currency] = $opening_balance[$currency];
                    ?>
                @endforeach
                <td></td>
            </tr>
            <?php
            $closing_balance['total'] = $opening_balance['total'];
            $closing_actual['total'] = $opening_balance['total'];
            $date_wise = $transactions->groupBy(function ($item, $key) {
                return substr($item['date'], 0);
            });
            ?>
            <!--            added capitals in between the selected date range -->
            @foreach($added_capitals_in_between as $module => $added_capital)
                <tr>
                    <td>{{ $added_capital->date }}</td>
                    <td>Capital Added</td>
                    <td></td>
                    <td></td>
                    <td>
                        {{ $added_amount = $added_capital->amount }}
                        <?php
                        $closing_balance['total'] += $added_amount;
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    @foreach($currencies as $currency_index => $currency)
                        <td>
                            @if($added_capital->currency == $currency_index)
                                {{ $added_currency_amount = $added_capital->amount }}
                                <?php
                                $closing_balance[$currency] += $added_currency_amount ?: 0;
                                ?>
                            @endif
                        </td>
                    @endforeach

                    <td>{{ $added_capital->date }}</td>
                    <td>Capital Added</td>
                    <td></td>
                    <td></td>
                    <td>
                        {{ $added_amount = $added_capital->amount }}
                        <?php
                        $closing_actual['total'] += $added_amount;
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    @foreach($currencies as $currency_index => $currency)
                        <td>
                            @if($added_capital->currency == $currency_index)
                                {{ $added_currency_amount = $added_capital->amount }}
                                <?php
                                $closing_actual[$currency] += $added_currency_amount ?: 0;
                                ?>
                            @endif
                        </td>
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
            @foreach($date_wise as $date => $date_wise_transactions)
                <?php
                $type_wise = $date_wise_transactions->groupBy(function ($item, $key) {
                    return substr($item['type'], 0);
                });
                ?>
                @foreach($type_wise as $type => $type_wise_transactions)
                    <?php
                    $module_wise = $type_wise_transactions->groupBy(function ($item, $key) {
                        return substr($item['cash_flow_able_type'], 4);
                    });
                    ?>
                    @foreach($module_wise as $module => $module_wise_transactions)
                        <tr>
                            <td>{{ $date }}</td>
                            <td>Expected @if($type == 'received') from @else for @endif {{ $module }}</td>
                            <td></td>

                            <td>
                                @if($type == 'paid')
                                    {{ $paid_expected = $module_wise_transactions->sum('expected') }}
                                    <?php
                                    $closing_balance['total'] -= $paid_expected;
                                    ?>
                                @else
                                    <?php $paid_expected = 0 ?>
                                @endif
                            </td>
                            <td>
                                @if($type == 'received')
                                    {{ $received_expected = $module_wise_transactions->sum('expected') }}
                                    <?php
                                    $closing_balance['total'] += $received_expected;
                                    ?>
                                @else
                                    <?php $received_expected = 0 ?>
                                @endif
                            </td>

                            <td></td>
                            <td></td>
                            @foreach($currencies as $currency_index => $currency)
                                <td>
                                    <?php $amount = $module_wise_transactions->where('currency', $currency_index)->sum('expected')?>
                                    @if($type == 'paid' && $amount)
                                        {{--                                        bracket sign for negative or balance that is being deducted--}}
                                        ({{ $amount }})
                                    @else
                                        {{ $amount?: '' }}
                                    @endif
                                </td>
                                <?php
                                if ($type == 'received')
                                    $closing_balance[$currency] += $amount ?: 0;
                                else
                                    $closing_balance[$currency] -= $amount ?: 0;
                                ?>
                            @endforeach

                            <td>{{ $date }}</td>
                            <td>Expected @if($type == 'received') from @else for @endif {{ $module }}</td>
                            <td></td>

                            <td>
                                @if($type == 'paid')
                                    {{ $paid_actual = $module_wise_transactions->sum('actual') }}
                                    <?php
                                    $closing_actual['total'] -= $paid_actual;
                                    ?>
                                @else
                                    <?php $paid_actual = 0 ?>
                                @endif
                            </td>
                            <td>
                                @if($type == 'received')
                                    {{ $received_actual = $module_wise_transactions->sum('actual') }}
                                    <?php
                                    $closing_actual['total'] += $received_actual;
                                    ?>
                                @else
                                    <?php $received_actual = 0 ?>
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                            @foreach($currencies as $currency_index => $currency)
                                <td>
                                    <?php $amount = $module_wise_transactions->where('currency', $currency_index)->sum('actual')?>
                                    @if($type == 'paid' && $amount)
                                        {{--                                        bracket sign for negative or balance that is being deducted--}}
                                        ({{ $amount }})
                                    @else
                                        {{ $amount?: '' }}
                                    @endif
                                </td>
                                <?php
                                if ($type == 'received')
                                    $closing_actual[$currency] += $amount ?: 0;
                                else
                                    $closing_actual[$currency] -= $amount ?: 0;
                                ?>
                            @endforeach
                            <td></td>
                        </tr>
                    @endforeach

                @endforeach
            @endforeach

            @if(!$transactions->isEmpty())
                <tr>
                    {{-- closing balance at the end of selected date range --}}
                    <td>{{$end_date}}</td>
                    <td>Closing Balance</td>
                    <td></td>
                    <td></td>
                    <td>{{ $closing_balance['total'] }}</td>
                    <td></td>
                    <td></td>
                    @foreach($currencies as $currency)
                        <td>{{ $closing_balance[$currency] }}</td>
                    @endforeach
                    <td>{{$end_date}}</td>
                    <td>Closing Balance</td>
                    <td></td>
                    <td></td>
                    <td>{{ $closing_actual['total'] }}</td>
                    <td></td>
                    <td></td>
                    @foreach($currencies as $currency)
                        <td>{{ $closing_actual[$currency] }}</td>
                    @endforeach
                    <td></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            let r_s = jQuery('input[name="range_start"]').val();
            let r_e = jQuery('input[name="range_end"]').val()

            let start = r_s ? moment(r_s, 'YYYY-MM-DD') : moment().subtract(6, 'days');
            let end = r_e ? moment(r_e, 'YYYY-MM-DD') : moment();

            // jQuery('input[name="range_start"]').val(start.format('YYYY-MM-DD'));
            // jQuery('input[name="range_end"]').val(end.format('YYYY-MM-DD'));

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

        });

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {

            jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

        });

    </script>
@endsection
