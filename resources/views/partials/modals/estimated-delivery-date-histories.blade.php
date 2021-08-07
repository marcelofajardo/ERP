<div id="estiate_del-history-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Estimated Delivery Date History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                <div class="row">
                <input type="hidden" name="lead_developer_task_id" id="lead_developer_task_id">

                    <div class="col-md-12" id="lead_time_history_div">
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
                                @if($estimated_delivery_histories)
                                @foreach($estimated_delivery_histories as $delDateHistory)
                                <tr>
                                <td>{{$delDateHistory->created_at}}</td>
                                <td>{{$delDateHistory->old_value}}</td>
                                <td>{{$delDateHistory->new_value}}</td>
                                <td>{{$delDateHistory->name}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>