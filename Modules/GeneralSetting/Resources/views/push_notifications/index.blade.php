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
                                        {{ __('common.push_notification') }} </h3>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                    <div class="white_box_50px box_shadow_white mb-40 minh-430">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="fcm_server_key">{{ __('common.fcm_server_key') }} <span class="text-danger">*</span></label>
                                                    <input class="primary_input_field" type="text" id="fcm_server_key" name="FCM_SERVER_KEY" autocomplete="off" value="{{env('FCM_SERVER_KEY')}}" placeholder="{{ __('common.fcm_server_key') }}" >
                                                    @error('FCM_SERVER_KEY')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-40 text-center">
                                            @if (permissionCheck('push.notification.store'))
                                                <button id="google_submit_btn" type="submit" class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    {{ __('common.save') }} </button>
                                            
                                            @else
                                                <span class="alert alert-warning" role="alert">
                                                    <strong>You don't have this permission</strong>
                                                </span>
                                            @endif
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
                    let server_key = $('#fcm_server_key').val();
                        $.ajax({
                            url: "{{ route('push.notification.store') }}",
                            type: "POST",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                server_key: server_key,
                            },
                            success: function(response) {
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
            });
        })(jQuery);
    </script>
@endpush
