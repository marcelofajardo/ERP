@extends('layouts.app')
@section('title', 'Auto Refresh Page')
@section('content')
<div class="row margin-tb">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Auto Refresh Page (<span id="user_count">{{ $pages->count() }}</span>)</h2>
    </div>
</div>
<div class="row margin-tb">
    <div class="col-lg-12 margin-tb">
        <form method="get" action="?">
            <div class="form-group">
                <div class="col-md-2">
                    <input name="term" type="text" class="form-control" value="{{ request('term') }}" placeholder="Enter keyword" id="term">
                </div>
                <div class="col-md-2">
                   <button type="submit" class="btn btn-image"><img src="/images/filter.png"></button>
                   <button class="btn btn-secondary btn-create-auto-refresh-page">Create Auto Refresh Page</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row margin-tb" style="margin-top: 5px;">
    <div class="col-lg-12 margin-tb">
        
    </div>
</div>
<div class="table-responsive mt-3">
    <table class="table table-bordered" id="category-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Page</th>
                <th>Time</th>
                <th>User</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
             @include('auto-refresh-page.partials.data')
        </tbody>
    </table>
</div>
{!! $pages->appends(Request::except('page'))->links() !!}


<div class="modal fade" id="create-auto-refresh-page" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/system/auto-refresh/create" method="post">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Auto Refresh Page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="createsizeform">
                        <div class="col-md-10">
                            <label for="create-page">Page Url</label>
                            <input type="text" class="form-control nav-link" id="create-page" name="page" placeholder="Page Url" style="margin-top : 1%;">
                        </div>
                        <div class="col-md-10">
                            <label for="create-time">Time (in second)</label>
                            <input type="text" class="form-control nav-link" id="create-time" name="time" placeholder="Time" style="margin-top : 1%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit-auto-refresh-page" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
    </div>
</div>
@endsection
@section('scripts')

<script>
$(document).on("click",".btn-create-auto-refresh-page",function(e) {
    e.preventDefault();
    $("#create-auto-refresh-page").modal("show");
});

$(document).on("click",".edit-page",function(e) {
    e.preventDefault();
    var $this = $(this);
    $.ajax({
        url:'/system/auto-refresh/'+$this.data("id")+"/edit",
        success:function(result){
            $("#edit-auto-refresh-page").find(".modal-dialog").html(result);
            $("#edit-auto-refresh-page").modal("show");
        },
        error:function(exx){
            console.log(exx)
        }
    })
}); 
</script>
@endsection
