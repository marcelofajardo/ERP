@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#repository-table').DataTable({
            "ordering": true,
            "info": false
        });
    });
</script>
<style>
    #repository-table_filter {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Repositories for user: {{$userDetails['user']['username']}} ({{sizeof($userDetails['repositories'])}})</h2>
    </div>
</div>
<div class="container">
    <table id="repository-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Rights</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userDetails['repositories'] as $repository)
            <tr>
                <td>{{$repository['name']}}</td>
                <td>{{$repository['rights']}}</td>
                <td>
                    <a href="/github/repos/{{$repository['id']}}/users/{{$userDetails['user']['username']}}/remove" class="btn btn-sm btn-primary">Revoke</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection