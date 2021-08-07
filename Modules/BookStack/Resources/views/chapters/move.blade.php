@extends('simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $chapter->book,
                $chapter,
                $chapter->getUrl('/move') => [
                    'text' => trans('bookstack::entities.chapters_move'),
                    'icon' => 'folder',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.chapters_move') }}</h1>

            <form action="{{ $chapter->getUrl('/move') }}" method="POST">

                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">

                @include('bookstack::components.entity-selector', ['name' => 'entity_selection', 'selectorSize' => 'large', 'entityTypes' => 'book', 'entityPermission' => 'chapter-create'])

                <div class="form-group text-right">
                    <a href="{{ $chapter->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
                    <button type="submit" class="button">{{ trans('bookstack::entities.chapters_move') }}</button>
                </div>
            </form>

        </main>



    </div>

@stop
