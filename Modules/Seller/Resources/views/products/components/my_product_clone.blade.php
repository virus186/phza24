@extends('backEnd.master')

@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/seller/css/my_product_clone.css'))}}" />

@endsection
@section('mainContent')
@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
<section class="admin-visitor-area up_st_admin_visitor">
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
        @csrf
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header common_table_header">
                    <div class="main-title d-md-flex">
                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.clone_product') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="white_box box_shadow_white mb-20">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title d-flex">
                                <h3 class="mb-2 mr-30">{{ __('product.product_information') }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">

                            <input type="hidden" value="{{ $product->product_type }}" name="product_type" id="product_type">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">{{ __('common.type') }} <span
                                        class="text-danger">*</span></label>
                                <ul id="theme_nav" class="permission_list sms_list ">
                                    <li>
                                        <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                            <input name="product_type" id="single_prod" value="1" disabled {{$product->product_type == 1?'checked':''}}
                                                class="active prod_type" type="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                        <p>{{ __('product.single') }}</p>
                                    </li>
                                    <li>
                                        <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                            <input name="product_type" value="2" id="variant_prod" disabled {{$product->product_type == 2?'checked':''}}
                                                class="de_active prod_type" type="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                        <p>{{ __('product.variant') }}</p>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <input type="hidden" name="id" value="{{ $product->id }}">
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
                                                    <input class="primary_input_field" name="product_sku[{{$language->code}}]" id="sku_single_{{$language->code}}" placeholder="{{ __('product.product_sku') }}" type="text" value="{{isset($product)?$product->skus->first()->sku:old('product_sku.'.$language->code)}}">
                                                    <span id="error_single_sku_{{$language->code}}" class="text-danger">{{ $errors->first('product_sku') }}</span>
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
                                <label class="primary_input_label" for=""> {{__("common.name")}} <span
                                        class="text-danger">*</span></label>
                                <input class="primary_input_field" name="product_name" id="product_name"
                                    placeholder="{{__("common.name")}}" type="text" value="{{ old('product_name')?old('product_name'):$product->product_name }}"
                                    >
                                <span class="text-danger" id="error_product_name">{{$errors->first('product_name')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 sku_single_div">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("product.product_sku")}}</label>
                                <input class="primary_input_field" name="product_sku" id="sku_single"
                                    placeholder="{{__("product.product_sku")}}" type="text" required="1"
                                    value="{{ $product->skus->first()->sku }}">
                                <span class="text-danger" id="error_single_sku">{{$errors->first('product_sku')}}</span>
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
                                <label class="primary_input_label" for="model_number">
                                    {{__("common.model_number")}}</label>
                                <input class="primary_input_field" name="model_number"
                                    placeholder="{{__("common.model_number")}}" type="text"
                                    value="{{old('model_number')?old('model_number'):$product->model_number}}">
                                <span class="text-danger">{{$errors->first('model_number')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.category') }}
                                    <span class="text-danger">*</span></label>
                                    @php
                                        $product_categories = $product->categories;
                                    @endphp
                                <select name="category_ids[]" id="category_id" class="mb-15 category" @if(app('general_setting')->multi_category == 1) multiple @elseif(isset($product) && count($product->categories) > 1) multiple @endif>
                                    
                                    @foreach ($product_categories as $key => $category)
                                        <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_category_ids">{{ $errors->first('category_id') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.brand') }}</label>
                                <select name="brand_id" id="brand_id" class="mb-15 brand">
                                    <option disabled selected>{{__('product.select_brand')}}</option>
                                    @if(old('brand_id'))
                                    @php
                                        $old_selected_brand = \DB::table('brands')->where('id', old('brand_id'))->first();
                                    @endphp
                                    <option value="{{$old_selected_brand->id}}" selected>{{$old_selected_brand->name}}</option>
                                    @elseif(isset($product))
                                        <option value="{{$product->brand_id}}" selected>{{$product->brand->name}}</option>
                                    @endif
                                </select>
                                <span class="text-danger">{{$errors->first('brand_id')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.unit') }}</label>
                                <select name="unit_type_id" id="unit_type_id" class="primary_select mb-15 unit">
                                    <option disabled selected>{{__('product.select_unit')}}</option>
                                    @foreach($units as $key => $unit)
                                    <option value="{{$unit->id}}" @if ($product->unit_type_id == $unit->id) selected
                                        @endif>{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error_unit_type">{{$errors->first('unit_type_id')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="">{{__('product.barcode_type')}}</label>
                                <select name="barcode_type" id="barcode_type" class="primary_select mb-15">
                                    @foreach (barcodeList() as $key => $barcode)
                                        <option value="{{ $barcode }}" @if($key==0) selected @endif>
                                            {{ $barcode }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{$errors->first('barcode_type')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("product.minimum_order_qty")}} <span
                                        class="text-danger">*</span></label>
                                <input class="primary_input_field" name="minimum_order_qty" id="minimum_order_qty"
                                    value="{{ $product->minimum_order_qty }}" type="number" min="1" step="0"
                                    required="1">
                                <span class="text-danger" id="error_minumum_qty">{{$errors->first('minimum_order_qty')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("product.max_order_qty")}}</label>
                                <input class="primary_input_field" name="max_order_qty" type="number" min="0"
                                    step="0" value="{{ $product->max_order_qty }}">
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

                                <label for="">@lang('blog.tags') (@lang('product.comma_separated')) <span class="text-danger">*</span></label>
                                <div class="tagInput_field mb_26">
                                    @php
                                    $tags =[];
                                    foreach($product->tags as $tag){
                                    $tags[] = $tag->name;
                                    }
                                    $tags = implode(',',$tags);
                                    @endphp
                                    <input name="tags" class="tag-input" id="tag-input-upload-shots" type="text" value="{{$tags}}" data-role="tagsinput" />
                                </div>

                                <span class="text-danger" id="error_tags">{{$errors->first('tags')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-12 attribute_div">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.attribute') }}</label>
                                <select name="choice_attributes" id="choice_attributes" class="primary_select mb-15">
                                    <option value="" selected disabled>{{__('product.select_attribute')}}</option>
                                    @foreach($attributes as $key => $attribute)
                                    <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{$errors->first('attribute_id')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="customer_choice_options" id="customer_choice_options">
                                @foreach ($product->variations->unique("attribute_id") as $key => $choice_option)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="hidden" name="choice_no[]" id="attribute_id_{{ $choice_option->attribute_id }}" value="{{ $choice_option->attribute_id }}">
                                        <div class="primary_input mb-25"><input class="primary_input_field" width="40%"
                                                name="choice[]" type="text"
                                                value="{{ \Modules\Product\Entities\Attribute::find($choice_option->attribute_id)->name }}"
                                                readonly></div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="primary_input mb-25">
                                            <select name="choice_options_{{ $choice_option->attribute_id }}[]"
                                                id="choice_options" class="primary_select mb-15 choice_attribute" multiple>
                                                @foreach ($choice_option->attribute->values as $key => $value)
                                                <option value="{{ $value->id }}" @if ($product->
                                                    variations->where('attribute_value_id', $value->id)->first()) selected
                                                    @endif>{{ $value->color ? $value->color->name : $value->value }}</option>
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
                        <div class="col-lg-12 sku_combination">
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
                                            <input name="" id="is_physical" {{$product->is_physical == 1?'checked':''}}
                                                value="1" type="checkbox">
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


                        <div class="col-lg-12 digital_file_upload_div_edit" style="display: {{$product->is_physical == 0?'block':'none'}}">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.program_file_upload') }}</label>
                                <div class="primary_file_uploader">
                                    <input class="primary-input" type="text" id="digital_file_place"
                                        placeholder="{{ __('common.upload_file') }}" readonly="">
                                    <button class="" type="button">
                                        <label class="primary-btn small fix-gr-bg" for="digital_file">{{ __('product.Browse') }}
                                        </label>
                                        <input type="file" class="d-none" name="digital_file" id="digital_file">
                                    </button>
                                </div>
                                <span class="text-danger">{{ $errors->first('documents') }}</span>
                            </div>
                        </div>

                        <div id="phisical_shipping_div" class="col-lg-12" style="display: {{$product->is_physical == 0?'none':'block'}}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="additional_shipping">{{ __('product.additional_shipping_charge') }}
                                        </label>
                                        <input class="primary_input_field" name="additional_shipping" placeholder="{{ __('product.tax') }}" type="number" min="0" step="{{step_decimal()}}" value="{{ $product->skus->first()->additional_shipping }}">
                                        <span class="text-danger">{{ $errors->first('additional_shipping') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 selling_price_div">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("product.selling_price")}} <span
                                        class="text-danger">*</span></label>
                                <input class="primary_input_field" name="selling_price" id="selling_price"
                                    placeholder="{{__("product.selling_price")}}" type="number" min="0" step="{{step_decimal()}}"
                                    value="{{ $product->skus->first()->selling_price }}" required>
                                <span class="text-danger" id="error_selling_price">{{$errors->first('selling_price')}}</span>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("product.discount")}}</label>
                                <input class="primary_input_field" name="discount" id="discount"
                                    placeholder="{{__("product.discount")}}" type="number" min="0" step="{{step_decimal()}}"
                                    value="{{ $product->discount }}">
                                <span class="text-danger" id="error_discunt">{{$errors->first('discount')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('product.discount_type') }}</label>
                                <select class="primary_select mb-25" name="discount_type" id="discount_type">
                                    <option value="1" @if ($product->discount_type == 1) selected
                                        @endif>{{ __('common.amount') }}</option>
                                    <option value="0" @if ($product->discount_type == 0) selected
                                        @endif>{{ __('common.percentage') }}</option>
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
                                                <textarea class="summernote" id="specification" name="specification[{{$language->code}}]">{{isset($product)?$product->getTranslation('specification',$language->code):old('specification.'.$language->code)}}</textarea>
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
                                <h3 class="mb-3 mr-30">{{ __('common.description') }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("common.description")}} </label>
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
                                <textarea class="summernote" id="specification"
                                    name="specification">{{ $product->specification }}</textarea>
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
                                <input class="primary_input_field" name="meta_title" placeholder="{{__("common.meta_title")}}"
                                    type="text" value="{{ $product->meta_title }}">
                                <span class="text-danger">{{$errors->first('meta_title')}}</span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for=""> {{__("common.meta_description")}}</label>
                                <textarea class="primary_textarea height_112 meta_description"
                                    placeholder="{{ __('common.meta_description') }}" name="meta_description"
                                    spellcheck="false">{{ $product->meta_description }}</textarea>
                                <span class="text-danger">{{$errors->first('meta_description')}}</span>
                            </div>
                        </div>
                    @endif
                        <div class="col-lg-12">
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
                                    @if(@$product->meta_image_media->media_id)
                                        <input type="hidden" name="meta_image" class="product_images_hidden" value="{{@$product->meta_image_media->media_id}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-lg-4">
            <div class="white_box box_shadow_white">
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
                                @php
                                    if($product->media_ids){
                                        $media_ids = explode(',',$product->media_ids);
                                    }else{
                                        $media_ids = [];
                                    }
                                @endphp
                                @foreach($media_ids as $media_id)
                                    <input type="hidden" name="images[]" class="product_images_hidden" value="{{$media_id}}">
                                @endforeach
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
                            <label class="primary_input_label" for="">{{__('product.pdf_specifications')}}</label>
                            <div class="primary_file_uploader">
                                <input class="primary-input" type="text" id="placeholderFileOneName"
                                    placeholder="{{ __('common.upload_pdf') }}" readonly="">
                                <button class="" type="button">
                                    <label class="primary-btn small fix-gr-bg" for="pdf_file">{{__("common.browse")}}
                                    </label>
                                    <input type="file" class="d-none" name="pdf_file" id="pdf_file">
                                </button>
                            </div>
                            <span class="text-danger">{{$errors->first('documents')}}</span>
                        </div>
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
                                <option value="youtube" @if ($product->video_provider == "youtube") selected
                                    @endif>{{ __('product.youtube') }}</option>
                                <option value="daily_motion" @if ($product->video_provider == "daily_motion") selected
                                    @endif>{{ __('product.daily_motion') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="primary_input mb-15">
                            <label class="primary_input_label" for=""> {{__("product.video_link")}}</label>
                            <input class="primary_input_field" name="video_link"
                                placeholder="{{__("product.video_link")}}" type="text"
                                value="{{ $product->video_link }}">
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
                    @php
                    $user = auth()->user();
                    @endphp
                    <input type="hidden" name="request_from" value="@if($user->role->type == 'seller') seller_product_form @else inhouse_product_form @endif">

            <div class="col-12 text-center">
                <button class="primary_btn_2 mt-5 text-center saveBtn"><i class="ti-check"></i>{{ __('common.save') }}
                </button>
            </div>

        </div>
        </div>
        </div>
        </div>



    </form>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
    (function($){
        "use strict";
        $(document).ready(function () {
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
            get_combinations();

            $(document).on('change', '#digital_file', function(){
                getFileName($(this).val(),'#digital_file_place');
            });



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
                }, function(data) {
                    $('#customer_choice_options').append(data);
                    $('select').niceSelect();
                    $('#choice_attributes').val('');
                    $('#choice_attributes').niceSelect('update');
                    $('#pre-loader').addClass('d-none');
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

            $(document).on('change', '.prod_type', function(){
                if($('#product_type').val($(this).val())){
                    getActiveFieldAttribute();
                }
            });

            $(document).on('change', '#choice_options', function(){
                get_combinations();
            });

            $(document).on('change', '#meta_image', function(){
                getFileName($(this).val(),'#meta_image_file');
                imageChangeWithFile($(this)[0],'#MetaImgDiv');
            });

            $(document).on('change', '#thumbnail_image', function(){
                getFileName($(this).val(),'#thumbnail_image_file');
                imageChangeWithFile($(this)[0],'#ThumbnailImg');
            });

            $(document).on('change', '#galary_image', function(){
                galleryImage($(this)[0],'#galler_img_prev');
            });

            $(document).on('change', '.variant_img_change', function(event){
                let name_id = $(this).data('name_id');
                let img_id = $(this).data('img_id');
                getFileName($(this).val(), name_id);
                imageChangeWithFile($(this)[0], img_id);
            });

            $(document).on('change', '#pdf_file', function(){
                getFileName($(this).val(),'#placeholderFileOneName');
            });




            function get_combinations(el){
                $('#pre-loader').removeClass('d-none');
                $.ajax({
                    type:"POST",
                    url:'{{ route('product.sku_combination_edit') }}',
                    data:$('#choice_form').serialize(),
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                    success: function(data){
                        $('.sku_combination').html(data);
                        $('#pre-loader').addClass('d-none');
                        if ($('#is_physical').is(":checked")){
                            $('.variant_physical_div').show();
                            $('.variant_digital_div').hide();
                        }else{
                            $('.variant_physical_div').hide();
                            $('.variant_digital_div').show();
                        }
                        Amaz.uploader.previewGenerate();
                    }
                });
            }

            function getActiveFieldAttribute()
            {
                var product_type = $('#product_type').val();
                if (product_type == 1) {
                    $('.attribute_div').hide();

                    $('#phisical_shipping_div').show();
                    $('.variant_physical_div').hide();
                    $('.customer_choice_options').hide();
                    $('.sku_combination').hide();
                    $('.weight_single_div').show();

                    $('.sku_single_div').show();
                    $('.purchase_price_div').show();
                    $('.selling_price_div').show();
                    $("#sku_single").removeAttr("disabled");
                    $("#purchase_price").removeAttr("disabled");
                    $("#selling_price").removeAttr("disabled");
                }else {
                    $('.attribute_div').show();
                    $('.sku_single_div').hide();

                    $('#phisical_shipping_div').hide();
                    $('.variant_physical_div').show();
                    $('.sku_combination').show();
                    $('.customer_choice_options').show();
                    $('.weight_single_div').hide();

                    $('.purchase_price_div').hide();
                    $('.selling_price_div').hide();
                    $("#sku_single").attr('disabled', true);
                    $("#purchase_price").attr('disabled', true);
                    $("#selling_price").attr('disabled', true);
                }
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

            $(document).on('click','.saveBtn',function(event) {
                $('#error_weight').text('');
                $('#error_length').text('');
                $('#error_breadth').text('');
                $('#error_height').text('');
                $('#error_product_id').text('');
                @if(isModuleActive('FrontendMultiLang'))
                    $('#error_product_name_{{auth()->user()->lang_code}}').text('');
                @else
                    $('#error_product_name').text('');
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
                    if ($("#product_name_{{auth()->user()->lang_code}}").val() === '') {
                        requireMatch = 1;
                        $('#error_product_name_{{auth()->user()->lang_code}}').text("{{ __('product.please_input_product_name') }}");
                    }
                @else
                    if ($("#product_name").val() === '') {
                        requireMatch = 1;

                        $('#error_product_name').text("{{ __('product.please_input_product_name') }}");
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

            if ($('#is_physical').is(":checked")){
                weightHeightDivShow();
            }else {
                weightHeightDivHide();
            }

            $(document).on('change', '#is_physical', function(event){
                var product_type = $('#product_type').val();
                if (product_type ==1) {
                    if ($('#is_physical').is(":checked"))
                    {
                        shipping_div_show();
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').hide();
                        $('.digital_file_upload_div_edit').hide();
                        weightHeightDivShow();
                    }else{
                        $('#phisical_shipping_div').hide();
                        $('.digital_file_upload_div_edit').show();

                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }else {
                    if($('#is_physical').is(":checked")){
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').show();
                        $('.variant_digital_div').hide();
                        $('.digital_file_upload_div_edit').hide();
                        shipping_div_show();
                        weightHeightDivShow();
                    }else{
                        $('.variant_physical_div').hide();
                        $('.variant_digital_div').show();
                        $('.digital_file_upload_div_edit').hide();
                        $('#phisical_shipping_div').hide();
                        shipping_div_hide();
                        weightHeightDivHide();

                    }
                }

                if ($('#is_physical').is(":checked")){
                    $('#is_physical_prod').val(1);
                    $('.shipping_title_div').show();
                    $('#shipping_method_div').show();
                }else{
                    $('#is_physical_prod').val(0);
                    $('.shipping_title_div').hide();
                    $('#shipping_method_div').hide();
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

            function shipping_div_hide() {
                $('.shipping_title_div').hide();
                $('.shipping_type_div').hide();
                $('.shipping_cost_div').hide();
                $('#shipping_cost').val(0);
            }

            function shipping_div_show() {
                $('.shipping_title_div').show();
                $('.shipping_type_div').show();
                $('.shipping_cost_div').show();
                $('#shipping_cost').val(0);
            }
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
            dynamicSelect2WithAjax("#brand_id", "{{route('product.brands.get-by-ajax')}}", "GET");
            dynamicSelect2WithAjax("#category_id", "{{url('/products/get-category-data')}}", "GET");

        });
    })(jQuery);



</script>
@endpush
