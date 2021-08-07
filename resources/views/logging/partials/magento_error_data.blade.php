@foreach($productErrorLogs as $log)
<tr>
    <td>
    @if($log->product_id)
        {{$log->product_id}}
    @endif
    </td>
    <td>
    {{ $log->created_at->format('d-m-y H:i:s') }}
    </td>
    <td>
        @if($log->store_website_id)
            {{$log->store_website->title}}
        @endif
        <br>
        <a title="{{$log->url}}" href="{{$log->url}}">{{ str_limit($log->url, 30, '...')}}</a>
    </td>
    <td class="expand-row-msg" data-name="message" data-id="{{$log->id}}">
        <span class="show-short-message-{{$log->id}}">{{ str_limit($log->message, 30, '...')}}</span>
        <span style="word-break:break-all;" class="show-full-message-{{$log->id}} hidden">{{$log->message}}</span>
    </td>
    <td class="expand-row-msg" data-name="request_data" data-id="{{$log->id}}">
        <span class="show-short-request_data-{{$log->id}}">{{ str_limit($log->request_data, 50, '...')}}</span>
        <span style="word-break:break-all;" class="show-full-request_data-{{$log->id}} hidden">{{$log->request_data}}</span>
    </td>
    <td class="expand-row-msg" data-name="response_data" data-id="{{$log->id}}">
        <span class="show-short-response_data-{{$log->id}}">{{ str_limit($log->response_data, 45, '...')}}</span>
        <span style="word-break:break-all;" class="show-full-response_data-{{$log->id}} hidden">
        {{$log->response_data}}</span>
    </td>
    <td>{{$log->response_status}}</td>
</tr>
@endforeach
