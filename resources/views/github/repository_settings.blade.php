@extends('layouts.app')

@section('content')
<style>
    #branches-table_filter {
        text-align: right;
    }
</style>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#branches-table').DataTable({
            "ordering": true,
            "info": false
        });
    });
</script>

<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
    @endif

    function confirmMergeToMaster(branchName, url) {
        let result = confirm("Are you sure you want to merge " + branchName + " to master?");
        if (result) {
            window.location.href = url;
        }
    }
</script>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading"><i>{{ $repository->name }}</i> branches ({{sizeof($branches)}})</h2>
    </div>
</div>

@if(Session::has('message'))
<div class="alert alert-warning alert-dismissible">
    {{Session::get('message')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


<div class="container">
    <div class="text-right">
        <a class="btn btn-sm btn-secondary" href="{{ url('/github/repos/'.$repository->id.'/deploy?branch=master') }}">Deploy Master</a>
        <a class="btn btn-sm btn-secondary" href="{{ url('/github/repos/'.$repository->id.'/deploy?branch=master') }}&composer=true">Deploy Master + Composer</a>
    </div>
    <table id="branches-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Behind By</th>
                <th>Ahead By</th>
                <th>Last Commit by</th>
                <th>Last Updated</th>
                <th>Deployment</th>
                <th>Merge</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{$branch->branch_name}}</td>
                <td>{{$branch->behind_by}}</td>
                <td>{{$branch->ahead_by}}</td>
                <td>{{$branch->last_commit_author_username}}</td>
                <td>{{$branch->last_commit_time}}</td>
                <td>
                    @if($branch->branch_name == $current_branch)
                    <span class="badge badge-pill badge-light">Deployed</span>
                    @else
                    <a class="btn btn-sm btn-secondary" href="{{ url('/github/repos/'.$repository->id.'/deploy?branch='.urlencode($branch->branch_name)) }}">Deploy</a>
                        @if($repository->name == "erp")
                            <a class="btn btn-sm btn-secondary" href="{{ url('/github/repos/'.$repository->id.'/deploy?branch='.urlencode($branch->branch_name)) }}&composer=true">Deploy + Composer</a>
                        @endif
                    @endif
                </td>
                <td>
                    <div>
                        <a class="btn btn-sm btn-secondary" href="{{url('/github/repos/'.$repository->id.'/branch/merge?source=master&destination='.urlencode($branch->branch_name))}}">
                            Merge from master
                        </a>
                    </div>
                    <div style="margin-top: 5px;">
                        <button class="btn btn-sm btn-secondary" onclick="confirmMergeToMaster('{{$branch->branch_name}}','{{url('/github/repos/'.$repository->id.'/branch/merge?destination=master&source='.urlencode($branch->branch_name))}}')">
                            Merge into master
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection