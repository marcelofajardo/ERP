@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                ($parent->isA('chapter') ? $parent->book : null),
                $parent,
                $parent->getUrl('/create-page') => [
                    'text' => trans('bookstack::entities.pages_new'),
                    'icon' => 'add',
                ]
            ]])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('bookstack::entities.pages_new') }}</h1>
            <form action="{{  $parent->getUrl('/create-guest-page') }}" method="POST">
                {!! csrf_field() !!}

                <div class="form-group title-input">
                    <label for="name">{{ trans('bookstack::entities.pages_name') }}</label>
                    @include('form.text', ['name' => 'name'])
                </div>

                <div class="form-group text-right">
                    <a href="{{ $parent->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
                    <button type="submit" class="button">{{ trans('bookstack::common.continue') }}</button>
                </div>

            </form>
        </main>
    </div>

@stop