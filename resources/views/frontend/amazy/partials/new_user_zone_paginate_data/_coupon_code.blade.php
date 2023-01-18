

<div class="coupon_gift_box_left flex-fill position-relative">
    <img class="img-fluid" src="{{showImage('frontend/amazy/img/amazCat/new_user_coupon3.png')}}" alt="Coupon Background Image" title="Coupon Background Image">
    <div class="coupon_gift_box_text position-absolute top-50 start-50 translate-middle">
        <h3 class="fs-4 lh-1 ">
            @if(@$coupon->coupon->discount_type == 1)
                {{single_price(@$coupon->coupon->discount)}}
            @else
                {{@$coupon->coupon->discount}} %
            @endif
        </h3>
        <div class="coupon_text">
            <h4>{{__('defaultTheme.orders_over')}} {{single_price(@$coupon->coupon->minimum_shopping)}}</h4>

            <p>{{date(app('general_setting')->dateFormat->format, strtotime(@$coupon->coupon->start_date))}} - {{date(app('general_setting')->dateFormat->format, strtotime(@$coupon->coupon->end_date))}}</p>
        </div>
    </div>
</div>
<div class="coupon_gift_box_right d-flex align-items-center justify-content-center">
    @if(auth()->check())
        @if($coupon_store_check == 0)
            <input type="hidden" id="coupon_id" value="{{@$coupon->coupon->id}}">
            <a id="get_now_btn" class="amaz_primary_btn style5">{{__('defaultTheme.get_now')}}</a>
        @else
        <a id="javascript:void(0);" class="amaz_primary_btn style5" >{{__('defaultTheme.time_to_shop')}}</a>
        @endif

    @else
        <a href="{{url('/login')}}" class="amaz_primary_btn style5">{{__('defaultTheme.get_now')}}</a>
    @endif
</div>