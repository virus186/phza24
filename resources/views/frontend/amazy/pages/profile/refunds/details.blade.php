@extends('frontend.amazy.layouts.app')

@section('title')
    {{__('common.refund')}}
@endsection

@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <!-- content ::start  -->
                    <div class="white_box style2 bg-white mb_30">
                        <div class="dashboard_white_box_body dashboard_orderDetails_body">
                            @foreach ($refund_request->refund_details as $key => $refund_detail)
                                <div class="order_details_progress style2">
                                    @php
                                        $next_step = null;
                                    @endphp
                                    @foreach ($processes as $key => $process)
                                    <div class="single_order_progress position-relative d-flex align-items-center flex-column">
                                        <div class="icon position-relative ">
                                            @if ($refund_detail->processing_state >= $process->id)
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
                                </div>
                                <div class="d-flex align-items-center gap_20 flex-wrap gray_color_1 dashboard_orderDetails_head  justify-content-between theme_border">
                                    <div class="d-flex flex-column  ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ $refund_request->order->order_number }}</p>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} :  </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ $refund_request->order->created_at->format('d-m-Y h:i:s A') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $refund_request->CheckConfirmed }}</p>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.request_sent_date')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $refund_request->created_at->format('d-m-Y h:i:s A') }} </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column  ">
                                        <div class="d-flex align-items-center flex-wrap gap_5">
                                            <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price( $refund_request->total_return_amount) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mb_20">
                                    <table class="table amazy_table3 style2 mb-0">
                                        <tbody>
                                            @foreach ($refund_detail->refund_products as $key => $refund_product)
                                                <tr>
                                                    <td>
                                                        <a href="{{singleProductURL(@$refund_product->seller_product_sku->product->seller->slug, @$refund_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                            <div class="thumb">
                                                                <img src="
                                                                    @if (@$refund_product->seller_product_sku->sku->product->product_type == 1)
                                                                        {{showImage(@$refund_product->seller_product_sku->sku->product->thumbnail_image_source)}}
                                                                    @else
                                                                        {{showImage(@$refund_product->seller_product_sku->sku->variant_image)}}
                                                                    @endif
                                                                " alt="">
                                                            </div>
                                                            <div class="summery_pro_content">
                                                                <h4 class="font_16 f_w_700 m-0 theme_hover">{{ textLimit(@$refund_product->seller_product_sku->product->product_name,30) }}</h4>
                                                                <p class="font_14 f_w_400 m-0 ">
                                                                    @php
                                                                        $countCombinatiion = count(@$refund_product->seller_product_sku->product_variations);
                                                                    @endphp
                                                                    @foreach(@$refund_product->seller_product_sku->product_variations as $key => $combination)
                                                                        @if($combination->attribute->name == 'Color')
                                                                            {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                                        @else
                                                                            {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                                        @endif

                                                                        @if(!$loop->last), @endif
                                                                    @endforeach
                                                                </p>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{ $refund_product->return_qty }}</h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($refund_product->return_amount / $refund_product->return_qty) }}</h4>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            <div class="d-flex align-items-center gap_20 mb_20 flex-wrap gray_color_1 dashboard_orderDetails_head2  justify-content-between theme_border">
                                <div class="d-flex flex-column  ">
                                    <div class="d-flex align-items-center flex-wrap gap_5 mb_7">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ $refund_request->order->order_number }}</p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap_5 mb_7">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} :  </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ $refund_request->order->created_at->format('d-m-Y h:i:s A') }}</p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap_5">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.refund_method')}}:  </h4> <p class="font_14 f_w_400 m-0 lh-base">{{ strtoupper(str_replace("_"," ",$refund_request->refund_method)) }}</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column ">
                                    <div class="d-flex align-items-center flex-wrap gap_5 mb_7">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $refund_request->CheckConfirmed }}</p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap_5 mb_7">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.request_sent_date')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $refund_request->created_at->format('d-m-Y h:i:s A') }}</p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap_5">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.shipping_method')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ strtoupper(str_replace("_"," ",$refund_request->shipping_method)) }} </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column  ">
                                    <div class="d-flex align-items-center flex-wrap gap_5">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}:   </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price( $refund_request->total_return_amount) }}65</p>
                                    </div>
                                </div>
                            </div>
                            <div class="order_details_list_box">
                                <div class="summery_order_body d-flex flex-wrap">
                                    @if ($refund_request->shipping_method == "courier")
                                        <div class="summery_lists flex-fill">
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 ">
                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{__('defaultTheme.pick_up_info')}} </h4>
                                                </div>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('defaultTheme.shipping_gateway')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->shipping_gateway->method_name }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.name')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->name }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.email')}} </h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->email }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.phone_number')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->phone }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.address')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->address }}</p>
                                            </div>

                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.city')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->getCity->name }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.state')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->getState->name }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.postcode')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->pick_up_address_customer->postal_code }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="summery_lists flex-fill">
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 ">
                                                    <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{__('defaultTheme.drop_off_info')}} </h4>
                                                </div>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('defaultTheme.shipping_gateway')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ @$refund_request->shipping_gateway->method_name }}</p>
                                            </div>
                                            <div class="single_summery_list d-flex align-items-start gap_20">
                                                <div class="order_text_head d-flex align-items-center justify-content-between font_14 f_w_500 "><h5 class="font_14 f_w_500 m-0">{{__('common.address')}}</h5><span>:</span>
                                                </div>
                                                <p class="font_14 f_w_400 m-0">{{ $refund_request->drop_off_address }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content ::end    -->
                </div>
                <div class="col-xl-3 col-lg-4">
                    @foreach ($processes as $key => $process)
                        <div class="dashboard_white_box style3 rounded-0 bg-white mb_20">
                            <div class="dashboard_white_box_body">
                                <h4 class="font_20 f_w_700 mb-2">{{ $process->name }}</h4>
                                <p class="lineHeight1 font_14 f_w_400 mb-0">
                                    {{ $process->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
