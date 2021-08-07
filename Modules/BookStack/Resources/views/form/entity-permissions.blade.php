<form action="{{ $model->getUrl('/permissions') }}" method="POST" entity-permissions-editor>
    {!! csrf_field() !!}
    <input type="hidden" name="_method" value="PUT">

    <p class="mb-none">{{ trans('bookstack::entities.permissions_intro') }}</p>

    <div class="form-group">
        @include('bookstack::form.checkbox', [
            'name' => 'restricted',
            'label' => trans('bookstack::entities.permissions_enable'),
        ])
    </div>

    <table permissions-table class="table permissions-table toggle-switch-list" style="{{ !$model->restricted ? 'display: none' : '' }}">
        <tr>
            <th>{{ trans('bookstack::common.role') }}</th>
            <th @if($model->isA('page')) colspan="3" @else colspan="4" @endif>
                {{ trans('bookstack::common.actions') }}
                <a href="#" permissions-table-toggle-all class="text-small ml-m text-primary">{{ trans('bookstack::common.toggle_all') }}</a>
            </th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td width="33%" class="pt-m">
                    {{ $role->name }}
                    <a href="#" permissions-table-toggle-all-in-row class="text-small float right ml-m text-primary">{{ trans('bookstack::common.toggle_all') }}</a>
                </td>
                <td>@include('bookstack::form.restriction-checkbox', ['name'=>'restrictions', 'label' => trans('bookstack::common.view'), 'action' => 'view'])</td>
                @if(!$model->isA('page'))
                    <td>@include('bookstack::form.restriction-checkbox', ['name'=>'restrictions', 'label' => trans('bookstack::common.create'), 'action' => 'create'])</td>
                @endif
                <td>@include('bookstack::form.restriction-checkbox', ['name'=>'restrictions', 'label' => trans('bookstack::common.update'), 'action' => 'update'])</td>
                <td>@include('bookstack::form.restriction-checkbox', ['name'=>'restrictions', 'label' => trans('bookstack::common.delete'), 'action' => 'delete'])</td>
            </tr>
        @endforeach
    </table>

    <div class="text-right">
        <a href="{{ $model->getUrl() }}" class="button outline">{{ trans('bookstack::common.cancel') }}</a>
        <button type="submit" class="button">{{ trans('bookstack::entities.permissions_save') }}</button>
    </div>
</form>