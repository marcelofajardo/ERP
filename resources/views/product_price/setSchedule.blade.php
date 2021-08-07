<div class="modal fade" id="setSchedule" tabindex="-1" role="dialog" aria-labelledby="translateModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <form id="create_mailable" action="{{ route('saveGTmetrixCronType') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning new-mailable-alerts d-none" role="alert"></div>
                <div class="form-group">
                    <select name="type"  class="form-control" required>
                        <label for="mailableName">Select type</label>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
  </div>
</div>