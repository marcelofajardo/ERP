@foreach ($logs as $log)

                <tr>
                    <td>{{ $log->ip }}</td>
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        {{ strlen( $log->browser ) > 20 ? substr( $log->browser , 0, 20).'...' :  $log->browser }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->browser }}
                        </span>
                    </td>
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        {{ strlen( $log->location ) > 20 ? substr( $log->location , 0, 20).'...' :  $log->location }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->location }}
                        </span>
                    </td>
                    <td>{{ $log->page_current }}</td>
                    <td>@if($log->visits == 1) New Visitor @else{{ $log->visits }} @endif</td>
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        @if(json_decode($log->page))
                         {{ json_decode($log->page)[0] }}
                        @endif
                        </span>
                        <span class="td-full-container hidden">
                        @if(json_decode($log->page)) 
                        @foreach(json_decode($log->page) as $page)
                        {{ $page }}
                        @endforeach
                        @endif
                        </span>
                    </td>
                    <td>{{ $log->chats }}</td>
                    <td>{{ $log->customer_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->last_visit)->format('d-m-y H:i:s')  }}</td>
                </tr>
@endforeach