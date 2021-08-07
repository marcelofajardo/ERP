 @if($calculations->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
@else

@foreach ($calculations as $calculation)
    <tr>
        <td>{{ $calculation->duty_hscode }}</td>
        <td>{{ $calculation->currency_type_destination }}</td>
        <td>{{ $calculation->currency_type_origin }}</td>
        <td>{{ $calculation->value }}</td>
        <td>{{ $calculation->duty }}</td>
        <td>{{ $calculation->duty_type }}</td>
        <td>{{ $calculation->duty_rate }}</td>
        <td>{{ $calculation->shipping }}</td>
        <td>{{ $calculation->insurance }}</td>
        <td>{{ $calculation->total }}</td>
        <td>{{ $calculation->exchange_rate }}</td>
        <td>{{ $calculation->duty_minimis }}</td>
        <td>{{ $calculation->vat_minimis }}</td>
        <td>{{ $calculation->vat_rate }}</td>
        <td>{{ $calculation->vat }}</td>
        <td>{{ $calculation->created_at->format('d-m-Y') }}</td>
        <td>{{ $calculation->updated_at->format('d-m-Y H:i:s') }}</td>
    </tr>  
    
    
@endforeach
@endif
