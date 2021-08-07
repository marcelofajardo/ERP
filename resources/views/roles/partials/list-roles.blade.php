@foreach ($roles as $key => $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a class="btn btn-image" href="{{ route('roles.show',$role->id) }}"><img src="/images/view.png" /></a>
                        @if(auth()->user()->isAdmin())
                            <a class="btn btn-image" href="{{ route('roles.edit',$role->id) }}"><img src="/images/edit.png" /></a>
                        @endif
                        {{--@can('role-delete')
                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        @endcan--}}
                    </td>
                </tr>
            @endforeach