<div>
    <form action="{{ url("/kb/settings/users/{$currentUser->id}/switch-${type}-view") }}" method="POST" class="inline">
        {!! csrf_field() !!}
        {!! method_field('PATCH') !!}
        <input type="hidden" value="{{ $view === 'list'? 'grid' : 'list' }}" name="view_type">
        @if ($view === 'list')
            <button type="submit" class="icon-list-item text-primary">
                <span class="icon">@icon('grid')</span>
                <span>{{ trans('bookstack::common.grid_view') }}</span>
            </button>
        @else
            <button type="submit" class="icon-list-item text-primary">
                <span>@icon('list')</span>
                <span>{{ trans('bookstack::common.list_view') }}</span>
            </button>
        @endif
    </form>
</div>