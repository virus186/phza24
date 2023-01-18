<div class="modal fade admin-query" id="customer_address_edit_modal">
    <div class="modal-dialog modal_1000px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> {{__('shipping.customer_address_update')}}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="address_form">
                    <div class="row">

                        @if ($order->order->customer_id)
                            
                            <input type="hidden" name="customer_address_id" value="{{$order->order->address->id}}">
                            <div class="col-md-6 col-lg-6">
                                <table class="table-borderless clone_line_table">
                                    <tr>
                                        <td><strong>{{__('defaultTheme.shipping_info')}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.name')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_name" type="text" value="{{@$order->order->address->shipping_name}}">
                                            <span class="text-danger" id="error_customer_shipping_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.email')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_email" type="text" value="{{ @$order->order->address->shipping_email }}">
                                            <span class="text-danger" id="error_customer_shipping_email"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.phone')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_phone" type="text" value="{{@$order->order->address->shipping_phone}}">
                                            <span class="text-danger" id="error_customer_shipping_phone"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.address')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_address" type="text" value="{{@$order->order->address->shipping_address}}">
                                            <span class="text-danger" id="error_customer_shipping_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.postal_code')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_postcode" type="text" value="{{$order->order->address->shipping_postcode}}">
                                            <span class="text-danger" id="error_customer_shipping_post_code"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.country')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_country" id="s_business_country" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($countries as $country)
                                                    <option {{@$order->order->address->shipping_country_id == $country->id?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_country"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.state')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_state" id="s_business_state" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($s_states as $key => $state)
                                                    <option {{@$order->order->address->shipping_state_id == $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach

                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_state"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{__('common.city')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_city" id="s_business_city" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($s_cities as $key => $city)
                                                    <option {{@$order->order->address->shipping_city_id == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_city"></span>
                                        </td>
                                    </tr>


                                </table>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <table class="table-borderless clone_line_table">
                                    <tr>
                                        <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.name')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="billing_name" type="text" value="{{@$order->order->address->billing_name}}">
                                            <span class="text-danger" id="error_customer_billing_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.email')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="billing_email" type="text" value="{{ @$order->order->address->billing_email }}">
                                            <span class="text-danger" id="error_customer_billing_email"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.phone')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="billing_phone" type="text" value="{{@$order->order->address->billing_phone}}">
                                            <span class="text-danger" id="error_customer_billing_phone"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.address')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="billing_address" type="text" value="{{@$order->order->address->billing_address}}">
                                            <span class="text-danger" id="error_customer_billing_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.postal_code')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="billing_postcode" type="text" value="{{@$order->order->address->billing_postcode}}">
                                            <span class="text-danger" id="error_customer_billing_post_code"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.country')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="billing_country" id="b_business_country" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($countries as $country)
                                                    <option {{@$order->order->address->billing_country_id == $country->id?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_billing_country"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.state')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="billing_state" id="b_business_state" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($b_states as $key => $state)
                                                    <option {{@$order->order->address->billing_state_id == $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_billing_state"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{__('common.city')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="billing_city" id="b_business_city" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($b_cities as $key => $city)
                                                    <option {{@$order->order->address->billing_city_id == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_billing_city"></span>
                                        </td>
                                    </tr>


                                </table>
                            </div>

                        @else
                            <input type="hidden" value="{{$order->order->guest_info->id}}" name="guest_address_id">
                            <div class="col-md-6 col-lg-6">
                                    <table class="table-borderless clone_line_table">
                                        <tr>
                                            <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.name')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <input class="primary_input_field" name="billing_name" type="text" value="{{$order->order->guest_info->billing_name}}">
                                                <span class="text-danger" id="error_customer_billing_name"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>{{__('common.email')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <input class="primary_input_field" name="billing_email" type="text" value="{{ $order->order->guest_info->billing_email}}">
                                                <span class="text-danger" id="error_customer_billing_email"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.phone')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <input class="primary_input_field" name="billing_phone" type="text" value="{{$order->order->guest_info->billing_phone}}">
                                                <span class="text-danger" id="error_customer_billing_phone"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.address')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <input class="primary_input_field" name="billing_address" type="text" value="{{$order->order->guest_info->billing_address}}">
                                                <span class="text-danger" id="error_customer_billing_address"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.postal_code')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <input class="primary_input_field" name="billing_postcode" type="text" value="{{$order->order->guest_info->billing_post_code}}">
                                                <span class="text-danger" id="error_customer_billing_post_code"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('common.country')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="billing_country" id="b_business_country" class="primary_select">
                                                    <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                    @foreach($countries as $country)
                                                        <option {{$order->order->guest_info->billing_country_id == $country->id?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="error_customer_billing_country"></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{__('common.state')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="billing_state" id="b_business_state" class="primary_select">
                                                    <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                    @foreach($b_states as $key => $state)
                                                        <option {{$order->order->guest_info->billing_state_id == $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="error_customer_billing_state"></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{__('common.city')}} <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="billing_city" id="b_business_city" class="primary_select">
                                                    <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                    @foreach($b_cities as $key => $city)
                                                        <option {{$order->order->guest_info->billing_city_id == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="error_customer_billing_city"></span>
                                            </td>
                                        </tr>


                                    </table>
                                </div>

                            <div class="col-md-6 col-lg-6">
                                <table class="table-borderless clone_line_table">
                                    <tr>
                                        <td><strong>{{__('defaultTheme.shipping_info')}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.name')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_name" type="text" value="{{$order->order->guest_info->shipping_name}}">
                                            <span class="text-danger" id="error_customer_shipping_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.email')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_email" type="text" value="{{ $order->order->guest_info->shipping_email}}">
                                            <span class="text-danger" id="error_customer_shipping_email"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.phone')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_phone" type="text" value="{{$order->order->guest_info->shipping_phone}}">
                                            <span class="text-danger" id="error_customer_shipping_phone"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.address')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_address" type="text" value="{{$order->order->guest_info->shipping_address}}">
                                            <span class="text-danger" id="error_customer_shipping_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.postal_code')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <input class="primary_input_field" name="shipping_postcode" type="text" value="{{$order->order->guest_info->shipping_post_code}}">
                                            <span class="text-danger" id="error_customer_shipping_post_code"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('common.country')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_country" id="s_business_country" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($countries as $country)
                                                    <option {{$order->order->guest_info->shipping_country_id == $country->id?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_country"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{__('common.state')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_state" id="s_business_state" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($s_states as $key => $state)
                                                    <option {{$order->order->guest_info->shipping_state_id == $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach

                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_state"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{__('common.city')}} <span class="text-danger">*</span></td>
                                        <td>
                                            <select name="shipping_city" id="s_business_city" class="primary_select">
                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                @foreach($s_cities as $key => $city)
                                                    <option {{$order->order->guest_info->shipping_city_id == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="error_customer_shipping_city"></span>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        @endif
                        <div class="col-lg-12 text-center mt-30">
                            <div class="d-flex justify-content-center">
                                <button class="primary-btn semi_large2  fix-gr-bg mr-10"  type="submit"><i class="ti-check"></i>{{__('common.submit')}}</button>
                                <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent" data-dismiss="modal" type="button"><i class="ti-check"></i>{{__('common.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
