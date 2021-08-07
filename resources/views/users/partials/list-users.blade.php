@foreach ($data as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td><span class="user-status {{ $user->isOnline() ? 'is-online' : '' }}"></span> {{ str_replace( '_' , ' ' ,$user->name) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <button data-toggle="tooltip" type="button" class="btn btn-xs btn-image load-communication-modal" data-object='user' data-id="{{ $user->id }}" title="Load messages"><img src="/images/chat.png" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" alt=""></button>
                        @if (Auth::id() == $user->id)
                            <a class="btn btn-image" href="{{ route('users.show',$user->id) }}"><img src="/images/view.png"/></a>
                        @else
                            <a class="btn btn-image" href="{{ route('users.edit',$user->id) }}"><img src="/images/edit.png"/></a>
                        @endif

                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                        {!! Form::close() !!}
                        <a href="{{ action('UserActionsController@show', $user->id) }}">Info</a>
                        <a title="Payments" class="btn btn-image" href="/users/{{$user->id}}/payments"><span class="glyphicon glyphicon-usd"></span></a>
                    </td>
                </tr>
@endforeach