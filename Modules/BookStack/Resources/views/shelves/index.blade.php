@extends('bookstack::tri-layout')

@section('body')
    @include('bookstack::shelves.list', ['shelves' => $shelves, 'view' => $view])
@stop

@section('right')

    <div class="actions mb-xl">
        <h5>{{ trans('bookstack::common.actions') }}</h5>
        <div class="icon-list text-primary">
            @if($currentUser->can('bookshelf-create-all'))
                <a href="{{ url("/kb/create-shelf") }}" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('bookstack::entities.shelves_new_action') }}</span>
                </a>
            @endif
            @include('bookstack::partials.view-toggle', ['view' => $view, 'type' => 'shelf'])
        </div>
    </div>

@stop

@section('left')
    @if($recents)
        <div id="recents" class="mb-xl">
            <h5>{{ trans('bookstack::entities.recently_viewed') }}</h5>
            @include('bookstack::partials.entity-list', ['entities' => $recents, 'style' => 'compact'])
        </div>
    @endif

    <div id="popular" class="mb-xl">
        <h5>{{ trans('bookstack::entities.shelves_popular') }}</h5>
        @if(count($popular) > 0)
            @include('bookstack::partials.entity-list', ['entities' => $popular, 'style' => 'compact'])
        @else
            <div class="text-muted">{{ trans('bookstack::entities.shelves_popular_empty') }}</div>
        @endif
    </div>

    <div id="new" class="mb-xl">
        <h5>{{ trans('bookstack::entities.shelves_new') }}</h5>
        @if(count($new) > 0)
            @include('bookstack::partials.entity-list', ['entities' => $new, 'style' => 'compact'])
        @else
            <div class="text-muted">{{ trans('bookstack::entities.shelves_new_empty') }}</div>
        @endif
    </div>
@stop