@extends('frontend.default.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/checkout.css'))}}" />
    <style>
        .cursor_pointer{
            cursor: pointer;
        }
        .form-control {
            border-radius: 0;
            height: 50px;
            margin-bottom: 17px;
            color: #8f8f8f;
            font-weight: 300;
        }
        .link_style{
            color: inherit!important;
        }
        .link_btn_design{
            font-size: 14px;
            color: #fd0027;
            text-transform: uppercase;
            font-weight: 600;
        }
        .link_btn_design:hover{
            font-size: 14px;
            color: #fd0027;
            text-transform: uppercase;
            font-weight: 600;
        }
        .modal_header_custom_design{
            border-bottom: none!important;
        }
        .cart_table_body{
            margin-top: 25px!important;
        }

        .tablesaw thead tr:first-child th {
             padding: 0 40px;
        }
        .custom_tr{
            padding-top: 10px!important;
        }
        .shipping_delivery_div {
            display: flex;
            grid-gap: 150px;
        }
        .ml-20{
            margin-left: 20px;
        }
        @media (max-width: 540px) {
            .shipping_delivery_div {
                display: block!important;
                margin-bottom: 20px;
            }
        }
    </style>
@endsection
@section('breadcrumb')
    {{ __('defaultTheme.customer_information') }}
@endsection
@section('title')
    {{ __('defaultTheme.checkout') }}
@endsection
@section('content')
    @php
        $postalCodeRequired = false;
        if(isModuleActive('ShipRocket')){
            $postalCodeRequired = true;
        }
    @endphp
    @include('frontend.default.partials._breadcrumb')
    <div id="mainDiv">
        @include('frontend.default.partials._checkout_details')
    </div>
@endsection

@push('scripts')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                $(document).on('click', '.link_btn_design', function(event){
                    shippingAddressDiv();
                });

                function shippingAddressDiv(){
                    let shipping_address_div = $('.shipping_address_div');
                    let shipping_address_edit_div = $('.shipping_address_edit_div');
                    shipping_address_div.toggleClass('d-none');
                    shipping_address_edit_div.toggleClass('d-none');
                }
                $(document).on('change', 'input[name=delivery_type]', function(){
                    $('.pick_location_list_div').toggleClass('d-none');
                    var delivery_type = $(this).val();
                    if(delivery_type == 'pickup_location'){
                        $('#next_step_btn_div').html(
                            `
                            <input type="hidden" name="step" value="select_payment">
                            <input type="hidden" name="shipping_method" value="{{encrypt($free_shipping_for_pickup_location->id)}}">
                            <button type="submit" class="btn_1 m-0 text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
                            `
                        );
                        $('.address_title').text("{{__('common.billing_address')}}");

                    }else if(delivery_type == 'home_delivery'){
                        $('#next_step_btn_div').html(
                            `
                            <input type="hidden" name="step" value="select_shipping">
                            <button type="submit" class="btn_1 m-0 text-uppercase ">{{__('defaultTheme.continue_to_shipping')}}</button>
                            `
                        );
                        $('.address_title').text("{{__('shipping.shipping_address')}}");
                    }
                });

                $(document).on('click', '#shipping_methods', function(event){
                    let id = $(this).data('target');
                    $('#'+id).modal('show');
                });

                $(document).on('change', '.shipping_method_select', function(event){
                    $('#pre-loader').show();
                    let id = $(this).data('package');
                    let shipping_method = $(this).val();
                    let url = "{{route('frontend.change_shipping_method')}}";
                    let data = {
                        _token:"{{csrf_token()}}",
                        seller:id,
                        shipping_method:shipping_method,
                    }
                    $('#shipping_methods_'+id).modal('hide');
                    $.post(url,data, function(res){
                        $('#mainDiv').html(res);
                        $('select').niceSelect();
                        $('#pre-loader').hide();
                    });
                });






                $(document).on('submit', '#mainOrderForm', function(event){

                    let is_submit = 0;
                    let postalCodeRequired = "{{$postalCodeRequired}}"
                    $('#error_term_check').text('');
                    $('#error_name').text('');
                    $('#error_address').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_country').text('');
                    $('#error_state').text('');
                    $('#error_city').text('');
                    $('#error_postal_code').text('');
                    $('#error_pickup_location').text('');
                    if(!$('#term_check').is(":checked")){
                        is_submit = 1;
                        $('#error_term_check').text('Please Agree With Terms');
                    }
                    if($('#name').val() == ''){
                        is_submit = 1;
                        $('#error_name').text('This Field Is Required');
                    }
                    if(postalCodeRequired == 1 && $('#postal_code').val() == ''){
                        is_submit = 1;
                        $('#error_postal_code').text('This Field Is Required');
                    }
                    if($('#address').val() == ''){
                        is_submit = 1;
                        $('#error_address').text('This Field Is Required');
                    }
                    if($('#email').val() == ''){
                        is_submit = 1;
                        $('#error_email').text('This Field Is Required');
                    }
                    if($('#phone').val() == ''){
                        is_submit = 1;
                        $('#error_phone').text('This Field Is Required');
                    }
                    if($('#country').val() == ''){
                        is_submit = 1;
                        $('#error_country').text('This Field Is Required');
                    }
                    if($('#state').val() == ''){
                        is_submit = 1;
                        $('#error_state').text('This Field Is Required');
                    }
                    if($('#city').val() == ''){
                        is_submit = 1;
                        $('#error_city').text('This Field Is Required');
                    }
                    if($('input[name=delivery_type]').length && $('input[name=delivery_type]:checked').val() == 'pickup_location' && $('#pickup_location').val() == ''){
                        is_submit = 1;
                        $('#error_pickup_location').text('This Field Is Required');
                    }
                    if(is_submit === 1){
                        event.preventDefault();
                    }else{

                    }
                });

                $(document).on('change', '#address_id', function(event) {
                    let data = {
                        _token:"{{csrf_token()}}",
                        id: $(this).val()
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.checkout.address.shipping')}}",data, function(res){
                        // $('#mainDiv').html(res.MainCheckout);
                        location.reload();
                        $('select').niceSelect();
                        // $('#pre-loader').hide();
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

            });
        })(jQuery);
    </script>
@endpush
