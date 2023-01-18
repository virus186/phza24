<div class="col-xl-12">
    <div class="primary_input">
        <ul id="theme_nav" class="permission_list sms_list ">
            <li>
                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                    <input name="status" id="status" value="1" {{$data->status?'checked':''}} type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <p>{{ __('appearance.enable_this_section') }}</p>
            </li>
        </ul>
        <span class="text-danger" id="is_featured_error"></span>
    </div>
    <input type="hidden" id="form_for" name="form_for" value="{{$data->section_name}}">
    <input type="hidden" name="id" value="{{$data->id}}">
</div>
<div id="hide_for_top_bar" class="row">
    <div class="col-xl-12">
        <div class="primary_input mb-15">
            <label class="primary_input_label" for=""> {{ __('common.title') }} <span
                    class="text-danger">*</span></label>
            <input class="primary_input_field" name="title" id="title" placeholder="{{ __('common.title') }}"
                type="text" value="{{$data->title}}">
            <span class="text-danger" id="error_title"></span>
        </div>
    </div>

    <div class="col-lg-12 @if(app('theme')->folder_path != 'default') d-none @endif">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('appearance.column_size') }}</label>

            <select name="column_size" id="column_size" class="primary_select mb-15"
                data-value="{{$data->column_size}}">
                <option disabled selected>{{ __('common.select') }}</option>
                <option {{$data->column_size =='col-lg-3'?'selected':'' }} value="col-lg-3">
                    {{__('appearance.3_column')}}</option>
                <option {{$data->column_size =='col-lg-4'?'selected':'' }} value="col-lg-4">
                    {{__('appearance.4_column')}}</option>
                <option {{$data->column_size =='col-lg-6'?'selected':'' }} value="col-lg-6">
                    {{__('appearance.6_column')}}</option>
                <option {{$data->column_size =='col-lg-12'?'selected':'' }} value="col-lg-12">
                    {{__('appearance.12_column')}}</option>

            </select>
            <span class="text-danger" id="coulmn_size_error"></span>
        </div>
    </div>



    @if ($data->section_for ==1)
    <div id="for_product_type" class="col-lg-12">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('common.type') }}</label>
            <select name="type" id="type" class="primary_select mb-15 product_type">
                <option {{$data->type == 1?'selected':''}} value="1">{{__('frontendCms.category_products')}}</option>
                <option {{$data->type == 2?'selected':''}} value="2">{{__('frontendCms.latest_products')}}</option>
                <option {{$data->type == 3?'selected':''}} value="3">{{__('frontendCms.recently_viewed_products')}}
                </option>
                <option {{$data->type == 4?'selected':''}} value="4">{{__('frontendCms.max_sale')}}</option>
                <option {{$data->type == 5?'selected':''}} value="5">{{__('frontendCms.max_review')}}</option>
                <option {{$data->type == 6?'selected':''}} value="6">{{__('frontendCms.custom_products')}}</option>

            </select>
            <span class="text-danger" id="type_error"></span>
        </div>
    </div>
    @endif
    @if ($data->section_for ==2)
    <div id="for_product_type" class="col-lg-12">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('common.type') }}</label>
            <select name="type" id="type" class="primary_select mb-15 category_type">
                <option {{$data->type == 1?'selected':''}} value="1">{{__('frontendCms.top_category')}}</option>
                <option {{$data->type == 2?'selected':''}} value="2">{{__('frontendCms.latest_category')}}</option>
                <option {{$data->type == 3?'selected':''}} value="3">{{__('frontendCms.max_sale')}}</option>
                <option {{$data->type == 4?'selected':''}} value="4">{{__('frontendCms.max_review')}}</option>
                <option {{$data->type == 5?'selected':''}} value="5">{{__('Amount Of Product')}}</option>
                <option {{$data->type == 6?'selected':''}} value="6">{{__('frontendCms.custom_category')}}</option>

            </select>
            <span class="text-danger" id="type_error"></span>
        </div>
    </div>
    @endif

    @if ($data->section_for ==3)
    <div id="for_product_type" class="col-lg-12">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('common.type') }}</label>
            <select name="type" id="type" class="primary_select mb-15 brand_type">
                <option {{ $data->type == 1?'selected':'' }} value="1">{{__('frontendCms.top_brands')}}</option>
                <option {{ $data->type == 2?'selected':'' }} value="2">{{__('frontendCms.latest_brands')}}</option>
                <option {{ $data->type == 3?'selected':'' }} value="3">{{__('Featured Brands')}}</option>
                <option {{ $data->type == 4?'selected':'' }} value="4">{{__('frontendCms.max_sale')}}</option>
                <option {{ $data->type == 5?'selected':'' }} value="5">{{__('frontendCms.max_review')}}</option>
                <option {{ $data->type == 6?'selected':'' }} value="6">{{__('frontendCms.custom_brands')}}</option>

            </select>
            <span class="text-danger" id="type_error"></span>
        </div>
    </div>
    @endif
    @if ($data->section_for ==1)
        <div id="product_list_div" class="col-lg-12 {{$data->type != 6? 'd-none':''}}">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('appearance.product_list') }}</label>
                <select name="product_list[]" id="product_list" class="primary_select mb-15" multiple>
                    @foreach($products as $key => $product)
                    <option @if($data->products->where('seller_product_id',$product->id)->first()) selected @endif
                        value="{{$product->id}}">{{$product->product->product_name}} @if(isModuleActive('MultiVendor'))[ @if($product->seller->role->type == 'seller') {{$product->seller->first_name}} @else Inhouse @endif] @endif</option>
                    @endforeach


                </select>
                <span class="text-danger"></span>
            </div>
        </div>
    @endif
    @if ($data->section_for ==2)
    <div id="category_list_div" class="col-lg-12 {{$data->type != 6? 'd-none':''}}">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('common.category_list') }}</label>
            <select name="category_list[]" id="category_list" class="primary_select mb-15" multiple>
                @foreach($categories as $key => $category)
                <option @if($data->categories->where('category_id',$category->id)->first()) selected @endif
                    value="{{$category->id}}">{{$category->name}}</option>
                @endforeach

            </select>
            <span class="text-danger"></span>
        </div>
    </div>
    @endif

    @if ($data->section_for ==3)
    <div id="brand_list_div" class="col-lg-12 {{$data->type != 6? 'd-none':''}}">
        <div class="primary_input mb-25">
            <label class="primary_input_label" for="">{{ __('frontendCms.brand_list') }}</label>
            <select name="brand_list[]" id="brand_list" class="primary_select mb-15" multiple>
                @foreach($brands as $key => $brand)
                <option @if($data->brands->where('brand_id',$brand->id)->first()) selected @endif
                    value="{{$brand->id}}">{{$brand->name}}</option>
                @endforeach

            </select>
            <span class="text-danger"></span>
        </div>
    </div>
    @endif

    @if($data->section_for == 4 && $data->section_name == 'filter_category')
        <input type="hidden" name="type" value="{{$data->type}}">
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('common.category_list') }}</label>
                <select name="category" id="category" class="primary_select mb-15">
                    @foreach($categories->where('parent_id', 0) as $key => $category)
                        <option {{@$data->customSection->field_1 == $category->id?'selected':''}} value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                <span class="text-danger"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('Section Image') }} (330 X 860 PX)</label>
                <div class="primary_file_uploader">
                  <input class="primary-input" type="text" id="filter_category_image_file" placeholder="{{__('common.browse_image_file')}}" readonly="">
                  <button class="" type="button">
                      <label class="primary-btn small fix-gr-bg" for="filter_category_image">{{__("common.browse")}} </label>
                      <input type="file" class="d-none image_file" accept="image/*" name="filter_category_image" id="filter_category_image" data-name_id="#filter_category_image_file" data-view_id="#filter_category_image_show">
                  </button>
               </div>
                @error('filter_category_image')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="form_img_div">
                    <img id="filter_category_image_show" src="{{ showImage($data->customSection->field_2?$data->customSection->field_2:'backend/img/default.png') }}" alt="">
                </div>
            </div>
        </div>
    @elseif($data->section_for == 4 && $data->section_name == 'discount_banner')
        <input type="hidden" name="type" value="{{$data->type}}">
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('Section Image 1') }} (450 X 300 PX)</label>
                <div class="primary_file_uploader">
                    <input class="primary-input" type="text" id="banner_image_file_1" placeholder="{{__('common.browse_image_file')}}" readonly="">
                    <button class="" type="button">
                        <label class="primary-btn small fix-gr-bg" for="banner_image_1">{{__("common.browse")}} </label>
                        <input type="file" class="d-none image_file" accept="image/*" name="banner_image_1" id="banner_image_1" data-name_id="#banner_image_file_1" data-view_id="#banner_image_show_1">
                    </button>
                </div>
                @error('banner_image_1')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form_img_div">
                <img id="banner_image_show_1" src="{{ showImage($data->customSection->field_1?$data->customSection->field_1:'backend/img/default.png') }}" alt="">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="name">
                    {{__('Section 1 link')}}
                    <span class="text-danger">*</span>
                </label>
                <input class="primary_input_field" type="text" id="section_1_link" value="{{$data->customSection->field_4}}" name="section_1_link" autocomplete="off"  placeholder="{{__('common.link')}}">
                <span class="text-danger" id="error_section_1_link"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('Section Image 2') }} (450 X 300 PX)</label>
                <div class="primary_file_uploader">
                    <input class="primary-input" type="text" id="banner_image_file_2" placeholder="{{__('common.browse_image_file')}}" readonly="">
                    <button class="" type="button">
                        <label class="primary-btn small fix-gr-bg" for="banner_image_2">{{__("common.browse")}} </label>
                        <input type="file" class="d-none image_file" accept="image/*" name="banner_image_2" id="banner_image_2" data-name_id="#banner_image_file_2" data-view_id="#banner_image_show_2">
                    </button>
                </div>
                @error('banner_image_2')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form_img_div">
                <img id="banner_image_show_2" src="{{ showImage($data->customSection->field_2?$data->customSection->field_2:'backend/img/default.png') }}" alt="">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="name">
                    {{__('Section 2 link')}}
                    <span class="text-danger">*</span>
                </label>
                <input class="primary_input_field" type="text" id="section_2_link" value="{{$data->customSection->field_5}}" name="section_2_link" autocomplete="off"  placeholder="{{__('common.link')}}">
                <span class="text-danger" id="error_section_2_link"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="">{{ __('Section Image 3') }} (450 X 300 PX)</label>
                <div class="primary_file_uploader">
                    <input class="primary-input" type="text" id="banner_image_file_3" placeholder="{{__('common.browse_image_file')}}" readonly="">
                    <button class="" type="button">
                        <label class="primary-btn small fix-gr-bg" for="banner_image_3">{{__("common.browse")}} </label>
                        <input type="file" class="d-none image_file" accept="image/*" name="banner_image_3" id="banner_image_3" data-name_id="#banner_image_file_3" data-view_id="#banner_image_show_3">
                    </button>
                </div>
                @error('banner_image_3')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form_img_div">
                <img id="banner_image_show_3" src="{{ showImage($data->customSection->field_3?$data->customSection->field_3:'backend/img/default.png') }}" alt="">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="primary_input mb-25">
                <label class="primary_input_label" for="name">
                    {{__('Section 3 link')}}
                    <span class="text-danger">*</span>
                </label>
                <input class="primary_input_field" type="text" id="section_3_link" value="{{$data->customSection->field_6}}" name="section_3_link" autocomplete="off"  placeholder="{{__('common.link')}}">
                <span class="text-danger" id="error_section_3_link"></span>
            </div>
        </div>
    @endif

</div>
    @if (permissionCheck('frontendcms.homepage.update'))
    <div class="col-xl-6 offset-xl-3">
        <button class="primary_btn_2 mt-5" id="widget_form_btn"><i class="ti-check"></i>{{ __('common.update') }}
        </button>
    </div>
    @endif


