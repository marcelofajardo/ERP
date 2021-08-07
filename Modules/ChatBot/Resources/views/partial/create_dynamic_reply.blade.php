<div class="modal" id="create-dynamic-reply" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo route("chatbot.question.save_dymanic_reply"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Create Intent for dynamic reply</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
            <div class="form-group">
                <label for="value">Name of the intent</label>
                <?php echo Form::text("value",isset($value) ?: "", ["class" => "form-control" , "placeholder" => "Enter your value"]); ?>
            </div>
            <div class="form-group">
                <label for="value">User intent</label>
                <?php echo Form::text("question",null, ["class" => "form-control" , "placeholder" => "Enter your value"]); ?>
            </div>
            <div class="form-group">
                <label for="value">Intent Category</label>
                <select name="category_id" id="" class="form-control">
                    <option value="">Select</option>
                    @foreach($allCategoryList as $cat)
                    <option value="{{$cat['id']}}">{{$cat['text']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="value">Select watson account</label>
                <select name="watson_account" class="form-control" required>
                    <option value="0">All account </option>
                    @foreach($watson_accounts as $acc)
                    	<option value="{{$acc->id}}" > {{$acc->id}} - {{$acc->storeWebsite->title}} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <strong>Reply:</strong>
                <textarea name="suggested_reply" class="form-control" rows="8" cols="80" required>{{ old('suggested_reply') }}</textarea>
            </div>
            <div class="form-group">
                <label for="value">Push to</label>
                <select name="erp_or_watson" id="" class="form-control">
                    <option value="watson">Watson</option>
                    <option value="erp">ERP</option>
                </select>
            </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary form-task-btn">Save</button>
	      </div>
	  </form>
    </div>
  </div>
</div>