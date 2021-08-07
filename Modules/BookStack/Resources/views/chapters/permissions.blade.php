@extends('bookstack::simple-layout')

@section('body')

    <div class="container">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $chapter->book,
                $chapter,
                $chapter->getUrl('/permissions') => [
                    'text' => trans('bookstack::entities.chapters_permissions'),
                    'icon' => 'lock',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.chapters_permissions') }}</h1>
            @include('bookstack::form.entity-permissions', ['model' => $chapter])
        </main>
    </div>

@stop
