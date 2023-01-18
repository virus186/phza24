@extends('frontend.default.layouts.app')

@section('styles')
    <style>
        .user-list {
            background-color: #f4f7f9;
            margin-top: 30px;
            min-height: 350px;
        }
        @media (max-width: 991px) {
            .cart_part tbody td {
                display: table-cell;
                white-space: nowrap;
            }
        }
        @media (max-width: 768px) {
            .user-list {
                min-height: 220px;
            }
        }
        .fund_add {
            background-color: #fd0303!important;
        }
        .empty_p {
            margin-left: 44%;
            margin-top: 100px;
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('breadcrumb')

{{ __('wallet.my_wallet') }}
@endsection
@section('title')

{{ __('wallet.my_wallet') }}
@endsection

@section('content')

@include('frontend.default.partials._breadcrumb')
@php
    session()->forget('wallet_recharge');
@endphp
<!--  dashboard part css here -->
<section class="dashboard_part bg-white padding_top">
    <div class="container">
        <div class="row">
            @include('frontend.default.pages.profile.partials._menu')
            <div class="col-xl-9 col-md-7">
                <div class="dashboard_item">
                    <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="single_dashboard_item order">
                                <a href="#" data-toggle="modal" data-target="#Item_Details"><i class="ti-reload fund_add"></i></a>
                                <div class="single_dashboard_text">
                                    <h4>00</h4>
                                    <p>{{ __('defaultTheme.add_fund') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="single_dashboard_item disputes">
                                <i class="ti-wallet"></i>
                                <div class="single_dashboard_text">
                                    
                                    <h4>{{ auth()->check()?single_price(auth()->user()->CustomerCurrentWalletAmounts):single_price(0.00) }}</h4>
                                    <p>{{ __('wallet.running_balance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="single_dashboard_item disputes">
                                <i class="ti-wallet"></i>
                                <div class="single_dashboard_text">
                                    <h4>{{ auth()->check()?single_price(auth()->user()->CustomerCurrentWalletPendingAmounts):single_price(0.00) }}</h4>
                                    <p>{{ __('wallet.pending_balance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <button data-toggle="modal" data-target="#RechargeUsingGiftCard" type="button" class="btn_small">{{ __('wallet.recharge_using_gift_card') }}</button>
                        </div>
                    </div>
                </div>
                <div class="coupons_item mt-2">
                    <div class="single_coupons_item cart_part">
                        <div class="table-responsive user-list">
                            <table class="table table-hover red-header" >
                                <thead>
                                    <tr>
                                        <th width="18%">{{ __('common.date') }}</th>
                                        <th width="30%">{{ __('common.txn_id') }}</th>
                                        <th width="28%">{{ __('common.amount') }}</th>
                                        <th width="10%">{{ __('common.type') }}</th>
                                        <th width="12%">{{ __('common.payment_method') }}</th>
                                        <th width="12%">{{ __('common.approval') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at)) }}</td>
                                            <td>{{ $transaction->txn_id }}</td>
                                            <td>{{ single_price($transaction->amount) }}</td>
                                            <td>{{ $transaction->type }}</td>
                                            <td>{{ $transaction->GatewayName }}</td>
                                            <td>
                                                @if ($transaction->status == 1)
                                                    <span>Approved</span>
                                                @else
                                                    <span>Pending</span>
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
                    
                    @if (count($transactions) > 0)
                    <x-pagination-component :items="$transactions" type=""/>
                    @endif
                </div>
             </div>
        </div>
    </div>
</section>
@include(theme('pages.profile.wallets.components._recharge_modal'))


{{-- Recharge Using Gift Card --}}
<div class="modal fade admin-query" id="RechargeUsingGiftCard">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('common.enter_secret_code_of_gift_card') }}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>

            <div class="modal-body">
                <section class="send_query bg-white contact_form">
                    <form id="redeem_form" action="{{route('frontend.wallet.recharge_via_gift_card')}}" method="post" class="send_query_form">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('common.secret_code') }}<span class="text-danger">*</span></label>
                            <input type="text" id="secret_code" name="secret_code" placeholder="{{ __('common.secret_code') }}" class="form-control">
                            <span class="text-danger"  id="error_secret_code"></span>
                        </div>
                        <div class="send_query_btn">
                            <button id="contactBtn" type="submit" class="btn_1">{{ __('common.redeem_now') }}</button>
                        </div>
                    </form>
                </section>
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
