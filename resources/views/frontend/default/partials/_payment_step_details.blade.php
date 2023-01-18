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
                                        @endif
                                    </h5>
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
                                        <h5 class="m-0 flex-fill">{{$address->address}}</h5>
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
                                            <h5 class="m-0 flex-fill">{{$selected_shipping_method->method_name}} - {{single_price($shipping_cost)}}</h5>
                                            <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                        </div>
                                    @else
                                        <div class="single_shipingV3_info d-flex align-items-start">
                                            <span>{{__('common.method')}}</span>
                                            <h5 class="m-0 flex-fill">Collect from pickup location - {{single_price(0)}}</h5>
                                            <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mb_10">
                            <h3 class="check_v3_title2 mb-2 ">{{__('common.payment')}}</h3>
                            <h6 class="shekout_subTitle_text">{{__('defaultTheme.all_transactions_are_secure_and_encrypted')}}.</h6>
                        </div>
                        <div class="col-12">
                            <div id="accordion" class="checkout_acc_style1 mb_30" >
                                @php
                                    if(isset($coupon_amount)){
                                        $coupon_am = $coupon_amount;
                                    }else{
                                        $coupon_am = 0;
                                    }
                                @endphp
                                @foreach($gateway_activations as $key => $payment)

                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                    <label class="primary_bulet_checkbox">
                                                        <input type="radio" name="payment_method" class="payment_method" data-name="{{$payment->method}}" data-id="{{encrypt($payment->id)}}" value="{{$payment->id}}" {{$key == 0?'checked':''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <span>{{$payment->method}}</span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapse{{$key}}" class="collapse {{$key == 0?'show':''}}" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    @if($payment->method == 'Cash On Delivery')
                                                        <div class="col-lg-12 text-center mt_5 mb_25">
                                                            <span></span>
                                                        </div>
                                                    @elseif($payment->method == 'Wallet')
                                                        <div class="col-lg-12 text-center mt_5 mb_25">
                                                            <strong>{{__('common.balance')}}: {{single_price(auth()->user()->CustomerCurrentWalletAmounts)}}</strong>
                                                            <br>
                                                            <span></span>
                                                        </div>
                                                    @elseif($payment->method == 'Stripe')
                                                        @include('frontend.default.partials.payments.stripe_payment')
                                                    @elseif(isModuleActive('Bkash') && $payment->method=="Bkash")
                                                        @include('bkash::partials._checkout')

                                                    @elseif(isModuleActive('MercadoPago') && $payment->method=="Mercado Pago")
                                                        @include('mercadopago::partials._checkout')

                                                    @elseif(isModuleActive('SslCommerz') && $payment->method=="SslCommerz")
                                                        @include('sslcommerz::partials._checkout')

                                                    @elseif($payment->method == 'PayPal')
                                                        @include('frontend.default.partials.payments.payment_paypal')
                                                    @elseif($payment->method == 'PayStack')
                                                        @include('frontend.default.partials.payments.paystack_payment')
                                                    @elseif($payment->method == 'RazorPay')
                                                        @include('frontend.default.partials.payments.razor_payment')
                                                    @elseif($payment->method == 'Instamojo')
                                                        @include('frontend.default.partials.payments.instamojo_payment')
                                                    @elseif($payment->method == 'PayTM')
                                                        @include('frontend.default.partials.payments.paytm_payment')
                                                    @elseif($payment->method == 'Midtrans')
                                                        @include('frontend.default.partials.payments.midtrans_payment')
                                                    @elseif($payment->method == 'PayUMoney')
                                                        @include('frontend.default.partials.payments.payumoney_payment')
                                                    @elseif($payment->method == 'JazzCash')
                                                        @include('frontend.default.partials.payments.jazzcash_payment_modal')
                                                    @elseif($payment->method == 'Google Pay')
                                                        <a class="btn_1 pointer d-none" id="buyButton">{{ __('wallet.continue_to_pay') }}</a>
                                                        @push('wallet_scripts')
                                                            @include('frontend.default.partials.payments.google_pay_script')
                                                        @endpush
                                                    @elseif($payment->method == 'FlutterWave')
                                                        @include('frontend.default.partials.payments.flutter_payment')
                                                    @elseif($payment->method == 'Bank Payment')
                                                        @include('frontend.default.partials.payments.bank_payment')
                                                    @endif

                                                </div>
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
                        <div class="col-lg-12">

                            <div class="row">
                                <div class="col-12 mb_10 @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') d-none @endif">
                                    <h3 class="check_v3_title2 mb-2 ">{{__('common.billing_address')}}</h3>
                                </div>
                                <div class="col-12 @if($delivery_info && $delivery_info['delivery_type'] == 'pickup_location') d-none @endif">
                                    <div id="accordion2" class="checkout_acc_style1 style2 mb_30" >
                                        <div class="card">
                                            <div class="card-header" id="headingOne1">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link"  type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo2">
                                                        <label class="primary_bulet_checkbox">
                                                            <input type="radio" name="is_same_billing" value="1" {{$billing_address?'':'checked'}}>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <span>{{__('defaultTheme.same_as_shipping_address')}}</span>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo2" data-parent="#accordion2">
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo1">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo1" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo" type="button">
                                                    <label class="primary_bulet_checkbox">
                                                        <input type="radio" name="is_same_billing" value="0" {{$billing_address?'checked':''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <span>{{__('defaultTheme.use_a_different_billing_address')}}</span>
                                                </button>
                                            </h5>
                                            </div>
                                            <div id="collapseTwo1" class="collapse {{$billing_address?'show':''}}" aria-labelledby="headingTwo1" data-parent="#accordion2">
                                                <div class="card-body">
                                                    <div class="row">

                                                        @if(auth()->check())
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="name">{{__('defaultTheme.address_list')}} <span class="text-danger">*</span></label>
                                                                    <select class="form-control nc_select" name="address_id" id="address_id">
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
                                                        <div class="col-lg-6">
                                                            <label for="name">{{__('common.name')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                                                            <input class="form-control" type="text" id="name" name="name"
                                                                placeholder="{{__('common.name')}}" value="{{isset($billing_address)?$billing_address->name:''}}">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="name">{{__('common.address')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_address">{{ $errors->first('address') }}</span>
                                                            <input class="form-control" type="text" id="address" name="address"
                                                                placeholder="{{__('common.address')}}" value="{{isset($billing_address)?$billing_address->address:''}}">
                                                        </div>
                                                        <div class="col-lg-6">
                                                        <label for="name">{{__('common.email')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                                                        <input class="form-control" type="text" id="email" name="email"
                                                            placeholder="{{__('common.email')}}" value="{{isset($billing_address)?$billing_address->email:''}}">
                                                        </div>
                                                        <div class="col-lg-6">
                                                        <label for="name">{{__('common.phone')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_phone">{{ $errors->first('phone') }}</span>
                                                        <input class="form-control" type="text" id="phone" name="phone"
                                                            placeholder="{{__('common.phone')}}" value="{{isset($billing_address)?$billing_address->phone:''}}">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                        <label>{{__('common.country')}} <span class="text-red">*</span></label>
                                                        <select class="primary_select nc_select" name="country" id="country" autocomplete="off">
                                                            <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                            @foreach ($countries as $key => $country)
                                                                <option value="{{ $country->id }}" @if(isset($billing_address) && $billing_address->country == $country->id) selected @elseif(!isset($billing_address) && app('general_setting')->default_country == $country->id) selected @endif>{{ $country->name }}</option>
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
                                                                    <option value="{{$state->id}}" @if(isset($billing_address) && $billing_address->state == $state->id) selected @elseif(app('general_setting')->default_state == $state->id) selected @endif>{{$state->name}}</option>
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
                                                                <option value="{{$city->id}}" @if(isset($billing_address) && $billing_address->city == $city->id) selected @endif>{{$city->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger" id="error_city">{{ $errors->first('city') }}</span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="postal_code">{{__('common.postal_code')}} @if(isModuleActive('ShipRocket')) <span class="text-red">*</span>@endif</label> <span class="text-danger" id="error_postal_code"></span>
                                                            <input class="form-control" type="text" id="postal_code" name="postal_code" placeholder="{{__('common.postal_code')}}" value="{{isset($billing_address)?$billing_address->postal_code:''}}">
                                                        </div>
                                                        <input type="hidden" id="token" value="{{csrf_token()}}">
                                                    </div>
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
                                                            $pay_now_btn = '<a href="javascript:void(0)" data-url="'.$url.'" data-type="CashOnDelivery" id="payment_btn_trigger" class="btn_1 m-0 text-uppercase">Pay now</a>';
                                                        }elseif($gateway_activations[0]->id == 2){
                                                            $url = url('/checkout?').'gateway_id='.$gateway_id.'&payment_id='.$payment_id.'&step=complete_order';
                                                            $pay_now_btn = '<a href="javascript:void(0)" data-url="'.$url.'" data-type="Wallet" id="payment_btn_trigger" class="btn_1 m-0 text-uppercase">Pay now</a>';
                                                        }
                                                    }else {
                                                        $method = '';
                                                        if(count($gateway_activations) > 0){
                                                            $method = $gateway_activations[0]->method;
                                                        }
                                                        $pay_now_btn = '<a href="javascript:void(0)" id="payment_btn_trigger" data-type="'.$method.'" class="btn_1 m-0 text-uppercase">Pay now</a>';
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
                                    " alt="">
                                </div>
                                <div class="product_list_content">
                                    <h4><a href="{{singleProductURL($cart->product->product->seller->slug, $cart->product->product->slug)}}">{{ textLimit(@$cart->product->product->product_name, 28) }}</a></h4>
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
                                                $gstAmount = $cart->total_price * $sameStateTax / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($sameStateTaxes as $key => $sameStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $sameStateTax->tax_percentage / 100;
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
                                                $gstAmount = $cart->total_price * $diffStateTax / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($diffStateTaxes as $key => $diffStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $diffStateTax->tax_percentage / 100;
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
                                    <img src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="">
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
                        <h4 >{{ __('common.subtotal') }}</h4>
                    </div>
                    <div class="single_total_right">
                        <span>+ {{single_price($subtotal_without_discount)}}</span>
                    </div>
                </div>
                <div class="single_total_list d-flex align-items-center flex-wrap">
                    <div class="single_total_left flex-fill">
                        <h4>{{__('common.shipping_charge')}}</h4>
                        <p>{{ __('defaultTheme.package_wise_shipping_charge') }}</p>
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
                    <div class="single_total_list d-flex align-items-center flex-wrap">
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
                            <div class="single_total_left flex-fill">
                                <h4>{{__('common.coupon')}} {{__('common.discount')}}</h4>
                            </div>
                            <div class="single_total_left flex-fill">
                                <strong id="coupon_delete" class="text-red cursor_pointer">X</strong>
                            </div>
                            <div class="single_total_right">
                                <span>- {{single_price($coupon)}}</span>
                            </div>
                        @else
                            <div class="input-group couponCodeDiv">
                                <input type="text" class="form-control" id="coupon_code" placeholder="{{__('common.coupon')}} {{__('common.code')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Coupon code'">
                                <div class="input-group-append">
                                <div class="input-group-text input_group_text coupon_apply_btn cursor_pointer" data-total="{{$actual_total}}">{{__('common.apply')}}</div>
                                </div>
                            </div>
                        @endif
                    </div>
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
                        <span class="total_text"> <span>{{single_price($total)}}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
