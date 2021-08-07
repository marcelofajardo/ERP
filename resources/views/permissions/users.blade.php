@extends('layouts.app')


@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style type="text/css">
        .dtHorizontalExampleWrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        #dtHorizontalExample th, td {
            white-space: nowrap;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before {
            bottom: .5em;
        }
        .but{
            background-color: lightblue;
            border-radius: 29px;
            border: 0;
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Grand Permission To Users</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('permissions.index') }}"> Back</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <div class="table-wrapper-scroll-y my-custom-scrollbar">

                    <table id="dtHorizontalExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Users </th>
                            @foreach($permissions as $permission)
                                <th>{{ $permission->name }}</th>
                            @endforeach
                            >
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{++$i }}</td>
                                <td><a href="/users/{{ $user->id }}/edit">{{ $user->name }} ({{ count($user->permissions) }})</a></td>
                                @foreach($permissions as $permission)
                                    <td>
                                        @if(in_array($permission->name, $user->permissions->pluck('name')->toArray()))
                                            <button class="but" onclick="activatePermission({{$permission->id}},{{$user->id}},1)" style="background-color: lightgreen !important;""><img src='/images/icons-checkmark.png' }}' height="10" width="10"/>
                                            </button>
                                        @else
                                            <button class="but" onclick="activatePermission({{$permission->id}},{{$user->id}},0)"><img src='/images/icons-delete.png' }}' height="10" width="10"/>
                                            </button>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                   
                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dtHorizontalExample').DataTable({
                "scrollX": true
            });
            $('.dataTables_length').addClass('bs-select');
        });

        function activatePermission($permission_id , $user_id , $is_Active) {
            if($permission_id == null && $user_id == null){
                alert('Failed To Update')
            }else{
            $.ajax({
                type: "POST",
                url: "/api/users/updatePermission",
                data: {"_token": "{{ csrf_token() }}","user_id": $user_id , "permission_id" : $permission_id ,"is_active" : $is_Active },
                dataType: "json",
                success: function(message) {
                    alert(message.message);
                    location.reload(true);
                }, error: function(){
                    alert('Failed adding Permission');
                }

            });
            }
        }
    </script>
@endsection
