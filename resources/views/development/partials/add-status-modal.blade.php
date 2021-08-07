<div id="newStatusModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add new status</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form style="padding:10px;" action="{{ route('development.status.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}" required>

                    @if ($errors->has('name'))
                        <div class="alert alert-danger">{{$errors->first('name')}}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-secondary ml-3">Add Status</button>
            </form>
        </div>
    </div>
</div>