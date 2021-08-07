@extends('layouts.app')

@section('favicon' , 'scrapproduct.png')

@section('title', 'Scrap Product Info')


@section('large_content')
    <div class="row">
        <div class="col-md-offset-5 col-md-2">
            <br>
            <form action="{{ action('ScrapController@showProductStat') }}" method="get">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input value="{{$request->get('start_date')}}" type="text" name="start_date" id="start_date" class="form-control date-type">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input value="{{$request->get('end_date')}}" type="text" name="end_date" id="end_date" class="form-control date-type">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block">Filter</button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-responsive">
                @foreach($products as $key=>$product)
                    <tr>
                        <th colspan="34" class="text-center">
                            <h3>{{ $key }}</h3>
                        </th>
                    </tr>
                    <tr>
                        @foreach($product as $bkey => $brand)
                                <td>{{ $bkey }}</td>
                        @endforeach
                        <th>Total</th>
                    </tr>
                    <tr>
                        <?php $total = 0; ?>
                        @foreach($product as $bkey=>$brand)
                            <?php $total += $brand ?>
                            <td>{{ $brand }}</td>
                        @endforeach
                        <th>{{ $total }}</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.date-type').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection