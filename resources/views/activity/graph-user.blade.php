@extends('layouts.app')


@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <section class="dashboard-counts section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('graph_user') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>User</strong>
									<?php
									echo Form::select( 'selected_user', $users, $selected_user, [
										'class' => 'form-control',
										'id'    => 'userList',
										'name'  => 'selected_user'
									] );?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>By Day/Month</strong>
									<?php

									$date_types = [ 'day' => 'day', 'month' => 'month' ];

									echo Form::select( 'date_type', $date_types, $date_type, [
										'class' => 'form-control',
										'id'    => 'date_type',
										'name'  => 'date_type'
									] );?>

                                    <script>
                                        jQuery(document).ready(function () {

                                            jQuery('#date_type').change(function () {
                                                let date_type = jQuery(this).val();

                                                if (date_type === 'day') {
                                                    jQuery('input[name="day_range"]').show();
                                                    jQuery('input[name="month_range"]').hide();
                                                }
                                                else {
                                                    jQuery('input[name="day_range"]').hide();
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
                                    <input style="{{ ( $date_type == 'month' )? 'display: none;' : '' }}" type="date"
                                           class="form-control" value="{{ $day_range }}" name="day_range"/>
                                    <input style="{{ ( $date_type == 'day' )? 'display: none;' : '' }}" type="month"
                                           class="form-control" value="{{ $month_range }}" name="month_range"/>
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
            let color = {
                'selection': '#6F90D0',
                'searcher': '#E49233',
                'attribute': '#6FBE4E',
                'supervisor' : '#DF5056',
                'imagecropper' : '#7C8383',
                'lister' : '#995CAB',
                'approver' : '#B66651',
                'inventory' : '#CCC768',
                'sales' : '#009EF2',
                // 'attribute': '#FF4D7D',
                // 'approver': '#00C4C3',
            };

            let ActivityChart = $('#ActivityChart');

            var barChartExample = new Chart(ActivityChart, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach($dataLabel as $item)
                            '{{ $item }}',
                        @endforeach
                    ],
                    datasets: [
                            @foreach($workDone as $key => $value)
                        {
                            label: "{{$key}}",
                            backgroundColor: color["{{$key}}"],
                            data: [
                                @for($i = $date_type == 'day' ? 0 : 1; $i < sizeof($value); $i++)
                                    '{{ $value[$i] }}',
                                @endfor
                            ],
                        },
                        @endforeach

                    ],
                },
                options: {
                    scaleShowValues: true,
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Work Done'
                            }
                        }],
                        xAxes: [{
                            stacked: true,
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
