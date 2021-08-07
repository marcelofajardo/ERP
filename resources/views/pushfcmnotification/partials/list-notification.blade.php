@foreach ($data as $key => $fcmnotification)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $fcmnotification->title }}</td>
                    <td>
                        {!! ($fcmnotification->url)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($fcmnotification->url, 10, '<a href="javascript:void(0)" class="readmore">...read more</a>'))."</span>":"" !!}
                        {!! ($fcmnotification->url)?"<span class='alltext' style='display:none;'>".$fcmnotification->url."<a href='javascript:void(0)' class='readless'>...read less</a></span>":"" !!}
                    </td>
                    <td>
                        {!! ($fcmnotification->body)?"<span class='lesstext'>".(\Illuminate\Support\Str::limit($fcmnotification->body, 10, '<a href="javascript:void(0)" class="readmore">...read more</a>'))."</span>":"" !!}
                        {!! ($fcmnotification->body)?"<span class='alltext' style='display:none;'>".$fcmnotification->body."<a href='javascript:void(0)' class='readless'>...read less</a></span>":"" !!}
                    </td>
                    <td>{{ $fcmnotification->sent_at }}</td>
                    <td>{{ $fcmnotification->sent_on }}</td>
                    <td>{{ $fcmnotification->username }}</td>
                    <td>{{ $fcmnotification->updated_at }}</td>
                    <td>
                        <a class="btn btn-image" href="{{ route('pushfcmnotification.edit',$fcmnotification->id) }}"><img src="/images/edit.png"/></a>
                        {!! Form::open(['method' => 'DELETE','route' => ['pushfcmnotification.destroy', $fcmnotification->id],'style'=>'display:inline']) !!}
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}
                        <a href="javascript:;" class="fcm-notification-list" data-id="{{ $fcmnotification->id }}"><i class="fa fa-globe"></i></a>
                    </td>
                </tr>
@endforeach