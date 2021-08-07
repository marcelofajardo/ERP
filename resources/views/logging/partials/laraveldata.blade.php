@foreach ($logs as $log)

                <tr>
                     <td style="width: 30%" class="expand-row table-hover-cell">
                        <span class="td-mini-container">
                        {{ strlen( $log->filename ) > 80 ? substr( $log->filename , 0, 80).'...' :  $log->filename }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->filename }}
                        </span>
                    </td>
                     
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        {{ strlen( $log->log ) > 60 ? substr( $log->log , 0, 60).'...' :  $log->log }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $log->log }}
                        </span>
                    </td>
                    <td>{{ $log->website }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->log_created)->format('d-m-y H:i:s')  }}</td>
                    <td>{{  $log->module_name  }}</td>
                    <td>{{  $log->controller_name  }}</td>
                    <td>{{  $log->action  }}</td>
                </tr>
@endforeach