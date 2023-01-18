@extends('frontend.amazy.layouts.app')

@section('title')
    {{__('defaultTheme.place_a_refund_request')}}
@endsection

@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 offset-xl-1">
                    <div class="white_box style2 bg-white mb_20">
                        <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                            <div class="d-flex flex-column  ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.package_code')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $package->package_code }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ @$package->order->order_number }}</p>
                                </div>
                                @if(isModuleActive('MultiVendor'))
                                    <div class="d-flex align-items-center flex-wrap gap_5">
                                        <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.seller')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{($package->seller_id != 1)?@$package->seller->sellerAccount->seller_shop_display_name:app('general_setting')->site_title}}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex flex-column ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> 
                                        @if($package->order->is_cancelled == 1)
                                            {{__('common.cancelled')}}
                                        @elseif($package->order->is_completed == 1)
                                            {{__('common.completed')}}
                                        @else
                                            @if ($package->order->is_confirmed == 1)
                                                {{__('common.confirmed')}}
                                            @elseif ($package->order->is_confirmed == 2)
                                                {{__('common.declined')}}
                                            @else
                                                {{__('common.pending')}}
                                            @endif
                                        @endif
                                    </p>
                                    
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $package->created_at }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column  ">
                                @php
                                    $grand_total = $package->products->sum('total_price') + $package->shipping_cost;
                                @endphp
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price($grand_total) }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.payment')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> 
                                        @if ($package->order->is_paid == 1)
                                            {{__('common.paid')}}
                                        @else
                                            {{__('common.pending')}}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('refund.refund_make_request_store') }}" method="post">
                            @csrf
                            @php
                                $e_items = [];
                            @endphp
                            <div class="dashboard_white_box_body">
                                <div class="table-responsive mb_10">
                                    <table class="table amazy_table3 style2 mb-0 min-height-250 refund_product_list">
                                        <tbody>
                                            <input type="hidden" name="order_id" value="{{ $package->order->id }}">
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            @foreach ($package->products->where('type','product') as $k => $package_product)
                                                @if(@$package_product->seller_product_sku->sku->product->is_physical)
                                                    @php
                                                        //ga4
                                                        $e_items[]=[
                                                            "item_id"=>$package_product->product_sku_id,
                                                            "item_name"=> $package_product->seller_product_sku->sku->product->product_name,
                                                            "currency"=> currencyCode(),
                                                            "price"=> $package_product->price
                                                        ];
                                                    @endphp
                                                    <input type="hidden" name='e_items' value="{{json_encode($e_items)}}" >
                                                    <tr>
                                                        <td>
                                                            <a href="{{singleProductURL($package_product->seller_product_sku->product->seller->slug, $package_product->seller_product_sku->product->slug)}}" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                                <label class="primary_checkbox d-flex" for="product_id{{ $package_product->id }}">
                                                                    <input type="checkbox" name="product_ids[]" id="product_id{{ $package_product->id }}" checked value="{{ $package->id }}-{{ $package_product->product_sku_id }}-{{ $package->seller_id }}-{{ $package_product->price }}">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <div class="thumb">
                                                                    <img src="
                                                                        @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                            {{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}
                                                                        @else
                
                                                                            {{showImage((@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img)??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}
                                                                        @endif
                                                                    " alt="">
                                                                </div>
                                                                <div class="summery_pro_content">
                                                                    <h4 class="font_16 f_w_700 m-0 theme_hover">{{ textLimit(@$package_product->seller_product_sku->product->product_name,30) }}</h4>
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
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ $package_product->qty }} X {{ single_price($package_product->price) }}</h4>
                                                        </td>
                                                        <td>
                                                            <div class="product_number_count style_4" data-target="amount-1">
                                                                <button type="button" value="-" class="count_single_item inumber_decrement "> <i class="ti-minus"></i></button>
                                                                <input class="count_single_item input-number qty" type="text" name="qty_{{ $package_product->product_sku_id }}" maxlength="{{ $package_product->qty }}" minlength="1" value="{{ $package_product->qty }}" readonly>
                                                                <button type="button" value="+" class="count_single_item number_increment cart-qty-plus cart-qty-minus"> <i class="ti-plus"></i></button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select class="theme_select wide rounded-0" required id="reason_{{ $package_product->product_sku_id }}" name="reason_{{ $package_product->product_sku_id }}">
                                                                @foreach ($reasons as $key => $reason)
                                                                    <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="amazy_bb3 mt-2 mb-3"></div>
                                <form action="#">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="primary_label2 style2 ">{{__('defaultTheme.additional_information')}}</label>
                                            <textarea  name="additional_info" id="additional_info" maxlength="255" placeholder="{{__('defaultTheme.additional_information')}}" class="primary_textarea4  rounded-0 mb_25"></textarea>
                                            <span class="text-danger"  id="error_message"></span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="primary_label2 style2 ">{{__('defaultTheme.set_prefered_option')}} </label>
                                            <select class="theme_select wide rounded-0 mb_30" name="money_get_method" id="money_get_method">
                                                <option value="wallet">{{__('defaultTheme.wallet')}}</option>
                                                <option value="bank_transfer">{{__('defaultTheme.bank_transfer')}}</option>
                                            </select>
                                            <div class="bank_info_div row d-none">
                                                <div class="col-md-12">
                                                    <h5>{{__('defaultTheme.bank_information_to_recieve_money')}}</h5>
                                                </div>
                                                <div class="col-12">
                                                    <label class="primary_label2 style2 ">{{__('common.bank_name')}} <span>*</span></label>
                                                    <input type="text" id="bank_name" name="bank_name" placeholder="{{__('common.bank_name')}}" class="primary_input3 style4 mb_30">
                                                </div>
                                                <div class="col-12">
                                                    <label class="primary_label2 style2 ">{{__('common.branch_name')}} <span>*</span></label>
                                                    <input type="text" id="branch_name" name="branch_name" placeholder="{{__('common.branch_name')}}" class="primary_input3 style4 mb_30">
                                                </div>
                                                <div class="col-12">
                                                    <label class="primary_label2 style2 ">{{__('common.account_name')}} <span>*</span></label>
                                                    <input type="text" id="account_name" name="account_name" placeholder="{{__('common.account_name')}}" class="primary_input3 style4 mb_30">
                                                </div>
                                                <div class="col-12">
                                                    <label class="primary_label2 style2 ">{{__('common.account_number')}}<span>*</span></label>
                                                    <input type="text" id="account_no" name="account_no" placeholder="{{__('common.account_number')}}" class="primary_input3 style4 mb_30">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="primary_label2 style2 ">{{__('defaultTheme.set_shipment_option')}} </label>
                                            <select class="theme_select wide rounded-0 mb_30" name="shipping_way" id="shipping_way">
                                                <option value="courier">{{ __('shipping.courier_pick_up') }}</option>
                                                <option value="drop_off">{{ __('shipping.drop_off') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-12">
                                            <div class="row shipment_info_div1">
                                                <div class="col-12">
                                                    <label class="primary_label2 style2 ">{{__('defaultTheme.courier_address')}} <span>*</span></label>
                                                    <select class="theme_select wide rounded-0 mb_30" name="pick_up_address_id" id="pick_up_address_id">
                                                        @foreach (auth()->user()->customerAddresses as $key_num => $address)
                                                            <option value="{{ $address->id }}">{{ $address->address }}, {{ @$address->getCity->name }}, {{ @$address->getState->name }} ({{ $address->phone }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shipment_info_div2 row d-none">
                                            <div class="col-12">
                                                <label class="primary_label2 style2 ">{{__('defaultTheme.courier_address')}} <span>*</span></label>
                                                <input id="drop_off_courier_address" name="drop_off_courier_address" placeholder="{{__('defaultTheme.courier_address')}}" class="primary_input3 style4 mb_30" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="contactBtn" class="amaz_primary_btn style2 text-nowrap ">{{__('common.send')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function() {
                $(document).on('change', '#money_get_method', function() {
                    $('#pre-loader').show();
                    var method = this.value;
                    if (method == "bank_transfer") {
                        $('.bank_info_div').removeClass('d-none');
                    }else {
                        $('.bank_info_div').addClass('d-none');
                    }
                    $('#pre-loader').hide();
                });
                $(document).on('change', '#shipping_way', function() {
                    $('#pre-loader').show();
                    var way = this.value;
                    if (way == "courier") {
                        $('.shipment_info_div1').removeClass('d-none');
                        $('.shipment_info_div2').addClass('d-none');
                    }else {
                        $('.shipment_info_div1').addClass('d-none');
                        $('.shipment_info_div2').removeClass('d-none');
                    }
                    $('#pre-loader').hide();
                });
                var incrementPlus;
                var incrementMinus;
                var buttonPlus  = $(".cart-qty-plus");
                var buttonMinus = $(".cart-qty-minus");

                var incrementPlus = buttonPlus.on('click',function() {
                  var $n = $(this)
                    .parent(".button-container")
                    .parent(".product_count")
                    .find(".qty");
                    var max_qty = parseInt($n.attr("maxlength"));
                    if (Number($n.val()) < max_qty) {
                        $n.val(Number($n.val())+1 );
                    }
                });

                var incrementMinus = buttonMinus.on('click',function() {
                    var $n = $(this)
                    .parent(".button-container")
                    .parent(".product_count")
                    .find(".qty");
                  var amount = Number($n.val());
                  if (amount > 1) {
                    $n.val(amount-1);
                  }
                });
            });
        })(jQuery);
    </script>
@endpush
