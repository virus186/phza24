@extends('frontend.amazy.layouts.app')
@section('title')
    {{ __('defaultTheme.select_shipping') }}
@endsection

@section('content')
    <!-- checkout_v3_area::start  -->
    <form action="{{route('frontend.checkout')}}" method="GET" enctype="multipart/form-data" id="mainOrderForm">
        <input type="hidden" name="step" value="select_payment">
        <div id="mainDiv">
            <div class="checkout_v3_area">
                <div class="checkout_v3_left d-flex justify-content-end">
                    <div class="checkout_v3_inner">
                        <div class="shiping_address_box checkout_form m-0">
                            <div class="billing_address">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="shipingV3_info mb_30">
                                            <div class="single_shipingV3_info d-flex align-items-start">
                                                <span>{{__('defaultTheme.contact')}}</span>
                                                <h5 class="m-0 flex-fill">{{$address->email}}</h5>
                                                <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                            </div>
                                            <div class="single_shipingV3_info d-flex align-items-start">
                                                <span>{{__('defaultTheme.ship_to')}}</span>
                                                <h5 class="m-0 flex-fill">{{$address->address}}</h5>
                                                <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h3 class="check_v3_title2 mb_13 ">{{__('defaultTheme.shipping_method')}}</h3>
                                    </div>
                                    <input type="hidden" value="home_delivery" name="delivery_type">
                                @if(isModuleActive('INTShipping'))
                                    <div class="col-12">
                                        @foreach($cartData as $key => $shipcart)
                                            @if($shipcart->product_type == 'product' && @$shipcart->product->product->product->is_physical)
                                                <div class="ck-cart-box">
                                                    <ul class="ck-cart-list">
                                                        <li class="d-grid align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <div class="img">
                                                                    <img src="@if($shipcart->product->product->product->product_type == 1)
                                                                    {{showImage($shipcart->product->product->product->thumbnail_image_source)}}
                                                                    @else
                                                                    {{showImage(@$shipcart->product->sku->variant_image?@$shipcart->product->sku->variant_image:@$shipcart->product->product->product->thumbnail_image_source)}}
                                                                    @endif" alt="{{$shipcart->product->product->product_name}}" title="{{$shipcart->product->product->product_name}}">
                                                                </div>
                                                            
                                                                <div class="content">
                                                                    <h6 class="mb-0">{{$shipcart->product->product->product_name}}</h6>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <p class="text-end">{{single_price($shipcart->price)}}</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    @php 
                                                        $products = \Modules\INTShipping\Entities\SellerProductShippingProfile::where('seller_product_id',$shipcart->product->product_id)->get();
                                                        $rates = [];
                                                        foreach ($products as $product) {
                                                            if($shipping_address != null){
                                                                $zones =  \Modules\INTShipping\Entities\ShippingZone::with('rates')->where('shipping_profile_id',$product->shipping_profile_id)->WhereHas('state_list', function($query) use($address){
                                                                        return $query->where('state_id', $address->state);
                                                                    })->get();
                                                            }else{
                                                                $zones =  \Modules\INTShipping\Entities\ShippingZone::with('rates')->where('shipping_profile_id',$product->shipping_profile_id)->WhereHas('state_list', function($query) use($shipping_address){
                                                                    return $query->where('state_id', $shipping_address->state);
                                                                })->get();
                                                            }
                                                            foreach($zones as $zone){
                                                                foreach($zone->rates as $rate){
                                                                    $rates[] = $rate;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    <select name="intshipping_cartItem[]" id="uniqueCartId{{$shipcart->id}}" class="theme_select w-100 ck-select intshiping" data-id="{{$shipcart->id}}">
                                                        <option value="0" selected disabled>{{__('defaultTheme.select_shipping')}}</option>
                                                        @foreach ($rates as $rate)
                                                            @php
                                                                $product_shipping_cost = 0;
                                                            @endphp
                                                            @if ($rate->base_on_item == 1)
                                                                @if ($rate->minimum * 1000 <= $shipcart->product->sku->weight && $rate->maximum * 1000 >= $shipcart->product->sku->weight)
                                                                    @php
                                                                        $product_shipping_cost = ($shipcart->total_price / 100) * $rate->rate_cost + $shipcart->product->sku->additional_shipping;
                                                                    @endphp
                                                                    <option value="{{($product_shipping_cost + $shipcart->product->sku->additional_shipping)}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost + $shipcart->product->sku->additional_shipping)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @elseif ($rate->base_on_item == 2)
                                                                @if ($rate->minimum <= $shipcart->price && $rate->maximum >= $shipcart->price)
                                                                    @php
                                                                        $product_shipping_cost = ($shipcart->total_price / 100) * $rate->rate_cost + $shipcart->product->sku->additional_shipping;
                                                                    @endphp
                                                                    <option value="{{($product_shipping_cost + $shipcart->product->sku->additional_shipping)}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost + $shipcart->product->sku->additional_shipping)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @else
                                                                @if ($rate->minimum <= $shipcart->price && $rate->maximum >= $shipcart->price)
                                                                    @php
                                                                        if(sellerWiseShippingConfig(1)['amount_multiply_with_qty']){
                                                                            $product_shipping_cost = ($rate->rate_cost + $shipcart->product->sku->additional_shipping) * $shipcart->qty;
                                                                        }else{
                                                                            $product_shipping_cost = $rate->rate_cost + $shipcart->product->sku->additional_shipping;
                                                                        }
                                                                    @endphp
                                                                    <option value="{{$product_shipping_cost}} {{$rate->id}}">{{$rate->rate_name}} - {{single_price($product_shipping_cost)}} - {{$rate->shipment_time}}</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="error_intship_cart_item_{{$shipcart->id}}"></span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="shipping_method_cost" id="shipping_method_cost" value="0">
                                    <input type="hidden" name="shipping_method" id="shipping_method">
                                @else
                                    <div class="col-12 mb_25">
                                        @php
                                            $additional_cost = 0;
                                            $totalItemPrice = 0;
                                            $totalItemWeight = 0;
                                            $physical_count = 0;
                                        @endphp
                                        @if(!isModuleActive('MultiVendor'))
                                            @foreach ($cartData as $ct => $item)
                                                @if($item->product_type == 'product' && @$item->product->product->product->is_physical)
                                                    @php
                                                        $additional_cost += $item->product->sku->additional_shipping;
                                                        $totalItemPrice += $item->total_price;
                                                        $totalItemWeight += !empty($item->product->sku->weight) ? $item->product->sku->weight * $item->qty : 0;
                                                        $physical_count += 1;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if($physical_count > 0)
                                                @php
                                                    $skip_method = null;
                                                    $disabled = [];
                                                @endphp
                                                @foreach($shipping_methods->where('id','>',1)->where('request_by_user', 1) as $key => $shipping)
                                                    @php
                                                        $cost = 0;
                                                        if($shipping->cost_based_on == 'Price'){
                                                            if($totalItemPrice > 0){
                                                                $cost = ($totalItemPrice / 100) * $shipping->cost + $additional_cost;
                                                            }

                                                        }elseif ($shipping->cost_based_on == 'Weight'){
                                                            if($totalItemWeight > 0){
                                                                $cost = ($totalItemWeight / 100) * $shipping->cost + $additional_cost;
                                                            }
                                                        }else{
                                                            if($shipping->cost > 0){
                                                                if(sellerWiseShippingConfig(1)['amount_multiply_with_qty']){
                                                                    $cost = ($shipping->cost + $additional_cost) * $item->qty;
                                                                }else{
                                                                    $cost = $shipping->cost + $additional_cost;
                                                                }
                                                            }
                                                        }
                                                        $checkoutRepo = new \App\Repositories\CheckoutRepository();
                                                        $tax_total = $checkoutRepo->totalAmountForPayment($cartData,$shipping,$address)['tax_total'];
                                                        // $total_check = $cost + $totalItemPrice + $tax_total;
                                                        $total_check = $totalItemPrice + $tax_total;
                                                    @endphp
                                                    @if($shipping->minimum_shopping >= $total_check)
                                                        @php
                                                            $disabled[] = $shipping->id;
                                                        @endphp
                                                    @else
                                                        @if($skip_method == null)
                                                            @php
                                                                $skip_method = $shipping->id;
                                                            @endphp
                                                        @endif
                                                    @endif

                                                    <div class="standard_shiping_box">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="primary_checkbox d-inline-flex style4 flex-wrap">
                                                                <input type="radio" data-cost="{{$cost}}" name="shipping_method" class="shipping_method" value="@if(in_array($shipping->id, $disabled))@else{{encrypt($shipping->id)}}@endif" {{$shipping->id == $skip_method?'checked':''}} @if(in_array($shipping->id, $disabled)) disabled @endif>
                                                                <span class="checkmark mr_10"></span>
                                                                <span class="label_name f_w_500 mute_text">{{$shipping->method_name}}</span>
                                                                <span class="label_name f_w_500 mute_text ml_15 text-nowrap">
                                                                    [
                                                                        {{sellerWiseShippingConfig(1)['carrier_show_for_customer'] == 1 ? $shipping->carrier->name. '-' :'' }}
                                                                        {{$shipping->shipment_time}} -

                                                                        [<span class="required_mark_theme">
                                                                        @if($shipping->cost_based_on == 'Price')
                                                                            Per Hundred
                                                                        @elseif($shipping->cost_based_on == 'Weight')
                                                                            Per 100 Gm
                                                                        @else
                                                                            Flat Rate
                                                                        @endif
                                                                        </span>]
                                                                    ]
                                                                </span>
                                                            </label>
                                                            @if($shipping->id == $skip_method)
                                                                <input type="hidden" id="shipping_method_cost" value="{{$cost}}">
                                                            @endif
                                                            <span class="text-nowrap">{{single_price($cost)}}</span>
                                                        </div>
                                                        <div>
                                                            <span class="label_name f_w_500 mute_text ml_15">{{__('shipping.minimum_shopping_amount')}} ({{__('shipping.without_shipping_cost')}}): {{single_price($shipping->minimum_shopping)}}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @php
                                                    $email_shipping = \Modules\Shipping\Entities\ShippingMethod::first();
                                                @endphp

                                                <div class="standard_shiping_box">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <label class="primary_checkbox d-inline-flex style4">
                                                            <input type="radio" data-cost="{{0}}" class="shipping_method" name="shipping_method" value="{{encrypt(1)}}" checked>
                                                            <span class="checkmark mr_10"></span>
                                                            <span class="label_name f_w_500 mute_text">{{$email_shipping->method_name}}</span>
                                                            <span class="label_name f_w_500 mute_text ml_15">
                                                                [
                                                                    {{sellerWiseShippingConfig(1)['carrier_show_for_customer'] == 1 ? $email_shipping->carrier->name. '-' :'' }}
                                                                    {{$email_shipping->shipment_time}}
                                                                ]
                                                            </span>
                                                        </label>
                                                        <input type="hidden" id="shipping_method_cost" value="0">
                                                        <span>{{single_price(0)}}</span>
                                                    </div>
                                                </div>

                                            @endif
                                        @endif
                                    </div>
                                @endif
                                    @if($errors->has('shipping_method'))
                                        <div class="col-12 mb_30 shipping_error">
                                            <span class="text-danger">{{__('shipping.minium_shopping_amount_is_not_fulfill')}}</span>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <div class="check_v3_btns flex-wrap d-flex align-items-center">
                                            @if(isModuleActive('INTShipping'))
                                            <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase" id="IntshippingBtn">{{__('defaultTheme.continue_to_payment')}}</button>
                                            @else
                                            <button type="submit" class="amaz_primary_btn style2  min_200 text-center text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
                                           @endif 
                                            <a href="{{url('/checkout')}}" class="return_text">{{__('defaultTheme.return_to_information')}}</a>
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
                                $tax = 0;

                            @endphp
                            @foreach($cartData as $key => $cart)
                                @php
                                    $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                                    $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                                    $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                                @endphp
                                @if($cart->product_type == 'product')
                                    <div class="singleVendor_product_lists">
                                        <div class="singleVendor_product_list d-flex align-items-center">
                                            <div class="thumb single_thumb">
                                                <img src="
                                                    @if($cart->product->product->product->product_type == 1)
                                                    {{showImage($cart->product->product->product->thumbnail_image_source)}}
                                                    @else
                                                    {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                                    @endif
                                                " alt="{{ \Illuminate\Support\Str::limit(@$cart->product->product->product_name, 28, $end='...') }}" title="{{ \Illuminate\Support\Str::limit(@$cart->product->product->product_name, 28, $end='...') }}">
                                            </div>
                                            <div class="product_list_content">
                                                <h4><a href="{{singleProductURL($cart->product->product->seller->slug, $cart->product->product->slug)}}">{{ \Illuminate\Support\Str::limit(@$cart->product->product->product_name, 28, $end='...') }}</a></h4>
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
                                                <h5 class="d-flex align-items-center"><span
                                                        class="product_count_text">{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
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
                                                $subtotal += $cart->product->selling_price * $cart->qty;
                                            }
                                        }else{
                                            $subtotal += $cart->product->selling_price * $cart->qty;
                                        }
                                    @endphp

                                    @if (file_exists(base_path().'/Modules/GST/') && $cart->product->product->product->is_physical == 1)

                                        @if ($address && app('gst_config')['enable_gst'] == "gst")
                                            @if (\app\Traits\PickupLocation::pickupPointAddress(1)->state_id == $address->state)

                                                @if($cart->product->product->product->gstGroup)
                                                    @php
                                                        $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                                        $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                                    @endphp
                                                    @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                                        @php
                                                            $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                                            $tax += $gstAmount;
                                                        @endphp
                                                    @endforeach
                                                @else

                                                    @foreach ($sameStateTaxes as $key => $sameStateTax)
                                                        @php
                                                            $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                                            $tax += $gstAmount;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            @else

                                                @if($cart->product->product->product->gstGroup)
                                                    @php
                                                        $diffStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->outsite_state_gst);
                                                        $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                                    @endphp
                                                    @foreach ($diffStateTaxesGroup as $key => $diffStateTax)
                                                        @php
                                                            $gstAmount = ($cart->total_price * $diffStateTax) / 100;
                                                            $tax += $gstAmount;
                                                        @endphp
                                                    @endforeach
                                                @else

                                                    @foreach ($diffStateTaxes as $key => $diffStateTax)
                                                        @php
                                                            $gstAmount = ($cart->total_price * $diffStateTax->tax_percentage) / 100;
                                                            $tax += $gstAmount;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            @endif

                                        @elseif(app('gst_config')['enable_gst'] == "flat_tax")

                                            @if($cart->product->product->product->gstGroup)
                                                @php
                                                    $flatTaxGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                                    $flatTaxGroup = (array) $flatTaxGroup;
                                                @endphp
                                                @foreach($flatTaxGroup as $sameStateTax)
                                                    @php
                                                        $gstAmount = $cart->total_price * $sameStateTax / 100;
                                                        $tax += $gstAmount;
                                                    @endphp
                                                @endforeach
                                            @else
                                                @php
                                                    $gstAmount = $cart->total_price * $flatTax->tax_percentage / 100;
                                                    $tax += $gstAmount;
                                                @endphp
                                            @endif

                                        @endif

                                    @else
                                        @if($cart->product->product->product->gstGroup)
                                            @php
                                                $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                                $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                            @endphp
                                            @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                                @php
                                                    $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                                    $tax += $gstAmount;
                                                @endphp
                                            @endforeach
                                        @else
                                            @foreach ($sameStateTaxes as $key => $sameStateTax)
                                                @php
                                                    $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                                    $tax += $gstAmount;
                                                @endphp
                                            @endforeach
                                        @endif

                                    @endif

                                @else
                                    <div class="singleVendor_product_lists">
                                        <div class="singleVendor_product_list d-flex align-items-center">
                                            <div class="thumb single_thumb">
                                                <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="{{ \Illuminate\Support\Str::limit(@$cart->giftCard->name, 28, $end='...') }}" title="{{ \Illuminate\Support\Str::limit(@$cart->giftCard->name, 28, $end='...') }}">
                                            </div>
                                            <div class="product_list_content">
                                                <h4><a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">{{ \Illuminate\Support\Str::limit(@$cart->giftCard->name, 28, $end='...') }}</a></h4>
                                                <h5 class="d-flex align-items-center"><span class="product_count_text" >{{getNumberTranslate($cart->qty)}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $actual_price += $cart->total_price;
                                        $subtotal += $cart->giftCard->selling_price * $cart->qty;
                                    @endphp
                                @endif
                            @endforeach

                            @php
                                $total = $actual_price + $tax;
                                $discount = $subtotal - $actual_price;
                                $totalCostWithoutShipping = $total;
                            @endphp

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
                                </div>
                                <div class="single_total_right">
                                    <span>+ <span id="shipping_cost"></span></span>
                                </div>
                            </div>
                            <div class="single_total_list d-flex align-items-center flex-wrap">
                                <div class="single_total_left flex-fill">
                                    <h4>{{__('common.discount')}}</h4>
                                </div>
                                <div class="single_total_right">
                                    <span>- {{single_price($discount)}}</span>
                                </div>
                            </div>
                            <div class="single_total_list d-flex align-items-center flex-wrap">
                                <div class="single_total_left flex-fill">
                                    <h4>{{__('common.vat/tax/gst')}}</h4>
                                </div>
                                <div class="single_total_right">
                                    <span>+ {{single_price($tax)}}</span>
                                </div>
                            </div>
                            <div class="total_amount d-flex align-items-center flex-wrap">
                                <div class="single_total_left flex-fill">
                                    <span class="total_text">{{__('common.total')}} (Incl. {{__('common.vat/tax/gst')}})</span>
                                </div>
                                <input type="hidden" id="total" value="{{$total}}">
                                <div class="single_total_right">
                                    <span class="total_text"><span id="grand_total"></span></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- checkout_v3_area::end  -->
@endsection
@push('scripts')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let shipping_amount = $('#shipping_method_cost').val();
                if(shipping_amount != undefined){
                    shipping_cost(shipping_amount);
                }else{
                    shipping_cost(0);
                    shipping_amount = 0;
                }
                let total = $('#total').val();
                let format_total = parseFloat(total) + parseFloat(shipping_amount);
                grand_total(format_total);

                $(document).on('click', '.shipping_method', function(){
                    let cost = $(this).data('cost');
                    shipping_cost(cost);
                    grand_total(parseFloat(total) + parseFloat(cost));
                    $('#shipping_method_cost').val(cost);
                });
                
                function shipping_cost(cost){
                    $('#shipping_cost').text(currency_format(cost));
                }
                function grand_total(total){
                    $('#grand_total').text(currency_format(total));
                }
        
                const unique = (value, index, self) => {
                    return self.indexOf(value) === index
                }
                const cartId = [];
                $(document).on('change', '.intshiping', function(){
                    const cartitem = $(this).data('id');
                    cartId.push(cartitem);
                    const uniqueCartId = cartId.filter(unique);
                    var cartCost = 0;
                    const ratrId = [];
                    for (let i = 0; i < uniqueCartId.length; i++) {
                        var itemvalue = $('#uniqueCartId'+uniqueCartId[i]).val();
                        const myArray = itemvalue.split(" ");
                            ratrId.push(myArray[1]);
                            cartCost += parseFloat(myArray[0]);
                            $('#error_intship_cart_item_'+uniqueCartId[i]).text('');
                        }
                    const cost = parseFloat(cartCost);
                    shipping_cost(cost);
                    grand_total(parseFloat(total) + parseFloat(cost));
                    $('#shipping_method_cost').val(cost);
                    $('#shipping_method').val(JSON.stringify(ratrId));
                }); 
                function selectIntshippingValidation(e){
                    var is_validate = 0;
                    $("select[name*='intshipping_cartItem']").each(function () {
                        var cartitem = $(this).data('id');
                        var item =$('#uniqueCartId'+cartitem).val();
                            if ($('#uniqueCartId'+cartitem).val() == null) {
                                $('#error_intship_cart_item_'+cartitem).text('Please Select Shipping Method');
                                is_validate = 1;
                            }
                    });
                    if(is_validate == 1){
                        e.preventDefault();
                    }
                }
                $(document).on('click', '#IntshippingBtn', function(e){
                    selectIntshippingValidation(e);
                });
            });
        })(jQuery);
    </script>
@endpush
