<div id="start-time" class="start-time modal" role="dialog">
    <form action="{{ route('landing-page.updateTime') }}" method="POST">
        {{ csrf_field() }}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Time</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" class="form-control" readonly>
                    <div class="form-group">
                        <label for="name">Start Time</label>
                        <input type="text" name="start_date" class="form-control" id="pr-start-time" placeholder="Enter Start Date">
                    </div>
                    <div class="form-group">
                        <label for="name">End Time</label>
                        <input type="text" name="end_date" class="form-control" id="pr-end-time" placeholder="Enter Start Date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary submit-platform">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>