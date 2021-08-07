<!-- Modal -->
<div id="quick_notes_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Notes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <!-- data-url="{{ route('notesCreate') }}" -->
                <textarea id="editor-notes-content"  class="editor-notes-content" name="instruction"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn_save_notes btn btn-secondary">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>