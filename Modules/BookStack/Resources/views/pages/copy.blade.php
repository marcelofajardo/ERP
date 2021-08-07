@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $page->book,
                $page->chapter,
                $page,
                $page->getUrl('/copy') => [
                    'text' => trans('bookstack::entities.pages_copy'),
                    'icon' => 'copy',
                ]
            ]])
        </div>

        <div class="card content-wrap auto-height">

            <h1 class="list-heading">{{ trans('bookstack::entities.pages_copy') }}</h1>

            <form action="{{ $page->getUrl('/copy') }}" method="POST">
                {!! csrf_field() !!}

                <div class="form-group title-input">
                    <label for="name">{{ trans('bookstack::common.name') }}</label>
                    @include('bookstack::form.text', ['name' => 'name'])
                </div>

                <div class="form-group" collapsible>
                    <button type="button" class="collapse-title text-primary" collapsible-trigger aria-expanded="false">
                        <label for="entity_selection">{{ trans('bookstack::entities.pages_copy_desination') }}</label>
                    </button>
                    <div class="collapse-content" collapsible-content>
                        @include('bookstack::components.entity-selector', ['name' => 'entity_selection', 'selectorSize' => 'large', 'entityTypes' => 'book,chapter', 'entityPermission' => 'page-create'])
                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ $page->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
                    <button type="submit" class="button">{{ trans('bookstack::entities.pages_copy') }}</button>
                </div>
            </form>

        </div>
    </div>

@stop
