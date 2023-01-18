<div class="col-lg-12 text-center mt_5 mb_25">
    <span></span>
</div>
<form action="{{route('frontend.order_payment')}}" method="post" id="stripe_form" class="stripe_form d-none">
    <input type="hidden" name="method" value="Stripe">
    <input type="hidden" name="amount" value="{{$total_amount - $coupon_am}}">
    <button type="submit" id="stribe_submit_btn" class="btn_1 order_submit_btn">{{ __('defaultTheme.process_to_payment') }}</button>
    @csrf
    @php
        if(app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout')){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 4);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 4);
        }
    @endphp
    <script
        src="https://checkout.stripe.com/checkout.js"
        class="stripe-button"
        data-key="{{ @$credential->perameter_1 }}"
        data-name="Stripe Payment"
        data-image="{{showImage(app('general_setting')->favicon)}}"
        data-locale="auto"
        data-currency="{{$currency_code}}">
    </script>
</form>