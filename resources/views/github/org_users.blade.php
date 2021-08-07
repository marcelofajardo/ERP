@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#org-users-table').DataTable({
            "ordering": true,
            "info": false
        });
    });
</script>
<style>
    #org-users-table_filter {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">All Github users</h2>
    </div>
</div>
<div class="container">
    <table id="org-users-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>App User</th>
                <th>Repositories</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user['id']}}</td>
                <td>{{$user['username']}}</td>
                <td>
                    <select onchange="assignUser({{$user['id']}}, this.value)">
                        <option value="0">Unassigned</option>
                        @foreach($platformUsers as $platformUser)
                        <option value="{{ $platformUser->id }}" {{ (isset($user->platformUser) && $platformUser->id == $user->platformUser->id) ? 'selected': '' }}>
                            {{ $platformUser->name }} ( {{$platformUser->email}} )
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    @foreach($user->repositories as $repository)
                    <span class="badge badge-pill badge-light">{{$repository->name}}</span>
                    @endforeach
                </td>
                <td>
                    <a href="/github/users/{{$user->id}}">Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('scripts')
<script>
    function assignUser(githubUserId, userId) {
        console.log(githubUserId);
        console.log(userId);
        if (userId) {
            var xhr = new XMLHttpRequest();
            var url = "linkUser";
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };

            var dataObj = {
                user_id: userId,
                github_user_id: githubUserId,
                _token: "{{csrf_token()}}"
            };

            console.log(dataObj);

            var data = JSON.stringify(dataObj);
            xhr.send(data);
        }
    }
</script>
@endsection