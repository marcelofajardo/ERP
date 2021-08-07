@extends('tri-layout')

@section('body')
    @include('books.list', ['books' => $books, 'view' => $view])
@stop

@section('left')
    @include('common.home-sidebar')
@stop

@section('right')
    <div class="actions mb-xl">
        <h5>{{ trans('common.actions') }}</h5>
        <div class="icon-list text-primary">
            @include('partials.view-toggle', ['view' => $view, 'type' => 'book'])
            @include('components.expand-toggle', ['target' => '.entity-list.compact .entity-item-snippet', 'key' => 'home-details'])
        </div>
    </div>
@stop