@extends('backEnd.master')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/ordermanage/css/sale_details.css'))}}" />

@endsection
@section('mainContent')
    <div id="add_product">
        <section class="admin-visitor-area up_st_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ $order->order_number }} </h3>
                                <ul class="d-flex float-right">
                                    <li><a href="{{ route('order_manage.print_order_details', $order->id) }}"
                                            target="_blank"
                                            class="primary-btn fix-gr-bg radius_30px mr-10">{{ __('order.print') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 student-details">
                        <div class="white_box_50px box_shadow_white" id="printableArea">
                            <div class="row pb-30 border-bottom">
                                <div class="col-md-6 col-lg-6">
                                    <div class="logo_div">
                                        <img src="{{ showImage(app('general_setting')->logo) }}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 text-right">
                                    <h4>{{ $order->order_number }}</h4>
                                </div>
                            </div>
                            <div class="row mt-30">
                                @if ($order->customer_id)
                                    <div class="col-md-6 col-lg-6">
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.name')}}</td>
                                                <td>: {{ @$order->address->billing_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.email')}}</td>
                                                <td><a class="link_color" href="mailto:{{ @$order->address->billing_email }}">:
                                                        {{ @$order->address->billing_email }}</a></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.phone')}}</td>
                                                <td>: {{ @$order->address->billing_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.address')}}</td>
                                                <td>: {{ @$order->address->billing_address }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.city')}}</td>
                                                <td>: {{ @$order->address->getBillingCity->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.state')}}</td>
                                                <td>: {{ @$order->address->getBillingState->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.country')}}</td>
                                                <td>: {{ @$order->address->getBillingCity->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.postcode')}}</td>
                                                <td>: {{ @$order->address->billing_postcode }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div class="col-md-6 col-lg-6">
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.name')}}</td>
                                                <td>: {{$order->guest_info->billing_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.email')}}</td>
                                                <td><a class="link_color" href="mailto:{{$order->guest_info->billing_email}}">: {{$order->guest_info->billing_email}}</a></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.phone')}}</td>
                                                <td>: {{$order->guest_info->billing_phone}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.address')}}</td>
                                                <td>: {{$order->guest_info->billing_address}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.city')}}</td>
                                                <td>: {{@$order->guest_info->getBillingCity->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.state')}}</td>
                                                <td>: {{@$order->guest_info->getBillingState->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.country')}}</td>
                                                <td>: {{@$order->guest_info->getBillingCountry->name}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                                <div class="col-md-6 col-lg-6">
                                    @if ($order->customer_id)
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>{{__('defaultTheme.shipping_info')}} @if($order->delivery_type == 'pickup_location')(Collect from Pickup location) @endif</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.name')}}</td>
                                                <td>: {{ @$order->address->shipping_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.email')}}</td>
                                                <td><a class="link_color" href="mailto:{{ @$order->address->shipping_email }}">:
                                                        {{ @$order->address->shipping_email }}</a></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.phone')}}</td>
                                                <td>: {{ @$order->address->shipping_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.address')}}</td>
                                                <td>: {{ @$order->address->shipping_address }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.city')}}</td>
                                                <td>: {{ @$order->address->getShippingCity->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.state')}}</td>
                                                <td>: {{ @$order->address->getShippingState->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.country')}}</td>
                                                <td>: {{ @$order->address->getShippingCountry->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.postcode')}}</td>
                                                <td>: {{ @$order->address->shipping_postcode }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>{{__('defaultTheme.shipping_info')}} @if($order->delivery_type == 'pickup_location')(Collect from Pickup location) @endif</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.secret_id')}}</td>
                                                <td>: {{$order->guest_info->guest_id}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.name')}}</td>
                                                <td>: {{$order->guest_info->shipping_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.email')}}</td>
                                                <td><a class="link_color" href="mailto:{{$order->guest_info->shipping_email}}">: {{$order->guest_info->shipping_email}}</a></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.phone')}}</td>
                                                <td>: {{$order->guest_info->shipping_phone}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.address')}}</td>
                                                <td>: {{$order->guest_info->shipping_address}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.city')}}</td>
                                                <td>: {{@$order->guest_info->getShippingCity->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.state')}}</td>
                                                <td>: {{@$order->guest_info->getShippingState->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.country')}}</td>
                                                <td>: {{@$order->guest_info->getShippingCountry->name}}</td>
                                            </tr>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-30">

                                <div class="col-md-6 col-lg-6">
                                    <table class="table-borderless clone_line_table">
                                        <tr>
                                            <td><strong>{{__('defaultTheme.payment_info')}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.payment_method')}}</td>
                                            <td>: {{ $order->GatewayName }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.amount')}}</td>
                                            <td>: {{ single_price(@$order->order_payment->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('order.txn_id')}}</td>
                                            <td>: {{ @$order->order_payment->txn_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.date')}}</td>
                                            <td>:
                                                {{ date(app('general_setting')->dateFormat->format, strtotime(@$order->order_payment->created_at)) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('defaultTheme.payment_status')}}</td>
                                            <td>:
                                                @if ($order->is_paid == 1)
                                                    <span>{{__('common.paid')}}</span>
                                                @else
                                                    <span>{{__('common.pending')}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                @if(isModuleActive('Affiliate'))
                                    @if($order->affiliateUser)
                                    <div class="col-md-6 col-lg-6">
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>{{__('Affiliate User')}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.name')}}</td>
                                                <td>: <a target="_blank" class="link_color" href="{{route('affiliate.user.show',$order->affiliateUser->payment_to)}}">{{ @$order->affiliateUser->user->first_name }}</a></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.email')}}</td>
                                                <td>: {{ @$order->affiliateUser->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('common.phone')}}</td>
                                                <td>: {{ @$order->affiliateUser->user->phone }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endif
                                @endif
                            </div>
                            <div class="row mt-30">
                                @foreach ($order->packages as $key => $order_package)
                                    <div class="col-12 mt-30">
                                        @if ($order_package->is_cancelled == 1)
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label red" for="">
                                                    {{__('defaultTheme.order_cancelled')}} - ({{ $order_package->package_code }})
                                                </label>
                                            </div>

                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label sub-title" for="">
                                                    {{ @$order_package->cancel_reason->name }}
                                                </label>
                                            </div>
                                        @endif
                                        <div class="box_header common_table_header">
                                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('common.package')}}:
                                                {{ $order_package->package_code }} @if ($order_package->delivery_process)
                                                    <small>({{ @$order_package->delivery_process->name }})</small>
                                                @endif
                                            </h3>
                                            @if(isModuleActive('MultiVendor'))
                                            <ul class="d-flex float-right">
                                                <li>
                                                    <strong>
                                                        @if($order_package->seller->role->type == 'seller')
                                                            {{ @$order_package->seller->SellerAccount->seller_shop_display_name ? @$order_package->seller->SellerAccount->seller_shop_display_name : @$order_package->seller->first_name }}
                                                        @else
                                                            {{ app('general_setting')->company_name }}
                                                        @endif

                                                    </strong>
                                                </li>
                                            </ul>
                                            @endif
                                        </div>
                                        <div class="box_header common_table_header justify-content-lg-end">
                                            
                                            <ul class="d-flex float-right">
                                                <li> <strong>Shipping Method : {{ $order_package->shipping->method_name }}</strong></li>
                                            </ul>
                                        </div>

                                        <div class="QA_section QA_section_heading_custom check_box_table">
                                            <div class="QA_table ">
                                                <!-- table-responsive -->
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <th scope="col">{{__('common.sl')}}</th>
                                                            <th scope="col">{{__('common.image')}}</th>
                                                            <th scope="col">{{__('common.name')}}</th>
                                                            <th scope="col">{{__('common.details')}}</th>
                                                            <th scope="col">{{__('common.price')}}</th>
                                                            <th scope="col">{{__('common.tax')}}/GST</th>
                                                            <th scope="col">{{__('common.total')}}</th>
                                                        </tr>
                                                        @foreach ($order_package->products as $key => $package_product)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <div class="product_img_div">
                                                                        @if ($package_product->type == "gift_card")
                                                                            <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="#">
                                                                        @else
                                                                            @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}"
                                                                                     alt="#">
                                                                            @else
                                                                                <img src="{{showImage(@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}"
                                                                                     alt="#">
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if ($package_product->type == "gift_card")
                                                                        <span class="text-nowrap">{{substr(@$package_product->giftCard->name,0,22)}} @if(strlen(@$package_product->giftCard->name) > 22)... @endif</span><br>
                                                                        <a class="green gift_card_div pointer" data-gift-card-id='{{ $package_product->giftCard->id }}' data-qty='{{ $package_product->qty }}' data-customer-mail='{{($order->customer_id) ? $order->customer_email : $order->guest_info->shipping_email}}' data-order-id='{{ $order->id }}'>
                                                                            <i class="ti-email mr-1 green"></i>
                                                                            {{($order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first() != null && $order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first()->is_mail_sent) ? "Sent Already" : "Send Code Now"}}
                                                                        </a>
                                                                    @else
                                                                        <span class="text-nowrap">{{substr(@$package_product->seller_product_sku->sku->product->product_name,0,22)}} @if(strlen(@$package_product->seller_product_sku->sku->product->product_name) > 22)... @endif</span>
                                                                        @if (!isModuleActive('MultiVendor') && @$package_product->seller_product_sku->product->product->is_physical == 0 && @$package_product->seller_product_sku->sku->digital_file)
                                                                            <br><a class="green is_digital_div pointer" data-customer-id='{{ $order->customer_id }}' data-product-sku-id='{{ @$package_product->seller_product_sku->product_sku_id }}' data-seller-sku-id='{{ @$package_product->seller_product_sku->id }}' data-seller-id='{{ $order_package->seller_id }}' data-package-id='{{ $order_package->id }}' data-qty='{{ $package_product->qty }}' data-customer-mail='{{($order->customer_id) ? @$order->address->shipping_email : @$order->guest_info->shipping_email}}' data-order-id='{{ $order->id }}'><i class="ti-email mr-1 green"></i>
                                                                                Sent Link to mail
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @if ($package_product->type == "gift_card")
                                                                    <td class="text-nowrap">Qty: {{ $package_product->qty }}</td>
                                                                @else
                                                                    @if (@$package_product->seller_product_sku->sku->product->product_type == 2)
                                                                        <td class="text-nowrap">
                                                                            Qty: {{ $package_product->qty }}
                                                                            <br>
                                                                            @php
                                                                                $countCombinatiion = count(@$package_product->seller_product_sku->product_variations);
                                                                            @endphp
                                                                            @foreach (@$package_product->seller_product_sku->product_variations as $key => $combination)
                                                                                @if ($combination->attribute->name == 'Color')
                                                                                    <div class="box_grid ">
                                                                                        <span>{{ $combination->attribute->name }}:</span><span class='box variant_color' style="background-color:{{ $combination->attribute_value->value }}"></span>
                                                                                    </div>
                                                                                @else
                                                                                    {{ $combination->attribute->name }}:
                                                                                    {{ $combination->attribute_value->value }}
                                                                                @endif
                                                                                @if ($countCombinatiion > $key + 1)
                                                                                    <br>
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @else
                                                                        <td class="text-nowrap">Qty: {{ $package_product->qty }}</td>
                                                                    @endif
                                                                @endif

                                                                <td class="text-nowrap">{{ single_price($package_product->price) }}</td>
                                                                <td class="text-nowrap">{{ single_price($package_product->tax_amount) }}</td>
                                                                <td class="text-nowrap">{{ single_price($package_product->price * $package_product->qty + $package_product->tax_amount) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-30">
                                <div class="col-md-12 col-lg-12">
                                    <table class="table-borderless clone_line_table w-100">
                                        <tr>
                                            <td><strong>{{__('order.order_info')}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{__('order.is_paid')}}</td>
                                            <td class="pl-25 text-nowrap">: {{ $order->is_paid == 1 ? __('common.yes') : __('common.no') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('order.subtotal')}}</td>
                                            <td class="pl-25 text-nowrap">: {{ single_price($order->sub_total) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.discount')}}</td>
                                            <td class="pl-25 text-nowrap">: - {{ single_price($order->discount_total) }}</td>
                                        </tr>
                                        @if($order->coupon)
                                        <tr>
                                            <td>{{__('common.coupon')}} {{__('common.discount')}}</td>
                                            <td class="pl-25 text-nowrap">: - {{single_price($order->coupon->discount_amount)}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>{{__('common.shipping_charge')}}</td>
                                            <td class="pl-25 text-nowrap">: {{ single_price($order->shipping_total) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.tax')}}/GST</td>
                                            <td class="pl-25 text-nowrap">: {{ single_price($order->tax_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('order.grand_total')}}</td>
                                            <td class="pl-25 text-nowrap">: {{ single_price($order->grand_total) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                @if (@$order->order_payment->payment_method == 7)
                                    <div class="col-md-6 col-lg-6">
                                        <table class="table-borderless clone_line_table">
                                            <tr>
                                                <td><strong>Bank Details</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('common.bank_name') }}</td>
                                                <td>: {{ @$order->order_payment->item_details->bank_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('common.branch_name') }}</td>
                                                <td>: {{ @$order->order_payment->item_details->branch_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('common.account_number') }}</td>
                                                <td>: {{ @$order->order_payment->item_details->account_number }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('wallet.account_holder') }}</td>
                                                <td>: {{ @$order->order_payment->item_details->account_holder }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('common.attachment') }}</td>
                                                <td>: <a href="{{ asset(asset_path(@$order->order_payment->item_details->image_src)) }}" target="_blank">{{ __('common.check') }}</a> </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 student-details">
                        @if ($order->is_cancelled != 1)
                            <form action="{{ route('order_manage.order_update_info', $order->id) }}" method="post">
                                @csrf
                                <div class="row white_box p-25 ml-0 mr-0 box_shadow_white">
                                    <div class="col-lg-12 p-0">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">
                                                <strong>{{ __('order.order_confirmation') }}</strong> </label>
                                            <select class="primary_select mb-25" name="is_confirmed" id="is_confirmed">
                                                @if ($order->is_confirmed == 1 && $order->is_completed == 1)
                                                    <option value="1" @if ($order->is_confirmed == 1) selected @endif>{{ __('order.confirmed') }}
                                                    </option>
                                                @elseif($order->is_confirmed == 1 && $order->is_completed == 0)
                                                    <option value="1" @if ($order->is_confirmed == 1) selected @endif>{{ __('order.confirmed') }}
                                                    </option>
                                                    <option value="2" @if ($order->is_confirmed == 2) selected @endif>{{ __('order.declined') }}
                                                    </option>
                                                @else
                                                    <option value="0" @if ($order->is_confirmed == 0) selected @endif>{{ __('order.pending') }}
                                                    </option>
                                                    <option value="1" @if ($order->is_confirmed == 1) selected @endif>{{ __('order.confirmed') }}
                                                    </option>
                                                    <option value="2" @if ($order->is_confirmed == 2) selected @endif>{{ __('order.declined') }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 p-0">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">
                                                <strong>{{ __('order.payment_status') }}</strong> </label>
                                            <select class="primary_select mb-25" name="is_paid" id="is_paid">
                                                <option value="0" @if ($order->is_paid == 0) selected @endif>{{ __('order.pending') }}</option>
                                                <option value="1" @if ($order->is_paid == 1) selected @endif>{{ __('order.paid') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 p-0">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">
                                                <strong>{{ __('order.is_completed') }}</strong> </label>
                                            <select class="primary_select mb-25" name="is_completed" id="is_completed">
                                                <option value="0" @if ($order->is_completed == 0) selected @endif>{{ __('order.pending') }}</option>
                                                <option value="1" @if ($order->is_completed == 1) selected @endif>{{ __('order.complete') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @if(!isModuleActive('MultiVendor'))
                                    @php
                                        $package = $order->packages->first();
                                    @endphp
                                    <div class="col-lg-12 p-0">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">
                                                <strong>{{ __('order.delivery_status') }}</strong></label>
                                            <select class="primary_select mb-25" name="delivery_status"
                                                id="delivery_status">
                                                @foreach ($processes as $key => $process)
                                                    <option value="{{ $process->id }}" @if ($package->delivery_status == $process->id) selected @endif>
                                                        {{ $process->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="current_package_status" value="{{$package->delivery_status}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 p-0 d-none" id="delivery_note">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">
                                                <strong>{{ __('order.note') }}</strong> </label>
                                            <textarea class="primary_textarea height_112 address"
                                                placeholder="{{ __('order.note') }}" name="note"
                                                spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-lg-12 p-0 text-center">
                                        <button class="primary_btn_2"><i class="ti-check"></i>{{ __('common.update') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="row white_box_50px box_shadow_white">
                                <div class="col-lg-12 p-0">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label red" for="">
                                            {{__('defaultTheme.order_cancelled')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 p-0">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label sub-title" for="">
                                            {{ @$order->cancel_reason->name }}
                                        </label>
                                        <label class="primary_input_label sub-details" for="">
                                            {{ @$order->cancel_reason->description }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($order->note != null)
                            <div class="row white_box p-25 ml-0 mr-0 box_shadow_white mt-20">
                                <div class="description_box">
                                    <h4 class="f_s_14 f_w_500 mb_10">{{__('common.order')}} {{__('common.note')}}:</h4>
                                    <p class="f_w_400">
                                        {{$order->note}}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click','.gift_card_div', function(){
                    var gift_card_id = $(this).attr("data-gift-card-id");
                    var order_id = $(this).attr("data-order-id");
                    var mail = $(this).attr("data-customer-mail");
                    var qty = $(this).attr("data-qty");
                    $(this).text('Sending.....');
                    var _this = this;
                    $.post('{{ route('send_gift_card_code_to_customer') }}', {_token:'{{ csrf_token() }}', order_id:order_id, mail:mail, gift_card_id:gift_card_id, qty:qty}, function(data){

                        if (data == "true" || data == 1) {
                            toastr.success("{{__('common.mail_has_been_sent_successful')}}","{{__('common.success')}}")
                            $(_this).text('Sent')
                        }else {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $(_this).text('Send Code Now')
                        }

                    }).fail(function(response) {
                        if(response.responseJSON.msg){
                            toastr.error(response.responseJSON.msg ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            $(_this).text('Already Used')
                            return false;
                        }
                    });
                });

                $(document).on('click', '.is_digital_div', function(){
                    var customer_id = $(this).attr("data-customer-id");
                    var seller_id = $(this).attr("data-seller-id");
                    var order_id = $(this).attr("data-order-id");
                    var package_id = $(this).attr("data-package-id");
                    var seller_product_sku_id = $(this).attr("data-seller-sku-id");
                    var product_sku_id = $(this).attr("data-product-sku-id");
                    var mail = $(this).attr("data-customer-mail");
                    var qty = $(this).attr("data-qty");

                    // console.log(customer_id+'-'+seller_id+'-'+order_id+'-'+package_id+'-'+seller_product_sku_id+'-'+product_sku_id+'-'+mail+'-'+qty)
                    $(this).text('Sending.....');
                    var _this = this;
                    $.post('{{ route('send_digital_file_access_to_customer') }}', {_token:'{{ csrf_token() }}', customer_id:customer_id, seller_id:seller_id, order_id:order_id, package_id:package_id, seller_product_sku_id:seller_product_sku_id, product_sku_id:product_sku_id, mail:mail, qty:qty}, function(data){
                        // console.log(data)
                        if (data == "true" || data == 1) {
                            toastr.success("{{__('common.mail_has_been_sent_successful')}}","{{__('common.success')}}")
                            $(_this).text('Sent')
                        }else {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");

                            $(_this).text('Send Code Now')
                        }
                    });
                });

                $(document).on('change', '#delivery_status', function(event){
                    var current_status = $('#current_package_status').val();
                    var change_status = $('#delivery_status').val();
                    if(current_status != change_status){
                        $('#delivery_note').removeClass('d-none');
                    }else{
                        $('#delivery_note').addClass('d-none');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
