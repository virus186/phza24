<div class="dropdown CRM_dropdown">
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
        @lang('common.select')
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a data-id="{{$value->id}}" class="dropdown-item copy_id">{{ __('product.Copy ID') }}</a>
        @if(permissionCheck('tags.edit'))
        <a class="dropdown-item edit_tag" data-value="{{$value}}" type="button">{{__('common.edit')}}</a>
        @endif
        @if(permissionCheck('tags.destroy'))
        <a class="dropdown-item"
            onclick="confirm_modal('{{route('tags.destroy', $value->id)}}');">{{__('common.delete')}}</a>
        @endif
    </div>
</div>
