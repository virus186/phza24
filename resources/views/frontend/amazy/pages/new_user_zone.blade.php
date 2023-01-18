@extends('frontend.amazy.layouts.app')
@section('title')
{{__('marketing.new_user_zone')}}
@endsection
@push('styles')
    <style>

        .bradcam_bg_1 {
            background-image: url({{showImage($new_user_zone->banner_image)}});
        }
        
        .breadcrumb_area {
            height: 500px;
            display: flex;
            align-items: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            padding: 20px;
        }


        .breadcrumb_area .bradcam_text {
            width: 100%;
        }

        .breadcrumb_area .bradcam_text h3 {
            font-size: 48px;
            font-weight: 700;
            color: #fff!important;
        }

        @media (max-width: 991px) {
            .breadcrumb_area .bradcam_text h3 {
                font-size: 30px;
            }
        }

        .breadcrumb_area .bradcam_text p {
            font-size: 14px;
            color: #000;
            font-weight: 600;
            text-transform: uppercase;
        }

        .breadcrumb_area .bradcam_text p a {
            color: #000;
        }

        .amazcart_tabs .nav-item .nav-link {
            padding: 12px 20px;
            line-height: 14px;
            border-radius: 5px;
        }


        .amazcart_tabs .nav-item .nav-link.active {
            background: #ff0027;
            color: #fff;
        }


        .gift_tabs {
            position: relative;
            top: -50px;
            border: 0 !important;
            margin-bottom: -50px;
        }


        .gift_tabs .nav-tabs {
            border: 0;
        }


        .gift_tabs .nav-tabs .nav-item a {
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }


        .coupon_gift_wrapper {
            background: #FF0027;
            border-radius: 5px;
            padding: 15px;
            max-width: 1200px;
            height: 238px;
            margin: 24px auto 0;
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            background-repeat: no-repeat;
            background-color: #FF0027;
            background-position: 100% 100%;
            border: 0 !important;
        }

        .coupon_gift_wrapper .coupon_box {
            display: flex;
            width: 1010px;
            height: 154px;
            position: relative;
            font-size: 0;
        }

        .coupon_gift_wrapper .coupon_box .coupon_box_left {
            width: 656px;
            height: 100%;
            margin-left: 8px;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            background-color: #fff;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_left .coupon_box_left_inner {
            display: inline-block;
            width: 100%;
            height: 100%;
            border-right: 2px dashed #FF0027;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_left h2 {
            font-size: 65px;
            font-weight: 900;
            color: #FF0027;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_left .coupon_text {
            height: 100%;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .coupon_gift_wrapper .coupon_box .coupon_box_left .coupon_text h3 {
            font-size: 18px;
            font-weight: 400;
            color: #000;
            margin-bottom: 0;
            margin-right: 15px;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_left .coupon_text p {
            display: inline-block;
            font-size: 14px;
            color: #818181;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_left .sawtooth-left {
            width: 8px;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0) 50%, #fff 55%);
            background-size: 17px 19px;
            background-position: -8px 1px;
            background-repeat: repeat-y;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_right {
            width: 338px;
            height: 100%;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .coupon_gift_wrapper .coupon_box .coupon_box_right .btn_1 {
            margin-top: 0;
        }

        .coupon_gift_wrapper .coupon_box .coupon_box_right .sawtooth-right {
            width: 8px;
            height: 100%;
            position: absolute;
            top: 0;
            right: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0) 50%, #fff 55%);
            background-size: 19px 19px;
            background-position: -2px 1px;
            background-repeat: repeat-y;
        }
        .btn_1{
            cursor: pointer;
        }
        .bg-white {
            background-color: {{$new_user_zone->background_color}}!important;
        }
        .custom_text_color{
            color: {{$new_user_zone->text_color}}!important;
        }
        .product_tricker .product_text p{
            color: {{$new_user_zone->text_color}}!important;
        }

        .account_details .nav-tabs .nav-link.active {
            color: #222 !important;
            background-color: {{$new_user_zone->background_color}} !important;
            border-color: {{$new_user_zone->background_color}} !important;
        }

        .nav_link {
            background-color: #FF0027!important;
            color: #fff!important;
        }
/* new  */
.coupon_gift_wrapper .coupon_box .coupon_box_left h2 {
    font-size: 60px;
    line-height: 1;
    margin-bottom: 0;
}
@media (max-width: 991.98px){
    .coupon_gift_wrapper .coupon_box {
        flex-wrap: wrap;
        max-width: 100%;
    }
    .coupon_gift_wrapper {
        height: 370px;
    }
    .coupon_gift_wrapper .coupon_box .coupon_box_left .coupon_text {
        flex-wrap: wrap;
    }
    .coupon_gift_wrapper .coupon_box .coupon_box_right {
        width: 100%;
    }
}
    </style>
@endpush
@section('content')
<div class="breadcrumb_area bradcam_bg_1">
    <div class="container-fluid">
        <div class="breadcrumb_iner ">
            <div class="bradcam_text">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        
                        @if ($new_user_zone->title_show)
                            <h3>{{$new_user_zone->sub_title}}</h3>
                            <h2 class="m-0">{{$new_user_zone->title}}</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- new_user_section::start  -->
<div class="new_user_zone_wrap">
    <ul class="nav new_user_tabs position_user_tab  justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{$new_user_zone->product_navigation_label}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{$new_user_zone->category_navigation_label}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">{{$new_user_zone->coupon_navigation_label}}</button>
        </li>
    </ul>
</div>
<div class="new_user_section section_spacing7 ">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tab-content" id="pills-tabContent">


                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- content ::start -->
                        <div class="row" id="productDiv">
                            <div class="col-12">
                                <div class="section__title d-flex align-items-center gap-3 mb_30 flex-wrap">
                                    <h3 class="m-0 flex-fill">{{$new_user_zone->product_slogan}}</h3>
                                </div>
                            </div>

                            @include('frontend.amazy.partials.new_user_zone_paginate_data._new_user_zone_product_paginate')
                        </div>
                        <!-- content ::end -->
                    </div>

                    <div class="tab-pane fade " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <!-- content ::start -->
                        <div class="row">
                            <div class="col-12">
                                <div class="section__title d-flex align-items-center gap_30 mb_30 flex-wrap">
                                    <h3 class="m-0 ">{{$new_user_zone->category_slogan}}</h3>

                                    <div class="nav new_portfolio_tab " id="nav-tab1" role="tablist">
                                        <button class="nav-link active" id="nav-All-tab" data-bs-toggle="tab" data-bs-target="#nav-All" type="button" role="tab" aria-controls="nav-All" aria-selected="true">{{__('common.all')}}</button>
                                        @foreach(@$new_user_zone->categories as $key => $category)
                                            @if($category->category->status != 0)
                                                <button class="nav-link" id="Category-tab_{{$category->id}}" data-bs-toggle="tab" data-bs-target="#Category_{{$category->id}}" type="button" role="tab" aria-controls="Category_{{$category->id}}" aria-selected="false">{{@$category->category->name}}</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="tab-content" id="nav-tabContent1">

                                    <div class="tab-pane fade show active" id="nav-All" role="tabpanel" aria-labelledby="nav-All-tab">
                                        <!-- content:start  -->
                                        @include('frontend.amazy.partials.new_user_zone_paginate_data._category_all_product_with_paginate')
                                        <!-- content:end  -->
                                    </div>
                                    @foreach(@$new_user_zone->categories as $key => $category)
                                        @if($category->category->status != 0)
                                            <div class="tab-pane fade" id="Category_{{$category->id}}" role="tabpanel" aria-labelledby="Category-tab_{{$category->id}}">
                                                @include('frontend.amazy.partials.new_user_zone_paginate_data._category_product_with_paginate')
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <!-- content ::start -->
                        <div class="row">
                            <div class="col-12">
                                @if ($couponShow == 1)
                                    <div class="coupon_gift_box">
                                        @include('frontend.amazy.partials.new_user_zone_paginate_data._coupon_code')
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="section__title d-flex align-items-center gap_30 mb_30 flex-wrap">
                                    <h3 class="m-0 ">{{$new_user_zone->coupon_slogan}}</h3>
                                    <div class="nav new_portfolio_tab " id="nav-tab2" role="tablist">
                                        <button class="nav-link active" id="CouponCategory-tab" data-bs-toggle="tab" data-bs-target="#CouponCategoryAll" type="button" role="tab" aria-controls="CouponCategoryAll" aria-selected="true">{{__('common.all')}}</button>

                                        @foreach(@$new_user_zone->couponCategories as $key => $category)
                                            @if(@$category->category->status != 0)
                                                <button class="nav-link" id="couponCategory-tab_{{$category->id}}" data-bs-toggle="tab" data-bs-target="#CouponCategory_{{$category->id}}" type="button" role="tab" aria-controls="CouponCategory_{{$category->id}}" aria-selected="false">{{@$category->category->name}}</button>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="tab-content" id="nav-tabContent2">
                                    <div class="tab-pane fade show active" id="CouponCategoryAll" role="tabpanel" aria-labelledby="CouponCategory-tab">
                                        <!-- content:start  -->

                                        @include('frontend.amazy.partials.new_user_zone_paginate_data._coupon_category_all_product_with_paginate')

                                        <!-- content:end  -->
                                    </div>
                                    @foreach(@$new_user_zone->couponCategories as $key => $category)
                                        @if(@$category->category->status != 0)
                                            <div class="tab-pane fade" id="CouponCategory_{{$category->id}}" role="tabpanel" aria-labelledby="couponCategory-tab_{{$category->id}}">
                                                @include('frontend.amazy.partials.new_user_zone_paginate_data._coupon_category_product_with_paginate')
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <!-- content ::end -->
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include(theme('partials.add_to_cart_script'))
@include(theme('partials.add_to_compare_script'))

@push('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click', '.page_counter_flash', function(event){
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetch_product_data(page);

                });

                function fetch_product_data(page){
                $('#pre-loader').show();
                if(page != ''){
                    $.ajax({
                    url:"{{route('frontend.new-user-zone.fetch-product-data',$new_user_zone->slug)}}"+'?item='+'product'+'&page='+page,
                    success:function(data)
                    {
                        $('#productDiv').html(data);
                        $('#pre-loader').hide();
                        initLazyload();
                    }
                });
                }else{
                    toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                }

                }


                $(document).on('click', '.allcategory_page_item', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('item=')[1];
                    fetch_all_category_data(page);
                });

                function fetch_all_category_data(page){
                    $('#pre-loader').show();

                    if(page != ''){
                        let url = "{{route('frontend.new-user-zone.fetch-all-category-data',$new_user_zone->slug)}}"+'?item='+page;

                        $.get(url, function(data){
                            $('#pre-loader').hide();
                            $('#nav-All').html(data);
                            initLazyload();
                        });

                    }

                }


                $(document).on('click', '.category_page_item', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('item=')[1];
                    let category = $(this)[0].parentNode.parentNode.id;
                    category = category.split('_');
                    fetch_category_data(page, category[1]);

                });


                function fetch_category_data(page, category){
                    $('#pre-loader').show();
                    if(page != ''){

                        let url = "{{route('frontend.new-user-zone.fetch-category-data',$new_user_zone->slug)}}"+'?item='+page;
                        let data = {
                            'parent_data' : category
                        }

                        $.get(url, data, function(data){
                            $('#pre-loader').hide();
                            $('#Category_'+category).html(data);
                            initLazyload();
                        });
                    }
                }



                $(document).on('click', '.all_coupon_category_page_item', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('item=')[1];
                    fetch_all_coupon_category_data(page);
                });

                function fetch_all_coupon_category_data(page){
                    $('#pre-loader').show();

                    if(page != ''){
                        let url = "{{route('frontend.new-user-zone.fetch-all-coupon-category-data',$new_user_zone->slug)}}"+'?item='+page;

                        $.get(url, function(data){
                            $('#pre-loader').hide();
                            $('#CouponCategoryAll').html(data);
                            initLazyload();
                        });

                    }

                }

                $(document).on('click', '.coupon_category_page_item', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('item=')[1];

                    let category = $(this)[0].parentNode.parentNode.id;
                    category = category.split('_');
                    fetch_coupon_category_data(page, category[1]);

                });


                function fetch_coupon_category_data(page, category){
                    $('#pre-loader').show();
                    if(page != ''){

                        let url = "{{route('frontend.new-user-zone.fetch-coupon-category-data',$new_user_zone->slug)}}"+'?item='+page;
                        let data = {
                            'parent_data' : category
                        }

                        $.get(url, data, function(data){
                            $('#pre-loader').hide();
                            $('#CouponCategory_'+category).html(data);
                            initLazyload();
                        });
                    }
                }


                $(document).on('click', '#get_now_btn', function(event){
                    event.preventDefault();
                    let coupon_id = $('#coupon_id').val();

                    let data = {
                        'coupon_id' : coupon_id,
                        '_token' : '{{ csrf_token() }}',
                        'new_user_zone_slug' : '{{$new_user_zone->slug}}'
                    }
                    $('#pre-loader').show();
                    $.post('{{route("frontend.new-user-zone.coupon-store",$new_user_zone->slug)}}', data, function(data){
                        $('#pre-loader').hide();
                        if(data.error){
                            toastr.error(data.error,'Error');
                        }else{
                            $('#coupon_code_div').html(data);
                            toastr.success("{{__('defaultTheme.coupon_store_successfully')}}","{{__('common.success')}}");
                        }
                    });
                });


            });
        })(jQuery);
    </script>
@endpush
