@foreach( $data['task']['completed'] as $task)
    @include("task-module.partials.completed-row",compact('task'))
@endforeach