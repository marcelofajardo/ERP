
<main class="content-wrap mt-m card">

    <div class="grid half v-center">
        <h1 class="list-heading">{{ trans('bookstack::entities.shelves') }}</h1>
        <div class="text-right">
            @include('bookstack::partials.sort', ['options' => $sortOptions, 'order' => $order, 'sort' => $sort, 'type' => 'bookshelves'])
        </div>
    </div>

    @if(count($shelves) > 0)
        @if($view === 'list')
            <div class="entity-list">
                @foreach($shelves as $index => $shelf)
                    @if ($index !== 0)
                        <hr class="my-m">
                    @endif
                    @include('bookstack::shelves.list-item', ['shelf' => $shelf])
                @endforeach
            </div>
        @else
            <div class="grid third">
                @foreach($shelves as $key => $shelf)
                    @include('bookstack::shelves.grid-item', ['shelf' => $shelf])
                @endforeach
            </div>
        @endif
        <div>
            {!! $shelves->render() !!}
        </div>
    @else
        <p class="text-muted">{{ trans('bookstack::entities.shelves_empty') }}</p>
        @if(userCan('bookstack::bookshelf-create-all'))
            <a href="{{ url("/kb/create-shelf") }}" class="button outline">@icon('edit'){{ trans('bookstack::entities.create_now') }}</a>
        @endif
    @endif

</main>
