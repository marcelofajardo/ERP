<div id="auto-reply-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Intent</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo route('reply.create.chatbot_questions'); ?>" id="auto-reply-popup-form" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="intent_reply_id" id="reply_id_edit">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Intent/Entity</strong>
                            <select class="form-control search-intent" name="intent_name" placeholder="Select Intent"></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>User Intent</strong>
                            <input class="form-control question-insert" name="question" id="question" placeholder="Insert user intent"></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Suggested Reply</strong>
                            <input class="form-control question-insert" name="intent_reply" id="intentReply" placeholder="Insert your Reply"></select>
                        </div>
                    </div>
                    <!-- <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Intent Model</strong>
                            <input class="form-control question-insert" name="intent_model" id="intentModel" placeholder=""></select>
                        </div>
                    </div> -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Category</strong>
                            <select class="form-control" id="intentCategory" name="intent_category_id" required>
                                <option value="">Select Category</option>
                              @foreach ($reply_categories as $categorysort)
                                <option value="{{$categorysort->id}}">{{$categorysort->name}}</option>
                              @endforeach
                            </select>
                            @if ($errors->has('model'))
                                <div class="alert alert-danger">{{$errors->first('model')}}</div>
                            @endif
                        </div>
                    </div>  
                    <div class="form-group">
                        <button class="btn btn-secondary save-task-window">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>