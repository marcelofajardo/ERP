{{--
$name
$label
$role
$action
$model?
--}}
@include('bookstack::components.custom-checkbox', [
    'name' => $name . '[' . $role->id . '][' . $action . ']',
    'label' => $label,
    'value' => 'true',
    'checked' => isset($model) && $model->hasRestriction($role->id, $action)
])