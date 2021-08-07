@extends('bookstack::simple-layout')

@section('body')

    <div class="container small">
        <div class="py-m">
            @include('bookstack::settings.navbar', ['selected' => 'roles'])
        </div>

        <form action="{{ url("/kb/settings/roles/{$role->id}") }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @include('bookstack::settings.roles.form', ['model' => $role, 'title' => trans('settings.role_edit'), 'icon' => 'edit'])
        </form>
    </div>

@stop
