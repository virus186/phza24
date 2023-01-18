@extends('backEnd.master')

@section('styles')
<link rel="stylesheet" href="{{ asset(asset_path('backend/vendors/css/icon-picker.css')) }}" />

<link rel="stylesheet" href="{{asset(asset_path('modules/product/css/product_clone.css'))}}" />
@endsection
@section('mainContent')

@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-20 white_box">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
                @csrf
                <input type="hidden" value="{{ $product->id }}" id="product_id">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.clone_product') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs justify-content-end mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" href="#GenaralInfo" role="tab" data-toggle="tab" id="1"
                            aria-selected="true">{{__('product.general_information')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link show" href="#RelatedProduct" role="tab" data-toggle="tab" id="2"
                            aria-selected="false">{{__('product.related_product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link show" href="#UpSale" role="tab" data-toggle="tab" id="3"
                            aria-selected="false">{{__('common.up_sale')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link show" href="#CrossSale" role="tab" data-toggle="tab" id="4"
                            aria-selected="true">{{__('common.cross_sale')}}</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active show" id="GenaralInfo">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="white_box pt-0 box_shadow_white mb-20 p-15">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-2 mr-30">{{ __('product.product_information') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">

                                            <input type="hidden" value="{{ $product->product_type }}" id="product_type">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for=""><strong>{{ __('common.type') }}</strong> @if($product->product_type == 2) ({{__('product.variant_product_is_not_changable_to_single_product')}}) @endif<span
                                                        class="text-danger">*</span></label>
                                                <ul id="theme_nav" class="permission_list sms_list ">
                                                    <li>
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input name="product_type" id="single_prod" value="1" {{$product->product_type == 2?'disabled':''}} {{$product->product_type == 1?'checked':''}}
                                                                class="active prod_type" type="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('product.single') }}</p>
                                                    </li>
                                                    <li>
                                                        <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                            <input name="product_type" value="2" id="variant_prod" {{$product->product_type == 2?'disabled':''}} {{$product->product_type == 2?'checked':''}}
                                                                class="de_active prod_type" type="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('product.variant') }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        @if($product->product_type == 2)
                                        <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                                        @endif
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
                                                                   <input class="primary_input_field" name="product_name[{{$language->code}}]" id="product_name_{{$language->code}}" placeholder="{{ __('common.name') }}" type="text" value="{{isset($product)?$product->getTranslation('product_name',$language->code):old('product_name.'.$language->code)}}">
                                                                   <span class="text-danger" id="error_product_name_{{$language->code}}">{{ $errors->first('product_name') }}</span>
                                                               </div>
                                                           </div>
                                                           <div class="col-lg-6 sku_single_div d-none" id="default_lang_{{$language->code}}">
                                                               <div class="primary_input mb-15">
                                                                   <label class="primary_input_label" for="sku_single"> {{ __('product.product_sku') }}</label>
                                                                   <input class="primary_input_field" name="product_sku[{{$language->code}}]" id="sku_single" placeholder="{{ __('product.product_sku') }}" type="text" value="{{isset($product)?$product->skus->first()->sku:old('product_sku.'.$language->code)}}">
                                                                   <span id="error_single_sku" class="text-danger">{{ $errors->first('product_sku') }}</span>
                                                               </div>
                                                           </div>
                                                           <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="subtitle_1"> {{ __('product.subtitle_1') }}</label>
                                                                <input class="primary_input_field" name="subtitle_1[{{$language->code}}]" id="subtitle_1" placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{isset($product)?$product->getTranslation('subtitle_1',$language->code):old('subtitle_1.'.$language->code)}}">
                                                                <span id="error_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="subtitle_2"> {{ __('product.subtitle_2') }}</label>
                                                                <input class="primary_input_field" name="subtitle_2[{{$language->code}}]" id="subtitle_2" placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{isset($product)?$product->getTranslation('subtitle_2',$language->code):old('subtitle_2.'.$language->code)}}">
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
                                                <label class="primary_input_label" for=""> {{__("common.name")}} <span class="text-danger">*</span></label>
                                                <input class="primary_input_field" name="product_name" id="product_name" placeholder="{{__("common.name")}}" type="text" value="{{ old('product_name')?old('product_name'):$product->product_name }}" required="1">
                                                <span class="text-danger" id="error_product_name">{{$errors->first('product_name')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 sku_single_div">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{ __('product.product_sku') }}</label>
                                                <input class="primary_input_field" name="product_sku" id="sku_single"
                                                    placeholder="{{ __('product.product_sku') }}" type="text"
                                                    value="{{ old('product_sku')??@$product->skus->first()->sku }}">
                                                <span class="text-danger" id="error_single_sku">{{ $errors->first('product_sku') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{ __('product.subtitle_1') }}</label>
                                                <input class="primary_input_field" name="subtitle_1" id="subtitle_1"
                                                    placeholder="{{ __('product.subtitle_1') }}" type="text" value="{{old('subtitle_1')?old('subtitle_1'):$product->subtitle_1}}">
                                                <span id="error_subtitle_1" class="text-danger">{{ $errors->first('subtitle_1') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 @if(!app('general_setting')->product_subtitle_show) d-none @endif">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{ __('product.subtitle_2') }}</label>
                                                <input class="primary_input_field" name="subtitle_2" id="subtitle_2"
                                                    placeholder="{{ __('product.subtitle_2') }}" type="text" value="{{old('subtitle_2')?old('subtitle_2'):$product->subtitle_2}}">
                                                <span id="error_subtitle_2" class="text-danger">{{ $errors->first('subtitle_2') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                        <div class="col-lg-3">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="model_number"> {{__("common.model_number")}}</label>
                                                <input class="primary_input_field" name="model_number" placeholder="{{__("common.model_number")}}" type="text" value="{{old('model_number')?old('model_number'):$product->model_number}}">
                                                <span class="text-danger">{{$errors->first('model_number')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3" id="category_select_div">
                                            @php
                                                $product_categories = $product->categories;
                                            @endphp
                                            @include('product::products.components._category_list_select',['product_categories' => $product_categories])
                                        </div>
                                        <div class="col-lg-3" id="brand_select_div">
                                            @include('product::products.components._brand_list_select')
                                        </div>
                                        <div class="col-lg-3" id="unit_select_div">
                                            @include('product::products.components._unit_list_select')
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">{{__('product.barcode_type')}}</label>
                                                <select name="barcode_type" id="barcode_type" class="primary_select mb-15">
                                                    @foreach (barcodeList() as $key => $barcode)
                                                        <option value="{{ $barcode }}" @if($barcode == @$product->skus->first()->barcode_type) selected @endif>{{ $barcode }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{$errors->first('barcode_type')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("product.minimum_order_qty")}} <span class="text-danger">*</span></label>
                                                <input class="primary_input_field" name="minimum_order_qty" id="minimum_order_qty" value="{{ $product->minimum_order_qty }}" type="number" min="1" step="0" required="1">
                                                <span class="text-danger" id="error_minumum_qty">{{$errors->first('minimum_order_qty')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("product.max_order_qty")}}</label>
                                                <input class="primary_input_field" name="max_order_qty" type="number" min="0" step="0" value="{{ $product->max_order_qty }}">
                                                <span class="text-danger">{{$errors->first('max_order_qty')}}</span>
                                            </div>
                                        </div>
                                        @if(isModuleActive('GoogleMerchantCenter'))
                                        <div class="col-lg-3">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.product_condition')
                                                    }}</label>
                                                <select class="primary_select mb-25" name="condition"
                                                    id="condition">
                                                    <option value="new" @if($product->condition == 'new') selected @endif>{{ __('product.new') }}</option>
                                                    <option value="used" @if($product->condition == 'used') selected @endif>{{ __('product.used') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{ __('common.gtin') }}</label>
                                                <input class="primary_input_field" name="gtin" id="gtin"
                                                    placeholder="{{ __('common.gtin') }}" type="text"
                                                    value="{{ $product->gtin }}">
                                                <span class="text-danger" id="error_gtin">{{ $errors->first('gtin') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{ __('common.mpn') }}</label>
                                                <input class="primary_input_field" name="mpn" id="mpn"
                                                    placeholder="{{ __('common.mpn') }}" type="text"
                                                    value="{{ $product->mpn }}">
                                                <span class="text-danger" id="error_mpn">{{ $errors->first('mpn') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">

                                                <label for="">@lang('blog.tags') (@lang('product.comma_separated'))<span class="text-danger">*</span></label>
                                                <div class="tagInput_field mb_26">
                                                    @php
                                                        $tags = [];
                                                        foreach ($product->tags as $tag) {
                                                            $tags[] = $tag->name;
                                                        }
                                                        $tags = implode(',', $tags);
                                                    @endphp
                                                    <input name="tags" class="tag-input" id="tag-input-upload-shots"
                                                        type="text" value="{{ $tags }}" data-role="tagsinput" />
                                                </div>
                                                <div class="suggeted_tags">
                                                    <label>@lang('blog.suggested_tags')</label><br>
                                                    <ul id="tag_show"  class="suggested_tag_show">
                                                    </ul>
                                                </div>
                                                <span class="text-danger" id="error_tags">{{ $errors->first('tags') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 attribute_div">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.attribute') }}</label>
                                                <select name="choice_attributes[]" id="choice_attributes" class="primary_select mb-15">
                                                    <option value="" selected disabled>{{__('product.select_attribute')}}</option>
                                                    @foreach($attributes as $key => $attribute)
                                                    <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                                    @endforeach
                                            </select>
                                            <span class="text-danger">{{$errors->first('choice_attributes')}}</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="customer_choice_options" id="customer_choice_options">
                                                @foreach ($product->variations->unique("attribute_id") as $key => $choice_option)
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <input type="hidden" name="choice_no[]" id="attribute_id_{{$choice_option->attribute_id}}" value="{{ $choice_option->attribute_id }}">
                                                            <div class="primary_input mb-25"><input class="primary_input_field" width="40%" name="choice[]" type="text" value="{{ \Modules\Product\Entities\Attribute::find($choice_option->attribute_id)->name }}" readonly></div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="primary_input mb-25">
                                                                <select name="choice_options_{{ $choice_option->attribute_id }}[]" id="choice_options" class="primary_select mb-15 choice_attribute" multiple>
                                                                    @foreach ($choice_option->attribute->values as $key => $value)
                                                                        <option value="{{ $value->id }}" @if ($product->variations->where('attribute_value_id', $value->id)->first()) selected @endif>{{ $value->color ? $value->color->name : $value->value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1 text-center">
                                                            <a class="btn cursor_pointer attribute_remove"><i class="ti-trash"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-12 sku_combination overflow-auto">

                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('product.price_info_and_stock') }}</h3>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="primary_input">
                                                <ul id="theme_nav" class="permission_list sms_list ">
                                                    <li>
                                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                            <input name="is_physical" id="is_physical" {{ $product->is_physical == 1 ? 'checked' : '' }} value="1" type="checkbox" >
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('product.is_physical_product') }}</p>
                                                        <input type="hidden" name="is_physical" id="is_physical_prod" value="{{$product->is_physical}}">
                                                    </li>
                                                </ul>

                                            </div>
                                        </div>

                                        <div class="col-lg-12 weight_height_div">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('product.weight_height_info') }}</h3>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.weight')}} [Gm]</label>
                                                        <input value="{{@$product->skus->first()->weight}}" class="primary_input_field" name="weight" id="weight"
                                                               type="number" min="0" step="{{step_decimal()}}">
                                                        <span class="text-danger" id="error_weight">{{ $errors->first('weight') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.length')}} [Cm]</label>
                                                        <input value="{{@$product->skus->first()->length}}" class="primary_input_field" name="length" id="length"
                                                               type="number" min="0" step="{{step_decimal()}}">
                                                        <span class="text-danger" id="error_length">{{ $errors->first('length') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.breadth')}} [Cm]</label>
                                                        <input value="{{@$product->skus->first()->breadth}}" class="primary_input_field" name="breadth" id="breadth"
                                                               type="number" min="0" step="{{step_decimal()}}">
                                                        <span class="text-danger" id="error_breadth">{{ $errors->first('breadth') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{ __('product.height')}} [Cm]</label>
                                                        <input value="{{@$product->skus->first()->height}}" class="primary_input_field" name="height" id="height"
                                                               type="number" min="0" step="{{step_decimal()}}">
                                                        <span class="text-danger" id="error_height">{{ $errors->first('height') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 digital_file_upload_div_edit" style="display: {{($product->is_physical == 1 || $product->product_type == 2)?'none':'block'}};">
                                            <div class="primary_input mb-25">
                                                @php
                                                $digital_file = @$product->skus->first()->digital_file->file_source;
                                            @endphp
                                            <label class="primary_input_label" for="pdf_place">{{ __('product.program_file_upload') }} <small> @if($digital_file) <a class="link_color" href="{{ asset(asset_path(@$product->skus->first()->digital_file->file_source)) }}" download>{{__('product.download_link')}}</a> @endif </small> </label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input" type="text" id="pdf_place" placeholder="{{__('product.upload_file')}}"
                                                        readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="pdf">{{ __('product.Browse') }} </label>
                                                        <input type="file" class="d-none" name="single_digital_file" id="pdf">
                                                    </button>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('documents') }}</span>
                                            </div>
                                        </div>

                                        <div id="phisical_shipping_div" class="col-lg-12" style="display: {{$product->is_physical == 0?'none':'block'}}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="additional_shipping">
                                                            {{ __('product.additional_shipping_charge') }}
                                                            </label>
                                                        <input class="primary_input_field" name="additional_shipping"
                                                                type="number"
                                                            min="0" step="{{step_decimal()}}"
                                                            value="{{ @$product->skus->first()->additional_shipping }}">
                                                        <span
                                                            class="text-danger">{{ $errors->first('additional_shipping') }}</span>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        @if(!isModuleActive('MultiVendor'))
                                            @php
                                                $frontend_product = $product->sellerProducts->where('user_id', 1)->first();
                                            @endphp
                                            <div class="col-lg-12" id="stock_manage_div">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">{{ __('Stock Manage') }}</label>
                                                    <select class="primary_select mb-25" name="stock_manage"
                                                        id="stock_manage">
                                                        <option value="1" {{@$frontend_product->stock_manage == 1?'selected':''}}>{{ __('common.yes') }}</option>
                                                        <option value="0" {{@$frontend_product->stock_manage == 0?'selected':''}}>{{ __('common.no') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-none" id="single_stock_div">
                                                <div class="primary_input mb-15">
                                                    <label class="primary_input_label" for="single_stock"> {{
                                                        __('product.product_stock') }}
                                                    </label>
                                                    <input class="primary_input_field" name="single_stock" id="single_stock"
                                                        type="number" min="0" step="0"
                                                        value="{{old('single_stock')?old('single_stock'):@$frontend_product->skus[0]->product_stock}}">
                                                    <span class="text-danger">{{ $errors->first('single_stock') }}</span>
                                                </div>
                                            </div>

                                        @endif

                                        <div class="col-lg-6 selling_price_div">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("product.selling_price")}} <span class="text-danger">*</span></label>
                                                <input class="primary_input_field selling_price" name="selling_price" id="selling_price" placeholder="{{__("product.selling_price")}}" type="number" min="0" step="{{step_decimal()}}" value="{{ @$product->skus->first()->selling_price }}" required>
                                                <span class="text-danger" id="error_selling_price">{{$errors->first('selling_price')}}</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("product.discount")}}</label>
                                                <input class="primary_input_field" name="discount" id="discount" placeholder="{{__("product.discount")}}" type="number" min="0" step="{{step_decimal()}}" value="{{ $product->discount }}">
                                                <span class="text-danger" id="error_discunt">{{$errors->first('discount')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.discount_type') }}</label>
                                                <select class="primary_select mb-25" name="discount_type" id="discount_type">
                                                    <option value="1" @if ($product->discount_type == 1) selected @endif>{{ __('common.amount') }}</option>
                                                    <option value="0" @if ($product->discount_type == 0) selected @endif>{{ __('common.percentage') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('gst.GST_group')
                                                    }}</label>
                                                <select class="primary_select mb-25" name="gst_group" id="tax_type">
                                                    <option value="" selected disabled>{{__('common.select_one')}}</option>
                                                    @foreach($gst_groups as $group)
                                                        <option value="{{$group->id}}" {{$group->id == $product->gst_group_id?'selected':''}}>{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="gst_list_div">
                                            @if($product->gst_group_id)
                                                @include('product::products.components._group_gst_list',['group' => $product->gstGroup])
                                            @endif
                                        </div>


                                        <!-- Whole-Sale -->
                                        @if (isModuleActive('WholeSale') && ($product->product_type==1))
                                            <div class="col-lg-12 whole_sale_info_add" id="whole_sale_info_add">
                                                <strong>{{ 'Whole-Sale Price' }}</strong>
                                                <div class="QA_section2 QA_section_heading_custom check_box_table">
                                                    <div class="QA_table mb_15">
                                                        <!-- table-responsive -->
                                                        <div class="table-responsive">
                                                            <table class="table create_table">
                                                                <tbody>
                                                                <tr class="whole_sale_price_list">
                                                                    <td class="pl-0 pb-0 border-0">
                                                                        <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_0[]">
                                                                    </td>
                                                                    <td class="pl-0 pb-0 border-0">
                                                                        <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_0[]">
                                                                    </td>
                                                                    <td class="pl-0 pb-0 border-0">
                                                                        <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_0[]">
                                                                    </td>

                                                                    <td class="pl-0 pb-0 pr-0 border-0">
                                                                        <div class="add_items_button pt-10">
                                                                            <button type="button" class="primary-btn radius_30px add_single_whole_sale_price  fix-gr-bg">
                                                                                <i class="ti-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- End Whole-Sale -->

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
                                                                <textarea class="summernote" name="description[{{$language->code}}]"> {{isset($product)?$product->getTranslation('description',$language->code):old('description.'.$language->code)}}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="main-title d-flex">
                                                                <h3 class="mb-3 mr-30">{{ __('product.specifications') }}</h3>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="primary_input mb-15">
                                                                <textarea class="summernote2" id="specification" name="specification[{{$language->code}}]">{{isset($product)?$product->getTranslation('specification',$language->code):old('specification.'.$language->code)}}</textarea>
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
                                                                <input class="primary_input_field" id="meta_title" name="meta_title[{{$language->code}}]" placeholder="{{ __('common.meta_title') }}" type="text" value="{{isset($product)?$product->getTranslation('meta_title',$language->code):old('meta_title.'.$language->code)}}">
                                                                <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="meta_description"> {{ __('common.meta_description') }}</label>
                                                                <textarea class="primary_textarea height_112 meta_description" id="meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description[{{$language->code}}]" spellcheck="false">{{isset($product)?$product->getTranslation('meta_description',$language->code):old('meta_description.'.$language->code)}}</textarea>
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
                                                <textarea class="summernote" name="description">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('product.specifications') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <textarea class="summernote2" id="specification" name="specification">{{ $product->specification }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('common.seo_info') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("common.meta_title")}}</label>
                                                <input class="primary_input_field" name="meta_title" placeholder="{{__("common.meta_title")}}" type="text" value="{{ $product->meta_title }}">
                                                <span class="text-danger">{{$errors->first('meta_title')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="meta_description"> {{ __('common.meta_description') }}</label>
                                                <textarea class="primary_textarea height_112 meta_description" id="meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description" spellcheck="false">{{ $product->meta_description }}</textarea>
                                                <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                        <div class="col-lg-8">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.meta_image') }}
                                                    (300x300)PX</label>
                                                <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="meta_image">
                                                    <input class="primary-input" type="text" id="meta_image_file"
                                                        placeholder="{{__('common.browse_image_file')}}" readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="meta_image">{{
                                                            __('product.meta_image') }} </label>
                                                        <input type="hidden" class="selected_files" value="{{@$product->meta_image_media->media_id}}">
                                                    </button>
                                                </div>
                                                <div class="product_image_all_div">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="white_box pt-0 box_shadow_white p-15">
                                    <div class="row image_section">
                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('product.product_image_info') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">

                                                <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="true" data-type="image" data-name="images[]">
                                                    <input class="primary-input file_amount" type="text" id="thumbnail_image_file"
                                                        placeholder="{{ __('Choose Images') }}" readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="thumbnail_image">{{
                                                            __('product.Browse') }} </label>

                                                        <input type="hidden" class="selected_files image_selected_files" value="{{$product->media_ids}}">
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
                                                <h3 class="mb-3 mr-30">{{ __('product.pdf_specifications') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="pdf_place1">{{ __('product.pdf_specifications') }}</label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input" type="text" id="pdf_place1" placeholder="{{__('product.upload_pdf')}}" readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="pdf_file">{{ __('common.browse') }} </label>
                                                        <input type="file" class="d-none" name="pdf_file" id="pdf_file">
                                                    </button>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('documents') }}</span>
                                            </div>
                                            @if(@$product->pdf)
                                            <a class="float-left anchore_color" href="{{ asset(asset_path($product->pdf)) }}" download>{{ __('product.download_file') }}</a>
                                            @endif
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('product.product_videos_info') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.video_provider') }}</label>
                                                <select class="primary_select mb-25" name="video_provider" id="video_provider">
                                                    <option value="youtube" @if ($product->video_provider == "youtube") selected @endif>{{ __('product.youtube') }}</option>
                                                    <option value="daily_motion" @if ($product->video_provider == "daily_motion") selected @endif>{{ __('product.daily_motion') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for=""> {{__("product.video_link")}}</label>
                                                <input class="primary_input_field" name="video_link" placeholder="{{__("product.video_link")}}" type="text" value="{{ $product->video_link }}">
                                                <span class="text-danger">{{$errors->first('video_link')}}</span>
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
                                                        <label data-id="bg_option"
                                                               class="primary_checkbox d-flex mr-12">
                                                            <input name="status" id="status_active" value="1" @if (@$product->status == 1) checked @endif class="active" type="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('common.publish') }}</p>
                                                    </li>
                                                    <li>
                                                        <label data-id="color_option"
                                                               class="primary_checkbox d-flex mr-12">
                                                            <input name="status" value="0" id="status_inactive" @if (@$product->status == 0) checked @endif  class="de_active"
                                                                   type="radio">
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
                                                        <label data-id="bg_option"
                                                               class="primary_checkbox d-flex mr-12">
                                                            <input name="display_in_details" id="status_active" value="1" @if ($product->display_in_details == 1) checked @endif class="active" type="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('common.up_sale') }}</p>
                                                    </li>
                                                    <li>
                                                        <label data-id="color_option"
                                                               class="primary_checkbox d-flex mr-12">
                                                            <input name="display_in_details" value="2" id="status_inactive" @if ($product->display_in_details == 2) checked @endif class="de_active"
                                                                   type="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{ __('common.cross_sale') }}</p>
                                                    </li>
                                                </ul>
                                                <span class="text-danger" id="status_error"></span>
                                            </div>
                                        </div>
                                        @if(isModuleActive('GoldPrice'))
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">{{
                                                        __('Auto update required') }} <span
                                                            class="text-danger">*</span></label>
                                                    <ul id="theme_nav" class="permission_list sms_list ">
                                                        <li>
                                                            <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                                <input name="auto_update_required" id="auto_update_required_active" value="1"
                                                                    {{$product->auto_update?'checked':''}} class="active" type="radio">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <p>{{ __('common.on') }}</p>
                                                        </li>
                                                        <li>
                                                            <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                                <input name="auto_update_required" value="0" id="auto_update_required_inactive"
                                                                {{!$product->auto_update?'checked':''}} class="de_active" type="radio">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <p>{{ __('common.off') }}</p>
                                                        </li>
                                                    </ul>
                                                    <span class="text-danger" id="auto_update_required_error"></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">{{ __('Gold Price')}}</label>
                                                    <select class="primary_select mb-25" name="gold_price_id" id="gold_price_id">
                                                        @foreach($gold_prices as $gold_price)
                                                            <option data-price="{{$gold_price->price}}" {{$product->gold_price_id == $gold_price->id?'selected':''}} value="{{$gold_price->id}}">{{$gold_price->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="primary_input mb-15">
                                                    <label class="primary_input_label" for="making_charge"> {{ __('Making Charge') }}
                                                    </label>
                                                    <input class="primary_input_field" name="making_charge" id="making_charge" placeholder="-" type="number" min="0"
                                                        step="{{step_decimal()}}" value="{{$product->making_charge}}">
                                                    <span class="text-danger" id="error_making_charge">{{ $errors->first('making_charge')}}</span>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="RelatedProduct">
                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.related_product') }}</h3>

                            </div>
                        </div>
                        <input class="primary_input_field" placeholder="Quick Search" type="text" id="rsearch_products">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table" id="related_product">
                                <!-- table-responsive -->
                                <div class="table-responsive" id="product_list_div">
                                    <table class="table">
                                        <thead>

                                            <tr>
                                                <th width="10%" scope="col">
                                                    <label class="primary_checkbox d-flex ">
                                                        <input type="checkbox" id="relatedProductAll">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </th>
                                                <th width="25%" scope="col">{{ __('common.name') }}</th>
                                                <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                                <th width="15%" scope="col">{{ __('product.thumbnail') }}</th>
                                                <th width="15%" scope="col">{{ __('product.created_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablecontentsrelatedProduct">
                                            @if(count(@$relatedProducts) > 0)
                                                @foreach (@$relatedProducts as $key => $relatedSale)

                                                <tr>
                                                    <th scope="col">
                                                        <label class="primary_checkbox d-flex">
                                                            <input name="related_product[]" id="related_product_{{$key}}" checked value="{{$relatedSale->main_product->id}}" type="checkbox" class="related_product_checked">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </th>
                                                    <td>{{ $relatedSale->main_product->product_name }}</td>
                                                    <td>{{ @$relatedSale->main_product->brand->name }}</td>
                                                    <td>
                                                        <div class="product_img_div">
                                                            <img class="product_list_img"
                                                                src="{{ showImage($relatedSale->main_product->thumbnail_image_source) }}"
                                                                alt="{{ $relatedSale->main_product->product_name }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ date(app('general_setting')->dateFormat->format, strtotime($relatedSale->main_product->created_at)) }}</td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="pagination-container">
                                        @php
                                        echo $relatedProducts->links();
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="related_product_hidden_name" id="related_product_hidden_id">
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="UpSale">

                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('common.up_sale') }}</h3>

                            </div>
                        </div>
                        <input class="primary_input_field" placeholder="Quick Search" type="text" id="upsale_search_products">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table" id="upsale_products">
                                <!-- table-responsive -->
                                <div class="table-responsive" id="product_list_div">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="10%" scope="col">
                                                    <label class="primary_checkbox d-flex ">
                                                        <input type="checkbox" id="upSaleAll">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </th>
                                                <th width="25%" scope="col">{{ __('common.name') }}</th>
                                                <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                                <th width="15%" scope="col">{{ __('product.thumbnail') }}</th>
                                                <th width="15%" scope="col">{{ __('product.created_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablecontentsupSaleAll">
                                            @if(count(@$upSales) > 0)
                                            @foreach ($upSales as $key => $upSale)
                                                <tr>
                                                    <th scope="col">
                                                        <label class="primary_checkbox d-flex">
                                                            <input name="up_sale[]" id="up_sale_{{$key}}" checked value="{{$upSale->main_product->id}}" type="checkbox" class="upsale_product_checked">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </th>
                                                    <td>{{ $upSale->main_product->product_name }}</td>
                                                    <td>{{ @$upSale->main_product->brand->name }}</td>
                                                    <td>
                                                        <div class="product_img_div">
                                                            <img class="product_list_img"
                                                                src="{{ showImage($upSale->main_product->thumbnail_image_source) }}"
                                                                alt="{{ $upSale->main_product->product_name }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ date(app('general_setting')->dateFormat->format, strtotime($upSale->main_product->created_at)) }}</td>

                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="pagination-container">
                                        @php
                                        echo $upSales->links();
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="upsale_product_hidden_name" id="upsale_product_hidden_id">
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="CrossSale">

                        <div class="box_header common_table_header ">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('common.cross_sale') }}</h3>

                            </div>
                        </div>
                        <input class="primary_input_field" placeholder="Quick Search" type="text" id="crosssale_search_products">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table" id="crosssale_products">
                                <!-- table-responsive -->
                                <div class="table-responsive" id="product_list_div">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="10%" scope="col">
                                                    <label class="primary_checkbox d-flex ">
                                                        <input type="checkbox" id="crossSaleAll">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </th>
                                                <th width="25%" scope="col">{{ __('common.name') }}</th>
                                                <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                                <th width="15%" scope="col">{{ __('product.thumbnail') }}</th>
                                                <th width="15%" scope="col">{{ __('product.created_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablecontentscrossSaleAll">
                                            @if(count(@$crossSales) > 0)
                                            @foreach ($crossSales as $key => $crossSale)
                                                <tr>
                                                    <th scope="col">
                                                        <label class="primary_checkbox d-flex">
                                                            <input name="cross_sale[]" id="cross_sale_{{$key}}" checked value="{{$crossSale->main_product->id}}" type="checkbox" class="crosssale_product_checked">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </th>
                                                    <td>{{ $crossSale->main_product->product_name }}</td>
                                                    <td>{{ @$crossSale->main_product->brand->name }}</td>
                                                    <td>
                                                        <div class="product_img_div">
                                                            <img class="product_list_img"
                                                                src="{{ showImage($crossSale->main_product->thumbnail_image_source) }}"
                                                                alt="{{ $crossSale->main_product->product_name }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ date(app('general_setting')->dateFormat->format, strtotime($crossSale->main_product->created_at)) }}</td>

                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="pagination-container">
                                        @php
                                        $crossSales->links();
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="crosssale_product_hidden_name" id="crosssale_product_hidden_id">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-warning mt-30 text-center">
                            {{__('product.save_information')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </div>
                    </div>
                    <input type="hidden" name="request_from" value="main_product_form">
                    <div class="col-12 text-center">
                        <button class="primary_btn_2 mt-5 text-center saveBtn"><i class="ti-check"></i>{{ __('common.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @include('product::products.components._create_category_modal')
    @include('product::products.components._create_brand_modal')
    @include('product::products.components._create_unit_modal')
    @include('product::products.components._create_shipping_modal')
@endsection
@include('product::products.clone_scripts')
