<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Show Updated History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-md-12" id="attribute-updated-history">
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
                    @if($records)
                        @foreach($records as $record)
                            <tr>
                                <td>{{$record->created_at}}</td>
                                <td>{{$record->old_value}}</td>
                                <td>{{$record->new_value}}</td>
                                <td>{{$record->user->name}}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>