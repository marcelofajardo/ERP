@foreach ($logs as $log)

                <tr class="currentPage" data-page="{{$logs->currentPage()}}">

                    <td>{{$log->id}}</td>

                    <td>{{$log->ip}}</td>

                    <td>{{$log->method}}</td>

                    

                        
                     <td style="width: 30%" class="expand-row table-hover-cell">
                        <span class="td-mini-container">
                        {{ strlen( $log->url ) > 50 ? substr( $log->url , 0, 50).'...' :  $log->url }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->url }}
                        </span>
                    </td>
                     
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        {{ strlen( $log->request) > 60 ? substr( $log->request, 0, 60).'...' :  $log->request}}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->request}}
                        </span>
                    </td>
                    <td>{{ $log->status_code }}</td>
                    <td>{{ $log->time_taken }} s</td>
                    
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-y H:i:s')  }}</td>

                    <td><button class="btn btn-warning showModalResponse" data-id="{{$log->id}}">View</button></td>

                    
                </tr>
@endforeach