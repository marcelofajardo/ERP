@foreach ($data as $key => $refferal)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $refferal->referrer_first_name.' '.$refferal->referrer_last_name }}</td>
                    <td>{{ $refferal->referrer_email }}</td>
                    <td>{{ $refferal->referrer_phone }}</td>
                    <td>{{ $refferal->referee_first_name.' '.$refferal->referee_last_name }}</td> 
                    <td>{{ $refferal->referee_email }}</td>
                    <td>{{ $refferal->referee_phone }}</td>
                    <td>{{ $refferal->website }}</td>
                    <td>
                        {!! Form::open(['method' => 'DELETE','route' => ['referfriend.destroy', $refferal->id],'style'=>'display:inline']) !!}
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}

                    </td>
                </tr>
@endforeach