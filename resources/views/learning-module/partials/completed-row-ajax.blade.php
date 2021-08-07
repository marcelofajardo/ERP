@foreach( $data['task']['completed'] as $task)
    @include("learning-module.partials.completed-row",compact('task'))
@endforeach