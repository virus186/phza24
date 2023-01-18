@php
    $current_url = url()->current();
@endphp
@if($current_url == url('/cart') || $current_url == url('/checkout'))
@else
    <!-- side_chartView_total::start  -->
    @if($items > 0)
        <div class="side_chartView_total d-flex align-items-center add_to_cart  gj-cursor-pointer ">
            <span class="remove_sidebar_cart gj-cursor-pointer d-inline-flex align-items-center justify-content-center" id="remove_cart_sidebar">
                <i class="ti-close"></i>
            </span>
            <div class="icon_lock dynamic_svg">
                {{-- <img src="{{url('/')}}/public/frontend/amazy/img/svg/lock_icon.svg" alt=""> --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 18 21">
                    <path id="lock_icon" d="M7,8V6A5,5,0,0,1,17,6V8h3a1,1,0,0,1,1,1V21a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V9A1,1,0,0,1,4,8Zm0,2H5V20H19V10H17v2H15V10H9v2H7ZM9,8h6V6A3,3,0,0,0,9,6Z" transform="translate(-3 -1)" fill="#fd4949"/>
                  </svg>                  
            </div>
            <div class="cart_view_text">
                <span>{{getNumberTranslate($items)}} {{__('product.items')}}</span>
                <h5 class="lh-1">{{single_price($carts->sum('total_price'))}}</h5>
            </div>
        </div>
    @endif
    <!-- side_chartView_total::end  -->

    <!-- shoping_cart::start  -->
    <div class="shoping_wrapper {{$items < 1?'d-none':''}}">
        <!-- <div class="dark_overlay"></div> -->
        <div class="shoping_cart">
            <div class="shoping_cart_inner">
                <div class="cart_header d-flex justify-content-between">
                    <div class="cart_header_text">
                        <h4>{{__('amazy.Shoping Cart')}}</h4>
                        <p>{{getNumberTranslate($items)}} {{__('amazy.Item’s selected')}}</p>
                    </div>
                    
                    <div class="chart_close">   
                        <i class="ti-close"></i>
                    </div>
                </div>
                @php
                    $subtotal = 0;
                    $base_url = url('/');
                    $current_url = url()->current();
                    $just_path = trim(str_replace($base_url,'',$current_url));
                @endphp
                @foreach ($carts as $key => $cart)
                    @php
                        $subtotal += $cart->price * $cart->qty;
                    @endphp
                    @if ($cart->product_type == "gift_card")
                        <div class="single_cart">
                            <div class="thumb d-flex align-items-center gap_10 mr_15">
                                {{-- <label class="primary_checkbox d-flex">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label> --}}
                                <div class="thumb_inner">
                                    <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$cart->giftCard->name, 20) }}" title="{{ textLimit(@$cart->giftCard->name, 20) }}">
                                </div>
                            </div>
                            <div class="cart_content flex-fill">
                                <a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">
                                    <h5>{{ textLimit(@$cart->giftCard->name, 20) }}</h5>
                                </a>
                                <div class="cart_content_text d-flex align-items-center gap_10 flex-fill flex-wrap">
                                    <div class="product_number_count style_2" data-target="amountc-1">
                                        <span class="count_single_item inumber_decrement"> <i class="ti-minus"></i></span>
                                        <input id="amountc-1" class="count_single_item input-number" type="text" data-value="{{$cart->qty}}" value="{{getNumberTranslate($cart->qty)}}">
                                        <span class="count_single_item number_increment"> <i class="ti-plus"></i></span>
                                    </div>
                                    <p><span class="prise" >{{single_price($cart->total_price)}}</span> </p>
                                </div>
                                
                            </div>
                            @if($just_path != '/checkout')
                                <div class="cart_trash_icon d-flex align-items-center  justify-content-end cursor_pointer" id="submenu_cart_btn_{{$cart->id}}">
                                    <span class="remove_from_submenu_btn" data-id="{{$cart->id}}" data-product_id="{{$cart->product_id}}" data-btn="#submenu_cart_btn_{{$cart->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12.249" height="15.076" viewBox="0 0 12.249 15.076">
                                            <g id="trash" transform="translate(-48)">
                                              <path id="Path_1449" data-name="Path 1449" d="M59.071,1.884H56.48V1.413A1.415,1.415,0,0,0,55.067,0H53.182a1.415,1.415,0,0,0-1.413,1.413v.471H49.178A1.179,1.179,0,0,0,48,3.062V4.711a.471.471,0,0,0,.471.471h.257l.407,8.547a1.412,1.412,0,0,0,1.412,1.346H57.7a1.412,1.412,0,0,0,1.412-1.346l.407-8.547h.257a.471.471,0,0,0,.471-.471V3.062A1.179,1.179,0,0,0,59.071,1.884Zm-6.36-.471a.472.472,0,0,1,.471-.471h1.884a.472.472,0,0,1,.471.471v.471H52.711ZM48.942,3.062a.236.236,0,0,1,.236-.236h9.893a.236.236,0,0,1,.236.236V4.24H48.942Zm9.23,10.623a.471.471,0,0,1-.471.449H50.547a.471.471,0,0,1-.471-.449l-.4-8.5h8.905Z" fill="#777"/>
                                              <path id="Path_1450" data-name="Path 1450" d="M240.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,1,0-.942,0V214.6A.471.471,0,0,0,240.471,215.067Z" transform="translate(-186.347 -201.875)" fill="#777"/>
                                              <path id="Path_1451" data-name="Path 1451" d="M320.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,1,0-.942,0V214.6A.471.471,0,0,0,320.471,215.067Z" transform="translate(-263.991 -201.875)" fill="#777"/>
                                              <path id="Path_1452" data-name="Path 1452" d="M160.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,0,0-.942,0V214.6A.471.471,0,0,0,160.471,215.067Z" transform="translate(-108.702 -201.875)" fill="#777"/>
                                            </g>
                                          </svg>                                          
                                    </span>
                                    {{-- <img class="remove_from_submenu_btn" data-id="{{$cart->id}}" data-product_id="{{$cart->product_id}}" data-btn="#submenu_cart_btn_{{$cart->id}}" src="{{url('/')}}/public/frontend/amazy/img/svg/trash.svg" alt=""> --}}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="single_cart">
                            <div class="thumb d-flex align-items-center gap_10 mr_15">
                                {{-- <label class="primary_checkbox d-flex">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label> --}}
                                <div class="thumb_inner">
                                    <img src="
                                    @if(@$cart->product->product->product->product_type == 1)
                                        {{showImage(@$cart->product->product->product->thumbnail_image_source)}}
                                    @else
                                        {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                    @endif
                                    " alt="{{ textLimit(@$cart->product->product->product_name, 20) }}" title="{{ textLimit(@$cart->product->product->product_name, 20) }}">
                                </div>
                            </div>
                            <div class="cart_content flex-fill">
                                <a href="{{singleProductURL($cart->seller->slug, $cart->product->product->slug)}}">
                                    <h5>{{ textLimit(@$cart->product->product->product_name, 20) }}</h5>
                                </a>
                                <div class="cart_content_text d-flex align-items-center gap_10 flex-fill flex-wrap">
                                    <div class="product_number_count style_2" data-target="amountc-1">
                                        <button id="sidebar_cart_minus_{{$cart->id}}" type="button" class="count_single_item inumber_decrement cart_qty_sidebar" value="-" data-value="-" data-id="{{$cart->id}}" data-product-id="{{$cart->product_id}}" data-qty="#sidebar_cart_qty_{{$cart->id}}" data-qty-minus-btn-id="#sidebar_cart_plus_{{$cart->id}}" data-maximum-qty="{{@$cart->product->product->product->max_order_qty}}" data-minimum-qty="{{@$cart->product->product->product->minimum_order_qty}}" data-stock-manage="{{@$cart->product->product->stock_manage}}" data-product-stock="{{@$cart->product->product_stock}}"> <i class="ti-minus"></i></button>
                                        <input id="sidebar_cart_qty_{{$cart->id}}" class="count_single_item input-number" type="text" data-value="{{$cart->qty}}" value="{{getNumberTranslate($cart->qty)}}" readonly>
                                        <button id="sidebar_cart_plus_{{$cart->id}}" type="button" class="count_single_item number_increment cart_qty_sidebar" value="+" data-value="+" data-id="{{$cart->id}}" data-product-id="{{$cart->product_id}}" data-qty="#sidebar_cart_qty_{{$cart->id}}" data-qty-plus-btn-id="#sidebar_cart_plus_{{$cart->id}}" data-maximum-qty="{{@$cart->product->product->product->max_order_qty}}" data-minimum-qty="{{@$cart->product->product->product->minimum_order_qty}}" data-stock-manage="{{@$cart->product->product->stock_manage}}" data-product-stock="{{@$cart->product->product_stock}}"> <i class="ti-plus"></i></button>
                                    </div>
                                    <p><span class="prise" >{{single_price($cart->total_price)}}</span> </p>
                                </div>
                                
                            </div>
                            @if($just_path != '/checkout')
                                <div class="cart_trash_icon d-flex align-items-center  justify-content-end cursor_pointer" id="submenu_cart_btn_{{$cart->id}}">
                                    <span class="remove_from_submenu_btn" data-id="{{$cart->id}}" data-product_id="{{$cart->product_id}}" data-btn="#submenu_cart_btn_{{$cart->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12.249" height="15.076" viewBox="0 0 12.249 15.076">
                                            <g id="trash" transform="translate(-48)">
                                              <path id="Path_1449" data-name="Path 1449" d="M59.071,1.884H56.48V1.413A1.415,1.415,0,0,0,55.067,0H53.182a1.415,1.415,0,0,0-1.413,1.413v.471H49.178A1.179,1.179,0,0,0,48,3.062V4.711a.471.471,0,0,0,.471.471h.257l.407,8.547a1.412,1.412,0,0,0,1.412,1.346H57.7a1.412,1.412,0,0,0,1.412-1.346l.407-8.547h.257a.471.471,0,0,0,.471-.471V3.062A1.179,1.179,0,0,0,59.071,1.884Zm-6.36-.471a.472.472,0,0,1,.471-.471h1.884a.472.472,0,0,1,.471.471v.471H52.711ZM48.942,3.062a.236.236,0,0,1,.236-.236h9.893a.236.236,0,0,1,.236.236V4.24H48.942Zm9.23,10.623a.471.471,0,0,1-.471.449H50.547a.471.471,0,0,1-.471-.449l-.4-8.5h8.905Z" fill="#777"/>
                                              <path id="Path_1450" data-name="Path 1450" d="M240.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,1,0-.942,0V214.6A.471.471,0,0,0,240.471,215.067Z" transform="translate(-186.347 -201.875)" fill="#777"/>
                                              <path id="Path_1451" data-name="Path 1451" d="M320.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,1,0-.942,0V214.6A.471.471,0,0,0,320.471,215.067Z" transform="translate(-263.991 -201.875)" fill="#777"/>
                                              <path id="Path_1452" data-name="Path 1452" d="M160.471,215.067a.471.471,0,0,0,.471-.471v-6.125a.471.471,0,0,0-.942,0V214.6A.471.471,0,0,0,160.471,215.067Z" transform="translate(-108.702 -201.875)" fill="#777"/>
                                            </g>
                                          </svg>                                          
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="shoping_cart_subtotal d-flex justify-content-between align-items-center">
                <h4 class="m-0">{{__('common.subtotal')}}</h4>
                <span>{{single_price($subtotal)}}</span>
            </div>
            <div class="view_checkout_btn d-flex justify-content-end mb_30 flex-column gap_10">
                <a href="{{url('/cart')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('defaultTheme.view_shopping_cart')}}</a>
                @if(!app('general_setting')->seller_wise_payment && !isModuleActive('MultiVendor'))
                <a href="{{url('/checkout')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('defaultTheme.proceed_to_checkout')}}</a>
                @endif
            </div>
        </div>
    </div>
    <!-- shoping_cart::end  -->
@endif