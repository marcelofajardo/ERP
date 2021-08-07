@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">

        <div class="py-m">
            @include('bookstack::settings.navbar', ['selected' => 'roles'])
        </div>

        <form action="{{ url("/kb/settings/roles/new") }}" method="POST">
            @include('bookstack::settings.roles.form', ['title' => trans('bookstack::settings.role_create')])
        </form>
    </div>

@stop
