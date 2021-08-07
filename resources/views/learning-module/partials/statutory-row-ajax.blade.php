@foreach(  $data['task']['statutory_not_completed'] as $task)
    @include("learning-module.partials.statutory-row",compact('task'))
@endforeach