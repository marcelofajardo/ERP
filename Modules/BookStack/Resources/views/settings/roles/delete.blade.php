@extends('bookstack::simple-layout')

@section('body')
    <div class="container small">

        <div class="py-m">
            @include('bookstack::settings.navbar', ['selected' => 'roles'])
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading"> {{ trans('bookstack::settings.role_delete') }}</h1>

            <p>{{ trans('bookstack::settings.role_delete_confirm', ['roleName' => $role->display_name]) }}</p>

            <form action="{{ url("/kb/settings/roles/delete/{$role->id}") }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">

                @if($role->users->count() > 0)
                    <div class="form-group">
                        <p>{{ trans('bookstack::settings.role_delete_users_assigned', ['userCount' => $role->users->count()]) }}</p>
                        @include('bookstack::orm.role-select', ['options' => $roles, 'name' => 'migration_role_id'])
                    </div>
                @endif

                <div class="grid half v-center">
                    <div>
                        <p class="text-neg">
                            <strong>{{ trans('bookstack::settings.role_delete_sure') }}</strong>
                        </p>
                    </div>
                    <div>
                        <div class="form-group text-right">
                            <a href="{{ url("/kb/settings/roles/{$role->id}") }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
                            <button type="submit" class="button">{{ trans('bookstack::common.confirm') }}</button>
                        </div>
                    </div>
                </div>


            </form>
        </div>

    </div>
@stop
