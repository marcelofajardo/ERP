@foreach($googleadsaccount as $googleadsac)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$googleadsac->account_name}}</td>
                    <td>{{$googleadsac->store_websites}}</td>
                    <td>{{$googleadsac->config_file_path}}</td>
                    <td>{{$googleadsac->notes}}</td>
                    <td>{{$googleadsac->status}}</td>
                    <td>{{$googleadsac->created_at}}</td>
                    <td>
                        <a href="/google-campaigns/ads-account/update/{{$googleadsac->id}}" class="btn-image"><img src="/images/edit.png"></a>
                        <a href="/google-campaigns?account_id={{$googleadsac->id}}" class="btn btn-sm">create campaign</a>
                    </td>
                </tr>
                @endforeach