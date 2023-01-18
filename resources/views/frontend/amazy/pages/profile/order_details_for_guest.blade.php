@extends('frontend.amazy.layouts.app')

@section('title')
    {{__('common.orders')}}
@endsection
@push('styles')

@endpush
@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <!-- content ::start  -->
                    <div class="white_box style2 bg-white mb_30">
                        <div class="white_box_header gray_color_1 d-flex align-items-center gap_20 flex-wrap  theme_border justify-content-between ">
                            <div class="d-flex flex-column  ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}:  </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $order->order_number }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} :  </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $order->created_at }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> 
                                    <p class="font_14 f_w_400 m-0 lh-base"> 
                                        @if($order->is_cancelled == 1)
                                            {{__('common.cancelled')}}
                                        @elseif($order->is_completed == 1)
                                            {{__('common.completed')}}
                                        @else
                                            @if ($order->is_confirmed == 1)
                                                {{__('common.confirmed')}}
                                            @elseif ($order->is_confirmed == 2)
                                                {{__('common.declined')}}
                                            @else
                                                {{__('common.pending')}}
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex flex-column  ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ single_price($order->grand_total) }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column  ">
                                <a href="{{ route('frontend.my_purchase_order_pdf', encrypt($order->id)) }}" target="_blank" class="amaz_primary_btn gray_bg_btn min_200 radius_3px">+ {{__('defaultTheme.download_invoice')}}</a>
                            </div>
                        </div>
                        <div class="dashboard_white_box_body dashboard_orderDetails_body">
                            @foreach ($order->packages as $key => $package)
                                <div class="order_prise d-flex justify-content-between gap-2 flex-wrap amazy_bb2 pb_11 mb_10">
                                    <h4 class="font_16 f_w_700 m-0">{{__('common.package')}} : {{ $package->package_code }}</h4>
                                    @if(isModuleActive('MultiVendor'))
                                        <h4 class="font_16 f_w_700 m-0">{{__('defaultTheme.sold_by')}}: @if($package->seller->role->type == 'seller') {{ @$package->seller->first_name }} @else {{ app('general_setting')->company_name }} @endif</h4>
                                    @endif
                                    <h4 class="font_16 f_w_700 m-0">{{__('common.price')}}: {{ single_price($package->products->sum('total_price') + $package->tax_amount + $package->gst_taxes->sum('amount')) }}</h4>
                                </div>
                                @if($package->is_cancelled == 0)
                                    <p class="font_14 f_w_400">{{ $package->shipping_date }}</p>
                                    <div class="order_details_progress ">
                                        @if($package->carrier->slug == 'Shiprocket')
                                        @php
                                            $status = $order_status[$package->id];
                                            $ready_to_ship = false;
                                            $pickup= false;
                                            $ship= false;
                                            $delivered= false;
                                            switch ($status){
                                                case "READY TO SHIP":
                                                    $ready_to_ship = true;
                                                    break;
                                                case 'PICKUP':
                                                $ready_to_ship = true;
                                                $pickup= true;
                                                break;
                                                case 'SHIPPED':
                                                $ready_to_ship = true;
                                                $pickup= true;
                                                $ship= true;
                                                break;
                                                case 'DELIVERED':
                                                $ready_to_ship = true;
                                                $pickup= true;
                                                $ship= true;
                                                $delivered= true;
                                                break;
                                            }

                                        @endphp
                                        <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                            <div class="icon position-relative ">
                                                @if ($package->delivery_status >= 1)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                            <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                        </g>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                        </g>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font_14 f_w_500 m-0 text-nowrap">{{__('common.pending')}}</h5>
                                        </div>
                                        <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                            <div class="icon position-relative ">
                                                @if ($ready_to_ship)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                            <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                        </g>
                                                    </svg>
                                                @elseif($package->delivery_status >= 1 && !$ready_to_ship)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#50cd89" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#50cd89"></circle>
                                                        </g>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                        </g>
                                                    </svg>
                                                @endif

                                            </div>
                                            <h5 class="font_14 f_w_500 m-0 text-nowrap">{{__('shipping.ready_to_ship')}}</h5>
                                        </div>
                                        <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                            <div class="icon position-relative ">
                                                @if ($pickup)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                            <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                        </g>
                                                    </svg>
                                                @elseif($ready_to_ship && !$pickup)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#50cd89" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#50cd89"></circle>
                                                        </g>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                        </g>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font_14 f_w_500 m-0 mute_text  text-nowrap">{{__('shipping.pickup')}}</h5>
                                        </div>
                                        <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                            <div class="icon position-relative ">
                                                @if ($ship)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                            <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                        </g>
                                                    </svg>
                                                @elseif($pickup && !$ship)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#50cd89" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#50cd89"></circle>
                                                        </g>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                        </g>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font_14 f_w_500 m-0 mute_text text-nowrap">{{__('common.shipped')}}</h5>
                                        </div>
                                        <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                            <div class="icon position-relative ">
                                                @if ($delivered)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                            <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                        </g>
                                                    </svg>
                                                @elseif($ship && !$delivered)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#50cd89" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#50cd89"></circle>
                                                        </g>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                        <g  data-name="1" transform="translate(-613 -335)">
                                                            <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                            <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                            <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                            </g>
                                                            <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                        </g>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font_14 f_w_500 m-0 mute_text text-nowrap">{{__('order.delivered')}}</h5>
                                        </div>
                                        @else
                                            @php
                                                $next_step = null;
                                            @endphp
                                            @foreach ($processes as $key => $process)
                                                <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                                    <div class="icon position-relative ">
                                                        
                                                        @if ($package->delivery_status >= $process->id)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                                <g  data-name="1" transform="translate(-613 -335)">
                                                                    <circle  data-name="Ellipse 239" cx="15" cy="15" r="15" transform="translate(613 335)" fill="#50cd89"></circle>
                                                                    <path  data-name="Path 4193" d="M95.541,18.379a1.528,1.528,0,0,1-1.16-.533l-3.665-4.276a1.527,1.527,0,0,1,2.319-1.988l2.4,2.8L103,5.245c1.172-1.642,2.4-.733,1.222.916L96.784,17.739a1.528,1.528,0,0,1-1.175.638Z" transform="translate(530.651 338.622)" fill="#fff"></path>
                                                                </g>
                                                            </svg>
                                                            @php
                                                                $next_step = $key + 1;
                                                            @endphp
                                                        @else
                                                            @if($next_step == $key)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                                    <g  data-name="1" transform="translate(-613 -335)">
                                                                        <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#50cd89" stroke-width="2">
                                                                        <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                                        <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                                        </g>
                                                                        <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#50cd89"></circle>
                                                                    </g>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                                    <g  data-name="1" transform="translate(-613 -335)">
                                                                        <g  data-name="Ellipse 239" transform="translate(613 335)" fill="none" stroke="#f1ece8" stroke-width="2">
                                                                        <circle cx="15" cy="15" r="15" stroke="none"></circle>
                                                                        <circle cx="15" cy="15" r="14" fill="none"></circle>
                                                                        </g>
                                                                        <circle  data-name="Ellipse 240" cx="5" cy="5" r="5" transform="translate(623 345)" fill="#f1ece8"></circle>
                                                                    </g>
                                                                </svg>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <h5 class="font_14 f_w_500 m-0 text-nowrap">{{ $process->name }}</h5>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap_20 flex-wrap gray_color_1 dashboard_orderDetails_head  justify-content-between theme_border">
                                        <div class="d-flex flex-column ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.package_code')}}:  </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $package->package_code }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($package->products->sum('total_price')) }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.tax_amount')}}:  </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($package->tax_amount) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap_20 flex-wrap gray_color_1 dashboard_orderDetails_head  justify-content-between theme_border">
                                        <h5 class="text-danger mt_20 w-100 text-center">{{__('defaultTheme.order_cancelled')}}</h5>
                                    </div>
                                @endif
                                <div class="table-responsive mb_10">
                                    <table class="table amazy_table3 style2 mb-0">
                                        <tbody>
                                            @php
                                                $physical_product = 0;
                                            @endphp
                                            @foreach ($package->products as $key => $package_product)
                                                @if ($package_product->type == "gift_card")
                                                    <tr>
                                                        <td>
                                                            <a href="{{route('frontend.gift-card.show',@$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20">
                                                                <div class="thumb">
                                                                    <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{textLimit(@$package_product->giftCard->name,22)}}" title="{{textLimit(@$package_product->giftCard->name,22)}}">
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{textLimit(@$package_product->giftCard->name,22)}}</h4>
                                                                    <p class="font_14 f_w_400 m-0 ">
                                                                        @if ($order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first() != null)
                                                                        {{__('order.Secret-Key')}}: {{ $order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first()->secret_code }}
                                                                        @else
                                                                        {{__('order.check_shipping_email_for_secret_key')}}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td></td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{ $package_product->qty }}</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($package_product->price) }}</h4>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @if(@$package_product->seller_product_sku->sku->product->is_physical)
                                                        @php
                                                            $physical_product = 1;
                                                        @endphp
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20">
                                                                <div class="thumb">
                                                                    @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                        <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name? textLimit(@$package_product->seller_product_sku->product->product_name, 28) : textLimit(@$package_product->seller_product_sku->sku->product->product_name, 28) }}" title="{{ @$package_product->seller_product_sku->product->product_name? textLimit(@$package_product->seller_product_sku->product->product_name, 28) : textLimit(@$package_product->seller_product_sku->sku->product->product_name, 28) }}">
                                                                    @else
                                                                        <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name? textLimit(@$package_product->seller_product_sku->product->product_name, 28) : textLimit(@$package_product->seller_product_sku->sku->product->product_name, 28) }}" title="{{ @$package_product->seller_product_sku->product->product_name? textLimit(@$package_product->seller_product_sku->product->product_name, 28) : textLimit(@$package_product->seller_product_sku->sku->product->product_name, 28) }}">
                                                                    @endif
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ @$package_product->seller_product_sku->product->product_name? textLimit(@$package_product->seller_product_sku->product->product_name, 28) : textLimit(@$package_product->seller_product_sku->sku->product->product_name, 28) }}</h4>
                                                                    @if (@$package_product->seller_product_sku->product->is_digital)
                                                                        <a class="font_14 f_w_400 m-0" target="_blank" href="{{ route('digital_file_download', encrypt($package->files->where('product_sku_id', $package_product->seller_product_sku->product_sku_id)->where('customer_id', auth()->user()->id)->first()->id)) }}"><i class="ti-download mr-1 green"></i>
                                                                            {{__('common.download')}}
                                                                        </a>
                                                                    @endif
                                                                    @if($package_product->seller_product_sku->sku->product->product_type == 2)
                                                                        <p class="font_14 f_w_400 m-0 ">
                                                                            @php
                                                                                $countCombinatiion = count(@$package_product->seller_product_sku->product_variations);
                                                                            @endphp
                                                                            @foreach(@$package_product->seller_product_sku->product_variations as $key => $combination)
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
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td></td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{ $package_product->qty }}</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($package_product->price) }}</h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                            @if($package->carrier->type == 'Manual' && $package->carrier_order_id)
                                <div class="d-flex align-items-center gap_20 flex-wrap gray_color_1 dashboard_orderDetails_head  justify-content-between theme_border">
                                    <div class="d-flex flex-column ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('shipping.shipping_by')}}:  </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{@$package->carrier->name }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column  ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('shipping.tracking_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{@$package->carrier_order_id }}</p>
                                        </div>
                                    </div>
                                    @if(@$package->carrier->tracking_url)
                                    <div class="d-flex flex-column  ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('shipping.tracking_url')}}:  </h4> <a class="font_14 f_w_400 m-0 lh-base text_color" target="_blank" href="{{ str_replace("@",@$package->carrier_order_id,$package->carrier->tracking_url)}}"> {{__('common.click_here')}}</a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif
                            <div class="d-flex flex-column mt_10">
                                <div class="d-flex align-items-center flex-wrap gap_5 justify-content-end">
                                    @if ($order->is_confirmed == 0 && $package->is_cancelled == 0 || $order->is_confirmed == 1 && $package->is_cancelled == 0 && $package->delivery_status <= 2)
                                        <a href="" data-id={{ $package->id }} class="amaz_primary_btn gray_bg_btn radius_3px order_cancel_by_id">{{__('defaultTheme.cancel_order')}}</a>
                                    @elseif ($order->is_completed == 1 || $package->delivery_status >= 5)
                                        @if (\Carbon\Carbon::now() <= $order->created_at->addDays(app('business_settings')->where('type', 'refund_times')->first()->status) && $package->is_cancelled == 0 && $physical_product == 1 && !$package->refundPackage)
                                            <a href="{{ route('refund.make_request', encrypt($package->id)) }}" class="amaz_primary_btn gray_bg_btn radius_3px">{{__('defaultTheme.open_dispute')}}</a>
                                        @endif
                                    @endif
                                    @if($package->delivery_status > 4 && count(@$package->reviews) < 1 && $order->is_completed == 1)
                                        <a href="{{url('/')}}/profile/product-review?order_id={{base64_encode($order->id)}}&&package_id={{base64_encode($package->id)}}&&seller_id={{base64_encode($package->seller_id)}}" class="amaz_primary_btn gray_bg_btn radius_3px">{{__('defaultTheme.write_a_review')}}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="order_details_list_box">
                                <div class="summery_order_body d-flex flex-wrap">
                                    <div class="summery_lists flex-fill">
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 ">
                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{__('defaultTheme.shipping_info')}} @if($order->delivery_type == 'pickup_location'){{__('shipping.collect_from_pickup_location')}} @endif</h4>
                                            </div>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.secret_id')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{$order->guest_info->guest_id}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.name')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->shipping_name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.email')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->shipping_email}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.phone_number')}} </h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->shipping_phone}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.address')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->shipping_address}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.city')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getShippingCity->name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.state')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getShippingState->name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.country')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getShippingCountry->name}}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="summery_lists flex-fill">
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 ">
                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover"> {{__('shipping.billing_info')}} </h4>
                                            </div>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.name')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->billing_name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.email')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->billing_email}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.phone_number')}} </h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->billing_phone}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.address')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->billing_address}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.city')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getBillingCity->name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.state')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getBillingState->name}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.country')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{@$order->guest_info->getBillingCountry->name}}</p>
                                        </div>

                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 ">
                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{__('defaultTheme.payment_info')}} </h4>
                                            </div>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.subtotal')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{single_price($order->sub_total)}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.discount')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">- {{single_price($order->discount_total)}}</p>
                                        </div>
                                        @if($order->coupon)
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.coupon')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">- {{single_price($order->coupon->discount_amount)}}</p>
                                            </div>
                                        @endif
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('defaultTheme.tax_amount')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{single_price($order->tax_amount)}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.shipping_charge')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{single_price($order->shipping_total)}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.grand_total')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{single_price($order->grand_total)}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('defaultTheme.paid_by')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">{{$order->GatewayName}}</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('order.txn_id')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0"> @if(@$order->order_payment->txn_id && @$order->order_payment->txn_id != 'none'){{ @$order->order_payment->txn_id }} @else - @endif</p>
                                        </div>
                                        <div class="single_summery_list d-flex align-items-start gap_20">
                                            <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 text-nowrap"><h5 class="font_14 f_w_500 m-0">{{__('defaultTheme.payment_status')}}</h5><span>:</span>
                                            </div>
                                            <p class="font_14 f_w_400 m-0">
                                                @if ($order->is_paid == 1)
                                                    {{__('common.paid')}}
                                                @else
                                                    {{__('common.pending')}}
                                                @endif
                                            </p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content ::end    -->
                </div>
                <div class="col-xl-4 col-lg-4">
                    <!-- order state list component -->
                    @foreach ($processes as $key => $process)
                        <div class="dashboard_white_box style3 rounded-0 bg-white mb_20">
                            <div class="dashboard_white_box_body">
                            <h4 class="font_20 f_w_700 mb-2">{{ $process->name }}</h4>
                            <p class="lineHeight1 font_14 f_w_400 mb-0">{{ $process->description }}</p>
                            </div>
                        </div>
                    @endforeach
                    <!-- order state list component -->
                </div>
            </div>
            <!-- cancel order modal -->
        <div class="modal fade login_modal about_modal" id="orderCancelReasonModal" tabindex="-1" role="dialog" aria-labelledby="asq_about_form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                    <div data-bs-dismiss="modal" class="close_modal">
                        <i class="ti-close"></i>
                    </div>
                    <!-- infix_login_area::start  -->
                        <div class="infix_login_area p-0">
                            <div class="login_area_inner">
                                <h3 class="sign_up_text mb_20 fs-5">{{ __('common.select_cancel_reason') }}</h3>
                                <form action="{{route('frontend.my_purchase_order_package_cancel_guest')}}" method="post" id="order_cancel_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb_30">
                                            <div class="form-group input_div_mb">
                                                <label class="primary_label2 style4">{{ __('refund.reason') }} <span>*</span></label>
                                                <select class="primary_input3 radius_3px style6" name="reason" id="reason" autocomplete="off">
                                                    @foreach ($cancel_reasons as $key => $cancel_reason)
                                                        <option value="{{ $cancel_reason->id }}">{{ $cancel_reason->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" id="order_id" name="order_id" class="form-control order_id" required>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="home10_primary_btn2 text-center f_w_700">{{ __('common.send') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- infix_login_area::end  -->
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- cancel order modal -->
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">

        (function($) {
           "use strict";
            $(document).ready(function() {
                $(".next-step").trigger('click');
                $('#reason').niceSelect();
                $(document).on('click','.order_cancel_by_id', function(event){
                    event.preventDefault();
                    $('#orderCancelReasonModal').modal('show');
                    $('.order_id').val($(this).attr('data-id'));
                });

                $(document).on('click','.change_delivery_state_status', function(){
                    change_delivery_state_status($(this).attr('data-id'));
                });

                function change_delivery_state_status(el)
                {
                    $("#pre-loader").show();
                    $.post('{{ route('change_delivery_status_by_customer') }}', {_token:'{{ csrf_token() }}', package_id:el}, function(data){
                        if (data == 1) {
                            toastr.success("{{__('defaultTheme.order_has_been_recieved')}}", "{{__('common.success')}}");
                        }else {
                            toastr.error("{{__('defaultTheme.order_not_recieved')}} {{__('common.error_message')}}", "{{__('common.error')}}");
                        }
                        $("#pre-loader").hide();
                    });
                }

                $(document).on('submit', '#order_cancel_form', function(){
                    $("#pre-loader").show();
                    $('#orderCancelReasonModal').modal('hide');
                });
            });


        })(jQuery);
    </script>
@endpush
