<div class="row">
    @php
    $headerSliderSection = $headers->where('type','slider')->first();
    $headerCategorySection = $headers->where('type','category')->first();
    $headerProductSection = $headers->where('type','product')->first();
    $headerNewUserZoneSection = $headers->where('type','new_user_zone')->first();
    @endphp
    <div id="slider" class="
        @if($headerSliderSection->column_size == '1 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '2 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '3 column')
        col-xl-3 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '4 column')
        col-xl-4 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '5 column')
        col-xl-5 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '6 column')
        col-xl-6 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '7 column')
        col-xl-7 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '8 column')
        col-xl-8 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '9 column')
        col-xl-9 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '10 column')
        col-xl-10 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '11 column')
        col-xl-11 col-lg-12 col-md-12
        @elseif($headerSliderSection->column_size == '12 column')
        col-xl-12 col-lg-12 col-md-12
        @endif
        {{$headerSliderSection->is_enable == 0?'d-none':''}}">
            <div class="banner_slider owl-carousel">
                @php
                    $sliders = $headerSliderSection->sliders();
                @endphp
                @if(count($sliders) > 0)
                    @foreach($sliders as $key => $slider)
                        <div class="single_banner_slider">
                            <div class="row align-items-center">

                                <a href="
                                    @if($slider->data_type == 'url')
                                        {{$slider->url}}
                                    @elseif($slider->data_type == 'product')
                                        {{singleProductURL(@$slider->product->seller->slug, @$slider->product->slug)}}
                                    @elseif($slider->data_type == 'category')
                                        {{route('frontend.category-product',['slug' => @$slider->category->slug, 'item' =>'category'])}}
                                    @elseif($slider->data_type == 'brand')
                                        {{route('frontend.category-product',['slug' => @$slider->brand->slug, 'item' =>'brand'])}}
                                    @elseif($slider->data_type == 'tag')
                                        {{route('frontend.category-product',['slug' => @$slider->tag->name, 'item' =>'tag'])}}
                                    @else
                                        {{url('/category')}}
                                    @endif

                                    " {{$slider->is_newtab == 1?'target="_blank"':''}} class="slider_img_div">
                                    <img src="{{showImage($slider->slider_image)}}" class="" alt="{{$slider->name}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
    </div>



    {{-- category section --}}

    <div id="category" class="
        @if($headerCategorySection->column_size == '1 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '2 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '3 column')
        col-xl-3 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '4 column')
        col-xl-4 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '5 column')
        col-xl-5 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '6 column')
        col-xl-6 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '7 column')
        col-xl-7 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '8 column')
        col-xl-8 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '9 column')
        col-xl-9 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '10 column')
        col-xl-10 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '11 column')
        col-xl-11 col-lg-12 col-md-12
        @elseif($headerCategorySection->column_size == '12 column')
        col-xl-12 col-lg-12 col-md-12
        @endif
        {{$headerCategorySection->is_enable == 0?'d-none':''}}
        ">
            <div class="banner_product_item justify-content-between">
                @foreach($headerCategorySection->categorySectionItems() as $key => $item)
                    <div class="single_product_item">
                        <a {{$item->is_newtab == 1?'target="_blank"':''}} href="{{route('frontend.category-product',['slug' => $item->category->slug, 'item' =>'category'])}}">
                            <div class="single_product_item_iner">
                                <div class="header_img_category_div">
                                    <img
                                    data-src="{{showImage(@$item->category->categoryImage->image?@$item->category->categoryImage->image:'frontend/default/img/default_category.png')}}"
                                    alt="{{$item->title}}"
                                    src="{{showImage(themeDefaultImg())}}"
                                    class="lazyload"
                                />
                                </div>
                                <p class="header_category_name">{{textLimit($item->title, 15)}}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
    </div>

    {{-- product sectiuon --}}
    <div id="product" class="
        @if($headerProductSection->column_size == '1 column')
        col-xl-1 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '2 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '3 column')
        col-xl-3 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '4 column')
        col-xl-4 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '5 column')
        col-xl-5 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '6 column')
        col-xl-6 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '7 column')
        col-xl-7 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '8 column')
        col-xl-8 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '9 column')
        col-xl-9 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '10 column')
        col-xl-10 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '11 column')
        col-xl-11 col-lg-12 col-md-12
        @elseif($headerProductSection->column_size == '12 column')
        col-xl-12 col-lg-12 col-md-12
        @endif
        {{$headerProductSection->is_enable == 0?'d-none':''}}
        ">
            @php
                $headerProducts = @$headerProductSection->productSectionItems();
            @endphp
            <div class="banner_product_list d-flex justify-content-between mt-1">
                @foreach($headerProducts as $key => $item)
                    <div class="single_banner_product product_price">
                        <a {{$item->is_newtab == 1?'target="_blank"':''}} href="{{singleProductURL(@$item->product->seller->slug, $item->product->slug)}}" class="product_img">
                            <img
                            data-src="{{showImage(@$item->product->product->thumbnail_image_source)}}"
                            alt="#"
                            class="img-fluid lazyload"
                            src="{{showImage(themeDefaultImg())}}"
                            />
                        </a>
                        <div class="product_text">
                            <a {{$item->is_newtab == 1?'target="_blank"':''}} href="{{singleProductURL(@$item->product->seller->slug, $item->product->slug)}}" class="product_btn">
                                @if(@$item->product->hasDeal)
                                    {{single_price(selling_price(@$item->product->skus->first()->selling_price,@$item->product->hasDeal->discount_type,@$item->product->hasDeal->discount))}}
                                @else
                                    @if(@$item->product->hasDiscount == 'yes')
                                    {{single_price(selling_price(@$item->product->skus->first()->selling_price,@$item->product->discount_type,@$item->product->discount))}}
                                    @else
                                    {{single_price(@$item->product->skus->first()->selling_price)}}
                                    @endif

                                @endif
                            </a>
                            <a {{$item->is_newtab == 1?'target="_blank"':''}} href="{{singleProductURL(@$item->product->seller->slug, $item->product->slug)}}"><p>{{textLimit($item->title, 12)}}</p></a>
                        </div>
                    </div>
                @endforeach

            </div>
    </div>
    {{-- new user zone section --}}
    <div id="new_user_zone" class="
        @if($headerNewUserZoneSection->column_size == '1 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '2 column')
        col-xl-2 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '3 column')
        col-xl-3 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '4 column')
        col-xl-4 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '5 column')
        col-xl-5 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '6 column')
        col-xl-6 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '7 column')
        col-xl-7 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '8 column')
        col-xl-8 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '9 column')
        col-xl-9 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '10 column')
        col-xl-10 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '11 column')
        col-xl-11 col-lg-12 col-md-12
        @elseif($headerNewUserZoneSection->column_size == '12 column')
        col-xl-12 col-lg-12 col-md-12
        @endif
        {{$headerNewUserZoneSection->is_enable == 0?'d-none':''}}
        ">
        @php
        $new_user_zone = $headerNewUserZoneSection->newUserZonePanel();
        @endphp
        @isset($new_user_zone->newUserZone->slug)
            <a href="{{route('frontend.new-user-zone',@$new_user_zone->newUserZone->slug)}}" class="user_cupon d-sm-none d-xl-block">
                <h4>{{@$new_user_zone->navigation_label}}</h4>
                <div class="user_cupon_iner">
                    <div class="user_cupon_tittle"><span>{{@$new_user_zone->pricing}}</span></div>
                    <div class="user_cupon_details">
                        <p>{{textLimit($new_user_zone->title, 16)}}</p>

                    </div>
                </div>
            </a>
        @endisset
    </div>
</div>