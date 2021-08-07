@extends('bookstack::tri-layout')

@section('body')

    <div class="mb-m print-hidden">
        @include('bookstack::partials.breadcrumbs', ['crumbs' => [
            $page->book,
            $page->hasChapter() ? $page->chapter : null,
            $page,
        ]])
    </div>

    <main class="content-wrap card">
        <div class="page-content" page-display="{{ $page->id }}">
            @include('bookstack::pages.pointer', ['page' => $page])
            @include('bookstack::pages.page-display')
        </div>
    </main>

    @if ($commentsEnabled)
        <div class="container small p-none comments-container mb-l print-hidden">
            @include('bookstack::comments.comments', ['page' => $page])
            <div class="clearfix"></div>
        </div>
    @endif
@stop

@section('left')

    @if($page->tags->count() > 0)
        <section>
            @include('bookstack::components.tag-list', ['entity' => $page])
        </section>
    @endif

    @if ($page->attachments->count() > 0)
        <div id="page-attachments" class="mb-l">
            <h5>{{ trans('bookstack::entities.pages_attachments') }}</h5>
            <div class="body">
                @foreach($page->attachments as $attachment)
                    <div class="attachment icon-list">
                        <a class="icon-list-item py-xs" href="{{ $attachment->getUrl() }}" @if($attachment->external) target="_blank" @endif>
                            <span class="icon">@icon($attachment->external ? 'export' : 'file')</span>
                            <span>{{ $attachment->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (isset($pageNav) && count($pageNav))
        <nav id="page-navigation" class="mb-xl" aria-label="{{ trans('bookstack::entities.pages_navigation') }}">
            <h5>{{ trans('bookstack::entities.pages_navigation') }}</h5>
            <div class="body">
                <div class="sidebar-page-nav menu">
                    @foreach($pageNav as $navItem)
                        <li class="page-nav-item h{{ $navItem['level'] }}">
                            <a href="{{ $navItem['link'] }}" class="limit-text block">{{ $navItem['text'] }}</a>
                            <div class="primary-background sidebar-page-nav-bullet"></div>
                        </li>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif

    @include('bookstack::partials.book-tree', ['book' => $book, 'sidebarTree' => $sidebarTree])
@stop

@section('right')
    <div id="page-details" class="entity-details mb-xl">
        <h5>{{ trans('bookstack::common.details') }}</h5>
        <div class="body text-small blended-links">
            @include('bookstack::partials.entity-meta', ['entity' => $page])

            @if($book->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $book))
                        <a href="{{ $book->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}
                    @endif
                </div>
            @endif

            @if($page->chapter && $page->chapter->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $page->chapter))
                        <a href="{{ $page->chapter->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.chapters_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.chapters_permissions_active') }}
                    @endif
                </div>
            @endif

            @if($page->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $page))
                        <a href="{{ $page->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.pages_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.pages_permissions_active') }}
                    @endif
                </div>
            @endif

            @if($page->template)
                <div>
                    @icon('template'){{ trans('bookstack::entities.pages_is_template') }}
                </div>
            @endif
        </div>
    </div>

    <div class="actions mb-xl">
        <h5>Actions</h5>

        <div class="icon-list text-primary">

            {{--User Actions--}}
            @if(userCan('page-update', $page))
                <a href="{{ $page->getUrl('/edit') }}" class="icon-list-item">
                    <span>@icon('edit')</span>
                    <span>{{ trans('bookstack::common.edit') }}</span>
                </a>
            @endif
            @if(userCanOnAny('page-create'))
                <a href="{{ $page->getUrl('/copy') }}" class="icon-list-item">
                    <span>@icon('copy')</span>
                    <span>{{ trans('bookstack::common.copy') }}</span>
                </a>
            @endif
            @if(userCan('page-update', $page))
                @if(userCan('page-delete', $page))
	                <a href="{{ $page->getUrl('/move') }}" class="icon-list-item">
	                    <span>@icon('folder')</span>
	                    <span>{{ trans('bookstack::common.move') }}</span>
	                </a>
                @endif
                <a href="{{ $page->getUrl('/revisions') }}" class="icon-list-item">
                    <span>@icon('history')</span>
                    <span>{{ trans('bookstack::entities.revisions') }}</span>
                </a>
            @endif
            @if(userCan('restrictions-manage', $page))
                <a href="{{ $page->getUrl('/permissions') }}" class="icon-list-item">
                    <span>@icon('lock')</span>
                    <span>{{ trans('bookstack::entities.permissions') }}</span>
                </a>
            @endif
            @if(userCan('page-delete', $page))
                <a href="{{ $page->getUrl('/delete') }}" class="icon-list-item">
                    <span>@icon('delete')</span>
                    <span>{{ trans('bookstack::common.delete') }}</span>
                </a>
            @endif

            <hr class="primary-background"/>

            {{--Export--}}
            @include('bookstack::partials.entity-export-menu', ['entity' => $page])
        </div>

    </div>
@stop
