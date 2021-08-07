@extends('bookstack::simple-layout')

@section('body')

    <div class="container px-xl py-s">
        <div class="icon-list inline block">
            @include('bookstack::components.expand-toggle', ['target' => '.entity-list.compact .entity-item-snippet', 'key' => 'home-details'])
        </div>
    </div>

    <div class="container" id="home-default">
        <div class="grid third gap-xxl no-row-gap" >
            <div>
                @if(count($draftPages) > 0)
                    <div id="recent-drafts" class="card mb-xl">
                        <h3 class="card-title">{{ trans('bookstack::entities.my_recent_drafts') }}</h3>
                        <div class="px-m">
                            @include('bookstack::partials.entity-list', ['entities' => $draftPages, 'style' => 'compact'])
                        </div>
                    </div>
                @endif

                <div id="{{ $signedIn ? 'recently-viewed' : 'recent-books' }}" class="card mb-xl">
                    <h3 class="card-title">{{ trans('entities.' . ($signedIn ? 'my_recently_viewed' : 'books_recent')) }}</h3>
                    <div class="px-m">
                        @include('bookstack::partials.entity-list', [
                        'entities' => $recents,
                        'style' => 'compact',
                        'emptyText' => $signedIn ? trans('bookstack::entities.no_pages_viewed') : trans('bookstack::entities.books_empty')
                        ])
                    </div>
                </div>
            </div>

            <div>
                <div id="recent-pages" class="card mb-xl">
                    <h3 class="card-title"><a class="no-color" href="{{ url("/pages/recently-updated") }}">{{ trans('bookstack::entities.recently_updated_pages') }}</a></h3>
                    <div id="recently-updated-pages" class="px-m">
                        @include('bookstack::partials.entity-list', [
                        'entities' => $recentlyUpdatedPages,
                        'style' => 'compact',
                        'emptyText' => trans('bookstack::entities.no_pages_recently_updated')
                        ])
                    </div>
                </div>
            </div>

            <div>
                <div id="recent-activity">
                    <div class="card mb-xl">
                        <h3 class="card-title">{{ trans('bookstack::entities.recent_activity') }}</h3>
                        @include('bookstack::partials.activity-list', ['activity' => $activity])
                    </div>
                </div>
            </div>

        </div>
    </div>



@stop
