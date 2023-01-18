    <input type="hidden" id="url" value="{{url('/')}}">
    @php
        $base_url = url('/');
        $current_url = url()->current();
        $just_path = trim(str_replace($base_url,'',$current_url));
        $flash_deal = \Modules\Marketing\Entities\FlashDeal::where('status', 1)->first();
        $new_user_zone = \Modules\Marketing\Entities\NewUserZone::where('status', 1)->first();
    @endphp
    <input type="hidden" id="just_url" value="{{$just_path}}">
    <!-- HEADER::START -->
    <header class="amazcartui_header">
        <div id="sticky-header" class="header_area">
            @include('frontend.amazy.partials._submenu',[$compares])
            @include('frontend.amazy.partials._mainmenu')
            <!-- main_header_area  -->
            @include('frontend.amazy.partials._mega_menu')
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
            <div class="menu_search_popup">
                <form class="menu_search_popup_field" method="GET" id="search_form2">
                    <input type="text" class="category_box_input2" placeholder="{{ __('defaultTheme.search_your_item') }}" id="inlineFormInputGroup">
                    <button type="submit" id="search_button">
                        <i class="ti-search"></i>
                    </button>
                </form>
                <span class="search_close home6_search_hide">
                    <i class="fas fa-times"></i>
                </span>
                <div class="live-search">
                    <ul class="p-0" id="search_items2">
                        <li class="search_item" id="search_empty_list2">
                            
                        </li>
                        <li class="search_item" id="search_history2">
                            
                        </li>
                        <li class="search_item" id="tag_search2">
                            
                        </li>
                        <li class="search_item" id="category_search2">
                            
                        </li>
                        <li class="search_item" id="product_search2">
                            
                        </li>
                        <li class="search_item" id="seller_search2">
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if(request()->is('gift-cards/*') || request()->is('product/*'))
            <div class="product_details_buttons d-md-none" id="cart_footer_mobile">
                
                @if(request()->is('product/*'))
                    @if(isModuleActive('MultiVendor'))
                        <a href="
                            @if ($product->seller->slug)
                                {{route('frontend.seller',$product->seller->slug)}}
                            @else
                                {{route('frontend.seller',base64_encode($product->seller->id))}}
                            @endif
                        " class="d-flex flex-column justify-content-center product_details_icon">
                            <i class="ti-save"></i>
                            <span>{{__('common.store')}}</span>
                        </a>
                    @else
                    <a href="{{url('/')}}" class="d-flex flex-column justify-content-center product_details_icon">
                        <i class="ti-home"></i>
                        <span>{{__('common.home')}}</span>
                    </a>
                    @endif
                    @if (@$product->stock_manage == 1 && @$product->skus->first()->product_stock >= @$product->product->minimum_order_qty || @$product->stock_manage == 0)
                        
                        <button type="button" class="product_details_button style1 buy_now_btn" data-id="{{$product->id}}" data-type="product">
                            <span>{{__('common.buy_now')}}</span>
                        </button>
                        
                        <button class="product_details_button add_to_cart_btn" type="button">{{__('common.add_to_cart')}}</button>
                    @else
                        <button type="button" class="product_details_button style1" disabled>
                            <span>{{__('defaultTheme.out_of_stock')}}</span>
                        </button>
                        <button type="button" class="product_details_button" disabled>{{__('defaultTheme.out_of_stock')}}</button>
                    @endif
                @else
                    
                    <button type="button" class="product_details_button style1 buy_now_btn" data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-type="gift_card">
                        <span>{{__('common.buy_now')}}</span>
                    </button>
                    
                    <button class="product_details_button add_gift_card_to_cart" type="button" data-gift-card-id="{{ $card->id }}" data-seller="1" data-base-price="{{$base_price}}" data-shipping-method="1" data-show="{{json_encode($showData)}}">{{__('common.add_to_cart')}}</button>
                @endif
            </div>
        @else
            <ul class="short_curt_icons">
                <li>
                    <a href="{{url('/')}}">
                        <div class="cart_singleIcon">
                            <i class="ti-home"></i>
                        </div>
                        <span>{{__('common.home')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/category') }}">
                        <div class="cart_singleIcon">
                            <i class="ti-align-justify"></i>
                        </div>
                        <span>{{__('common.category')}}</span>
                    </a>
                </li>
                <li>
                    <a class="position-relative" href="{{url('/cart')}}">
                        <div class="cart_singleIcon cart_singleIcon_cart d-flex align-items-center justify-content-center">
                            <i class="ti-shopping-cart"></i>
                        </div>
                        <span>{{__('common.cart')}} (<span class="cart_count_bottom">{{getNumberTranslate($items)}}</span>)</span>
                    </a>
                </li>
                <li>
                    @if (isset($flash_deal))
                        <a class="position-relative" href="{{ route('frontend.flash-deal', $flash_deal->slug) }}">
                            <div class="cart_singleIcon">
                                <img class="mb_5" src="{{showImage('frontend/amazy/img/amaz_icon/deals_white.svg')}}" alt="{{__('amazy.Daily Deals')}}" title="{{__('amazy.Daily Deals')}}">
                            </div>
                            <span>{{__('amazy.Daily Deals')}}</span>
                        </a>
                    @else
                        <a class="position-relative" href="{{url('/profile/notifications')}}">
                            <div class="cart_singleIcon">
                                <i class="ti-bell"></i>
                            </div>
                            <span>{{__('common.notification')}}</span>
                        </a>
                    @endif
                </li>
                @guest
                    <li>
                        <a href="{{ url('/login') }}">
                            <div class="cart_singleIcon">
                                <i class="ti-user"></i>
                            </div>
                            <span>{{ __('defaultTheme.login') }}</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('frontend.dashboard') }}">
                            <div class="cart_singleIcon">
                                <i class="ti-user"></i>
                            </div>
                            <span>{{__('common.account')}}</span>
                        </a>
                    </li>
                @endguest
            </ul>
        @endif
    </header>
    <!--/ HEADER::END -->

