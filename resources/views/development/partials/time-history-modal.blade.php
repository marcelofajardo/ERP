<!-- <div class="modal" tabindex="-1" role="dialog" id="time_history_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Estimated Time History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="approve-time-btn" method="POST">
                @csrf
                <div class="modal-body">
                <div class="row">
                <input type="hidden" name="developer_task_id" id="developer_task_id">

                    <div class="col-md-12" id="time_history_div">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
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
</div> -->
<div id="date_history_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Estimated Date History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="approve-date-btn" method="POST">
                @csrf
                <div class="modal-body">
                <div class="row">
                <input type="hidden" name="developer_task_id" id="developer_task_id">

                    <div class="col-md-12" id="time_history_div">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Updated by</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
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


<div id="status_history_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Status History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
                <div class="row">
                    <div class="col-md-12" id="status_history_div">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Old Status</th>
                                    <th>New Status</th>
                                    <th>Updated by</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="time_history_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Estimated Time History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="approve-time-btn" method="POST">
                @csrf
                <div class="modal-body">
                <div class="row">
                <input type="hidden" name="developer_task_id" id="developer_task_id">

                    <div class="col-md-12" id="time_history_div">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Updated by</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                <!--Purpose : add style - DEVTASK-4354 -->
                <div class="modal-footer1 mx-5 d-flex justify-content-between" style="margin-bottom:10px;">
                    <div class="b">
                    @if(\Auth::user()->isAdmin())
                        <button type="button" class="btn btn-secondary remind_btn" disabled>Remind</button>
                        <button type="button" class="btn btn-secondary revise_btn">Revise</button>
                    @endif
                    </div>
                    <div class="a">
                        <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
                        @if(auth()->user()->isReviwerLikeAdmin())
                            <button type="submit" class="btn btn-secondary confirm_btn">Confirm</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>