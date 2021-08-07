<div id="archive-list-history{{ $note->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Archive Remarks</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                    <div class="speech-wrapper" id="archive-remark-details{{ $note->id }}">
                        @foreach ($note->archiveSubnotes as $subnote)
                        <div class="bubble alt">
                            <div class="txt">
                            <p class="name alt"></p>
                            <p class="message">{{ $subnote->remark }}
                            </p>
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