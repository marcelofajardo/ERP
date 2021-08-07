@foreach(  $data['task']['statutory_not_completed'] as $task)
    @include("task-module.partials.statutory-row",compact('task'))
@endforeach