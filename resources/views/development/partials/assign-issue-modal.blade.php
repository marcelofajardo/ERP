<div id="assignIssueModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Assign Issue</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="assignIssueForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <strong>User:</strong>
                        <select class="form-control" name="user_id" id="user_field" required>
                            @foreach ($users as $id => $name)
                                <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('user_id'))
                            <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>