@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $book,
                $chapter,
                $chapter->getUrl('/edit') => [
                    'text' => trans('bookstack::entities.chapters_edit'),
                    'icon' => 'edit'
                ]
            ]])
        </div>

        <main class="content-wrap card">
            <h1 class="list-heading">{{ trans('bookstack::entities.chapters_edit') }}</h1>
            <form action="{{  $chapter->getUrl() }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                @include('bookstack::chapters.form', ['model' => $chapter])
            </form>
        </main>

    </div>

@stop