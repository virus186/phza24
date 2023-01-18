@extends('frontend.amazy.layouts.app')
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="dashboard_white_box style2 bg-white mb_25">
                    @if(isset($myCode))
                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_24 f_w_700 mb_20">{{__('defaultTheme.my_referral_code')}}</h4>
                    </div>

                    <div class="d-flex gap_10 flex-sm-wrap flex-md-nowrap gray_color_1 theme_border padding25 mb_40">
                        <input name="code" id="code" value="{{$myCode->referral_code}}" class="primary_input3 rounded-0 style2  flex-fill" readonly type="text">
                        <button id="copyBtn" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.copy_code')}}</button>
                    </div>

                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_20 f_w_700 mb_20">{{__('defaultTheme.user_list')}}</h4>
                    </div>
                    <div class="dashboard_white_box_body">
                        <div class="table_border_whiteBox mb_30">
                            <div class="table-responsive">
                                <table class="table amazy_table style4 mb-0">
                                    <thead>
                                        <tr>
                                        <th class="font_14 f_w_700 priamry_text" scope="col">{{__('common.sl')}}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{__('common.user')}}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{__('common.date')}}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{__('common.status')}}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{__('defaultTheme.discount_amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($referList as $key => $referral)
                                        <tr>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{$key +1}}</span>
                                            </td>
                                            <td>
                                                <span class="font_16 f_w_500 mute_text">{{textLimit(@$referral->user->first_name. @$referral->user->last_name,20)}}</span><br>
                                                <span class="font_12 f_w_400 mute_text">{{@$referral->user->email?@$referral->user->email:@$referral->user->username}}</span>
                                            </td>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{date(app('general_setting')->dateFormat->format, strtotime($referral->created_at))}} </span>
                                            </td>
                                            <td>
                                            <a href="#" class="table_badge_btn {{$referral->is_use == 1?'style4':'style3'}} text-nowrap">{{$referral->is_use == 1?__('defaultTheme.already_use'):__('defaultTheme.not_used')}}</a>
                                            </td>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{single_price($referral->discount_amount)}} </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($referList->lastPage() > 1)
                            <x-pagination-component :items="$referList" type=""/>
                        @elseif(!$referList->count())
                            <p class="empty_p">{{ __('common.empty_list') }}.</p>
                        @endif
                    </div>
                    @else
                        <div class="dashboard_white_box_header d-flex align-items-center">
                            <h4 class="font_24 f_w_700 mb_20 text-center w-100">{{__('defaultTheme.you_will_get_referral_after')}}</h4>
                        </div>
                    @endif
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

            $(document).ready(function(){
                $(document).on('click', '#copyBtn', function(event){
                    let copyTextarea = document.createElement("textarea");
                    copyTextarea.style.position = "fixed";
                    copyTextarea.style.opacity = "0";
                    copyTextarea.textContent = document.getElementById("code").value;
                    document.body.appendChild(copyTextarea);
                    copyTextarea.select();
                    document.execCommand("copy");
                    document.body.removeChild(copyTextarea);
                    toastr.success("{{__('defaultTheme.code_copied_successfully')}}", "{{__('common.success')}}");
                });
            });

        })(jQuery);
    </script>

@endpush