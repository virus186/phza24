@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/seller/css/create.css'))}}" />
@endsection
@section('mainContent')
@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center mb-40">
            <div class="col-12">
                <div class="box_header">
                    <div class="main-title d-flex justify-content-between w-100">
                        <h3 class="mb-0 mr-30">{{__('common.add')}} {{ __('common.product') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="white_box box_shadow_white p-25">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="product_types">{{ __('common.product_type') }}
                                    <span class="text-danger">*</span></label>
                                <select class="primary_select mb-25" name="product_types" id="product_types" required>
                                    <option @if(!session()->has('seller_product_create_state') || session()->get('seller_product_create_state') == 2) selected @endif value="2">{{ __('product.existing_product') }}</option>
                                    <option @if(session()->has('seller_product_create_state') && session()->get('seller_product_create_state') == 1) selected @endif value="1">{{ __('product.new_product') }}</option>
                                </select>
                                <input type="hidden" id="seller_product_create_state" value="{{ session()->has('seller_product_create_state')?session()->get('seller_product_create_state'):2 }}">
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div id="exsisitng_product_div" class="col-xl-12">
                            <form action="{{route('seller.product.store')}}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="product_id" id="product_id" value="">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="product_sku">{{ __('common.select') }} <span
                                                    class="text-danger">*</span></label>
                                            <select class="mb-25 product_id" id="main_product_for_select" name="product_id" required>
                                                <option value="" selected disabled>{{ __('common.select') }}</option>

                                            </select>
                                            <span class="text-danger" id="error_product_id"></span>
                                        </div>

                                    </div>
                                    <div id="single_product_stock_manage_div" class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="product_stock_manage">{{__('product.product_stock_manage')}} <span
                                                    class="text-danger">*</span></label>
                                            <select class="primary_select mb-25" name="stock_manage" id="stock_manage"
                                                required>
                                                <option value="1">{{ __('common.yes') }}</option>
                                                <option value="0" selected>{{ __('common.no') }}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div id="single_product_stock_div" class="col-xl-6 d-none">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="product_stock">{{__('product.product_stock')}} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" name="product_stock" id="product_stock"
                                                placeholder="{{__("product.product_stock")}}" type="number" min="0"
                                                step="{{step_decimal()}}" value="0" required>
                                            @error('product_stock')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div id="variant_sku_div" class="col-xl-6 d-none">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="product_sku">{{ __('product.select_product_sku') }} <span class="text-danger">*</span></label>
                                            <select class="primary_select mb-25" name="product_sku []" id="product_sku" multiple>
                                                <option value="" selected disabled>{{ __('common.select') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="priceBoxDiv" class="row">
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{__("product.selling_price")}}
                                                <span class="text-danger">*</span></label>
                                            <input class="primary_input_field" name="selling_price" id="selling_prices"
                                                placeholder="{{__("product.selling_price")}}" type="number" min="0"
                                                step="{{step_decimal()}}" value="0" required>
                                            @error('selling_price')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if(isModuleActive('WholeSale'))
                                    <div class="col-lg-6 d-none whole_sale_info_add" id="whole_sale_info_add">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">{{ __('wholesale.Wholesale Price') }}</label>
                                            <!-- table-responsive -->
                                            <div class="table-responsive">
                                                <table class="create_table">
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="add_items_button mb-20">
                                            <button type="button" class="d-none btn btn-light btn-sm border add_single_whole_sale_price">
                                                Add More
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                <div class="row">
                                @if(isModuleActive('FrontendMultiLang'))
                                    <div class="col-lg-12">
                                        <ul class="nav nav-tabs justify-content-start mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                                            @foreach ($LanguageList as $key => $language)
                                                <li class="nav-item">
                                                    <a class="nav-link anchore_color @if (auth()->user()->lang_code == $language->code) active @endif" href="#epnelement{{$language->code}}" role="tab" data-toggle="tab" aria-selected="@if (auth()->user()->lang_code == $language->code) true @else false @endif">{{ $language->native }} </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach ($LanguageList as $key => $language)
                                                <div role="tabpanel" class="tab-pane fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="epnelement{{$language->code}}">
                                                   <div class="row">
                                                       <div class="col-lg-6">
                                                           <div class="primary_input mb-15">
                                                               <label class="primary_input_label" for="product_name"> {{ __('common.name') }} <span class="text-danger">*</span></label>
                                                               <input class="primary_input_field" name="product_name[{{$language->code}}]" id="product_name_{{$language->code}}" placeholder="{{ __('common.name') }}" type="text">
                                                               <span class="text-danger" id="error_product_name_{{$language->code}}">{{ $errors->first('product_name') }}</span>
                                                           </div>
                                                       </div>
                                                       <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                        <div class="primary_input mb-15">
                                                            <label class="primary_input_label" for="subtitle_1"> {{ __('product.subtitle_1') }}</label>
                                                            <input class="primary_input_field" name="subtitle_1[{{$language->code}}]" id="subtitle_1_{{$language->code}}" placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{old('subtitle_1')}}">
                                                            <span id="error_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                        <div class="primary_input mb-15">
                                                            <label class="primary_input_label" for="subtitle_2"> {{ __('product.subtitle_2') }}</label>
                                                            <input class="primary_input_field" name="subtitle_2[{{$language->code}}]" id="subtitle_2_{{$language->code}}" placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{old('subtitle_2')}}">
                                                            <span id="error_subtitle_2" class="text-danger">{{ $errors->first('subtitle_2') }}</span>
                                                        </div>
                                                    </div>
                                                   </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="product_name"> {{__("product.display_name")}} </label>
                                            <input class="primary_input_field" id="product_name" name="product_name" placeholder="{{__("product.display_name")}}" type="text">
                                            <span class="text-danger">{{$errors->first('product_name')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="exsist_subtitle_1"> {{ __('product.subtitle_1') }}</label>
                                            <input class="primary_input_field" name="subtitle_1" id="exsist_subtitle_1" placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{old('subtitle_1')}}">
                                            <span id="error_exsist_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="exsist_subtitle_2"> {{ __('product.subtitle_2') }}</label>
                                            <input class="primary_input_field" name="subtitle_2" id="exsist_subtitle_2" placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{old('subtitle_2')}}">
                                            <span id="exsist_subtitle_2" class="text-danger">{{ $errors->first('subtitle_2') }}</span>
                                        </div>
                                    </div>
                                @endif
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label"
                                                for="">{{ __('product.thumbnail_image') }} (165x165)PX</label>
                                            <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="thumbnail_image">
                                                <input class="primary-input file_amount" type="text"
                                                    id="thumbnail_image_file_seller"
                                                    placeholder="{{ __('product.thumbnail_image') }}"
                                                    readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="thumbnail_image_seller">{{ __('product.Browse') }}
                                                    </label>

                                                    <input type="hidden" class="selected_files" value="">
                                                </button>
                                            </div>
                                            <div class="product_image_all_div"></div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">
                                                {{__("product.discount")}}</label>
                                            <input class="primary_input_field" name="discount" id="discount"
                                                placeholder="{{__("product.discount")}}" type="number" min="0"
                                                step="{{step_decimal()}}" value="0">
                                            <span class="text-danger">{{$errors->first('discount')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="">{{ __('product.discount_type') }}</label>
                                            <select class="primary_select mb-25" name="discount_type"
                                                id="discount_type">
                                                <option value="1">{{ __('product.amount') }}</option>
                                                <option value="0">{{ __('product.percentage') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label"
                                                for="startDate">{{__('product.discount_start_date')}}</label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input placeholder="{{ __('common.date') }}"
                                                                class="primary_input_field primary-input date form-control"
                                                                id="startDate" type="text" name="discount_start_date"
                                                                value="" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="" type="button">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label"
                                                for="endDate">{{__('product.discount_end_date')}}</label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input placeholder="{{ __('common.date') }}"
                                                                class="primary_input_field primary-input date form-control"
                                                                id="endDate" type="text" name="discount_end_date"
                                                                value="" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="" type="button">
                                                        <i class="ti-calendar" id="end-date-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="variant_table_div" class="col-xl-12 d-none overflow-auto">

                                        <table class="table table-bordered sku_table_exsist">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">{{ __('product.variant') }}</th>

                                                    <th class="text-center">{{ __('product.selling_price') }}</th>
                                                    <th class="text-center product_stock_th stock_td">
                                                        {{ __('product.product_stock') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sku_tbody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6" id="gst_list_exsisting">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 text-center mt-20">
                                        <div class="d-flex justify-content-center">
                                            <button class="primary-btn semi_large2  fix-gr-bg mr-1"
                                                id="save_button_parent" type="submit"><i
                                                    class="ti-check"></i>{{__('common.save')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div id="new_product_div" class="col-xl-12 d-none">
                            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="white_box box_shadow_white mb-20 p-15">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-2 mr-30">{{ __('product.product_information') }}
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <input type="hidden" value="1" id="product_type">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">{{ __('common.type') }} <span
                                                                class="text-danger">*</span></label>
                                                        <ul id="theme_nav" class="permission_list sms_list ">
                                                            <li>
                                                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="product_type" id="single_prod" value="1" checked
                                                                        class="active prod_type" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('product.single') }}</p>
                                                            </li>
                                                            <li>
                                                                <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="product_type" value="2" id="variant_prod"
                                                                        class="de_active prod_type" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('product.variant') }}</p>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            @if(isModuleActive('FrontendMultiLang'))
                                                <div class="col-lg-12">
                                                    <ul class="nav nav-tabs justify-content-start mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                                                        @foreach ($LanguageList as $key => $language)
                                                            <li class="nav-item">
                                                                <a class="nav-link default_lang anchore_color @if (auth()->user()->lang_code == $language->code) active @endif" data-id="{{$language->code}}" href="#pnelement{{$language->code}}" role="tab" data-toggle="tab" aria-selected="@if (auth()->user()->lang_code == $language->code) true @else false @endif">{{ $language->native }} </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="tab-content">
                                                        @foreach ($LanguageList as $key => $language)
                                                            <div role="tabpanel" class="tab-pane fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="pnelement{{$language->code}}">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="product_name"> {{ __('common.name') }} <span class="text-danger">*</span></label>
                                                                        <input class="primary_input_field" name="product_name[{{$language->code}}]" id="product_name_new_{{$language->code}}" placeholder="{{ __('common.name') }}" type="text">
                                                                        <span class="text-danger" id="error_product_new_name_{{$language->code}}">{{ $errors->first('product_name') }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 sku_single_div d-none" id="default_lang_{{$language->code}}">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="sku_single"> {{ __('product.product_sku') }}</label>
                                                                        <input class="primary_input_field" name="product_sku[{{$language->code}}]" id="sku_single_{{$language->code}}" placeholder="{{ __('product.product_sku') }}" type="text" value="{{old('product_sku')}}">
                                                                        <span id="error_single_sku_{{$language->code}}" class="text-danger">{{ $errors->first('product_sku') }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="subtitle_1"> {{ __('product.subtitle_1') }}</label>
                                                                        <input class="primary_input_field" name="subtitle_1[{{$language->code}}]" id="subtitle_1" placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{old('subtitle_1')}}">
                                                                        <span id="error_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="subtitle_2"> {{ __('product.subtitle_2') }}</label>
                                                                        <input class="primary_input_field" name="subtitle_2[{{$language->code}}]" id="subtitle_2" placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{old('subtitle_2')}}">
                                                                        <span id="error_subtitle_2" class="text-danger">{{ $errors->first('subtitle_2') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{__("common.name")}}
                                                            <span class="text-danger">*</span></label>
                                                        <input class="primary_input_field" name="product_name" id="product_name_new" placeholder="{{__("common.name")}}" type="text" value="{{old('product_name')}}" required="1">
                                                        <span class="text-danger" id="error_product_new_name">{{$errors->first('product_name')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 sku_single_div">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="sku_single">{{__("product.product_sku")}} </label>
                                                        <input class="primary_input_field" name="product_sku" id="sku_single"
                                                            placeholder="{{__("product.product_sku")}}" type="text"
                                                            required="1">
                                                        <span class="text-danger" id="error_single_sku">{{$errors->first('product_sku')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.subtitle_1') }}</label>
                                                        <input class="primary_input_field" name="subtitle_1" id="subtitle_1"
                                                            placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{old('subtitle_1')}}">
                                                        <span id="error_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.subtitle_2') }}</label>
                                                        <input class="primary_input_field" name="subtitle_2" id="subtitle_2"
                                                            placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{old('subtitle_2')}}">
                                                        <span id="error_subtitle_2" class="text-danger">{{ $errors->first('subtitle_2') }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="model_number">
                                                            {{__("common.model_number")}}</label>
                                                        <input class="primary_input_field" name="model_number"
                                                            placeholder="{{__("common.model_number")}}" type="text"
                                                            value="{{old('model_number')}}">
                                                        <span
                                                            class="text-danger">{{$errors->first('model_number')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.category') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="category_ids[]" id="category_id"
                                                            class="mb-15 category" @if(app('general_setting')->multi_category == 1) multiple @endif required="1">

                                                        </select>
                                                        <span class="text-danger" id="error_category_ids">{{$errors->first('category_id')}}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.brand') }}</label>
                                                        <select name="brand_id" id="brand_id"
                                                            class="mb-15 brand">
                                                            <option disabled selected>{{__('product.select_brand')}}
                                                            </option>

                                                        </select>
                                                        <span class="text-danger">{{$errors->first('brand_id')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.unit') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="unit_type_id" id="unit_type_id"
                                                            class="primary_select mb-15 unit">
                                                            <option disabled selected>{{__('product.select_unit')}}
                                                            </option>
                                                            @foreach($units as $key => $unit)
                                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span
                                                            class="text-danger" id="error_unit_type">{{$errors->first('unit_type_id')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label"
                                                            for="">{{__('product.barcode_type')}}</label>
                                                        <select name="barcode_type" id="barcode_type"
                                                            class="primary_select mb-15">
                                                            @foreach (barcodeList() as $key => $barcode)
                                                            <option value="{{ $barcode }}" @if($key==0) selected @endif>
                                                                {{ $barcode }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span
                                                            class="text-danger">{{$errors->first('barcode_type')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">
                                                            {{__("product.minimum_order_qty")}} <span
                                                                class="text-danger">*</span></label>
                                                        <input class="primary_input_field" name="minimum_order_qty"
                                                            id="minimum_order_qty" value="1" type="number" min="1"
                                                            step="0" required="1">
                                                        <span
                                                            class="text-danger" id="error_minumum_qty">{{$errors->first('minimum_order_qty')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">
                                                            {{__("product.max_order_qty")}} </label>
                                                        <input class="primary_input_field" name="max_order_qty"
                                                            type="number" min="0">
                                                        <span
                                                            class="text-danger">{{$errors->first('max_order_qty')}}</span>
                                                    </div>
                                                </div>
                                                @if(isModuleActive('GoogleMerchantCenter'))
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="">{{ __('product.product_condition')
                                                            }}</label>
                                                        <select class="primary_select mb-25" name="condition"
                                                            id="condition">
                                                            <option value="new" @if(old('condition') && old('condition') == 'new') selected @endif>{{ __('product.new') }}</option>
                                                            <option value="used" @if(old('condition') && old('condition') == 'used') selected @endif>{{ __('product.used') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('common.gtin') }}</label>
                                                        <input class="primary_input_field" name="gtin" id="gtin"
                                                            placeholder="{{ __('common.gtin') }}" type="text"
                                                            value="{{ old('gtin') }}">
                                                        <span class="text-danger" id="error_gtin">{{ $errors->first('gtin') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('common.mpn') }}</label>
                                                        <input class="primary_input_field" name="mpn" id="mpn"
                                                            placeholder="{{ __('common.mpn') }}" type="text"
                                                            value="{{ old('mpn') }}">
                                                        <span class="text-danger" id="error_mpn">{{ $errors->first('mpn') }}</span>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-lg-12">

                                                    <div class="single_field ">
                                                        <label for="">@lang('blog.tags') (@lang('product.comma_separated'))<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="tagInput_field mb_26">
                                                        <input name="tags" class="tag-input" id="tag-input-upload-shots"
                                                            type="text" value="" data-role="tagsinput" />
                                                    </div>
                                                    <br>
                                                    <div class="suggeted_tags">
                                                        <label>@lang('blog.suggested_tags')</label>
                                                        <ul id="tag_show"  class="suggested_tag_show">
                                                        </ul>
                                                    </div>
                                                    <br>
                                                    <span class="text-danger" id="error_tags"></span>
                                                </div>

                                                <div class="col-lg-12 attribute_div">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.attribute') }}</label>
                                                        <select name="choice_attributes" id="choice_attributes"
                                                            class="primary_select mb-15 choice_attribute">
                                                            <option value="" selected disabled>{{__('product.select_attribute')}}</option>
                                                            @foreach($attributes as $key => $attribute)
                                                            <option value="{{$attribute->id}}">{{$attribute->name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <span
                                                            class="text-danger">{{$errors->first('choice_attributes')}}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="customer_choice_options" id="customer_choice_options">

                                                    </div>
                                                </div>

                                                <div class="col-lg-12 sku_combination overflow-auto">

                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.price_info_and_stock') }}
                                                        </h3>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <div class="primary_input">
                                                        <ul id="theme_nav" class="permission_list sms_list ">
                                                            <li>
                                                                <label data-id="bg_option"
                                                                    class="primary_checkbox d-flex mr-12">
                                                                    <input name="" id="is_physical" checked value="1"
                                                                        type="checkbox">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('product.is_physical_product') }}</p>
                                                                <input type="hidden" name="is_physical" value="1"
                                                                    id="is_physical_prod">
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12  weight_height_div">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.weight_height_info') }}</h3>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-lg-3">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for=""> {{ __('product.weight')}} [Gm]</label>
                                                                <input class="primary_input_field" name="weight" id="weight"
                                                                       type="number" min="0" step="{{step_decimal()}}">
                                                                <span class="text-danger" id="error_weight">{{ $errors->first('weight') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for=""> {{ __('product.length')}} [Cm]</label>
                                                                <input class="primary_input_field" name="length" id="length"
                                                                       type="number" min="0" step="{{step_decimal()}}">
                                                                <span class="text-danger" id="error_length">{{ $errors->first('length') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for=""> {{ __('product.breadth')}} [Cm]</label>
                                                                <input class="primary_input_field" name="breadth" id="breadth"
                                                                       type="number" min="0" step="{{step_decimal()}}">
                                                                <span class="text-danger" id="error_breadth">{{ $errors->first('breadth') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for=""> {{ __('product.height')}} [Cm]</label>
                                                                <input class="primary_input_field" name="height" id="height"
                                                                       type="number" min="0" step="{{step_decimal()}}">
                                                                <span class="text-danger" id="error_height">{{ $errors->first('height') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="phisical_shipping_div" class="col-lg-12">
                                                    <div class="row">

                                                        <div class="col-lg-12">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label"
                                                                    for="additional_shipping">
                                                                    {{ __('product.additional_shipping_charge') }}
                                                                </label>
                                                                <input class="primary_input_field"
                                                                    name="additional_shipping"
                                                                    placeholder="{{ __('product.tax') }}" type="number"
                                                                    min="0" step="{{step_decimal()}}"
                                                                    value="{{old('additional_shipping')?old('additional_shipping'):0}}">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('additional_shipping') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 digital_file_upload_div_single">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.program_file_upload') }}</label>
                                                        <div class="primary_file_uploader">
                                                            <input class="primary-input" type="text"
                                                                id="digital_file_place"
                                                                placeholder="{{ __('common.upload_file') }}"
                                                                readonly="">
                                                            <button class="" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                    for="digital_file">{{ __('product.Browse') }}
                                                                </label>
                                                                <input type="file" class="d-none" accept=".pdf"
                                                                    name="digital_file" id="digital_file">
                                                            </button>
                                                        </div>
                                                        <span
                                                            class="text-danger">{{ $errors->first('documents') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 selling_price_div">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">
                                                            {{__("product.selling_price")}} <span
                                                                class="text-danger">*</span></label>
                                                        <input class="primary_input_field" name="selling_price"
                                                            id="selling_price"
                                                            placeholder="{{__("product.selling_price")}}" type="number"
                                                            min="0" step="{{step_decimal()}}" value="0" required>
                                                        <span
                                                            class="text-danger" id="error_selling_price">{{$errors->first('selling_price')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">
                                                            {{__("product.discount")}} </label>
                                                        <input class="primary_input_field" name="discount" id="discount"
                                                            placeholder="{{__("product.discount")}}" type="number"
                                                            min="0" step="{{step_decimal()}}" value="0">
                                                        <span class="text-danger" id="error_discunt">{{$errors->first('discount')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('product.discount_type') }}</label>
                                                        <select class="primary_select mb-25" name="discount_type"
                                                            id="discount_type">
                                                            <option value="1">{{ __('product.amount') }}</option>
                                                            <option value="0">{{ __('product.percentage') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="">{{ __('GST/TAX Group')
                                                            }}</label>
                                                        <select class="primary_select mb-25" name="gst_group" id="tax_type">
                                                            <option value="" selected disabled>{{__('common.select_one')}}</option>
                                                            @foreach($gst_groups as $group)
                                                                <option value="{{$group->id}}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6" id="gst_list_div">
                                                </div>
                                            @if(isModuleActive('FrontendMultiLang'))
                                                <div class="col-lg-12">
                                                    <ul class="nav nav-tabs justify-content-start mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                                                        @foreach ($LanguageList as $key => $language)
                                                            <li class="nav-item">
                                                                <a class="nav-link anchore_color @if (auth()->user()->lang_code == $language->code) active @endif" href="#pelement{{$language->code}}" role="tab" data-toggle="tab" aria-selected="@if (auth()->user()->lang_code == $language->code) true @else false @endif">{{ $language->native }} </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="tab-content">
                                                        @foreach ($LanguageList as $key => $language)
                                                            <div role="tabpanel" class="tab-pane fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="pelement{{$language->code}}">
                                                                <div class="col-lg-12">
                                                                    <div class="main-title d-flex">
                                                                        <h3 class="mb-3 mr-30">{{ __('common.description') }}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input mb-15">
                                                                        <textarea class="summernote" name="description[{{$language->code}}]"> {{old('description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="main-title d-flex">
                                                                        <h3 class="mb-3 mr-30">{{ __('product.specifications') }}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input mb-15">
                                                                        <textarea class="summernote" id="specification" name="specification[{{$language->code}}]"> {{old('specification')}} </textarea>
                                                                    </div>
                                                                </div>
                            
                                                                <div class="col-lg-12">
                                                                    <div class="main-title d-flex">
                                                                        <h3 class="mb-3 mr-30">{{ __('common.seo_info') }}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="meta_title"> {{ __('common.meta_title')}}</label>
                                                                        <input class="primary_input_field" id="meta_title" name="meta_title[{{$language->code}}]" placeholder="{{ __('common.meta_title') }}" type="text" value="{{ old('meta_title') }}">
                                                                        <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="meta_description"> {{ __('common.meta_description') }}</label>
                                                                        <textarea class="primary_textarea height_112 meta_description" id="meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description[{{$language->code}}]" spellcheck="false">{{old('meta_description')}}</textarea>
                                                                        <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{__("common.description")}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <textarea class="summernote" name="description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.specifications') }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <textarea class="summernote" id="specification" name="specification"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('common.seo_info') }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="meta_title"> {{__("common.meta_title")}}</label>
                                                        <input class="primary_input_field" id="meta_title" name="meta_title" placeholder="{{__("common.meta_title")}}" type="text" value="{{old('meta_title')}}">
                                                        <span class="text-danger">{{$errors->first('meta_title')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="meta_description"> {{__("common.meta_description")}}</label>
                                                        <textarea id="meta_description" class="primary_textarea height_112 meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description" spellcheck="false"></textarea>
                                                        <span class="text-danger">{{$errors->first('meta_description')}}</span>
                                                    </div>
                                                </div>
                                            @endif
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="meta_image_file">{{ __('product.meta_image') }} (300x300)PX</label>
                                                        <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="meta_image">
                                                            <input class="primary-input file_amount" type="text" id="meta_image_file" placeholder="{{__('common.browse_image_file')}}" readonly="">
                                                            <button class="" type="button">
                                                                <label class="primary-btn small fix-gr-bg" for="meta_image">{{__('product.meta_image') }} </label>
                                                                <input type="hidden" class="selected_files" value="">
                                                            </button>
                                                        </div>
                                                        <div class="product_image_all_div"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="white_box box_shadow_white p-15">
                                            <div class="row image_section">
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.product_image_info') }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="true" data-type="image" data-name="images[]">
                                                            <input class="primary-input file_amount" type="text" id="thumbnail_image_file" placeholder="{{ __('Choose Images') }}" readonly="">
                                                            <button class="" type="button">
                                                                <label class="primary-btn small fix-gr-bg" for="thumbnail_image">{{__('product.Browse') }} </label>
                                                                <input type="hidden" class="selected_files image_selected_files" value="">
                                                            </button>
                                                            <span class="text-danger" id="error_thumbnail"></span>
                                                        </div>
                                                        <div class="product_image_all_div">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.pdf_specifications') }}
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="pdf_place">{{__('product.pdf_specifications')}}</label>
                                                        <div class="primary_file_uploader">
                                                            <input class="primary-input" type="text" id="pdf_place" placeholder="{{ __('common.upload_pdf') }}" readonly>
                                                            <button class="" type="button">
                                                                <label class="primary-btn small fix-gr-bg" for="pdf">{{__("product.Browse")}} </label>
                                                                <input type="file" class="d-none" accept=".pdf" name="pdf_file" id="pdf">
                                                            </button>
                                                        </div>
                                                        <span class="text-danger">{{$errors->first('documents')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.product_videos_info') }}
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="video_provider">{{ __('product.video_provider') }}</label>
                                                        <select class="primary_select mb-25" name="video_provider" id="video_provider">
                                                            <option value="youtube">{{ __('product.youtube') }}</option>
                                                            <option value="daily_motion"> {{ __('product.daily_motion') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="video_link">{{__("product.video_link")}}</label>
                                                        <input class="primary_input_field" id="video_link" name="video_link" placeholder="{{__("product.video_link")}}" type="text" value="{{old('video_link')}}">
                                                        <span
                                                            class="text-danger">{{$errors->first('video_link')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="main-title d-flex">
                                                        <h3 class="mb-3 mr-30">{{ __('product.others_info') }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">{{ __('common.status') }} <span class="text-danger">*</span></label>
                                                        <ul id="theme_nav" class="permission_list sms_list ">
                                                            <li>
                                                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="status" id="status_active" value="1" checked class="active" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('common.publish') }}</p>
                                                            </li>
                                                            <li>
                                                                <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="status" value="0" id="status_inactive"  class="de_active" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('common.pending') }}</p>
                                                            </li>
                                                        </ul>
                                                        <span class="text-danger" id="status_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">{{ __('common.make_Display_in_details_page') }} <span class="text-danger">*</span></label>
                                                        <ul id="theme_nav" class="permission_list sms_list ">
                                                            <li>
                                                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="display_in_details" id="status_active" value="1" checked class="active" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('common.up_sale') }}</p>
                                                            </li>
                                                            <li>
                                                                <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                                    <input name="display_in_details" value="2" id="status_inactive"  class="de_active" type="radio">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{ __('common.cross_sale') }}</p>
                                                            </li>
                                                        </ul>
                                                        <span class="text-danger" id="status_error"></span>
                                                    </div>
                                                </div>
                                                @php
                                                $user = auth()->user();
                                                @endphp
                                                <input type="hidden" name="request_from" value="@if($user->role->type == 'seller') seller_product_form @else inhouse_product_form @endif">
                                                <div class="col-12 text-center">
                                                    <button class="primary_btn_2 mt-5 saveBtn"><i class="ti-check"></i>{{__("common.save")}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <input type="hidden" id="product_type_input" value="1">

</section>


@endsection

@push('scripts')

<script type="text/javascript">
    (function($){
        "use strict";

        $(document).ready(function(){
            if("{{$errors->has('sku.*')}}"){
                toastr.error('SKU must be unique.','Error');
            }
            $('.summernote').summernote({
                height: 200,
                codeviewFilter: true,
			    codeviewIframeFilter: true,
                disableDragAndDrop:true
            });
            getActiveFieldAttribute();
            getActiveFieldShipping();
            $('.digital_file_upload_div_single').hide();

            productTypeChange($('#seller_product_create_state').val());

            $(document).on('change', '#product_types', function(){
                let val = $('#product_types').val();
                productTypeChange(val);
                let url = "{{route('seller.product.change-state')}}" +'?type='+val;
                $.get(url, function(res){});
            });

            function productTypeChange(val){
                if(val == 2){
                        $('#exsisitng_product_div').removeClass('d-none');
                        $('#new_product_div').addClass('d-none');

                }if(val == 1){
                    if("{{ auth()->user()->role->type }}" == "superadmin" || "{{ auth()->user()->role->type }}" == "admin" || "{{ auth()->user()->role->type }}" == "staff"){
                        location.href = "{{ route('product.create') }}";
                    }else{
                        $('#exsisitng_product_div').addClass('d-none');
                        $('#new_product_div').removeClass('d-none');
                    }
                }
            }

            $(document).on('change', 'select[name="product_id"]', function(event){
                let val = $(this).val();
                getActiveField(val);
            });

            function getActiveField(val){
                $('#variant_table_div').addClass('d-none');
                $('#pre-loader').removeClass('d-none');
                    if(val != null){

                        let base_url = $('#url').val();
                        let url = base_url+"/seller/product/" + val;
                        $.ajax({
                            url: url,
                            type: "GET",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                $('#pre-loader').addClass('d-none');
                                if(response == 'product_exsist'){
                                    toastr.error("{{__('seller.product_allready_added')}}", "{{__('common.error')}}");
                                    $('#priceBoxDiv').removeClass('d-none');
                                    $('#variant_sku_div').addClass('d-none');
                                    $('select[name="product_id"]').val('');
                                    $('select[name="product_id"]').niceSelect('update');

                                }else{
                                    let product = response.product;
                                    $('#product_type_input').val(product.product_type);

                                    @if(isModuleActive('FrontendMultiLang'))
                                        if (product.product_name != null) {
                                            $.each(product.product_name, function(key,value) {
                                                $('#product_name_'+key).val(value);
                                            });
                                        }else{
                                            $('#product_name_{{auth()->user()->lang_code}}').val(product.translateProductName);
                                        }
                                        if (product.subtitle_1 != null) {
                                            $.each(product.subtitle_1, function(key,value) {
                                                $('#subtitle_1_'+key).val(value);
                                            });
                                        }else{
                                            $('#subtitle_1_{{auth()->user()->lang_code}}').val(product.TranslateProductSubtitle1);
                                        }
                                        if (product.subtitle_2 != null) {
                                            $.each(product.subtitle_2, function(key,value) {
                                                $('#subtitle_2_'+key).val(value);
                                            });
                                        }else{
                                            $('#subtitle_2_{{auth()->user()->lang_code}}').val(product.TranslateProductSubtitle2);
                                        }
                                    @else
                                        $('#product_name').val(product.product_name);
                                        $('#exsist_subtitle_1').val(product.subtitle_1);
                                        $('#exsist_subtitle_2').val(product.subtitle_2);
                                    @endif
                                    if(product.product_type == 1){
                                        getStockField();

                                        $('#priceBoxDiv').removeClass('d-none');
                                        $('#variant_sku_div').addClass('d-none');


                                        $('#product_id').val(product.id);
                                        $('#purchase_prices').val(product.skus[0].purchase_price)
                                        $('#selling_prices').val(product.skus[0].selling_price)
                                        $('#tax').val(product.tax)
                                        $('#discount').val(product.discount)
                                        $('#tax_type').val(response.tax_type)
                                        $('#discount_type').val(product.discount_type)
                                        $('#tax_type').niceSelect('update');
                                        $('#discount_type').niceSelect('update');


                                        if (response.checkWholeSaleM == 1){
                                            $('#whole_sale_info_add').removeClass('d-none');
                                            $('.whole_sale_price_list_child').remove();

                                            $('.whole_sale_info_add tbody').append(`<tr class="whole_sale_price_list whole_sale_price_list_child">
                                                            <td class="pl-0 pb-0 border-0">
                                                                <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_0[]">
                                                            </td>
                                                            <td class="pl-0 pb-0 border-0">
                                                                <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_0[]">
                                                            </td>
                                                            <td class="pl-0 pb-0 border-0">
                                                                <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_0[]">
                                                            </td>
                                                        </tr>`);
                                            $('.add_single_whole_sale_price').removeClass('d-none');
                                        }
                                    }else{
                                        $('#single_product_stock_div').addClass('d-none');
                                        $('#single_product_stock_manage_div').removeClass('col-xl-3');
                                        $('#single_product_stock_manage_div').addClass('col-xl-6');
                                        $('#product_stock').removeAttr('required');
                                        $('#priceBoxDiv').addClass('d-none');
                                        $('#variant_sku_div').removeClass('d-none');

                                        $('#tax').val(product.tax)
                                        $('#discount').val(product.discount)
                                        $('#tax_type').val(product.tax_type)
                                        $('#discount_type').val(product.discount_type)
                                        $('#tax_type').niceSelect('update');
                                        $('#discount_type').niceSelect('update');

                                        $('#product_sku').empty();
                                        $.each( product.active_skus, function(key,value) {
                                            $('#product_sku').append(`<option value="${value.id}">${value.sku}</option>`)
                                        });
                                        $('#product_sku').niceSelect('update');

                                        $('#whole_sale_info_add').addClass('d-none');
                                        $('.whole_sale_price_list').remove();
                                        $('.whole_sale_price_list_child').remove();
                                    }
                                    $('#gst_list_exsisting').html(response.gst_list);
                                }

                            },
                            error: function(response) {
                                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                            }
                        });


                    }else{
                        $('#priceBoxDiv').addClass('d-none');
                    }
            }

            $(document).on('change', '#stock_manage', function(){
                getStockField();
            });

            function getStockField(){
                var stock_manage = $('#stock_manage').val();
                if (stock_manage == 1) {
                    if($('#product_type_input').val()== 1){
                        $('#single_product_stock_div').removeClass('col-xl-6');
                        $('#single_product_stock_div').addClass('col-xl-3');
                        $('#single_product_stock_manage_div').removeClass('col-xl-6');
                        $('#single_product_stock_manage_div').addClass('col-xl-3');
                        $('#single_product_stock_div').removeClass('d-none');
                        $("#product_stock").prop('required',true);

                    }else{
                        $('.stock_td').removeClass('d-none');

                    }
                }else {
                    $('#single_product_stock_div').addClass('d-none');
                    $('#single_product_stock_manage_div').removeClass('col-xl-3');
                    $('#single_product_stock_manage_div').addClass('col-xl-6');
                    $('#product_stock').removeAttr('required');
                    $('.stock_td').addClass('d-none');
                }
            }

            $(document).on('change', '#thumbnail_image_seller', function(){
                getFileName($(this).val(),'#thumbnail_image_file_seller');
                imageChangeWithFile($(this)[0],'#sellerThumbnailImg');
            });

            $(document).on('click', '.prod_type', function(){
                if($('#product_type').val($(this).val())){
                    getActiveFieldAttribute();
                }
            });

            function getActiveFieldAttribute() {
                $('#is_physical').prop('checked',true);
                var product_type = $('#product_type').val();
                if (product_type == 1) {
                    $('.attribute_div').hide();
                    $('.weight_single_div').show();

                    $('.variant_physical_div').hide();
                    $('.customer_choice_options').hide();
                    $('.sku_combination').hide();

                    $('.sku_single_div').show();
                    $('.selling_price_div').show();
                    $("#sku_single").removeAttr("disabled");
                    $("#purchase_price").removeAttr("disabled");
                    $("#selling_price").removeAttr("disabled");
                } else {
                    $('.attribute_div').show();
                    $('.sku_single_div').hide();
                    $('.variant_physical_div').show();
                    $('.sku_combination').show();
                    $('.customer_choice_options').show();
                    $('.weight_single_div').hide();

                    $('.selling_price_div').hide();
                    $("#sku_single").attr('disabled', true);
                    $("#purchase_price").attr('disabled', true);
                    $("#selling_price").attr('disabled', true);
                    $("#weight_single").attr('disabled', true);
                }
            }

            $(document).on('change','#product_sku', function(){

                $('#variant_table_div').addClass('d-none');

                $('#sku_tbody').empty();
                var a_id = $(this).val();
                var a_name = $(this).text();
                var stock_manage = $('#stock_manage').val();
                $.post('{{ route('seller.product.variant') }}', {_token:'{{ csrf_token() }}', ids:a_id, stock_manage:stock_manage}, function(data){
                    $('#variant_table_div').removeClass('d-none');
                    if (stock_manage == 1) {
                        $('.product_stock_th').removeClass('d-none');
                    }else {
                        $('.product_stock_th').addClass('d-none');
                    }
                    $('#sku_tbody').empty();
                    $('#sku_tbody').append(data.variants)
                });
            });

            $(document).on('change', '#digital_file', function(){
                getFileName($(this).val(),'#digital_file_place');
            });

            $(document).on('change', '#meta_image' , function(){
                getFileName($(this).val(),'#meta_image_file'),imageChangeWithFile($(this)[0],'#MetaImgDiv');
            });

            $(document).on('change', '#thumbnail_image', function(){
                getFileName($(this).val(),'#thumbnail_image_file'),imageChangeWithFile($(this)[0],'#ThumbnailImg');
            });

            $(document).on('change', '#galary_image', function(){
                galleryImage($(this)[0],'#galler_img_prev');
            });

            $(document).on('change', '#pdf', function(){
                getFileName(this.value,'#pdf_place');
            });

            $(document).on('change', '#choice_options', function(){
                get_combinations();
            })

            function get_combinations(el){
                $.ajax({
                    type:"POST",
                    url:'{{ route('product.sku_combination') }}',
                    data:$('#choice_form').serialize(),
                    success: function(data){
                        $('.sku_combination').html(data);
                        if ($('#is_physical').is(":checked")){
                            $('.variant_physical_div').show();
                            $('.variant_digital_div').hide();
                        }else{
                            $('.variant_physical_div').hide();
                            $('.variant_digital_div').show();
                        }
                    }
                });
            }

            $(document).on('change', '.variant_img_change', function(event){
                let name_id = $(this).data('name_id');
                let img_id = $(this).data('img_id');
                getFileName($(this).val(), name_id);
                imageChangeWithFile($(this)[0], img_id);
            });

            $(document).on('change', '#is_physical', function(event){
                var product_type = $('#product_type').val();
                if (product_type ==1) {
                    if ($('#is_physical').is(":checked"))
                    {
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').hide();
                        $('.digital_file_upload_div_single').hide();
                        shipping_div_show();
                        weightHeightDivShow();
                    }else{
                        $('#phisical_shipping_div').hide();
                        $('.digital_file_upload_div_single').show();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }else {
                    if($('#is_physical').is(":checked")){
                        $('#phisical_shipping_div').show();
                        $('.digital_file_upload_div_single').hide();
                        $('.variant_physical_div').show();
                        $('.variant_digital_div').hide();
                        shipping_div_show();
                        weightHeightDivShow();
                    }else{
                        $('.variant_physical_div').hide();
                        $('.digital_file_upload_div_single').hide();
                        $('.variant_digital_div').show();
                        $('#phisical_shipping_div').hide();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }

                if ($('#is_physical').is(":checked")){
                    $('#is_physical_prod').val(1);
                }else{
                    $('#is_physical_prod').val(0);
                }

            });

            function weightHeightDivShow(){
                let weight_height_div = $('.weight_height_div');
                weight_height_div.show()
                $("#weight").attr('disabled', false);
                $("#length").attr('disabled', false);
                $("#breadth").attr('disabled', false);
                $("#height").attr('disabled', false);
            }

            function weightHeightDivHide(){
                let weight_height_div = $('.weight_height_div');
                weight_height_div.hide()
                $("#weight").attr('disabled', true);
                $("#length").attr('disabled', true);
                $("#breadth").attr('disabled', true);
                $("#height").attr('disabled', true);
            }

            $(document).on('change', '#choice_attributes', function() {

                var a_id = $(this).val();
                var a_name = $(this).text();
                $('#pre-loader').removeClass('d-none');
                var exsist = $('#attribute_id_'+a_id).length;
                if(exsist > 0){
                    toastr.error("{{__('marketing.this_item_already_added_to_list')}}");
                    $('#pre-loader').addClass('d-none');
                    $('#choice_attributes').val('');
                    $('#choice_attributes').niceSelect('update');
                    return false;
                }
                $.post('{{ route('product.attribute.values') }}', {
                    _token: '{{ csrf_token() }}',
                    id: a_id
                },
                function(data) {
                    $('#customer_choice_options').append(data);
                    $('select').niceSelect();
                    $('#pre-loader').addClass('d-none');
                    $('#choice_attributes').val('');
                    $('#choice_attributes').niceSelect('update');
                });

            });

            $(document).on('click', '.attribute_remove', function(){
                let this_data = $(this)[0];
                delete_product_row(this_data);
                $('.sku_combination').html('');
            });
            function delete_product_row(this_data){
                let row = this_data.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }

            $(document).on('change', '#tax_type', function(event){
                let id = $(this).val();
                let data = {
                    _token:"{{csrf_token()}}",
                    id:id
                }
                $('#pre-loader').removeClass('d-none');
                $.post("{{route('product.change-gst-group')}}", data, function(response){
                    $('#gst_list_div').html(response);
                    $('#pre-loader').addClass('d-none');
                });
            });

            function shipping_div_hide()
            {
                $('.shipping_title_div').hide();
                $('.shipping_type_div').hide();
                $('.shipping_cost_div').hide();
                $('#shipping_cost').val(0);
            }

            function shipping_div_show()
            {
                $('.shipping_title_div').show();
                $('.shipping_type_div').show();
                $('.shipping_cost_div').show();
                $('#shipping_cost').val(0);
            }


            function getActiveFieldShipping()
            {
                var shipping_type = $('#shipping_type').val();
                if (shipping_type == 1) {
                    $('.shipping_cost_div').hide();
                    $('#shipping_cost').val(0);
                }else {
                    $('.shipping_cost_div').show();
                    $('#shipping_cost').val(0);
                }
            }

            function galleryImage(data, divId){

                if(data.files){

                    $.each( data.files, function(key,value) {
                        $('#gallery_img_prev').empty();
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#gallery_img_prev').append(
                            `
                                <div class="galary_img_div">
                                    <img class="galaryImg" src="`+ e.target.result +`" alt="">
                                </div>
                            `
                        );

                        };
                        reader.readAsDataURL(value);
                    });
                }

            }

            $(document).on('click','.saveBtn',function() {
                $('#error_weight').text('');
                $('#error_length').text('');
                $('#error_breadth').text('');
                $('#error_height').text('');
                $('#error_product_id').text('');
                @if(isModuleActive('FrontendMultiLang'))
                    $('#error_product_new_name_{{auth()->user()->lang_code}}').text('');
                @else
                    $('#error_product_new_name').text('');
                @endif
                $('#error_category_ids').text('');
                $('#error_unit_type').text('');
                $('#error_minumum_qty').text('');
                $('#error_selling_price').text('');
                $('#error_tax').text('');
                $('#error_discunt').text('');
                $('#error_thumbnail').text('');
                $('#error_shipping_method').text('');
                $('#error_tags').text('');
                var requireMatch = 0;
                @if(isModuleActive('FrontendMultiLang'))
                    if ($("#product_name_new_{{auth()->user()->lang_code}}").val() === '') {
                        requireMatch = 1;
                        $('#error_product_new_name_{{auth()->user()->lang_code}}').text("{{ __('product.please_input_product_name') }}");
                    }
                @else
                    if ($("#product_name_new").val() === '') {
                        requireMatch = 1;
                        $('#error_product_new_name').text("{{ __('product.please_input_product_name') }}");
                    }
                @endif
                if ($("#category_id").val().length < 1) {
                    requireMatch = 1;
                    $('#error_category_ids').text("{{ __('product.please_select_category') }}");
                }
                if ($("#unit_type_id").val() === null) {
                    requireMatch = 1;
                    $('#error_unit_type').text("{{ __('product.please_select_product_unit') }}");
                }
                if (parseInt($("#minimum_order_qty").val()) < 1 || $("#minimum_order_qty").val() === '') {
                    requireMatch = 1;
                    $('#error_minumum_qty').text("{{ __('product.please_input_minimum_order_qty') }}");
                }

                if ($("#selling_price").val() === '') {
                    requireMatch = 1;
                    $('#error_selling_price').text("{{ __('product.please_input_selling_price') }}");
                }
                if ($("#tax").val() === '') {
                    requireMatch = 1;
                    $('#error_tax').text("{{ __('product.please_input_tax') }}");
                }
                if ($("#discount").val() === '') {
                    requireMatch = 1;
                    $('#error_discunt').text("{{ __('product.please_input_discount_minimum_0') }}");
                }
                // if ($(".image_selected_files").val() === '') {
                //     requireMatch = 1;
                //     $('#error_thumbnail').text("{{ __('product.please_upload_thumnail_image') }}");
                // }

                if ($("#tag-input-upload-shots").val() === '') {
                    requireMatch = 1;
                    $('#error_tags').text("{{ __('product.please_input_tags') }}");
                }
                if ($('#product_type').val() === '2' && $(".choice_attribute").val().length === 0) {
                    requireMatch = 1;
                    toastr.warning("{{ __('product.please_select_attribute') }}");
                }
                if (requireMatch == 1) {
                    event.preventDefault();
                }
            });

            $(document).on('click','#save_button_parent', function(event){
                $('#error_product_id').text("");
                var requireMatch = 0;
                if ($(".product_id").val() === null) {
                    requireMatch = 1;
                    $('#error_product_id').text("{{ __('defaultTheme.please_select_product_first') }}");
                }
                if (requireMatch == 1) {
                    event.preventDefault();
                }
            });

            function deleteRow(btn) {
                var row = btn.parentNode;
                row.parentNode.removeChild(row);
            }


            //Add more Whole-Sale price for Single Product
            $(document).on('click','.add_single_whole_sale_price',function () {
                $('.whole_sale_price_list:last').after(`<tr class="whole_sale_price_list whole_sale_price_list_child">
                                <td class="pl-0 pb-0 pt-2 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_0[]">
                                </td>
                                <td class="pl-0 pb-0 pt-2 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_0[]">
                                </td>
                                <td class="pl-0 pb-0 pt-2 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_0[]">
                                </td>
                                <td class="pl-0 pb-0 pt-2 remove_whole_sale border-0">
                                    <button type="button" class="btn close style_close_icon">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </td>
                        </tr>`);
            });

            $(document).on('click', '.remove_whole_sale', function () {
                $(this).parents('.whole_sale_price_list').remove();
            });


            //Add more Whole-Sale price for Variant Product
            $(document).on('click','.add_variant__whole_sale_price',function () {
                var targetModalId = $(this).data('id');
                var incKey = $(this).attr('incKey');

                $(targetModalId).append(`<div class="col-lg-12 variant_whole_sale_price_list">
                            <div class="row mt-2">
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_v_${incKey}[]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_v_${incKey}[]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_v_${incKey}[]">
                                </div>
                                <div class="col">
                                    <button type="button" class="float-left mt-2 style_plus_icon remove_variant_whole_sale border-0">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`);
            });

            $(document).on('click', '.remove_variant_whole_sale', function () {
                $(this).parents('.variant_whole_sale_price_list').remove();
            });

            //Append wholesale price in sku table
            $(document).on('click', '.wholesale_p_save_btn', function (){
                var append_w_priceId = $(this).attr('append_w_priceId');
                var w_incKey = $(this).attr('w_incKey');
                $('#append_w_p'+append_w_priceId).empty();

                var wholesale_min_qty_v = $('input[name="wholesale_min_qty_v_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();
                var wholesale_max_qty_v = $('input[name="wholesale_max_qty_v_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();
                var wholesale_price_v = $('input[name="wholesale_price_v_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();

                var w_s_p_list=[];
                for (var w=0; w<wholesale_min_qty_v.length; w++){
                    // console.log(wholesale_min_qty_v[w]);
                    w_s_p_list[w] = "<li>Range:("+wholesale_min_qty_v[w]+"-"+wholesale_max_qty_v[w]+")     $"+wholesale_price_v[w]+"</li>";
                }

                $('#append_w_p'+append_w_priceId).append(w_s_p_list);
                $('#variant_wholesale_price_modal_'+append_w_priceId).modal('toggle');
            });



        // tag
        $(document).on('click', '.tag-add', function(e){
            e.preventDefault();
            $('#tag-input-upload-shots').tagsinput('add', $(this).text());
        });
        $(document).on('focusout', '#product_name_new', function(){
            // tag get
            $("#tag_show").html('<li></li>');
            var sentence = $(this).val();
            $.get('/setup/getTagBySentence',{sentence:sentence},function(result){
                $("#tag_show").append(result);
            })
        });

        dynamicSelect2WithAjax("#brand_id", "{{route('product.brands.get-by-ajax')}}", "GET");
        dynamicSelect2WithAjax("#category_id", "{{url('/products/get-category-data')}}", "GET");
        dynamicSelect2WithAjax("#main_product_for_select", "{{url('/products/get-by-ajax')}}", "GET");

        @if(isModuleActive('FrontendMultiLang'))
            $(document).on('click', '.default_lang', function(event){
                var lang = $(this).data('id');
                if (lang == "{{auth()->user()->lang_code}}") {  
                    $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
                }
            });
            if ("{{auth()->user()->lang_code}}") {  
                    $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
            }
        @endif


        });
    })(jQuery);

</script>

@endpush
