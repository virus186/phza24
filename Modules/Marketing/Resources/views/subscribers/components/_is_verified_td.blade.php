@if($subscriber->is_verified)
<span class="badge_1">{{__('common.yes')}}</span>
@else
<span class="badge_2">{{__('common.no')}}</span>
@endif