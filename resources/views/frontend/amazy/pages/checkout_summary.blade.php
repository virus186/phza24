@extends('frontend.amazy.layouts.app')
@section('title')
    {{ __('defaultTheme.checkout') }} {{__('common.summary')}}
@endsection

@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="white_box style2 bg-white mb_20">
                        <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-center ">
                            <div class="title text-center">
                                <h3 class="m-0">{{ __('defaultTheme.thank_you_for_your_purchase') }}!</h3>
                                <p>{{ __('defaultTheme.your_order_number_is') }} {{ $order->order_number }}</p>
                            </div>
                        </div>
                        <div class="dashboard_white_box_body">
                            @foreach ($order->packages as $key => $package)
                                <div class="card rounded-0 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap">
                                            <div class="flex-fill">
                                                @foreach ($package->products as $key => $package_product)
                                                    @if ($package_product->type == "gift_card")
                                                        <a href="{{route('frontend.gift-card.show',$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20 w-100 flex-fill @if(!$loop->last) amazy_bb3 @endif cart_thumb_div">
                                                            <div class="thumb">
                                                                <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$package_product->giftCard->name, 28) }}" title="{{ textLimit(@$package_product->giftCard->name, 28) }}">
                                                            </div>
                                                            <div class="summery_pro_content">
                                                                <h4 class="font_16 f_w_700 m-0 theme_hover">{{ textLimit(@$package_product->giftCard->name, 28) }}</h4>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 w-100 flex-fill @if(!$loop->last) amazy_bb3 @endif cart_thumb_div">
                                                            <div class="thumb">
                                                                @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                    <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="{{ textLimit(@$package_product->seller_product_sku->product->product_name, 28) }}" title="{{ textLimit(@$package_product->seller_product_sku->product->product_name, 28) }}">
                                                                @else
                                                                    <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" alt="{{ textLimit(@$package_product->seller_product_sku->product->product_name, 28) }}" title="{{ textLimit(@$package_product->seller_product_sku->product->product_name, 28) }}">
                                                                @endif
                                                            </div>
                                                            <div class="summery_pro_content">
                                                                <h4 class="font_16 f_w_700 m-0 theme_hover">{{ textLimit(@$package_product->seller_product_sku->product->product_name, 28) }}</h4>
                                                                @if($package_product->seller_product_sku->sku->product->product_type == 2)
                                                                    <p class="font_14 f_w_400 m-0">
                                                                    @foreach($package_product->seller_product_sku->product_variations as $key => $combination)
                                                                        @if($combination->attribute->name == 'Color')
                                                                            {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                                        @else
                                                                            {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                                        @endif
                                                                        @if(!$loop->last), @endif
                                                                    @endforeach
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                            
                                            <h4 class="font_16 f_w_500 m-0 text-capitalize">{{ $package->shipping_date }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between align-items-center g-2 mb_20 flex-wrap">
                                <p>{{ __('defaultTheme.for_more_details_track_your_delivery_status_order') }} <span class="f_w_600">{{ __('customer_panel.my_account') }} > {{ __('order.my_order') }}</span></p>
                                <a href="{{ route('frontend.my_purchase_order_detail', encrypt($order->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('common.view_order')}}</a>
                            </div>
                            <div class="table-responsive mb_10">
                                <table class="table amazy_table3 style2 mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p><i class="ti-email"></i> {{ __('defaultTheme.we_have_a_confirmation_email_to') }} {{ $order->customer_email }} {{ __('defaultTheme.with_the_order_details') }}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center g-2 mb_20 border gray_color_1 p-3">
                                <h4 class="f_w_500 font_25 m-0 text-capitalize">{{ __('defaultTheme.order_summary') }}</h4>
                                <span  class="f_w_500 font_20 m-0 text-capitalize secondary_text ">{{ single_price($order->grand_total) }}</span>
                            </div>
                            <div class="continue_shoping text-center">
                                <a class="amaz_primary_btn style2 text-nowrap" href="{{ route('frontend.welcome') }}">{{ __('defaultTheme.continue_shopping') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection