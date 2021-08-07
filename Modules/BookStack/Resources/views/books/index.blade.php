@extends('bookstack::tri-layout')

@section('body')
    @include('bookstack::books.list', ['books' => $books, 'view' => $view])
@stop

@section('left')
    @if($recents)
        <div id="recents" class="mb-xl">
            <h5>{{ trans('bookstack::entities.recently_viewed') }}</h5>
            @include('bookstack::partials.entity-list', ['entities' => $recents, 'style' => 'compact'])
        </div>
    @endif

    <div id="popular" class="mb-xl">
        <h5>{{ trans('bookstack::entities.books_popular') }}</h5>
        @if(count($popular) > 0)
            @include('bookstack::partials.entity-list', ['entities' => $popular, 'style' => 'compact'])
        @else
            <div class="body text-muted">{{ trans('bookstack::entities.books_popular_empty') }}</div>
        @endif
    </div>

    <div id="new" class="mb-xl">
        <h5>{{ trans('bookstack::entities.books_new') }}</h5>
        @if(count($popular) > 0)
            @include('bookstack::partials.entity-list', ['entities' => $new, 'style' => 'compact'])
        @else
            <div class="body text-muted">{{ trans('bookstack::entities.books_new_empty') }}</div>
        @endif
    </div>
@stop

@section('right')

    <div class="actions mb-xl">
        <h5>{{ trans('bookstack::common.actions') }}</h5>
        <div class="icon-list text-primary">
            @if($currentUser->can('book-create-all'))
                <a href="{{ url("/create-book") }}" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('bookstack::entities.books_create') }}</span>
                </a>
            @endif

            @include('bookstack::partials.view-toggle', ['view' => $view, 'type' => 'book'])
        </div>
    </div>

@stop