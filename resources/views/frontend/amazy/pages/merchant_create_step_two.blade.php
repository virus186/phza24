@extends('frontend.amazy.auth.layouts.app')

@section('title')
    {{ __('Merchant Register') }}
@endsection
@section('content')
    <div class="amazy_login_area">
        @php
            $loginPageInfo = \Modules\FrontendCMS\Entities\LoginPage::findOrFail(3);
        @endphp
        <div class="amazy_login_area_left d-flex align-items-center justify-content-center">
            <div class="amazy_login_form">
                <a href="{{url('/')}}" class="logo mb_50 d-block">
                    <img src="{{showImage(app('general_setting')->logo)}}" alt="{{app('general_setting')->company_name}}" title="{{app('general_setting')->company_name}}">
                </a>
                <h3 class="m-0">{{ __('common.welcome') }}! {{ __('common.please') }} <br>{{ __('defaultTheme.create_your_merchant_account') }}</h3>
                <p class="support_text">{{__('auth.See your growth and get consulting support!')}}</p>
                <form id="registerForm" action="{{route('frontend.merchant.store')}}" method="POST" class="register_form">
                    @csrf
                    <div class="row">
                        @php
                            $custom_field = [];
                            $default_field = [];
                        @endphp
                        @if(!empty($row) && !empty($form_data))
                            @foreach($form_data as $row)
                                @php
                                    if($row->type != 'header' && $row->type !='paragraph'){
                                        if(property_exists($row,'className') && strpos($row->className, 'default-field') !== false){
                                            $default_field[] = $row->name;
                                        }else{
                                            $custom_field[] = $row->name;
                                        }
                                        $required = property_exists($row,'required');
                                        $type = property_exists($row,'subtype') ? $row->subtype : $row->type;
                                        $placeholder = property_exists($row,'placeholder') ? $row->placeholder : $row->label;
                                    }
                                @endphp
                                @if($row->type =='header' || $row->type =='paragraph')
                                    <div class="col-lg-12">
                                        <{{ $row->subtype }}>{{ $row->label }} </{{ $row->subtype }}>
                                    </div>
                                @elseif($row->type == 'text' || $row->type == 'number' || $row->type == 'email' || $row->type == 'date')
                                    <div class="col-xl-12">
                                        <label class="primary_label2" for="{{$row->name}}">{{$row->label}} @if($required) <span>*</span> @endif </label>
                                        <input {{$required ? 'required' :''}} type="{{$type}}" id="{{$row->name}}" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{$placeholder}}" class="@error($row->name) is-invalid @enderror">
                                        @error($row->name)
                                            <span class="text-danger" >{{ $message }}</span>
                                        @enderror
                                    </div>
                                @elseif($row->type=='select')
                                    @if($row->name == 'account_type')
                                        @if (session()->has('pricing_id'))
                                            <div class="col-xl-12 mb_25">
                                                <div class="form-group input_div_mb">
                                                    <label class="primary_label2 style4" for={{$row->name}}>{{$row->label}} @if($required) <span>*</span> @endif</label>
                                                    <select class="primary_input3 radius_3px style6 select_box" {{$required ? 'required' :''}} name="subscription_type" id="{{$row->name}}" autocomplete="off">
                                                        @foreach($row->values as $value)
                                                            <option value="{{$value->value}}" {{old($row->name) == $value->value? 'selected': ''}}>{{$value->label}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="text-danger">{{$errors->first($row->name)}}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-xl-12 mb_25">
                                            <div class="form-group input_div_mb">
                                                <label class="primary_label2 style4" for={{$row->name}}>{{$row->label}}@if($required) <span>*</span> @endif</label>
                                                <select class="primary_input3 radius_3px style6 select_box" {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" autocomplete="off">
                                                    @foreach($row->values as $value)
                                                        <option value="{{$value->value}}" {{old($row->name) == $value->value? 'selected': ''}}>{{$value->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger">{{$errors->first($row->name)}}</span>
                                        </div>
                                    @endif
                                @elseif($row->type == 'date')
                                    <div class="col-12 mb_30">
                                        <label class="primary_label2 style2 " for="start_datepicker">{{$row->label}} @if($required) <span>*</span> @endif</label>
                                        <input id="start_datepicker" {{$required ? 'required' :''}} type="{{$type}}" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{$placeholder}}" class="primary_input3 style4 mb-0 @error($row->name) is-invalid @enderror">
                                        @error($row->name)
                                        <span class="text-danger" >{{ $message }}</span>
                                        @enderror
                                    </div>
                                @elseif($row->type=='textarea')
                                    <div class="col-12 mb_25">
                                        <label class="primary_label2 style2 " for={{$row->name}}>{{$row->label}}@if($required) <span>*</span> @endif</label>
                                        <textarea {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}"  placeholder="{{$placeholder}}" class="primary_textarea4 radius_5px">{{old($row->name)}}</textarea>
                                    </div>
                                    @elseif($row->type=="radio-group")
                                    <div class="col-lg-12 form-group  mt-10 mb-10">
                                        <label for="">{{ $row->label }}</label>
                                        <div class="d-flex radio-btn-flex">
                                            @foreach($row->values as $value)
                                                <label class="primary_bulet_checkbox mr-10">
                                                    <input type="radio" name="{{ $row->name }}" value="{{ $value->value }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <span class="mr-10">{{ $value->label }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($row->type=="checkbox-group")
                                    <div class="col-lg-12 form-group mt-10 mb-10">
                                        <label>{{@$row->label}}</label>
                                        <div class="checkbox">
                                            @foreach($row->values as $value)
                                                <label class="cs_checkbox mr-10">
                                                    <input  type="checkbox" name="{{ $row->name }}[]" value="{{ $value->value }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <p class="mr-10">{{$value->label}}</p>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif($row->type =='file')

                                    <div class="col-lg-6">
                                        <div class="customer_img">
                                            <label for={{$row->name}}>{{$row->label}}@if($required) <span class="text-danger">*</span> @endif</label>
                                            <div class="form-group">
                                                <input type="{{$type}}" name="{{$row->name}}" id="{{$row->name}}" >
                                            </div>
                                        </div>
                                    </div>
                                @elseif($row->type =='checkbox')
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label class="cs_checkbox">
                                                <input id="policyCheck" type="checkbox" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{!! $row->label !!}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @if (session()->has('pricing_id'))
                                <div class="col-xl-12 mb_25">
                                    <div class="form-group input_div_mb">
                                        <label class="primary_label2 style4" for={{$row->name}}>{{ __('Account Type') }} <span>*</span></label>
                                        <select class="primary_input3 radius_3px style6 select_box" name="subscription_type" autocomplete="off" disabled>
                                            @foreach ($pricing_plans as $pricing_plan)
                                                <option value="{{ $pricing_plan->id }}" @if (session()->get('pricing_id') == $pricing_plan->id) selected @endif>{{ $pricing_plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="text-danger">{{$errors->first($row->name)}}</span>
                                </div>
                            @endif
                            <div class="col-lg-12 mb_20">
                                <label class="primary_label2">{{ __('common.shop_name') }} <span>*</span></label>
                                <input type="text" name="name" id="Shop" value="{{old('name')}}" placeholder="{{ __('common.shop_name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.shop_name') }}'" class="primary_input3 radius_5px">
                                @error('name')
                                    <span class="text-danger" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb_20">
                                <label class="primary_label2">{{ __('common.email_address') }} <span>*</span></label>
                                <input type="email" id="email" name="email" value="{{old('email')}}" placeholder="{{ __('common.email_address') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email_address') }}'" class="primary_input3 radius_5px">
                                @error('email')
                                    <span class="text-danger" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb_20">
                                <label class="primary_label2">{{ __('common.phone_number') }} <span>*</span></label>
                                <input type="text" id="phone" name="phone" value="{{old('phone')}}" placeholder="{{ __('common.phone_number') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.phone_number') }}'" class="primary_input3 radius_5px">
                                @error('phone')
                                    <span class="text-danger" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb_20">
                                <label class="primary_label2" for="password">{{ __('common.password') }} <span>*</span></label>
                                <input type="password" id="password" name="password" value="{{old('password')}}" placeholder="{{ __('common.password') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.password') }}'" class="primary_input3 radius_5px">
                                @error('password')
                                    <span class="text-danger" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb_20">
                                <label class="primary_label2" for="re_password">{{ __('common.confirm_password') }} <span>*</span></label>
                                <input type="password" id="re_password" name="password_confirmation" placeholder="{{ __('common.confirm_password') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.confirm_password') }}'" class="primary_input3 radius_5px">
                            </div>

                            <div class="col-12 mb_20">
                                <label class="primary_checkbox d-flex">
                                    <input checked="" type="checkbox" id="termCheck" checked value="1">
                                    <span class="checkmark mr_15"></span>
                                    <span class="label_name f_w_400 ">{{ __('defaultTheme.by_signing_up_you_agree_to_terms_of_service_and_privacy_policy') }}</span>
                                </label>
                            </div>
                            
                        @endif
                        @if(env('NOCAPTCHA_FOR_REG') == "true")
                        <div class="col-12 mb_20">
                            @if(env('NOCAPTCHA_INVISIBLE') != "true")
                            <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                            @endif
                            <span class="text-danger" >{{ $errors->first('g-recaptcha-response') }}</span>
                        </div>
                        @endif
                        <div class="col-12">
                            @if(env('NOCAPTCHA_INVISIBLE') == "true")
                        <button type="button" class="g-recaptcha amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{ __('defaultTheme.register') }}</button>
                        @else
                        <button type="submit" class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25"  id="submitBtn">{{ __('defaultTheme.register') }}</button>
                        @endif
                        </div>
                        <div class="col-12">
                            <p class="sign_up_text">{{ __('defaultTheme.already_a_merchant') }}  <a href="{{route('seller.login')}}">{{__('auth.Sign in')}}</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="amazy_login_area_right d-flex align-items-center justify-content-center">
            <div class="amazy_login_area_right_inner d-flex align-items-center justify-content-center flex-column">
                <div class="thumb">
                    <img class="img-fluid" src="{{ showImage($loginPageInfo->cover_img) }}" alt="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}" title="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}">
                </div>
                <div class="login_text d-flex align-items-center justify-content-center flex-column text-center">
                    <h4>{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}</h4>
                    <p class="m-0">{{ isset($loginPageInfo->sub_title)? $loginPageInfo->sub_title:'' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("registerForm").submit();
    }
</script>
<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $(document).on('click','#termCheck',function(event){

                if($("#termCheck").prop('checked') == true){
                    //do something
                    $('#submitBtn').prop('disabled', false);
                }else{
                    $('#submitBtn').prop('disabled', true);
                }
            });

            $('.select_box').niceSelect();
        });
    })(jQuery);
</script>
@endpush
