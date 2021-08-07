@extends('bookstack::simple-layout')

@section('body')

    <div class="container">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $page->book,
                $page->chapter,
                $page,
                $page->getUrl('/permissions') => [
                    'text' => trans('bookstack::entities.pages_permissions'),
                    'icon' => 'lock',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.pages_permissions') }}</h1>
            @include('bookstack::form.entity-permissions', ['model' => $page])
        </main>
    </div>

@stop
