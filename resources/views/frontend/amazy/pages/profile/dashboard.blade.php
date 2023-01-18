@extends('frontend.amazy.layouts.app')

@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    @include('frontend.amazy.pages.profile.partials._menu')
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="dashBoard_cart_boxs mb_25 dynamic_svg">
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24.005" height="24.001" viewBox="0 0 24.005 24.001">
                                        <g  transform="translate(-1.25 -1.254)">
                                            <path  data-name="Path 4133" d="M5.278,6.979a.856.856,0,0,1-.592-.246.842.842,0,0,1,0-1.183L8.739,1.5A.837.837,0,0,1,9.923,2.68L5.87,6.733A.877.877,0,0,1,5.278,6.979Z" transform="translate(0.372 0)" fill="#fd4949"/>
                                            <path  data-name="Path 4134" d="M19.319,6.979a.828.828,0,0,1-.592-.246L14.674,2.68A.837.837,0,0,1,15.858,1.5L19.911,5.55a.842.842,0,0,1,0,1.183A.856.856,0,0,1,19.319,6.979Z" transform="translate(1.536 0)" fill="#fd4949"/>
                                            <path  data-name="Path 4135" d="M22.419,11.242H4.32A3.007,3.007,0,0,1,2,10.606,3.245,3.245,0,0,1,1.25,8.172C1.25,5.1,3.494,5.1,4.566,5.1H21.939c1.072,0,3.316,0,3.316,3.07a3.228,3.228,0,0,1-.748,2.434A2.769,2.769,0,0,1,22.419,11.242ZM4.566,9.568H22.2c.5.011.971.011,1.128-.145.078-.078.246-.346.246-1.25,0-1.262-.313-1.4-1.641-1.4H4.566c-1.329,0-1.641.134-1.641,1.4,0,.9.179,1.172.246,1.25A2.247,2.247,0,0,0,4.3,9.568Z" transform="translate(0 0.448)" fill="#fd4949"/>
                                            <path  data-name="Path 4136" d="M9.847,18.888a.843.843,0,0,1-.837-.837V14.087a.837.837,0,0,1,1.675,0v3.964A.836.836,0,0,1,9.847,18.888Z" transform="translate(0.904 1.398)" fill="#fd4949"/>
                                            <path  data-name="Path 4137" d="M14.447,18.888a.843.843,0,0,1-.837-.837V14.087a.837.837,0,1,1,1.675,0v3.964A.836.836,0,0,1,14.447,18.888Z" transform="translate(1.44 1.398)" fill="#fd4949"/>
                                            <path  data-name="Path 4138" d="M16.305,24.324H9.573c-4,0-4.89-2.378-5.236-4.444L2.762,10.222a.837.837,0,0,1,1.652-.268L5.989,19.6c.324,1.976.994,3.048,3.584,3.048h6.732c2.869,0,3.193-1,3.562-2.948l1.876-9.769a.836.836,0,0,1,1.641.324l-1.876,9.769C21.073,22.292,20.347,24.324,16.305,24.324Z" transform="translate(0.175 0.932)" fill="#fd4949"/>
                                        </g>
                                        </svg>
                                    </div>
                                    <span class="font_14 f_w_500"> {{__('amazy.Total Order')}}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_order_count) }}</h3>
                                </div>
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24.162" height="24" viewBox="0 0 24.162 24">
                                        <path  d="M19.7,25.321a4.636,4.636,0,0,1-2.316-.753l-3.361-1.99a1.6,1.6,0,0,0-1.36,0l-3.372,1.99c-1.99,1.18-3.159.708-3.687.326s-1.326-1.36-.8-3.609l.8-3.451a1.586,1.586,0,0,0-.36-1.248L2.453,13.8c-1.394-1.394-1.282-2.586-1.09-3.17A3.088,3.088,0,0,1,4.094,8.683l3.586-.6a1.608,1.608,0,0,0,.967-.719L10.637,3.4c.9-1.81,2.08-2.08,2.7-2.08s1.8.27,2.7,2.08l1.978,3.957a1.664,1.664,0,0,0,.978.719l3.586.6c1.945.326,2.552,1.36,2.732,1.945a3.1,3.1,0,0,1-1.09,3.17l-2.788,2.8a1.617,1.617,0,0,0-.36,1.248l.8,3.451c.517,2.248-.281,3.226-.8,3.609A2.3,2.3,0,0,1,19.7,25.321Zm-6.363-4.587a3.077,3.077,0,0,1,1.54.393l3.361,1.99c.978.585,1.6.585,1.832.416s.4-.764.157-1.866l-.8-3.451a3.289,3.289,0,0,1,.809-2.822l2.788-2.788c.551-.551.8-1.09.686-1.461s-.641-.674-1.405-.8l-3.586-.6a3.3,3.3,0,0,1-2.2-1.63L14.538,4.164c-.36-.719-.809-1.147-1.2-1.147s-.843.427-1.192,1.147l-1.99,3.957a3.3,3.3,0,0,1-2.2,1.63l-3.575.6c-.764.124-1.282.427-1.405.8s.135.922.686,1.461l2.788,2.788a3.279,3.279,0,0,1,.809,2.822l-.8,3.451c-.259,1.113-.079,1.7.157,1.866s.843.157,1.832-.416l3.361-1.99A3,3,0,0,1,13.335,20.734Z" transform="translate(-1.25 -1.32)" fill="#fd4949"/>
                                    </svg>
                                    </div>
                                    <span class="font_14 f_w_500">{{ __('customer_panel.my_wishlist') }}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_wishlist_count) }}</h3>
                                </div>
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23.999" height="24" viewBox="0 0 23.999 24">
                                        <g  transform="translate(-1.25 -1.25)">
                                            <path id="Path_4159" data-name="Path 4159" d="M8.615,23.967H5.6c-2.968,0-4.352-1.384-4.352-4.352V16.6c0-2.968,1.384-4.352,4.352-4.352H8.615c2.968,0,4.352,1.384,4.352,4.352v3.013C12.967,22.583,11.583,23.967,8.615,23.967ZM5.6,13.924c-2.053,0-2.678.625-2.678,2.678v3.013c0,2.053.625,2.678,2.678,2.678H8.615c2.053,0,2.678-.625,2.678-2.678V16.6c0-2.053-.625-2.678-2.678-2.678Z" transform="translate(0 1.283)" fill="#fd4949"/>
                                            <path id="Path_4160" data-name="Path 4160" d="M15.087,23.735a.833.833,0,0,1-.714-1.261l1.172-1.953a.838.838,0,1,1,1.439.859l-.3.5a7,7,0,0,0,5.39-6.8.837.837,0,1,1,1.674,0A8.679,8.679,0,0,1,15.087,23.735Z" transform="translate(1.503 1.515)" fill="#fd4949"/>
                                            <path id="Path_4161" data-name="Path 4161" d="M2.087,10.735A.843.843,0,0,1,1.25,9.9,8.663,8.663,0,0,1,9.9,1.25a.833.833,0,0,1,.714,1.261L9.44,4.475A.836.836,0,0,1,8.012,3.6l.3-.5a6.987,6.987,0,0,0-5.39,6.8A.843.843,0,0,1,2.087,10.735Z" fill="#fd4949"/>
                                            <path id="Path_4162" data-name="Path 4162" d="M18.108,12.967a5.858,5.858,0,1,1,5.858-5.858A5.872,5.872,0,0,1,18.108,12.967Zm0-10.043a4.185,4.185,0,1,0,4.185,4.185A4.186,4.186,0,0,0,18.108,2.924Z" transform="translate(1.271)" fill="#fd4949"/>
                                        </g>
                                        </svg>

                                    </div>
                                    <span class="font_14 f_w_500">{{ __('refund.refund_success') }}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_success_refund) }}</h3>
                                </div>
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21.209" height="23.997" viewBox="0 0 21.209 23.997">
                                            <g id="bag-2" transform="translate(-2.496 -1.246)">
                                                <path id="Path_4178" data-name="Path 4178" d="M17.63,9.486a.843.843,0,0,1-.837-.837V7.109a4.218,4.218,0,0,0-1.373-3.1A4.141,4.141,0,0,0,12.2,2.947,4.511,4.511,0,0,0,8.424,7.333V8.415a.837.837,0,0,1-1.674,0V7.321a6.186,6.186,0,0,1,5.289-6.048,5.79,5.79,0,0,1,4.508,1.5,5.881,5.881,0,0,1,1.919,4.341v1.54A.843.843,0,0,1,17.63,9.486Z" transform="translate(0.493)" fill="#fd4949"/>
                                                <path id="Path_4179" data-name="Path 4179" d="M16.449,24.547h-6.7c-5.156,0-6.115-2.4-6.361-4.731l-.837-6.684a5.3,5.3,0,0,1,1-4.229c1-1.116,2.667-1.652,5.077-1.652h8.927c2.422,0,4.084.547,5.077,1.652a5.308,5.308,0,0,1,1,4.207l-.837,6.707C22.564,22.147,21.6,24.547,16.449,24.547ZM8.638,8.924c-1.886,0-3.18.368-3.839,1.1a3.611,3.611,0,0,0-.58,2.913l.837,6.684c.19,1.785.681,3.258,4.7,3.258h6.7c4.017,0,4.508-1.462,4.7-3.236l.837-6.707a3.618,3.618,0,0,0-.58-2.9c-.658-.748-1.953-1.116-3.839-1.116Z" transform="translate(0 0.696)" fill="#fd4949"/>
                                                <path id="Path_4180" data-name="Path 4180" d="M15.537,13.38a1.116,1.116,0,1,1,1.1-1.116A1.11,1.11,0,0,1,15.537,13.38Z" transform="translate(1.381 1.148)" fill="#fd4949"/>
                                                <path id="Path_4181" data-name="Path 4181" d="M8.537,13.38a1.116,1.116,0,1,1,1.1-1.116A1.11,1.11,0,0,1,8.537,13.38Z" transform="translate(0.57 1.148)" fill="#fd4949"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="font_14 f_w_500"> {{__('amazy.Product in Cart')}}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_item_in_carts) }}</h3>
                                </div>
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.924" height="24" viewBox="0 0 22.924 24">
                                            <g id="clipboard" transform="translate(-1.227 -1.254)">
                                                <path id="Path_4151" data-name="Path 4151" d="M14.428,21.53a12.112,12.112,0,0,1-1.932-.167l-5.237-.826a9.058,9.058,0,0,1-3.875-1.384c-2.445-1.7-2.3-4.61-1.988-6.652l.826-5.235c.759-4.8,3.238-6.6,8.039-5.848l5.237.826c2.468.391,5.784,1.384,6,5.547a11.162,11.162,0,0,1-.156,2.489l-.815,5.235C19.877,19.61,17.967,21.53,14.428,21.53ZM8.264,2.924c-2.657,0-3.863,1.351-4.388,4.61L3.05,12.768c-.514,3.293.424,4.409,1.3,5.023a7.349,7.349,0,0,0,3.171,1.094l5.237.826c3.9.614,5.5-.558,6.119-4.465l.815-5.235a9.292,9.292,0,0,0,.134-2.132V7.868c-.123-2.344-1.452-3.493-4.589-3.985l-5.226-.815A11.308,11.308,0,0,0,8.264,2.924Z" transform="translate(0 0)" fill="#fd4949"/>
                                                <path id="Path_4152" data-name="Path 4152" d="M16.06,24.648a10.417,10.417,0,0,1-3.26-.614L7.764,22.36c-2.87-.949-4.422-2.31-4.891-4.3a.83.83,0,0,1,.346-.882.842.842,0,0,1,.949.011,7.3,7.3,0,0,0,3.16,1.094l5.237.826c3.9.614,5.5-.558,6.119-4.464L19.5,9.413a9.292,9.292,0,0,0,.134-2.132.861.861,0,0,1,.391-.748.827.827,0,0,1,.849-.033c2.992,1.6,3.785,4.152,2.49,8.058l-1.675,5.034c-.793,2.366-1.831,3.817-3.283,4.543A5.2,5.2,0,0,1,16.06,24.648Zm-9.993-4.9a9.286,9.286,0,0,0,2.222,1.027l5.036,1.674c1.92.636,3.294.692,4.321.19s1.809-1.652,2.445-3.572l1.675-5.034c.916-2.768.558-4.241-.5-5.235-.022.279-.067.569-.112.882l-.815,5.235c-.759,4.8-3.238,6.6-8.039,5.86l-5.237-.826C6.715,19.882,6.38,19.815,6.067,19.748Z" transform="translate(0.189 0.606)" fill="#fd4949"/>
                                                <path id="Path_4153" data-name="Path 4153" d="M8.529,10.3a2.779,2.779,0,1,1,2.779-2.779A2.787,2.787,0,0,1,8.529,10.3Zm0-3.873a1.1,1.1,0,1,0,1.1,1.1A1.107,1.107,0,0,0,8.529,6.424Z" transform="translate(0.528 0.406)" fill="#fd4949"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="font_14 f_w_500"> {{__('amazy.Coupon Used')}}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_coupon_used) }}</h3>
                                </div>
                                <!-- single_items -->
                                <div class="single_cart_box d-flex align-items-center justify-content-center text-center flex-column">
                                    <div class="icon d-flex align-items-center justify-content-center text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23.996" viewBox="0 0 24 23.996">
                                        <g id="bag-happy" transform="translate(-1.25 -1.254)">
                                            <path id="Path_4182" data-name="Path 4182" d="M12.494,19.081A4.755,4.755,0,0,1,7.75,14.337a.837.837,0,1,1,1.674,0,3.07,3.07,0,1,0,6.14,0,.837.837,0,1,1,1.674,0A4.755,4.755,0,0,1,12.494,19.081Z" transform="translate(0.756 1.424)" fill="#fd4949"/>
                                            <path id="Path_4183" data-name="Path 4183" d="M5.278,6.978a.856.856,0,0,1-.592-.246.842.842,0,0,1,0-1.183L8.738,1.5A.837.837,0,1,1,9.921,2.68L5.869,6.732A.877.877,0,0,1,5.278,6.978Z" transform="translate(0.371 0)" fill="#fd4949"/>
                                            <path id="Path_4184" data-name="Path 4184" d="M19.318,6.978a.828.828,0,0,1-.592-.246L14.674,2.68A.837.837,0,0,1,15.858,1.5L19.91,5.549a.842.842,0,0,1,0,1.183A.856.856,0,0,1,19.318,6.978Z" transform="translate(1.533 0)" fill="#fd4949"/>
                                            <path id="Path_4185" data-name="Path 4185" d="M22.415,11.241H4.32A3.006,3.006,0,0,1,2,10.6,3.245,3.245,0,0,1,1.25,8.171C1.25,5.1,3.494,5.1,4.565,5.1H21.935c1.072,0,3.315,0,3.315,3.07A3.228,3.228,0,0,1,24.5,10.6,2.768,2.768,0,0,1,22.415,11.241ZM4.565,9.567H22.191c.5.011.971.011,1.127-.145.078-.078.246-.346.246-1.25,0-1.261-.313-1.4-1.641-1.4H4.565c-1.328,0-1.641.134-1.641,1.4,0,.9.179,1.172.246,1.25A2.247,2.247,0,0,0,4.3,9.567Z" transform="translate(0 0.447)" fill="#fd4949"/>
                                            <path id="Path_4186" data-name="Path 4186" d="M16.3,24.321H9.571c-4,0-4.889-2.378-5.235-4.443L2.762,10.222a.837.837,0,0,1,1.652-.268L5.988,19.6c.324,1.976.993,3.047,3.583,3.047H16.3c2.869,0,3.193-1,3.561-2.947l1.875-9.767a.836.836,0,0,1,1.641.324L21.5,20.023C21.069,22.289,20.343,24.321,16.3,24.321Z" transform="translate(0.174 0.93)" fill="#fd4949"/>
                                        </g>
                                    </svg>
                                    </div>
                                    <span class="font_14 f_w_500"> {{__('amazy.Completed Order')}}</span>
                                    <h3 class=" font_20 f_w_700 m-0">{{ getNumberTranslate($total_completed_order_count) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="dashboard_white_box bg-white mb_25 amazy_full_height">
                                <div class="dashboard_white_box_header d-flex align-items-center gap_15 pb_10 mb_5">
                                    <h3 class="font_20 f_w_700 mb-0  flex-fill">{{__('amazy.Purchase History')}}</h3>
                                    <a href="{{route('frontend.my_purchase_histories')}}" class="amaz_badge_btn2 text-uppercase text-nowrap">{{__('common.see_all')}}</a>
                                </div>
                                <div class="dashboard_white_box_body">
                                    <div class="table-responsive">
                                        <table class="table amazy_table mb-0">
                                            <thead>
                                                <tr>
                                                <th class="font_14 f_w_700" scope="col">{{__('common.details')}}</th>
                                                <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.amount')}}</th>
                                                <th class="font_14 f_w_700" scope="col">{{__('common.status')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($purchase_histories as $key => $order)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <h4 class="font_16 f_w_700  ">{{__('common.order')}}: {{@$order->order->order_number}}</h4>
                                                            @if(isModuleActive('MultiVendor'))
                                                                <h4 class="font_14 f_w_600  ">{{__('common.package')}}: {{@$order->package_code}}</h4>
                                                            @endif
                                                            <p class="font_14 f_w_500 mb-0 lh-1">{{date(app('general_setting')->dateFormat->format, strtotime($order->created_at))}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $total_price = $order->products->sum('total_price') + $order->shipping_cost + $order->tax_amount;
                                                        @endphp
                                                        <h4 class="font_16 f_w_500 m-0 ">{{single_price($total_price)}}</h4>
                                                    </td>
                                                    <td>
                                                        @if($order->is_cancelled)
                                                            <a class="table_badge_btn style_5 text-nowrap">{{__('common.cancelled')}}</a>
                                                        @elseif($order->delivery_status == 1)
                                                            <a class="table_badge_btn style3 text-nowrap">{{__('common.pending')}}</a>
                                                        @elseif($order->delivery_status == 2)
                                                            <a class="table_badge_btn text-nowrap">{{__('defaultTheme.processing')}}</a>
                                                        @elseif($order->delivery_status == 3)
                                                            <a class="table_badge_btn text-nowrap">{{__('common.shipped')}}</a>
                                                        @elseif($order->delivery_status == 4)
                                                            <a class="table_badge_btn text-nowrap">{{__('amazy.Received')}}</a>
                                                        @elseif($order->delivery_status >= 5)
                                                            <a class="table_badge_btn style4 text-nowrap">{{$order->delivery_process->name}}</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="dashboard_white_box bg-white mb_25 amazy_full_height">
                                <div class="dashboard_white_box_header d-flex align-items-center gap_15 amazy_bb3 pb_10 mb_5">
                                    <h3 class="font_20 f_w_700 mb-0  flex-fill">{{__('customer_panel.my_wishlist')}}</h3>
                                    <a href="{{route('frontend.my-wishlist')}}" class="amaz_badge_btn2 text-uppercase">{{__('common.see_all')}}</a>
                                </div>
                                <div class="dashboard_white_box_body">
                                    <div class="dash_product_lists">

                                        @foreach ($wishlists as $key => $product)
                                            @if($product->type == 'product')
                                                <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="@if(@$product->product->thum_img != null) {{showImage(@$product->product->thum_img)}} @else {{showImage(@$product->product->product->thumbnail_image_source)}} @endif" alt="@if (@$product->product->product_name) {{ \Illuminate\Support\Str::limit(@$product->product->product_name, 28, $end='...') }}  @else {{ \Illuminate\Support\Str::limit(@$product->product->product->product_name, 28, $end='...') }} @endif" title="@if (@$product->product->product_name) {{ \Illuminate\Support\Str::limit(@$product->product->product_name, 28, $end='...') }}  @else {{ \Illuminate\Support\Str::limit(@$product->product->product->product_name, 28, $end='...') }} @endif">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">@if (@$product->product->product_name) {{ \Illuminate\Support\Str::limit(@$product->product->product_name, 28, $end='...') }}  @else {{ \Illuminate\Support\Str::limit(@$product->product->product->product_name, 28, $end='...') }} @endif</h4>
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2">
                                                            @if(getProductwitoutDiscountPrice(@$product->product) != single_price(0)) 
                                                                <span class="discount_prise text-decoration-line-through">{{getProductwitoutDiscountPrice(@$product->product)}}</span> 
                                                            @endif
                                                            <span class="secondary_text">{{getProductDiscountedPrice(@$product->product)}}</span>  
                                                        </p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{route('frontend.gift-card.show',@$product->giftcard->sku)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="{{showImage(@$product->giftcard->thumbnail_image)}}" alt="{{ \Illuminate\Support\Str::limit(@$product->giftcard->name, 28, $end='...') }}" title="{{ \Illuminate\Support\Str::limit(@$product->giftcard->name, 28, $end='...') }}">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">{{ \Illuminate\Support\Str::limit(@$product->giftcard->name, 28, $end='...') }}</h4>
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2">
                                                            @if(getGiftcardwithoutDiscountPrice(@$product->giftcard) != single_price(0)) 
                                                                <span class="discount_prise text-decoration-line-through">{{getGiftcardwithoutDiscountPrice(@$product->giftcard)}}</span> 
                                                            @endif
                                                            <span class="secondary_text">{{getGiftcardwithDiscountPrice(@$product->giftcard)}}</span>  
                                                        </p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- cta::start  -->
                        <div class="col-xl-12 mb_20">
                            <x-random-ads-component/>
                        </div>
                        <!-- cta::end  -->
                        <div class="col-lg-6 ">
                            <div class="dashboard_white_box bg-white mb_25 amazy_full_height">
                                <div class="dashboard_white_box_header d-flex align-items-center gap_15 amazy_bb3 pb_10 mb_5">
                                    <h3 class="font_20 f_w_700 mb-0  flex-fill">{{__('order.recent_order')}}</h3>
                                    <a href="{{url('/my-purchase-orders')}}" class="amaz_badge_btn2 text-uppercase">{{__('common.see_all')}}</a>
                                </div>
                                <div class="dashboard_white_box_body">
                                    <div class="dash_product_lists">
                                        @foreach($recent_order_products as $key => $product)
                                            @if($product->type == 'product')
                                                <a href="{{singleProductURL(@$product->seller_product_sku->product->seller->slug, @$product->seller_product_sku->product->slug)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="
                                                        @if(@$product->seller_product_sku->product->product->product_type == 1)
                                                            {{showImage(@$product->seller_product_sku->product->product->thumbnail_image_source)}}
                                                        @else
                                                            {{showImage(@$product->seller_product_sku->sku->variant_image?@$product->seller_product_sku->sku->variant_image:@$product->seller_product_sku->product->product->thumbnail_image_source)}}
                                                        @endif
                                                        " alt="{{textLimit($product->seller_product_sku->product->product_name,22)}}" title="{{textLimit($product->seller_product_sku->product->product_name,22)}}">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">{{textLimit($product->seller_product_sku->product->product_name,22)}}</h4>
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2"> 
                                                            @if(getProductwitoutDiscountPrice(@$product->seller_product_sku->product) != single_price(0))  
                                                                <span class="discount_prise text-decoration-line-through">{{getProductwitoutDiscountPrice(@$product->seller_product_sku->product)}} </span>
                                                            @endif 
                                                            <span class="secondary_text">{{getProductDiscountedPrice(@$product->seller_product_sku->product)}}</span>  </p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{route('frontend.gift-card.show',@$product->giftcard->sku)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="{{showImage(@$product->giftCard->thumbnail_image)}}" alt="{{textLimit($product->giftCard->name,22)}}" title="{{textLimit($product->giftCard->name,22)}}">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">{{textLimit($product->giftCard->name,22)}}</h4>
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2"> 
                                                            @if(getGiftcardwithoutDiscountPrice(@$product->giftcard) != single_price(0))
                                                                <span class="discount_prise text-decoration-line-through">{{getGiftcardwithoutDiscountPrice(@$product->giftcard)}}</span> 
                                                            @endif
                                                            <span class="secondary_text">{{getGiftcardwithDiscountPrice(@$product->giftcard)}}</span>  </p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="dashboard_white_box bg-white mb_25 amazy_full_height">
                                <div class="dashboard_white_box_header d-flex align-items-center gap_15 amazy_bb3 pb_10 mb_5">
                                    <h3 class="font_20 f_w_700 mb-0  flex-fill">{{__('amazy.Product in Cart')}}</h3>
                                    <a href="{{url('/cart')}}" class="amaz_badge_btn2 text-uppercase">{{__('common.see_all')}}</a>
                                </div>
                                <div class="dashboard_white_box_body">
                                    <div class="dash_product_lists">
                                        @foreach($carts as $key => $cart)
                                            @if($cart->product_type == 'product')
                                                <a href="{{singleProductURL($cart->seller->slug, $cart->product->product->slug)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="
                                                        @if(@$cart->product->product->product->product_type == 1)
                                                            {{showImage(@$cart->product->product->product->thumbnail_image_source)}}
                                                        @else
                                                            {{showImage(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source)}}
                                                        @endif
                                                        " alt="{{textLimit($cart->product->product->product_name,28)}}" title="{{textLimit($cart->product->product->product_name,28)}}">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">{{textLimit($cart->product->product->product_name,28)}}</h4>
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2">
                                                            @if(getProductwitoutDiscountPrice(@$cart->product->product) != single_price(0))  
                                                                <span class="discount_prise text-decoration-line-through">{{getProductwitoutDiscountPrice(@$cart->product->product)}} </span>
                                                            @endif 
                                                            <span class="secondary_text">{{single_price($cart->price)}}</span>  </p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}" class="dashboard_order_list d-flex align-items-center flex-wrap  gap_20">
                                                    <div class="thumb">
                                                        <img class="img-fluid" src="{{showImage(@$cart->giftCard->thumbnail_image)}}" alt="{{textLimit(@$cart->giftCard->name, 28)}}" title="{{textLimit(@$cart->giftCard->name, 28)}}">
                                                    </div>
                                                    <div class="dashboard_order_content">
                                                        <h4 class="font_16 f_w_700 mb-1 lh-base theme_hover">{{textLimit(@$cart->giftCard->name, 28)}}</h4> 
                                                        <p class="font_14 f_w_500 d-flex align-items-center gap-2">
                                                            @if(getGiftcardwithoutDiscountPrice(@$cart->giftCard) != single_price(0))  
                                                                <span class="discount_prise text-decoration-line-through">{{getGiftcardwithoutDiscountPrice(@$cart->giftCard)}}</span> 
                                                            @endif
                                                            <span class="secondary_text">{{single_price($cart->price)}}</span>  </p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection