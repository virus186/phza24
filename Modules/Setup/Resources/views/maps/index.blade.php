@extends('backEnd.master')

@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/setup/css/style.css'))}}" />


@endsection

@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">
                                        {{ __('setup.google_maps_api') }} </h3>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                    <div class="white_box_50px box_shadow_white mb-40 minh-430">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="google_api_key">{{ __('setup.api_key') }} <span class="text-danger">*</span></label>
                                                    <input class="primary_input_field" type="text" id="google_api_key" name="GOOGLE_MAP_KEY" autocomplete="off" value="{{config('app.map_api_key')}}" placeholder="{{ __('setup.api_key') }}" >
                                                    @error('GOOGLE_MAP_KEY')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <strong class="text-danger"> Google Support 5 Restricted Countries</strong>
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="google_country_1">{{ __('setup.autocomplete_restricted_countries') }} <span class="text-danger">*</span></label>
                                                    <input class="primary_input_field" type="text" id="google_country_1" name="GOOGLE_MAPS_COUNTRY_1" autocomplete="off" value="{{config('app.map_api_country_1')}}" placeholder="{{ __('setup.country_code') }}" >
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <input class="primary_input_field" type="text" id="google_country_2" name="GOOGLE_MAPS_COUNTRY_2" autocomplete="off" value="{{config('app.map_api_country_2')}}" placeholder="{{ __('setup.country_code') }}" >
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <input class="primary_input_field" type="text" id="google_country_3" name="GOOGLE_MAPS_COUNTRY_3" autocomplete="off" value="{{config('app.map_api_country_3')}}" placeholder="{{ __('setup.country_code') }}" >
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <input class="primary_input_field" type="text" id="google_country_4" name="GOOGLE_MAPS_COUNTRY_4" autocomplete="off" value="{{config('app.map_api_country_4')}}" placeholder="{{ __('setup.country_code') }}" >
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <input class="primary_input_field" type="text" id="google_country_5" name="GOOGLE_MAPS_COUNTRY_5" autocomplete="off" value="{{config('app.map_api_country_5')}}" placeholder="{{ __('setup.country_code') }}" >
                                                </div>
                                            </div>
                                            <ul id="theme_nav" class="permission_list sms_list ">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input id="maps_view_status" @if(config('app.map_api_status') == "true") checked @endif value="1" type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>Enable Google Maps API</p>
                                                    
                                                </li>
                                                <input type="hidden" id="dashboard_is_enable" name="types[]" value="@if(config('app.map_api_status') == "true") 1 @else 0 @endif">
                                                
                                            </ul>
                                           
                                               <div class="col-lg-12">
                                                <span>country: ["us", "ca"]</span>
                                                <a target="_blank" style="color: #415094 !important;" class="facebook_link_btn float-right" href="https://countrycode.org/">Country Code List</a>
                                               </div>

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
                $(document).on('click', '#google_submit_btn', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let api_key = $('#google_api_key').val();
                    let status_value = $('#dashboard_is_enable').val();
                    let country_1 = $('#google_country_1').val();
                    let country_2 = $('#google_country_2').val();
                    let country_3 = $('#google_country_3').val();
                    let country_4 = $('#google_country_4').val();
                    let country_5 = $('#google_country_5').val();
                        $.ajax({
                            url: "{{ route('setup.google-maps-api-update') }}",
                            type: "POST",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                api_key: api_key,
                                status_value: status_value,
                                country_1: country_1,
                                country_2: country_2,
                                country_3: country_3,
                                country_4: country_4,
                                country_5: country_5,
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
                $(document).on('change', '#maps_view_status', function(event){
                    let status = 0;
                    if($('#maps_view_status').prop('checked')){
                        status = 1;
                    }
                    $('#dashboard_is_enable').val(status);
                });
            });
        })(jQuery);
    </script>
@endpush
