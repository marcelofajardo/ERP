<div class="form-group">
	<label for="value">{{ $params['title'] }}</label>
	<?php echo Form::select($params['name'],$params['options'], isset($params['value']) ?: null, ["class" => $params['class'], "placeholder" => $params['placeholder'] , "style" => "width:100%"]); ?>
</div>