@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/frontendcms/css/promotion.css'))}}" />
@endsection
@section('mainContent')
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header">
                    <div class="main-title d-flex justify-content-between w-100">
                        <h3 class="mb-0 mr-30">{{ __('frontendCms.promotion_bar') }}</h3>

                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="white_box_50px box_shadow_white">
                    @include('frontendcms::promotion_bar.components.form')
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
@include('frontendcms::promotion_bar.components.scripts')


