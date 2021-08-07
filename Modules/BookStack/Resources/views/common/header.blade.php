<header id="header" header-mobile-toggle class="primary-background">
    <div class="grid mx-l">

        <div>
            <a href="{{ url('/') }}" class="logo">
                @if(setting('app-logo', '') !== 'none')
                    <img class="logo-image" src="{{ setting('app-logo', '') === '' ? url('/logo.png') : url(setting('app-logo', '')) }}" alt="Logo">
                @endif
                @if (setting('app-name-header'))
                    <span class="logo-text">{{ setting('app-name') }}</span>
                @endif
            </a>
            <div class="mobile-menu-toggle hide-over-l">@icon('more')</div>
        </div>

        <div class="header-search hide-under-l">
            @if (hasAppAccess())
            <!-- <form action="{{ url('/search') }}" method="GET" class="search-box" role="search">
                <button id="header-search-box-button" type="submit" aria-label="{{ trans('bookstack::common.search') }}" tabindex="-1">@icon('search') </button>
                <input id="header-search-box-input" type="text" name="term"
                       aria-label="{{ trans('bookstack::common.search') }}" placeholder="{{ trans('bookstack::common.search') }}"
                       value="{{ isset($searchTerm) ? $searchTerm : '' }}">
            </form> -->
            @endif
        </div>

        <div class="text-right">
            <nav class="header-links" >
                <div class="links text-center">
                    @if (hasAppAccess())
                        <a class="hide-over-l" href="{{ url('/kb/search') }}">@icon('search'){{ trans('bookstack::common.search') }}</a>
                        <!-- @if(userCanOnAny('view', \Modules\BookStack\Entities\Bookshelf::class) || userCan('bookshelf-view-all') || userCan('bookshelf-view-own'))
                            <a href="{{ url('/kb/shelves') }}">@icon('bookshelf'){{ trans('bookstack::entities.shelves') }}</a>
                        @endif
                        <a href="{{ url('/kb/books') }}">@icon('books'){{ trans('bookstack::entities.books') }}</a> -->
                        @if(signedInUser() && userCan('settings-manage'))
                            <a href="{{ url('/kb/settings') }}">@icon('settings'){{ trans('bookstack::settings.settings') }}</a>
                        @endif
                        @if(signedInUser() && userCan('users-manage') && !userCan('settings-manage'))
                            <a href="{{ url('/kb/settings/users') }}">@icon('users'){{ trans('bookstack::settings.users') }}</a>
                        @endif
                    @endif
                </div>
                @if(signedInUser())
                    <?php $currentUser = user(); ?>
                    <div class="dropdown-container" dropdown>
                        <span class="user-name py-s hide-under-l" dropdown-toggle
                              aria-haspopup="true" aria-expanded="false" aria-label="{{ trans('bookstack::common.profile_menu') }}" tabindex="0">
                            <img class="avatar" src="{{$currentUser->getAvatar(30)}}" alt="{{ $currentUser->name }}">
                            <span class="name">{{ $currentUser->getShortName(9) }}</span> @icon('caret-down')
                        </span>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="{{ url('/logout') }}">@icon('logout'){{ trans('bookstack::auth.logout') }}</a>
                            </li>
                        </ul>
                    </div>
                @endif
            </nav>
        </div>

    </div>
</header>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>