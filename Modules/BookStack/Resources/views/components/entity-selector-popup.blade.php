<div id="entity-selector-wrap">
    <div overlay entity-selector-popup>
        <div class="popup-body small" tabindex="-1">
            <div class="popup-header primary-background">
                <div class="popup-title">{{ trans('bookstack::entities.entity_select') }}</div>
                <button type="button" class="popup-header-close">x</button>
            </div>
            @include('bookstack::components.entity-selector', ['name' => 'entity-selector'])
            <div class="popup-footer">
                <button type="button" disabled="true" class="button entity-link-selector-confirm corner-button">{{ trans('bookstack::common.select') }}</button>
            </div>
        </div>
    </div>
</div>