@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#user-table').DataTable({
            "ordering": true,
            "info": false
        });
    });
</script>
<style>
    #user-table_filter {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading"><i>{{ $repoName }}</i> users ({{sizeof($users)}})</h2>
    </div>
</div>
<div class="text-right">
    <a href="/github/repos/{{ $repoName }}/users/add" class="btn btn-primary">Add User</a>
</div>
<div class="container">
    <table id="user-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Permission</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->username}}</td>
                <td>{{$user->pivot->rights}}</td>
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ url('github/repo_user_access/'.$user->pivot->id.'/remove')}}">Remove</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function modifyAccess(username, access) {
        console.log(githubUserId);
        console.log(userId);
        if (userId) {
            var xhr = new XMLHttpRequest();
            var url = "modifyUserAccess";
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };

            var dataObj = {
                user_name: userId,
                access: access,
                repository_name: "{{ $repoName }}",
                _token: "{{csrf_token()}}"
            };

            console.log(dataObj);

            var data = JSON.stringify(dataObj);
            xhr.send(data);
        }
    }
</script>
@endsection