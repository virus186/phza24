@extends('frontend.amazy.auth.layouts.app')

@section('content')
<div class="amazy_login_area">
    @php
        $loginPageInfo = \Modules\FrontendCMS\Entities\LoginPage::findOrFail(4);
    @endphp
    <div class="amazy_login_area_left d-flex align-items-center justify-content-center">
        <div class="amazy_login_form">
            <a href="{{url('/')}}" class="logo mb_50 d-block">
                <img src="{{showImage(app('general_setting')->logo)}}" alt="{{app('general_setting')->company_name}}" title="{{app('general_setting')->company_name}}">
            </a>
            <h3 class="m-0">{{ __('common.welcome') }}! {{ __('common.please') }}</h3>
            <p class="support_text">{{ __('defaultTheme.verify_your_email') }}.</p>
            <br>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{route('frontend.resend-link',$user->id)}}}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" name="verify_code" value="{{$user->verify_code}}">
                    <div class="col-12">
                        <button class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" id="submitBtn">{{ __('defaultTheme.click_here_to_request_another') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="amazy_login_area_right d-flex align-items-center justify-content-center">
        <div class="amazy_login_area_right_inner d-flex align-items-center justify-content-center flex-column">
            <div class="thumb">
                <img class="img-fluid" src="{{ showImage($loginPageInfo->cover_img) }}" alt="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}" title="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}">
            </div>
            <div class="login_text d-flex align-items-center justify-content-center flex-column text-center">
                <h4>{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}</h4>
                <p class="m-0">{{ isset($loginPageInfo->sub_title)? $loginPageInfo->sub_title:'' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection