@extends('bookstack::tri-layout')

@section('container-attrs')
    id="entity-dashboard"
    entity-id="{{ $chapter->id }}"
    entity-type="chapter"
@stop

@section('body')

    <div class="mb-m print-hidden">
        @include('bookstack::partials.breadcrumbs', ['crumbs' => [
            $chapter->book,
            $chapter,
        ]])
    </div>

    <main class="content-wrap card">
        <h1 class="break-text" v-pre>{{ $chapter->name }}</h1>
        <div class="chapter-content" v-show="!searching">
            <p v-pre class="text-muted break-text">{!! nl2br(e($chapter->description)) !!}</p>
            @if(count($pages) > 0)
                <div v-pre class="entity-list book-contents">
                    @foreach($pages as $page)
                        @include('pages.list-item', ['page' => $page])
                    @endforeach
                </div>
            @else
                <div class="mt-xl" v-pre>
                    <hr>
                    <p class="text-muted italic mb-m mt-xl">{{ trans('bookstack::entities.chapters_empty') }}</p>

                    <div class="icon-list block inline">
                        @if(userCan('page-create', $chapter))
                            <a href="{{ $chapter->getUrl('/create-page') }}" class="icon-list-item text-page">
                                <span class="icon">@icon('page')</span>
                                <span>{{ trans('bookstack::entities.books_empty_create_page') }}</span>
                            </a>
                        @endif
                        @if(userCan('book-update', $book))
                            <a href="{{ $book->getUrl('/sort') }}" class="icon-list-item text-book">
                                <span class="icon">@icon('book')</span>
                                <span>{{ trans('bookstack::entities.books_empty_sort_current_book') }}</span>
                            </a>
                        @endif
                    </div>

                </div>
            @endif
        </div>

        @include('bookstack::partials.entity-dashboard-search-results')
    </main>

@stop

@section('right')

    <div class="mb-xl">
        <h5>{{ trans('bookstack::common.details') }}</h5>
        <div class="blended-links text-small text-muted">
            @include('bookstack::partials.entity-meta', ['entity' => $chapter])

            @if($book->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $book))
                        <a href="{{ $book->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}
                    @endif
                </div>
            @endif

            @if($chapter->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $chapter))
                        <a href="{{ $chapter->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.chapters_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.chapters_permissions_active') }}
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="actions mb-xl">
        <h5>{{ trans('bookstack::common.actions') }}</h5>
        <div class="icon-list text-primary">

            @if(userCan('page-create', $chapter))
                <a href="{{ $chapter->getUrl('/create-page') }}" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('bookstack::entities.pages_new') }}</span>
                </a>
            @endif

            <hr class="primary-background"/>

            @if(userCan('chapter-update', $chapter))
                <a href="{{ $chapter->getUrl('/edit') }}" class="icon-list-item">
                    <span>@icon('edit')</span>
                    <span>{{ trans('bookstack::common.edit') }}</span>
                </a>
            @endif
            @if(userCan('chapter-update', $chapter) && userCan('chapter-delete', $chapter))
                <a href="{{ $chapter->getUrl('/move') }}" class="icon-list-item">
                    <span>@icon('folder')</span>
                    <span>{{ trans('bookstack::common.move') }}</span>
                </a>
            @endif
            @if(userCan('restrictions-manage', $chapter))
                <a href="{{ $chapter->getUrl('/permissions') }}" class="icon-list-item">
                    <span>@icon('lock')</span>
                    <span>{{ trans('bookstack::entities.permissions') }}</span>
                </a>
            @endif
            @if(userCan('chapter-delete', $chapter))
                <a href="{{ $chapter->getUrl('/delete') }}" class="icon-list-item">
                    <span>@icon('delete')</span>
                    <span>{{ trans('bookstack::common.delete') }}</span>
                </a>
            @endif

            <hr class="primary-background"/>

            @include('bookstack::partials.entity-export-menu', ['entity' => $chapter])
        </div>
    </div>
@stop

@section('left')

    @include('bookstack::partials.entity-dashboard-search-box')

    @if($chapter->tags->count() > 0)
        <div class="mb-xl">
            @include('bookstack::components.tag-list', ['entity' => $chapter])
        </div>
    @endif

    @include('bookstack::partials.book-tree', ['book' => $book, 'sidebarTree' => $sidebarTree])
@stop


