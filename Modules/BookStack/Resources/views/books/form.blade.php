
{{ csrf_field() }}
<div class="form-group title-input">
    <label for="name">{{ trans('bookstack::common.name') }}</label>
    @include('bookstack::form.text', ['name' => 'name'])
</div>

<div class="form-group description-input">
    <label for="description">{{ trans('bookstack::common.description') }}</label>
    @include('bookstack::form.textarea', ['name' => 'description'])
</div>

<div class="form-group" collapsible id="logo-control">
    <button type="button" class="collapse-title text-primary" collapsible-trigger aria-expanded="false">
        <label>{{ trans('bookstack::common.cover_image') }}</label>
    </button>
    <div class="collapse-content" collapsible-content>
        <p class="small">{{ trans('bookstack::common.cover_image_description') }}</p>

        @include('bookstack::components.image-picker', [
            'defaultImage' => url('/book_default_cover.png'),
            'currentImage' => (isset($model) && $model->cover) ? $model->getBookCover() : url('/book_default_cover.png') ,
            'name' => 'image',
            'imageClass' => 'cover'
        ])
    </div>
</div>

<div class="form-group" collapsible id="tags-control">
    <button type="button" class="collapse-title text-primary" collapsible-trigger aria-expanded="false">
        <label for="tag-manager">{{ trans('bookstack::entities.book_tags') }}</label>
    </button>
    <div class="collapse-content" collapsible-content>
        @include('bookstack::components.tag-manager', ['entity' => isset($book)?$book:null, 'entityType' => 'chapter'])
    </div>
</div>

<div class="form-group text-right">
    <a href="{{ isset($book) ? $book->getUrl() : url('/kb/books') }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
    <button type="submit" class="button">{{ trans('bookstack::entities.books_save') }}</button>
</div>