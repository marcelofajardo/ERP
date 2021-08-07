@foreach ($logListMagentos as $logListMagento)
    <tr>
        <td>{{ $logListMagento->product_id }}</td>
        <td>{{ $logListMagento->sku }}</td>
        <td>{{ $logListMagento->name }}</td>
        <td>{{ $logListMagento->title }}</td>
        <td class="text-right">&euro; {{ $logListMagento->price }}</td>
        <td>{{ $logListMagento->message }}</td>
        <td>{{ $logListMagento->created_at->format('d-M-Y H:i:s') }}</td>
    </tr>
@endforeach