<div id="meeting_time_modal" class="modal fade" role="dialog" style="padding: 0 !important;">
    <div class="modal-dialog" style="width: 100%;max-width: none;margin: 0;">
        <!-- Modal content-->
        <div class="modal-content" style="border: 0;border-radius: 0;">
            <div class="modal-header">
            <h5 class="modal-title">Other time history</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="add-time-form" style="padding:10px 150px 0px 150px;    margin-bottom: 0px;">
            @csrf
                <input type="hidden" id="meeting_hidden_task_id" name="task_id" value="">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="number" placeholder="add time in minutes"  name="time" class="form-control">
                        </div>
                    </div>
                    @if (auth()->user()->isAdmin())
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="user_type" id="" class="form-control">
                                <option value="">Select</option>
                                <option value="developer">Developer</option>
                                <option value="lead">Lead developer</option>
                                <option value="tester">Tester</option>
                            </select>
                        </div>
                    </div>
                    @else 
                    <input type="hidden" name="user_type" id="hidden_type">
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="timing_type" id="" class="form-control">
                                <option value="">Select</option>
                                <option value="meeting">Meeting</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <textarea name="note" cols="30" rows="1" class="form-control" placeholder="add note"></textarea>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                        <button type="submit" class="btn btn-xs btn-secondary mt-2" id="addTimeButton">Add Time</button>
                        </div>
                    </div>
                </div>
            </form>
            @if (auth()->user()->isAdmin())
            <form id="search-time-form" style="padding:0px 150px 0px 150px;margin-bottom: 0px;">
            @csrf
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                <label for="">User</label>
                    <select name="user_type" id="user_type_id" class="form-control">
                        <option value="">Select</option>
                        <option value="developer">Developer</option>
                        <option value="lead">Lead developer</option>
                        <option value="tester">Tester</option>
                    </select>
                </div>
                </div>
                <div class="col-md-4">
                <div class="form-group">
                <label for="">Time type</label>
                    <select name="timing_type" id="timing_type_id" class="form-control">
                        <option value="">Select</option>
                        <option value="meeting">Meeting</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                </div>

                <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px;margin-top: 24px;">
                <label for=""></label>
                <button type="submit" class="btn btn-xs btn-secondary mt-2" >Search</button>
                </div>
                </div>
            </div>
            </form>
            @endif
            <form action="" id="approve-meeting-time-btn" method="POST">
                @csrf
                <div class="modal-body">
                <div class="row">
                <input type="hidden" name="developer_task_id" id="developer_task_id">

                    <div class="col-md-12" id="meeting_time_div">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Timing type</th>
                                    <th>User</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Updated by</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (auth()->user()->isAdmin())
                        <p>Developer time approved : <span id="developer_approved_time"></span></p>
                        <p>Lead Dev time approved : <span id="master_approved_time"></span></p>
                        <p>Tester time approved : <span id="tester_approved_time"></span></p>
                @endif
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