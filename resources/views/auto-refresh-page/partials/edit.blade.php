<form action="/system/auto-refresh/{{$autoRefresh->id}}/update" method="post">
    {{ csrf_field() }}
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Auto Refresh Page</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row" id="createsizeform">
                <div class="col-md-10">
                    <label for="create-page">Page Url</label>
                    <input type="hidden" name="user_id" value="{{ $autoRefresh->user_id }}">
                    <input type="text" class="form-control nav-link" id="create-page" name="page" value="{{ $autoRefresh->page }}" placeholder="Page Url" style="margin-top : 1%;">
                </div>
                <div class="col-md-10">
                    <label for="create-time">Time (in second)</label>
                    <input type="text" class="form-control nav-link" id="create-time" name="time" value="{{ $autoRefresh->time }}"  placeholder="Time" style="margin-top : 1%;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save changes</button>
        </div>
    </div>
</form>