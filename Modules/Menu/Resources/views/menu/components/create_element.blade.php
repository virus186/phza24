<div class="row">

    <div id="formHtml" class="col-lg-12 mb-20">

        <div class="white-box minh-250">
            <div class="add-visitor">
                @if ($menu->menu_type == 'mega_menu')
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0 create-title" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="false"
                                        aria-controls="collapseOne">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_column')}}
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div id="row_element_div" class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="name">
                                                            {{ __('marketing.navigation_label') }} <span class="textdanger">*</span>

                                                        </label>
                                                        <input class="primary_input_field name" type="text" id="row"
                                                            name="row" autocomplete="off" placeholder="{{__('menu.column')}}" required>
                                                    </div>
                                                    <span class="text-danger" id="error_name"></span>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('menu.size') }} <span class="text-danger">*</span></label>
                                                        <select name="size" id="size" class="primary_select mb-15">
                                                            <option data-display="{{__('menu.select_size')}}" value="">
                                                                {{ __('menu.size') }}</option>
                                                            <option value="1/1">1/1</option>
                                                            <option value="1/2">1/2</option>
                                                            <option value="1/3">1/3</option>
                                                            <option value="1/4">1/4</option>
                                                        </select>
                                                        <span class="text-danger" id="error_size"></span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_row_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10 mt-10">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_links')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="title">
                                                            {{ __('common.title') }} <span class="text-danger">*</span>

                                                        </label>
                                                        <input class="primary_input_field title" type="text" id="title"
                                                            name="title" autocomplete="off" value="" placeholder="{{ __('common.title') }}"
                                                            required>
                                                    </div>
                                                    <span class="text-danger" id="error_name"></span>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="link">
                                                            {{ __('common.link') }}

                                                        </label>
                                                        <input class="primary_input_field link" type="text" id="link"
                                                            name="link" autocomplete="off" value="" placeholder="{{ __('common.link') }}"
                                                            required>
                                                    </div>
                                                    <span class="text-danger" id="error_name"></span>
                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_link_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{ __('menu.add_to_menu') }} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_categories')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('common.category') }} <span class="text-danger">*</span></label>
                                                        <select name="category" id="category" multiple
                                                            class="mb-15">
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>


                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_category_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingPages">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#pages" aria-expanded="false"
                                        aria-controls="collapsePages">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_pages')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="pages" class="collapse" aria-labelledby="headingPages"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('common.page') }} <span class="text-danger">*</span></label>
                                                        <select name="page" id="page" class="primary_select mb-15"
                                                            multiple>
                                                            @foreach ($pages as $key => $page)
                                                                <option value="{{ $page->id }}">
                                                                    {{ $page->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_page_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingProduct">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#products" aria-expanded="false"
                                        aria-controls="collapseProduct">
                                            <button class="btn btn-link add_btn_link ">
                                                {{__('menu.add_product')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="products" class="collapse" aria-labelledby="headingProduct"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{__('common.product')}} <span class="text-danger">*</span></label>
                                                        <select name="product" id="product" class="mb-15"
                                                            multiple>
                                                        </select>
                                                        <span
                                                            class="text-danger">{{ $errors->first('barcode_type') }}</span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_product_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-10">
                                    <div class="card-header" id="headingBrand">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#brands" aria-expanded="false"
                                        aria-controls="collapseBrand">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_brand')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="brands" class="collapse" aria-labelledby="headingBrand"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{__('product.brand')}} <span class="text-danger">*</span></label>
                                                        <select name="brand" id="brand" class="mb-15"
                                                            multiple>
                                                            
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_brand_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" id="headingTag">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#tags" aria-expanded="false" aria-controls="collapseTag">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_tag')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="tags" class="collapse" aria-labelledby="headingTag"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{ __('common.tag') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="tag" id="tag" class="mb-15"
                                                            multiple>
                                                            
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_tag_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($menu->menu_type == 'normal_menu')
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="accordion">
                                <div class="card mb-10 mt-10">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                            <button class="btn btn-link add_btn_link ">
                                                {{__('menu.add_links')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="title">
                                                            {{ __('common.title') }} <span class="text-danger">*</span>

                                                        </label>
                                                        <input class="primary_input_field title" type="text" id="title"
                                                            name="title" autocomplete="off" value="" placeholder="{{ __('common.title') }}"
                                                            required>
                                                    </div>
                                                    <span class="text-danger" id="error_name"></span>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="link">
                                                            {{ __('common.link') }}

                                                        </label>
                                                        <input class="primary_input_field link" type="text" id="link"
                                                            name="link" autocomplete="off" value="" placeholder="{{ __('common.link') }}"
                                                            required>
                                                    </div>
                                                    <span class="text-danger" id="error_name"></span>
                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_link_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_categories')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label"
                                                            for="">{{ __('common.category') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="category" id="category"
                                                            class="mb-15" multiple>
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>


                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_category_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingPages">
                                        <h5 class="mb-0 collapsed create-title"  data-toggle="collapse"
                                        data-target="#pages" aria-expanded="false"
                                        aria-controls="collapsePages">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_pages')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="pages" class="collapse" aria-labelledby="headingPages"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{ __('common.page') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="page" id="page" class="primary_select mb-15"
                                                            multiple>
                                                            @foreach ($pages as $key => $page)
                                                                <option value="{{ $page->id }}">
                                                                    {{ $page->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_page_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-10">
                                    <div class="card-header" id="headingProduct">
                                        <h5 class="mb-0 collapsed create-title"  data-toggle="collapse"
                                        data-target="#products" aria-expanded="false"
                                        aria-controls="collapseProduct">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_product')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="products" class="collapse" aria-labelledby="headingProduct"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{__('common.product')}} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="product" id="product" class="mb-15"
                                                            multiple>
                                                            
                                                        </select>
                                                        <span
                                                            class="text-danger">{{ $errors->first('barcode_type') }}</span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_product_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-10">
                                    <div class="card-header" id="headingBrand">
                                        <h5 class="mb-0 collapsed create-title"  data-toggle="collapse"
                                        data-target="#brands" aria-expanded="false"
                                        aria-controls="collapseBrand">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_brand')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="brands" class="collapse" aria-labelledby="headingBrand"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{ __('product.brand') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="brand" id="brand" class="mb-15"
                                                            multiple>
                                                        </select>
                                                        <span
                                                            class="text-danger">{{ $errors->first('barcode_type') }}</span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_brand_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-10">
                                    <div class="card-header" id="headingTag">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#tags" aria-expanded="false" aria-controls="collapseTag">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('menu.add_tag')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="tags" class="collapse" aria-labelledby="headingTag"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{ __('common.tag') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="tag" id="tag" class="mb-15"
                                                            multiple>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_tag_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFunc">
                                        <h5 class="mb-0 collapsed create-title" data-toggle="collapse"
                                        data-target="#funcs" aria-expanded="false" aria-controls="collapseFunc">
                                            <button class="btn btn-link add_btn_link">
                                                {{__('Add functions')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="funcs" class="collapse" aria-labelledby="headingFunc"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for="">{{ __('Functions') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="function" id="function" class="primary_select mb-15">
                                                            <option value="" disabled selected>Select one</option>
                                                            <option value="1">Lang & Currency</option>
                                                        </select>
                                                        <span class="text-danger"></span>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 text-center">
                                                    <button id="add_func_btn" type="submit"
                                                        class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                        title="" data-original-title="">
                                                        <span class="ti-check"></span>
                                                        {{__('menu.add_to_menu')}} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($menu->menu_type == 'multi_mega_menu')



                    <div class="row">
                        <div class="col-lg-12">
                            <div id="accordion">


                                <div class="card">
                                    <div class="card-header" id="headingMenu">
                                        <h5 class="mb-0 create-title collapsed" data-toggle="collapse"
                                        data-target="#menus" aria-expanded="false" aria-controls="collapseMenu">
                                            <button class="btn btn-link add_btn_link" >
                                                {{__('menu.add_menu')}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="menus" class="collapse" aria-labelledby="headingMenu"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                          <div class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-15">
                                                    <label class="primary_input_label" for="">{{__('menu.menu')}} <span class="text-danger">*</span></label>
                                                    <select name="menu" id="menu" class="primary_select mb-15" multiple>
                                                        @foreach($menus->where('menu_type', '!=', 'normal_menu') as $key => $menu)
                                                        <option value="{{$menu->id}}">{{$menu->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 text-center">
                                                <button id="add_menu_btn" type="submit" class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip" title=""
                                                    data-original-title="">
                                                    <span class="ti-check"></span>
                                                    {{__('menu.add_to_menu')}} </button>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

            </div>
        </div>

    </div>
</div>
