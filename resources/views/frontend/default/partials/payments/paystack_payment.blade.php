<form action="{{route('frontend.order_payment')}}" method="post" id="paystack_form" class="paystack_form d-none">
    @csrf
    <input type="hidden" name="email" value="{{$address->email}}"> {{-- required --}}
    <input type="hidden" name="orderID" value="{{rand(111, 999).date('ymdhis')}}">
    <input type="hidden" name="amount" value="{{ ($total_amount - $coupon_am)*100}}">
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="currency" value="{{$currency_code}}">

    <input type="hidden" name="method" value="Paystack">

    <button type="submit" class="btn_1 order_submit_btn" id="paystack_btn">{{ __('defaultTheme.process_to_payment') }}</button>

</form>
