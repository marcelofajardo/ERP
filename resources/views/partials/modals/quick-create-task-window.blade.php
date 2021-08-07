<div id="quick-create-task" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Task / Dev Task</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo route('task.create.task.shortcut'); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="task_type">Task Type</label>
                        <?php echo Form::select("task_type",\App\Task::TASK_TYPES,null,["class" => "form-control select2-vendor type-on-change","style" => "width:100%;"]); ?>
                    </div>
                    <!-- <div class="form-group normal-subject">
                        <label for="task_subject">Task Subject</label>
                        <input type="text" name="task_subject" id="task_subject" class="form-control"/>
                    </div> -->
                    <div class="form-group discussion-task-subject">
                        <label for="task_subject">Task Subject</label>
                        <select name="task_subject" class="form-control select2-discussion add-discussion-subjects" style="width:100%;">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="task_detail">Task Detail</label>
                        <input type="text" name="task_detail" id="task_detail" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="task_asssigned_to">Assigned to</label>
                        <?php echo Form::select("task_asssigned_to",['' => ''],null,["class" => "form-control select2-vendor task_asssigned_to globalSelect2" ,"style" => "width:100%;", 'data-ajax' => route('select2.user'), 'data-placeholder' => 'Assign to']); ?>
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