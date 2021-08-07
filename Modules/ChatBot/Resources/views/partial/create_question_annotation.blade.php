<div class="modal" id="create-question-annotation" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo route("chatbot.question.annotation.save"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Select Keyword</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      		
	      		<input type="hidden" id="question-example-id" name="question_example_id" value="">
	      		<input type="hidden" id="keyword-value" name="keyword_value" value="">
	      		<input type="hidden" id="start-char-range" name="start_char_range" value="">
	      		<input type="hidden" id="end-char-range" name="end_char_range" value="">

	      		@include('chatbot::partial.form.select',["params" => ["title" => "keyword", "name" => "chatbot_question_id" , "options" => [] , "class" => "search-keyword" , "placeholder" => "Select keyword for annotation"]] )
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary form-save-btn">Save changes</button>
	      </div>
	  </form>
    </div>
  </div>
</div>
