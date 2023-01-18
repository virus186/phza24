<form action="{{route('frontend.checkout')}}" method="GET" enctype="multipart/form-data" id="mainOrderForm">
    <div class="checkout_v3_area">
        <div class="checkout_v3_left d-flex justify-content-end">
            <div class="checkout_v3_inner">
                @guest
                    <div class="checout_head">
                        <i class="ti-folder"></i>
                        <p>{{__('defaultTheme.returning_customer')}}? <a href="{{url('/login')}}">{{__('defaultTheme.click_here_to_login')}}</a></p>
                    </div>
                @endguest
                @if(isModuleActive('MultiVendor'))
                    @php
                        $total = 0;
                        $subtotal = 0;
                        $actual_price = 0;
                        $tax = 0;
                        $current_pkg = 0;
                        $index = 0;
                        $total_shipping_charge = 0;
                        $is_physical_count = 0;
                    @endphp

                    @php
                        $package_wise_shipping = session()->get('package_wise_shipping');
                    @endphp
                    <div class="checout_head_title d-flex align-items-center ">
                        <span class="flex-fill">{{$total_items}} {{__('common.items')}}</span>
                        <span>{{__('common.quantity')}}</span>
                        <span>{{__('common.price')}}</span>
                    </div>
                    @foreach($cartData as $seller_id => $packages)
                        @php
                            $seller = App\Models\User::where('id',$seller_id)->first();
                            $is_physical_count = $package_wise_shipping[$seller_id]['physical_count'];
                            $seller_actual_price = 0;
                            $current_pkg ++;
                            $total_shipping_charge += $package_wise_shipping[$seller_id]['shipping_cost'];
                        @endphp
                        @if(isModuleActive('INTShipping'))
                            @php
                                $profiles = \Modules\INTShipping\Entities\ShippingProfile::where('user_id',$seller_id)->get();
                            @endphp
                        @endif
                        <div class="checkout_shiped_box mb_20">
                            @if(!isModuleActive('INTShipping'))
                                <div class="checout_shiped_head flex-wrap d-flex align-items-center ">
                                    <span class="package_text flex-fill">{{__('common.package')}} {{$current_pkg}} {{__('common.of')}} {{$total_package}}</span>
                                    <p class="flex-wrap">
                                        <span class="Shipped_text">{{__('defaultTheme.shipping')}} :</span>
                                        <span class="name_text text-nowrap">
                                            <a class="link_style font_16 f_w_700 text-nowrap m-0 theme_hover text_color" href="javascript:void(0)">
                                                @if($is_physical_count > 0)
                                                <span id="shipping_methods" data-target="shipping_methods_{{$package_wise_shipping[$seller_id]['seller_id']}}">{{single_price($package_wise_shipping[$seller_id]['shipping_cost'])}} via {{$package_wise_shipping[$seller_id]['shipping_method']}}   {{$package_wise_shipping[$seller_id]['shipping_time']}} =></span>
                                                @else
                                                {{single_price($package_wise_shipping[$seller_id]['shipping_cost'])}} via {{$package_wise_shipping[$seller_id]['shipping_method']}}   {{$package_wise_shipping[$seller_id]['shipping_time']}}
                                                @endif
                                            </a>
                                    </span>
                                    </p>
                                </div>
                            @endif
                            <div class="checout_shiped_products">
                                <div class="table-responsive mb-0 overflow-visible">
                                    <table class="table amazy_table3 style3 mb-0">
                                        <tbody>
                                        @if(isModuleActive('INTShipping'))  
                                            @foreach($packages as $key => $item)
                                                @if($item->product_type == 'product' && @$item->product->product->product->is_physical)
                                                    @php
                                                        $is_physical_count += 1;
                                                    @endphp
                                                @endif
                                                @if($item->product_type == 'product')
                                                    @php
                                                        $actual_price += $item->total_price;
                                                        $seller_actual_price += $item->total_price;
                                                        $pro_price = 0;
                                                        if (isModuleActive('WholeSale')){
                                                            $w_main_price = 0;
                                                            $wholeSalePrices = $item->product->wholeSalePrices;
                                                            if($wholeSalePrices->count()){
                                                                foreach ($wholeSalePrices as $w_p){
                                                                    if ( ($w_p->min_qty<=$item->qty) && ($w_p->max_qty >=$item->qty) ){
                                                                        $w_main_price = $w_p->selling_price;
                                                                    }
                                                                    elseif($w_p->max_qty < $item->qty){
                                                                        $w_main_price = $w_p->selling_price;
                                                                    }
                                                                }
                                                            }

                                                            if ($w_main_price!=0){
                                                                $subtotal += $w_main_price * $item->qty;
                                                                $pro_price = $w_main_price;
                                                            }else{
                                                                $subtotal += @$item->product->selling_price * $item->qty;
                                                                $pro_price = @$item->product->selling_price;
                                                            }
                                                        }else{
                                                            $subtotal += @$item->product->selling_price * $item->qty;
                                                            $pro_price = @$item->product->selling_price;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <a href="{{singleProductURL(@$item->seller->slug, @$item->product->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                <div class="thumb">
                                                                    <img src="@if(@$item->product->product->product->product_type == 1)
                                                                                {{showImage(@$item->product->product->product->thumbnail_image_source)}}
                                                                            @else
                                                                                {{showImage(@$item->product->sku->variant_image?@$item->product->sku->variant_image:@$item->product->product->product->thumbnail_image_source)}}
                                                                            @endif" alt="{{ textLimit(@$item->product->product->product_name, 28) }}" title="{{ textLimit(@$item->product->product->product_name, 28) }}">
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$item->product->product->product_name, 28) }}</h4>
                                                                    <p class="font_14 f_w_400 m-0 ">
                                                                        @if($item->product->product->product->product_type == 2)
                                                                            @php
                                                                                $countCombinatiion = count(@$item->product->product_variations);
                                                                            @endphp
                                                                            @foreach($item->product->product_variations as $key => $combination)
                                                                                @if($combination->attribute->name == 'Color')
                                                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                                                @else
                                                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                                                @endif

                                                                                @if($countCombinatiion > $key +1)
                                                                                    ,
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap_7">
                                                                @if($item->product->product->hasDeal)
                                                                    @if($item->product->product->hasDeal->discount > 0)
                                                                        @if($item->product->product->hasDeal->discount_type == 0)
                                                                            <span class="green_badge text-nowrap">-{{getNumberTranslate($item->product->product->hasDeal->discount)}}%</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @else
                                                                            <span class="green_badge text-nowrap">-{{single_price($item->product->product->hasDeal->discount)}}</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if(@$item->product->product->hasDiscount == 'yes')
                                                                        @if($item->product->product->discount_type == 0)
                                                                            <span class="green_badge text-nowrap">-{{$item->product->product->discount}}%</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @else
                                                                            <span class="green_badge text-nowrap">-{{single_price($item->product->product->discount)}}</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="font_16 f_w_500 mute_text text-nowrap">{{single_price($pro_price)}}</span>
                                                                    @endif
                                                                @endif

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{getNumberTranslate($item->qty)}}</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{single_price($item->total_price)}}</h4>
                                                        </td>
                                                    </tr>
                                        
                                                @else
                                                    @php
                                                        $actual_price += $item->total_price;
                                                        $seller_actual_price += $item->total_price;
                                                        $subtotal += $item->giftCard->selling_price * $item->qty;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <a href="{{route('frontend.gift-card.show',$item->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                <div class="thumb">
                                                                    <img src="{{showImage($item->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$item->giftCard->name, 28) }}" title="{{ textLimit(@$item->giftCard->name, 28) }}">
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$item->giftCard->name, 28) }}</h4>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap_7">
                                                                @if($item->giftCard->hasDiscount())
                                                                    @if($item->giftCard->discount_type == 0)
                                                                        <span class="green_badge text-nowrap">-{{$item->giftCard->discount}}%</span>
                                                                    @else
                                                                        <span class="green_badge text-nowrap">-{{single_price($item->giftCard->discount)}}</span>
                                                                    @endif
                                                                    <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($item->giftCard->selling_price)}}</span>
                                                                @else
                                                                    <span class="font_16 f_w_500 mute_text text-nowrap">{{single_price($item->giftCard->selling_price)}}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{$item->qty}}</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{single_price($item->total_price)}}</h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($item->product_type == 'product' && @$item->product->product->product->is_physical)
                                                <tr class="custom-tr">
                                                    <td colspan="4" class="p-0 border-0">
                                                    @php
                                                        $products = \Modules\INTShipping\Entities\SellerProductShippingProfile::whereHas('profile',function($query) use ($seller_id){
                                                            return $query->where('user_id',$seller_id);
                                                        })->where('seller_product_id',$item->product->product_id)->get();
                                                        $rates = [];

                                                        foreach ($products as $product) {
                                                            if($shipping_address != null){
                                                                $zones =  \Modules\INTShipping\Entities\ShippingZone::where('shipping_profile_id',$product->shipping_profile_id)->WhereHas('state_list', function($query) use($shipping_address){
                                                                    return $query->where('state_id', $shipping_address->state);
                                                                })->get();
                                                            }else{
                                                                $zones = [];
                                                            }

                                                            foreach($zones as $zone){
                                                                foreach($zone->rates as $rate){
                                                                    $rates[] = $rate;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    <select name="intshipping_cartItem[]" id="uniqueCartId{{$item->id}}" class="theme_select w-100 ck-select intshiping border-top-0" data-id="{{$item->id}}">
                                                        <option value="0" selected disabled>{{__('defaultTheme.select_shipping')}}</option>
                                                        @foreach ($rates as $rate)
                                                            @php
                                                                $product_shipping_cost = 0;
                                                            @endphp
                                                            @if ($rate->base_on_item == 1)
                                                                @if ($rate->minimum * 1000 <= $item->product->sku->weight && $rate->maximum * 1000 >= $item->product->sku->weight)
                                                                    @php
                                                                        $product_shipping_cost = ($item->total_price / 100) * $rate->rate_cost + $item->product->sku->additional_shipping;
                                                                    @endphp
                                                                    <option value="{{($product_shipping_cost + $item->product->sku->additional_shipping)}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost + $item->product->sku->additional_shipping)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @elseif ($rate->base_on_item == 2)
                                                                @if ($rate->minimum <= $item->price && $rate->maximum >= $item->price)
                                                                    @php
                                                                        $product_shipping_cost = ($item->total_price / 100) * $rate->rate_cost + $item->product->sku->additional_shipping;
                                                                    @endphp
                                                                    <option value="{{($product_shipping_cost + $item->product->sku->additional_shipping)}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost + $item->product->sku->additional_shipping)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @else
                                                                @if ($rate->minimum <= $item->price && $rate->maximum >= $item->price)
                                                                    @php
                                                                        if(sellerWiseShippingConfig($seller_id)['amount_multiply_with_qty']){
                                                                            $product_shipping_cost = ($rate->rate_cost + $item->product->sku->additional_shipping) * $item->qty;
                                                                        }else{
                                                                            $product_shipping_cost = $rate->rate_cost + $item->product->sku->additional_shipping;
                                                                        }
                                                                    @endphp
                                                                    <option value="{{$product_shipping_cost}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="error_intship_cart_item_{{$item->id}}"></span>
                                                            
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($packages as $key => $item)
                                                @if($item->product_type == 'product' && @$item->product->product->product->is_physical)
                                                    @php
                                                        $is_physical_count += 1;
                                                    @endphp
                                                @endif
                                                @if($item->product_type == 'product')
                                                    @php
                                                        $actual_price += $item->total_price;
                                                        $seller_actual_price += $item->total_price;
                                                        $pro_price = 0;
                                                        if (isModuleActive('WholeSale')){
                                                            $w_main_price = 0;
                                                            $wholeSalePrices = $item->product->wholeSalePrices;
                                                            if($wholeSalePrices->count()){
                                                                foreach ($wholeSalePrices as $w_p){
                                                                    if ( ($w_p->min_qty<=$item->qty) && ($w_p->max_qty >=$item->qty) ){
                                                                        $w_main_price = $w_p->selling_price;
                                                                    }
                                                                    elseif($w_p->max_qty < $item->qty){
                                                                        $w_main_price = $w_p->selling_price;
                                                                    }
                                                                }
                                                            }

                                                            if ($w_main_price!=0){
                                                                $subtotal += $w_main_price * $item->qty;
                                                                $pro_price = $w_main_price;
                                                            }else{
                                                                $subtotal += @$item->product->selling_price * $item->qty;
                                                                $pro_price = @$item->product->selling_price;
                                                            }
                                                        }else{
                                                            $subtotal += @$item->product->selling_price * $item->qty;
                                                            $pro_price = @$item->product->selling_price;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <a href="{{singleProductURL(@$item->seller->slug, @$item->product->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                <div class="thumb">
                                                                    <img src="@if(@$item->product->product->product->product_type == 1)
                                                                                {{showImage(@$item->product->product->product->thumbnail_image_source)}}
                                                                            @else
                                                                                {{showImage(@$item->product->sku->variant_image?@$item->product->sku->variant_image:@$item->product->product->product->thumbnail_image_source)}}
                                                                            @endif" alt="{{ textLimit(@$item->product->product->product_name, 28) }}" title="{{ textLimit(@$item->product->product->product_name, 28) }}">
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$item->product->product->product_name, 28) }}</h4>
                                                                    <p class="font_14 f_w_400 m-0 ">
                                                                        @if($item->product->product->product->product_type == 2)
                                                                            @php
                                                                                $countCombinatiion = count(@$item->product->product_variations);
                                                                            @endphp
                                                                            @foreach($item->product->product_variations as $key => $combination)
                                                                                @if($combination->attribute->name == 'Color')
                                                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                                                @else
                                                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                                                @endif

                                                                                @if($countCombinatiion > $key +1)
                                                                                    ,
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap_7">
                                                                @if($item->product->product->hasDeal)
                                                                    @if($item->product->product->hasDeal->discount > 0)
                                                                        @if($item->product->product->hasDeal->discount_type == 0)
                                                                            <span class="green_badge text-nowrap">-{{$item->product->product->hasDeal->discount}}%</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @else
                                                                            <span class="green_badge text-nowrap">-{{single_price($item->product->product->hasDeal->discount)}}</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if(@$item->product->product->hasDiscount == 'yes')
                                                                        @if($item->product->product->discount_type == 0)
                                                                            <span class="green_badge text-nowrap">-{{$item->product->product->discount}}%</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @else
                                                                            <span class="green_badge text-nowrap">-{{single_price($item->product->product->discount)}}</span>
                                                                            <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($pro_price)}}</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="font_16 f_w_500 mute_text text-nowrap">{{single_price($pro_price)}}</span>
                                                                    @endif
                                                                @endif

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{getNumberTranslate($item->qty)}}</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{single_price($item->total_price)}}</h4>
                                                        </td>
                                                    </tr>

                                                @else
                                                @php
                                                    $actual_price += $item->total_price;
                                                    $seller_actual_price += $item->total_price;
                                                    $subtotal += $item->giftCard->selling_price * $item->qty;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <a href="{{route('frontend.gift-card.show',$item->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                            <div class="thumb">
                                                                <img src="{{showImage($item->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$item->giftCard->name, 28) }}" title="{{ textLimit(@$item->giftCard->name, 28) }}">
                                                            </div>
                                                            <div class="summery_pro_content">
                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$item->giftCard->name, 28) }}</h4>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap_7">
                                                            @if($item->giftCard->hasDiscount())
                                                                @if($item->giftCard->discount_type == 0)
                                                                    <span class="green_badge text-nowrap">-{{$item->giftCard->discount}}%</span>
                                                                @else
                                                                    <span class="green_badge text-nowrap">-{{single_price($item->giftCard->discount)}}</span>
                                                                @endif
                                                                <span class="font_16 f_w_500 mute_text text-decoration-line-through text-nowrap">{{single_price($item->giftCard->selling_price)}}</span>
                                                            @else
                                                                <span class="font_16 f_w_500 mute_text text-nowrap">{{single_price($item->giftCard->selling_price)}}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{getNumberTranslate($item->qty)}}</h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{single_price($item->total_price)}}</h4>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @php
                            $total_check = $package_wise_shipping[$seller_id]['shipping_cost'] + $package_wise_shipping[$seller_id]['additional_cost'] + $seller_actual_price;

                            $a_carriers = \Modules\Shipping\Entities\Carrier::where('type','Automatic')->whereHas('carrierConfigFrontend',function ($q) use ($seller){
                                $q->where('seller_id',$seller->id)->where('carrier_status',1);
                            });
                            $m_carriers = \Modules\Shipping\Entities\Carrier::where('type','Manual')->where('status', 1)->where('created_by',$seller->id);
                            if(sellerWiseShippingConfig(1)['seller_use_shiproket']){
                                $carriers = $a_carriers->unionAll($m_carriers)->get()->pluck('id')->toArray();
                            }else{
                                $carriers = $m_carriers->get()->pluck('id')->toArray();
                            }
                            $seller_shippings = $shipping_methods->where('request_by_user',$seller->id)->whereIn('carrier_id',$carriers)->where('minimum_shopping','<=', $total_check);
                            if(count($seller_shippings) < 1){
                                $seller_shippings = $shipping_methods->where('request_by_user',$seller->id)->whereIn('carrier_id',$carriers)->take(1);
                            }
                        @endphp
                        @if(!isModuleActive('INTShipping'))
                            {{-- @dd($package_wise_shipping[$seller_id]) --}}
                            @include('frontend.amazy.partials._cart_shipping_method', ['shipping_methods' => $seller_shippings, 'package'=>$package_wise_shipping[$seller_id],'is_physical_count' => $is_physical_count])
                        @endif
                    @endforeach
                @endif
                <div class="shiping_address_box checkout_form m-0">
                    <h3 class="check_v3_title mb_25">{{__('defaultTheme.contact_information')}}</h3>
                    @if(auth()->check())
                        <div class="Contact_sVendor_box d-flex align-items-center mb_30">
                            <div class="thumb">
                                <img class="img-fluid" src="{{showImage(auth()->user()->avatar?auth()->user()->avatar:'frontend/default/img/avatar.jpg')}}" alt="{{textLimit(auth()->user()->first_name.' '.auth()->user()->last_name,28)}}" title="{{textLimit(auth()->user()->first_name.' '.auth()->user()->last_name,28)}}">
                            </div>
                            <div class="Contact_sVendor_info">
                                <h5>{{textLimit(auth()->user()->first_name.' '.auth()->user()->last_name,28)}} <span>({{auth()->user()->email}})</span> </h5>
                            </div>
                        </div>
                    @else
                        <div class="mb_20">
                            <label for="name" class="primary_label2 style3">{{__('common.email')}} <span>*</span></label>
                            <input class="primary_input3 style5 radius_3px" type="email" id="email" placeholder="{{__('common.email')}}"  value="{{$shipping_address?$shipping_address->email:''}}" name="email">
                        </div>
                    @endif
                    <div class="col-12 mb_25">
                        <label class="primary_checkbox d-flex">
                            <input type="checkbox" name="news_letter" value="1" checked>
                            <span class="checkmark mr_15"></span>
                            <span class="label_name f_w_400 ">{{__('defaultTheme.email_me_with_news_and_offers')}}</span>
                        </label>
                    </div>
                    <div class="billing_address">
                        <div class="shipping_delivery_div">
                            @php
                                $delivery_info = null;
                            @endphp
                            <h3 class="check_v3_title mb_25"> <span class="address_title">@if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') {{__('shipping.shipping_address')}} @else {{__('common.billing_address')}} @endif</span> 
                                @if($shipping_address)
                                    <span id="address_btn">
                                        <a href="javascript:void(0)" class="amaz_badge_btn3 text-uppercase text-nowrap link_btn_design">{{__('common.edit')}}</a>
                                    </span>
                                @elseif(isModuleActive('INTShipping') && isModuleActive('MultiVendor'))
                                    <span id="address_btn">
                                        <a href="javascript:void(0)" class="amaz_badge_btn3 text-uppercase text-nowrap saveAddress">{{__('common.save')}}</a>
                                    </span>
                                @endif
                            </h3>
                            @if(!isModuleActive('MultiVendor'))
                                @php
                                    if(session()->has('delivery_info')){
                                        $delivery_info = session()->get('delivery_info');
                                    }
                                @endphp
                                <div class="delivery_type_button">
                                    <label class="primary_bulet_checkbox">
                                        <input type="radio" name="delivery_type" class="payment_method"  value="home_delivery" @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                    <a>{{__('shipping.home_delivery')}}</a>
                                    @if(session()->has('buy_it_now') && @$cartData->where('is_buy_now', 1)->first()->product_type == 'gift_card')
                                    @else
                                        <label class="primary_bulet_checkbox ml-20">
                                            <input type="radio" name="delivery_type" class="payment_method"  value="pickup_location" @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <a>{{__('shipping.pickup_location')}}</a>

                                        <div class="pick_location_list_div mt_30 @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') d-none @endif">
                                            <label class="primary_label2 style2">{{__('shipping.pickup_location')}} <span>*</span></label>
                                            <select class="theme_select style2 wide" name="pickup_location" id="pickup_location" autocomplete="off">
                                                <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                @foreach($pickup_locations as $pickup_location)
                                                    <option value="{{base64_encode($pickup_location->id)}}" @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location' && $delivery_info['pickup_location'] == base64_encode($pickup_location->id)) selected @endif>
                                                        {{$pickup_location->pickup_location}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger pick_location_list_div @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') d-none @endif" id="error_pickup_location">{{ $errors->first('pickup_location') }}</span>
                                    @endif
                                </div>

                            @endif
                        </div>
                        <div class="row shipping_address_div mb_30 {{$shipping_address?'':"d-none"}}">
                            @php
                                $user_name = '';
                                $user_email = '';
                                $user_phone = '';
                            @endphp
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table-borderless">
                                        <tr>
                                            <td> {{__('common.name')}}</td>
                                            <td>: {{$shipping_address?$shipping_address->name:$user_name}}</td>
                                        </tr>
                                        <tr>
                                            <td> {{__('common.address')}}</td>
                                            <td>: {{$shipping_address?$shipping_address->address:''}}</td>
                                        </tr>
                                        <tr>
                                            <td> {{__('common.email')}}</td>
                                            <td>:  {{$shipping_address?$shipping_address->email:$user_email}}</td>
                                        </tr>
                                        <tr>
                                            <td> {{__('common.phone')}}</td>
                                            <td> :{{$shipping_address?$shipping_address->phone:$user_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td> {{__('common.postal_code_or_pin_code')}}</td>
                                            <td> :{{$shipping_address?$shipping_address->postal_code:''}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row shipping_address_edit_div {{$shipping_address?'d-none':""}}">
                            @php
                                $user_name = '';
                                $user_email = '';
                                $user_phone = '';
                            @endphp
                            @if(auth()->check())
                                <div class="col-lg-12">
                                    <label class="primary_label2 style2" for="name">{{__('defaultTheme.address_list')}} <span>*</span></label>
                                    <select class="theme_select style2 wide mb_20" name="address_id" id="address_id">
                                        <option value="0">{{__('defaultTheme.new_address')}}</option>
                                        @foreach (auth()->user()->customerAddresses as $address)
                                            <option value="{{$address->id}}" @if($shipping_address && $shipping_address->id == $address->id) selected @endif >{{$address->address}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $user_name = auth()->user()->first_name;
                                    $user_email = auth()->user()->email?auth()->user()->email:'';
                                    $user_phone = auth()->user()->phone?auth()->user()->phone:'';
                                @endphp
                            @endif
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3">{{__('common.name')}} <span>*</span></label>
                                <input class="primary_input3 style5 radius_3px" id="name" name="name" value="{{$shipping_address?$shipping_address->name:$user_name}}" type="text"  placeholder="{{__('common.name')}}">
                                <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3" for="address">{{__('common.address')}} <span>*</span></label>
                                <input class="primary_input3 style5 radius_3px" name="address" id="address" type="text"  placeholder="{{__('common.address')}}" value="{{$shipping_address?$shipping_address->address:''}}">
                                <span class="text-danger" id="error_address">{{ $errors->first('address') }}</span>
                            </div>
                            @if(auth()->check())
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3" for="email">{{__('common.email')}} <span>*</span></label>
                                <input class="primary_input3 style5 radius_3px" type="email" name="email" id="email" placeholder="{{__('common.email')}}" value="{{$shipping_address?$shipping_address->email:$user_email}}">
                                <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                            </div>
                            @endif
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3" for="phone">{{__('common.phone')}} <span>*</span></label>
                                <input class="primary_input3 style5 radius_3px" type="text" name="phone" value="{{$shipping_address?$shipping_address->phone:$user_phone}}" id="phone" placeholder="{{__('common.phone')}}">
                                <span class="text-danger" id="error_phone">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3">{{__('common.country')}} <span>*</span></label>
                                <select class="theme_select style2 wide" name="country" id="country" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $country->id }}" @if($shipping_address && $shipping_address->country == $country->id) selected @elseif(!$shipping_address && app('general_setting')->default_country == $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_country">{{ $errors->first('country') }}</span>
                            </div>
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3">{{__('common.state')}} <span>*</span></label>
                                <select class="theme_select style2 wide" name="state" id="state" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @if(app('general_setting')->default_country != null)
                                        @foreach ($states as $state)
                                            <option value="{{$state->id}}" @if($shipping_address && $shipping_address->state == $state->id) selected @elseif(app('general_setting')->default_state == $state->id) selected @endif>{{$state->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger" id="error_state">{{ $errors->first('state') }}</span>
                            </div>
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3">{{__('common.city')}} <span>*</span></label>
                                <select class="theme_select style2 wide" name="city" id="city" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{$city->id}}" @if($shipping_address && $shipping_address->city == $city->id) selected @endif>{{$city->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_city">{{ $errors->first('city') }}</span>
                            </div>
                            <div class="col-lg-6 mb_20">
                                <label class="primary_label2 style3" for="postal_code">{{__('common.postal_code_or_pin_code')}} @if($postalCodeRequired) <span>*</span> @endif</label>
                                <input class="primary_input3 style5 radius_3px" type="text"  id="postal_code" name="postal_code" placeholder="{{__('common.postal_code')}}" value="{{$shipping_address?$shipping_address->postal_code:''}}">
                                <span class="text-danger" id="error_postal_code">{{ $errors->first('postal_code') }}</span>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="primary_label2 style2" for="note">{{__('common.note')}}</label>
                                <textarea  name="note" id="note" placeholder="{{__('common.note')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.note')}}'" class="primary_textarea4 radius_5px mb_25"></textarea>
                                <span class="text-danger"  id="error_note"></span>
                            </div>
                            @if(env('NOCAPTCHA_FOR_CHECKOUT') == "true")
                            <div class="col-12 mb_20">
                                @if(env('NOCAPTCHA_INVISIBLE') != "true")
                                    <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                                @endif
                                    <span class="text-danger" id="captcha_response">{{ $errors->first('g-recaptcha-response') }}</span>
                            </div>
                            @endif
                            <div class="col-12 mb_25">
                                <label class="primary_checkbox d-flex">
                                    <input value="1" id="term_check" checked type="checkbox">
                                    <span class="checkmark mr_15"></span>
                                    <span class="label_name f_w_400 ">{{__('defaultTheme.I agree with the terms and conditions')}}.</span>
                                    <span id="error_term_check" class="text-danger"></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <div class="check_v3_btns flex-wrap d-flex align-items-center">
                                    @if(isModuleActive('MultiVendor'))
                                        <input type="hidden" name="step" value="select_payment">
                                        @if(env('NOCAPTCHA_FOR_CHECKOUT') == "true")
                                            @if(env('NOCAPTCHA_INVISIBLE') == "true")
                                                <button type="button" class="g-recaptcha amaz_primary_btn style2  min_200 text-center text-uppercase" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{__('defaultTheme.continue_to_payment')}}</button>
                                            @else
                                                <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase">{{__('defaultTheme.continue_to_payment')}}</button>
                                            @endif
                                        @else    
                                            <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
                                        @endif 
                                    @else
                                        <div id="next_step_btn_div">
                                            @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery')
                                                <input type="hidden" name="step" value="select_shipping">
                                                @if(env('NOCAPTCHA_FOR_CHECKOUT') == "true" && env('NOCAPTCHA_INVISIBLE') == "true")
                                                    <button type="button" class="g-recaptcha amaz_primary_btn style2  min_200 text-center text-uppercase" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{__('defaultTheme.continue_to_shipping')}}</button>
                                                @else
                                                    <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase ">{{__('defaultTheme.continue_to_shipping')}}</button>
                                                @endif
                                            @else
                                                <input type="hidden" name="step" value="select_payment">
                                                <input type="hidden" name="shipping_method" value="{{encrypt($free_shipping_for_pickup_location->id)}}">
                                                @if(env('NOCAPTCHA_FOR_CHECKOUT') == "true" && env('NOCAPTCHA_INVISIBLE') == "true")
                                                    <button type="button" class="g-recaptcha amaz_primary_btn style2  min_200 text-center text-uppercase" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{__('defaultTheme.continue_to_payment')}}</button>
                                                @else
                                                    <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    <a href="{{url('/cart')}}" class="return_text">{{__('defaultTheme.return_to_cart')}}</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="checkout_v3_right d-flex justify-content-start">
            <div class="order_sumery_box flex-fill">
                @if(!isModuleActive('MultiVendor'))
                    @php
                        $total = 0;
                        $subtotal = 0;
                        $actual_price = 0;
                    @endphp
                    @foreach($cartData as $key => $cart)
                        @if($cart->product_type == 'product')
                            <div class="singleVendor_product_lists">
                                <div class="singleVendor_product_list d-flex align-items-center cart_thumb_div">
                                    <div class="thumb">
                                        <img src="
                                        @if($cart->product->product->product->product_type == 1)
                                        {{showImage($cart->product->product->product->thumbnail_image_source)}}
                                        @else
                                        {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                        @endif
                                        " alt="{{ textLimit(@$cart->product->product->product_name, 28) }}" title="{{ textLimit(@$cart->product->product->product_name, 28) }}">
                                    </div>
                                    <div class="product_list_content">
                                        <h4><a href="{{singleProductURL(@$cart->seller->slug, @$cart->product->product->slug)}}">{{ textLimit(@$cart->product->product->product_name, 28) }}</a></h4>
                                        @if($cart->product->product->product->product_type == 2)
                                            @php
                                                $countCombinatiion = count(@$cart->product->product_variations);
                                            @endphp
                                            <p>
                                            @foreach($cart->product->product_variations as $key => $combination)
                                                @if($combination->attribute->name == 'Color')
                                                {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                @else
                                                {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                @endif

                                                @if($countCombinatiion > $key +1)
                                                ,
                                                @endif
                                            @endforeach
                                            </p>
                                        @endif
                                        <h5 class="d-flex align-items-center"><span class="product_count_text">{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                    </div>
                                </div>
                            </div>
                            @php
                                $actual_price += $cart->total_price;
                                if (isModuleActive('WholeSale')){
                                    $w_main_price = 0;
                                    $wholeSalePrices = $cart->product->wholeSalePrices;
                                    if($wholeSalePrices->count()){
                                        foreach ($wholeSalePrices as $w_p){
                                            if ( ($w_p->min_qty<=$cart->qty) && ($w_p->max_qty >=$cart->qty) ){
                                                $w_main_price = $w_p->selling_price;
                                            }
                                            elseif($w_p->max_qty < $cart->qty){
                                                $w_main_price = $w_p->selling_price;
                                            }
                                        }
                                    }

                                    if ($w_main_price!=0){
                                        $subtotal += $w_main_price * $cart->qty;
                                    }else{
                                        $subtotal += $cart->product->sku->selling_price * $cart->qty;
                                    }
                                }else{
                                    $subtotal += $cart->product->sku->selling_price * $cart->qty;
                                }

                            @endphp
                        @else
                            <div class="singleVendor_product_lists">
                                <div class="singleVendor_product_list d-flex align-items-center cart_thumb_div">
                                    <div class="thumb">
                                        <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$cart->giftCard->name, 28) }}" title="{{ textLimit(@$cart->giftCard->name, 28) }}">
                                    </div>
                                    <div class="product_list_content">
                                        <h4><a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">{{ textLimit(@$cart->giftCard->name, 28) }}</a></h4>
                                        <h5 class="d-flex align-items-center"><span class="product_count_text">{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                    </div>
                                </div>
                            </div>
                            @php
                                $actual_price += $cart->total_price;
                                $subtotal += $cart->giftCard->selling_price * $cart->qty;
                            @endphp
                        @endif
                    @endforeach
                @endif
                <h3 class="check_v3_title mb_25">{{__('common.order_summary')}}</h3>
                <div class="subtotal_lists">
                    <div class="single_total_list d-flex align-items-center">
                        <div class="single_total_left flex-fill">
                            <h4>{{ __('common.subtotal') }}</h4>
                        </div>
                        <div class="single_total_right">
                            <span>+ {{single_price($subtotal)}}</span>
                        </div>
                    </div>
                    <div class="single_total_list d-flex align-items-center flex-wrap">
                        <div class="single_total_left flex-fill">
                            <h4>{{__('common.shipping_charge')}}</h4>
                            @if(isModuleActive('MultiVendor'))
                                @if(isModuleActive('INTShipping'))
                                <p>{{ __('defaultTheme.product_wise_shipping_charge') }}</p>
                                @else
                                <p>{{ __('defaultTheme.package_wise_shipping_charge') }}</p>
                                @endif
                            @endif
                        </div>
                        <div class="single_total_right">
                            <span id="shipping_cost">
                                @if(isModuleActive('MultiVendor'))
                                    @if(isModuleActive('INTShipping'))
                                    + {{single_price(0)}}
                                    @else
                                    + {{single_price($total_shipping_charge)}}
                                    @endif
                                @else
                                {{__('defaultTheme.calculated_at_next_step')}}
                                @endif
                            </span>
                        </div>
                    </div>
                    @php
                        if(isModuleActive('MultiVendor')){
                            if(isModuleActive('INTShipping')){
                                $total = $actual_price;
                            }else{
                                $total = $actual_price + $total_shipping_charge;
                            }
                        }else{
                            $total = $actual_price;
                            $discount = $subtotal - $actual_price;
                        }
                    @endphp
                    <div class="single_total_list d-flex align-items-center flex-wrap">
                        <div class="single_total_left flex-fill">
                            <h4>{{__('common.discount')}}</h4>
                        </div>
                        <div class="single_total_right">
                            <span>-{{single_price($discount)}}</span>
                        </div>
                    </div>
                    <div class="single_total_list d-flex align-items-center flex-wrap">
                        <div class="single_total_left flex-fill">
                            <h4>{{__('common.vat/tax/gst')}}</h4>
                        </div>
                        <div class="single_total_right">
                            <span>{{__('defaultTheme.calculated_at_next_step')}}</span>
                        </div>
                    </div>
                    @php
                        $coupon = 0;
                        $coupon_id = null;
                        $total_for_coupon = $actual_price;
                    @endphp
                    <div class="total_amount d-flex align-items-center flex-wrap">
                        <div class="single_total_left flex-fill">
                            <span class="total_text">{{__('common.total')}}</span>
                        </div>
                        <div class="single_total_right">
                            @if(isModuleActive('INTShipping'))
                            <input type="hidden" id="total" value="{{$total}}">
                            <span class="total_text"><span id="grand_total">{{single_price($total-$coupon)}}</span></span>
                            @else
                               <span class="total_text"><span>{{single_price($total-$coupon)}}</span></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
