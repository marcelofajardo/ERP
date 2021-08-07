<div id="chat-list-history{{ $note->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remarks</h4>
            </div>
            <div class="modal-body" style="background-color: #999999;">
                <div class="speech-wrapper" id="note-remark-details{{ $note->id }}">
                    @foreach ($note->subnotes as $subnote)
                    <div class="row">
                        <div class="col">
                            {{ $subnote->remark }}
                        </div>
                        <div class="col d-flex align-items-center justify-content-left">
                            {{ $subnote->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>