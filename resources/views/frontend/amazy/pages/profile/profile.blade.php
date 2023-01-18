@extends('frontend.amazy.layouts.app')
@push('styles')
    <style>
        .pac-container {
            z-index: 10000 !important;
        }
    </style>
@endpush
@section('content')

<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="profile_white_box bg-white">
                    <ul class="nav profile_tabs mb_40" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="Info-tab" data-bs-toggle="tab" data-bs-target="#Info" type="button" role="tab" aria-controls="Info" aria-selected="true">{{__('common.basic_info') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="Password-tab" data-bs-toggle="tab" data-bs-target="#Password" type="button" role="tab" aria-controls="Password" aria-selected="false">{{__('common.change_password') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="Address-tab" data-bs-toggle="tab" data-bs-target="#Address" type="button" role="tab" aria-controls="Address" aria-selected="false">{{__('common.address') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="language-tab" data-bs-toggle="tab" data-bs-target="#Language" type="button" role="tab" aria-controls="Language" aria-selected="false">{{__('common.language') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Info" role="tabpanel" aria-labelledby="Info-tab">
                            <!-- content ::start  -->
                            <div class="dashboard_account_wrapper mb_20">
                                <!-- form  -->
                                <form action="#" name="basic_info" method="POST" id="basic_info" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb_30">
                                            <label class="primary_label2 style2 ">{{__('common.first_name')}} <span>*</span></label>
                                            <input name="first_name" id="first_name" placeholder="{{__('common.first_name')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.first_name')}}'" class="primary_input3 style4" type="text" value="{{$user_info->first_name}}">
                                            <span class="text-danger info_error" id="error_first_name"></span>
                                        </div>
                                        <div class="col-12 mb_30">
                                            <label class="primary_label2 style2 ">{{__('common.last_name')}}</label>
                                            <input name="last_name" placeholder="{{__('common.last_name')}}" id="last_name" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.last_name')}}'" class="primary_input3 style4" type="text" value="{{$user_info->last_name}}">
                                        </div>
                                        <div class="col-12 mb_30">
                                            <label class="primary_label2 style2 ">{{__('common.email_address')}} <span>*</span></label>
                                            <input name="email" id="email" placeholder="{{__('common.email_address')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.email_address')}}'" class="primary_input3 style4" type="email" value="{{$user_info->email}}">
                                            <span class="text-danger info_error" id="error_email"></span>
                                        </div>
                                        <div class="col-12 mb_30">
                                            <label class="primary_label2 style2 ">{{__('common.phone_number')}}</label>
                                            <input name="phone" id="phone" placeholder="01XX-XXX-XXX" onfocus="this.placeholder = ''" onblur="this.placeholder = '01XX-XXX-XXX'" class="primary_input3 style4" type="text" value="{{$user_info->phone}}">
                                            <span class="text-danger" id="error_phone"></span>
                                        </div>
                                        <div class="col-12 mb_30">
                                            <label class="primary_label2 style2 ">{{__('common.date_of_birth')}}</label>
                                            <input id="start_datepicker" name="date_of_birth" placeholder="06/23/1995" onfocus="this.placeholder = ''" onblur="this.placeholder = '06/23/1995'" class="primary_input3 style4 mb-0" value="{{date('m/d/Y',strtotime($user_info->date_of_birth))}}" type="text">
                                            <span class="text-danger" id="error_date_of_birth"></span>
                                        </div>
                                        <div class="col-12 mb_25">
                                            <label class="primary_label2 style2 ">{{__('common.description')}}</label>
                                            <textarea id="textarea" name="name" placeholder="{{__('common.description')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.description')}}'" class="primary_textarea4 radius_5px">{{$user_info->description}}</textarea>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center min_200" id="update_info">{{__('common.update_now')}}</button>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form  -->
                                <div class="account_img_upload">
                                    <div class="thumb mb_20">
                                        <img class="img-fluid" src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}" alt="{{@$user_info->first_name}} {{@$user_info->last_name}}" title="{{@$user_info->first_name}} {{@$user_info->last_name}}" id="avaterShow">
                                    </div>
                                    <div class="theme_img_uploader mb_13 d-flex align-items-center gap_20">
                                        <span class="img_upload_btn position-relative">
                                            <input class="form-control position-absolute start-0 top-0 bottom-0 end-0 profile_image" type="file" name="file" id="file" accept="image/*">
                                            <span class="font_14 f_w_500 ">{{__('common.choose_file')}}</span>
                                        </span>
                                        <p class="font_14 f_w_400 mute_text" id="file_name_text">{{__('common.no_file_chosen')}}</p>
                                    </div>
                                    <p class="font_14 f_w_400 m-0 lh-1">(200x200)PX</p>
                                </div>
                            </div>
                            
                            <!-- content ::end  -->
                        </div>
                        <div class="tab-pane fade " id="Password" role="tabpanel" aria-labelledby="Password-tab">
                            <!-- content ::start  -->
                            <form action="#" name="basic_info" method="POST" id="update_pass">
                                <div class="row">
                                    <div class="col-12 mb_30">
                                        <label class="primary_label">{{__('common.current')}} {{__('common.password')}}</label>
                                        <input name="current_password" id="currentPassword" placeholder="{{__('common.current')}} {{__('common.password')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.current')}} {{__('common.password')}}'" class="primary_input3 style3" type="password">
                                        <span class="validation-old-pass-error text-danger error" ></span>
                                    </div>
                                    <div class="col-12 mb_30">
                                        <label class="primary_label">{{__('common.new')}} {{__('common.password')}}</label>
                                        <input name="name" placeholder="{{__('common.new')}} {{__('common.password')}}" id="newPass" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.new')}} {{__('common.password')}}'" class="primary_input3 style3" type="password">
                                        <span class="validation-new-pass-error text-danger error"></span>
                                    </div>
                                    <div class="col-12 mb_30">
                                        <label class="primary_label">{{__('common.re_enter')}} {{__('common.new')}} {{__('common.password')}}</label>
                                        <input name="new_password_confirmation" id="rePass" placeholder="{{__('common.re_enter')}} {{__('common.new')}} {{__('common.password')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.re_enter')}} {{__('common.new')}} {{__('common.password')}}'" class="primary_input3 style3" type="password">
                                        <span class="validation-new-pass-confirm-error text-danger error"></span>
                                    </div>
                                    <div class="col-12">
                                        <button class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center min_200 change_password">{{__('common.update_now')}}</button>
                                    </div>
                                </div>
                            </form>
                            <!-- content ::end  -->
                        </div>
                        <div class="tab-pane fade " id="Address" role="tabpanel" aria-labelledby="Address-tab">
                            <!-- content ::start  -->
                            <div class="table-responsive mb_30">
                                <table class="table amazy_table style6 mb-0" id="address_table">
                                    @include('frontend.amazy.pages.profile.partials._table')
                                </table>
                            </div>
                            <a href="#" class="add_new_address amaz_primary_btn style2 rounded-0 text-uppercase text-center min_200">{{__('common.add_new_address')}}</a>
                            <!-- content ::end  -->
                        </div>
                        <div class="tab-pane fade " id="Language" role="tabpanel" aria-labelledby="language-tab">
                            @php
                                $langs = app('langs');
                                $currencies = app('currencies');
                                $locale = app('general_setting')->language_code;
                                $currency_code = app('general_setting')->currency_code;
                                $ship = app('general_setting')->country_name;
                                if(\Session::has('locale')){
                                    $locale = \Session::get('locale');
                                }
                                if(\Session::has('currency')){
                                    $currency = \Modules\GeneralSetting\Entities\Currency::where('id', session()->get('currency'))->first();
                                    $currency_code = $currency->code;
                                }

                                if(auth()->check()){
                                    $currency_code = auth()->user()->currency_code;
                                    $locale = auth()->user()->lang_code;
                                }
                            @endphp
                            <!-- content ::start  -->
                            <form action="{{route('frontend.locale')}}" name="basic_info" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mb_30">
                                        <div class="form-group input_div_mb">
                                            <label class="primary_label2 style4">{{ __('defaultTheme.language') }} <span>*</span></label>
                                            <select class="theme_select style2 wide" name="lang" autocomplete="off">
                                                <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                @foreach($langs as $key => $lang)
                                                    <option {{ $locale == $lang->code?'selected':'' }} value="{{$lang->code}}">
                                                    {{$lang->native}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger" id="error_country"></span>
                                    </div>
                                    <div class="col-12 mb_30">
                                        <div class="form-group input_div_mb">
                                            <label class="primary_label2 style4">{{ __('defaultTheme.currency') }} <span>*</span></label>
                                            <select class="theme_select style2 wide" name="currency" autocomplete="off">
                                                <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                @foreach($currencies as $key => $item)
                                                <option {{$currency_code==$item->code?'selected':'' }}
                                                    value="{{$item->id}}">
                                                    {{$item->name}} ({{$item->symbol}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger" id="error_country"></span>
                                    </div>
                                    <div class="col-12">
                                        <button class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center min_200">{{ __('defaultTheme.save_change') }}</button>
                                    </div>
                                </div>
                            </form>
                            <!-- content ::end  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.amazy.partials._delete_modal_for_ajax',['item_name' => __('common.address'),'form_id' => 'adrs_delete_form','modal_id' => 'adrs_delete_modal'])
</div>
@include('frontend.amazy.pages.profile.partials._form')
<div id="address_form_div_edit"></div>
@endsection

@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";

            $(document).ready(function(){

                $(document).on('click','#update_info',function(e){
                    e.preventDefault();
                    $('#pre-loader').show();
                    var formElement = $('#basic_info').serializeArray();
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");

                    let avatar = $('#file')[0].files[0];
                    if (avatar) {
                        formData.append('avatar', avatar)
                    }
                    basic_info_remove_validate_error();
                    $.ajax({
                        url: "{{route('customer.update.info')}}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('.info_error').text('');
                            $('#first_name').val(response.first_name);
                            $('#last_name').val(response.last_name);
                            $('#email').val(response.email);
                            $('#phone').val(response.phone);
                            $('#description').text(response.description);
                            if(avatar){
                                if(response.avatar.includes('amazonaws.com')){
                                    var image_path = response.avatar;
                                }else{
                                    var image_path="{{asset(asset_path(''))}}" + "/"+response.avatar;
                                }
                                $('.customer_img img').attr('src',image_path);
                            }
                            $('#file').val('');
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#pre-loader').hide();
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            basic_info_update_validate_error(response);
                            $('#pre-loader').hide();
                        }

                    });
                });

                function basic_info_update_validate_error(response){
                    $('.info_error').text('');

                    $('#error_first_name').text(response.responseJSON.errors.first_name);
                    $('#error_email').text(response.responseJSON.errors.email);
                    $('#error_phone').text(response.responseJSON.errors.phone);
                    $('#error_date_of_birth').text(response.responseJSON.errors.date_of_birth);
                    $('#error_avatar').text(response.responseJSON.errors.avatar);
                }

                function basic_info_remove_validate_error(){
                    $('#error_first_name').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_date_of_birth').text('');
                    $('#error_avatar').text('');
                }

                $(document).on('click','.change_password', function(e){
                    e.preventDefault();
                    $('#pre-loader').show();
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('current_password',$('#currentPassword').val());
                    formData.append('new_password',$('#newPass').val());
                    formData.append('new_password_confirmation',$('#rePass').val());
                    $.ajax({
                        url: "{{route('cusotmer.update.password')}}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('.error').text('');
                            $("#update_pass").trigger("reset");
                            toastr.success(response, "{{__('common.success')}}");
                            $('#pre-loader').hide();

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            $('.error').text('');
                            if (response.responseJSON.errors.current_password) {
                                $('.validation-old-pass-error').text(response.responseJSON.errors.current_password);
                            }
                            if (response.responseJSON.errors.new_password) {
                                $('.validation-new-pass-error').text(response.responseJSON.errors.new_password);
                            }
                            if (response.responseJSON.errors.new_password_confirmation) {
                                $('.validation-new-pass-confirm-error').text(response.responseJSON.errors.new_password_confirmation);
                            }
                            $('#pre-loader').hide();
                        }
                    });

                });

                //address
                $(document).on('click','.add_new_address',function(e){
                    e.preventDefault();
                    $('#Address_modal').modal('show');
                });

                $(document).on('click','.default_setup_shipping',function(){


                    var c_id= $("input[name='dft_adrs_shipping']:checked").attr('c_id');
                    var c_list_id= $("input[name='dft_adrs_shipping']:checked").attr('c_list_id');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('c_id', c_id);
                    formData.append('c_list_id', c_list_id);
                    $.ajax({
                        url: "{{ route('customer.address.default.shipping') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('#address_table').html(response.addressList);
                            $('#default_shipping_adrs').html(response.addressListForShipping);
                            $('#default_billing_adrs').html(response.addressListForBilling);

                            $('#default_shipping_adrs').addClass('d-none');
                            $('#address_list').removeClass('d-none');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        }
                    });
                });

                $(document).on('click','.default_setup_billing',function(){


                    var c_id= $("input[name='dft_adrs_billing']:checked").attr('c_id');
                    var c_list_id= $("input[name='dft_adrs_billing']:checked").attr('c_list_id');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('c_id', c_id);
                    formData.append('c_list_id', c_list_id);
                    $.ajax({
                        url: "{{ route('customer.address.default.billing') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {

                            $('#address_table').html(response.addressList);

                            $('#default_billing_adrs').html(response.addressListForBilling);

                            $('#default_billing_adrs').addClass('d-none');
                            $('#address_list').removeClass('d-none');

                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        }
                    });
                });
                //store address
                $(document).on('submit', '#address_form', function(event) {
                    event.preventDefault();
                    $('#pre-loader').show();
                    removeValidateError('#address_form');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    var formElement = $(this).serializeArray()
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    $.ajax({
                        url: "{{ route('customer.address.store') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('#address_table').html(response.addressList);
                            $('#Address_modal').modal('hide');
                            $('#pre-loader').hide();
                            $('#address_form')[0].reset();
                            toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidateError('#address_form', response.responseJSON.errors);
                            $('#pre-loader').hide();
                        }
                    });
                });

                //update address
                $(document).on('submit', '#address_form_edit', function(event) {
                    event.preventDefault();
                    $('#pre-loader').show();
                    removeValidateError('#address_form_edit');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    var formElement = $(this).serializeArray()
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    $.ajax({
                        url: "{{ route('customer.address.update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {

                            $('#address_table').html(response.addressList);
                            $('#default_shipping_adrs').html(response.addressListForShipping);
                            $('#default_billing_adrs').html(response.addressListForBilling);
                            $('#Address_edit_modal').modal('hide');
                            $('#address_form_div_edit').html('');

                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                            $('#pre-loader').hide();
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidateError('#address_form_edit', response.responseJSON.errors);
                            $('#pre-loader').hide();
                        }
                    });
                });


                $(document).on('submit', '#adrs_delete_form', function(event) {
                    event.preventDefault();
                    $('#pre-loader').show();
                    $('#adrs_delete_modal').modal('hide');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', $('#delete_item_id').val());
                    $.ajax({
                        url: "{{ route('customer.address.delete') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if(response == 'is_used'){
                                toastr.error("{{__('customer_panel.address_already_used_for_shipping_or_billing_address')}}", "{{__('common.error')}}");
                                $('#pre-loader').hide();
                            }
                            else if(response == 'is_default'){
                                toastr.error("{{__('customer_panel.address_already_set_for_default_shipping_or_billing_change_first')}}", "{{__('common.error')}}");
                                $('#pre-loader').hide();
                            }
                            else{
                                toastr.success("{{__('common.deleted_successfully')}}");
                                $('#address_table').html(response.addressList);
                                $('#pre-loader').hide();
                            }
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.address_already_used')}}", "{{__('common.error')}}");
                            $('#pre-loader').hide();
                        }
                    });
                });

                $(document).on('change', '#country', function(event){
                    let country = $('#country').val();
                    $('#pre-loader').show();
                    if(country){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' +country;

                        $('#state').empty();

                        $('#state').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#state').niceSelect('update');
                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#city').niceSelect('update');
                        $.get(url, function(data){

                            $.each(data, function(index, stateObj) {
                                $('#state').append('<option value="'+ stateObj.id +'">'+ stateObj.name +'</option>');
                            });

                            $('#state').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });


                $(document).on('change', '#country_edit', function(event){
                    let country = $('#country_edit').val();
                    $('#pre-loader').show();
                    if(country){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' +country;

                        $('#state_edit').empty();

                        $('#state_edit').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#state_edit').niceSelect('update');
                        $('#city_edit').empty();
                        $('#city_edit').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#city_edit').niceSelect('update');
                        $.get(url, function(data){

                            $.each(data, function(index, stateObj) {
                                $('#state_edit').append('<option value="'+ stateObj.id +'">'+ stateObj.name +'</option>');
                            });

                            $('#state_edit').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });


                $(document).on('change', '#state', function(event){
                    let state = $('#state').val();
                    $('#pre-loader').show();
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;


                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#city').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });

                $(document).on('change', '#state_edit', function(event){
                    let state = $('#state_edit').val();
                    $('#pre-loader').show();
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;


                        $('#city_edit').empty();
                        $('#city_edit').append(
                            `<option value="">Select from options</option>`
                        );
                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#city_edit').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#city_edit').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });

                $(document).on('change', '.profile_image', function(event){
                    imageChangeWithFile($(this)[0],'#avaterShow');
                    getFileName2($(this).val(),'#file_name_text');
                });

                function getFileName2(value, placeholder){
                    if (value) {
                        var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                        var filename = value.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        $(placeholder).text('');
                        $(placeholder).text(filename);
                    }
                }

                $(document).on('click', '.edit_address', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');
                    editAddress(id);
                });

                function editAddress(c_id){
                    $('#pre-loader').show();
                    let base_url = $('#url').val();
                    let url = base_url + '/customer/address/edit/'+c_id;
                    $.ajax({
                        url: url,
                        type: "GET",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#address_form_div_edit').html(response);
                            $('#Address_edit_modal').modal('show');
                            $('#country_edit').niceSelect();
                            $('#state_edit').niceSelect();
                            $('#city_edit').niceSelect();
                            $('#address_list').addClass('d-none');
                            if (response.is_shipping_default==1 || response.is_billing_default==1) {
                                $('#dlt_adrs').addClass('d-none');
                            }
                            else{
                                $('#dlt_adrs').removeClass('d-none');
                            }
                            $('#pre-loader').hide();
                            initAutocompleteEdit();
                        },
                        error: function(response) {
                            toastr.error('{{__("common.error_message")}}','{{__("common.error")}}')
                            $('#pre-loader').hide();
                        }

                    });

                }

                $(document).on('click', '.delete_address_btn', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');
                    $('#delete_item_id').val(id);
                    $('#adrs_delete_modal').modal('show');
                });

                function showValidateError(formId, errors){
                    $(formId + ' #error_name').text(errors.name);
                    $(formId + ' #error_email').text(errors.email);
                    $(formId + ' #error_phone').text(errors.phone);
                    $(formId + ' #error_address').text(errors.address);
                    $(formId + ' #error_country').text(errors.country);
                    $(formId + ' #error_state').text(errors.state);
                    $(formId + ' #error_city').text(errors.city);
                    $(formId + ' #error_postcode').text(errors.postal_code);
                }

                function removeValidateError(formId){
                    $(formId + ' #error_name').text('');
                    $(formId + ' #error_email').text('');
                    $(formId + ' #error_phone').text('');
                    $(formId + ' #error_address').text('');
                    $(formId + ' #error_country').text('');
                    $(formId + ' #error_state').text('');
                    $(formId + ' #error_city').text('');
                    $(formId + ' #error_postcode').text('');
                }

            });
        })(jQuery);


    </script>

<?php if (config('app.map_api_status') == "true") { ?>
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('app.map_api_key')}}&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
    <script type="text/javascript">

        let autocomplete;
        let address1Field;
        let postalField;
        function initAutocomplete() {
            address1Field = document.querySelector("#address");
            postalField = document.querySelector("#postal_code");
            autocomplete = new google.maps.places.Autocomplete(address1Field, {
                componentRestrictions: { country: [@if(config('app.map_api_country_1') != "" ) "{{config('app.map_api_country_1')}}" @endif @if(config('app.map_api_country_2') != "" ) ,"{{config('app.map_api_country_2')}}" @endif @if(config('app.map_api_country_3') != "" ) ,"{{config('app.map_api_country_3')}}" @endif @if(config('app.map_api_country_4') != "" ) ,"{{config('app.map_api_country_4')}}" @endif @if(config('app.map_api_country_5') != "" ) ,"{{config('app.map_api_country_5')}}" @endif] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            address1Field.focus();
            autocomplete.addListener("place_changed", fillInAddress);
        }
        function fillInAddress() {
            const place = autocomplete.getPlace();
            let address1 = "";
            let postal_code = "";
            let countryId = "";
            let state_list = [];
            let city_list = [];
            postalField.value = postal_code;

            for (const component of place.address_components) {
                const componentType = component.types[0];

                if ( componentType == 'country') {
                    const country = component.long_name;
                    $("#country option").each(function(i,e)
                    {
                        if (country == e.innerHTML ) {
                            countryId = e.value;
                            $(this).attr('selected', true);
                        }else{
                            $(this).attr('selected', false);
                        }
                    
                    })
                    $('#country').niceSelect('update');
                    $('#pre-loader').show();
                    //change country
                    let base_url = $('#url').val();
                    let url = base_url + '/seller/profile/get-state?country_id=' + countryId;

                    $('#state').empty();

                    $('#state').append(
                        `<option value="">Select from options</option>`
                    );
                    $('#state').niceSelect('update');
                    $('#city').empty();
                    $('#city').append(
                        `<option value="">Select from options</option>`
                    );
                    $('#city').niceSelect('update');
                    $.get(url, function(data) {

                        $.each(data, function(index, stateObj) {
                            $('#state').append('<option value="' + stateObj
                                .id + '">' + stateObj.name + '</option>');
                            state_list.push(stateObj.name);
                        });
                        $('#state').niceSelect('update');
                        $('#pre-loader').hide();
                        for (const component of place.address_components) {
                            const componentType = component.types[0];
                            if ( componentType == 'locality' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state').niceSelect('update');

                                getAndSelectCity(stateId);

                            }
                            else if ( componentType == 'administrative_area_level_2' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state').niceSelect('update');

                                // get city list
                                getAndSelectCity(stateId);
                            }
                            else if ( componentType == 'administrative_area_level_1' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state').niceSelect('update');

                                // get city list
                                getAndSelectCity(stateId);
                            }
                        }
                    });
                }
                if(componentType == 'postal_code'){
                    postalField.value = component.long_name;
                }

               
            }

            function getAndSelectCity(stateId){
                // get city list
                let base_url = $('#url').val();
                let url = base_url + '/seller/profile/get-city?state_id=' +stateId;

                $('#city').empty();
                $('#city').append(
                    `<option value="">Select from options</option>`
                );
                $('#pre-loader').show();
                $.get(url, function(data){

                    $.each(data, function(index, cityObj) {
                        $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                        city_list.push(cityObj.name);
                    });

                    $('#city').niceSelect('update');
                    $('#pre-loader').hide();

                    for (const component of place.address_components) {
                        const componentType = component.types[0];
                        if ( componentType == 'sublocality_level_2' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        else if ( componentType == 'sublocality_level_1' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        else if ( componentType == 'locality' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        else if ( componentType == 'locality' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        else if ( componentType == 'administrative_area_level_2' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        else if ( componentType == 'administrative_area_level_1' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city').niceSelect('update');
                        }
                        
                    }
                });
            }
        }
        window.initAutocomplete = initAutocomplete;

        
    </script>
    <script type="text/javascript">

        let autocompleteEdit;
        let address1FieldEdit;
        let postalFieldEdit;
        function initAutocompleteEdit() {
            address1FieldEdit = document.querySelector("#address_edit");
            postalFieldEdit = document.querySelector("#postal_code_edit");
            autocompleteEdit = new google.maps.places.Autocomplete(address1FieldEdit, {
                componentRestrictions: { country: [@if(config('app.map_api_country_1') != "" ) "{{config('app.map_api_country_1')}}" @endif @if(config('app.map_api_country_2') != "" ) ,"{{config('app.map_api_country_2')}}" @endif @if(config('app.map_api_country_3') != "" ) ,"{{config('app.map_api_country_3')}}" @endif @if(config('app.map_api_country_4') != "" ) ,"{{config('app.map_api_country_4')}}" @endif @if(config('app.map_api_country_5') != "" ) ,"{{config('app.map_api_country_5')}}" @endif] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            address1FieldEdit.focus();
            autocompleteEdit.addListener("place_changed", fillInAddressEdit);
        }
        function fillInAddressEdit() {
            const place = autocompleteEdit.getPlace();
            let address1 = "";
            let postal_code = "";
            let countryId = "";
            let state_list = [];
            let city_list = [];
            postalField.value = postal_code;

            for (const component of place.address_components) {
                const componentType = component.types[0];

                if ( componentType == 'country') {
                    const country = component.long_name;
                    $("#country_edit option").each(function(i,e)
                    {
                        if (country == e.innerHTML ) {
                            countryId = e.value;
                            $(this).attr('selected', true);
                        }else{
                            $(this).attr('selected', false);
                        }
                    
                    })
                    $('#country_edit').niceSelect('update');
                    $('#pre-loader').show();
                    //change country
                    let base_url = $('#url').val();
                    let url = base_url + '/seller/profile/get-state?country_id=' + countryId;

                    $('#state_edit').empty();

                    $('#state_edit').append(
                        `<option value="">Select from options</option>`
                    );
                    $('#state_edit').niceSelect('update');
                    $('#city_edit').empty();
                    $('#city_edit').append(
                        `<option value="">Select from options</option>`
                    );
                    $('#city_edit').niceSelect('update');
                    $.get(url, function(data) {

                        $.each(data, function(index, stateObj) {
                            $('#state_edit').append('<option value="' + stateObj
                                .id + '">' + stateObj.name + '</option>');
                            state_list.push(stateObj.name);
                        });
                        $('#state_edit').niceSelect('update');
                        $('#pre-loader').hide();
                        for (const component of place.address_components) {
                            const componentType = component.types[0];
                            if ( componentType == 'locality' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state_edit option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state_edit').niceSelect('update');

                                getAndSelectCityEdit(stateId);

                            }
                            else if ( componentType == 'administrative_area_level_2' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state_edit option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state_edit').niceSelect('update');

                                // get city list
                                getAndSelectCityEdit(stateId);
                            }
                            else if ( componentType == 'administrative_area_level_1' && state_list.includes(component.long_name)) {
                                state = component.long_name
                                $("#state_edit option").each(function(i,e)
                                {
                                    if (state == e.innerHTML ) {
                                        stateId = e.value;
                                        $(this).attr('selected', true);
                                    }else{
                                        $(this).attr('selected', false);
                                    }
                                })
                                $('#state_edit').niceSelect('update');

                                // get city list
                                getAndSelectCityEdit(stateId);
                            }
                        }
                    });
                }
                if(componentType == 'postal_code'){
                    postalField.value = component.long_name;
                }

               
            }

            function getAndSelectCityEdit(stateId){
                // get city list
                let base_url = $('#url').val();
                let url = base_url + '/seller/profile/get-city?state_id=' +stateId;

                $('#city_edit').empty();
                $('#city_edit').append(
                    `<option value="">Select from options</option>`
                );
                $('#pre-loader').show();
                $.get(url, function(data){

                    $.each(data, function(index, cityObj) {
                        $('#city_edit').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                        city_list.push(cityObj.name);
                    });

                    $('#city_edit').niceSelect('update');
                    $('#pre-loader').hide();

                    for (const component of place.address_components) {
                        const componentType = component.types[0];
                        if ( componentType == 'sublocality_level_2' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        else if ( componentType == 'sublocality_level_1' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        else if ( componentType == 'locality' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        else if ( componentType == 'locality' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        else if ( componentType == 'administrative_area_level_2' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        else if ( componentType == 'administrative_area_level_1' && city_list.includes(component.long_name)) {
                            city = component.long_name
                            $("#city_edit option").each(function(i,e)
                            {
                                if (city == e.innerHTML ) {
                                    cityId = e.value;
                                    $(this).attr('selected', true);
                                }else{
                                    $(this).attr('selected', false);
                                }
                            })
                            $('#city_edit').niceSelect('update');
                        }
                        
                    }
                });
            }
        }

        
    </script>
<?php } ?>
@endpush