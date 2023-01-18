@extends('frontend.amazy.layouts.app')
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-8 col-lg-8">
                <div class="order_tab_box d-flex justify-content-between gap-2 flex-wrap mb_20">
                    <ul class="nav amazy_order_tabs d-inline-flex" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if (Request::get('myPurchaseOrderList') != null || (Request::get('myPurchaseOrderListNotPaid') == null && Request::get('toShipped') == null && Request::get('toRecievedList') == null)) active @endif" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{__('common.all')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if (Request::get('myPurchaseOrderListNotPaid') != null) active @endif" id="Pay-tab" data-bs-toggle="tab" data-bs-target="#Pay" type="button" role="tab" aria-controls="Pay" aria-selected="false">{{__('defaultTheme.to_pay')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if (Request::get('toShipped') != null) active @endif" id="Ship-tab" data-bs-toggle="tab" data-bs-target="#Ship" type="button" role="tab" aria-controls="Ship" aria-selected="false">{{__('defaultTheme.to_ship')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if (Request::get('toRecievedList') != null) active @endif" id="Receive-tab" data-bs-toggle="tab" data-bs-target="#Receive" type="button" role="tab" aria-controls="Receive" aria-selected="false">{{__('defaultTheme.to_recieve')}}</button>
                        </li>
                    </ul>
                    <form class="p-0" action="{{ route('frontend.my_purchase_order_list') }}" method="get" id="rnForm">
                        <div class="d-flex align-items-center">
                            <select class="amaz_select5" id="rn" name="rn">
                                @isset($rn)
                                    <option value="5" @if ($rn == 5) selected @endif>{{__('common.last')}} {{getNumberTranslate(5)}} {{__('common.orders')}}</option>
                                    <option value="10" @if ($rn == 10) selected @endif>{{__('common.last')}} {{getNumberTranslate(10)}} {{__('common.orders')}}</option>
                                    <option value="20" @if ($rn == 20) selected @endif>{{__('common.last')}} {{getNumberTranslate(20)}} {{__('common.orders')}}</option>
                                    <option value="40" @if ($rn == 40) selected @endif>{{__('common.last')}} {{getNumberTranslate(40)}} {{__('common.orders')}}</option>
                                @else
                                    <option value="5">{{__('common.last')}} {{getNumberTranslate(5)}} {{__('common.orders')}}</option>
                                    <option value="10">{{__('common.last')}} {{getNumberTranslate(10)}} {{__('common.orders')}}</option>
                                    <option value="20">{{__('common.last')}} {{getNumberTranslate(20)}} {{__('common.orders')}}</option>
                                    <option value="40">{{__('common.last')}} {{getNumberTranslate(40)}} {{__('common.orders')}}</option>
                                @endisset
                            </select>
                        </div>
                    </form>
                </div>
                <!-- tab-content  -->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade @if (Request::get('myPurchaseOrderList') != null || (Request::get('myPurchaseOrderListNotPaid') == null && Request::get('toShipped') == null && Request::get('toRecievedList') == null)) show active @endif" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @if(count($orders) > 0)
                            
                            <!-- content ::start  -->
                            @foreach ($orders as $key => $order)
                                <div class="white_box style2 bg-white mb_20">
                                    <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate($order->order_number) }}</p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate($order->created_at) }}</p>
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
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($order->grand_total) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.paid_by')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{$order->GatewayName}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <a class="amaz_primary_btn gray_bg_btn min_200 radius_3px" href="{{ route('frontend.my_purchase_order_pdf', encrypt($order->id)) }}" target="_blank">+ {{__('defaultTheme.download_invoice')}}</a>
                                        </div>
                                    </div>
                                    <div class="dashboard_white_box_body">
                                        <div class="table-responsive mb_10">
                                            <table class="table amazy_table3 style2 mb-0">
                                                <tbody>
                                                    @foreach ($order->packages as $key => $package)
                                                        @foreach ($package->products as $key => $package_product)
                                                            @if ($package_product->type == "gift_card")
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{route('frontend.gift-card.show',@$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                            <div class="thumb">
                                                                                <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{textLimit(@$package_product->giftCard->name,22)}}" title="{{textLimit(@$package_product->giftCard->name,22)}}">
                                                                            </div>
                                                                            <div class="summery_pro_content">
                                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{textLimit(@$package_product->giftCard->name,22)}}</h4>
                                                                            </div>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($package_product->price) }}</h4>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                            <div class="thumb">
                                                                                @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                    <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="@if (@$package_product->seller_product_sku->product->product_name) {{textLimit(@$package_product->seller_product_sku->product->product_name,22)}} @else {{textLimit(@$package_product->seller_product_sku->sku->product->product_name,22)}} @endif" title="@if (@$package_product->seller_product_sku->product->product_name) {{textLimit(@$package_product->seller_product_sku->product->product_name,22)}} @else {{textLimit(@$package_product->seller_product_sku->sku->product->product_name,22)}} @endif">
                                                                                @else

                                                                                    <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" alt="@if (@$package_product->seller_product_sku->product->product_name) {{textLimit(@$package_product->seller_product_sku->product->product_name,22)}} @else {{textLimit(@$package_product->seller_product_sku->sku->product->product_name,22)}} @endif" title="@if (@$package_product->seller_product_sku->product->product_name) {{textLimit(@$package_product->seller_product_sku->product->product_name,22)}} @else {{textLimit(@$package_product->seller_product_sku->sku->product->product_name,22)}} @endif">
                                                                                @endif
                                                                            </div>
                                                                            <div class="summery_pro_content">
                                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">@if (@$package_product->seller_product_sku->product->product_name) {{textLimit(@$package_product->seller_product_sku->product->product_name,22)}} @else {{textLimit(@$package_product->seller_product_sku->sku->product->product_name,22)}} @endif</h4>
                                                                                @if(@$package_product->seller_product_sku->sku->product->product_type == 2)
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

                                                                                            @if(!$loop->last), @endif
                                                                                        @endforeach
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($package_product->price) }}</h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end flex-wrap gap_10">
                                            <a href="{{ route('frontend.my_purchase_order_detail', encrypt($order->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.order_details')}}</a>
                                            @if ($order->is_confirmed == 0)
                                                @if ($order->is_cancelled == 0)
                                                    <a data-id={{ $order->id }} class="amaz_primary_btn gray_bg_btn min_200 radius_3px ml_10 order_cancel_by_id" href="">{{__('defaultTheme.cancel_order')}}</a>
                                                @else
                                                    <a class="amaz_primary_btn gray_bg_btn min_200 radius_3px ml_10" href="">{{__('defaultTheme.order_cancelled')}}</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- content ::end    -->
                            @if (strpos($_SERVER['REQUEST_URI'], 'rn'))
                                @include(theme('pages.profile.partials.paginations'), ['orders' => $orders->appends('rn',$rn), 'request_type' => request()->myPurchaseOrderList])
                            @else
                                @include(theme('pages.profile.partials.paginations'), ['orders' => $orders, 'request_type' => request()->myPurchaseOrderList])
                            @endif
                        @else
                            <div class="row">
                                <div class="col-lg-12 empty_list">
                                    <span class="text-canter">{{ __('order.no_order_found') }}</span>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="tab-pane fade @if (Request::get('myPurchaseOrderListNotPaid') != null) show active @endif" id="Pay" role="tabpanel" aria-labelledby="Pay-tab">
                        @if(count($no_paid_orders) > 0)
                            @foreach ($no_paid_orders as $key => $no_paid_order)
                                <!-- content ::start  -->
                                <div class="white_box style2 bg-white mb_20">
                                    <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate($no_paid_order->order_number) }}</p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate($no_paid_order->created_at) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> 
                                                <p class="font_14 f_w_400 m-0 lh-base"> 
                                                    @if($no_paid_order->is_cancelled == 1)
                                                        {{__('common.cancelled')}}
                                                    @elseif($no_paid_order->is_completed == 1)
                                                        {{__('common.completed')}}
                                                    @else
                                                        @if ($no_paid_order->is_confirmed == 1)
                                                            {{__('common.status')}}</span>: {{__('common.confirmed')}}
                                                        @elseif ($no_paid_order->is_confirmed == 2)
                                                            {{__('common.status')}}</span>: {{__('common.declined')}}
                                                        @else
                                                            {{__('common.status')}}</span>: {{__('common.pending')}}
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($no_paid_order->grand_total) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.paid_by')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{@$no_paid_order->GatewayName}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <a href="{{ route('frontend.my_purchase_order_pdf', encrypt($no_paid_order->id)) }}" target="_blank" class="amaz_primary_btn gray_bg_btn min_200 radius_3px">+ {{__('defaultTheme.download_invoice')}}</a>
                                        </div>
                                    </div>
                                    <div class="dashboard_white_box_body">
                                        <div class="table-responsive mb_10">
                                            <table class="table amazy_table3 style2 mb-0">
                                                <tbody>
                                                    @foreach ($no_paid_order->packages as $key => $package)
                                                        @foreach ($package->products as $key => $package_product)
                                                            @if ($package_product->type == "gift_card")
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{route('frontend.gift-card.show',@$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                            <div class="thumb">
                                                                                <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{textLimit(@$package_product->giftCard->name,22)}}" title="{{textLimit(@$package_product->giftCard->name,22)}}">
                                                                            </div>
                                                                            <div class="summery_pro_content">
                                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{textLimit(@$package_product->giftCard->name,22)}}</h4>
                                                                            </div>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty')}}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 ">{{ single_price($package_product->price) }}</h4>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                            <div class="thumb">
                                                                                @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                    <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                                @else

                                                                                    <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                                @endif
                                                                            </div>
                                                                            <div class="summery_pro_content">
                                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}</h4>
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
                                                                    <td>
                                                                        
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="font_16 f_w_500 m-0 ">{{ single_price($package_product->price) }}</h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('frontend.my_purchase_order_detail', encrypt($no_paid_order->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.order_details')}}</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- content ::end    -->
                            @endforeach
                            @include(theme('pages.profile.partials.paginations'), ['orders' => $no_paid_orders, 'request_type' => request()->myPurchaseOrderListNotPaid])
                        @else
                            <div class="row">
                                <div class="col-lg-12 empty_list">
                                    <span class="text-canter">{{ __('order.no_order_found') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade @if (Request::get('toShipped') != null) show active @endif" id="Ship" role="tabpanel" aria-labelledby="Ship-tab">
                        @if(count($to_shippeds) > 0)
                            @foreach ($to_shippeds as $key => $order_package)
                                <!-- content ::start  -->
                                <div class="white_box style2 bg-white mb_20">
                                    <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate(@$order_package->order->order_number) }}</p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ getNumberTranslate(@$order_package->order->created_at) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> 
                                                <p class="font_14 f_w_400 m-0 lh-base"> 
                                                    @if(@$order_package->order->is_cancelled == 1)
                                                        {{__('common.cancelled')}}
                                                    @elseif(@$order_package->order->is_completed == 1)
                                                        {{__('common.completed')}}
                                                    @else
                                                        @if (@$order_package->order->is_confirmed == 1)
                                                            {{__('common.confirmed')}}
                                                        @elseif (@$order_package->order->is_confirmed == 2)
                                                            {{__('common.declined')}}
                                                        @else
                                                            {{__('common.pending')}}
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price(@$order_package->order->grand_total) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.paid_by')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{@$order_package->order->GatewayName}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <a href="{{ route('frontend.my_purchase_order_pdf', encrypt(@$order_package->order->id)) }}" target="_blank" class="amaz_primary_btn gray_bg_btn min_200 radius_3px">+ {{__('defaultTheme.download_invoice')}}</a>
                                        </div>
                                    </div>
                                    <div class="dashboard_white_box_body">
                                        <div class="table-responsive mb_10">
                                            <table class="table amazy_table3 style2 mb-0">
                                                <tbody>
                                                    @foreach ($order_package->products as $key => $package_product)
                                                        @if ($package_product->type == "gift_card")
                                                            <tr>
                                                                <td>
                                                                    <a href="{{route('frontend.gift-card.show',@$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                        <div class="thumb">
                                                                            <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$package_product->giftCard->name,22) }}" title="{{ textLimit(@$package_product->giftCard->name,22) }}">
                                                                        </div>
                                                                        <div class="summery_pro_content">
                                                                            <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$package_product->giftCard->name,22) }}</h4>
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                                <td></td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                </td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 "> {{ single_price($package_product->price) }}</h4>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                        <div class="thumb">
                                                                            @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                            @else

                                                                                <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                            @endif
                                                                        </div>
                                                                        <div class="summery_pro_content">
                                                                            <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}</h4>
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
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                </td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{ single_price($package_product->price) }}</h4>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('frontend.my_purchase_order_detail', encrypt($order_package->order->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.order_details') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- content ::end    -->
                            @endforeach
                            @include(theme('pages.profile.partials.paginations'), ['orders' => $to_shippeds, 'request_type' => request()->toShipped])
                        @else
                            <div class="row">
                                <div class="col-lg-12 empty_list">
                                    <span class="text-canter">{{ __('order.no_order_found') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade @if (Request::get('toRecievedList') != null) show active @endif" id="Receive" role="tabpanel" aria-labelledby="Receive-tab">
                        @if(count($to_recieves) > 0)
                            @foreach ($to_recieves as $key => $order_package)
                                <!-- content ::start  -->
                                <div class="white_box style2 bg-white mb_20">
                                    <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ @$order_package->order->order_number }}</p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ @$order_package->order->created_at }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> 
                                                <p class="font_14 f_w_400 m-0 lh-base"> 
                                                    @if(@$order_package->order->is_cancelled == 1)
                                                        {{__('common.cancelled')}}
                                                    @elseif(@$order_package->order->is_completed == 1)
                                                        {{__('common.completed')}}
                                                    @else
                                                        @if (@$order_package->order->is_confirmed == 1)
                                                            {{__('common.confirmed')}}
                                                        @elseif (@$order_package->order->is_confirmed == 2)
                                                            {{__('common.declined')}}
                                                        @else
                                                            {{__('common.pending')}}
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($order_package->order->grand_total) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <div class="d-flex align-items-center flex-wrap gap_5">
                                                <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.paid_by')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{@$order_package->order->GatewayName}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column  ">
                                            <a href="{{ route('frontend.my_purchase_order_pdf', encrypt(@$order_package->order->id)) }}" target="_blank" class="amaz_primary_btn gray_bg_btn min_200 radius_3px">+ {{__('defaultTheme.download_invoice')}}</a>
                                        </div>
                                    </div>
                                    <div class="dashboard_white_box_body">
                                        <div class="table-responsive mb_10">
                                            <table class="table amazy_table3 style2 mb-0">
                                                <tbody>
                                                    @foreach ($order_package->products as $key => $package_product)
                                                        @if ($package_product->type == "gift_card")
                                                            <tr>
                                                                <td>
                                                                    <a href="{{route('frontend.gift-card.show',@$package_product->giftCard->sku)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                        <div class="thumb">
                                                                            <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="{{ textLimit(@$package_product->giftCard->name,22) }}" title="{{ textLimit(@$package_product->giftCard->name,22) }}">
                                                                        </div>
                                                                        <div class="summery_pro_content">
                                                                            <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$package_product->giftCard->name,22) }}</h4>
                                                                        </div>
                                                                    </a>
                                                                </td><td>
                                                                </td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                </td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{ single_price($package_product->price) }}</h4>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <a href="{{singleProductURL(@$package_product->seller_product_sku->product->seller->slug, @$package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                        <div class="thumb">
                                                                            @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                            @else
                                                                                <img src="{{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}" alt="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}" title="{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}">
                                                                            @endif
                                                                        </div>
                                                                        <div class="summery_pro_content">
                                                                            <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ @$package_product->seller_product_sku->product->product_name?textLimit(@$package_product->seller_product_sku->product->product_name,22):textLimit(@$package_product->seller_product_sku->sku->product->product_name,22) }}</h4>
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
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{__('common.qty') }}: {{ getNumberTranslate($package_product->qty) }}</h4>
                                                                </td>
                                                                <td>
                                                                    <h4 class="font_16 f_w_500 m-0 ">{{ single_price($package_product->price) }}</h4>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end flex-wrap gap_10">
                                            <a href="{{ route('frontend.my_purchase_order_detail', encrypt($order_package->order->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.order_details')}}</a>
                                            @if (\Carbon\Carbon::now() <= $order_package->order->created_at->addDays(app('business_settings')->where('type', 'refund_times')->first()->status) && $order->is_cancelled == 0 && $order->is_completed == 1)
                                                <a href="{{ route('refund.make_request', encrypt($order_package->order->id)) }}" class="amaz_primary_btn gray_bg_btn min_200 radius_3px ml_10">{{__('defaultTheme.open_dispute')}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- content ::end    -->
                            @endforeach
                            @include(theme('pages.profile.partials.paginations'), ['orders' => $to_recieves, 'request_type' => request()->toRecievedList])
                        @else
                            <div class="row">
                                <div class="col-lg-12 empty_list">
                                    <span class="text-canter">{{ __('order.no_order_found') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
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
                            <form action="{{route('frontend.order_cancel_by_customer')}}" method="post" id="order_cancel_form">
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
@endsection
@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";

            $(document).ready(function(){
                $(document).on('click', '.change_delivery_state_status', function(event){
                    event.preventDefault();
                    let package_id = $(this).data('package_id');
                    change_delivery_state_status(package_id);
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

                $(document).on('change', '#rn', function(){    // 2nd (A)
                    $("#rnForm").submit();
                });

                $('#reason').niceSelect();
                $(document).on('click','.order_cancel_by_id', function(e){
                    e.preventDefault();
                    $('#orderCancelReasonModal').modal('show');
                    $('.order_id').val($(this).attr('data-id'));
                });

                $(document).on('submit', '#order_cancel_form', function(){
                    $("#pre-loader").show();
                    $('#orderCancelReasonModal').modal('hide');
                });
            });
        })(jQuery);

    </script>
@endpush