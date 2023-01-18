@extends('frontend.amazy.layouts.app')

@section('title')
{{ __('common.notifications') }} {{ __('common.setting') }}
@endsection

@section('content')

<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="dashboard_white_box style2 bg-white mb_25">
                    <div class="dashboard_white_box_header d-flex align-items-center gap_20 flex-wrap mb_20">
                        <h4 class="font_24 f_w_700 flex-fill m-0">{{ __('common.notifications') }} {{ __('common.setting') }} </h4>
                        
                    </div>
                    <div class="dashboard_white_box_body">
                        <div class="table-responsive mb_30">
                            <table class="table amazy_table style5 mb-0">
                                <thead>
                                    <tr>
                                        <th class="font_14 f_w_700 priamry_text" scope="col">{{ __('common.sl') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('hr.event') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.type') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userNotificationSettings as $userNotificationSetting)
                                        <tr>
                                            <form action="{{route('frontend.notification_setting.update',$userNotificationSetting->id)}}" method="POST">
                                                @csrf
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ $loop->index+1 }}</span>
                                                </td>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ $userNotificationSetting->notification_setting->event }}</span>
                                                </td>
                                                <td>
                                                    @if (Str::contains($userNotificationSetting->notification_setting->type,'email'))
                                                        <label class="primary_checkbox d-flex">
                                                            <input class="type check{{ $userNotificationSetting->id }}" data-value="{{ $userNotificationSetting->id }}" name="type[]" id="status" value="email" @if (Str::contains($userNotificationSetting->type,'email')) checked @endif
                                                                type="checkbox">
                                                            <span class="checkmark mr_15"></span>
                                                            <span class="label_name f_w_400 ">{{__('common.email')}}</span>
                                                        </label>
                                                    @endif

                                                    @if (Str::contains($userNotificationSetting->notification_setting->type,'mobile'))
                                                        <label class="primary_checkbox d-flex">
                                                            <input class="type check{{ $userNotificationSetting->id }}" data-value="{{ $userNotificationSetting->id }}" name="type[]" id="status" value="mobile" @if (Str::contains($userNotificationSetting->type,'mobile')) checked @endif
                                                            type="checkbox">
                                                            <span class="checkmark mr_15"></span>
                                                            <span class="label_name f_w_400 ">{{__('common.mobile')}}</span>
                                                        </label>
                                                    @endif

                                                    @if (Str::contains($userNotificationSetting->notification_setting->type,'sms'))
                                                        <label class="primary_checkbox d-flex">
                                                            <input class="type check{{ $userNotificationSetting->id }}" data-value="{{ $userNotificationSetting->id }}" name="type[]" id="status" value="sms" @if (Str::contains($userNotificationSetting->type,'sms')) checked @endif
                                                            type="checkbox">
                                                            <span class="checkmark mr_15"></span>
                                                            <span class="label_name f_w_400 ">{{__('common.sms')}}</span>
                                                        </label>
                                                    @endif

                                                    @if (Str::contains($userNotificationSetting->notification_setting->type,'system'))
                                                        <label class="primary_checkbox d-flex">
                                                            <input class="type check{{ $userNotificationSetting->id }}" data-value="{{ $userNotificationSetting->id }}" name="type[]" id="status" value="system" @if (Str::contains($userNotificationSetting->type,'system')) checked @endif
                                                            type="checkbox">
                                                            <span class="checkmark mr_15"></span>
                                                            <span class="label_name f_w_400 ">{{__('common.system')}}</span>
                                                        </label>
                                                    @endif
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    (function($){
        "use strict";
        $(document).ready(function() {
            $(document).on('change', '.type', function(event){
                let user_notification_setting_id = $(this).data('value');
                let check = "check"+user_notification_setting_id;
                var val = [];
                $('.'+check+':checked').each(function(i){
                val[i] = $(this).val();
                });

                $('#pre-loader').show();
                var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', user_notification_setting_id);
                    formData.append('type', val);
                $.ajax({
                        url: "{{ route('frontend.notification_setting.update','') }}"+"/"+user_notification_setting_id,
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            // console.log(response);
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#pre-loader').hide();
                        },
                        error: function(response) {

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').hide();
                        }
                    });
            });
        });

    })(jQuery);



    </script>

@endpush
