<div editor-toolbox class="floating-toolbox">

    <div class="tabs primary-background-light">
        <button type="button" toolbox-toggle aria-expanded="false">@icon('caret-left-circle')</button>
        <button type="button" toolbox-tab-button="tags" title="{{ trans('bookstack::entities.page_tags') }}" class="active">@icon('tag')</button>
        @if(userCan('attachment-create-all'))
            <button type="button" toolbox-tab-button="files" title="{{ trans('bookstack::entities.attachments') }}">@icon('attach')</button>
        @endif
        <button type="button" toolbox-tab-button="templates" title="{{ trans('bookstack::entities.templates') }}">@icon('template')</button>
    </div>

    <div toolbox-tab-content="tags">
        <h4>{{ trans('bookstack::entities.page_tags') }}</h4>
        <div class="px-l">
            @include('bookstack::components.tag-manager', ['entity' => $page, 'entityType' => 'page'])
        </div>
    </div>

    @if(userCan('attachment-create-all'))
        @include('bookstack::pages.attachment-manager', ['page' => $page])
    @endif

    <div toolbox-tab-content="templates">
        <h4>{{ trans('bookstack::entities.templates') }}</h4>

        <div class="px-l">
            @include('bookstack::pages.template-manager', ['page' => $page, 'templates' => $templates])
        </div>

    </div>

</div>
