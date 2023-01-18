@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{ asset(asset_path('backend/vendors/css/icon-picker.css')) }}" />
<link rel="stylesheet" href="{{asset(asset_path('modules/multivendor/css/setting.css'))}}" />
@endsection
@section('mainContent')
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12">
                            @include('frontendcms::social_link.components.social_link')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
@include('frontendcms::social_link.components._scripts')
