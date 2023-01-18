<div class="table_border_whiteBox mb_30">
    <div class="table-responsive">
        <table class="table amazy_table style4 mb-0">
            <thead>
                <tr>
                    <th class="font_14 f_w_700 priamry_text text-nowrap" scope="col">{{__('customer_panel.coupon_value')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('customer_panel.store_name')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('common.coupon_code')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('customer_panel.validity')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('common.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $key => $coupon)
                    <tr>
                        <td>
                            <span class="font_16  f_w_700 text-uppercase secondary_text gray_color_1 theme_border2 line_badge_btn3 text-nowrap">
                                @if ($coupon->coupon->coupon_type == 3)
                                    {{ single_price($coupon->coupon->discount) }}
                                @else
                                    @if ($coupon->coupon->discount_type == 0)
                                        {{ $coupon->coupon->discount }} %
                                    @else
                                        {{ single_price($coupon->coupon->discount) }}
                                    @endif
                                @endif {{__('common.off')}}
                            </span>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text"> @if(@$coupon->coupon->user->role->type == 'seller') {{@$coupon->coupon->user->SellerAccount->seller_shop_display_name}} @else {{app('general_setting')->company_name}} @endif</span>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text text-nowrap">{{ @$coupon->coupon->coupon_code }}</span>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text text-nowrap">{{__('common.start')}}
                                {{ date('dS M, Y', strtotime(@$coupon->coupon->start_date)) }} <br> {{__('common.end')}}
                                {{ date('dS M, Y', strtotime(@$coupon->coupon->end_date)) }}</span>
                        </td>
                        <td>
                            <div class="copy_del_icon d-flex align-items-center gap_20">
                                <svg data-code="{{ @$coupon->coupon->coupon_code }}" class="gj-cursor-pointer copyBtn" xmlns="http://www.w3.org/2000/svg" width="15"
                                    height="15" viewBox="0 0 15 15">
                                    <g id="Layer_1" transform="translate(-2.16 -2.16)">
                                        <g id="Group_3521" data-name="Group 3521" transform="translate(2.16 2.16)">
                                            <path id="Path_4219" data-name="Path 4219"
                                                d="M15.388,2.16H6.245A1.77,1.77,0,0,0,4.479,3.931v.548H3.925A1.77,1.77,0,0,0,2.16,6.25v9.139A1.77,1.77,0,0,0,3.925,17.16h9.144a1.773,1.773,0,0,0,1.771-1.771v-.548h.548A1.773,1.773,0,0,0,17.16,13.07V3.931A1.773,1.773,0,0,0,15.388,2.16ZM13.756,15.389a.688.688,0,0,1-.687.687H3.925a.685.685,0,0,1-.682-.687V6.25a.686.686,0,0,1,.682-.687h9.144a.688.688,0,0,1,.687.687v9.139Zm2.319-2.319a.688.688,0,0,1-.687.687H14.84V6.25a1.773,1.773,0,0,0-1.771-1.771H5.563V3.931a.685.685,0,0,1,.682-.687h9.144a.688.688,0,0,1,.687.687Z"
                                                transform="translate(-2.16 -2.16)" fill="#687083" />
                                        </g>
                                    </g>
                                </svg>
                                <svg data-id="{{ $coupon->id }}" class="gj-cursor-pointer coupon_delete_btn"
                                    xmlns="http://www.w3.org/2000/svg" width="13.184" height="15"
                                    viewBox="0 0 13.184 15">
                                    <g id="bin" transform="translate(-31)">
                                        <g id="Group_3523" data-name="Group 3523" transform="translate(31)">
                                            <g id="Group_3522" data-name="Group 3522" transform="translate(0)">
                                                <path id="Path_4220" data-name="Path 4220"
                                                    d="M42.865,1.758h-2.2V1.318A1.32,1.32,0,0,0,39.35,0H35.834a1.32,1.32,0,0,0-1.318,1.318v.439h-2.2a1.318,1.318,0,0,0-.41,2.571l.784,9.462A1.326,1.326,0,0,0,34.006,15h7.172a1.326,1.326,0,0,0,1.314-1.209l.784-9.462a1.318,1.318,0,0,0-.41-2.571Zm-7.471-.439a.44.44,0,0,1,.439-.439H39.35a.44.44,0,0,1,.439.439v.439H35.395Zm6.221,12.4a.442.442,0,0,1-.438.4H34.006a.442.442,0,0,1-.438-.4L32.8,4.395h9.592Zm1.25-10.2H32.318a.439.439,0,0,1,0-.879H42.865a.439.439,0,1,1,0,.879Z"
                                                    transform="translate(-31)" fill="#687083" />
                                            </g>
                                        </g>
                                        <g id="Group_3525" data-name="Group 3525"
                                            transform="translate(34.515 5.249)">
                                            <g id="Group_3524" data-name="Group 3524">
                                                <path id="Path_4221" data-name="Path 4221"
                                                    d="M152.32,187.52l-.441-7.108a.441.441,0,0,0-.879.055l.441,7.108a.441.441,0,1,0,.879-.055Z"
                                                    transform="translate(-150.999 -179.999)" fill="#687083" />
                                            </g>
                                        </g>
                                        <g id="Group_3527" data-name="Group 3527"
                                            transform="translate(37.151 5.249)">
                                            <g id="Group_3526" data-name="Group 3526">
                                                <path id="Path_4222" data-name="Path 4222"
                                                    d="M241.441,180a.441.441,0,0,0-.441.441v7.108a.441.441,0,1,0,.881,0v-7.108A.441.441,0,0,0,241.441,180Z"
                                                    transform="translate(-241 -180)" fill="#687083" />
                                            </g>
                                        </g>
                                        <g id="Group_3529" data-name="Group 3529"
                                            transform="translate(39.347 5.249)">
                                            <g id="Group_3528" data-name="Group 3528">
                                                <path id="Path_4223" data-name="Path 4223"
                                                    d="M316.907,180a.441.441,0,0,0-.467.412L316,187.521a.441.441,0,0,0,.879.055l.441-7.108A.441.441,0,0,0,316.907,180Z"
                                                    transform="translate(-315.999 -180)" fill="#687083" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@if(count($coupons) < 1)
    <p class="empty_p">{{ __('common.empty_list') }}.</p>
@elseif($coupons->lastPage() > 1)
    <x-pagination-component :items="$coupons" type=""/>
@endif

