@extends('frontend.amazy.layouts.app')

@section('title')
    {{$card->name}}
@endsection

@section('share_meta')
    <meta property="og:description" content="{{@$card->description}}" />
    <meta name="description" content="{{@$card->description}}">

    <meta property="og:title" content="{{@substr(@$card->name,0,60)}}" />
    <meta name="title" content="{{ @substr(@$card->name,0,60) }}"/>

    @if(@$card->thumbnail_image != null && @getimagesize(showImage(@$card->thumbnail_image))[0] > 200)
        <meta property="og:image" content="{{showImage(@$thumbnail_image->thumbnail_image)}}" />
    @elseif(count(@$card->galaryImages) > 0 && @getimagesize(showImage(@$card->galaryImages[0]->image_name))[0] > 200)
        <meta property="og:image" content="{{showImage(@$card->galaryImages[0]->image_name)}}" />
    @endif
    <meta property="og:url" content="{{route('frontend.gift-card.show',$card->sku)}}" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:type" content="{{@$card->description}}" />

    @php
        $total_tag = count($card->tags);
        $meta_tags = '';
        foreach($card->tags as $key => $tag){
            if($key + 1 < $total_tag){
                $meta_tags .= $tag->name.', ';
            }else{
                $meta_tags .= $tag->name;
            }
        }
    @endphp

    <meta name ="keywords", content="{{$meta_tags}}">
@endsection

@push('styles')
    <style>
        .slider-for .slick-slide{
            display: none;
        }
        .slider-for .slick-slide.slick-active{
            display: block;
            left: 0px!important;
        }
    </style>
@endpush
@section('content')
    <!-- product_details_wrapper::start  -->
<div class="product_details_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-lg-6 col-xl-6">
                        <div class="slider-container slick_custom_container mb_30">
                            <div class="slider-for gallery_large">
                                @if(count($card->galaryImages) > 0)
                                    @foreach($card->galaryImages as $image)
                                        <div class="item-slick {{$card->galaryImages->first()->id == $image->id?'slick-current slick-active':''}}" id="thumb_{{$image->id}}">
                                            <img class="zoom_01" src="{{showImage($image->image_name)}}" data-zoom-image="{{showImage($image->image_name)}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-slick slick-current slick-active" id="thumb_{{$card->id}}">
                                        <img class="zoom_01" src="{{showImage($card->thumbnail_image)}}" data-zoom-image="{{showImage($card->thumbnail_image)}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                    </div>
                                @endif
                            </div>
                            <div class="slider-nav">
                                @if(count($card->galaryImages) > 0) 
                                    @foreach($card->galaryImages as $i => $image)
                                        <div class="item-slick {{$i == 0?'slick-active slick-current':''}}">
                                            <img src="{{showImage($image->image_name)}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-slick slick-active slick-current">
                                        <img src="{{showImage($card->thumbnail_image)}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6 col-xl-6">
                        <div class="product_content_details mb_20">
                            <span class="stoke_badge">{{__('common.in_stock')}}</span>
                            <h3>{{$card->name}}</h3>
                            <div class="viendor_text d-flex align-items-center">
                                <p class="stock_text"> <span class="text-uppercase">{{__('defaultTheme.sku')}}:</span> {{$card->sku}}</p>
                            </div>
                            <div class="product_ratings">
                                <div class="stars">
                                    <x-rating :rating="$rating"/>
                                </div>
                                <span>{{getNumberTranslate(sprintf("%.2f",$rating))}}/{{getNumberTranslate(5)}} ({{$total_review<10?'0':''}}{{getNumberTranslate($total_review)}} {{__('defaultTheme.review')}})</span>
                            </div>

                            <div class="destils_prise_information_box mb_20">
                                <h2 class="pro_details_prise d-flex align-items-center  m-0">
                                    <span>
                                        @if(getGiftcardwithoutDiscountPrice($card) != single_price(0))
                                            {{getGiftcardwithDiscountPrice($card)}}
                                        @else
                                            {{single_price($card->selling_price)}}
                                        @endif
                                    </span>
                                </h2>
                                @if(@$card->hasDiscount())
                                    <div class="pro_details_disPrise d-flex align-items-center gap_15">
                                        <h4 class="discount_prise  m-0  "> <span class="text-decoration-line-through"> {{single_price($card->selling_price)}}</span> </h4>
                                        <span class="diccount_percents">
                                            @if($card->discount_type === 0)
                                                {{getNumberTranslate($card->discount)}} %
                                            @else
                                                {{single_price($card->discount)}}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="product_info">
                                
                                <div class="single_pro_varient">
                                    <h5 class="font_14 f_w_500 theme_text3 " >{{__('common.quantity')}}:</h5>
                                    <div class="product_number_count mr_5" data-target="amount-1">
                                        <span class="count_single_item inumber_decrement qtyChange" data-value="-"> <i class="ti-minus"></i></span>
                                        <input id="qty" class="count_single_item input-number qty" name="qty" type="text" value="1" readonly>
                                        <span class="count_single_item number_increment qtyChange" data-value="+"> <i class="ti-plus"></i></span>
                                    </div>
                                    
                                </div>
                                @php
                                    if($card->hasDiscount()){
                                        $base_price = selling_price($card->selling_price, $card->discount_type, $card->discount);
                                    }else{
                                        $base_price = $card->selling_price;
                                    }
                                    $showData = [
                                        'name' => $card->name,
                                        'url' => route('frontend.gift-card.show',$card->sku),
                                        'price' => single_price($base_price),
                                        'thumbnail' => showImage($card->thumbnail_image)
                                    ];
                                @endphp
                                <input type="hidden" name="unit_price" id="unit_price" value="{{$base_price}}">
                                <h5 class="mb-0">{{__('common.total')}}: 
                                    <span id="total_price">
                                        {{getGiftcardwithDiscountPrice($card)}}
                                    </span>
                                </h5>
                                <div class="row mt_30 ">
                                    <div class="col-md-6">
                                    <a data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-show="{{json_encode($showData)}}" class="cursor_pointer add_gift_card_to_cart amaz_primary_btn style2 mb_20  add_to_cart text-uppercase flex-fill text-center w-100">{{__('defaultTheme.add_to_cart')}}</a>
                                    </div>
                                    <div class="col-md-6">
                                    <a id="butItNow" class="cursor_pointer amaz_primary_btn3 mb_20  w-100 text-center justify-content-center text-uppercase buy_now_btn" data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-type="gift_card">{{__('common.buy_now')}}</a>
                                    </div>
                                </div>
                                
                                <div class="add_wish_compare d-flex alingn-items-center mb_20">
                                    <a href="#" class="single_wish_compare text-uppercase text-nowrap add_to_wishlist" data-product_id="{{$card->id}}" data-seller_id="1">
                                        <i class="ti-heart"></i> {{__('defaultTheme.add_to_wishlist')}}
                                    </a>
                                </div>
                            </div>
                            <div class="contact_wiz_box mt_20">
                                <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('defaultTheme.share_on')}}:</span>
                                <div class="contact_link">
                                    <a target="_blank" href="{{ Share::currentPage()->facebook()->getRawLinks() }}">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->twitter()->getRawLinks() }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->linkedin()->getRawLinks() }}">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->whatsapp()->getRawLinks() }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->telegram()->getRawLinks() }}">
                                        <i class="fab fa-telegram-plane"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->reddit()->getRawLinks() }}">
                                        <i class="ti-reddit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="product_details_dec mb_76">
                            <div class="product_details_dec_header">
                                <h4 class="font_20 f_w_400 m-0 ">{{__('common.description')}}</h4>
                            </div>
                            <div class="product_details_dec_body">
                                
                                <div class="single_desc mb_25">
                                    @php echo $card->description; @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @include(theme('partials._giftcard_review_with_paginate'),['reviews' => @$card->activeReviews, 'all_reviews' => $card->reviews])
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="amazcart_delivery_wiz mb_20">
                    <div class="amazcart_delivery_wiz_head">
                        <h4 class="font_18 f_w_700 m-0">{{__('shipping.delivery_returns')}}</h4>
                    </div>
                    <div class="amazcart_delivery_wiz_body">
                        <h4 class="font_16 f_w_700 mb_6">{{__('amazy.Email Delivery')}}</h4>
                        <p class="delivery_text font_14 f_w_400">
                            {{__('amazy.email delivery note')}}
                        </p>
                    </div>
                </div>
                @if(isModuleActive('MultiVendor'))
                    <div class="amazcart_delivery_wiz mb_30">
                        <div class="amazcart_delivery_wiz_head">
                            <h4 class="font_18 f_w_700 m-0">{{__('amazy.Seller Information')}}</h4>
                        </div>
                        <div class="amazcart_delivery_wiz_body">
                            <h4 class="font_14 f_w_700 mb-0">{{app('general_setting')->company_name}}</h4>
                            <div class="Information_box d-flex gap-2 flex-wrap ">
                                @php
                                    $seller_rating_avg = $card->seller->sellerReviews()->where('status',1)->avg('rating');
                                    $seller_score = ($seller_rating_avg * 20);
                                @endphp
                                <div class="Information_box_left flex-fill">
                                    <div class="single_info_seller d-flex align-items-center gap_15">
                                        <h4 class="font_14 f_w_500 m-0">{{$seller_score}}%</h4>
                                        <p class="font_14 f_w_400 m-0">{{__('amazy.Seller Score')}}</p>
                                    </div>
                                    {{-- <div class="single_info_seller d-flex align-items-center gap_15">
                                        <h4 class="font_14 f_w_500 m-0">2387</h4>
                                        <p class="font_14 f_w_400 m-0">Followers</p>
                                    </div> --}}
                                </div>
                                {{-- <div class="Information_box_right">
                                    <a href="#" class="amaz_primary_btn style3 text-uppercase">Follow</a>
                                </div> --}}
                            </div>
                            <div class="seller_performance_box">
                                <h4 class="font_14 f_w_700 text-uppercase ">{{__('amazy.Seller Performance')}}</h4>
                                @foreach($card->seller->sellerReviews->where('status',1) as $seller_review)
                                    <div class="single_seller_performance d-flex align-items-center gap_10 mb-1">
                                        <img src="{{showImage('frontend/amazy/img/product_details/star.svg')}}" alt="{{__('amazy.Order Fulfilment Rate')}}" title="{{__('amazy.Order Fulfilment Rate')}}">
                                        <p class="font_14 f_w_400 m-0">{{__('amazy.Order Fulfilment Rate')}}:</p>
                                        <h4 class="font_14 f_w_500 m-0">
                                            @if($seller_review->rating == 1)
                                            {{__('common.very_poor')}}
                                            @elseif($seller_review->rating == 2)
                                            {{__('common.poor')}}
                                            @elseif($seller_review->rating == 3)
                                            {{__('common.neutral')}}
                                            @elseif($seller_review->rating == 4)
                                            {{__('common.satisfactory')}}
                                            @elseif($seller_review->rating == 5)
                                            {{__('common.delightful')}}
                                            @endif
                                        </h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" id="login_check" value="@if(auth()->check()) 1 @else 0 @endif">
</div>
<!-- product_details_wrapper::end  -->
@endsection

@push('scripts')
    <script src="{{ asset(asset_path('frontend/default/js/zoom.js')) }}"></script>
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){

                if (window.matchMedia('(min-width: 500px)').matches && $(".zoom_01").length > 0) {
                    zoom_enable();
                }else{
                    $('.var_img_show').removeClass('zoom_01');
                }

                function zoom_enable(){
                    $(".zoom_01").elevateZoom({
                        zoomEnabled: true,
                        zoomWindowHeight:120,
                        zoomWindowWidth:120,
                        zoomLevel:.9
                    });
                }

                $(document).on('click', ".add_gift_card_to_cart", function(event) {
                    event.preventDefault();
                    addToCart($(this).attr('data-gift-card-id'),$(this).attr('data-seller'),$('#qty').val(),$(this).attr('data-base-price'),$(this).attr('data-shipping-method'),'gift_card',$(this).data('show'))
                });

                $(document).on('click', '.qtyChange' , function(){
                    qtyChange($(this).data('value'));
                });

                $(document).on('click', '.add_to_wishlist', function(event){
                    event.preventDefault();
                    let product_id = $(this).data('product_id');
                    let seller_id = $(this).data('seller_id');
                    let is_login = $('#login_check').val();
                    let type = 'gift_card';
                    if(is_login == 1){
                        addToWishlist(product_id,seller_id, type);
                    }else{
                        toastr.warning("{{__('defaultTheme.please_login_first')}}","{{__('common.warning')}}");
                    }

                });

                $(document).on('change', '#qty', function(){
                    totalValue($(this).val(),'#main_price','#total_price');
                });

                $(document).on('click', '.page-item a', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    fetch_data(page);

                });

                function fetch_data(page){
                    $('#pre-loader').show();

                    var url = "{{route('frontend.giftcard.reviews.get-data')}}" + '?giftcard_id='+ "{{$card->id}}" +'&page=' + page;

                    if(page != 'undefined'){
                        $.ajax({
                            url: url,
                            success:function(data)
                            {
                                $('#Reviews').html(data);
                                $('#pre-loader').hide();
                            }
                        });
                    }else{

                        toastr.warning("{{__('defaultTheme.this_is_undefined')}}","{{__('common.warning')}}");
                    }

                }

                function calculatePrice(main_price, discount, discount_type)
                {
                    var main_price = main_price;
                    var discount = discount;
                    var discount_type = discount_type;
                    var total_price = 0;
                    if (discount_type == 0) {
                        discount = (main_price * discount) / 100;
                    }
                    total_price = (main_price - discount);

                    $('#total_price').html(currency_format(total_price * qty));
                    $('#base_sku_price').val(total_price);
                    $('#final_price').val(total_price);
                }
                function qtyChange(val){
                    $('.cart-qty-minus').prop('disabled',false);

                    let qty = $('#qty').val();
                    if(val == '+'){
                        let qty1 = parseInt(++qty);
                        $('#qty').val(qty1)
                        totalValue(qty1);
                    }
                    if(val == '-'){
                        if(qty>1){
                            let qty1 = parseInt(--qty)
                            $('#qty').val(qty1)
                            totalValue(qty1)
                            $('.cart-qty-minus').prop('disabled',false);
                        }else{
                            $('.cart-qty-minus').prop('disabled',true);
                        }
                    }

                }
                function totalValue(qty){
                    let unit_price = $('#unit_price').val();
                    let value = parseInt(qty) * parseFloat(unit_price);
                    $('#total_price').html(currency_format(value));
                    $('#final_price').val(value);
                }

                $(document).on("click", ".buy_now_btn", function(event){
                    event.preventDefault();
                    buyNow($(this).attr('data-gift-card-id'),$(this).attr('data-seller'),$('#qty').val(),$(this).attr('data-base-price'),$(this).attr('data-shipping-method'),'gift_card');
                });

            });
        })(jQuery);


    </script>
@endpush