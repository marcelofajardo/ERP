 @if($products->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
@else

@foreach ($products as $product)
    
    <tr id="category{{ $product['category'] }}">
        <td><input type="checkbox" class="form-control checkBoxClass" name="composition" data-name="{{ $product['composition'] }} {{ $product['category_name'] }}" data-category="{{ $product['category'] }}"></td>
        <td>{{ $product['category_name'] }}</td>
        <td>{{ $product['total'] }}</td>
        <td>{{ $product['composition'] }}</td>
    </tr>   
@endforeach
@endif