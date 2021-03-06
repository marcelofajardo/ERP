@extends('bookstack::tri-layout')

@section('container-attrs')
    id="entity-dashboard"
    entity-id="{{ $book->id }}"
    entity-type="book"
@stop

@section('body')

    <div class="mb-s">
        @include('bookstack::partials.breadcrumbs', ['crumbs' => [
            $book,
        ]])
    </div>

    <main class="content-wrap card">
        <h1 class="break-text" v-pre>{{$book->name}}</h1>
        <div class="book-content" v-show="!searching">
            <p class="text-muted" v-pre>{!! nl2br(e($book->description)) !!}</p>
            @if(count($bookChildren) > 0)
                <div class="entity-list book-contents" v-pre>
                    @foreach($bookChildren as $childElement)
                        @if($childElement->isA('chapter'))
                            @include('bookstack::chapters.list-item', ['chapter' => $childElement])
                        @else
                            @include('bookstack::pages.list-item', ['page' => $childElement])
                        @endif
                    @endforeach
                </div>
            @else
                <div class="mt-xl" v-pre>
                    <hr>
                    <p class="text-muted italic mb-m mt-xl">{{ trans('bookstack::entities.books_empty_contents') }}</p>

                    <div class="icon-list block inline">
                        @if(userCan('page-create', $book))
                            <a href="{{ $book->getUrl('/create-page') }}" class="icon-list-item text-page">
                                <span class="icon">@icon('page')</span>
                                <span>{{ trans('bookstack::entities.books_empty_create_page') }}</span>
                            </a>
                        @endif
                        @if(userCan('chapter-create', $book))
                            <a href="{{ $book->getUrl('/create-chapter') }}" class="icon-list-item text-chapter">
                                <span class="icon">@icon('chapter')</span>
                                <span>{{ trans('bookstack::entities.books_empty_add_chapter') }}</span>
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
        <div class="text-small text-muted blended-links">
            @include('bookstack::partials.entity-meta', ['entity' => $book])
            @if($book->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $book))
                        <a href="{{ $book->getUrl('/permissions') }}">@icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('bookstack::entities.books_permissions_active') }}
                    @endif
                </div>
            @endif
        </div>
    </div>


    <div class="actions mb-xl">
        <h5>{{ trans('bookstack::common.actions') }}</h5>
        <div class="icon-list text-primary">

            @if(userCan('page-create', $book))
                <a href="{{ $book->getUrl('/create-page') }}" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('bookstack::entities.pages_new') }}</span>
                </a>
            @endif
            @if(userCan('chapter-create', $book))
                <a href="{{ $book->getUrl('/create-chapter') }}" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('bookstack::entities.chapters_new') }}</span>
                </a>
            @endif

            <hr class="primary-background">

            @if(userCan('book-update', $book))
                <a href="{{ $book->getUrl('/edit') }}" class="icon-list-item">
                    <span>@icon('edit')</span>
                    <span>{{ trans('bookstack::common.edit') }}</span>
                </a>
                <a href="{{ $book->getUrl('/sort') }}" class="icon-list-item">
                    <span>@icon('sort')</span>
                    <span>{{ trans('bookstack::common.sort') }}</span>
                </a>
            @endif
            @if(userCan('restrictions-manage', $book))
                <a href="{{ $book->getUrl('/permissions') }}" class="icon-list-item">
                    <span>@icon('lock')</span>
                    <span>{{ trans('bookstack::entities.permissions') }}</span>
                </a>
            @endif
            @if(userCan('book-delete', $book))
                <a href="{{ $book->getUrl('/delete') }}" class="icon-list-item">
                    <span>@icon('delete')</span>
                    <span>{{ trans('bookstack::common.delete') }}</span>
                </a>
            @endif

            <hr class="primary-background">

            @include('bookstack::partials.entity-export-menu', ['entity' => $book])
        </div>
    </div>

@stop

@section('left')

    @include('bookstack::partials.entity-dashboard-search-box')

    @if($book->tags->count() > 0)
        <div class="mb-xl">
            @include('bookstack::components.tag-list', ['entity' => $book])
        </div>
    @endif

    @if(count($activity) > 0)
        <div class="mb-xl">
            <h5>{{ trans('bookstack::entities.recent_activity') }}</h5>
            @include('bookstack::partials.activity-list', ['activity' => $activity])
        </div>
    @endif
@stop

