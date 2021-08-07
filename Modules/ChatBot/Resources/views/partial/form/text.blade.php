<div class="form-group">
	<label for="{{ $params['name'] }}">{{ $params['title'] }}</label>
	<?php echo Form::text($params['name'], isset($params['value']) ?: null, ["class" => "form-control", "placeholder" => $params['placeholder']]); ?>
</div>