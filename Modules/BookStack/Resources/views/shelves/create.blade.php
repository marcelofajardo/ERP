@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                '/kb/shelves' => [
                    'text' => trans('bookstack::entities.shelves'),
                    'icon' => 'bookshelf',
                ],
                '/kb/create-shelf' => [
                    'text' => trans('bookstack::entities.shelves_create'),
                    'icon' => 'add',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.shelves_create') }}</h1>
            <form action="{{ url("/kb/shelves") }}" method="POST" enctype="multipart/form-data">
                @include('bookstack::shelves.form', ['shelf' => null, 'books' => $books])
            </form>
        </main>

    </div>

@stop