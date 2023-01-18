@php
    $currency_code = getCurrencyCode();
@endphp
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
                                    <h5 class="m-0 flex-fill">
                                        @if(auth()->check())
                                            {{auth()->user()->email != null?auth()->user()->email : auth()->user()->phone}}
                                        @else
                                            {{$address->email}}
                                        @endif</h5>
                                    <a href="{{url('/checkout')}}" class="edit_info_text">{{__('common.change')}}</a>
                                </div>
                                @php
                                    $delivery_info = null;
                                    if(session()->has('delivery_info')){
                                        $delivery_info = session()->get('delivery_info');
                                    }
                                @endphp
                                @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery')
                                    <div class="single_shipingV3_info d-flex align-items-start">
                                        <span>{{__('defaultTheme.ship_to')}}</span>
                                        <h5 class="m-0 flex-fill">{{@$address->address}}</h5>
                                        <a href="{{url('/checkout')}}" class="edit_info_text">{{__('common.change')}}</a>
                                    </div>
                                @else
                                    <div class="single_shipingV3_info d-flex align-items-start">
                                        <span>{{__('common.billing_address')}}</span>
                                        <h5 class="m-0 flex-fill">{{$address->address}}</h5>
                                        <a href="{{url('/checkout')}}" class="edit_info_text">{{__('common.change')}}</a>
                                    </div>
                                @endif
                                @if(!isModuleActive('MultiVendor'))
                                    @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery')
                                        <div class="single_shipingV3_info d-flex align-items-start">
                                            <span>{{__('common.method')}}</span>
                                            @if(isModuleActive('INTShipping'))
                                            <h5 class="m-0 flex-fill">{{__('Product wise Shipping')}} - {{single_price($shipping_cost)}}</h5>
                                            <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                            @else
                                            <h5 class="m-0 flex-fill">{{$selected_shipping_method->method_name}} - {{single_price($shipping_cost)}}</h5>
                                            <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                            @endif
                                        </div>
                                    @else
                                        <div class="single_shipingV3_info d-flex align-items-start">
                                            <span>{{__('common.method')}}</span>
                                            <h5 class="m-0 flex-fill">{{__('shipping.collect_from_pickup_location')}} - {{single_price(0)}}</h5>
                                            <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mb_10">
                            <h3 class="check_v3_title2">{{__('common.payment')}}</h3>
                            <h6 class="shekout_subTitle_text">{{__('defaultTheme.all_transactions_are_secure_and_encrypted')}}.</h6>
                        </div>
                        <div class="col-12">
                            <div class="accordion checkout_acc_style mb_30" id="accordionExample">
                                @php
                                    if(isset($coupon_amount)){
                                        $coupon_am = $coupon_amount;
                                    }else{
                                        $coupon_am = 0;
                                    }
                                @endphp
                                @foreach($gateway_activations as $key => $payment)
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="headingOne">
                                            <span class="accordion-button shadow-none" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}"  aria-controls="collapse{{$key}}">
                                                <span>
                                                    <label class="primary_checkbox d-inline-flex style4 gap_10" >
                                                        <input type="radio" name="payment_method" class="payment_method" data-name="{{$payment->method}}" data-id="{{encrypt($payment->id)}}" value="{{$payment->id}}" {{$key == 0?'checked':''}}>
                                                        <span class="checkmark mr_10"></span>
                                                        <span class="label_name f_w_500 ">{{$payment->method}}</span>
                                                    </label>
                                                </span>
                                            </span>
                                        </div>
                                        <div id="collapse{{$key}}" class="accordion-collapse collapse {{$key == 0?'show':''}}" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body" id="acc_{{$payment->id}}">
                                                <!-- content ::start  -->
                                                <div class="row">
                                                    @if($payment->method == 'Cash On Delivery')

                                                    @elseif($payment->method == 'Wallet')
                                                        <div class="col-lg-12 text-center mb_20">
                                                            <strong>{{__('common.balance')}}: {{single_price(auth()->user()->CustomerCurrentWalletAmounts)}}</strong>
                                                        </div>
                                                    @elseif($payment->method == 'Stripe')
                                                        @include('frontend.amazy.partials.payments.stripe_payment')
                                                    @elseif($payment->method == 'PayPal')
                                                        @include('frontend.amazy.partials.payments.payment_paypal')
                                                    @elseif($payment->method == 'PayStack')
                                                        @include('frontend.amazy.partials.payments.paystack_payment')
                                                    @elseif($payment->method == 'RazorPay')
                                                        @include('frontend.amazy.partials.payments.razor_payment')
                                                    @elseif($payment->method == 'Instamojo')
                                                        @include('frontend.amazy.partials.payments.instamojo_payment')
                                                    @elseif($payment->method == 'PayTM')
                                                        @include('frontend.amazy.partials.payments.paytm_payment')
                                                    @elseif($payment->method == 'Midtrans')
                                                        @include('frontend.amazy.partials.payments.midtrans_payment')
                                                    @elseif($payment->method == 'PayUMoney')
                                                        @include('frontend.amazy.partials.payments.payumoney_payment')
                                                    @elseif($payment->method == 'JazzCash')
                                                        @include('frontend.amazy.partials.payments.jazzcash_payment_modal')
                                                    @elseif($payment->method == 'Google Pay')
                                                        <a class="btn_1 pointer d-none" id="buyButton">{{ __('wallet.continue_to_pay') }}</a>
                                                        @push('wallet_scripts')
                                                            @include('frontend.amazy.partials.payments.google_pay_script')
                                                        @endpush
                                                    @elseif($payment->method == 'FlutterWave')
                                                        @include('frontend.amazy.partials.payments.flutter_payment')
                                                    @elseif($payment->method == 'Bank Payment')
                                                        @include('frontend.amazy.partials.payments.bank_payment')
                                                    @elseif(isModuleActive('Bkash') && $payment->method=="Bkash")
                                                        @include('bkash::partials._checkout')
                                                    @elseif(isModuleActive('MercadoPago') && $payment->method=="Mercado Pago")
                                                        @include('mercadopago::partials._checkout_amazy')
                                                    @elseif(isModuleActive('SslCommerz') && $payment->method=="SslCommerz")
                                                        @include('sslcommerz::partials._checkout')
                                                    @endif

                                                </div>
                                                <!-- content ::end  -->
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @php
                            $delivery_info = null;
                            if(session()->has('delivery_info')){
                                $delivery_info = session()->get('delivery_info');
                            }
                        @endphp
                        <div class="col-12 mb_10 @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') d-none @endif">
                            <h3 class="check_v3_title2">{{__('common.billing_address')}}</h3>
                        </div>
                        <div class="col-12 @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') d-none @endif">
                            <div class="accordion checkout_acc_style style2 mb_30" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo33">
                                    <span class="accordion-button shadow-none collapsed" data-bs-target="#collapseTwo33" data-bs-toggle="collapse" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo33">
                                        <label class="primary_checkbox d-inline-flex style4 gap_10">
                                            <input type="radio" name="is_same_billing" value="1" {{$billing_address?'':'checked'}}>
                                            <span class="checkmark mr_10"></span>
                                            <span class="label_name f_w_500 ">{{__('defaultTheme.same_as_shipping_address')}}</span>
                                        </label>
                                    </span>
                                    </h2>
                                    <div id="collapseTwo33" class="accordion-collapse collapse" aria-labelledby="headingTwo33" data-bs-parent="#accordionExample1">
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo44">
                                    <span class="accordion-button shadow-none collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseTwo44" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo44">
                                        <label class="primary_checkbox d-inline-flex style4 gap_10">
                                            <input type="radio" name="is_same_billing" value="0" {{$billing_address?'checked':''}}>
                                            <span class="checkmark mr_10"></span>
                                            <span class="label_name f_w_500 ">
                                            {{__('defaultTheme.use_a_different_billing_address')}}
                                            </span>
                                        </label>

                                    </span>
                                    </h2>
                                    <div id="collapseTwo44" class="accordion-collapse collapse" aria-labelledby="headingTwo44" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body">
                                            <!-- content ::start  -->
                                            <div class="row">
                                                @if(auth()->check())
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="name" class="primary_label2 style2">{{__('defaultTheme.address_list')}} <span class="text-danger">*</span></label>
                                                            <select class="theme_select style2 wide mb_20" name="address_id" id="address_id">
                                                            <option value="0">{{__('defaultTheme.new_address')}}</option>
                                                                @foreach (auth()->user()->customerAddresses->where('is_shipping_default',0) as $addresss)
                                                                    <option value="{{$addresss->id}}" @if(isset($billing_address) && $billing_address->id == $addresss->id) selected @endif >{{$addresss->address}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @else
                                                    <input type="hidden" id="address_id" value="0" name="address_id">
                                                @endif
                                                <div class="col-lg-6 mb_20">
                                                    <label class="primary_label2 style3">{{__('common.name')}} <span>*</span></label>
                                                    <input class="primary_input3 style5 radius_3px" id="name" name="name" value="{{isset($billing_address)?$billing_address->name:''}}" type="text"  placeholder="{{__('common.name')}}">
                                                    <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                                                </div>
                                                <div class="col-lg-6 mb_20">
                                                    <label for="address" class="primary_label2 style3">{{__('common.address')}} <span>*</span></label>
                                                    <input class="primary_input3 style5 radius_3px" type="text" id="address" name="address"
                                                        placeholder="{{__('common.address')}}" value="{{isset($billing_address)?$billing_address->address:''}}">
                                                    <span class="text-danger" id="error_address">{{ $errors->first('address') }}</span>
                                                </div>
                                                <div class="col-lg-6 mb_20">
                                                    <label class="primary_label2 style3" for="email">{{__('common.email')}} <span>*</span></label>
                                                    <input class="primary_input3 style5 radius_3px" type="email" name="email" id="email" placeholder="{{__('common.email')}}" value="{{isset($billing_address)?$billing_address->email:''}}">
                                                    <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                                                </div>
                                                <div class="col-lg-6 mb_20">
                                                    <label class="primary_label2 style3" for="phone">{{__('common.phone')}} <span>*</span></label>
                                                    <input class="primary_input3 style5 radius_3px" type="text" name="phone" value="{{isset($billing_address)?$billing_address->phone:''}}" id="phone" placeholder="{{__('common.phone')}}">
                                                    <span class="text-danger" id="error_phone">{{ $errors->first('phone') }}</span>
                                                </div>
                                                <div class="col-lg-6 mb_20">
                                                    <label class="primary_label2 style3">{{__('common.country')}} <span>*</span></label>
                                                    <select class="theme_select style2 wide" name="country" id="country" autocomplete="off">
                                                        <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                        @foreach ($countries as $key => $country)
                                                            <option value="{{ $country->id }}" @if(isset($billing_address) && $billing_address->country == $country->id) selected @elseif(!isset($billing_address) && app('general_setting')->default_country == $country->id) selected @endif>{{ $country->name }}</option>
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
                                                                <option value="{{$state->id}}" @if(isset($billing_address) && $billing_address->state == $state->id) selected @elseif(app('general_setting')->default_state == $state->id) selected @endif>{{$state->name}}</option>
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
                                                            <option value="{{$city->id}}" @if(isset($billing_address) && $billing_address->city == $city->id) selected @endif>{{$city->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="error_city">{{ $errors->first('city') }}</span>
                                                </div>
                                                <div class="col-lg-6 mb_20">
                                                    <label for="postal_code" class="primary_label2 style3">{{__('common.postal_code')}} @if(isModuleActive('ShipRocket')) <span>*</span>@endif</label>
                                                    <input class="primary_input3 style5 radius_3px" type="text" id="postal_code" name="postal_code" placeholder="{{__('common.postal_code')}}" value="{{isset($billing_address)?$billing_address->postal_code:''}}">
                                                    <span class="text-danger" id="error_postal_code"></span>
                                                </div>
                                                <input type="hidden" id="token" value="{{csrf_token()}}">
                                            </div>
                                            <!-- content ::end  -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="check_v3_btns flex-wrap d-flex align-items-center">
                                <div id="btn_div">
                                    @php
                                        $payment_id = encrypt(0);
                                        $url = '';
                                        if(count($gateway_activations) > 0 && $gateway_activations[0]->id == 1 || count($gateway_activations) > 0 && $gateway_activations[0]->id == 2){
                                            $gateway_id = (count($gateway_activations) > 0)?encrypt($gateway_activations[0]->id):0;
                                            if($gateway_activations[0]->id == 1){
                                                $url = url('/checkout?').'gateway_id='.$gateway_id.'&payment_id='.$payment_id.'&step=complete_order';
                                                $pay_now_btn = '<a href="javascript:void(0)" data-url="'.$url.'" data-type="CashOnDelivery" id="payment_btn_trigger" class="amaz_primary_btn style2  min_200 text-center text-uppercase">Pay Now</a>';
                                            }elseif($gateway_activations[0]->id == 2){
                                                $url = url('/checkout?').'gateway_id='.$gateway_id.'&payment_id='.$payment_id.'&step=complete_order';
                                                $pay_now_btn = '<a href="javascript:void(0)" data-url="'.$url.'" data-type="Wallet" id="payment_btn_trigger" class="amaz_primary_btn style2  min_200 text-center text-uppercase">Pay Now</a>';
                                            }
                                        }else {
                                            $method = '';
                                            if(count($gateway_activations) > 0){
                                                $method = $gateway_activations[0]->method;
                                            }
                                            $pay_now_btn = '<a href="javascript:void(0)" id="payment_btn_trigger" data-type="'.$method.'" class="amaz_primary_btn style2  min_200 text-center text-uppercase">Pay Now</a>';
                                        }
                                    @endphp
                                    {!! $pay_now_btn !!}
                                </div>
                                <input type="hidden" value="{{encrypt(0)}}" id="off_payment_id">
                                @if(isModuleActive('MultiVendor'))
                                    <a href="{{url()->previous()}}" class="return_text">{{__('defaultTheme.return_to_information')}}</a>
                                @else
                                    @if(!$delivery_info || $delivery_info && $delivery_info['delivery_type'] == 'home_delivery')
                                        <a href="{{url()->previous()}}" class="return_text">{{__('defaultTheme.return_to_shipping')}}</a>
                                    @else
                                        <a href="{{url()->previous()}}" class="return_text">{{__('defaultTheme.return_to_information')}}</a>
                                    @endif
                                @endif
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
                    $actual_total = 0;
                    $subtotal = 0;
                    $additional_shipping = 0;
                    $tax = 0;
                    $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                    $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                    $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
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
                                    " alt="{{@$cart->product->product->product_name}}" title="{{@$cart->product->product->product_name}}">
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
                            if (isModuleActive('WholeSale')){
                                $w_main_price = 0;
                                $wholeSalePrices = $cart->product->wholeSalePrices;
                                foreach ($wholeSalePrices as $w_p){
                                    if ( ($w_p->min_qty<=$cart->qty) && ($w_p->max_qty >=$cart->qty) ){
                                        $w_main_price = $w_p->selling_price;
                                    }
                                    elseif($w_p->max_qty < $cart->qty){
                                        $w_main_price = $w_p->selling_price;
                                    }
                                }

                                if ($w_main_price!=0){
                                    $subtotal += $w_main_price * $cart->qty;
                                }else{
                                    $subtotal += $cart->total_price;
                                }
                            }else{
                                $subtotal += $cart->total_price;
                            }
                            $additional_shipping += $cart->product->sku->additional_shipping;
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
                                    <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$cart->giftCard->name, 28) }}" title="{{ textLimit(@$cart->giftCard->name, 28) }}">
                                </div>
                                <div class="product_list_content">
                                    <h4><a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">{{ textLimit(@$cart->giftCard->name, 28) }}</a></h4>
                                    <h5 class="d-flex align-items-center"><span class="product_count_text" >{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                </div>
                            </div>
                        </div>
                        @php
                            $subtotal += $cart->total_price;
                        @endphp
                    @endif
                    @php
                        $actual_total += $cart->total_price;
                    @endphp
                @endforeach

                @php
                    $discount = $subtotal - $actual_total;
                    $total = $subtotal + $tax + $shipping_cost - $discount;
                @endphp
            @endif
            <h3 class="check_v3_title mb_25">{{__('common.order_summary')}}</h3>
            @if(isModuleActive('MultiVendor'))
                @php
                    $total = $total_amount;
                @endphp
            @endif
            <div class="subtotal_lists">
                <div class="single_total_list d-flex align-items-center">
                    <div class="single_total_left flex-fill">
                        <h4>{{ __('common.subtotal') }}</h4>
                    </div>
                    <div class="single_total_right">
                        {{-- <span>+ {{single_price($subtotal)}}</span> --}}
                        <span>+ {{single_price($subtotal_without_discount)}}</span>
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
                        <span>+ {{single_price(collect($shipping_cost)->sum())}}</span>
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
                        <span>+ {{single_price($tax_total)}}</span>
                    </div>
                </div>
                @php
                    $coupon = 0;
                    $coupon_id = null;
                    $total_for_coupon = $actual_total;
                @endphp
                @auth
                    @php
                        if(\Session::has('coupon_type')&&\Session::has('coupon_discount')){
                            $coupon_type = \Session::get('coupon_type');
                            $coupon_discount = \Session::get('coupon_discount');
                            $coupon_discount_type = \Session::get('coupon_discount_type');
                            $coupon_id = \Session::get('coupon_id');

                            if($coupon_type == 1){
                                $couponProducts = \Session::get('coupon_products');
                                if($coupon_discount_type == 0){
                                    foreach($couponProducts as  $key => $item){
                                        $cart = \App\Models\Cart::where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product',function($query) use($item){
                                            $query->whereHas('product', function($q) use($item){
                                                $q->where('id', $item);
                                            });
                                        })->first();
                                        $coupon += ($cart->total_price/100)* $coupon_discount;
                                    }
                                }else{
                                    if($total_for_coupon > $coupon_discount){
                                        $coupon = $coupon_discount;
                                    }else {
                                        $coupon = $total_for_coupon;
                                    }
                                }

                            }
                            elseif($coupon_type == 2){

                                if($coupon_discount_type == 0){

                                    $maximum_discount = \Session::get('maximum_discount');
                                    $coupon = ($total_for_coupon/100)* $coupon_discount;

                                    if($coupon > $maximum_discount && $maximum_discount > 0){
                                        $coupon = $maximum_discount;
                                    }
                                }else{
                                    $coupon = $coupon_discount;
                                }
                            }
                            elseif($coupon_type == 3){
                                $maximum_discount = \Session::get('maximum_discount');
                                $coupon = $shipping_cost;

                                if($coupon > $maximum_discount && $maximum_discount > 0){
                                    $coupon = $maximum_discount;
                                }

                            }

                        }
                    @endphp
                    @if(\Session::has('coupon_type')&&\Session::has('coupon_discount'))
                        <div class="single_total_list d-flex align-items-center flex-wrap">
                            <div class="single_total_left flex-fill">
                                <h4>{{__('common.coupon')}} {{__('common.discount')}}</h4>
                            </div>
                            <div class="single_total_right">
                                <span>- {{single_price($coupon)}}</span>
                            </div>
                        </div>
                        <div class="coupon_verify_information mb_20 d-flex align-items-center flex-wrap ">
                            <div class="icon">
                                <img src="{{url('/')}}/public/frontend/amazy/img/cart/verified.svg" alt="{{__('common.valid_coupon_code')}}" title="{{__('common.valid_coupon_code')}}">
                            </div>
                            <div class="coupon_content">
                                <h4 class="font_14 f_w_700 lh-1">{{__('common.valid_coupon_code')}}</h4>
                                <a class="remove_coupon text-uppercase text-decoration-underline cursor_pointer" id="coupon_delete">{{__('common.remove_code')}}</a>
                            </div>
                        </div>
                    @else
                        <div class="coupon_wrapper pb_25 couponCodeDiv">
                            <input placeholder="{{__('common.coupon')}} {{__('common.code')}}" id="coupon_code" class="primary_input5 " onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.coupon')}} {{__('common.code')}}'" type="text">
                            <button type="button" class="amaz_primary_btn style4 min_100 text-uppercase text-center coupon_apply_btn" data-total="{{$actual_total}}">{{__('common.apply')}}</button>
                        </div>
                    @endif
                    @isset($coupon_amount)
                        @php
                            $total = $total - $coupon_amount;
                        @endphp
                    @endisset
                @endauth
                <div class="total_amount d-flex align-items-center flex-wrap">
                    <div class="single_total_left flex-fill">
                        <span class="total_text">{{__('common.total')}} (Incl. {{__('common.vat/tax/gst')}})</span>
                    </div>
                    <div class="single_total_right">
                        <span class="total_text">{{single_price($total)}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
