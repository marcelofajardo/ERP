@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#repository-table').DataTable({
            "paging": true,
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
        <h2 class="page-heading">Github Repositories ({{ sizeof($repositories) }})</h2>
    </div>
</div>
<div class="container">
    <table id="repository-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Name</th>
                <th>Last Update </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($repositories as $repository)
            <tr>
                <td>{{$repository['id']}}</td>
                <td>{{$repository['name']}}</td>
                <td>{{$repository['updated_at']}}</td>
                <td>
                    <a class="btn btn-default" href="{{ url('/github/repos/'.$repository['id'].'/branches') }}">
                        <span title="Branches" class="glyphicon glyphicon-tasks"></span>
                    </a>
                    <a class="btn btn-default" href="{{ url('/github/repos/'.$repository['name'].'/users') }}">
                        <span title="Users" class="glyphicon glyphicon-user"></span>
                    </a>
                    <a class="btn btn-default" href="{{ url('/github/repos/'.$repository['id'].'/pull-request') }}">
                        <span title="Pull Request" class="glyphicon glyphicon-import"></span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection