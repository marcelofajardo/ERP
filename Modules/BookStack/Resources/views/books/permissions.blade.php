@extends('bookstack::simple-layout')

@section('body')

    <div class="container">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $book,
                $book->getUrl('/permissions') => [
                    'text' => trans('bookstack::entities.books_permissions'),
                    'icon' => 'lock',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.books_permissions') }}</h1>
            @include('bookstack::form.entity-permissions', ['model' => $book])
        </main>
    </div>

@stop
