
{!! csrf_field() !!}

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
        <label for="tags">{{ trans('bookstack::entities.chapter_tags') }}</label>
    </button>
    <div class="collapse-content" collapsible-content>
        @include('bookstack::components.tag-manager', ['entity' => isset($chapter)?$chapter:null, 'entityType' => 'chapter'])
    </div>
</div>

<div class="form-group text-right">
    <a href="{{ isset($chapter) ? $chapter->getUrl() : $book->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
    <button type="submit" class="button">{{ trans('bookstack::entities.chapters_save') }}</button>
</div>
