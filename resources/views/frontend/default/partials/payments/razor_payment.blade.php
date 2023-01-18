<form action="{{route('frontend.order_payment')}}" method="POST" id="razor_form" class="razor_form d-none">
    <input type="hidden" name="method" value="RazorPay">
    <input type="hidden" name="amount" value="{{ ($total_amount - $coupon_am) * 100 }}">


    <button type="submit" class="btn_1 order_submit_btn" id="razorpay_btn">{{ __('defaultTheme.process_to_payment') }}</button>
    @csrf
    @php
        if(app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout')){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 6);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 6);
        }
    @endphp
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ @$credential->perameter_1 }}"
        data-amount="{{ ($total_amount - $coupon_am) * 100 }}" data-name="{{str_replace('_', ' ',app('general_setting')->company_name ) }}"
        data-description="Order Total" data-image="{{showImage(app('general_setting')->favicon)}}"
        data-prefill.name="{{$address->name}}"
        data-prefill.email="{{$address->email}}"
        data-theme.color="#ff7529">
    </script>
</form>
