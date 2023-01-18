<!-- Modal::start  -->
<div class="modal fade theme_modal" id="theme_modal" tabindex="-1" role="dialog" aria-labelledby="theme_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="product_quick_view ">
                <button type="button" class="close_modal_icon" data-bs-dismiss="modal">
                    <i class="ti-close"></i>
                </button>
                    <div class="product_details_img" style="background-image: url(@if ($product->thum_img != null) {{showImage($product->thum_img)}} @else {{showImage($product->product->thumbnail_image_source)}} @endif)"></div>
                    <div class="product_details_wrapper">
                        <div class="product_content_details mb_30">
                            <p> <span>{{__('defaultTheme.sku')}}:</span> <span id="sku_id_li_modal" class="stock_text">{{@$product->skus->first()->sku->sku??'-'}}</span></p>
                            @php
                                $stock = 0;
                            @endphp
                            @if ($product->stock_manage == 1)
                                <p> <span>{{__('defaultTheme.availability')}}:</span> <span class="stock_text" id="availability_modal">{{ $product->skus->first()->product_stock }}</span> <span class="stock_text">{{__('common.in_stock')}}</span></p>
                            @else
                                <p class="stock_text"> <span>{{__('defaultTheme.availability')}}:</span> {{__('defaultTheme.unlimited')}}</p>
                            @endif
                            <h3>{{$product->product_name}}</h3>
                            <h5 class="prise_text d-flex align-items-center">{{getProductDiscountedPrice($product)}}</h5>
                            <div class="pro_details_disPrise d-flex align-items-center gap_15">
                                <h4 class="discount_prise  m-0  ">
                                    <span class="text-decoration-line-through">
                                        @if($product->hasDeal || $product->hasDiscount == 'yes')
                                            <span>{{single_price($product->skus->max('selling_price'))}}</span>
                                        @endif
                                    </span>
                                </h4>
                                <span class="diccount_percents">
                                    @if(@$product->hasDeal)
                                        @if(@$product->hasDeal->discount >0)
                                            @if(@$product->hasDeal->discount_type ==0)
                                                -{{@$product->hasDeal->discount}}%
                                            @else
                                                -{{single_price(@$product->hasDeal->discount)}}
                                            @endif
                                        @endif
                                    @else
                                        @if(@$product->hasDiscount == 'yes')
                                            @if($product->discount > 0)

                                                @if($product->discount_type == 0)
                                                -{{getNumberTranslate($product->discount)}}%
                                                @else
                                                -{{single_price($product->discount)}}
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </span>
                            </div>
                            <div class="product_ratings">
                                <div class="stars justify-content-center">
                                    <x-rating :rating="$rating"/>
                                </div>
                                <span>({{$total_review}} {{__('defaultTheme.review')}})</span>
                            </div>
                            @if($product->product->product_type == 2)

                                @foreach (session()->get('item_details') as $key => $item)
                                        @if ($item['name'] === "Color")
                                            <div class="product_color_varient mb_20">
                                                <h5 class="font_14 f_w_500 theme_text3  text-capitalize d-block mb_10" >{{ $item['name'] }}:</h5>
                                                <div class="color_List d-flex gap_5 flex-wrap">
                                                    <input type="hidden" class="attr_value_name" name="attr_val_name_modal[]" value="{{$item['value'][0]}}">
                                                    <input type="hidden" class="attr_value_id" name="attr_val_id_modal[]" value="{{$item['id'][0]}}-{{$item['attr_id']}}">
                                                    @foreach ($item['value'] as $ks => $value_name)
                                                        <label class="round_checkbox d-flex">
                                                            <input id="radio-{{$ks}}" name="color_filt" class="attr_val_name" type="radio" color="color" @if ($ks === 0) checked @endif data-value="{{ $item['id'][$ks] }}" data-value-key="{{$item['attr_id']}}" value="{{ $value_name }}"/>
                                                            <span class="checkmark modal_colors_{{$ks}} class_color_{{ $item['code'][$ks] }}">
                                                                <div class="check_bg_color"></div>
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if ($item['name'] != "Color")
                                            <div class="product_color_varient mb_20">
                                                <h5 class="font_14 f_w_500 theme_text3  text-capitalize d-block mb_10" >{{$item['name']}}:</h5>
                                                <div class="color_List d-flex gap_5 flex-wrap">
                                                    <input type="hidden" class="attr_value_name" name="attr_val_name_modal[]" value="{{$item['value'][0]}}">
                                                    <input type="hidden" class="attr_value_id" name="attr_val_id_modal[]" value="{{$item['id'][0]}}-{{$item['attr_id']}}">
                                                    @foreach ($item['value'] as $m => $value_name)
                                                        <a class="attr_val_name size_btn not_111 @if ($m === 0) selected_btn @endif" color="not" data-value-key="{{$item['attr_id']}}" data-value="{{ $item['id'][$m] }}">{{ $value_name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                            @endif
                            
                            <input type="hidden" name="product_sku_id" id="product_sku_id_modal"
                                value="{{$product->product->product_type == 1?$product->skus->first()->id : $product->skus->first()->id}}">
                            <input type="hidden" name="seller_id" id="seller_id_modal" value="{{$product->user_id}}">
                            <input type="hidden" name="stock_manage_status" id="stock_manage_status_modal"
                                value="{{$product->stock_manage}}">
                            <input type="hidden" id="product_id_modal" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" id="maximum_order_qty_modal"
                                value="{{@$product->product->max_order_qty}}">
                            <input type="hidden" id="minimum_order_qty_modal"
                                value="{{@$product->product->minimum_order_qty}}">
                            <input type="hidden" name="product_type" class="product_type"
                                    value="{{ $product->product->product_type }}">
                                    <input type="hidden" name="base_sku_price" id="base_sku_price_modal"
                                    value="
                                        @if(@$product->hasDeal)
                                            {{ selling_price($product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount) }}
                                        @else
                                            @if($product->hasDiscount == 'yes')
                                                {{ selling_price($product->skus->first()->selling_price,$product->discount_type,$product->discount) }}
                                            @else
                                                {{ $product->skus->first()->selling_price }}
                                            @endif
                                        @endif
                                        ">
                                <input type="hidden" name="final_price" id="final_price_modal" value="
                                        @if(@$product->hasDeal)
                                            {{ selling_price($product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount) }}
                                        @else
                                            @if($product->hasDiscount == 'yes')
                                                {{ selling_price($product->skus->first()->selling_price,$product->discount_type,$product->discount) }}
                                            @else
                                                {{ $product->skus->first()->selling_price }}
                                            @endif
                                        @endif
                                        ">
                                <input type="hidden" value="{{textLimit($product->product_name, 28)}}" id="product_name_modal">
                                <input type="hidden" value="{{singleProductURL(@$product->seller->slug, @$product->slug)}}" id="product_url_modal">
                                <input type="hidden" name="thumb_image" id="thumb_image_modal" value="@if ($product->thum_img != null) {{showImage($product->thum_img)}} @else {{showImage($product->product->thumbnail_image_source)}} @endif">
                            <div class="product_info">

                                <div class="single_pro_varient">
                                    <h5 class="font_14 f_w_500 theme_text3 " >{{__('common.quantity')}}:</h5>
                                    <div class="product_number_count mr_5" data-target="amount-10">
                                        <button class="count_single_item inumber_decrement cart-qty-minus-modal qtyChangeMinus" value="-"> <i class="ti-minus"></i></button>
                                        <input id="qty_modal" name="qty" class="count_single_item input-number qty" type="text" data-value="{{@$product->product->minimum_order_qty}}" value="{{getNumberTranslate(@$product->product->minimum_order_qty)}}" readonly>
                                        <button class="count_single_item number_increment qtyChangePlus cart-qty-plus-modal" value="+"> <i class="ti-plus"></i></button>
                                    </div>

                                </div>
                                <div class="row mt_20">
                                    <h4><span>{{__('common.total')}}:</span>
                                        <span id="total_price_modal">
                                            @if(@$product->hasDeal)
                                                {{single_price(selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) * $product->product->minimum_order_qty)}}
                                            @else
                                                @if($product->hasDiscount == 'yes')
                                                    {{single_price(selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) * $product->product->minimum_order_qty)}}
                                                @else
                                                    {{single_price(@$product->skus->first()->selling_price * $product->product->minimum_order_qty)}}
                                                @endif
                                            @endif
                                        </span>
                                    </h4>
                                </div>
                                <div class="row mt_30" id="add_to_cart_div_modal">
                                    @if ($product->stock_manage == 1 && $product->skus->first()->product_stock >= $product->product->minimum_order_qty || $product->stock_manage == 0)
                                        <div class="col-md-6">
                                            <a href="" id="add_to_cart_btn_modal" class="home10_primary_btn2 mb_20 w-100 text-center add_to_cart text-uppercase flex-fill text-center">{{__('common.add_to_cart')}}</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="#" class="home10_primary_btn4  w-100 radius_5px mb_20 w-100 text-center justify-content-center text-uppercase buy_now_btn_modal" data-id="{{$product->id}}" data-type="product">{{__('common.buy_now')}}</a>
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <button type="button" disabled class="amaz_primary_btn style2 mb_20  add_to_cart text-uppercase flex-fill text-center w-100">{{__('defaultTheme.out_of_stock')}}</button>
                                        </div>
                                    @endif
                                </div>

                                <div class="add_wish_compare d-flex alingn-items-center mb-0">
                                    <a href="#" class="single_wish_compare add_to_wishlist_modal" id="wishlist_btn" data-product_id="{{$product->id}}"
                                        data-seller_id="{{$product->user_id}}">
                                        <i class="ti-heart"></i> {{__('defaultTheme.add_to_wishlist')}}
                                    </a>
                                    <a href="compare.php" class="single_wish_compare" id="add_to_compare_btn"
                                    data-product_sku_id="#product_sku_id_modal"
                                    data-product_type="{{$product->product->product_type}}">
                                        <i class="ti-control-shuffle"></i> {{__('defaultTheme.add_to_compare')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(@$product->hasDeal)
        <input type="hidden" id="discount_type_modal" value="{{@$product->hasDeal->discount_type}}">
        <input type="hidden" id="discount_modal" value="{{@$product->hasDeal->discount}}">
    @else
        @if(@$product->hasDiscount == 'yes')
            <input type="hidden" id="discount_type_modal" value="{{$product->discount_type}}">
            <input type="hidden" id="discount_modal" value="{{$product->discount}}">
        @else
            <input type="hidden" id="discount_type_modal" value="{{$product->discount_type}}">
            <input type="hidden" id="discount_modal" value="0">
        @endif
    @endif

    <!-- for whole sale price -->
    @if(isModuleActive('WholeSale'))
        <input type="hidden" id="getWholesalePriceModal" value="@if(@$product->skus->first()->wholeSalePrices->count()){{ json_encode(@$product->skus->first()->wholeSalePrices) }} @else 0 @endif">
    @endif

    <input type="hidden" id="isWholeSaleActiveModal" value="{{isModuleActive('WholeSale')}}">
    <input type="hidden" id="owner_modal" value="{{encrypt($product->user_id)}}">
</div>
<!-- Modal::end  -->

<script>
    (function($){
        "use strict";

        $(document).ready(function(){
            var productType = $('.product_type').val();
            if (productType == 2) {
                '@if (session()->has('item_details'))'+
                    '@foreach (session()->get('item_details') as $key => $item)'+
                        '@if ($item['name'] === "Color")'+
                            '@foreach ($item['value'] as $k => $value_name)'+
                                $(".modal_colors_{{$k}}").css("background", "{{ $item['code'][$k]}}");
                            '@endforeach'+
                        '@endif'+
                    '@endforeach'+
                '@endif'
            }
        });
    })(jQuery);
</script>
