<!-- wallet_modal::start  -->
<div class="modal fade theme_modal2" id="Address_edit_modal" tabindex="-1" role="dialog" aria-labelledby="theme_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <form action="#" method="POST" id="address_form_edit">
                    @csrf
                    <input type="hidden" name="address_id" id="address_id" value="{{$address->id}}">
                    <div class="payment_modal_wallet style2">
                        <div class="d-flex align-items-center gap_10 mb_30">
                            <h3 class="font_24 f_w_700  flex-fill mb-0">{{__('common.update')}} {{__('common.address')}}</h3>
                            <button type="button" class="close_modal_icon" data-bs-dismiss="modal">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                        
                        <label class="primary_label2 style4 mb_15">{{__('common.type')}}</label>
                        <div class="address_type d-flex align-items-center gap_30 flex-wrap mb_30">
                            <label class="primary_checkbox style6 d-flex" >
                                <input type="checkbox" name="shipping_address" {{$address->is_shipping_default?'checked disabled':''}} value="1">
                                <span class="checkmark mr_10"></span>
                                <span class="label_name f_w_500">{{__('common.shipping')}} {{__('common.address')}}</span>
                            </label>
                            <label class="primary_checkbox style6 d-flex" >
                                <input type="checkbox" name="billing_address" {{$address->is_billing_default?'checked disabled':''}} value="1">
                                <span class="checkmark mr_10"></span>
                                <span class="label_name f_w_500">{{__('common.billing_address')}}</span>
                            </label>
                        </div>
                        <form action="#">
                            <div class="row">
                                <div class="col-12 mb_25">
                                    <label class="primary_label2 style4" for="address_name_edit">{{ __('common.name') }} <span>*</span></label>
                                    <input name="name" id="address_name_edit" placeholder="{{ __('common.name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.name') }}'" class="primary_input3 radius_3px style5" type="text" value="{{$address->name}}">
                                    <span class="text-danger" id="error_name"></span>
                                </div>
                                <div class="col-6 mb_25">
                                    <label class="primary_label2 style4" for="Email_Address_edit">{{ __('common.email_address') }} <span>*</span></label>
                                    <input name="email" id="Email_Address_edit" placeholder="{{ __('common.email_address') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email_address') }}'" class="primary_input3 radius_3px style5" type="email" value="{{$address->email}}">
                                    <span class="text-danger" id="error_email"></span>
                                </div>
                                <div class="col-6 mb_25">
                                    <label class="primary_label2 style4" for="customer_phn_edit">{{ __('common.phone_number') }} <span>*</span></label>
                                    <input name="phone" id="customer_phn_edit" placeholder="{{ __('common.phone_number') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.phone_number') }}'" class="primary_input3 radius_3px style5" type="text" value="{{$address->phone}}">
                                    <span class="text-danger" id="error_phone"></span>
                                </div>
                                <div class="col-xl-6 mb_25">
                                    <div class="form-group input_div_mb">
                                        <label class="primary_label2 style4">{{ __('common.country') }} <span>*</span></label>
                                        <select class="theme_select style2 wide" name="country" id="country_edit" autocomplete="off">
                                        <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                        @foreach($countries as $key => $country)
                                        <option {{$address->country == $country->id?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <span class="text-danger" id="error_country"></span>
                                </div>
                                <div class="col-xl-6 mb_25">
                                    <div class="form-group input_div_mb">
                                        <label class="primary_label2 style4 ">{{ __('common.state') }} <span>*</span></label>
                                        <select class="theme_select style2 wide" name="state" id="state_edit" autocomplete="off">
                                            <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                            @if($address->getCountry)
                                                @foreach($address->getCountry->states as $key => $state)
                                                <option {{$address->state == $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <span class="text-danger" id="error_state"></span>
                                </div>
                                <div class="col-xl-6 mb_25">
                                    <div class="form-group input_div_mb">
                                        <label class="primary_label2 style4">{{ __('common.city') }} <span>*</span></label>
                                        <select class="theme_select style2 wide" name="city" id="city_edit" autocomplete="off">
                                           <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                           @if($address->getState)
                                               @foreach($address->getState->cities as $key => $city)
                                               <option {{$address->city == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                               @endforeach
                                           @endif
                                        </select>
                                     </div>
                                     <span class="text-danger" id="error_city"></span>
                                </div>
                                <div class="col-xl-6 mb_25">
                                    <label class="primary_label2 style4" for="postal_code_edit">{{ __('common.postal_code') }}</label>
                                    <input name="postal_code" id="postal_code_edit" placeholder="{{ __('common.postal_code') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.postal_code') }}'" class="primary_input3 radius_3px style5" value="{{$address->postal_code}}" type="text">
                                </div>
                                <div class="col-12 mb_25">
                                    <label class="primary_label2 style4" for="address_edit">{{__('common.Street Address')}} <span>*</span></label>
                                    <textarea name="address" id="address_edit" placeholder="{{__('common.Street Address')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.Street Address')}}'" class="primary_textarea4 radius_5px mb_25">{{$address->address}}</textarea>
                                    <span class="text-danger" id="error_address"></span>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="amaz_primary_btn style2 radius_5px w-100 text-center  text-uppercase  text-center min_200">{{__('common.update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- wallet_modal::end  -->
