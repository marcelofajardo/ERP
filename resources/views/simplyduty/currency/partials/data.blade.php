 @if($currencies->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
@else

@foreach ($currencies as $currency)
    <tr>
        <td>{{ $currency->currency }}</td>
        <td>{{ $currency->created_at->format('d-m-Y') }}</td>
        <td>{{ $currency->updated_at->format('d-m-Y H:i:s') }}</td>
    </tr>   
@endforeach
@endif