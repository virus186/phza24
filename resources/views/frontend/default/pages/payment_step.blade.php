@extends('frontend.default.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/checkout.css'))}}" />
    <style>
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }
        .form-control {
            border-radius: 0;
            height: 50px;
            margin-bottom: 17px;
            color: #8f8f8f;
            font-weight: 300;
        }
        .input_group_text {
            background-color: #ff0027;
            border-radius: 0;
            border: 1px solid transparent;
            color: #fff;
            font-size: 13px;
            text-transform: none;
            font-weight: 500;
            padding: 13px 30px;
            cursor: pointer;
        }
        .cursor_pointer{
            cursor: pointer!important;
        }
    </style>
@endsection
@section('breadcrumb')
    {{ __('defaultTheme.select_payment') }}
@endsection
@section('title')
    {{ __('defaultTheme.select_payment') }}
@endsection
@section('content')

    @include('frontend.default.partials._breadcrumb')
    <div id="mainDiv">
        @include('frontend.default.partials._payment_step_details')
    </div>

@endsection

@push('scripts')
    @if(isModuleActive('Bkash'))
        @include('bkash::partials._bkash_data')
    @endif
    
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('change', 'input[type=radio][name=payment_method]', function(){
                    let method = $(this).data('name');
                    $('#order_payment_method').val($(this).val());
                    let payment_id = $('#off_payment_id').val();
                    let gateway_id = $(this).data('id');
                    let baseUrl = $('#url').val();
                    if(method === 'Cash On Delivery'){
                        var url = baseUrl + '/checkout?gateway_id='+gateway_id+'&payment_id='+payment_id+'&step=complete_order';
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="CashOnDelivery" data-url="`+url+`" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    if(method === 'Wallet'){
                        var url = baseUrl + '/checkout?gateway_id='+gateway_id+'&payment_id='+payment_id+'&step=complete_order';
                        $('#btn_div').html(`<a href="javascript:void(0)" data-url="`+url+`" id="payment_btn_trigger" data-type="Wallet" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }

                    if(method === 'Stripe'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Stripe" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Bkash'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Bkash" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'SslCommerz'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="SslCommerz" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Mercado Pago'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Mercado Pago" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayPal'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayPal" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayStack'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayStack" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'RazorPay'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="RazorPay" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Instamojo'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Instamojo" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayTM'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayTM" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Midtrans'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Midtrans" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayUMoney'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayUMoney" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'JazzCash'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="JazzCash" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Google Pay'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Google Pay" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'FlutterWave'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="FlutterWave" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Bank Payment'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Bank Payment" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }

                });

                $(document).on('click', '#payment_btn_trigger', function(event){
                    let method = $(this).data('type');
                    let is_same_billing = $('input[type=radio][name=is_same_billing]:checked').val();
                    $('#error_name').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_address').text('');
                    $('#error_country').text('');
                    $('#error_state').text('');
                    $('#error_city').text('');
                    let is_true = 0;
                    if(is_same_billing == 0){
                        if($('#name').val() == ''){
                            $('#error_name').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#email').val() == ''){
                            $('#error_email').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#phone').val() == ''){
                            $('#error_phone').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#address').val() == ''){
                            $('#error_address').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#country').val() == ''){
                            $('#error_country').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#state').val() == ''){
                            $('#error_state').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#city').val() == ''){
                            $('#error_city').text('This Field is Required.');
                            is_true = 1;
                        }
                        if('{{isModuleActive('ShipRocket')}}'){
                            $('#error_postal_code').text('This Field is Required.');
                            is_true = 1;
                        }
                        if(is_true === 1){
                            return false;
                        }
                        let data = {
                            address_id: $('#address_id').val(),
                            name: $('#name').val(),
                            email: $('#email').val(),
                            address: $('#address').val(),
                            phone: $('#phone').val(),
                            country: $('#country').val(),
                            state: $('#state').val(),
                            city: $('#city').val(),
                            postal_code: $('#postal_code').val(),
                            _token: $('#token').val()
                        }
                        $('#pre-loader').show();
                        $.post("{{route('frontend.checkout.billing.address.store')}}",data, function(response){
                            if("{{isModuleActive('GoldPrice')}}" == 1){
                                $.post("{{route('frontend.checkout.check-cart-price-update')}}", {_token:"{{csrf_token()}}"}, function(response){
                                    if(response.count > 0){
                                        toastr.info('Cart Price Updated. Try again with updated price', 'Info');
                                        location.reload();
                                    }else{
                                        paymentAction(method);
                                    }
                                    $('#pre-loader').hide();
                                });
                            }else{
                                paymentAction(method);
                                $('#pre-loader').hide();
                            }
                        }).fail(function(response) {
                            $('#error_name').text(response.responseJSON.errors.name);
                            $('#error_address').text(response.responseJSON.errors.address);
                            $('#error_email').text(response.responseJSON.errors.email);
                            $('#error_phone').text(response.responseJSON.errors.phone);
                            $('#error_country').text(response.responseJSON.errors.country);
                            $('#error_state').text(response.responseJSON.errors.state);
                            $('#error_city').text(response.responseJSON.errors.city);
                            return false;
                        });

                    }else{
                        if("{{isModuleActive('GoldPrice')}}" == 1){
                            $('#pre-loader').show();
                            $.post("{{route('frontend.checkout.check-cart-price-update')}}", {_token:"{{csrf_token()}}"}, function(response){
                                if(response.count > 0){
                                    toastr.info('Cart Price Updated. Try again with updated price', 'Info');
                                    location.reload();
                                }else{
                                    paymentAction(method);
                                }
                                $('#pre-loader').hide();
                            });
                        }else{
                            paymentAction(method);
                        }
                    }

                });
                function paymentAction(method){
                    if(method == 'CashOnDelivery' || method == 'Wallet'){
                        var dataUrl = $('#payment_btn_trigger').data('url');
                        location.href = dataUrl;
                    }
                    else if(method == 'Stripe'){
                        $('#stribe_submit_btn').click();
                        $('#pre-loader').show();
                    }
                    else if(method == 'PayPal'){
                        $('.paypal_btn').click();
                    }
                    else if(method == 'PayStack'){
                        $('#paystack_btn').click();
                    }
                    else if(method == 'RazorPay'){
                        $('#razorpay_btn').click();
                    }
                    else if(method == 'Instamojo'){
                        $("#instamojo_btn").click();
                    }
                    else if(method == 'PayTM'){
                        $("#paytm_btn").click();
                    }
                    else if(method == 'Midtrans'){
                        $("#midtrans_btn").click();
                    }
                    else if(method == 'PayUMoney'){
                        $("#payumoney_btn").click();
                    }
                    else if(method == 'JazzCash'){
                        $("#jazzcash_btn").click();
                    }
                    else if(method == 'Google Pay'){
                        $("#buyButton").click();
                    }
                    else if(method == 'FlutterWave'){
                        $("#flutterwave_btn").click();
                    }
                    else if(method == 'Bank Payment'){
                        $("#bank_btn").click();
                    }
                    else if(method == 'Bkash'){
                        $("#bKash_button").click();
                    }

                    else if(method == 'SslCommerz'){
                        $("#ssl_commerz_form").submit();
                    }
                    else if(method == 'Mercado Pago'){
                        mercado_field_validate();
                        $("#form-checkout__submit").click();
                    }
                }

                function mercado_field_validate() {
                    let cardholderName = $('#form-checkout__cardholderName').val();
                    let cardholderEmail = $('#form-checkout__cardholderEmail').val();
                    let cardNumber = $('#form-checkout__cardNumber').val();
                    let cardExpirationDate = $('#form-checkout__cardExpirationDate').val();
                    let securityCode = $('#form-checkout__securityCode').val();
                    let installments = $('#form-checkout__installments').val();
                    let identificationType = $('#form-checkout__identificationType').val();
                    let identificationNumber = $('#form-checkout__identificationNumber').val();
                    let issuer = $('#form-checkout__issuer').val();

                    if (cardholderName == null) {
                        toastr.error('Cardholder name required');
                        return false;
                    }
                    if (cardholderEmail == null) {
                        toastr.error('Email required');
                        return false;
                    }
                    if (cardNumber == null) {
                        toastr.error('CardNumber required');
                        return false;
                    }
                    if (cardExpirationDate == null) {
                        toastr.error('Card Expiration Date required');
                        return false;
                    }
                    if (securityCode == null) {
                        toastr.error('Security Code required');
                        return false;
                    }
                    if (installments == null) {
                        toastr.error('Installments required');
                        return false;
                    }
                    if (identificationType == null) {
                        toastr.error('Identification Type required');
                        return false;
                    }
                    if (identificationNumber == null) {
                        toastr.error('Identification Number required');
                        return false;
                    }
                    if (issuer == null) {
                        toastr.error('issuer required');
                        return false;
                    }

                }

                $(document).on('change', '#address_id', function(event) {
                    let data = {
                        _token:"{{csrf_token()}}",
                        id: $(this).val()
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.checkout.address.billing')}}",data, function(res){
                        $('#pre-loader').hide();
                        let address = res.address;
                        let states = res.states;
                        let cities = res.cities;
                        $('#name').val(address.name);
                        $('#address').val(address.address);
                        $('#email').val(address.email);
                        $('#phone').val(address.phone);
                        $('#postal_code').val(address.postal_code);
                        $('#country').val(address.country);

                        $('#state').empty();
                        $('#state').append(
                            `<option value="">Select from options</option>`
                        );
                        $.each(states, function(index, stateObj) {
                            $('#state').append('<option value="' + stateObj
                                .id + '">' + stateObj.name + '</option>');
                        });
                        $('#state').val(address.state);

                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $.each(cities, function(index, cityObj) {
                            $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                        });
                        $('#city').val(address.city);
                        $('select').niceSelect('update');

                    });
                });

                $(document).on('change', '#country', function(event) {
                    let country = $('#country').val();
                    $('#pre-loader').show();
                    if (country) {
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' + country;

                        $('#state').empty();

                        $('#state').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#state').niceSelect('update');
                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#city').niceSelect('update');
                        $.get(url, function(data) {

                            $.each(data, function(index, stateObj) {
                                $('#state').append('<option value="' + stateObj
                                    .id + '">' + stateObj.name + '</option>');
                            });

                            $('#state').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });

                $(document).on('change', '#state', function(event){
                    let state = $('#state').val();
                    $('#pre-loader').show();
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;


                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#city').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });
                $(document).on('click', '.coupon_apply_btn', function(event){
                    event.preventDefault();
                    let total = $(this).data('total');
                    couponApply(total);
                });

                function couponApply(total){
                    let coupon_code = $('#coupon_code').val();
                    if(coupon_code){
                        $('#pre-loader').show();

                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('coupon_code', coupon_code);
                        formData.append('shopping_amount', total);
                        $.ajax({
                            url: '{{route('frontend.checkout.coupon-apply')}}',
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (response) {
                                if(response.error){
                                    toastr.error(response.error,'Error');
                                    $('#pre-loader').hide();
                                }else{
                                    $('#mainDiv').html(response.MainCheckout);
                                    toastr.success("{{__('defaultTheme.coupon_applied_successfully')}}","{{__('common.success')}}");
                                    $('#pre-loader').hide();
                                }
                            },
                            error: function (response) {
                                toastr.error(response.responseJSON.errors.coupon_code)
                                $('#pre-loader').hide();
                            }
                        });
                    }else{
                        toastr.error("{{__('defaultTheme.coupon_field_is_required')}}","{{__('common.error')}}");
                    }
                }
                $(document).on('click', '#coupon_delete', function(event){
                    event.preventDefault();
                    couponDelete();
                });

                function couponDelete(){
                    $('#pre-loader').show();
                    let base_url = $('#url').val();
                    let url = base_url + '/checkout/coupon-delete';
                    $.get(url, function(response) {
                        $('#mainDiv').html(response.MainCheckout);
                        $('#pre-loader').hide();
                        toastr.success("{{__('defaultTheme.coupon_deleted_successfully')}}","{{__('common.success')}}");
                    });
                }

            });

        })(jQuery);
    </script>

@endpush
