@extends('frontend.default.layouts.app')

@section('breadcrumb')
    {{ __('customer_panel.my_wallet_recharge') }}
@endsection
@section('title')
    {{ __('customer_panel.my_wallet_recharge') }}
@endsection

@section('styles')
    <style>
        .bank_btn{
            padding: 20px 80px;
        }
        #bKash_button, #sslczPayBtn{
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            height: 50px;
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        #bKash_button img, #sslczPayBtn img{
            width: 100%;
            max-width: 80px;
        }
        
    </style>
@endsection

@section('content')

@include('frontend.default.partials._breadcrumb')

<!--  dashboard part css here -->
<section class="dashboard_part bg-white padding_top">
    @php
        $currency_code = getCurrencyCode();
    @endphp
    <div class="container">
        <div class="row">
            @include('frontend.default.pages.profile.partials._menu')
            <div class="col-xl-9 col-md-7">
                <div class="dashboard_item">
                    <div class="row">
                        <div class="col-12">
                            <h4>{{ __('common.recharge_amount') }} : {{ single_price($converted_amount) }}</h4>
                        </div>
                        <div class="col-12">
                            <div class="deposit_lists_wrapper mb-50">
                                @if (@$payment_gateways->where('method','Stripe')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <form action="{{route('my-wallet.store')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="method" value="Stripe">
                                            <input type="hidden" name="amount" value="{{ $recharge_amount }}">

                                            <!-- single_deposite_item  -->
                                            <button type="submit">
                                                <img src="{{showImage($gateway_activations->where('method', 'Stripe')->first()->logo)}}" alt="">
                                            </button>
                                            @php
                                                $stripe_credential = getPaymentInfoViaSellerId(1, 4);
                                            @endphp
                                            <script
                                                src="https://checkout.stripe.com/checkout.js"
                                                class="stripe-button"
                                                data-key="{{ @$stripe_credential->perameter_1 }}"
                                                data-name="Stripe Payment"
                                                data-image="{{showImage(app('general_setting')->favicon)}}"
                                                data-locale="auto"
                                                data-currency="{{$currency_code}}">
                                            </script>
                                        </form>
                                    </div>
                                @endif

                                @if(isModuleActive('Bkash') && @$payment_gateways->where('method','Bkash')->first()->active_status == 1)
                                    @php
                                        $credential = getPaymentInfoViaSellerId(1, 15);
                                    @endphp
                                    @include('bkash::partials._wallet_recharge_frontend', ['credential' => $credential])   
                                @endif

                                @if(isModuleActive('SslCommerz') && @$payment_gateways->where('method','SslCommerz')->first()->active_status == 1)
                                    @include('sslcommerz::partials._wallet_recharge_frontend')
                                @endif

                                @if (isModuleActive('MercadoPago') && @$payment_gateways->where('method','Mercado Pago')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#MercadoPagoModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'Mercado Pago')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','RazorPay')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <form action="{{ route('my-wallet.store') }}" method="POST">
                                            <input type="hidden" name="method" value="RazorPay">
                                            <input type="hidden" name="amount" value="{{ $recharge_amount * 100 }}">

                                            <button type="submit">
                                                <img src="{{showImage($gateway_activations->where('method', 'RazorPay')->first()->logo)}}" alt="">
                                            </button>
                                            @csrf
                                            @php
                                                $razor_credential = getPaymentInfoViaSellerId(1, 6);
                                            @endphp
                                            <script
                                                src="https://checkout.razorpay.com/v1/checkout.js"
                                                data-key="{{ @$razor_credential->perameter_1 }}"
                                                data-amount="{{ $recharge_amount * 100 }}"
                                                data-name="{{str_replace('_', ' ',app('general_setting')->company_name ) }}"
                                                data-description="Wallet Recharge"
                                                data-image="{{showImage(app('general_setting')->favicon)}}"
                                                data-prefill.name="{{ auth()->user()->username }}"
                                                data-prefill.email="{{ auth()->user()->email }}"
                                                data-theme.color="#ff7529">
                                            </script>
                                        </form>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','PayPal')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <form action="{{route('my-wallet.store')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="method" value="Paypal">
                                            <input type="hidden" name="purpose" value="wallet_recharge">
                                            <input type="hidden" name="amount" value="{{ $recharge_amount }}">

                                            <button type="submit">
                                                <img src="{{showImage($gateway_activations->where('method', 'PayPal')->first()->logo)}}" alt="">
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','PayStack')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <form action="{{ route('my-wallet.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ @Auth::user()->email}}"> {{-- required --}}
                                            <input type="hidden" name="orderID" value="{{md5(uniqid(rand(), true))}}">
                                            <input type="hidden" name="amount" value="{{ $recharge_amount*100}}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="currency" value="{{$currency_code}}">

                                            <input type="hidden" name="method" value="Paystack">

                                            <button type="submit">
                                                <img src="{{showImage($gateway_activations->where('method', 'PayStack')->first()->logo)}}">
                                            </button>

                                        </form>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','Bank Payment')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#exampleModal" class="{{$gateway_activations->where('method', 'Bank Payment')->first()->logo == null?'bank_btn':''}}">
                                            <img src="{{showImage($gateway_activations->where('method', 'Bank Payment')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif



                                @if (@$payment_gateways->where('method','PayTM')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#PayTMModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'PayTM')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','Instamojo')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#InstamojoModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'Instamojo')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','Midtrans')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <form action="{{ route('my-wallet.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="method" value="Midtrans">
                                            <input type="hidden" name="amount" value="{{ $recharge_amount * 100 }}">
                                            <input type="hidden" name="ref_no" value="{{ rand(1111,99999).'-'.date('y-m-d').'-'.auth()->user()->id }}">
                                            <button type="submit">
                                                <img src="{{showImage($gateway_activations->where('method', 'Midtrans')->first()->logo)}}" alt="">
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','PayUMoney')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#PayUMoneyModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'PayUMoney')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','JazzCash')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#JazzCashModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'JazzCash')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','Google Pay')->first()->active_status == 1)
                                    <div class="single_deposite" id="gPayBtn" href="javascript:void(0);">
                                        <a id="buyButton">
                                            <img src="{{showImage($gateway_activations->where('method', 'Google Pay')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif

                                @if (@$payment_gateways->where('method','FlutterWave')->first()->active_status == 1)
                                    <div class="single_deposite">
                                        <a href="#" data-toggle="modal" data-target="#FlutterWaveModal">
                                            <img src="{{showImage($gateway_activations->where('method', 'FlutterWave')->first()->logo)}}" alt="">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include(theme('pages.profile.wallets.components._bank_payment_modal'))
@include(theme('pages.profile.wallets.components._instamojo_payment_modal'))
@include(theme('pages.profile.wallets.components._paytm_payment_modal'))
@include(theme('pages.profile.wallets.components._payumoney_payment_modal'))
@include(theme('pages.profile.wallets.components._jazzcash_payment_modal'))
@include(theme('pages.profile.wallets.components._google_pay_script'))
@include(theme('pages.profile.wallets.components._flutter_wave_payment_modal'))
@if (isModuleActive('MercadoPago') && @$payment_gateways->where('method','Mercado Pago')->first()->active_status == 1)
    @include(theme('pages.profile.wallets.components._mercado_pago_modal'))
@endif
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($) {
        	"use strict";
            $(document).ready(function() {
                $(".stripe-button-el").remove();
                $(".razorpay-payment-button").hide();

                $('#bankpayment_submit_btn').removeAttr('disabled');
                $('#paytm_submit_btn').removeAttr('disabled');
                $('#intamojo_submit_btn').removeAttr('disabled');
                $('#payumoney_submit_btn').removeAttr('disabled');
                $('#flatter_wave_submit_btn').removeAttr('disabled');

                $(document).on('submit', '#bank_payment_form', function(event){

                    $('#bank_name').text('');
                    $('#branch_name').text('');
                    $('#account_number').text('');
                    $('#account_holder').text('');

                    let name = $('#bank_payment_form > div > div:nth-child(3) > div:nth-child(1) > input').val();
                    let branch_name = $('#bank_payment_form > div > div:nth-child(3) > div:nth-child(2) > input').val();
                    let account_number = $('#bank_payment_form > div > div:nth-child(4) > div:nth-child(1) > input').val();
                    let account_holder = $('#bank_payment_form > div > div:nth-child(4) > div:nth-child(2) > input').val();

                    let val_check = 0;
                    if(name == ''){
                        $('#bank_name').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(branch_name == ''){
                        $('#branch_name').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(account_number == ''){
                        $('#account_number').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(account_holder == ''){
                        $('#account_holder').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(val_check == 1){
                        event.preventDefault();
                    }

                });

                $(document).on('submit', '#paytm_form', function(event){

                    $('#paytm_form > div:nth-child(3) > div:nth-child(1) > span').text('');
                    $('#paytm_form > div:nth-child(3) > div:nth-child(2) > span').text('');
                    $('#error_mobile').text('');
                    $('#paytm_form > div.row.mb-20 > div:nth-child(2) > span').text('');

                    let name = $('#paytm_form > div:nth-child(3) > div:nth-child(1) > input').val();
                    let email = $('#paytm_form > div:nth-child(3) > div:nth-child(2) > input').val();
                    let mobile = $('#paytm_form > div.row.mb-20 > div:nth-child(1) > input').val();
                    let amount = $('#paytm_form > div.row.mb-20 > div:nth-child(2) > input').val();

                    let val_check = 0;
                    if(name == ''){
                        $('#paytm_form > div:nth-child(3) > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(email == ''){
                        $('#paytm_form > div:nth-child(3) > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(mobile == ''){
                        $('#error_mobile').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(amount == ''){
                        $('#paytm_form > div.row.mb-20 > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }


                });

                $(document).on('submit', '#instamojo_form', function(event){

                    $('#instamojo_form > div:nth-child(3) > div:nth-child(1) > span').text('');
                    $('#instamojo_form > div:nth-child(3) > div:nth-child(2) > span').text('');
                    $('#instamojo_form > div.row.mb-20 > div:nth-child(1) > span').text('');
                    $('#instamojo_form > div.row.mb-20 > div:nth-child(2) > span').text('');

                    let name = $('#instamojo_form > div:nth-child(3) > div:nth-child(1) > input').val();
                    let email = $('#instamojo_form > div:nth-child(3) > div:nth-child(2) > input').val();
                    let mobile = $('#instamojo_form > div.row.mb-20 > div:nth-child(1) > input').val();
                    let amount = $('#instamojo_form > div.row.mb-20 > div:nth-child(2) > input').val();

                    let val_check = 0;
                    if(name == ''){
                        $('#instamojo_form > div:nth-child(3) > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(email == ''){
                        $('#instamojo_form > div:nth-child(3) > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(mobile == ''){
                        $('#instamojo_form > div.row.mb-20 > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(amount == ''){
                        $('#instamojo_form > div.row.mb-20 > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }
                });

                $(document).on('submit', '#payumoney_form', function(event){
                    $('#payumoney_form > div.row > div:nth-child(1) > span').text('');
                    $('#payumoney_form > div.row > div:nth-child(2) > span').text('');
                    $('#payumoney_form > div.row > div:nth-child(3) > span').text('');
                    $('#payumoney_form > div.row > div:nth-child(4) > span').text('');

                    let name = $('#payumoney_form > div.row > div:nth-child(1) > input').val();
                    let email = $('#payumoney_form > div.row > div:nth-child(2) > input').val();
                    let mobile = $('#payumoney_form > div.row > div:nth-child(3) > input').val();
                    let amount = $('#payumoney_form > div.row > div:nth-child(4) > input').val();

                    let val_check = 0;
                    if(name == ''){
                        $('#payumoney_form > div.row > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(email == ''){
                        $('#payumoney_form > div.row > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(mobile == ''){
                        $('#payumoney_form > div.row > div:nth-child(3) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(amount == ''){
                        $('#payumoney_form > div.row > div:nth-child(4) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }
                });

                $(document).on('submit', '#flatter_wave_form', function(event){
                    $('#flatter_wave_form > div:nth-child(3) > div:nth-child(1) > span').text('');
                    $('#flatter_wave_form > div:nth-child(3) > div:nth-child(2) > span').text('');
                    $('#flatter_wave_form > div.row.mb-20 > div:nth-child(1) > span').text('');
                    $('#flatter_wave_form > div.row.mb-20 > div:nth-child(2) > span').text('');

                    let name = $('#flatter_wave_form > div:nth-child(3) > div:nth-child(1) > input').val();
                    let email = $('#flatter_wave_form > div:nth-child(3) > div:nth-child(2) > input').val();
                    let mobile = $('#flatter_wave_form > div.row.mb-20 > div:nth-child(1) > input').val();
                    let amount = $('#flatter_wave_form > div.row.mb-20 > div:nth-child(2) > input').val();

                    let val_check = 0;
                    if(name == ''){
                        $('#flatter_wave_form > div:nth-child(3) > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(email == ''){
                        $('#flatter_wave_form > div:nth-child(3) > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(mobile == ''){
                        $('#flatter_wave_form > div.row.mb-20 > div:nth-child(1) > span').text("{{__('validation.this_field_is_required')}}");
                        val_check = 1;
                    }
                    if(amount == ''){
                        $('#flatter_wave_form > div.row.mb-20 > div:nth-child(2) > span').text("{{__('validation.this_field_is_required')}}");
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
