@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Attribute Replacement</h2>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered">
                <form action="{{ action('AttributeReplacementController@store') }}" method="post">
                    @csrf
                    <tr>
                        <th>
                            <select style="background: #FFFFFF;border: none; padding: 5px;" name="field_identifier" id="field_identifier">
                                <option value="">Select attribute...</option>
                                <option value="name">Name</option>
                                <option value="short_description">Short Description</option>
                                <option value="composition">Composition</option>
                            </select>
                        </th>
                        <th>
                            <input style="padding: 5px; border: none" type="text" name="first_term" id="first_term" placeholder="Term...">
                        </th>
                        <th>
                            <input style="padding: 5px; border: none" type="text" name="replacement_term" id="replacement_term" placeholder="Replace with...">
                        </th>
                        <th>
                            <input style="padding: 5px; border: none" type="text" name="remarks" id="remarks" placeholder="Remark...">
                        </th>
                        <th>
                            <button class="btn btn-secondary btn-xs">Add</button>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                </form>
                    <tr>
                        <th>Attribute</th>
                        <th>Subject</th>
                        <th>Replace With</th>
                        <th>Remark</th>
                        <th>Authorization</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php $last_value = ''; ?>
                    @foreach($replacements as $replacement)
                        <tr>
                            <td style="{{ $replacement->field_identifier==$last_value ? 'border: 0 !important;' : '' }}">
                                {{ $replacement->field_identifier!=$last_value ? $replacement->field_identifier : '' }}
                            </td>
                            <?php $last_value = $replacement->field_identifier; ?>
                            <td>{{ $replacement->first_term }}</td>
                            <td>{{ $replacement->replacement_term ?? 'Empty Value' }}</td>
                            <td>{{ $replacement->remarks }}</td>
                            <td>
                                 @if(auth()->user()->isAdmin())
                                    @if($replacement->user)
                                        {{ $replacement->user->name }}
                                    @else
                                        <button class="btn btn-xs btn-secondary authorize-entry" data-id="{{$replacement->id}}">
                                            Authorize
                                        </button>
                                        <span class="hidden" id="name_{{ $replacement->id  }}">{{ Auth::user()->name }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $replacement->created_at->format('Y-m-d') }}</td>
                            <td>
                                 @if(auth()->user()->isAdmin())
                                    <form method="post" action="{{ action('AttributeReplacementController@destroy', $replacement->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-danger btn-xs">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @if (Session::has('message'))\
        <script>
            toastr['success']('{{Session::get('message')}}', 'Success');
        </script>
    @endif

    <script>
        $(document).on('click', '.authorize-entry', function() {
            let id = $(this).attr('data-id');
            let self = this;
            $.ajax({
                url: '{{ action('AttributeReplacementController@show', '') }}'+ '/'+ id,
                success: function() {
                    toastr['success']('Authorized successfully!');
                    $(self).hide();
                    $('#name_'+id).removeClass('hidden');
                }
            });
        });
    </script>
@endsection