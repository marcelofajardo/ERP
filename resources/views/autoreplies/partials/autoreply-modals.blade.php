<div id="autoReplyCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('autoreply.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Create Auto Reply</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Type:</strong>
                        <select class="form-control" name="type" required>
                            <option value="simple">Simple Text</option>
                            <option value="priority-customer">Priority Customer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <strong>Keyword:</strong>
                        <input type="text" name="keyword" class="form-control" value="{{ old('keyword') }}" placeholder="Enter Comma Separated Values">

                        @if ($errors->has('keyword'))
                            <div class="alert alert-danger">{{$errors->first('keyword')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Reply:</strong>
                        <textarea name="reply" class="form-control" rows="8" cols="80" required>{{ old('reply') }}</textarea>

                        @if ($errors->has('reply'))
                            <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Completion Date:</strong>
                        <div class='input-group date' id='sending-datetime'>
                            <input type='text' class="form-control" name="sending_time" value="{{ date('Y-m-d H:i') }}"/>

                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>

                        @if ($errors->has('sending_time'))
                            <div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Repeat:</strong>
                        <select class="form-control" name="repeat">
                            <option value="">Don't Repeat</option>
                            <option value="Every Day">Every Day</option>
                            <option value="Every Week">Every Week</option>
                            <option value="Every Month">Every Month</option>
                            <option value="Every Year">Every Year</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <strong>Active:</strong>
                        <input type="checkbox" class="form-control" name="is_active" value="1" checked>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Create</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="autoReplyEditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h4 class="modal-title">Update Auto Reply</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Type:</strong>
                        <select class="form-control" name="type" required id="autoreply_type">
                            <option value="simple">Simple Text</option>
                            <option value="priority-customer">Priority Customer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <strong>Keyword:</strong>
                        <input type="text" name="keyword" class="form-control" value="{{ old('keyword') }}" id="autoreply_keyword">

                        @if ($errors->has('keyword'))
                            <div class="alert alert-danger">{{$errors->first('keyword')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Reply:</strong>
                        <textarea name="reply" class="form-control" rows="8" cols="80" required id="autoreply_reply">{{ old('reply') }}</textarea>

                        @if ($errors->has('reply'))
                            <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Completion Date:</strong>
                        <div class='input-group date' id='edit-sending-datetime'>
                            <input type='text' class="form-control" name="sending_time" id="autoreply_sending_time" value=""/>

                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>

                        @if ($errors->has('sending_time'))
                            <div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Repeat:</strong>
                        <select class="form-control" name="repeat" id="autoreply_repeat">
                            <option value="">Don't Repeat</option>
                            <option value="Every Day">Every Day</option>
                            <option value="Every Week">Every Week</option>
                            <option value="Every Month">Every Month</option>
                            <option value="Every Year">Every Year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Active:</strong>
                        <input type="checkbox" class="form-control" name="is_active" value="1" id="autoreply_is_active" <?php echo old('is_active') == 1 ? 'checked' : '' ?>>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
