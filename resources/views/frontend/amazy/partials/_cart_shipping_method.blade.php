
<div class="modal fade theme_modal2 login_modal" id="shipping_methods_{{$package['seller_id']}}" tabindex="-1" role="dialog" aria-labelledby="shiping_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div data-bs-dismiss="modal" class="close_modal">
                    <i class="ti-close"></i>
                </div>
                <div class="row mt_60 mb_20">
                    <div id="list_div" class="col-md-12">
                        <div class="address_list_div" id="address_list_div">
                            <table class="table table-hover tablesaw tablesaw-stack text-center shipping_modal_table">
                                <thead>
                                    <tr class="">
                                        <th>{{__('Estimated Delivery')}}</th>
                                        <th>{{__('Cost')}}</th>
                                        <th>{{__('Carrier')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="cart_table_body">
                                @if($is_physical_count > 0)
                                    @foreach($shipping_methods as $key => $shipping)
                                        @if($shipping->id == 1)
                                            @continue
                                        @endif
                                        @php
                                            $cost = 0;
                                            if($shipping->cost_based_on == 'Price'){
                                                if($package['totalItemPrice'] > 0 && $shipping->cost > 0){
                                                    $cost = ($package['totalItemPrice'] / 100) * $shipping->cost + $package['additional_cost'];
                                                }

                                            }elseif ($shipping->cost_based_on == 'Weight'){
                                                 if($package['totalItemWeight'] > 0 && $shipping->cost > 0){
                                                    $cost = ($package['totalItemWeight'] / 100) * $shipping->cost + $package['additional_cost'];
                                                }
                                            }else{
                                                if($shipping->cost > 0){
                                                    if(sellerWiseShippingConfig($package['seller_id'])['amount_multiply_with_qty']){
                                                        $cost = ($shipping->cost * $package['item_incart']) + $package['additional_cost'];
                                                    }else{
                                                        $cost = $shipping->cost + $package['additional_cost'];
                                                    }
                                                }else{
                                                    $cost = 0;
                                                }
                                            }
                                        @endphp
                                        <tr class="custom_tr">
                                            <td>
                                                <div class="d-flex align-items-center flex-wrap gap_15">
                                                <label class="primary_checkbox d-inline-flex style4">
                                                    <input value="{{$shipping->id}}" {{$package['shipping_id'] == $shipping->id ? 'checked':'' }} name="shipping_method_{{$package['seller_id']}}" class="shipping_input_data shipping_method_select" data-package="{{$package['seller_id']}}" type="radio">
                                                    <span class="checkmark"></span>
                                                </label>

                                                <p class="f_s_14 f_w_400 m-0  selected_shipping_text">{{__('shipping.estimated_delivery_time')}}: {{$shipping->shipment_time}}</p>
                                                </div>
                                            </td>
                                            <td><p class=" selected_shipping_text">{{__('common.shipping')}}: {{single_price($cost)}}</p></td>
                                            <td><p class=" selected_shipping_text">{{sellerWiseShippingConfig(1)['carrier_show_for_customer'] == 1?$shipping->carrier->name .'->':''}} {{$shipping->method_name}}</p></td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

