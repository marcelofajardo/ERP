<!DOCTYPE html>
<html lang="{{ config('app.lang') }}" class="@yield('body-class')">
<head>
    <title>{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ setting('app-name') }}</title>

    <!-- Meta -->
    <meta name="viewport" content="width=device-width">
    <meta name="token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta charset="utf-8">

    <!-- Styles and Fonts -->
    <link rel="stylesheet" href="{{ versioned_asset('dist/styles.css') }}">
    <link rel="stylesheet" media="print" href="{{ versioned_asset('dist/print-styles.css') }}">

    @yield('head')

    <!-- Custom Styles & Head Content -->
    @include('bookstack::partials.custom-styles')
    @include('bookstack::partials.custom-head')

    @stack('head')

    <!-- Translations for JS -->
    @stack('translations')

</head>
<body class="@yield('body-class')">

    @include('bookstack::partials.notifications')
    @include('bookstack::common.header')

    <div id="content" class="block">
        @yield('content')
    </div>

    <div back-to-top class="primary-background print-hidden">
        <div class="inner">
            @icon('chevron-up') <span>{{ trans('common.back_to_top') }}</span>
        </div>
    </div>

    @yield('bottom')
    <script src="{{ versioned_asset('dist/app.js') }}"></script>
    @yield('scripts')

</body>
</html>
