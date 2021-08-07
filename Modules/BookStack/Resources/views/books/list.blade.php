
<main class="content-wrap mt-m card">
    <div class="grid half v-center no-row-gap">
        <h1 class="list-heading">{{ trans('bookstack::entities.books') }}</h1>
        <div class="text-m-right my-m">

            @include('bookstack::partials.sort', ['options' => $sortOptions, 'order' => $order, 'sort' => $sort, 'type' => 'books'])

        </div>
    </div>
    @if(count($books) > 0)
        @if($view === 'list')
            <div class="entity-list">
                @foreach($books as $book)
                    @include('bookstack::books.list-item', ['book' => $book])
                @endforeach
            </div>
        @else
             <div class="grid third">
                @foreach($books as $key => $book)
                    @include('bookstack::books.grid-item', ['book' => $book])
                @endforeach
             </div>
        @endif
        <div>
            {!! $books->render() !!}
        </div>
    @else
        <p class="text-muted">{{ trans('bookstack::entities.books_empty') }}</p>
        @if(userCan('books-create-all'))
            <a href="{{ url("/kb/create-book") }}" class="text-pos">@icon('edit'){{ trans('bookstack::entities.create_now') }}</a>
        @endif
    @endif
</main>