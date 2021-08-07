<div id="priority_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Priority</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="priorityForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <strong>User:</strong>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    @if(auth()->user()->isReviwerLikeAdmin())
                                        <select class="form-control" name="user_id" id="priority_user_id">
                                            @foreach ($users as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{auth()->user()->name}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <strong>Remarks:</strong>
                            </div>
                            <div class="col-md-8">
                                @if(auth()->user()->isReviwerLikeAdmin())
                                    <div class="form-group">
                                        <textarea cols="45" class="form-control" name="global_remarkes"></textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="1%">ID</th>
                                    <th width="5%">Module</th>
                                    <th width="15%">Subject</th>
                                    <th width="67%">Issue</th>
                                    <th width="5%">Submitted By</th>
                                    <th width="2%">Action</th>
                                </tr>
                                <tbody class="show_issue_priority">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(auth()->user()->isReviwerLikeAdmin())
                        <button type="submit" class="btn btn-secondary">Confirm</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>