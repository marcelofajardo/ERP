@foreach($data['task']['pending'] as $task)
    @include("learning-module.partials.pending-row",compact('task'))
@endforeach