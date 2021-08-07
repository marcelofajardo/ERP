
<nav class="active-link-list">
    @if($currentUser->can('settings-manage'))
        <a href="{{ url('/kb/settings') }}" @if($selected == 'settings') class="active" @endif>@icon('settings'){{ trans('bookstack::settings.settings') }}</a>
        <a href="{{ url('/kb/settings/maintenance') }}" @if($selected == 'maintenance') class="active" @endif>@icon('spanner'){{ trans('bookstack::settings.maint') }}</a>
    @endif
</nav>