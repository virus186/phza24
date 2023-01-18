@extends('frontend.amazy.layouts.app')

@section('title')
    @if(@$product->product->meta_title != null)
        {{ @substr(@$product->product->meta_title,0, 60)}}
    @else
        {{ @substr(@$product->product_name,0, 60)}}
    @endif
@endsection

@section('share_meta')

    @if(@$product->product->meta_description != null)
        <meta property="og:description" content="{{@$product->product->meta_description}}" />
        <meta name="description" content="{{@$product->product->meta_description}}">
    @else
        <meta property="og:description" content="{{@$product->product->description}}" />
        <meta name="description" content="{{@$product->product->description}}">
    @endif

    @if(@$product->product->meta_title != null)
        <meta name="title" content="{{ @substr(@$product->product->meta_title,0,60) }}"/>
        <meta property="og:title" content="{{substr(@$product->product->meta_title,0,60)}}" />
    @else
        <meta property="og:title" content="{{@substr(@$product->product_name,0,60)}}" />
        <meta name="title" content="{{ @substr(@$product->product_name,0,60) }}"/>
    @endif

    @if(@$product->product->meta_image != null && @getimagesize(showImage(@$product->product->meta_image))[0] > 200)

        <meta property="og:image" content="{{showImage($product->product->meta_image)}}" />
    @elseif(@$product->product->thumbnail_image_source != null && @getimagesize(showImage(@$product->product->thumbnail_image_source))[0] > 200)

        <meta property="og:image" content="{{showImage(@$product->product->thumbnail_image_source)}}" />
    @elseif(count(@$product->product->gallary_images) > 0 && @getimagesize(showImage(@$product->product->gallary_images[0]->images_source))[0] > 200)
        <meta property="og:image" content="{{showImage(@$product->product->gallary_images[0]->images_source)}}" />

    @endif
    <meta property="og:url" content="{{singleProductURL(@$product->seller->slug, $product->slug)}}" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:type" content="{{@$product->product->meta_description}}" />


    @php
        $total_tag = count($product->product->tags);
        $meta_tags = '';
        foreach($product->product->tags as $key => $tag){
            if($key + 1 < $total_tag){
                $meta_tags .= $tag->name.', ';
            }else{
                $meta_tags .= $tag->name;
            }
        }
    @endphp

    <meta name ="keywords", content="{{$meta_tags}}">
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset(asset_path('frontend/amazy/css/page_css/product_details.css'))}}" />
    @if(isRtl())
        <style>
            .zoomWindowContainer div {
                left: 0!important;
                right: 510px;
            }
            .product_details_part .cs_color_btn .radio input[type="radio"] + .radio-label:before {
                left: -1px !important;
            }
            @media (max-width: 970px) {
                .zoomWindowContainer div {
                    right: inherit!important;
                }
            }
        </style>
    @endif
@endpush

@section('content')
    <!-- product_details_wrapper::start  -->
    <div class="product_details_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="row">
                        <div class="col-lg-6 col-xl-6">
                            <div class="slider-container slick_custom_container mb_30" id="myTabContent">
                                <div class="slider-for gallery_large">
                                    @if(count($product->product->gallary_images) > 0)
                                        @foreach($product->product->gallary_images as $image)
                                            <div class="item-slick {{$product->product->gallary_images->first()->id == $image->id?'slick-current slick-active':''}}" id="thumb_{{$image->id}}">
                                                <img class="varintImg zoom_01" src="{{showImage($image->images_source)}}" data-zoom-image="{{showImage($image->images_source)}}" alt="{{$product->product_name}}" title="{{$product->product_name}}">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="item-slick slick-current slick-active" id="thumb_{{$product->id}}">
                                            <img class="varintImg zoom_01" @if ($product->thum_img != null) data-zoom-image="{{showImage($product->thum_img)}}" @else data-zoom-image="{{showImage($product->product->thumbnail_image_source)}}" @endif @if ($product->thum_img != null) src="{{showImage($product->thum_img)}}" @else src="{{showImage($product->product->thumbnail_image_source)}}" @endif alt="{{$product->product_name}}" title="{{$product->product_name}}">
                                        </div>
                                    @endif
                                </div>
                                <div class="slider-nav">
                                    @if(count($product->product->gallary_images) > 0)
                                        @foreach($product->product->gallary_images as $i => $image)
                                            <div class="item-slick {{$i == 0?'slick-active slick-current':''}}">
                                                <img src="{{showImage($image->images_source)}}" alt="{{$product->product_name}}" title="{{$product->product_name}}">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="item-slick slick-active slick-current">
                                            <img @if ($product->thum_img != null) src="{{showImage($product->thum_img)}}" @else src="{{showImage($product->product->thumbnail_image_source)}}" @endif alt="{{$product->product_name}}" title="{{$product->product_name}}">
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" id="maximum_order_qty" value="{{@$product->product->max_order_qty}}">
                                <input type="hidden" id="minimum_order_qty" value="{{@$product->product->minimum_order_qty}}">
                                <input type="hidden" name="thumb_image" id="thumb_image" value="@if ($product->thum_img != null) {{showImage($product->thum_img)}} @else {{showImage($product->product->thumbnail_image_source)}} @endif">
                            </div>

                        </div>
                        <div class="col-lg-6 col-xl-6">
                            <div class="product_content_details mb_20">
                                <div id="stock_div">
                                    @if ($product->stock_manage == 1 && @$product->skus->first()->product_stock >= @$product->product->minimum_order_qty)
                                        <span class="stoke_badge">{{__('common.in_stock')}}</span>
                                    @elseif($product->stock_manage == 0)
                                        <span class="stoke_badge">{{__('common.in_stock')}}</span>
                                    @else
                                        <span class="stokeout_badge">{{__('amazy.Out of stock')}}</span>
                                    @endif
                                </div>
                                <h3>{{$product->product_name}}</h3>
                                @if(app('general_setting')->product_subtitle_show)
                                    @if($product->subtitle_1)
                                        <h5>{{$product->subtitle_1}}</h5>
                                    @endif
                                    @if($product->subtitle_2)
                                        <h5>{{$product->subtitle_2}}</h5>
                                    @endif
                                @endif
                                <div class="viendor_text d-flex align-items-center">
                                    <p class="stock_text"> <span class="text-uppercase">{{__('defaultTheme.sku')}}:</span> <span class="stock_value" id="sku_id_li"> {{@$product->skus->first()->sku->sku??'-'}}</span></p>
                                    <p class="stock_text"> <span class="text-uppercase">{{__('common.category')}}:</span>
                                        @php
                                            $cates = count($product->product->categories);
                                        @endphp
                                        @foreach($product->product->categories as $key => $category)
                                            <span>{{$category->name}}</span>
                                            @if($key + 1 < $cates), @endif
                                        @endforeach
                                    </p>
                                </div>
                                <div class="viendor_text d-flex align-items-center">
                                    <p class="stock_text"> <span class="text-uppercase">{{__('defaultTheme.availability')}}:</span> <span class="stock_value" id="availability">
                                        @if ($product->stock_manage == 0)
                                        {{__('defaultTheme.unlimited')}}
                                        @else
                                            {{ $product->skus->first()->product_stock }}
                                        @endif
                                    </span></p>
                                </div>
                                <div class="product_ratings">
                                    <div class="stars">
                                        <x-rating :rating="$rating"/>
                                    </div>
                                    <span>{{getNumberTranslate(sprintf("%.2f",$rating))}}/{{getNumberTranslate(5)}} ({{($total_review<10 && $total_review>0)?'0':''}}{{getNumberTranslate($total_review)}} {{__('defaultTheme.review')}})</span>
                                </div>

                                <div class="destils_prise_information_box mb_20">
                                    <h2 class="pro_details_prise d-flex align-items-center  m-0">
                                        <span>
                                            {{getProductDiscountedPrice($product)}}
                                        </span>
                                    </h2>
                                    <div class="pro_details_disPrise d-flex align-items-center gap_15">
                                        <h4 class="discount_prise  m-0  ">
                                            <span class="text-decoration-line-through">
                                                @if($product->hasDeal || $product->hasDiscount == 'yes')
                                                    <span>{{single_price($product->skus->max('selling_price'))}}</span>
                                                @endif
                                            </span>
                                        </h4>

                                        @if(@$product->hasDeal)
                                            @if(@$product->hasDeal->discount > 0)
                                                @if(@$product->hasDeal->discount_type == 0)
                                                    <span class="diccount_percents">
                                                        -{{getNumberTranslate(@$product->hasDeal->discount)}}%
                                                    </span>
                                                @else
                                                    <span class="diccount_percents">
                                                        -{{single_price(@$product->hasDeal->discount)}}
                                                    </span>
                                                @endif
                                            @endif
                                        @else
                                            @if(@$product->hasDiscount == 'yes')
                                                @if($product->discount > 0)
                                                    @if($product->discount_type == 0)
                                                    <span class="diccount_percents">
                                                        -{{getNumberTranslate($product->discount)}}%
                                                    </span>
                                                    @else
                                                    <span class="diccount_percents">
                                                        -{{single_price($product->discount)}}
                                                    </span>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <input type="hidden" name="product_sku_id" id="product_sku_id" value="{{$product->product->product_type == 1?$product->skus->first()->id : $product->skus->first()->id}}">
                                    <input type="hidden" name="seller_id" id="seller_id" value="{{$product->user_id}}">
                                    <input type="hidden" id="owner" value="{{encrypt($product->user_id)}}">
                                    <input type="hidden" name="stock_manage_status" id="stock_manage_status" value="{{$product->stock_manage}}">

                                    <p class="pro_details_text">
                                        <span class="text-uppercase">{{__('common.tag')}}:</span>
                                        @php
                                            $total_tag = count($product->product->tags);
                                        @endphp
                                        @foreach($product->product->tags as $key => $tag)
                                            <a class="tag_link" href="{{route('frontend.category-product',['slug' => $tag->name, 'item' =>'tag'])}}">{{$tag->name}}</a>
                                            @if($key + 1 < $total_tag), @endif
                                        @endforeach
                                    </p>
                                </div>

                                <input type="hidden" name="product_type" class="product_type" value="{{ $product->product->product_type }}">
                                @if($product->product->product_type == 2)
                                    @foreach (session()->get('item_details') as $key => $item)
                                        @if ($item['name'] === "Color")
                                            <div class="product_color_varient mb_20">
                                                <h5 class="font_14 f_w_500 theme_text3  text-capitalize d-block mb_10" >{{ $item['name'] }}:</h5>
                                                <div class="color_List d-flex gap_5 flex-wrap">
                                                    <input type="hidden" class="attr_value_name" name="attr_val_name[]" value="{{$item['value'][0]}}">
                                                    <input type="hidden" class="attr_value_id" name="attr_val_id[]" value="{{$item['id'][0]}}-{{$item['attr_id']}}">
                                                    @foreach ($item['value'] as $k => $value_name)
                                                        <label class="round_checkbox d-flex">
                                                            <input id="radio-{{$k}}" name="color_filt" class="attr_val_name" type="radio" color="color" @if ($k === 0) checked @endif data-value="{{ $item['id'][$k] }}" data-value-key="{{$item['attr_id']}}" value="{{ $value_name }}"/>
                                                            <span class="checkmark colors_{{$k}} class_color_{{ $item['code'][$k] }}">
                                                                <div class="check_bg_color"></div>
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if ($item['name'] != "Color")
                                            <div class="product_color_varient mb_20">
                                                <h5 class="font_14 f_w_500 theme_text3  text-capitalize d-block mb_10" >{{$item['name']}}:</h5>
                                                <div class="color_List d-flex gap_5 flex-wrap">
                                                    <input type="hidden" class="attr_value_name" name="attr_val_name[]" value="{{$item['value'][0]}}">
                                                    <input type="hidden" class="attr_value_id" name="attr_val_id[]" value="{{$item['id'][0]}}-{{$item['attr_id']}}">
                                                    @foreach ($item['value'] as $m => $value_name)
                                                        <a class="attr_val_name size_btn not_111 @if ($m === 0) selected_btn @endif" color="not" data-value-key="{{$item['attr_id']}}" data-value="{{ $item['id'][$m] }}">{{ $value_name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @php
                                        $variant_images = [];
                                        $variant_skus = [];
                                        foreach($product->skus as $sku){
                                            if(@$sku->sku->variant_image){
                                                $variant_images[] = $sku->sku->variant_image;
                                                $variant_skus[] = $sku->sku->sku;
                                            }
                                        }
                                    @endphp
                                    @if(count($variant_images) > 0)
                                        <div class="single_details_content variant_image d-md-flex">
                                            <h5>{{__('amazy.Variant images')}}:</h5>
                                            <div class="img_div_width d-flex">
                                                @foreach($variant_images as $variant_key => $variant_image)
                                                    <div class="sku_img_div">
                                                        <img src="{{showImage($variant_image)}}" title="{{ $variant_skus[$variant_key] }}" class="img-fluid p-1" title="{{ $variant_skus[$variant_key] }}"/>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <!--show wholesale price -->
                                @if(isModuleActive('WholeSale'))
                                    <div class="{{ @$product->skus->first()->wholeSalePrices->count() ? 'd-flex':'d-none'}}">
                                        <table class="table-sm append_w_s_p_tbl mb-3" width="100%">
                                            <thead>
                                            <tr class="border-bottom ">
                                                <td  class="text-left">
                                                    <label for="" class="control-label">{{__('common.Min QTY')}}</label>
                                                </td>
                                                <td class="text-left">
                                                    <label for="" class="control-label">{{__('common.Max QTY')}}</label>
                                                </td>

                                                <td class="text-left">
                                                    <label for="" class="control-label">{{__('common.unit_price')}}</label>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody id="append_w_s_p_all">

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="product_info">
                                    {{-- <div class="size_quide_info d-flex alingn-items-center mb_20 flex-wrap">
                                        <a href="#"  data-bs-toggle="modal" data-bs-target="#asq_about_form" class="single_side_guide m-0">
                                            <img src="{{url('/')}}/public/frontend/amazy/img/svg/chatting.svg" alt="#"> Ask about this product
                                        </a>
                                    </div> --}}
                                    <div class="single_pro_varient">
                                        <h5 class="font_14 f_w_500 theme_text3 " >{{__('common.quantity')}}:</h5>
                                        <div class="product_number_count mr_5" data-target="amount-1">
                                            <span class="count_single_item inumber_decrement qtyChange" data-value="-"> <i class="ti-minus"></i></span>
                                            <input name="qty" id="qty" class="count_single_item input-number qty" type="text" readonly data-value="{{@$product->product->minimum_order_qty}}" value="{{getNumberTranslate(@$product->product->minimum_order_qty)}}">
                                            <span class="count_single_item number_increment qtyChange" data-value="+"> <i class="ti-plus"></i></span>
                                        </div>

                                        {{-- <div class="size_quide_info d-flex alingn-items-center mb-0 flex-wrap flex-fill">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#size_modal"  class="single_side_guide  text-nowrap">
                                                <img src="{{url('/')}}/public/frontend/amazy/img/svg/size.svg" alt="#"> Size Guide
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#shiping_modal" class="single_side_guide  m-0 text-nowrap">
                                                <img src="{{url('/')}}/public/frontend/amazy/img/svg/ship.svg" alt="#"> Shipping
                                            </a>
                                        </div> --}}
                                    </div>
                                    <input type="hidden" name="base_sku_price" id="base_sku_price" value="
                                        @if(@$product->hasDeal)
                                            {{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                                        @else
                                            @if($product->hasDiscount == 'yes')
                                            {{selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount)}}

                                            @else
                                                {{@$product->skus->first()->selling_price}}
                                            @endif
                                        @endif
                                    ">
                                    <input type="hidden" name="final_price" id="final_price" value="
                                        @if(@$product->hasDeal)
                                            {{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                                        @else
                                            @if($product->hasDiscount == 'yes')
                                            {{selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount)}}

                                            @else
                                                {{@$product->skus->first()->selling_price}}
                                            @endif
                                        @endif
                                    ">
                                    <h5 class="mb-0">{{__('common.total')}}:
                                        <span id="total_price">
                                            @if(@$product->hasDeal)
                                                {{single_price(selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) * $product->product->minimum_order_qty)}}
                                            @else
                                                @if($product->hasDiscount == 'yes')
                                                    {{single_price(selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) * $product->product->minimum_order_qty)}}

                                                @else
                                                    {{single_price(@$product->skus->first()->selling_price * $product->product->minimum_order_qty)}}
                                                @endif

                                            @endif
                                        </span>
                                    </h5>

                                    <div class="row mt_30 " id="add_to_cart_div">
                                        
                                            @if ($product->stock_manage == 1 && $product->skus->first()->product_stock >= $product->product->minimum_order_qty)
                                                <div class="col-md-6">
                                                    <button type="button" id="add_to_cart_btn" class="amaz_primary_btn style2 mb_20  add_to_cart add_to_cart_btn text-uppercase flex-fill text-center w-100">{{__('common.add_to_cart')}}</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="butItNow" class="amaz_primary_btn3 mb_20  w-100 text-center justify-content-center text-uppercase buy_now_btn" data-id="{{$product->id}}" data-type="product">{{__('common.buy_now')}}</button>
                                                </div>
                                            @elseif($product->stock_manage == 0)
                                                <div class="col-md-6">
                                                    <button type="button" id="add_to_cart_btn" class="amaz_primary_btn style2 mb_20  add_to_cart text-uppercase add_to_cart_btn flex-fill text-center w-100">{{__('common.add_to_cart')}}</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="butItNow" class="amaz_primary_btn3 mb_20  w-100 text-center justify-content-center text-uppercase buy_now_btn" data-id="{{$product->id}}" data-type="product">{{__('common.buy_now')}}</button>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <button type="button" disabled class="amaz_primary_btn style2 mb_20  add_to_cart text-uppercase flex-fill text-center w-100">{{__('defaultTheme.out_of_stock')}}</button>
                                                </div>
                                            @endif
                                        
                                    </div>

                                    <div class="add_wish_compare d-flex alingn-items-center mb_20">
                                        <a id="wishlist_btn" data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}" class="single_wish_compare text-uppercase text-nowrap cursor_pointer">
                                            <i class="ti-heart"></i> {{__('defaultTheme.add_to_wishlist')}}
                                        </a>
                                        <a id="add_to_compare_btn" data-product_sku_id="#product_sku_id" data-product_type="{{$product->product->product_type}}" class="single_wish_compare text-uppercase text-nowrap cursor_pointer">
                                            <i class="ti-control-shuffle"></i> {{__('defaultTheme.add_to_compare')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @php
                                $both_buy_product = null;
                                if(@$product->product->display_in_details == 1){
                                    if($product->up_sales->count()){
                                        $both_buy_product = @$product->up_sales[0]->up_seller_products[0];
                                    }
                                }else{
                                    if($product->cross_sales->count()){
                                        $both_buy_product = @$product->cross_sales[0]->cross_seller_products[0];
                                    }
                                }
                            @endphp
                            @if($product->stock_manage == 1 && $product->skus->first()->product_stock >= $product->product->minimum_order_qty || $product->stock_manage == 0)
                                @if($both_buy_product && $both_buy_product->stock_manage == 1 && $both_buy_product->skus->first()->product_stock >= $both_buy_product->product->minimum_order_qty || $both_buy_product && $both_buy_product->stock_manage == 0)
                                <div class="product_details_sujjetion">
                                    <h4 class="font_14 f_w_700 text-uppercase mb_12 lh-1">{{__('amazy.YOU CAN ALSO BUY')}}:</h4>
                                    <div class="product_details_sujjetion_box">
                                        <a href="{{singleProductURL(@$both_buy_product->seller->slug, $both_buy_product->slug)}}" class="product_details_sujjetion_single d-flex align-items-center gap_15">
                                            @php
                                                if(@$product->hasDeal){
                                                    $both_buy_price = selling_price(@$both_buy_product->skus->first()->selling_price,@$both_buy_product->hasDeal->discount_type,@$both_buy_product->hasDeal->discount);
                                                }else{
                                                    if(@$product->hasDiscount == 'yes'){
                                                        $both_buy_price = selling_price(@$both_buy_product->skus->first()->selling_price,@$both_buy_product->discount_type,@$both_buy_product->discount);
                                                    }else{
                                                        $both_buy_price = @$both_buy_product->skus->first()->selling_price;
                                                    }
                                                }
                                            @endphp
                                            <input type="hidden" id="both_buy_price" value="{{$both_buy_price}}">
                                            <div class="thumb both_buy">
                                                <img src="
                                                @if(@$both_buy_product->thum_img != null)
                                                    {{showImage(@$both_buy_product->thum_img)}}
                                                @else
                                                    {{showImage(@$both_buy_product->product->thumbnail_image_source)}}
                                                @endif
                                                " alt="{{@$both_buy_product->product->product_name}}" title="{{@$both_buy_product->product->product_name}}">
                                            </div>
                                            <div class="product_details_sujjetion_content">
                                                <h4 class="fs-6 f_w_700">@if ($both_buy_product->product_name) {{ textLimit($both_buy_product->product_name,28) }} @else {{textLimit(@$both_buy_product->product->product_name,28)}} @endif</h4>
                                                <p class="font_14 f_w_500 mb-0 lh-1">
                                                    {{single_price($both_buy_price)}}
                                                </p>
                                            </div>
                                        </a>
                                        <div class="product_details_sujjetion_total d-flex align-items-center gap_15">
                                            <div class="product_details_sujjetion_left flex-fill">
                                                <span class="font_12 f_w_500 d-block">{{__('common.total_price')}}:</span>
                                                <h4 id="both_buy_price_show" class="font_16 f_w_700 m-0 lh-1"></h4>
                                            </div>
                                            <a href="#" class="amaz_primary_btn style3 text-uppercase" id="both_buy_btn" data-sku_id="{{ @$both_buy_product->skus->first()->id }}"
                                                data-seller_id="{{ $both_buy_product->user_id }}" data-product_id="{{$both_buy_product->id}}" data-qty="{{@$both_buy_product->product->minimum_order_qty}}" >{{__('amazy.Buy Both')}}</a>
                                        </div>
                                        
                                    </div>
                                </div>
                                @endif
                            @endif
                            <div class="contact_wiz_box mt_20">
                                <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('defaultTheme.share_on')}}:</span>
                                <div class="contact_link">
                                    <a target="_blank" href="{{ Share::currentPage()->facebook()->getRawLinks() }}">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->twitter()->getRawLinks() }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->linkedin()->getRawLinks() }}">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->whatsapp()->getRawLinks() }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->telegram()->getRawLinks() }}">
                                        <i class="fab fa-telegram-plane"></i>
                                    </a>
                                    <a target="_blank" href="{{ Share::currentPage()->reddit()->getRawLinks() }}">
                                        <i class="ti-reddit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="product_details_dec mb_76">
                                <div class="product_details_dec_header">
                                    <h4 class="font_20 f_w_400 m-0 ">{{__('common.description')}}</h4>
                                </div>
                                <div class="product_details_dec_body">
                                    @php
                                        echo $product->product->description;
                                    @endphp
                                </div>
                            </div>
                            <div class="product_details_dec mb_76">
                                <div class="product_details_dec_header">
                                    <h4 class="font_20 f_w_400 m-0 ">{{__('defaultTheme.product_specifications')}}</h4>
                                </div>
                                <div class="product_details_dec_body">
                                    <div class="single_desc style2 mb_20">
                                        <p class="f_w_500 m-0">{{ __('common.brand') }}: {{@$product->product->brand->name}}</p>
                                        <p class="f_w_500 m-0">{{ __('common.model_number') }}: {{@$product->product->model_number}}</p>
                                        <p class="f_w_500 m-0">{{ __('common.availability') }}:
                                            @if ($product->stock_manage == 1 && $product->skus->first()->product_stock >= $product->product->minimum_order_qty)
                                                {{ __('common.in_stock') }}
                                            @elseif($product->stock_manage == 0)
                                                {{ __('common.in_stock') }}
                                            @else
                                                {{__('defaultTheme.out_of_stock')}}
                                            @endif
                                        </p>
                                        <p class="f_w_500 m-0">{{ __('common.product') }} {{ __('product.sku') }}:
                                            @foreach ($product->product->skus as $sku)
                                                {{$sku->sku}} &nbsp; &nbsp;
                                            @endforeach
                                        </p>
                                        <p class="f_w_500 m-0">{{ __('common.minimum_order_quantity') }}: {{getNumberTranslate(@$product->product->minimum_order_qty)}}</p>
                                        <p class="f_w_500 m-0">{{ __('common.maximum_order_quantity') }}: {{getNumberTranslate(@$product->product->max_order_qty)}}</p>
                                        <p class="f_w_500 m-0">{{ __('common.listed_date') }}: {{date(app('general_setting')->dateFormat->format, strtotime(@$product->product->created_at))}}</p>
                                    </div>

                                    @php
                                        echo $product->product->specification;
                                    @endphp
                                </div>
                            </div>
                            @if (!empty($product->product->pdf))
                            <div class="product_details_dec mb_76">
                                <div class="product_details_dec_header">
                                    <h4 class="font_20 f_w_400 m-0 ">{{ __('product.pdf_specifications') }}</h4>
                                </div>
                                <div class="product_details_dec_body">
                                    <a class="anchore_color" href="{{ asset(asset_path($product->product->pdf)) }}" download>{{ __('product.download_file') }}</a>
                                </div>
                            </div>
                            @endif
                            @if ($product->product->video_link)
                                <div class="product_details_dec mb_76">
                                    <div class="product_details_dec_header">
                                        <h4 class="font_20 f_w_400 m-0 ">{{__('defaultTheme.video')}}</h4>
                                    </div>
                                    <div class="product_details_dec_body">
                                        <div class="product_details">
                                            @if ($product->product->video_provider == 'youtube')
                                                @php
                                                    $link = str_replace('watch?v=','embed/',$product->product->video_link);
                                                @endphp
                                                <iframe src="{{ $link }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @endif
                                            @if ($product->product->video_provider == 'daily_motion')
                                                @php
                                                    if(strpos($product->product->video_link, 'dai.ly') != false){
                                                        $link = str_replace('https://dai.ly/','https://www.dailymotion.com/embed/video/',$product->product->video_link);
                                                    }else{
                                                        $link = str_replace('https://www.dailymotion.com/video/','https://www.dailymotion.com/embed/video/',$product->product->video_link);
                                                    }
                                                @endphp
                                                <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">
                                                    <iframe style="width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden" frameborder="0" type="text/html"
                                                    src="{{$link}}" width="100%" height="100%" allowfullscreen allow="autoplay"> </iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($recent_viewed_products->count())
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section__title d-flex align-items-center gap-3 mb_30">
                                            <h3 class="m-0 flex-fill">{{__('amazy.Customers who viewed this also viewed')}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($recent_viewed_products as $recent_viewed_product)
                                        <div class="col-xl-4 col-lg-4 col-md-6">
                                            <div class="product_widget5 mb_30">
                                                <div class="product_thumb_upper">
                                                    @php
                                                        if(@$recent_viewed_product->thum_img != null){
                                                            $thumbnail = showImage(@$recent_viewed_product->thum_img);
                                                        }else {
                                                            $thumbnail = showImage(@$recent_viewed_product->product->thumbnail_image_source);
                                                        }
                
                                                        $price_qty = getProductDiscountedPrice(@$recent_viewed_product);
                                                        $showData = [
                                                            'name' => @$recent_viewed_product->product_name,
                                                            'url' => singleProductURL(@$recent_viewed_product->seller->slug, @$recent_viewed_product->slug),
                                                            'price' => $price_qty,
                                                            'thumbnail' => $thumbnail
                                                        ];
                                                    @endphp
                                                    <a href="{{singleProductURL($recent_viewed_product->seller->slug, $recent_viewed_product->slug)}}" class="thumb">
                                                        <img data-src="{{$thumbnail}}" src="{{showImage(themeDefaultImg())}}" alt="{{@$recent_viewed_product->product_name}}" title="{{@$recent_viewed_product->product_name}}" class="lazyload">
                                                    </a>
                                                    <div class="product_action">
                                                        <a href="" class="addToCompareFromThumnail" data-producttype="{{ @$recent_viewed_product->product->product_type }}" data-seller={{ $recent_viewed_product->user_id }} data-product-sku={{ @$recent_viewed_product->skus->first()->id }} data-product-id={{ $recent_viewed_product->id }}>
                                                            <i class="ti-control-shuffle"></i>
                                                        </a>
                                                        <a href="" class="add_to_wishlist {{$recent_viewed_product->is_wishlist() == 1?'is_wishlist':''}}" id="wishlistbtn_{{$recent_viewed_product->id}}" data-product_id="{{$recent_viewed_product->id}}" data-seller_id="{{$recent_viewed_product->user_id}}">
                                                            <i class="ti-heart"></i>
                                                        </a>
                                                        <a class="quickView" data-product_id="{{$recent_viewed_product->id}}" data-type="product">
                                                            <i class="ti-eye"></i>
                                                        </a>
                                                    </div>
                                                    <div class="recomanded_discount">
                                                        @if($recent_viewed_product->hasDeal)
                                                            @if($recent_viewed_product->hasDeal->discount >0)
                                                                <span class="badge_1">
                                                                    @if($recent_viewed_product->hasDeal->discount >0)
                                                                        @if($recent_viewed_product->hasDeal->discount_type ==0)
                                                                            {{getNumberTranslate($recent_viewed_product->hasDeal->discount)}} % {{__('common.off')}}
                                                                        @else
                                                                            {{single_price($recent_viewed_product->hasDeal->discount)}} {{__('common.off')}}
                                                                        @endif
                
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        @else
                                                            @if($recent_viewed_product->hasDiscount == 'yes')
                                                            @if($recent_viewed_product->discount > 0)
                                                                <span class="badge_1">
                                                                    @if($recent_viewed_product->discount >0)
                                                                        @if($recent_viewed_product->discount_type ==0)
                                                                            {{getNumberTranslate($recent_viewed_product->discount)}} % {{__('common.off')}}
                                                                        @else
                                                                            {{single_price($recent_viewed_product->discount)}} {{__('common.off')}}
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product__meta text-center">
                                                    <span class="product_banding ">{{ @$recent_viewed_product->brand->name ?? __('amazy.no_brand') }}</span>
                                                    <a href="{{singleProductURL(@$recent_viewed_product->seller->slug, $recent_viewed_product->slug)}}">
                                                        <h4>@if ($recent_viewed_product->product_name) {{ textLimit(@$recent_viewed_product->product_name, 50) }} @else {{ textLimit(@$recent_viewed_product->product->product_name, 50) }} @endif</h4>
                                                    </a>
                                                    <div class="stars justify-content-center">
                                                        @php
                                                            $reviews = $recent_viewed_product->reviews->where('status',1)->pluck('rating');
                                                            if(count($reviews)>0){
                                                                $value = 0;
                                                                $rating = 0;
                                                                foreach($reviews as $review){
                                                                    $value += $review;
                                                                }
                                                                $rating = $value/count($reviews);
                                                                $total_review = count($reviews);
                                                            }else{
                                                                $rating = 0;
                                                                $total_review = 0;
                                                            }
                                                        @endphp
                                                        <x-rating :rating="$rating"/>
                                                    </div>
                                                    <div class="product_prise">
                                                        <p>
                                                            @if($recent_viewed_product->hasDeal)
                                                                {{single_price(selling_price($recent_viewed_product->skus->first()->selling_price,$recent_viewed_product->hasDeal->discount_type,$recent_viewed_product->hasDeal->discount))}}
                                                            @else
                                                                @if($recent_viewed_product->hasDiscount == 'yes')
                                                                    {{single_price(selling_price(@$recent_viewed_product->skus->first()->selling_price,@$recent_viewed_product->discount_type,@$recent_viewed_product->discount))}}
                                
                                                                @else
                                                                    {{single_price(@$recent_viewed_product->skus->first()->selling_price)}}
                                                                @endif
                                                            @endif
                                                        </p>
                                                        <a class="add_cart add_to_cart addToCartFromThumnail" data-producttype="{{ @$recent_viewed_product->product->product_type }}" data-seller={{ $recent_viewed_product->user_id }} data-product-sku={{ @$recent_viewed_product->skus->first()->id }} 
                                                            @if(@$recent_viewed_product->hasDeal)
                                                                data-base-price={{ selling_price(@$recent_viewed_product->skus->first()->selling_price,@$recent_viewed_product->hasDeal->discount_type,@$recent_viewed_product->hasDeal->discount) }}
                                                            @else
                                                                @if(@$recent_viewed_product->hasDiscount == 'yes')
                                                                    data-base-price={{ selling_price(@$recent_viewed_product->skus->first()->selling_price,@$recent_viewed_product->discount_type,@$recent_viewed_product->discount) }}
                                                                @else
                                                                    data-base-price={{ @$recent_viewed_product->skus->first()->selling_price }}
                                                                @endif
                                                            @endif
                                                            data-shipping-method=0
                                                            data-product-id={{ $recent_viewed_product->id }}
                                                            data-stock_manage="{{$recent_viewed_product->stock_manage}}"
                                                            data-stock="{{@$recent_viewed_product->skus->first()->product_stock}}"
                                                            data-min_qty="{{@$recent_viewed_product->product->minimum_order_qty}}"
                                                            data-prod_info="{{ json_encode($showData) }}"
                                                            >{{__('defaultTheme.add_to_cart')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="col-12" id="Reviews">
                            @include(theme('partials._product_review_with_paginate'),['reviews' => @$product->ActiveReviewsWithPaginate, 'all_reviews' => $product->reviews])
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    @php
                        $free_shipping = \Modules\Shipping\Entities\ShippingMethod::where('request_by_user', $product->user_id)->where('is_active', 1)->where('id','>', 1)->where('cost', 0)->first();
                    @endphp
                    @if($free_shipping)
                        <div class="amazcart_delivery_wiz mb_20">
                            <div class="amazcart_delivery_wiz_head">
                                <h4 class="font_18 f_w_700 m-0">{{__('amazy.Delivery Info')}}</h4>
                            </div>
                            <div class="amazcart_delivery_wiz_body">
                                <h4 class="font_16 f_w_700 mb_6">{{$free_shipping->method_name}}</h4>
                                <p class="delivery_text font_14 f_w_400">{{__('common.free_shipping_on')}} {{$free_shipping->method_name}} {{__('common.starts from order amount')}} {{single_price($free_shipping->minimum_shopping)}}</p>
                            </div>
                        </div>
                    @endif
                    <div class="amazcart_delivery_wiz mb_20">
                        <div class="amazcart_delivery_wiz_head">
                            <h4 class="font_18 f_w_700 m-0">{{__('common.choose_your_location')}}</h4>
                        </div>
                        <div class="amazcart_delivery_wiz_body">
                            <div class="loc_city_selectBox d-flex flex-column">
                                <div class="selectBox_box ">
                                    @php
                                        if(@$product->seller->role_id == 1){
                                            $country_id = app('general_setting')->country_id;
                                            $city_id = app('general_setting')->city_id;
                                        }else{
                                            $country_id = @$product->seller->SellerBusinessInformation->business_country;
                                            $city_id = @$product->seller->SellerBusinessInformation->business_city;
                                        }
                                        $country = Modules\Setup\Entities\Country::find($country_id);
                                    @endphp
                                    <select class="amaz_select2 mb_10 w-100" id="select_city" name="select_city">
                                        <option data-display="Choose City" selected disabled>{{__('common.choose_city')}}</option>
                                        @foreach($country->cities as $city)
                                            <option value="{{$city->id}}" {{($city->id == $city_id)?'selected':''}}>{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="selectBox_box">
                                    @php
                                        $pickup_locations = \Modules\Shipping\Entities\PickupLocation::where('created_by', $product->user_id)->where('status', 1)->get();
                                    @endphp
                                    <select class="amaz_select2 w-100" id="selectPickup">
                                        <option data-display="Choose pickup location" disabled>{{__('amazy.Choose pickup location')}}</option>
                                        @foreach($pickup_locations as $pickup_location)
                                            <option value="{{$pickup_location->id}}" {{$pickup_location->is_default?'selected':''}}>{{$pickup_location->address}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="amazcart_delivery_wiz_sep d-flex gap_15 mb_10">
                                <div class="icon d-flex align-items-center justify-content-center ">
                                    <img src="{{url('/')}}/public/frontend/amazy/img/product_details/details_car.svg" alt="{{__('amazy.Door Delivery')}}" title="{{__('amazy.Door Delivery')}}">
                                </div>
                                <div class="amazcart_delivery_wiz_content">
                                    <h4 class="font_16 f_w_700 mb_6">{{__('amazy.Door Delivery')}}</h4>
                                    <p class="delivery_text font_14 f_w_400" id="door_delivery"></p>
                                </div>
                            </div>
                            <div class="amazcart_delivery_wiz_sep d-flex gap_15 ">
                                <div class="icon d-flex align-items-center justify-content-center ">
                                    <img src="{{url('/')}}/public/frontend/amazy/img/product_details/details_pickup.svg" alt="{{__('amazy.Pickup Location')}}" title="{{__('amazy.Pickup Location')}}">
                                </div>
                                <div class="amazcart_delivery_wiz_content">
                                    <h4 class="font_16 f_w_700 mb_6">{{__('amazy.Pickup Location')}}</h4>
                                    <p class="delivery_text font_14 f_w_400 mb-0" id="pickup_location"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="amazcart_delivery_wiz mb_20">
                        <div class="amazcart_delivery_wiz_body">
                            <div class="amazcart_delivery_wiz_sep d-flex gap_15 mb_10">
                                <div class="icon d-flex align-items-center justify-content-center ">
                                    <img src="{{url('/')}}/public/frontend/amazy/img/product_details/details_pickup.svg" alt="{{__('amazy.Return Policy')}}" title="{{__('amazy.Return Policy')}}">
                                </div>
                                <div class="amazcart_delivery_wiz_content">
                                    <h4 class="font_16 f_w_700 mb_6">{{__('amazy.Return Policy')}}</h4>
                                    <p class="delivery_text font_14 f_w_400">
                                        {{__('amazy.Easy Return, Quick Refund.')}} <a class="text-nowrap" href="{{url('/return-exchange')}}">{{__('common.see_more')}}.</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isModuleActive('MultiVendor'))
                        <div class="amazcart_delivery_wiz mb_30">
                            <div class="amazcart_delivery_wiz_head">
                                <h4 class="font_18 f_w_700 m-0">{{__('amazy.Seller Information')}}</h4>
                            </div>
                            <div class="amazcart_delivery_wiz_body">
                                <h4 class="font_14 f_w_700 mb-0">
                                    <a id="shopLink" href="
                                        @if ($product->seller->slug)
                                            {{route('frontend.seller',$product->seller->slug)}}
                                        @else
                                            {{route('frontend.seller',base64_encode($product->seller->id))}}
                                        @endif
                                    ">
                                        @if($product->seller->role->type == 'seller')
                                            @if (@$product->seller->SellerAccount->seller_shop_display_name)
                                                {{ @$product->seller->SellerAccount->seller_shop_display_name }}
                                            @else
                                                {{$product->seller->first_name .' '.$product->seller->last_name}}
                                            @endif
                                        @else
                                            {{ app('general_setting')->company_name }}
                                        @endif
                                    </a>

                                </h4>
                                @php
                                    $seller_rating_avg = $product->seller->sellerReviews()->where('status',1)->avg('rating');
                                    $seller_score = ($seller_rating_avg * 20);
                                @endphp                                                          
                                    <input type="hidden" class="form-control" name="seller_id" id="seller_id" value="{{$product->seller->id}}">
                                    <div class="Information_box d-flex gap-2 flex-wrap ">
                                        <div class="Information_box_left flex-fill">
                                            <div class="single_info_seller d-flex align-items-center gap_15">
                                                <h4 class="font_14 f_w_500 m-0">{{getNumberTranslate($seller_score)}}%</h4>
                                                <p class="font_14 f_w_400 m-0">{{__('amazy.Seller Score')}}</p>
                                            </div>                                   
                                            <div class="single_info_seller d-flex align-items-center gap_15">
                                                <h4 class="font_14 f_w_500 m-0">{{getNumberTranslate($product->seller->countFollow())}}</h4>
                                                <p class="font_14 f_w_400 m-0">{{__('amazy.Followers')}}</p>
                                            </div>
                                        </div>
                                        <div class="Information_box_right">
                                            @if(auth()->check() && auth()->id()!= $product->seller->id)
                                                @if(auth()->check() && !auth()->user()->follow($product->seller->id)) 
                                                    <button type="btn" id="follow_seller_btn" class="amaz_primary_btn style3 text-uppercase">{{__('common.follow')}}</button>
                                                @elseif(auth()->check() && auth()->user()->follow($product->seller->id))
                                                    <button type="btn" class="amaz_primary_btn style3 text-uppercase">{{__('amazy.Followed')}}</button>
                                                @endif
                                            @elseif(!auth()->check())
                                                <a href="{{url('/login')}}" class="amaz_primary_btn style3 text-uppercase">{{__('common.follow')}}</a>
                                            @endif
                                        </div>                                
                                    </div>                           
                                <div class="seller_performance_box">
                                    <h4 class="font_14 f_w_700 text-uppercase ">{{__('amazy.Seller Performance')}}</h4>
                                    @foreach($product->seller->sellerReviews->where('status',1) as $seller_review)
                                        <div class="single_seller_performance d-flex align-items-center gap_10 mb-1">
                                            <img src="{{showImage('frontend/amazy/img/product_details/star.svg')}}" alt="{{@$product->seller->SellerAccount->seller_shop_display_name}}" title="{{@$product->seller->SellerAccount->seller_shop_display_name}}">
                                            <p class="font_14 f_w_400 m-0">{{__('amazy.Order Fulfilment Rate')}}:</p>
                                            <h4 class="font_14 f_w_500 m-0">
                                                @if($seller_review->rating == 1)
                                                {{__('common.very_poor')}}
                                                @elseif($seller_review->rating == 2)
                                                {{__('common.poor')}}
                                                @elseif($seller_review->rating == 3)
                                                {{__('common.neutral')}}
                                                @elseif($seller_review->rating == 4)
                                                {{__('common.satisfactory')}}
                                                @elseif($seller_review->rating == 5)
                                                {{__('common.delightful')}}
                                                @endif
                                            </h4>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- product_details_wrapper::end  -->


    @if ($product->related_sales->count() > 0)
        <!-- sujjested_prosuct_area::start  -->
        <div class="sujjested_prosuct_area">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section__title d-flex align-items-center gap-3 mb_30">
                            <h3 class="m-0 flex-fill">{{__('defaultTheme.related_products')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($product->related_sales as $key => $related_sale)
                        @foreach ($related_sale->related_seller_products->take(2) as $key => $related_seller_product)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="product_widget5 mb_30">
                                    <div class="product_thumb_upper">
                                        @php
                                            if(@$related_seller_product->thum_img != null){
                                                $thumbnail = showImage(@$related_seller_product->thum_img);
                                            }else {
                                                $thumbnail = showImage(@$related_seller_product->product->thumbnail_image_source);
                                            }

                                            $price_qty = getProductDiscountedPrice(@$related_seller_product);
                                            $showData = [
                                                'name' => @$related_seller_product->product_name,
                                                'url' => singleProductURL(@$related_seller_product->seller->slug, @$related_seller_product->slug),
                                                'price' => $price_qty,
                                                'thumbnail' => $thumbnail
                                            ];
                                        @endphp
                                        <a href="{{singleProductURL(@$related_seller_product->seller->slug, $related_seller_product->slug)}}" class="thumb">
                                            <img src="
                                            {{$thumbnail}}
                                            " alt="{{@$related_seller_product->product_name}}" title="{{@$related_seller_product->product_name}}">
                                        </a>
                                        <div class="product_action">
                                            <a href="" class="addToCompareFromThumnail" data-producttype="{{ @$related_seller_product->product->product_type }}" data-seller={{ $related_seller_product->user_id }} data-product-sku={{ @$related_seller_product->skus->first()->id }} data-product-id={{ $related_seller_product->id }}>
                                                <i class="ti-control-shuffle"></i>
                                            </a>
                                            <a href="" class="add_to_wishlist {{$related_seller_product->is_wishlist() == 1?'is_wishlist':''}}" id="wishlistbtn_{{$related_seller_product->id}}" data-product_id="{{$related_seller_product->id}}" data-seller_id="{{$related_seller_product->user_id}}">
                                                <i class="ti-heart"></i>
                                            </a>
                                            <a class="quickView" data-product_id="{{$related_seller_product->id}}" data-type="product">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </div>
                                        <div class="recomanded_discount">
                                            @if($related_seller_product->hasDeal)
                                                @if($related_seller_product->hasDeal->discount >0)
                                                    <span class="badge_1">
                                                        @if($related_seller_product->hasDeal->discount >0)
                                                            @if($related_seller_product->hasDeal->discount_type ==0)
                                                                {{getNumberTranslate($related_seller_product->hasDeal->discount)}} % {{__('common.off')}}
                                                            @else
                                                                {{single_price($related_seller_product->hasDeal->discount)}} {{__('common.off')}}
                                                            @endif
    
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                @if($related_seller_product->hasDiscount == 'yes')
                                                @if($related_seller_product->discount > 0)
                                                    <span class="badge_1">
                                                        @if($related_seller_product->discount >0)
                                                            @if($related_seller_product->discount_type ==0)
                                                                {{getNumberTranslate($related_seller_product->discount)}} % {{__('common.off')}}
                                                            @else
                                                                {{single_price($related_seller_product->discount)}} {{__('common.off')}}
                                                            @endif
                                                        @endif
                                                    </span>
                                                @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product__meta text-center">
                                        <span class="product_banding ">{{ @$related_seller_product->brand->name ?? __('amazy.no_brand') }}</span>
                                        <a href="{{singleProductURL(@$related_seller_product->seller->slug, $related_seller_product->slug)}}">
                                            <h4>@if ($related_seller_product->product_name) {{ textLimit(@$related_seller_product->product_name, 50) }} @else {{ textLimit(@$related_seller_product->product->product_name, 50) }} @endif</h4>
                                        </a>
                                        <div class="stars justify-content-center">
                                            @php
                                                $reviews = $related_seller_product->reviews->where('status',1)->pluck('rating');
                                                if(count($reviews)>0){
                                                    $value = 0;
                                                    $rating = 0;
                                                    foreach($reviews as $review){
                                                        $value += $review;
                                                    }
                                                    $rating = $value/count($reviews);
                                                    $total_review = count($reviews);
                                                }else{
                                                    $rating = 0;
                                                    $total_review = 0;
                                                }
                                            @endphp
                                            <x-rating :rating="$rating"/>
                                        </div>
                                        <div class="product_prise">
                                            <p>
                                                @if($related_seller_product->hasDeal)
                                                    {{single_price(selling_price($related_seller_product->skus->first()->selling_price,$related_seller_product->hasDeal->discount_type,$related_seller_product->hasDeal->discount))}}
                                                @else
                                                    @if($related_seller_product->hasDiscount == 'yes')
                                                        {{single_price(selling_price(@$related_seller_product->skus->first()->selling_price,@$related_seller_product->discount_type,@$related_seller_product->discount))}}
                    
                                                    @else
                                                        {{single_price(@$related_seller_product->skus->first()->selling_price)}}
                                                    @endif
                                                @endif
                                            </p>
                                            <a class="add_cart add_to_cart addToCartFromThumnail" data-producttype="{{ @$related_seller_product->product->product_type }}" data-seller={{ $related_seller_product->user_id }} data-product-sku={{ @$related_seller_product->skus->first()->id }} 
                                                @if(@$related_seller_product->hasDeal)
                                                    data-base-price={{ selling_price(@$related_seller_product->skus->first()->selling_price,@$related_seller_product->hasDeal->discount_type,@$related_seller_product->hasDeal->discount) }}
                                                @else
                                                    @if(@$related_seller_product->hasDiscount == 'yes')
                                                        data-base-price={{ selling_price(@$related_seller_product->skus->first()->selling_price,@$related_seller_product->discount_type,@$related_seller_product->discount) }}
                                                    @else
                                                        data-base-price={{ @$related_seller_product->skus->first()->selling_price }}
                                                    @endif
                                                @endif
                                                data-shipping-method=0
                                                data-product-id={{ $related_seller_product->id }}
                                                data-stock_manage="{{$related_seller_product->stock_manage}}"
                                                data-stock="{{@$related_seller_product->skus->first()->product_stock}}"
                                                data-min_qty="{{@$related_seller_product->product->minimum_order_qty}}"
                                                data-prod_info="{{ json_encode($showData) }}"
                                                >{{__('defaultTheme.add_to_cart')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <!-- sujjested_prosuct_area::end  -->
    @endif

    @if(@$product->hasDeal)
        <input type="hidden" id="discount_type" value="{{@$product->hasDeal->discount_type}}">
        <input type="hidden" id="discount" value="{{@$product->hasDeal->discount}}">
    @else
        @if(@$product->hasDiscount == 'yes')
        <input type="hidden" id="discount_type" value="{{$product->discount_type}}">
        <input type="hidden" id="discount" value="{{$product->discount}}">
        @else
        <input type="hidden" id="discount_type" value="{{$product->discount_type}}">
        <input type="hidden" id="discount" value="0">
        @endif
    @endif

    <!--for whole sale price -->
    @if(isModuleActive('WholeSale'))
        <input type="hidden" id="getWholesalePrice" value="@if(@$product->skus->first()->wholeSalePrices->count()){{ json_encode(@$product->skus->first()->wholeSalePrices) }} @else 0 @endif">
    @endif

    <input type="hidden" id="isWholeSaleActive" value="{{isModuleActive('WholeSale')}}">
    <input type="hidden" id="isMultiVendorActive" value="{{isModuleActive('MultiVendor')}}">

@endsection

@push('scripts')
    <script src="{{ asset(asset_path('frontend/default/js/zoom.js')) }}"></script>
    <script>

        (function($){
            "use strict";
            $(document).ready(function(){
                if (window.matchMedia('(min-width: 500px)').matches && $(".zoom_01").length > 0) {
                    zoom_enable();
                }else{
                    $('.varintImg').removeClass('zoom_01');
                }

                if($('#isWholeSaleActive').val() == 1 && $('#getWholesalePrice').val() != 0){
                    var getWholesalePrice = JSON.parse($('#getWholesalePrice').val());
                    if(getWholesalePrice){
                        appendWholeSaleP();
                        $('.append_w_s_p_tbl').removeClass('d-none');
                    }else {
                        $('.append_w_s_p_tbl').addClass('d-none');
                    }
                }else{
                    var getWholesalePrice = null;
                }

                function zoom_enable(){
                    $(".zoom_01").elevateZoom({
                        zoomEnabled: true,
                        zoomWindowHeight:120,
                        zoomWindowWidth:120,
                        zoomLevel:.9
                    });
                }

                both_buy_price($('#base_sku_price').val());
                function both_buy_price(product_price){
                    let both_buy_price = $('#both_buy_price').val();
                    let qty = $('.qty').val();
                    let total_product_price = parseFloat(product_price) * parseInt(qty);
                    let total = currency_format(total_product_price + parseFloat(both_buy_price));
                    $('#both_buy_price_show').text(total);
                }

                $(document).on('click', '.page_link', function(event){
                    event.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    fetch_data(page);

                });

                function fetch_data(page){
                    $('#pre-loader').show();

                    var url = "{{route('frontend.product.reviews.get-data')}}" + '?product_id='+ "{{$product->id}}" +'&page=' + page;


                    if(page != 'undefined'){
                        $.ajax({
                            url: url,
                            success:function(data)
                            {
                                $('#Reviews').html(data);
                                $('#pre-loader').hide();
                            }
                        });
                    }else{
                        toastr.warning('this is undefined');
                    }

                }


                var productType = $('.product_type').val();
                if (productType == 2) {
                    '@if (session()->has('item_details'))'+
                        '@foreach (session()->get('item_details') as $key => $item)'+
                            '@if ($item['name'] === "Color")'+
                                '@foreach ($item['value'] as $k => $value_name)'+
                                    $(".colors_{{$k}}").css("background", "{{ $item['code'][$k]}}");
                                '@endforeach'+
                            '@endif'+
                        '@endforeach'+
                    '@endif'
                }


                $(document).on('click', '.attr_val_name', function(){

                    $(this).parent().parent().find('.attr_value_name').val($(this).attr('data-value')+'-'+$(this).attr('data-value-key'));
                    $(this).parent().parent().find('.attr_value_id').val($(this).attr('data-value')+'-'+$(this).attr('data-value-key'));

                    if ($(this).attr('color') == "color") {
                        $('.attr_clr').removeClass('selected_btn');
                    }
                    if ($(this).attr('color') == "not") {
                        $('.not_111').removeClass('selected_btn');
                    }
                    $(this).addClass('selected_btn');
                    get_price_accordint_to_sku();

                });

                var old_html = $("#myTabContent").html();
                $('.var_img_sources').hover(function() {
                    var logo = $(this).attr("src"); // get logo from data-icon parameter
                    $('.varintImg').attr("src", logo); // change logo
                }, function() {
                    $("#myTabContent").html(old_html); // remove logo
                    $('.slider-nav').slick('refresh');
                    if($(".zoom_01").length > 0){
                        zoom_enable();
                    }
                });


                $(document).on('click', '.add_to_cart_btn', function(event){
                    event.preventDefault();
                    var showData = {
                        'name' : "{{ @$product->product_name }}",
                        'url' : "{{singleProductURL(@$product->seller->slug, @$product->slug)}}",
                        'price' : currency_format($('#final_price').val()),
                        'thumbnail' : $('#thumb_image').val()
                    };
                    addToCart($('#product_sku_id').val(),$('#seller_id').val(),$('#qty').data('value'),$('#base_sku_price').val(),$('#shipping_type').val(),'product',showData);

                });

                $(document).on('click', '#both_buy_btn', function (event){
                    event.preventDefault();
                    let product_sku_id = $(this).data('sku_id');
                    let product_id = $(this).data('product_id');
                    let qty = $(this).data('qty');
                    let seller_id = $(this).data('seller_id');

                    addToCart(product_sku_id, seller_id, qty, $('#both_buy_price').val(), '0', 'product');
                    addToCart($('#product_sku_id').val(),$('#seller_id').val(),$('#qty').data('value'),$('#base_sku_price').val(),$('#shipping_type').val(),'product');
                });

                $(document).on('click', '#wishlist_btn', function(event){
                    event.preventDefault();
                    let product_id = $(this).data('product_id');
                    let seller_id = $(this).data('seller_id');
                    let type = "product";
                    let is_login = $('#login_check').val();
                    if(is_login == 1){
                        addToWishlist(product_id, seller_id, type);
                    }else{
                        toastr.warning("{{__('defaultTheme.please_login_first')}}","{{__('common.warning')}}");
                    }

                });


                $(document).on('click', '#add_to_compare_btn', function(event){
                    event.preventDefault();
                    let product_sku_class = $(this).data('product_sku_id');
                    let product_sku_id = $(product_sku_class).val();
                    let product_type = $(this).data('product_type');
                    addToCompare(product_sku_id, product_type, 'product');
                });

                $(document).on('click', '.qtyChange', function(event){
                    event.preventDefault();
                    let value = $(this).data('value');
                    qtyChange(value);
                });

                function qtyChange(val){
                    $('.cart-qty-minus').prop('disabled',false);
                    let available_stock = $('#availability').html();
                    let stock_manage_status = $('#stock_manage_status').val();
                    let maximum_order_qty = $('#maximum_order_qty').val();
                    let minimum_order_qty = $('#minimum_order_qty').val();
                    let qty = $('#qty').data('value');
                    console.log(qty);
                    if (stock_manage_status != 0) {

                        if(val == '+'){
                            if (parseInt(qty) < parseInt(available_stock)) {
                                if(maximum_order_qty != ''){
                                    if(parseInt(qty) < parseInt(maximum_order_qty)){
                                    let qty1 = parseInt(++qty);
                                    $('#qty').val(numbertrans(qty1));
                                    $('#qty').data('value',qty1);
                                    totalValue(qty1, '#base_price','#total_price', getWholesalePrice);
                                    }else{
                                        toastr.warning('{{__("defaultTheme.maximum_quantity_limit_is")}}'+maximum_order_qty+'.', '{{__("common.warning")}}');
                                    }
                                }else{
                                    let qty1 = parseInt(++qty);
                                    $('#qty').val(numbertrans(qty1));
                                    $('#qty').data('value',qty1);
                                    totalValue(qty1, '#base_price','#total_price', getWholesalePrice);
                                }
                            }else{
                                toastr.error("{{__('defaultTheme.no_more_stock')}}", "{{__('common.error')}}");
                            }

                        }
                        if(val == '-'){
                            if (parseInt(qty) <= parseInt(available_stock)) {
                                if(minimum_order_qty != ''){
                                    if(parseInt(qty) > parseInt(minimum_order_qty)){
                                        if(qty>1){
                                            let qty1 = parseInt(--qty)
                                            $('#qty').val(numbertrans(qty1));
                                            $('#qty').data('value',qty1);
                                            totalValue(qty1, '#base_price','#total_price', getWholesalePrice)
                                            $('.cart-qty-minus').prop('disabled',false);
                                        }else{
                                            $('.cart-qty-minus').prop('disabled',true);
                                        }
                                    }else{
                                        toastr.warning('{{__("defaultTheme.minimum_quantity_Limit_is")}}'+minimum_order_qty+'.', '{{__("common.warning")}}')
                                    }
                                }else{
                                    if(parseInt(qty)>1){
                                        let qty1 = parseInt(--qty)
                                        $('#qty').val(numbertrans(qty1));
                                        $('#qty').data('value',qty1);
                                        totalValue(qty1, '#base_price','#total_price', getWholesalePrice)
                                        $('.cart-qty-minus').prop('disabled',false);
                                    }else{
                                        $('.cart-qty-minus').prop('disabled',true);
                                    }
                                }
                            }else{
                                toastr.error("{{__('defaultTheme.no_more_stock')}}", "{{__('common.error')}}");
                            }
                        }

                    }
                    else {
                        if(val == '+'){
                            if(maximum_order_qty != ''){
                                if(parseInt(qty) < parseInt(maximum_order_qty)){
                                let qty1 = parseInt(++qty);
                                $('#qty').val(numbertrans(qty1));
                                $('#qty').data('value',qty1);
                                totalValue(qty1, '#base_price','#total_price', getWholesalePrice);
                                }else{
                                    toastr.warning('{{__("defaultTheme.maximum_quantity_limit_is")}}'+maximum_order_qty+'.', '{{__("common.warning")}}')
                                }
                            }else{
                                let qty1 = parseInt(++qty);
                                $('#qty').val(numbertrans(qty1));
                                $('#qty').data('value',qty1);
                                totalValue(qty1, '#base_price','#total_price', getWholesalePrice);
                            }


                        }
                        if(val == '-'){
                            if(minimum_order_qty != ''){
                                if(parseInt(qty) > parseInt(minimum_order_qty)){
                                    if(qty>1){
                                        let qty1 = parseInt(--qty)
                                        $('#qty').val(numbertrans(qty1));
                                        $('#qty').data('value',qty1);
                                        totalValue(qty1, '#base_price','#total_price', getWholesalePrice)
                                        $('.cart-qty-minus').prop('disabled',false);
                                    }else{
                                        $('.cart-qty-minus').prop('disabled',true);
                                    }
                                }else{
                                    toastr.warning('{{__("defaultTheme.minimum_quantity_Limit_is")}}'+minimum_order_qty+'.', '{{__("common.warning")}}')
                                }
                            }else{
                                if(parseInt(qty)>1){
                                    let qty1 = parseInt(--qty)
                                    $('#qty').val(numbertrans(qty1));
                                    $('#qty').data('value',qty1);
                                    totalValue(qty1, '#base_price','#total_price', getWholesalePrice)
                                    $('.cart-qty-minus').prop('disabled',false);
                                }else{
                                    $('.cart-qty-minus').prop('disabled',true);
                                }
                            }
                        }
                    }

                }

                function totalValue(qty, main_price, total_price, getWholesalePrice){
                    if($('#isWholeSaleActive').val() == 1 && getWholesalePrice != null){
                        var max_qty='',min_qty='',selling_price='';
                        for (let i = 0; i < getWholesalePrice.length; ++i) {
                            max_qty = getWholesalePrice[i].max_qty;
                            min_qty = getWholesalePrice[i].min_qty;
                            selling_price = getWholesalePrice[i].selling_price;

                            if ( (min_qty<=qty) && (max_qty>=qty) ){
                                main_price = selling_price;
                            }
                            else if(max_qty < qty){
                                main_price = selling_price;
                            }
                            else if(main_price=='#base_price'){
                                var main_price = $('#base_sku_price').val();
                            }
                        }
                        let discount = $('#discount').val();
                        let discount_type = $('#discount_type').val();
                        if (discount_type == 0) {
                            discount = (main_price * discount) / 100;
                        }
                        var base_sku_price = (main_price - discount);
                    }else {
                        var base_sku_price = $('#base_sku_price').val();
                    }
                    let value = parseInt(qty) * parseFloat(base_sku_price);
                    $(total_price).html(currency_format(value));

                    both_buy_price(base_sku_price);
                    $('#final_price').val(value);
                }

                function get_price_accordint_to_sku(){
                    var value = $("input[name='attr_val_name[]']").map(function(){return $(this).val();}).get();
                    var id = $("input[name='attr_val_id[]']").map(function(){return $(this).val();}).get();
                    var product_id = $("#product_id").val();
                    var user_id = $('#seller_id').val();
                    $('#pre-loader').show();
                    $.post("{{ route('seller.get_seller_product_sku_wise_price') }}", {_token:'{{ csrf_token() }}', id:id, product_id:product_id, user_id:user_id}, function(response){

                        if (response != 0) {
                            let discount_type = $('#discount_type').val();
                            let discount = $('#discount').val();
                            let qty = $('.qty').val();

                            if(response.data.whole_sale_prices){
                                getWholesalePrice = response.data.whole_sale_prices;
                                if(getWholesalePrice){
                                    appendWholeSaleP();
                                    $('.append_w_s_p_tbl').removeClass('d-none');
                                }else {
                                    $('.append_w_s_p_tbl').addClass('d-none');
                                }
                            }

                            calculatePrice(response.data.selling_price, discount, discount_type, qty);
                            $('#sku_id_li').text(response.data.sku.sku);
                            $('#product_sku_id').val(response.data.id);
                            $('#availability').html(response.data.product_stock);

                            if(response.data.product.stock_manage == 1 && parseInt(response.data.product_stock) >= parseInt(response.data.product.product.minimum_order_qty) || response.data.product.stock_manage == 0){
                                $('#add_to_cart_div').html(`
                                    <div class="col-md-6">
                                        <button type="button" id="add_to_cart_btn" class="amaz_primary_btn style2 mb_20  add_to_cart text-uppercase add_to_cart_btn flex-fill text-center w-100">{{__('defaultTheme.add_to_cart')}}</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="butItNow" class="amaz_primary_btn3 mb_20  w-100 text-center justify-content-center text-uppercase buy_now_btn" data-id="{{$product->id}}" data-type="product">{{__('common.buy_now')}}</button>
                                    </div>
                                `);
                                $('#stock_div').html(`<span class="stoke_badge">In stock</span>`);
                                if($('#isMultiVendorActive').val() == 1){
                                    $('#cart_footer_mobile').html(`
                                        <a href="
                                            @if ($product->seller->slug)
                                                {{route('frontend.seller',$product->seller->slug)}}
                                            @else
                                                {{route('frontend.seller',base64_encode($product->seller->id))}}
                                            @endif
                                        " class="d-flex flex-column justify-content-center product_details_icon">
                                            <i class="ti-save"></i>
                                            <span>store</span>
                                        </a>
                                        <button type="button" class="product_details_button style1 buy_now_btn" data-id="{{$product->id}}" data-type="product">
                                            <span>{{__('common.buy_now')}}</span>
                                        </button>
                                        <button class="product_details_button add_to_cart_btn" type="button">{{__('common.add_to_cart')}}</button>
                                    `);
                                }else{
                                    if($('#isMultiVendorActive').val() == 1){
                                        $('#cart_footer_mobile').html(`
                                            <a href="
                                                @if ($product->seller->slug)
                                                    {{route('frontend.seller',$product->seller->slug)}}
                                                @else
                                                    {{route('frontend.seller',base64_encode($product->seller->id))}}
                                                @endif
                                            " class="d-flex flex-column justify-content-center product_details_icon">
                                                <i class="ti-save"></i>
                                                <span>store</span>
                                            </a>
                                            <button type="button" class="product_details_button style1 buy_now_btn" data-id="{{$product->id}}" data-type="product">
                                                <span>{{__('common.buy_now')}}</span>
                                            </button>
                                            <button class="product_details_button add_to_cart_btn" type="button">{{__('common.add_to_cart')}}</button>
                                        `);
                                    }else{
                                        $('#cart_footer_mobile').html(`
                                            <button type="button" class="product_details_button style1 buy_now_btn" data-id="{{$product->id}}" data-type="product">
                                                <span>{{__('common.buy_now')}}</span>
                                            </button>
                                            <button class="product_details_button add_to_cart_btn" type="button">{{__('common.add_to_cart')}}</button>
                                        `);
                                    }
                                }
                            }

                            else{
                                $('#add_to_cart_div').html(`
                                    <div class="col-md-6">
                                        <button type="button" disabled class="amaz_primary_btn style2 mb_20 add_to_cart text-uppercase flex-fill text-center w-100">{{__('defaultTheme.out_of_stock')}}</button>
                                    </div>
                                `);
                                $('#stock_div').html(`<span class="stokeout_badge">{{__('defaultTheme.out_of_stock')}}</span>`);
                                toastr.warning("{{__('defaultTheme.out_of_stock')}}");
                                $('#cart_footer_mobile').html(`
                                    <button type="button" class="product_details_button style1" disabled>
                                        <span>{{__('defaultTheme.out_of_stock')}}</span>
                                    </button>
                                    <button type="button" class="product_details_button" disabled>{{__('defaultTheme.out_of_stock')}}</button>
                                `);
                            }
                        }else {
                            toastr.error("{{__('defaultTheme.no_stock_found_for_this_seller')}}", "{{__('common.error')}}");
                        }
                        $('#pre-loader').hide();
                    });
                }

                function calculatePrice(main_price, discount, discount_type, qty){
                    var main_price = main_price;
                    var discount = discount;
                    var discount_type = discount_type;
                    var total_price = 0;
                    if($('#isWholeSaleActive').val() == 1 && getWholesalePrice != null){
                        var max_qty='',min_qty='',selling_price='';
                        for (let i = 0; i < getWholesalePrice.length; ++i) {
                            max_qty = getWholesalePrice[i].max_qty;
                            min_qty = getWholesalePrice[i].min_qty;
                            selling_price = getWholesalePrice[i].selling_price;

                            if ( (min_qty<=qty) && (max_qty>=qty) ){
                                main_price = selling_price;
                            }
                            else if(max_qty < qty){
                                main_price = selling_price;
                            }
                        }
                    }

                    if (discount_type == 0) {
                        discount = (main_price * discount) / 100;
                    }
                    total_price = (main_price - discount);

                    $('#total_price').text(numbertrans(currency_format((total_price * qty))));

                    both_buy_price((total_price));
                    //$('#base_sku_price').val(total_price);
                    $('#final_price').val(total_price);
                }

                function appendWholeSaleP(){
                    $('#append_w_s_p_all').empty();
                    $.each(getWholesalePrice, function(index, value) {
                        $('#append_w_s_p_all').append(`
                        <tr class="border-bottom">
                            <td class="text-left">
                                <span>${numbertrans(value.min_qty)}</span>
                            </td>
                            <td class="text-left">
                                <span>${numbertrans(value.max_qty)}</span>
                            </td>
                            <td class="text-left">
                                <span>${numbertrans(value.selling_price)}</span>
                            </td>
                        </tr>
                    `);
                    });
                }

                $(document).on('change', '#select_city', function(event){
                    let id = $(this).val();
                    let data = {
                        city_id : id,
                        _token : "{{csrf_token()}}",
                        seller_id: "{{$product->seller->id}}"
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.item.get_pickup_by_city')}}",data,function(response){
                        $('#selectPickup').empty();
                        $('#selectPickup').append(
                            `<option selected disabled data-display="Choose pickup location">Choose pickup location</option>`
                        );
                        $.each(response, function(index, pickup) {
                            $('#selectPickup').append('<option value="' + pickup
                                .id + '">' + pickup.address + '</option>');
                        });
                        $('#selectPickup').niceSelect('update');
                        $('#pre-loader').hide();
                    });
                });

                $(document).on('change', '#selectPickup', function (event){
                    getPickupInfo();
                });
                getPickupInfo();
                function getPickupInfo(){
                    let pickup_id = $('#selectPickup').val();
                    let data = {
                        pickup_id : pickup_id,
                        _token : "{{csrf_token()}}",
                        seller_id: "{{$product->seller->id}}"
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.item.get_pickup_info')}}",data,function(response){
                        if(response.shipping.cost == 0){
                            $('#door_delivery').text(`
                                ${trans('amazy.free_shipping_note')} ${response.shipping.shipment_time}.
                            `);
                        }else{
                            $('#door_delivery').text(`
                                ${trans('amazy.shipping_note')} ${currency_format(response.shipping.cost)}. ${trans('amazy.Delivery within')} ${response.shipping.shipment_time}.
                            `);
                        }
                        $('#pickup_location').text(`
                            Delivery from pickup location always free of cost. Pickup address: ${response.pickup_location.address}.
                            Counttry: ${response.pickup_location.country.name} State: ${response.pickup_location.state.name} City: ${response.pickup_location.city.name} Post code: ${response.pickup_location.pin_code}
                        `);
                        $('#pre-loader').hide();
                    });
                }
                $(document).on("click", ".buy_now_btn", function(event){
                    event.preventDefault();
                    buyNow($('#product_sku_id').val(),$('#seller_id').val(),$('#qty').val(),$('#base_sku_price').val(),$('#shipping_type').val(),'product', $('#owner').val());
                });
                $(document).on("click","#follow_seller_btn" ,function(event){
                    event.preventDefault();
                    let id = $('#seller_id').val();
                    let data = {
                        seller_id: id,
                        _token : "{{csrf_token()}}"
                    }
                    $('#pre-loader').show();
                    $(this).prop("disabled",true);
                    $.post("{{route('frontend.follow_seller')}}",data,function(response){
                        if(response.message == 'success'){
                            toastr.success("{{__('amazy.Followed Successfully')}}","{{__('common.success')}}");
                            $('#follow_seller_btn').text("{{__('amazy.Followed')}}");
                        }
                        else{
                            $(this).prop("disabled",false);
                            toastr.error("{{__('amazy.Not Followed')}}","{{__('common.error')}}");
                        }
                        $('#pre-loader').hide();
                    });  
                });
                
            });
        })(jQuery);
    </script>
@endpush

@include(theme('partials.add_to_cart_script'))
@include(theme('partials.add_to_compare_script'))