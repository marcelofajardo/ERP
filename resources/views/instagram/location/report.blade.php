@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Instagram Data Stats</h1>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
        </div>
        <div class="col-md-12 text-center">
            <canvas id="myChart" width="400" height="150"></canvas>
        </div>
        <div class="col-md-12">
            <table class="table-striped table table-sm mt-5">
                <tr>
                    <th>SN</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>Count</th>
                    <th>Actions</th>
                </tr>

                @foreach($stats as $key=>$stat)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $stat->country }}</td>
                        <td>{{ $stat->region }}</td>
                        <td>{{ $stat->count }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="@{{ action('TargetLocationController@show', $stat->location_id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>
    <script>

        var coloR = [];

        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        for (var i = 0;i< {{count($stats)}};i++) {
            coloR.push(dynamicColors());
        }

        let data = {
            datasets: [{
                data: [{{ $data }}],
                backgroundColor: coloR
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [{!!  $labels !!}]
        };

        var myPieChart = new Chart(document.getElementById('myChart').getContext('2d'), {
            type: 'pie',
            data: data
        });
    </script>
@endsection