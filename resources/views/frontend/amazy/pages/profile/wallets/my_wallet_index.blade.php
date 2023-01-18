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
                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_24 f_w_700 mb_20">{{ __('wallet.my_wallet') }}</h4>
                    </div>

                    <div class="dashboard_wallet_boxes mb_40">
                        <div class="singl_dashboard_wallet green_box d-flex align-items-center justify-content-center flex-column">
                            <h4 class="font_16 f_w_400 lh-1">{{ __('wallet.running_balance') }}</h4>
                            <h3 class="f_w_700 m-0 lh-1">{{ auth()->check()?single_price(auth()->user()->CustomerCurrentWalletAmounts):single_price(0.00) }}</h3>
                        </div>
                        <div class="singl_dashboard_wallet pink_box d-flex align-items-center justify-content-center flex-column">
                            <h4 class="font_16 f_w_400 lh-1">{{ __('wallet.pending_balance') }}</h4>
                            <h3 class="f_w_700 m-0 lh-1">{{ auth()->check()?single_price(auth()->user()->CustomerCurrentWalletPendingAmounts):single_price(0.00) }}</h3>
                        </div>
                        <div  data-bs-toggle="modal" data-bs-target="#recharge_wallet" class="singl_dashboard_wallet bordered d-flex align-items-center justify-content-center flex-column gj-cursor-pointer ">
                            <h4 class="font_16 f_w_400 lh-1 mb_10 mute_text">{{__('wallet.recharge_wallet')}}</h4>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                                <path id="plus_1_" data-name="plus (1)" d="M12.5,0A12.5,12.5,0,1,0,25,12.5,12.514,12.514,0,0,0,12.5,0Zm0,23.437A10.937,10.937,0,1,1,23.438,12.5,10.95,10.95,0,0,1,12.5,23.438ZM19.435,12.5a.781.781,0,0,1-.781.781H13.282v5.371a.781.781,0,0,1-1.563,0V13.282H6.349a.781.781,0,1,1,0-1.563H11.72V6.349a.781.781,0,1,1,1.563,0V11.72h5.371A.781.781,0,0,1,19.435,12.5Z" transform="translate(-0.001 -0.001)" fill="#687083"/>
                            </svg>
                        </div>
                    </div>

                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_20 f_w_700 mb_20">{{__('wallet.wallet_recharge_history')}}</h4>
                    </div>
                    <div class="dashboard_white_box_body">
                        <div class="table_border_whiteBox mb_30">
                            <div class="table-responsive">
                                <table class="table amazy_table style3 mb-0">
                                    <thead>
                                        <tr>
                                        <th class="font_14 f_w_700 priamry_text" scope="col">{{ __('common.date') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.txn_id') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.amount') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.type') }}</th>
                                        <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.payment_method') }}</th>
                                        <th class="font_14 f_w_700 priamry_text" scope="col">{{ __('common.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $key => $transaction)
                                            <tr>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at)) }}</span>
                                                </td>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ $transaction->txn_id }}</span>
                                                </td>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ single_price($transaction->amount) }}</span>
                                                </td>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ $transaction->type }}</span>
                                                </td>
                                                <td>
                                                    <span class="font_14 f_w_500 mute_text">{{ $transaction->GatewayName }}</span>
                                                </td>
                                                <td>
                                                    @if ($transaction->status == 1)
                                                        <a class="table_badge_btn style4 text-nowrap">{{__('common.approved')}}</a>
                                                    @else
                                                        <a class="table_badge_btn style3 text-nowrap">{{__('common.pending')}}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if(count($transactions) < 1)
                                    <p class="empty_p">{{ __('common.empty_list') }}.</p>
                                @endif
                            </div>
                        </div>
                        @if($transactions->lastPage() > 1)
                            <x-pagination-component :items="$transactions" type=""/>
                        @endif
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
            $(document).ready(function(){
                $(document).on('submit', '#recharge_form', function(event){
                    $('#error_amount').text('');

                    let amount = $('#recharge_amount').val();
                    let val_check = 0;
                    if(amount == '' || amount < 1){
                        $('#error_amount').text('The amount field is required.');
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }
                });

                $(document).on('submit', '#redeem_form', function(event){
                    $('#error_secret_code').text('');

                    let secret_code = $('#secret_code').val();
                    let val_check = 0;
                    if(secret_code == ''){
                        $('#error_secret_code').text('The Secret code field is required.');
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }
                });

            });
        })(jQuery);
    </script>
@endpush