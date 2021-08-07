<div class="comment-box" comment-box style="display:none;">
    <div class="header p-s">{{ trans('bookstack::entities.comment_new') }}</div>
    <div comment-form-reply-to class="reply-row primary-background-light text-muted px-s py-xs mb-s" style="display: none;">
        <div class="grid left-focus v-center">
            <div>
                {!! trans('bookstack::entities.comment_in_reply_to', ['commentId' => '<a href=""></a>']) !!}
            </div>
            <div class="text-right">
                <button class="text-button" action="remove-reply-to">{{ trans('bookstack::common.remove') }}</button>
            </div>
        </div>
    </div>
    <div class="content px-s" comment-form-container>
        <form novalidate>
            <div class="form-group description-input">
                        <textarea name="markdown" rows="3"
                                  placeholder="{{ trans('bookstack::entities.comment_placeholder') }}"></textarea>
            </div>
            <div class="form-group text-right">
                <button type="button" class="button outline"
                        action="hideForm">{{ trans('bookstack::common.cancel') }}</button>
                <button type="submit" class="button">{{ trans('bookstack::entities.comment_save') }}</button>
            </div>
            <div class="form-group loading" style="display: none;">
                @include('bookstack::partials.loading-icon', ['text' => trans('bookstack::entities.comment_saving')])
            </div>
        </form>
    </div>
</div>