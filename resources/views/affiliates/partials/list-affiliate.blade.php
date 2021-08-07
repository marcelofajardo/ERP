@foreach ($data as $key => $affiliate)
                <tr>
                    <td><input type="checkbox" id ="affilate_multi_select" name="affilate_multi_select[]" value="{{$affiliate->id}}">{{ ++$i }}</td>
                    <td>{{ $affiliate->first_name }} {{ $affiliate->last_name }}</td>
                    <td>{{ $affiliate->phone }}</td>
                    <td>{{ $affiliate->source }}</td>
                    <td>{{ $affiliate->emailaddress }}</td>
                    <td>{{ $affiliate->unique_visitors_per_month}}</td> 
                    <td>{{ $affiliate->page_views_per_month }}</td>
                    <td>{{ $affiliate->facebook_followers }}</td>
                    <td>{{ $affiliate->instagram_followers }}</td>
                    <td>{{ $affiliate->youtube_followers }}</td>
                    <td>{{ $affiliate->linkedin_followers }}</td>
                    <td>{{ $affiliate->pinterest_followers }}</td>
                    <td>{{ $affiliate->country }}</td>
                    <td>{{ ucwords($affiliate->type) }}</td>
                    <td>
                        {!! Form::open(['method' => 'POST','route' => ['affiliates.destroy'],'style'=>'display:inline']) !!}
                        <input type="hidden" value="{{$affiliate->id}}" name="id">
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}
                        <a href="javascript:;" class="btn btn-image get-details" data-id="{{$affiliate->id}}"><img src="/images/view.png" style="cursor: nwse-resize;"></a>
                    </td>
                </tr>
@endforeach