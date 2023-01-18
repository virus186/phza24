<div class="col-lg-12">
    <form id="contactForm" enctype="multipart/form-data" action="{{route('frontend.order_payment')}}" class="p-0" method="POST">
        @csrf
        <input type="hidden" name="method" value="flutterwave">
        <input type="hidden" name="amount" value="{{$total_amount - $coupon_am}}">
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
                <input class="primary_input3 style4 radius_3px mb_20" type="text" required name="phone" placeholder="{{ __('common.mobile') }}" value="{{@old('phone')}}">
                <input type="hidden" value="Order Payment" name="purpose">
            </div>
        </div>
        <button class="btn_1 d-none" id="flutterwave_btn" type="submit">{{ __('wallet.continue_to_pay') }}</button>
    </form>
</div>