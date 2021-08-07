@foreach($data['task']['pending'] as $task)
    @include("task-module.partials.pending-row",compact('task'))
@endforeach