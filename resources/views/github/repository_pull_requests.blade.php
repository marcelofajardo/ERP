@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"> </script>
<script>
    $(document).ready(function() {
        $('#pull-request-table').DataTable({
            "paging": false,
            "ordering": true,
            "info": false
        });
    });

    function confirmMergeToMaster(branchName, url) {
        let result = confirm("Are you sure you want to merge " + branchName + " to master?");
        if (result) {
            window.location.href = url;
        }
    }
</script>
<style>
    #pull-request-table_filter {
        text-align: right;
    }
</style>

<div class="row">
    <div class="col-lg-12 margin-tb page-heading">
        <h2>Github Repository: {{$repository->name}}</h2>
        <h3>Pull Request ({{sizeof($pullRequests)}})</h3>
    </div>
</div>
<div class="container">
    <table id="pull-request-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Number</th>
                <th>Title</th>
                <th>Branch</th>
                <th>Deploy</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pullRequests as $pullRequest)
            <tr>
                <td>{{$pullRequest['id']}}</td>
                <td>{{$pullRequest['title']}}</td>
                <td>{{$pullRequest['source']}}</td>
                <td>
                    <a class="btn btn-sm btn-secondary" href="{{ url('/github/repos/'.$repository->id.'/deploy?branch='.urlencode($pullRequest['source'])) }}">Deploy</a>
                </td>
                <td>
                    <div>
                        <a class="btn btn-sm btn-secondary" href="{{url('/github/repos/'.$repository->id.'/branch/merge?source=master&destination='.urlencode($pullRequest['source']))}}">
                            Merge from master
                        </a>
                    </div>
                    <div style="margin-top: 5px;">
                        <button class="btn btn-sm btn-secondary" onclick="confirmMergeToMaster('{{$pullRequest["source"]}}','{{url('/github/repos/'.$repository->id.'/branch/merge?destination=master&source='.urlencode($pullRequest['source']))}}')">
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