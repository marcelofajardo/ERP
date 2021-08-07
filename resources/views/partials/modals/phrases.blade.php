<div id="addPhrases" class="modal fade" role="dialog">
  <div class="modal-dialog <?php echo (!empty($type) && $type = 'scrap') ? 'modal-lg' : ''  ?>">

    <!-- Modal conten1t-->
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Add Intent</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form method="post" action="<?php echo route('chatbot.question.saveAjax'); ?>" id="add-phrases">
          {{csrf_field()}}

          <div class="form-group">
            <?php echo Form::select("group_id",[],null,["class" => "form-control select-phrase-group", "id" => "select-phrase-group-box" , "style"=> "width:100%", "placeholder" => "Choose Existing"]); ?>
          </div>

          <div class="form-group">
            <?php echo Form::select("category_id",[],null,["class" => "form-control select-phrase-category", "id" => "select-phrase-category" , "style"=> "width:100%", "placeholder" => "Choose Category"]); ?>
          </div>

          <div class="form-group">
            <?php echo Form::text("question",null,["class" => "form-control question", "placeholder" => "Enter User Intent"]); ?>
          </div>
          <div class="form-group">
            <?php echo Form::text("suggested_reply",null,["class" => "form-control suggested_reply", "placeholder" => "Enter Suggested reply"]); ?>
          </div>
          <div class="form-group">
            <select name="erp_or_watson" id="" class="form-control">
              <option value="">Push to</option>
              <option value="watson">Watson</option>
              <option value="erp">Erp</option>
            </select>
          </div>
          <button type="button" class="btn btn-secondary btn-block mt-2" id="add-phrases-btn">Add</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
