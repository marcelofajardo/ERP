@foreach ($apilogs as  $key => $log)
    @php
        $scraper = \App\Scraper::find($log->scraper_id);
        $scraper_name = '';
        if ($scraper) {
            $scraper_name = $scraper->scraper_name;
        }
    @endphp
    <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $scraper_name }}</td>
        <td>{{ $log->server_id }}</td>
        <td>{{ $log->created_at }}</td>
        @if (strlen($log->log_messages) > 250)
            <td style="word-break: break-word;" data-log_message="{{ $log->log_messages }}" class="log-message-popup">{{ substr($log->log_messages,0,250) }}...</td>    
        @else
            <td style="word-break: break-word;">{{ $log->log_messages }}</td>
        @endif
    </tr>
@endforeach