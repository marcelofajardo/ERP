@foreach ($scraperLogs as $log)
    <tr>
        <td>{{ $log->id }}</td>
        <td>{{ $log->ip_address }}</td>
        <td>{{ $log->website }}</td>
        <td><a href="{{ $log->url }}" target="__blank">{{ $log->url }}</a></td>
        <td>{{ $log->sku }}</td>
        <td>{{ $log->original_sku }}</td>
        {{-- <td>{{ $log->title }}</td>
        <td>{{ $log->validation_result }}</td>
        <td>{{ $log->size }}</td>
        <td>{{ $log->composition }}</td>
        <td>{{ $log->country }}</td>
        <td>{{ $log->supplier }}</td> --}}
        <td>
            {{ $log->created_at }} 

            <button type="button" onclick="showScrappedProduct()" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                View
            </button>
        </td>
    </tr>
@endforeach


<script>
  function showScrappedProduct(){

  }
</script>