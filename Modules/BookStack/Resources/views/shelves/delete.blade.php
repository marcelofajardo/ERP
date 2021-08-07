@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('bookstack::partials.breadcrumbs', ['crumbs' => [
                $shelf,
                $shelf->getUrl('/delete') => [
                    'text' => trans('bookstack::entities.shelves_delete'),
                    'icon' => 'delete',
                ]
            ]])
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading">{{ trans('bookstack::entities.shelves_delete') }}</h1>
            <p>{{ trans('bookstack::entities.shelves_delete_explain', ['name' => $shelf->name]) }}</p>

            <div class="grid half">
                <p class="text-neg">
                    <strong>{{ trans('bookstack::entities.shelves_delete_confirmation') }}</strong>
                </p>

                <form action="{{ $shelf->getUrl() }}" method="POST" class="text-right">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">

                    <a href="{{ $shelf->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
                    <button type="submit" class="button">{{ trans('bookstack::common.confirm') }}</button>
                </form>
            </div>


        </div>
    </div>

@stop