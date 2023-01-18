@extends('backEnd.master')
@section('styles')
    <style>
        .blogImgShow {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            max-height: 150px;
        }
    </style>
    <link rel="stylesheet" href="{{asset(asset_path('modules/frontendcms/css/promotion.css'))}}" />
@endsection
@section('mainContent')

@php
    if(\Session::has('login_tab')){
        $loginPageTab = \Session::get('login_tab');
    }else{
        $loginPageTab = 1;
    }
@endphp

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header">
                    <div class="main-title d-flex justify-content-between w-100">
                        <h3 class="mb-0 mr-30">{{ __('frontendCms.login_page') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Tab list -->
            <div class="row">
                <div class="col-md-12 mb-20">
                    <div class="box_header_right">
                        <div class="float-lg-right float-none pos_tab_btn justify-content-end">
                            <ul class="nav" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $loginPageTab == 1?'active':'' }} show active_section_class" href="#admin" role="tab" data-toggle="tab" id="1" data-id="1"
                                       aria-selected="true">{{__('frontendCms.admin')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $loginPageTab == 2?'active':'' }} show active_section_class" href="#customer" role="tab" data-toggle="tab" id="2" data-id="2"
                                       aria-selected="false">{{__('frontendCms.customer')}}</a>
                                </li>
                                @if(isModuleActive('MultiVendor'))
                                    <li class="nav-item" id="company_tab">
                                        <a class="nav-link {{ $loginPageTab == 3?'active':'' }} show active_section_class" href="#seller" role="tab" data-toggle="tab" id="3" data-id="3"
                                           aria-selected="false">{{__('frontendCms.seller')}}</a>
                                    </li>
                                @endif
                                <li class="nav-item" id="password_tab">
                                    <a class="nav-link {{ $loginPageTab == 4?'active':'' }} show active_section_class" href="#password_reset" role="tab" data-toggle="tab" id="4" data-id="4"
                                       aria-selected="false">{{__('Password Reset')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="white_box_50px box_shadow_white">
                    @include('frontendcms::login-page.components.form')
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
@include('frontendcms::login-page.components.scripts')



