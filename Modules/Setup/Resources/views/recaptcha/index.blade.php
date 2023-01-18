@extends('backEnd.master')

@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/setup/css/style.css'))}}" />


@endsection

@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">
                                        {{ __('setup.google_recaptcha') }} </h3>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                    <div class="white_box_50px box_shadow_white mb-40 minh-430">
                                        <div class="row">

                                            <div class="col-lg-12">
                                                    <label class="primary_input_label" for="nocaptcha_version">{{ __('setup.google_recaptcha_version') }} <span class="text-danger">*</span></label>
                                                <ul id="theme_nav" class="permission_list sms_list ">
                                                    <li>
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input id="nocaptcha_version_v3" @if(config('app.recaptcha_version') == "3") checked @endif value="3" type="radio" name="nocaptcha_version">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('setup.google_recaptcha_v3') }}</p>
                                                    </li>
                                                    <li>
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input id="nocaptcha_version_v2" @if(config('app.recaptcha_version') == "2") checked @endif value="2" type="radio" name="nocaptcha_version">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('setup.google_recaptcha_v2') }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-12">
                                                    <label class="primary_input_label" for="nocaptcha_version">{{ __('setup.invisible') }} <span class="text-danger">*</span></label>
                                                    <span class="text-danger">If invisible yes not show checkbox</span>
                                                <ul id="theme_nav" class="permission_list sms_list ">
                                                    <li>
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input id="invisible_yes" @if(config('app.recaptcha_invisible') == "true") checked @endif value="1" type="radio" name="nocaptcha_invisible">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('setup.yes') }}</p>
                                                    </li>
                                                    <li id="invisible_no_show" class="@if(config('app.recaptcha_version') == "3") d-none @else @endif">
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input id="invisible_no" @if(config('app.recaptcha_invisible') != "true") checked @endif value="0" type="radio" name="nocaptcha_invisible">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('setup.no') }}</p>
                                                    </li>
                                                </ul>
                                                
                                            </div>
                                          
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="google_captcha_sitekey">{{ __('setup.captcha_sitekey') }} <span class="text-danger">*</span></label>
                                                    <input class="primary_input_field" type="text" id="google_captcha_sitekey" name="NOCAPTCHA_SITEKEY" autocomplete="off" value="{{config('app.recaptcha_site_key')}}" placeholder="{{ __('setup.captcha_sitekey') }}" >
                                                    @error('CAPTCHA_SITEKEY')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="google_captcha_secret">{{ __('setup.captcha_secret') }} <span class="text-danger">*</span></label>
                                                    <input class="primary_input_field" type="text" id="google_captcha_secret" name="NOCAPTCHA_SECRET" autocomplete="off" value="{{config('app.recaptcha_secret_key')}}" placeholder="{{ __('setup.captcha_secret') }}" >
                                                </div>
                                            </div>
                                            
                                            <ul id="theme_nav" class="permission_list sms_list ">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="nocaptcha_for_login" @if(config('app.recaptcha_for_login') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('setup.captcha_login_page') }}</p>
                                                </li>
                                                <input type="hidden" id="nocaptcha_for_login_value" name="types[]" value="@if(config('app.recaptcha_for_login') == "true") 1 @else 0 @endif">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="nocaptcha_for_register" @if(config('app.recaptcha_for_reg') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('setup.captcha_register_page') }}</p>
                                                </li>
                                                <input type="hidden" id="nocaptcha_for_register_value" name="types[]" value="@if(config('app.recaptcha_for_reg') == "true") 1 @else 0 @endif">
                                                <li>
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="nocaptcha_for_email" @if(config('app.recaptcha_for_email') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('setup.captcha_email_page') }}</p>
                                                </li>
                                                <input type="hidden" id="nocaptcha_for_email_value" name="types[]" value="@if(config('app.recaptcha_for_email') == "true") 1 @else 0 @endif">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="nocaptcha_for_contact" @if(config('app.recaptcha_for_contact') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('setup.captcha_contact_page') }}</p>
                                                </li>
                                                <input type="hidden" id="nocaptcha_for_contact_value" name="types[]" value="@if(config('app.recaptcha_for_contact') == "true") 1 @else 0 @endif">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="nocaptcha_for_checkout" @if(config('app.recaptcha_for_checkout') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('setup.captcha_checkout_page') }}</p>
                                                </li>
                                                <input type="hidden" id="nocaptcha_for_checkout_value" name="types[]" value="@if(config('app.recaptcha_for_checkout') == "true") 1 @else 0 @endif">
                                            </ul>

                                            <div class="col-lg-12 mt-40 text-center">
                                                <button id="google_submit_btn" type="submit" class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    {{ __('common.save') }} </button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                </div>
                
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $('#nocaptcha_version_v2').on('change', function(event){
                    if ($('#nocaptcha_version_v2').prop('checked')) {    
                        $('#invisible_no_show').removeClass('d-none');
                    }
                });
                $('#nocaptcha_version_v3').on('change', function(event){
                    if ($('#nocaptcha_version_v3').prop('checked')) {    
                        $('#invisible_no_show').addClass('d-none');
                        $('#invisible_yes').prop('checked');
                    }
                });
                $(document).on('click', '#google_submit_btn', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let nocaptcha_version = $("input[name='nocaptcha_version']:checked").val();
                    let nocaptcha_invisible = $("input[name='nocaptcha_invisible']:checked").val();
                    let captcha_sitekey = $('#google_captcha_sitekey').val();
                    let captcha_secret = $('#google_captcha_secret').val();
                    let login_value = $('#nocaptcha_for_login_value').val();
                    let register_value = $('#nocaptcha_for_register_value').val();
                    let contact_value = $('#nocaptcha_for_contact_value').val();
                    let checkout_value = $('#nocaptcha_for_checkout_value').val();
                    let email_value = $('#nocaptcha_for_email_value').val();
                        $.ajax({
                            url: "{{ route('setup.recaptcha.update') }}",
                            type: "POST",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                nocaptcha_version: nocaptcha_version,
                                nocaptcha_invisible: nocaptcha_invisible,
                                captcha_sitekey: captcha_sitekey,
                                captcha_secret: captcha_secret,
                                login_value: login_value,
                                register_value: register_value,
                                contact_value: contact_value,
                                checkout_value: checkout_value,
                                email_value: email_value,
                            },
                            success: function(response) {
                                // console.log(response);
                                $('#pre-loader').addClass('d-none');
                                toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");

                            },
                            error: function(response) {
                                if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }
                                toastr.error('{{ __("common.error_message") }}');
                                $('#pre-loader').addClass('d-none');
                            }
                        });

                });
                $(document).on('change', '#nocaptcha_for_login', function(event){
                    let status = 0;
                    if($('#nocaptcha_for_login').prop('checked')){
                        status = 1;
                    }
                    $('#nocaptcha_for_login_value').val(status);
                });
                $(document).on('change', '#nocaptcha_for_register', function(event){
                    let status = 0;
                    if($('#nocaptcha_for_register').prop('checked')){
                        status = 1;
                    }
                    $('#nocaptcha_for_register_value').val(status);
                });
                $(document).on('change', '#nocaptcha_for_contact', function(event){
                    let status = 0;
                    if($('#nocaptcha_for_contact').prop('checked')){
                        status = 1;
                    }
                    $('#nocaptcha_for_contact_value').val(status);
                });
                $(document).on('change', '#nocaptcha_for_checkout', function(event){
                    let status = 0;
                    if($('#nocaptcha_for_checkout').prop('checked')){
                        status = 1;
                    }
                    $('#nocaptcha_for_checkout_value').val(status);
                });
                $(document).on('change', '#nocaptcha_for_email', function(event){
                    let status = 0;
                    if($('#nocaptcha_for_email').prop('checked')){
                        status = 1;
                    }
                    $('#nocaptcha_for_email_value').val(status);
                });
            });
        })(jQuery);
    </script>
@endpush
