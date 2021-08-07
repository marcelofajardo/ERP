@foreach($ads as $ad)
                <tr>
                    <td>{{$ad->id}}</td>
                    <td>{{$ad->headline1}}</td>
                    <td>{{$ad->headline2}}</td>
                    <td>{{$ad->headline3}}</td>
                    <td>{{$ad->description1}}</td>
                    <td>{{$ad->description2}}</td>
                    <td>{{$ad->final_url}}</td>
                    <td>{{$ad->path1}}</td>
                    <td>{{$ad->path2}}</td>
                    <td>{{$ad->status}}</td>
                    <td>{{$ad->created_at}}</td>
                    <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['ads.deleteAd',$campaignId,$adGroupId,$ad['google_ad_id']],'style'=>'display:inline']) !!}
                <button type="submit" class="btn btn-image"><img src="/images/delete.png"></button>
                {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach