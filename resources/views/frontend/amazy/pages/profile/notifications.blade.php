@extends('frontend.amazy.layouts.app')

@section('title')
{{ __('common.notifications') }}
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
                        <h4 class="font_24 f_w_700 flex-fill m-0">{{ __('common.notifications') }} </h4>
                        <div class="wish_selects d-flex align-items-center gap_10 flex-wrap">
                            <a href="{{url('/profile/notification_setting')}}" class="amaz_primary_btn style7 text-nowrap radius_3px">{{__('common.setting')}}</a>
                        </div>
                    </div>
                    <div class="dashboard_white_box_body">
                        <div class="table-responsive mb_30">
                            <table class="table amazy_table style5 mb-0">
                                <thead>
                                    <tr>
                                        <th class="font_14 f_w_700 priamry_text" scope="col">{{ __('common.sl') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.title') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.date') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $notification)
                                        <tr>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{ $loop->index+1 }}</span>
                                            </td>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{ ucfirst($notification->title) }}</span>
                                            </td>
                                            <td>
                                                <span class="font_14 f_w_500 mute_text">{{ date(app('general_setting')->dateFormat->format, strtotime($notification->created_at)) }}</span>
                                            </td>
                                            <td>
                                                @if ($notification->url != "#" || $notification->url != null)
                                                    <a href="{{url('/').$notification->url}}" class="amaz_badge_btn4 text-nowrap text-capitalize text-center">{{__('common.view')}}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($notifications->lastPage() > 1)
                            <x-pagination-component :items="$notifications" type=""/>
                        @elseif(!$notifications->count())
                            <p class="empty_p">{{ __('common.empty_list') }}.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection