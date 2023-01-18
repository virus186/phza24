@extends('frontend.amazy.auth.layouts.app')

@section('content')
<div class="amazy_login_area">
    <div class="amazy_login_area_left d-flex align-items-center justify-content-center">
        <div class="amazy_login_form">
            <h3 class="m-0">{{ __('amazy.Welcome back') }}</h3>
            <p class="support_text">{{__('amazy.Please confirm with new password.')}}</p>
            @if(config('app.sync'))
                <div class="d-flex justify-content-center mt-20 grid_gap_5 flex-wrap">
                    <button class="amaz_primary_btn style2 radius_5px text-uppercase  text-center mb_25" id="admin" data-email="{{$admin_email}}">{{ __('common.admin') }}</button>
                </div>
            @endif
            <br>

            
            <form action="{{ route('password.update') }}" method="POST" name="login" id="login_form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="row">
                    <div class="col-12 mb_20">
                        <label class="primary_label2" for="email">{{ __('common.email_address') }} <span>*</span> </label>
                        <input name="email" id="email" value="{{ $email ?? old('email') }}" placeholder="{{ __('common.email_address') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email_address') }}'" class="primary_input3 radius_5px" type="email">
                        @error('email')
                        <span class="text-danger" >{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 mb_20">
                        <label class="primary_label2" for="password">{{ __('common.password') }} <span>*</span></label>
                        <input name="password" id="password" required placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                        @error('password')
                        <span class="text-danger" >{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 mb_20">
                        <label class="primary_label2" for="password-confirm">{{ __('common.confirm_password') }} <span>*</span></label>
                        <input name="password_confirmation" id="password-confirm" placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                    </div>
                    <div class="col-12">
                        <button class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" id="sign_in_btn">{{__('common.reset_password')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="amazy_login_area_right d-flex align-items-center justify-content-center">
        <div class="amazy_login_area_right_inner d-flex align-items-center justify-content-center flex-column">
            <div class="thumb">
                <img class="img-fluid" src="{{url('/')}}/public/frontend/amazy/img/banner/login_img.png" alt="{{__('amazy.turn_your_ideas_into_reality')}}" title="{{__('amazy.turn_your_ideas_into_reality')}}">
            </div>
            <div class="login_text d-flex align-items-center justify-content-center flex-column text-center">
                <h4>{{__('amazy.turn_your_ideas_into_reality')}}</h4>
                <p class="m-0">{{__('amazy.consistent_quality_and_experience_across_all_platforms_and_devices')}}</p>
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
                $('#submit_btn').removeAttr('disabled');
                $(document).on('submit', '#login_form', function(event){

                    $('#login_form > div > div:nth-child(1) > span:nth-child(3)').text('');
                    $('#login_form > div > div:nth-child(2) > span').text('');
                    $('#login_form > div > div:nth-child(1) > span:nth-child(4)').text('');

                    let email = $('#text').val();
                    let password = $('#password').val();

                    let val_check = 0;

                    if(email == ''){
                        $('#login_form > div > div:nth-child(1) > span:nth-child(3)').text('The email or phone field is required.');
                        val_check = 1;
                    }

                    if(password == ''){
                        $('#login_form > div > div:nth-child(2) > span').text('The password field is required.');
                        val_check = 1;
                    }

                    if(val_check == 1){
                        event.preventDefault();
                    }
                });

                @if(config('app.sync'))
                    $(document).on('click', '#admin', function(event){
                        let email = $(this).data('email');
                        $("#text").val('');
                        $("#password").val('');
                        if(email != ''){
                            $("#text").val(email);
                            $("#password").val('12345678');
                            $('#login_form').submit();
                        }else{
                            toastr.error('Please Create a Admin First.', 'Error');
                        }
                    });
                    $(document).on('change', '#password', function(){
                        let value = $(this).val();
                        if($('#auto_login').length){
                            $('#auto_login').val(value == '12345678');  
                        }
                    });
                @endif

            });
        })(jQuery);
    </script>
@endpush