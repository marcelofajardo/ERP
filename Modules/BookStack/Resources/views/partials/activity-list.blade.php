
@if(count($activity) > 0)
    <div class="activity-list">
        @foreach($activity as $activityItem)
            <div class="activity-list-item">
                @include('bookstack::partials.activity-item', ['activity' => $activityItem])
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted empty-text">{{ trans('bookstack::common.no_activity') }}</p>
@endif