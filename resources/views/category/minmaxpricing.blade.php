@extends('layouts.app')

@section('content')
    <table class="table table-striped">
        <tr>
            <th>Segment</th>
            <th>Category</th>
            <th>&nbsp;</th>
            <th>Min. Price</th>
            <th>Max. Price</th>
            <th>&nbsp;</th>
        </tr>
        @foreach ( $brandSegments as $brandSegment )
            @foreach ( $results as $result )
                <tr>
                    <td>{{ $brandSegment }}</td>
                    <td>{{ $result->parent_name.' -> '.$result->title }}</td>
                    <td><input type="text" data-type='min' data-cat='{{ $result->cat_id }}' data-brand='{{ $brandSegment }}' class="form-control update-pricing" style="text-align: right;" value="{{ $formResults[$brandSegment][$result->cat_id]['min'] ?? '' }}"></td>
                    <td>{{ $result->minimumPrice }}</td>
                    <td class="text-right">{{ $result->maximumPrice }}</td>
                    <td><input type="text" data-type='max' data-cat='{{ $result->cat_id }}' data-brand='{{ $brandSegment }}' class="form-control update-pricing" value="{{ $formResults[$brandSegment][$result->cat_id]['max'] ?? '' }}"></td>
                </tr>
            @endforeach
        @endforeach
    </table>

    <script>
        $(document).ready(function () {
            $(document).on('blur', '.update-pricing', function (event) {
                let data_category_id = $(this).data('cat');
                let data_brand_segment = $(this).data('brand');
                let data_type = $(this).data('type');
                let data_price = $(this).val();

                console.log(data_category_id, data_brand_segment, data_type, data_price);

                $.ajax({
                    url: '/category/brand/update-min-max-pricing',
                    data: {
                        brand_segment: data_brand_segment,
                        category_id: data_category_id,
                        type: data_type,
                        price: data_price,
                        _token: "{{ csrf_token() }}",
                    },
                    type: 'POST',
                    success: function () {
                        console.log('Price update successfully');
                    }
                });

            });
        });
    </script>
@endsection
