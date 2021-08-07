<!-- Modal -->
<div id="quick-instruction-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Quick Instruction</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            @php
                $pageInstruction = \App\PageInstruction::where("page",request()->fullUrl())->first()
            @endphp
            <textarea id="editor-instruction-content" data-url="{{ route('instructionCreate') }}" data-page="{{ request()->fullUrl() }}" class="editor-instruction-content" name="instruction">{{ ($pageInstruction) ? $pageInstruction->instruction : "" }}</textarea>
        </div>
    </div>
</div>