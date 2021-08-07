 @if($countries->isEmpty())

            <tr>
                <td colspan="5">
                    No Result Found
                </td>
            </tr>
@else

@foreach ($countries as $country)
    <tr>
        <td>{{ $country->country_code }}</td>
        <td>{{ $country->country_name }}</td>
        <td><input type="number" step="0.00" class="dutyinput form-control" data-id="{{$country->id}}" value="{{$country->default_duty}}"></td>
        <td>{{ $country->created_at->format('d-m-Y') }}</td>
        <td>{{ $country->updated_at->format('d-m-Y H:i:s') }}</td>
    </tr>   
@endforeach
@endif