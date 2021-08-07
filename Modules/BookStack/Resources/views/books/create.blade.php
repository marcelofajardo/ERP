@extends('bookstack::simple-layout')

@section('body')
    <div class="container small">
        <div class="my-s">
            @if (isset($bookshelf))
                @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                    $bookshelf,
                    $bookshelf->getUrl('/create-book') => [
                        'text' => trans('bookstack::entities.books_create'),
                        'icon' => 'add'
                    ]
                ]])
            @else
                @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                    '/kb/books' => [
                        'text' => trans('bookstack::entities.books'),
                        'icon' => 'book'
                    ],
                    '/kb/create-book' => [
                        'text' => trans('bookstack::entities.books_create'),
                        'icon' => 'add'
                    ]
                ]])
            @endif
        </div>

        <main class="content-wrap card">
            <h1 class="list-heading">{{ trans('bookstack::entities.books_create') }}</h1>
            <form action="{{ isset($bookshelf) ? $bookshelf->getUrl('/create-book') : url('/kb/books') }}" method="POST" enctype="multipart/form-data">
                @include('bookstack::books.form')
            </form>
        </main>
    </div>

@stop