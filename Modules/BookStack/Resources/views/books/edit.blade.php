@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $book,
                $book->getUrl('/kb/edit') => [
                    'text' => trans('bookstack::entities.books_edit'),
                    'icon' => 'edit',
                ]
            ]])
        </div>

        <main class="content-wrap card">
            <h1 class="list-heading">{{ trans('bookstack::entities.books_edit') }}</h1>
            <form action="{{ $book->getUrl() }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                @include('bookstack::books.form', ['model' => $book])
            </form>
        </main>
    </div>
@stop