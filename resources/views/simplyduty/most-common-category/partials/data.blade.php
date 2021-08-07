 @if(count($categories) == 0)

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
@else
@php
$count = 0;
@endphp
@foreach ($categories as $category)

    <tr class="category{{ $category['id'] }}{{ $count }}">
        <td><input type="checkbox" class="form-control checkBoxClass" name="composition" data-name="{{ $category['composition'] }} {{ $category['name'] }}" data-category="{{ $category['id'] }}" data-count="{{ $count }}"></td>
        <td>
        {{ $category['name'] }}</td>
        <td>{{ $category['composition'] }}</td>
    </tr> 
    @php
    $count++;
    @endphp    
@endforeach
@endif