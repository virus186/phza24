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
                    @foreach($cartData as $seller_id => $packages)
                        @php
                            $seller = App\Models\User::where('id',$seller_id)->first();
                            $is_physical_count = $package_wise_shipping[$seller_id]['physical_count'];
                            $seller_actual_price = 0;
                        @endphp

                        @php
                            $current_pkg ++;
                            $total_shipping_charge += $package_wise_shipping[$seller_id]['shipping_cost'];
                        @endphp
                        <div class="checkout_shiped_box mb_10">
                            <div class="checout_shiped_head flex-wrap d-flex align-items-center ">
                                <span class="package_text flex-fill">{{__('common.package')}} {{$current_pkg}} {{__('common.of')}} {{$total_package}}</span>
                                <p>
                                    <span class="Shipped_text text-nowrap">
                                        {{__('defaultTheme.shipping')}} :
                                    </span>
                                    <span class="name_text text-nowrap">
                                            <a class="link_style" href="javascript:void(0)">
                                                @if($is_physical_count > 0)
                                                <span id="shipping_methods" data-target="shipping_methods_{{$package_wise_shipping[$seller_id]['seller_id']}}">{{single_price($package_wise_shipping[$seller_id]['shipping_cost'])}} via {{$package_wise_shipping[$seller_id]['shipping_method']}}   {{$package_wise_shipping[$seller_id]['shipping_time']}} =></span>
                                                @else
                                                {{single_price($package_wise_shipping[$seller_id]['shipping_cost'])}} via {{$package_wise_shipping[$seller_id]['shipping_method']}}   {{$package_wise_shipping[$seller_id]['shipping_time']}}
                                                @endif
                                            </a>
                                    </span>
                                </p>
                            </div>

                            <div class="checout_shiped_products mt-2">
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
                                                }else{
                                                    $subtotal += $item->product->sku->selling_price * $item->qty;
                                                }
                                            }else{
                                                $subtotal += $item->product->sku->selling_price * $item->qty;
                                            }
                                        @endphp
                                        <div class="single_checkout_shiped_product d-flex align-items-center">
                                            <div class="product_information d-flex align-items-center flex-fill">
                                                <div class="thumb">
                                                    <img src="
                                                    @if(@$item->product->product->product->product_type == 1)
                                                        {{showImage(@$item->product->product->product->thumbnail_image_source)}}
                                                    @else
                                                        {{showImage(@$item->product->sku->variant_image?@$item->product->sku->variant_image:@$item->product->product->product->thumbnail_image_source)}}
                                                    @endif
                                                    " alt="">
                                                </div>
                                                <div class="product_content">

                                                    <p>{{ textLimit(@$item->product->product->product_name, 28) }}</p>
                                                    @if($item->product->product->product->product_type == 2)
                                                        <span>
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
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="offer_prise">
                                                @if($item->product->product->hasDeal)
                                                    @if($item->product->product->hasDeal->discount > 0)
                                                        @if($item->product->product->hasDeal->discount_type == 0)
                                                            <span class="prise_offer">-{{$item->product->product->hasDeal->discount}}%</span>
                                                            <span class="prise">{{single_price($item->product->selling_price)}}</span>
                                                        @else
                                                            <span class="prise_offer">-{{single_price($item->product->product->hasDeal->discount)}}</span>
                                                            <span class="prise">{{single_price($item->product->selling_price)}}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if(@$item->product->product->hasDiscount == 'yes')
                                                        @if($item->product->product->discount_type == 0)
                                                            <span class="prise_offer">-{{$item->product->product->discount}}%</span>
                                                            <span class="prise">{{single_price($item->product->selling_price)}}</span>
                                                        @else
                                                            <span class="prise_offer">-{{single_price($item->product->product->discount)}}</span>
                                                            <span class="prise">{{single_price($item->product->selling_price)}}</span>
                                                        @endif
                                                    @else
                                                        <span class="normal_price">{{single_price($item->product->selling_price)}}</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="quentity">
                                                <span>{{__('common.qty')}}: {{$item->qty}}</span>
                                            </div>
                                            <div class="total_prise d-flex align-items-center">
                                                <span>{{single_price($item->total_price)}}</span>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $actual_price += $item->total_price;
                                            $seller_actual_price += $item->total_price;
                                            $subtotal += $item->giftCard->selling_price * $item->qty;
                                        @endphp
                                        <div class="single_checkout_shiped_product d-flex align-items-center">
                                            <div class="product_information d-flex align-items-center flex-fill">
                                                <div class="thumb">
                                                    <img src="{{showImage($item->giftCard->thumbnail_image)}}" alt="">
                                                </div>
                                                <div class="product_content">
                                                    <p>{{ textLimit(@$item->giftCard->name, 28) }}</p>
                                                </div>
                                            </div>
                                            <div class="offer_prise">
                                                @if($item->giftCard->hasDiscount())
                                                    @if($item->giftCard->discount_type == 0)
                                                        <span class="prise_offer">-{{$item->giftCard->discount}}%</span>
                                                    @else
                                                        <span class="prise_offer">-{{single_price($item->giftCard->discount)}}</span>
                                                    @endif
                                                    <span class="prise">{{single_price($item->giftCard->selling_price)}}</span>
                                                @else
                                                <span class="normal_price">{{single_price($item->giftCard->selling_price)}}</span>
                                                @endif
                                            </div>
                                            <div class="quentity">
                                                <span>{{__('common.qty')}}: {{$item->qty}}</span>
                                            </div>
                                            <div class="total_prise d-flex align-items-center">
                                                <span>{{single_price($item->total_price)}}</span>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach

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
                        @include('frontend.default.partials._cart_shipping_method', ['shipping_methods' => $seller_shippings, 'package'=>$package_wise_shipping[$seller_id],'is_physical_count' => $is_physical_count])
                    @endforeach


                @endif

                <div class="shiping_address_box checkout_form m-0">
                    <div class="billing_address">
                        <h3 class="check_v3_title mb_25">{{__('defaultTheme.contact_information')}}</h3>
                        @if(auth()->check())
                        <div class="Contact_sVendor_box d-flex align-items-center mb_30">
                            <div class="thumb">
                                <img class="img-fluid" src="{{showImage(auth()->user()->avatar?auth()->user()->avatar:'frontend/default/img/avatar.jpg')}}" alt="">
                            </div>
                            <div class="Contact_sVendor_info">
                                <h5>{{textLimit(auth()->user()->first_name.' '.auth()->user()->last_name,28)}} <span>({{auth()->user()->email}})</span> </h5>
                            </div>
                        </div>
                        @else
                        <div class="">
                            <label for="name">{{__('common.email')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                            <input class="form-control" type="email" id="email" placeholder="{{__('common.email')}}"  value="{{$shipping_address?$shipping_address->email:''}}" name="email">
                        </div>
                        @endif
                        <div class="product_ceck mb_20">
                            <ul>
                                <li>
                                    <label class="cs_checkbox">
                                        <input type="checkbox" name="news_letter" value="1" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                    <a href="javascript:void(0)">{{__('defaultTheme.email_me_with_news_and_offers')}}</a>
                                </li>
                            </ul>
                        </div>


                        <div class="shipping_delivery_div">
                            @php
                                $delivery_info = null;
                            @endphp
                            <h3 class="check_v3_title mb_25"><span class="address_title">@if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') {{__('shipping.shipping_address')}} @else {{__('common.billing_address')}} @endif</span> @if($shipping_address) <a href="javascript:void(0)" class="link_btn_design">{{__('common.edit')}}</a> @endif</h3>
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
                                    <a>Home delivery</a>
                                    @if(session()->has('buy_it_now') && @$cartData->where('is_buy_now', 1)->first()->product_type == 'gift_card')
                                    @else
                                        <label class="primary_bulet_checkbox ml-20">
                                            <input type="radio" name="delivery_type" class="payment_method"  value="pickup_location" @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <a>Pickup location</a>

                                        <div class="pick_location_list_div @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery') d-none @endif">
                                            <label>{{__('Pickup location')}} <span class="text-red">*</span></label>
                                            <select class="primary_select nc_select" name="pickup_location" id="pickup_location" autocomplete="off">
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
                                    <div class="form-group">
                                        <label for="name">{{__('defaultTheme.address_list')}} <span class="text-danger">*</span></label>
                                        <select class="form-control nc_select" name="address_id" id="address_id">
                                            <option value="0">{{__('defaultTheme.new_address')}}</option>
                                            @foreach (auth()->user()->customerAddresses as $address)
                                                <option value="{{$address->id}}" @if($shipping_address && $shipping_address->id == $address->id) selected @endif >{{$address->address}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $user_name = auth()->user()->first_name;
                                    $user_email = auth()->user()->email?auth()->user()->email:'';
                                    $user_phone = auth()->user()->phone?auth()->user()->phone:'';
                                @endphp
                            @endif
                            <div class="col-lg-6">
                                <label for="name">{{__('common.name')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                                <input class="form-control" type="text" id="name" name="name"
                                       placeholder="{{__('common.name')}}" value="{{$shipping_address?$shipping_address->name:$user_name}}">
                            </div>
                            <div class="col-lg-6">
                                <label for="address">{{__('common.address')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_address">{{ $errors->first('address') }}</span>
                                <input class="form-control" type="text" id="address" name="address"
                                       placeholder="{{__('common.address')}}" value="{{$shipping_address?$shipping_address->address:''}}">
                            </div>
                            @if(auth()->check())
                                <div class="col-lg-6">
                                    <label for="email">{{__('common.email')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                                    <input class="form-control" type="email" id="email" name="email"
                                           placeholder="{{__('common.email')}}" value="{{$shipping_address?$shipping_address->email:$user_email}}">
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <label for="phone">{{__('common.phone')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_phone">{{ $errors->first('phone') }}</span>
                                <input class="form-control" type="text" id="phone" name="phone"
                                       placeholder="{{__('common.phone')}}" value="{{$shipping_address?$shipping_address->phone:$user_phone}}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{__('common.country')}} <span class="text-red">*</span></label>
                                <select class="primary_select nc_select" name="country" id="country" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $country->id }}" @if($shipping_address && $shipping_address->country == $country->id) selected @elseif(!$shipping_address && app('general_setting')->default_country == $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_country">{{ $errors->first('country') }}</span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{__('common.state')}} <span class="text-red">*</span></label>
                                <select class="primary_select nc_select" name="state" id="state" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @if(app('general_setting')->default_country != null)
                                        @foreach ($states as $state)
                                            <option value="{{$state->id}}" @if($shipping_address && $shipping_address->state == $state->id) selected @elseif(app('general_setting')->default_state == $state->id) selected @endif>{{$state->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger" id="error_state">{{ $errors->first('state') }}</span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{__('common.city')}} <span class="text-red">*</span></label>

                                <select class="primary_select nc_select" name="city" id="city" autocomplete="off">
                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{$city->id}}" @if($shipping_address && $shipping_address->city == $city->id) selected @endif>{{$city->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_city">{{ $errors->first('city') }}</span>
                            </div>
                            <div class="col-lg-6">
                                <label for="postal_code">{{__('common.postal_code_or_pin_code')}} </label> @if($postalCodeRequired) <span class="text-red">*</span> @endif <span class="text-danger" id="error_postal_code">{{ $errors->first('postal_code') }}</span>
                                <input class="form-control" type="text" id="postal_code" name="postal_code" placeholder="{{__('common.postal_code')}}" value="{{$shipping_address?$shipping_address->postal_code:''}}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="note">{{__('common.note')}}</label>
                                    <textarea name="note" id="note" placeholder="{{__('common.note')}}">{{session()->get('order_note')}}</textarea>
                                    <span class="text-danger"  id="error_note"></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="product_ceck">
                                    <ul>
                                        <li>
                                            <label class="cs_checkbox">
                                                <input type="checkbox" value="1" id="term_check" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                            <a href="javascript:void(0)">{{__('defaultTheme.I agree with the terms and conditions')}}.</a>
                                        </li>
                                        <li>
                                            <span class="text-danger" id="error_term_check"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="check_v3_btns flex-wrap d-flex align-items-center">
                                    @if(isModuleActive('MultiVendor'))
                                        <input type="hidden" name="step" value="select_payment">
                                        <button type="submit" class="btn_1 m-0 text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
                                    @else
                                        <div id="next_step_btn_div">
                                            @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery')
                                                <input type="hidden" name="step" value="select_shipping">
                                                <button type="submit" class="btn_1 m-0 text-uppercase ">{{__('defaultTheme.continue_to_shipping')}}</button>
                                            @else
                                                <input type="hidden" name="step" value="select_payment">
                                                <input type="hidden" name="shipping_method" value="{{encrypt($free_shipping_for_pickup_location->id)}}">
                                                <button type="submit" class="btn_1 m-0 text-uppercase ">{{__('defaultTheme.continue_to_payment')}}</button>
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
                          <div class="singleVendor_product_list d-flex align-items-center">
                              <div class="thumb single_thumb">
                                  <img src="
                                    @if($cart->product->product->product->product_type == 1)
                                      {{showImage($cart->product->product->product->thumbnail_image_source)}}
                                    @else
                                      {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                    @endif
                                  " alt="">
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
                                    $subtotal += $cart->product->sku->selling_price * $cart->qty;
                                }
                            }else{
                                $subtotal += $cart->product->sku->selling_price * $cart->qty;
                            }
                        @endphp
                    @else
                        <div class="singleVendor_product_lists">
                            <div class="singleVendor_product_list d-flex align-items-center">
                                <div class="thumb single_thumb">
                                    <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="">
                                </div>
                                <div class="product_list_content">
                                    <h4><a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">{{ textLimit(@$cart->giftCard->name, 28) }}</a></h4>
                                    <h5 class="d-flex align-items-center"><span class="product_count_text" >{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
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
                              <p>{{ __('defaultTheme.package_wise_shipping_charge') }}</p>
                            @endif
                        </div>
                        <div class="single_total_right">
                            <span>
                              @if(isModuleActive('MultiVendor'))
                                + {{single_price($total_shipping_charge)}}
                              @else
                              {{__('defaultTheme.calculated_at_next_step')}}
                              @endif
                            </span>
                        </div>
                    </div>
                        @php
                            if(isModuleActive('MultiVendor')){
                                $total = $actual_price + $total_shipping_charge;
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

                    <div class="total_amount d-flex align-items-center flex-wrap">
                        <div class="single_total_left flex-fill">
                            <span class="total_text">{{__('common.total')}}</span>
                        </div>
                        <div class="single_total_right">
                            <span class="total_text"><span>{{single_price($total)}}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
