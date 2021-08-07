@extends('layouts.app')


@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <section class="dashboard-counts section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('graph') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>By Month/Week</strong>
                                <?php

                                $date_types = ['week'=>'week', 'month'=>'month'];
	                            echo Form::select( 'date_type', $date_types, $date_type , [
		                            'class'       => 'form-control',
		                            'id' => 'date_type',
		                            'name' => 'date_type'
	                            ] );?>

                                <script>
                                    jQuery(document).ready(function () {

                                        jQuery('#date_type').change(function () {
                                            let date_type = jQuery(this).val();

                                            if( date_type === 'week' ){
                                                jQuery('input[name="week_range"]').show();
                                                jQuery('input[name="month_range"]').hide();
                                            }
                                            else {
                                                jQuery('input[name="week_range"]').hide();
                                                jQuery('input[name="month_range"]').show();
                                            }
                                        });

                                        jQuery('#date_type').trigger('change');
                                    });

                                </script>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Date Range</strong>
                                <input style="{{ ( $date_type == 'month' )? 'display: none;' : '' }}" type="week" class="form-control" value="{{ $week_range }}" name="week_range" />
                                <input style="{{ ( $date_type == 'week' )? 'display: none;' : '' }}" type="month" class="form-control" value="{{ $month_range }}" name="month_range" />
                            </div>
                        </div>
                            <div class="col-md-4">
                                <strong>&nbsp;</strong>
                                <button type="submit" class="btn btn-secondary">Submit</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{--{{ json_encode($workDone) }}--}}

    <section class="dashboard-counts section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card activity-chart">
                        <div class="card-header d-flex align-items-center">
                            <h4>Activity Chart</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="ActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {

            'use strict';

            let brandPrimary = 'rgba(51, 179, 90, 1)';

            let ActivityChart = $('#ActivityChart');

            var barChartExample = new Chart(ActivityChart, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach($workDone as $key => $value)
                        '{{ $key }}',
                        @endforeach
                    ],
                    datasets: [
                        {
                            label: "Work Done",

                            // backgroundColor: '#5EBA31' ,
                            backgroundColor: [

                                @foreach($workDone as $key => $value)
                                '{{
                                    isset( $benchmark[$key] )  ? (
                                    $benchmark[$key] < $value ? '#5EBA31' : '#FF0000'
                                    ) : '#5EBA31'
                                 }}',
                                @endforeach
                            ] ,
                            // data: [65, 59, 80, 81, 56, 55, 140],
                            data: [
                                @foreach($workDone as $key => $value)
                                {{ $value.',' }}
                                @endforeach
                            ],
                        },
                        {
                            label: "Benchmark",
                            // backgroundColor: ['rgba(203, 203, 203, 0.6)',],
                            backgroundColor: '#5738CA' ,
                            // backgroundColor: '#FF0000',

                            // data: [35, 40, 60, 47, 88, 27, 30],
                            data: [
                                @foreach($workDone as $key => $value)
                                {{ ( isset( $benchmark[$key] ) ? $benchmark[$key] : 0 ).',' }}
                                @endforeach
                            ],
                        }
                    ],
                },
                options: {
                    scaleShowValues: true,
                    scales: {
                        yAxes: [{
                            // stacked: true,
                            ticks: {
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Work Done'
                            }
                        }],
                        xAxes: [{
                            // stacked: true,
                            ticks: {
                                autoSkip: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Days'
                            }
                        }]
                    }
                }
            });

        });
    </script>

@endsection

{{--
/*backgroundColor: [
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)',
'rgba(51, 179, 90, 0.6)'
],
*/
/*borderColor: [
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)',
'rgba(51, 179, 90, 1)'
],*/

borderColor: [
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)',
'rgba(203, 203, 203, 1)'
],--}}
