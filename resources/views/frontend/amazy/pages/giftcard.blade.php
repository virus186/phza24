@extends('frontend.amazy.layouts.app')

@section('title')
    {{ __('common.gift_cards') }}
@endsection

@section('content')

<!-- brand_banner::start  -->
<div class="brand_banner d-flex align-items-center">
    <!-- <img src="img/banner/category_banner.jpg" alt="category_banner" class="img-fluid w-100"> -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="branding_text">{{ __('common.gift_cards') }}</h3>
            </div>
        </div>
    </div>
</div>
<!-- brand_banner::end  -->
<!-- prodcuts_area ::start  -->
<div class="prodcuts_area ">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3">
                <div id="product_category_chose" class="product_category_chose mb_30 mt_15">
                    <div class="course_title mb_15 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19.5" height="13" viewBox="0 0 19.5 13">
                            <g id="filter-icon" transform="translate(28)">
                                <rect id="Rectangle_1" data-name="Rectangle 1" width="19.5" height="2" rx="1" transform="translate(-28)" fill="#fd4949"/>
                                <rect id="Rectangle_2" data-name="Rectangle 2" width="15.5" height="2" rx="1" transform="translate(-26 5.5)" fill="#fd4949"/>
                                <rect id="Rectangle_3" data-name="Rectangle 3" width="5" height="2" rx="1" transform="translate(-20.75 11)" fill="#fd4949"/>
                            </g>
                        </svg>
                        <h5 class="font_16 f_w_700 mb-0 ">{{__('common.filter_category')}}</h5>
                        <div class="catgory_sidebar_closeIcon flex-fill justify-content-end d-flex d-lg-none">
                            <button id="catgory_sidebar_closeIcon" class="home10_primary_btn2 gj-cursor-pointer mb-0 small_btn">{{__('common.close')}}</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light text-dark refresh_btn" id="refresh_btn">{{__('amazy.refresh')}}</button>
                    </div>
                    <div class="course_category_inner">
                        <div class="single_pro_categry">
                            <h4 class="font_18 f_w_700">
                            {{__('common.filter_by')}} {{ __('defaultTheme.rating') }}
                            </h4>
                            <ul class="rating_lists mb_35">
                                <li>
                                    <div class="ratings">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <label class="primary_checkbox d-flex filter-by-rating-one">
                                            <input type="radio" name="attr_value[]" class="getProductByChoice attr_checkbox" data-id="rating" data-value="5" id="attr_value">
                                            <span class="checkmark mr_10"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="ratings">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <span>{{ __('defaultTheme.and_up') }}</span>
                                        <label class="primary_checkbox d-flex filter-by-ratings">
                                            <input type="radio" name="attr_value[]" class="getProductByChoice attr_checkbox" data-id="rating" data-value="4" id="attr_value">
                                            <span class="checkmark mr_10"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="ratings">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <span>{{ __('defaultTheme.and_up') }}</span>
                                        <label class="primary_checkbox d-flex filter-by-ratings">
                                            <input type="radio" name="attr_value[]" class="getProductByChoice attr_checkbox" data-id="rating" data-value="4" id="attr_value">
                                            <span class="checkmark mr_10"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="ratings">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <span>{{ __('defaultTheme.and_up') }}</span>
                                        <label class="primary_checkbox d-flex filter-by-ratings">
                                            <input type="radio" name="attr_value[]" class="getProductByChoice attr_checkbox" data-id="rating" data-value="4" id="attr_value">
                                            <span class="checkmark mr_10"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="ratings">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <i class="fas fa-star unrated"></i>
                                        <span>{{ __('defaultTheme.and_up') }}</span>
                                        <label class="primary_checkbox d-flex filter-by-ratings">
                                            <input type="radio" name="attr_value[]" class="getProductByChoice attr_checkbox" data-id="rating" data-value="4" id="attr_value">
                                            <span class="checkmark mr_10"></span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="single_pro_categry">
                            <h4 class="font_18 f_w_700">
                            {{__('common.filter_by_price')}}
                            </h4>
                            <div class="filter_wrapper">
                                <input type="hidden" id="min_price" value="{{$min_price}}" />
                                <input type="hidden" id="max_price" value="{{ $max_price }}" />
                                <div id="slider-range"></div>
                                <div class="d-flex align-items-center prise_line">
                                    <button class="home10_primary_btn2 mr_20 mb-0 small_btn js-range-slider-0">{{__('common.filter')}}</button>
                                    <span>{{__('common.price')}}: </span> <input type="text" id="amount" readonly >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-xl-9" id="dataWithPaginate">
                @include('frontend.amazy.partials._giftcard_list')
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
                var filterType = [];
                $(document).on('click', '#refresh_btn', function(event){
                    event.preventDefault();
                    fetch_data(1);

                    $('.attr_checkbox').prop('checked', false);

                    $('#price_range_div').html(
                        `<div class="wrapper">
                        <div class="range-slider">
                            <input type="text" class="js-range-slider-0" value=""/>
                        </div>
                        <div class="extra-controls form-inline">
                            <div class="form-group">
                                <div class="price_rangs">
                                    <input type="text" class="js-input-from form-control" id="min_price" value="100" readonly/>
                                    <p>Min</p>
                                </div>
                                <div class="price_rangs">
                                    <input type="text" class="js-input-to form-control" id="max_price" value="1000" readonly/>
                                    <p>Max</p>
                                </div>
                            </div>
                        </div>
                    </div>`
                    );

                    $(".js-range-slider-0").ionRangeSlider({
                        type: "double",
                        min: $('#min_price').val(),
                        max: $('#max_price').val(),
                        from: $('#min_price').val(),
                        to: $('#max_price').val(),
                        drag_interval: true,
                        min_interval: null,
                        max_interval: null
                    });

                });

                $(document).on('click', '.getProductByChoice', function(event){
                    let type = $(this).data('id');
                    let el = $(this).data('value');
                    getProductByChoice(type, el);
                });


                let minimum_price = 0;
                let maximum_price = 0;
                let price_range_gloval = 0;
                $(document).on('change', '.js-range-slider-0', function(event){
                    var price_range = $(this).val().split(';');
                    minimum_price = price_range[0];
                    maximum_price = price_range[1];
                    price_range_gloval = price_range;
                    myEfficientFn();
                });
                var myEfficientFn = debounce(function() {
                    $('#min_price').val(minimum_price);
                    $('#max_price').val(maximum_price);
                    getProductByChoice("price_range",price_range_gloval);
                }, 500);
                function debounce(func, wait, immediate) {
                    var timeout;
                    return function() {
                        var context = this, args = arguments;
                        var later = function() {
                            timeout = null;
                            if (!immediate) func.apply(context, args);
                        };
                        var callNow = immediate && !timeout;
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                        if (callNow) func.apply(context, args);
                    };
                };


                // $(".js-range-slider-0").ionRangeSlider({
                //     type: "double",
                //     min: $('#min_price').val(),
                //     max: $('#max_price').val(),
                //         from: $('#min_price').val(),
                //     to: $('#max_price').val(),
                //     drag_interval: true,
                //     min_interval: null,
                //     max_interval: null
                // });

                $(document).on('click', ".add_to_cart_gift_thumnail", function() {
                    addToCart($(this).attr('data-gift-card-id'),$(this).attr('data-seller'),1,$(this).attr('data-base-price'),1,'gift_card',$(this).data('prod_info'));
                });
                $(document).on('change', '.filterDataChange', function(event){
                    var paginate = $('#paginate_by').val();
                    var prev_stat = $('.filterCatCol').val();
                    var sort_by = $('#product_short_list').val();
                    $('#pre-loader').show();
                    if (prev_stat == 0) {
                        var url = "{{route('frontend.gift-card.fetch-data')}}";
                    }else {
                        var url = "{{route('frontend.gift-card.filter_page_by_type')}}";
                    }
                    $.get(url, {sort_by:sort_by, paginate:paginate}, function(data){
                        $('#dataWithPaginate').html(data);
                        $('#product_short_list').niceSelect();
                        $('#paginate_by').niceSelect();
                        $('#pre-loader').hide();
                        $('.filterCatCol').val(prev_stat);
                    });
                });

                $(document).on('click', '.page_link', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    var filterStatus = $('.filterCatCol').val();
                    if (filterStatus == 0) {
                        fetch_data(page);
                    }
                    else {
                        fetch_filter_data(page);
                    }

                });

                function getProductByChoice(type, el){

                    var objNew = {filterTypeId:type, filterTypeValue:[el]};

                    var objExistIndex = filterType.findIndex((objData) => objData.filterTypeId === type );
                    if (objExistIndex < 0) {
                        filterType.push(objNew);
                    }else {
                        var objExist = filterType[objExistIndex];
                        if (objExist && objExist.filterTypeId == "price_range") {
                            objExist.filterTypeValue.pop(el);
                        }
                        if (objExist && objExist.filterTypeId == "rating") {
                            objExist.filterTypeValue.pop(el);
                        }
                        if (objExist.filterTypeValue.includes(el)) {
                            objExist.filterTypeValue.pop(el);
                        }else {
                            objExist.filterTypeValue.push(el);
                        }
                    }

                    $('#pre-loader').show();
                    $.post('{{ route("frontend.gift-card.filter_by_type") }}', {_token:'{{ csrf_token() }}', filterType:filterType}, function(data){
                        $('#dataWithPaginate').html(data);
                        $('.filterCatCol').val(1);
                        $('#product_short_list').niceSelect();
                        $('#paginate_by').niceSelect();
                        $('#pre-loader').hide();

                    });

                }

                function fetch_data(page){
                    $('#pre-loader').show();
                    var paginate = $('#paginate_by').val();
                    var sort_by = $('#product_short_list').val();
                    if (sort_by != null && paginate != null) {
                        var url = "{{route('frontend.gift-card.fetch-data')}}"+'?sort_by='+sort_by+'&paginate='+paginate+'&page='+page;
                    }else if (sort_by == null && paginate != null) {
                        var url ="{{route('frontend.gift-card.fetch-data')}}"+'?paginate='+paginate+'&page='+page;
                    }else {
                        var url = "{{route('frontend.gift-card.fetch-data')}}" + '?page=' + page;
                    }
                    if(page != 'undefined'){
                        $.ajax({
                            url: url,
                            success:function(data)
                            {
                                $('#dataWithPaginate').html(data);
                                $('#product_short_list').niceSelect();
                                $('#paginate_by').niceSelect();
                                $('#pre-loader').hide();
                                activeTab();
                            }
                        });
                    }else{
                        toastr.warning('this is undefined')
                    }

                }
                function fetch_filter_data(page){
                    $('#pre-loader').show();
                    var paginate = $('#paginate_by').val();
                    var sort_by = $('#product_short_list').val();
                    if (sort_by != null && paginate != null) {
                        var url = "{{route('frontend.gift-card.filter_page_by_type')}}"+'?sort_by='+sort_by+'&paginate='+paginate+'&page='+page;
                    }else if (sort_by == null && paginate != null) {
                        var url = "{{route('frontend.gift-card.filter_page_by_type')}}"+'?paginate='+paginate+'&page='+page;
                    }else {
                        var url = "{{route('frontend.gift-card.filter_page_by_type')}}"+'?page='+page;
                    }
                    if(page != 'undefined'){
                        $.ajax({
                            url:url,
                            success:function(data)
                            {
                                $('#dataWithPaginate').html(data);
                                $('#product_short_list').niceSelect();
                                $('#paginate_by').niceSelect();
                                $('.filterCatCol').val(1);
                                $('#pre-loader').hide();
                                activeTab();
                            }
                        });
                    }else{
                        toastr.warning("{{__('defaultTheme.this_is_undefined')}}","{{__('common.warning')}}");
                    }

                }

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

                function activeTab(){
                    var active_tab = localStorage.getItem('view_product_tab');
                    if(active_tab != null && active_tab == 'profile'){
                        $("#profile").addClass("active");
                        $("#profile").addClass("show");
                        $("#home").removeClass('active');
                        $("#home-tab").removeClass("active");
                    }else{
                        $("#home").addClass("active");
                        $("#home").addClass("show");
                        $("#profile").removeClass('active');
                        $("#profile-tab").removeClass("active");
                    }
                }
                activeTab();

                $(document).on('click', ".view-product", function () {
                    var target = $(this).attr("href");
                    if(target == '#profile'){
                        localStorage.setItem('view_product_tab', 'profile');
                        $(this).addClass("active");
                        $("#profile").addClass("active");
                        $("#profile").addClass("show");
                        $("#home").removeClass('active');
                        $("#home-tab").removeClass("active");
                    }else{
                        localStorage.setItem('view_product_tab', 'home');
                        $("#home").addClass("active");
                        $("#home").addClass("show");
                        $("#profile").removeClass('active');
                        $("#profile-tab").removeClass("active");
                    }
                });

            });
        })(jQuery);
    </script>
@endpush