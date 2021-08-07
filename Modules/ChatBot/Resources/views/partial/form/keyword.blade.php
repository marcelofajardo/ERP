<div class="form-group">
	<label for="Keyword">Entity</label>
	<?php echo Form::text("keyword",isset($keyword) ?: "", ["class" => "form-control" , "placeholder" => "Enter entity name"]); ?>
</div>