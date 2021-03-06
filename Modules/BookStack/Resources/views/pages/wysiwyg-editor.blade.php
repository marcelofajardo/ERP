<div wysiwyg-editor class="flex-fill flex">

    @exposeTranslations([
        'bookstack::errors.image_upload_error',
    ])

    <textarea id="html-editor"  name="html" rows="5" v-pre
          @if($errors->has('html')) class="text-neg" @endif>@if(isset($model) || old('html')){!!( old('html') ? old('html') : $model->html)!!}@endif</textarea>
</div>

@if($errors->has('html'))
    <div class="text-neg text-small">{{ $errors->first('html') }}</div>
@endif