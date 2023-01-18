<div class="dropdown CRM_dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ __('common.select') }}
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
        @if(permissionCheck('marketing.subscriber.delete'))
            <a class="dropdown-item delete_subscription" data-id="{{ $subscriber->id }}">{{ __('common.delete') }}</a>
        @endif
        @if(!$subscriber->is_verified)
            <a class="dropdown-item send_verification_link" data-id="{{ $subscriber->id }}">{{ __('marketing.Send verify link') }}</a>
        @endif
    </div>
</div>
