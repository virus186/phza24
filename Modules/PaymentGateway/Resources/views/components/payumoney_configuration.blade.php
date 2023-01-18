<form action="{{ route('payment_gateway.configuration') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="name" value="PayUMoney Configuration">

        <div class="col-lg-2 mt-20 mb-30">
            <input type="hidden" name="types[]" value="PAYU_MONEY_MODE">
            <div class="input-effect">
                <div class="input-effect">
                    <div class="text-left float-left">
                        <input type="radio" name="PAYU_MONEY_MODE" @if ($gateway->perameter_1 == "TEST_MODE"  || $gateway->perameter_1 == null) checked @endif
                            id="PAYU_MONEY_MODE_mode_check_1" value="TEST_MODE" class="common-radio relationButton read-only-input">
                        <label for="PAYU_MONEY_MODE_mode_check_1">{{ __('payment_gatways.test') }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 mt-20 mb-30">
            <div class="input-effect">
                <div class="input-effect">
                    <div class="text-left float-left">
                        <input type="radio" name="PAYU_MONEY_MODE" id="PAYU_MONEY_MODE_live_mode_check_1" @if ($gateway->perameter_1 == "LIVE_MODE") checked @endif value="LIVE_MODE" class="common-radio relationButton read-only-input">
                        <label for="PAYU_MONEY_MODE_live_mode_check_1">{{ __('payment_gatways.live') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="primary_input mb-25">
                <input type="hidden" name="types[]" value="PAYU_MONEY_KEY">
                <label class="primary_input_label" for="">{{ __('payment_gatways.payumoney_key') }}</label>
                <input name="PAYU_MONEY_KEY" class="primary_input_field" value="{{ $gateway->perameter_2 }}"
                    placeholder="{{ __('payment_gatways.payumoney_key') }}" type="text">
            </div>
        </div>
        <div class="col-xl-12">
            <div class="primary_input mb-25">
                <input type="hidden" name="types[]" value="PAYU_MONEY_SALT">
                <label class="primary_input_label" for="">{{ __('payment_gatways.payumoney_salt') }}</label>
                <input name="PAYU_MONEY_SALT" class="primary_input_field" value="{{ $gateway->perameter_3 }}"
                    placeholder="{{ __('payment_gatways.payumoney_salt') }}" type="text">
            </div>
        </div>
        <div class="col-xl-12">
            <div class="primary_input mb-25">
                <input type="hidden" name="types[]" value="PAYU_MONEY_AUTH">
                <label class="primary_input_label" for="">{{ __('payment_gatways.payumoney_auth') }}</label>
                <input name="PAYU_MONEY_AUTH" class="primary_input_field" value="{{ $gateway->perameter_4 }}"
                    placeholder="{{ __('payment_gatways.payumoney_auth') }}" type="text">
            </div>
        </div>
        <input type="hidden" name="id" value="{{ @$gateway->id }}">
        <input type="hidden" name="method_id" value="{{ @$gateway->method->id }}">
        @if(auth()->user()->role->type != 'seller')
            <div class="col-xl-8">
                <div class="primary_input mb-25">
                    <label class="primary_input_label" for="">{{ __('payment_gatways.gateway_logo') }} (400x166)PX</label>
                    <div class="primary_file_uploader">
                        <input class="primary-input" type="text" id="logoPayUmoney_file"
                            placeholder="{{ __('payment_gatways.gateway_logo') }}" readonly="" />
                        <button class="" type="button">
                            <label class="primary-btn small fix-gr-bg" for="logoPayUmoney">{{ __('product.Browse') }}
                            </label>
                            <input type="file" class="d-none" name="logo" accept="image/*" id="logoPayUmoney" />
                        </button>
                    </div>

                </div>
            </div>
            <div class="col-xl-4">
                <div class="logo_div">
                    @if (@$gateway->method->logo)
                    <img id="logoPayUmoneyDiv" class=""
                        src="{{ showImage(@$gateway->method->logo) }}" alt="">
                    @else
                    <img id="logoPayUmoneyDiv" class="" src="{{ showImage('backend/img/default.png') }}" alt="">
                    @endif
                </div>
            </div>
        @endif
        <div class="col-lg-12 text-center">
            <button class="primary_btn_2 mt-2"><i class="ti-check"></i>{{__("common.update")}} </button>
        </div>
    </div>
</form>
