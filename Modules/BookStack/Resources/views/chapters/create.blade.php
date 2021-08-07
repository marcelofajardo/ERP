@extends('bookstack::simple-layout')

@section('body')
    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $book,
                $book->getUrl('create-chapter') => [
                    'text' => trans('bookstack::entities.chapters_create'),
                    'icon' => 'add',
                ]
            ]])
        </div>

        <main class="content-wrap card">
            <h1 class="list-heading">{{ trans('bookstack::entities.chapters_create') }}</h1>
            <form action="{{ $book->getUrl('/create-chapter') }}" method="POST">
                @include('bookstack::chapters.form')
            </form>
        </main>

    </div>
@stop