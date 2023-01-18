<div class="col-md-12 mb-20">
    <div class="box_header_right">
        <div class=" float-none pos_tab_btn justify-content-start">
            @php
                $key = 0;
            @endphp
            <ul class="nav nav_list" role="tablist">
                @foreach(@$gateway_activations as $gateway)
                    @if($gateway->method->method == 'Wallet' || $gateway->method->method == 'Cash On Delivery')
                        @php
                            $key = 0;
                        @endphp
                        @continue
                    @endif

                    @if($gateway->method->method == 'PayPal' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#paypalTab" role="tab"
                                data-toggle="tab" id="1" aria-selected="true">{{__('payment_gatways.paypal')}}</a>
                        </li>
                    @elseif($gateway->method->method == 'Stripe' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#stripeTab" role="tab" data-toggle="tab" id="1"
                                aria-selected="true">{{__('payment_gatways.stripe')}}</a>
                        </li>
                    @elseif($gateway->method->method == 'PayStack' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#paystackTab" role="tab" data-toggle="tab" id="1"
                                aria-selected="true">{{__('payment_gatways.paystack')}}</a>
                        </li>
                    @elseif($gateway->method->method == 'RazorPay' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#razorpayTab" role="tab" data-toggle="tab" id="1"
                                aria-selected="true">{{__('payment_gatways.razorpay')}}</a>
                        </li>
                    @elseif($gateway->method->method == 'PayTM' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#paytmTab" role="tab" data-toggle="tab" id="1"
                                aria-selected="true">{{__('payment_gatways.paytm')}}</a>
                        </li>
                    @elseif($gateway->method->method == 'Instamojo' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#instamojoTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.instamojo')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'Midtrans' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#midtransTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.midtrans')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'PayUMoney' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#payumoneyTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.payumoney')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'JazzCash' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#jazzcashTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.jazzcash')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'Google Pay' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#google_payTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.google_pay')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'FlutterWave' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#flutterWaveTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.flutter_wave_payment')}}</a>
                    </li>
                    @elseif($gateway->method->method == 'Bank Payment' && @$gateway->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if($key == 0) active show @endif" href="#bankTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.bank_payment')}}</a>
                    </li>

                    @elseif(isModuleActive('Bkash') && $gateway->method->method == 'Bkash' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#bkashTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.bkash')}}</a>
                        </li>

                    @elseif(isModuleActive('SslCommerz') && $gateway->method->method == 'SslCommerz' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#SslCommerzTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.ssl_commerz')}}</a>
                        </li>

                    @elseif(isModuleActive('MercadoPago') && $gateway->method->method == 'Mercado Pago' && @$gateway->status == 1)
                        <li class="nav-item mb-2">
                            <a class="nav-link @if($key == 0) active show @endif" href="#MercadoPagoTab" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('payment_gatways.mercado_pago')}}</a>
                        </li>
                    @endif
                    @php
                        $key ++;
                    @endphp
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="white_box_30px mb_30">
        <div class="tab-content">

            @php
                $key = 0;
            @endphp
            @foreach(@$gateway_activations as $gateway)
                @if($gateway->method->method == 'Wallet' || $gateway->method->method == 'Cash On Delivery')
                    @php
                        $key = 0;
                    @endphp
                    @continue
                @endif
                @if($gateway->method->method == 'PayPal' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="paypalTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('payment_gatways.paypal_configuration') }}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>

                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.paypal_config')

                    </div>

                @elseif($gateway->method->method == 'Stripe' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="stripeTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.stripe_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.stripe_config')
                    </div>
                @elseif($gateway->method->method == 'PayStack' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="paystackTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.paystack_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.paystack_config')
                    </div>
                @elseif($gateway->method->method == 'RazorPay' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="razorpayTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.razorpay_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.razorpay_config')
                    </div>
                @elseif($gateway->method->method == 'PayTM' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="paytmTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.paytm_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.paytm_config')
                    </div>
                @elseif($gateway->method->method == 'Instamojo' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="instamojoTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.instamojo_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.instamojo_config')
                    </div>
                @elseif($gateway->method->method == 'Midtrans' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="midtransTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.midtrans_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.midtrans_configuration')
                    </div>
                @elseif($gateway->method->method == 'PayUMoney' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="payumoneyTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.payumoney_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.payumoney_configuration')
                    </div>
                @elseif($gateway->method->method == 'JazzCash' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="jazzcashTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.jazzcash_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="" >
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.jazzcash_configuration')
                    </div>
                @elseif($gateway->method->method == 'Google Pay' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="google_payTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.google_pay_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.google_pay_configuration')
                    </div>
                @elseif($gateway->method->method == 'FlutterWave' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="flutterWaveTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.flutter_wave_payment_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.flutter_wave_payment_configuration')
                    </div>
                @elseif($gateway->method->method == 'Bank Payment' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="bankTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.bank_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('paymentgateway::components.bank_config')
                    </div>
                
                @elseif(isModuleActive('Bkash') &&  $gateway->method->method == 'Bkash' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="bkashTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.bkash_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('bkash::bkash_config')
                    </div>
                
                @elseif(isModuleActive('SslCommerz') &&  $gateway->method->method == 'SslCommerz' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="SslCommerzTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.sslcommerz_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('sslcommerz::config')
                    </div>
                
                @elseif(isModuleActive('MercadoPago') &&  $gateway->method->method == 'Mercado Pago' && @$gateway->status == 1)
                    <div role="tabpanel" class="tab-pane fade @if($key == 0) active show @endif" id="MercadoPagoTab">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('payment_gatways.mercado_pago_configuration')}}</h3>
                                <ul class="d-flex">
                                    <div class="img_logo_div">
                                        <img src="{{ showImage(@$gateway->method->logo) }}" alt="">
                                    </div>
                                </ul>
                            </div>
                        </div>
                        @include('mercadopago::config')
                    </div>
                @endif
                @php
                    $key ++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
