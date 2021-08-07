@foreach ($data as $key => $program)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $program->name.' '.$program->referrer_last_name }}</td>
                    <td>{{ $program->uri }}</td>
                    <td>{{ $program->credit }}</td>
                    <td>{{ $program->currency.' '.$program->referee_last_name }}</td> 
                    <td>{{ $program->lifetime_minutes }}</td>
                    <td>
                        <a class="btn btn-image" href="{{ route('referralprograms.edit',$program->id) }}"><img src="/images/edit.png"/></a>
                        {!! Form::open(['method' => 'DELETE','route' => ['referralprograms.destroy', $program->id],'style'=>'display:inline']) !!}
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}

                    </td>
                </tr>
@endforeach