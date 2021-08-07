<div class="entity-list">
    @if(count($entities) > 0)
        @foreach($entities as $index => $entity)

            @include('bookstack::partials.entity-list-item', ['entity' => $entity, 'showPath' => true])
            @if($index !== count($entities) - 1)
                <hr>
            @endif

        @endforeach
    @else
        <p class="text-muted text-large p-xl">
            {{ trans('bookstack::common.no_items') }}
        </p>
    @endif
</div>