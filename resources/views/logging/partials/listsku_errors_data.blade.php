@foreach ($logScrappers as $logScrapper)
    <tr>
        <td style="width: 20% !important;">@if($logScrapper->brandLink($logScrapper->sku_search_url,$logScrapper->sku))<a href="{{ $logScrapper->brandLink($logScrapper->sku_search_url,$logScrapper->sku) }}" target="_blank"> @endif{{ $logScrapper->brand }}</a></td>
        <td style="width: 20% !important;">@if(isset($logScrapper->category)) {{ $logScrapper->dataUnserialize($logScrapper->category) }} @endif</td>
        <td style="width: 20% !important;"><a href="{{ $logScrapper->getSKUExampleLinkFromLogScraper($logScrapper->website,$logScrapper->brand) }}" target="_blank">{{ $logScrapper->website }}</a></td>
        <td>{{ $logScrapper->total }}</td>
        <td>@if($logScrapper->taskType($logScrapper->website,$logScrapper->dataUnserialize($logScrapper->category),$logScrapper->brand) == false) 
                <button onclick="addTask('{{ $logScrapper->website }}' , '{{ $logScrapper->dataUnserialize($logScrapper->category) }}','{{ $logScrapper->sku }}','{{ $logScrapper->brand }}')" class="btn btn-secondary">Add Issue</button>
            @else
               {!! $logScrapper->taskType($logScrapper->website,$logScrapper->dataUnserialize($logScrapper->category),$logScrapper->brand) !!}
            @endif
            </td>
    </tr>
    <tr>
        <td style="width: 20% !important;">SKU Format :<br> {{ $logScrapper->sku_format }}</td>
        <td style="width: 20% !important;">SKU Example :<br> {{ $logScrapper->sku_examples }}</td>
        <td style="width: 20% !important;">SKU Log Scraper :<br> {{ $logScrapper->sku }}</td>
        <td>{{ $logScrapper->skuStringCompareWithExample($logScrapper->sku_examples,$logScrapper->sku) }}</td>
        <td></td>
    </tr>
@endforeach
