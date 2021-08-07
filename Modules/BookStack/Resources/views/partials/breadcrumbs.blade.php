<nav class="breadcrumbs text-center" aria-label="{{ trans('bookstack::common.breadcrumb') }}">
    <?php $breadcrumbCount = 0; ?>

    {{-- Show top level books item --}}
    @if (count($crumbs) > 0 && array_first($crumbs) instanceof  \Modules\BookStack\Entities\Book)
        <a href="{{  url('/kb/books')  }}" class="text-book icon-list-item outline-hover">
            <span>@icon('books')</span>
            <span>{{ trans('bookstack::entities.books') }}</span>
        </a>
        <?php $breadcrumbCount++; ?>
    @endif

    {{-- Show top level shelves item --}}
    @if (count($crumbs) > 0 && array_first($crumbs) instanceof  \Modules\BookStack\Entities\Bookshelf)
        <a href="{{  url('/kb/shelves')  }}" class="text-bookshelf icon-list-item outline-hover">
            <span>@icon('bookshelf')</span>
            <span>{{ trans('bookstack::entities.shelves') }}</span>
        </a>
        <?php $breadcrumbCount++; ?>
    @endif

    @foreach($crumbs as $key => $crumb)
        <?php $isEntity = ($crumb instanceof \Modules\BookStack\Entities\Entity); ?>

        @if (is_null($crumb))
            <?php continue; ?>
        @endif
        @if ($breadcrumbCount !== 0 && !$isEntity)
            <div class="separator">@icon('chevron-right')</div>
        @endif

        @if (is_string($crumb))
            <a href="{{  url($key)  }}">
                {{ $crumb }}
            </a>
        @elseif (is_array($crumb))
            <a href="{{  url($key)  }}" class="icon-list-item outline-hover">
                <span>@icon($crumb['icon'])</span>
                <span>{{ $crumb['text'] }}</span>
            </a>
        @elseif($isEntity && userCan('view', $crumb))
            @if($breadcrumbCount > 0)
                @include('bookstack::partials.breadcrumb-listing', ['entity' => $crumb])
            @endif
            <a href="{{ $crumb->getUrl() }}" class="text-{{$crumb->getType()}} icon-list-item outline-hover">
                <span>@icon($crumb->getType())</span>
                <span>
                    {{ $crumb->getShortName() }}
                </span>
            </a>
        @endif
        <?php $breadcrumbCount++; ?>
    @endforeach
</nav>