{{ csrf_field() }}

<div class="form-group title-input">
    <label for="name">{{ trans('bookstack::common.name') }}</label>
    @include('bookstack::form.text', ['name' => 'name'])
</div>

<div class="form-group description-input">
    <label for="description">{{ trans('bookstack::common.description') }}</label>
    @include('bookstack::form.textarea', ['name' => 'description'])
</div>

<div shelf-sort class="grid half gap-xl">
    <div class="form-group">
        <label for="books">{{ trans('bookstack::entities.shelves_books') }}</label>
        <input type="hidden" id="books-input" name="books"
               value="{{ isset($shelf) ? $shelf->books->implode('id', ',') : '' }}">
        <div class="scroll-box" shelf-sort-assigned-books data-instruction="{{ trans('bookstack::entities.shelves_drag_books') }}">
            @if (isset($shelfBooks) && count($shelfBooks) > 0)
                @foreach ($shelfBooks as $book)
                    <div data-id="{{ $book->id }}" class="scroll-box-item">
                        <a href="{{ $book->getUrl() }}" class="text-book">@icon('book'){{ $book->name }}</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="books">{{ trans('bookstack::entities.shelves_add_books') }}</label>
        <div class="scroll-box">
            @foreach ($books as $book)
                <div data-id="{{ $book->id }}" class="scroll-box-item">
                    <a href="{{ $book->getUrl() }}" class="text-book">@icon('book'){{ $book->name }}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>



<div class="form-group" collapsible id="logo-control">
    <button type="button" class="collapse-title text-primary" collapsible-trigger aria-expanded="false">
        <label>{{ trans('bookstack::common.cover_image') }}</label>
    </button>
    <div class="collapse-content" collapsible-content>
        <p class="small">{{ trans('bookstack::common.cover_image_description') }}</p>

        @include('bookstack::components.image-picker', [
            'defaultImage' => url('/book_default_cover.png'),
            'currentImage' => (isset($shelf) && $shelf->cover) ? $shelf->getBookCover() : url('/book_default_cover.png') ,
            'name' => 'image',
            'imageClass' => 'cover'
        ])
    </div>
</div>

<div class="form-group" collapsible id="tags-control">
    <button type="button" class="collapse-title text-primary" collapsible-trigger aria-expanded="false">
        <label for="tag-manager">{{ trans('bookstack::entities.shelf_tags') }}</label>
    </button>
    <div class="collapse-content" collapsible-content>
        @include('bookstack::components.tag-manager', ['entity' => $shelf ?? null, 'entityType' => 'bookshelf'])
    </div>
</div>

<div class="form-group text-right">
    <a href="{{ isset($shelf) ? $shelf->getUrl() : url('/kb/shelves') }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
    <button type="submit" class="button">{{ trans('bookstack::entities.shelves_save') }}</button>
</div>