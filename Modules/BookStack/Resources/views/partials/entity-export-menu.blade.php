<div dropdown class="dropdown-container" id="export-menu">
    <div dropdown-toggle class="icon-list-item"
         aria-haspopup="true" aria-expanded="false" aria-label="{{ trans('bookstack::entities.export') }}" tabindex="0">
        <span>@icon('export')</span>
        <span>{{ trans('bookstack::entities.export') }}</span>
    </div>
    <ul class="wide dropdown-menu" role="menu">
        <li><a href="{{ $entity->getUrl('/export/html') }}" target="_blank">{{ trans('bookstack::entities.export_html') }} <span class="text-muted float right">.html</span></a></li>
        <!-- <li><a href="{{ $entity->getUrl('/export/pdf') }}" target="_blank">{{ trans('bookstack::entities.export_pdf') }} <span class="text-muted float right">.pdf</span></a></li> -->
        <li><a href="{{ $entity->getUrl('/export/plaintext') }}" target="_blank">{{ trans('bookstack::entities.export_text') }} <span class="text-muted float right">.txt</span></a></li>
    </ul>
</div>