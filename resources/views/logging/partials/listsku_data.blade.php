@foreach ($logScrappers as $logScrapper)
    <tr>
        <td style="width: 5% !important;"><a href="/search?term={{ $logScrapper->sku }}&roletype=Inventory&key=&category%5B%5D=1&size=&price_min=&price_max=&date=" target="_blank">{{ $logScrapper->sku }}</a></td>
        <td style="width: 5% !important;">{{ $logScrapper->skuFormat($logScrapper->sku,$logScrapper->brand) }}</td>
         <td style="width: 5% !important;">{{ $logScrapper->skuFormatExample($logScrapper->sku,$logScrapper->brand) }}</td>
        <td style="width: 20% !important;">@if($logScrapper->brandLink($logScrapper->sku,$logScrapper->brand))<a href="{{ $logScrapper->brandLink($logScrapper->sku,$logScrapper->brand) }}" target="_blank"> @endif{{ $logScrapper->brand }}</a></td>
        <td style="width: 20% !important;">@if(isset($logScrapper->category)) {{ $logScrapper->dataUnserialize($logScrapper->category) }} @endif</td>
        <td style="width: 20% !important;">{{ $logScrapper->website }}</td>
        <td>{{ $logScrapper->skuError( $logScrapper->validation_result) }}</td>
        <td>@if($logScrapper->created_at) {{ $logScrapper->created_at->format('d-M-Y H:i:s') }} @endif</td>
        <td>@if($logScrapper->updated_at) {{ $logScrapper->updated_at->format('d-M-Y H:i:s') }} @endif</td>
        <td>@if($logScrapper->taskType($logScrapper->website,$logScrapper->dataUnserialize($logScrapper->category),$logScrapper->brand) == false) 
                <button onclick="addTask('{{ $logScrapper->website }}' , '{{ $logScrapper->dataUnserialize($logScrapper->category) }}','{{ $logScrapper->sku }}','{{ $logScrapper->brand }}')" class="btn btn-secondary">Add Issue</button>
            @else
               {!! $logScrapper->taskType($logScrapper->website,$logScrapper->dataUnserialize($logScrapper->category),$logScrapper->brand) !!}
            @endif
            </td>
    </tr>
@endforeach
