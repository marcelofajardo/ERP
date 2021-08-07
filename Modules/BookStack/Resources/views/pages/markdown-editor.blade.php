<div v-pre id="markdown-editor" markdown-editor class="flex-fill flex code-fill">
    @exposeTranslations([
        'bookstack::errors.image_upload_error',
    ])

    <div class="markdown-editor-wrap active">
        <div class="editor-toolbar">
            <span class="float left editor-toolbar-label">{{ trans('bookstack::entities.pages_md_editor') }}</span>
            <div class="float right buttons">
                @if(config('services.drawio'))
                    <button class="text-button" type="button" data-action="insertDrawing">@icon('drawing'){{ trans('bookstack::entities.pages_md_insert_drawing') }}</button>
                    &nbsp;|&nbsp
                @endif
                <button class="text-button" type="button" data-action="insertImage">@icon('image'){{ trans('bookstack::entities.pages_md_insert_image') }}</button>
                &nbsp;|&nbsp;
                <button class="text-button" type="button" data-action="insertLink">@icon('link'){{ trans('bookstack::entities.pages_md_insert_link') }}</button>
            </div>
        </div>

        <div markdown-input class="flex flex-fill">
                        <textarea  id="markdown-editor-input"  name="markdown" rows="5"
                                   @if($errors->has('markdown')) class="text-neg" @endif>@if(isset($model) || old('markdown')){{htmlspecialchars( old('markdown') ? old('markdown') : ($model->markdown === '' ? $model->html : $model->markdown))}}@endif</textarea>
        </div>

    </div>

    <div class="markdown-editor-wrap">
        <div class="editor-toolbar">
            <div class="editor-toolbar-label">{{ trans('bookstack::entities.pages_md_preview') }}</div>
        </div>
        <iframe srcdoc="" class="markdown-display" sandbox="allow-same-origin"></iframe>
    </div>
    <input type="hidden" name="html"/>

</div>



@if($errors->has('markdown'))
    <div class="text-neg text-small">{{ $errors->first('markdown') }}</div>
@endif