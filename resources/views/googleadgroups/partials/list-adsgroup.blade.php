@foreach($adGroups as $adGroup)
                <tr>
                    <td>{{$adGroup->id}}</td>
                    <td>{{$adGroup->ad_group_name}}</td>
                    <td>{{$adGroup->adgroup_google_campaign_id}}</td>
                    <td>{{$adGroup->google_adgroup_id}}</td>
                    <td>{{$adGroup->bid}}</td>
                    <td>{{$adGroup->status}}</td>
                    <td>{{$adGroup->created_at}}</td>
                    <td>
                    <form method="GET" action="/google-campaigns/{{$campaignId}}/adgroups/{{$adGroup['google_adgroup_id']}}/ads">
                    <button type="submit" class="btn-image">Ads</button>
                    </form>
                    {!! Form::open(['method' => 'DELETE','route' => ['adgroup.deleteAdGroup',$campaignId,$adGroup['google_adgroup_id']],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn-image"><img src="/images/delete.png"></button>
                    {!! Form::close() !!}
                    {!! Form::open(['method' => 'GET','route' => ['adgroup.updatePage',$campaignId,$adGroup['google_adgroup_id']],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn-image"><img src="/images/edit.png"></i></button>
                    {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach