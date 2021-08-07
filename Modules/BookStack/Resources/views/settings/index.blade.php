@extends('bookstack::simple-layout')

@section('body')
    <div class="container small">

        <div class="grid left-focus v-center no-row-gap">
            <div class="py-m">
                @include('bookstack::settings.navbar', ['selected' => 'settings'])
            </div>
            <div class="text-right mb-l px-m">
                <br>
                BookStack @if(strpos($version, 'v') !== 0) version @endif {{ $version }}
            </div>
        </div>

        <div class="card content-wrap auto-height">
            <h2 class="list-heading">{{ trans('bookstack::settings.app_features_security') }}</h2>
            <form action="{{ url("/kb/settings") }}" method="POST">
                {!! csrf_field() !!}

                <div class="setting-list">


                    <div class="grid half gap-xl">
                        <div>
                            <label for="setting-app-public" class="setting-list-label">{{ trans('bookstack::settings.app_public_access') }}</label>
                            <p class="small">{!! trans('bookstack::settings.app_public_access_desc') !!}</p>
                            @if(userCan('users-manage'))
                                <p class="small mb-none">
                                    <a href="{{ ($guestUser) ? url($guestUser->getEditUrl()) : '' }}">{!! trans('bookstack::settings.app_public_access_desc_guest') !!}</a>
                                </p>
                            @endif
                        </div>
                        <div>
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-app-public',
                                'value' => setting('app-public'),
                                'label' => trans('bookstack::settings.app_public_access_toggle'),
                            ])
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.app_secure_images') }}</label>
                            <p class="small">{{ trans('bookstack::settings.app_secure_images_desc') }}</p>
                        </div>
                        <div>
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-app-secure-images',
                                'value' => setting('app-secure-images'),
                                'label' => trans('bookstack::settings.app_secure_images_toggle'),
                            ])
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.app_disable_comments') }}</label>
                            <p class="small">{!! trans('bookstack::settings.app_disable_comments_desc') !!}</p>
                        </div>
                        <div>
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-app-disable-comments',
                                'value' => setting('app-disable-comments'),
                                'label' => trans('bookstack::settings.app_disable_comments_toggle'),
                            ])
                        </div>
                    </div>


                </div>

                <div class="form-group text-right">
                    <button type="submit" class="button">{{ trans('bookstack::settings.settings_save') }}</button>
                </div>
            </form>
        </div>

        <div class="card content-wrap auto-height">
            <h2 class="list-heading">{{ trans('bookstack::settings.app_customization') }}</h2>
            <form action="{{ url("/kb/settings") }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="setting-list">

                    <div class="grid half gap-xl">
                        <div>
                            <label for="setting-app-name" class="setting-list-label">{{ trans('bookstack::settings.app_name') }}</label>
                            <p class="small">{{ trans('bookstack::settings.app_name_desc') }}</p>
                        </div>
                        <div>
                            <input type="text" value="{{ setting('app-name', 'BookStack') }}" name="setting-app-name" id="setting-app-name">
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-app-name-header',
                                'value' => setting('app-name-header'),
                                'label' => trans('bookstack::settings.app_name_header'),
                            ])
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.app_editor') }}</label>
                            <p class="small">{{ trans('bookstack::settings.app_editor_desc') }}</p>
                        </div>
                        <div>
                            <select name="setting-app-editor" id="setting-app-editor">
                                <option @if(setting('app-editor') === 'wysiwyg') selected @endif value="wysiwyg">WYSIWYG</option>
                                <option @if(setting('app-editor') === 'markdown') selected @endif value="markdown">Markdown</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.app_logo') }}</label>
                            <p class="small">{!! trans('bookstack::settings.app_logo_desc') !!}</p>
                        </div>
                        <div>
                            @include('bookstack::components.image-picker', [
                                     'removeName' => 'setting-app-logo',
                                     'removeValue' => 'none',
                                     'defaultImage' => url('/logo.png'),
                                     'currentImage' => setting('app-logo'),
                                     'name' => 'app_logo',
                                     'imageClass' => 'logo-image',
                                 ])
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.app_primary_color') }}</label>
                            <p class="small">{!! trans('bookstack::settings.app_primary_color_desc') !!}</p>
                        </div>
                        <div setting-app-color-picker class="text-m-right">
                            <input type="color" value="{{ setting('app-color') }}" name="setting-app-color" id="setting-app-color" placeholder="#206ea7">
                            <input type="hidden" value="{{ setting('app-color-light') }}" name="setting-app-color-light" id="setting-app-color-light">
                            <br>
                            <button type="button" class="text-button text-muted mt-s mx-s" setting-app-color-picker-reset>{{ trans('common.reset') }}</button>
                        </div>
                    </div>

                    <div homepage-control id="homepage-control" class="grid half gap-xl">
                        <div>
                            <label for="setting-app-homepage" class="setting-list-label">{{ trans('bookstack::settings.app_homepage') }}</label>
                            <p class="small">{{ trans('bookstack::settings.app_homepage_desc') }}</p>
                        </div>
                        <div>
                            <select name="setting-app-homepage-type" id="setting-app-homepage-type">
                                <option @if(setting('app-homepage-type') === 'default') selected @endif value="default">{{ trans('bookstack::common.default') }}</option>
                                <option @if(setting('app-homepage-type') === 'books') selected @endif value="books">{{ trans('bookstack::entities.books') }}</option>
                                <option @if(setting('app-homepage-type') === 'bookshelves') selected @endif value="bookshelves">{{ trans('bookstack::entities.shelves') }}</option>
                                <option @if(setting('app-homepage-type') === 'page') selected @endif value="page">{{ trans('bookstack::entities.pages_specific') }}</option>
                            </select>

                            <div page-picker-container style="display: none;" class="mt-m">
                                @include('bookstack::components.page-picker', ['name' => 'setting-app-homepage', 'placeholder' => trans('bookstack::settings.app_homepage_select'), 'value' => setting('app-homepage')])
                            </div>
                        </div>
                    </div>


                    <div>
                        <label for="setting-app-custom-head" class="setting-list-label">{{ trans('bookstack::settings.app_custom_html') }}</label>
                        <p class="small">{{ trans('bookstack::settings.app_custom_html_desc') }}</p>
                        <textarea name="setting-app-custom-head" id="setting-app-custom-head" class="simple-code-input mt-m">{{ setting('app-custom-head', '') }}</textarea>
                        <p class="small text-right">{{ trans('bookstack::settings.app_custom_html_disabled_notice') }}</p>
                    </div>


                </div>

                <div class="form-group text-right">
                    <button type="submit" class="button">{{ trans('bookstack::settings.settings_save') }}</button>
                </div>
            </form>
        </div>

        <!-- <div class="card content-wrap auto-height">
            <h2 class="list-heading">{{ trans('bookstack::settings.reg_settings') }}</h2>
            <form action="{{ url("/kb/settings") }}" method="POST">
                {!! csrf_field() !!}

                <div class="setting-list">
                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.reg_enable') }}</label>
                            <p class="small">{!! trans('bookstack::settings.reg_enable_desc') !!}</p>
                        </div>
                        <div>
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-registration-enabled',
                                'value' => setting('registration-enabled'),
                                'label' => trans('bookstack::settings.reg_enable_toggle')
                            ])

                            <label for="setting-registration-role">{{ trans('bookstack::settings.reg_default_role') }}</label>
                            <select id="setting-registration-role" name="setting-registration-role" @if($errors->has('setting-registration-role')) class="neg" @endif>
                                @foreach(\Modules\BookStack\Auth\Role::all() as $role)
                                    <option value="{{$role->id}}" data-role-name="{{ $role->name }}"
                                            @if(setting('registration-role', \Modules\BookStack\Auth\Role::first()->id) == $role->id) selected @endif
                                    >
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label for="setting-registration-restrict" class="setting-list-label">{{ trans('bookstack::settings.reg_confirm_restrict_domain') }}</label>
                            <p class="small">{!! trans('bookstack::settings.reg_confirm_restrict_domain_desc') !!}</p>
                        </div>
                        <div>
                            <input type="text" id="setting-registration-restrict" name="setting-registration-restrict" placeholder="{{ trans('bookstack::settings.reg_confirm_restrict_domain_placeholder') }}" value="{{ setting('registration-restrict', '') }}">
                        </div>
                    </div>

                    <div class="grid half gap-xl">
                        <div>
                            <label class="setting-list-label">{{ trans('bookstack::settings.reg_email_confirmation') }}</label>
                            <p class="small">{{ trans('bookstack::settings.reg_confirm_email_desc') }}</p>
                        </div>
                        <div>
                            @include('bookstack::components.toggle-switch', [
                                'name' => 'setting-registration-confirmation',
                                'value' => setting('registration-confirmation'),
                                'label' => trans('bookstack::settings.reg_email_confirmation_toggle')
                            ])
                        </div>
                    </div>

                </div>

                <div class="form-group text-right">
                    <button type="submit" class="button">{{ trans('bookstack::settings.settings_save') }}</button>
                </div>
            </form>
        </div> -->

    </div>

    @include('bookstack::components.image-manager', ['imageType' => 'system'])
    @include('bookstack::components.entity-selector-popup', ['entityTypes' => 'page'])
@stop