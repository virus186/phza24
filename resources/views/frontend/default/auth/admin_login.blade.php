@extends('frontend.default.auth.layouts.app')

@section('styles')
    <style>
        .login_logo img {
            max-width: 140px;
            margin: 0 auto;
        }
        .register_part {
            background: var(--background_color) !important;
            min-height: 100vh !important;
        }
    </style>
@endsection
@section('content')
<section class="login_area register_part">
    <div class="container">
        <div class="row justify-content-center align-items-center">

            <div class="col-lg-6 col-xl-4">
                @if(config('app.sync'))
                    <div class="d-flex justify-content-center mt-20 grid_gap_5 flex-wrap">
                        <button class="btn_1" id="admin" data-email="{{$admin_email}}">{{ __('common.admin') }}</button>
                    </div>
                @endif
                <br>
                <div class="register_form_iner">
                    <div class="login_logo text-center mb-3">
                        <a href="{{url('/')}}"><img src="{{showImage(app('general_setting')->logo)}}" alt=""></a>
                    </div>
                    <h2>{{ __('defaultTheme.welcome_back') }}, <br>{{ __('defaultTheme.please_login_to_your_account') }}</h2>
                    <form method="POST" class="register_form" name="login" action="{{ route('admin.login_submit') }}" id="login_form">
                        @csrf

                        
                        @if(config('app.sync'))
                            <input type="hidden" id="auto_login" name="auto_login" value="true">
                        @endif
                        <div class="form-row">
                            <div class="col-md-12 input_div_mb">
                                <label for="email">{{ __('defaultTheme.email_or_phone') }}</label>
                                <input type="text" id="text" name="login" placeholder="{{ __('defaultTheme.email_or_phone') }}" value="{{ old('login') }}" class="@error('email') is-invalid @enderror">

                                <span class="text-danger" >{{ $errors->first('email') }}</span>
                                <span class="text-danger" >{{ $errors->first('username') }}</span>
                            </div>
                            <div class="col-md-12 input_div_mb">
                                <label for="password">{{ __('common.password') }}</label>
                                <input type="password" id="password" name="password" placeholder="{{ __('common.password') }}" class="@error('password') is-invalid @enderror" value="{{old('password')}}">

                                <span class="text-danger" >{{ $errors->first('password') }}</span>

                            </div>
                            <div class="col-md-6 col-6">
                                <div class="checkbox">
                                    <label class="cs_checkbox">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>{{ __('defaultTheme.remember_me') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                            <a href="{{url('/password/reset')}}" class="forgot_pass_btn">{{ __('defaultTheme.forgot_password') }}</a>
                            </div>
                            <div class="col-md-12 text-center">
                                <div class="register_area">
                                    <button type="submit" class="btn_1" id="submit_btn" disabled>{{ __('defaultTheme.login') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                            $('#submit_btn').attr('disabled', true);
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
