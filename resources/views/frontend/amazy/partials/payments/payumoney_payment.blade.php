@php
    if(app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout')){
        $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 11);
    }else{
        $credential = getPaymentInfoViaSellerId(1, 11);
    }
    if (@$credential->perameter_1 == "TEST_MODE") {
        $PAYU_BASE_URL = "https://test.payu.in/_payment";
    }
    else {
        $PAYU_BASE_URL = "https://secure.payu.in/_payment";
    }

    $MERCHANT_KEY = @$credential->perameter_2; // add your id
    $SALT = @$credential->perameter_3; // add your id

    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    $posted =  array(
    'key' => $MERCHANT_KEY,
    'txnid' => $txnid,
    'amount' => number_format($total_amount - $coupon_am,2),
    'firstname' => @$address->name,
    'email' => @$address->email,
    'phone' => null,
    'productinfo' => 'walletRecharge',
    'surl' => route('payumoney.success'),
    'furl' => route('payumoney.failed'),
    'service_provider' => 'payu_paisa',
    );

    $hash = '';
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

    if(empty($posted['hash']) && sizeof($posted) > 0) {
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
        $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
        $hash_string .= '|';
    }
    $hash_string .= $SALT;

    $hash = strtolower(hash('sha512', $hash_string));
    }
@endphp

    <div class="col-lg-12">
    <form id="contactForm" enctype="multipart/form-data" action="{{$PAYU_BASE_URL}}" class="p-0" method="POST">
    @csrf
    <input type="hidden" name="method" value="PayUMoney">
    <input type="hidden" name="amount" value="{{$total_amount - $coupon_am}}">

    <input type="hidden" name="key" value="{{ $MERCHANT_KEY }}"/>
    <input type="hidden" name="txnid" value="{{ $txnid }}"/>
    <input type="hidden" name="surl" value="{{ route('payumoney.success') }}"/>
    <input type="hidden" name="furl" value="{{ route('payumoney.success') }}"/>
    <input type="hidden" name="hash" value="{{ $hash }}"/>
    <input type="hidden" name="service_provider" value="payu_paisa"/>
    <input type="hidden" name="productinfo" value="Checkout"/>

    <div class="row">
        <div class="col-lg-12">
            <label class="primary_label2 style3" for="">{{ __('common.name') }} <span>*</span></label>
            <input class="primary_input3 style4 radius_3px mb_20" type="text" required name="name" placeholder="{{ __('common.name') }}" value="{{@$address->name}}">
        </div>
        <div class="col-lg-12">
            <label class="primary_label2 style3" for="">{{ __('common.email') }} <span>*</span></label>
            <input class="primary_input3 style4 radius_3px mb_20" type="text" required name="email" placeholder="{{ __('common.email') }}" value="{{@$address->email}}">
        </div>
        <div class="col-lg-12">
            <label class="primary_label2 style3" for="">{{ __('common.mobile') }} <span>*</span></label>
            <input class="primary_input3 style4 radius_3px mb_20" type="text" required name="mobile" placeholder="{{ __('common.mobile') }}" value="{{@old('mobile')}}">
        </div>
    </div>
    <button class="btn_1 d-none" id="payumoney_btn" type="submit">{{ __('wallet.continue_to_pay') }}</button>
    </form>
    </div>