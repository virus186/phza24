@php
    $total_number_of_item_per_page = $products->perPage();
    $total_number_of_items = ($products->total() > 0) ? $products->total() : 0;
    $total_number_of_pages = $total_number_of_items / $total_number_of_item_per_page;
    $reminder = $total_number_of_items % $total_number_of_item_per_page;
    if ($reminder > 0) {
        $total_number_of_pages += 1;
    }
    $current_page = $products->currentPage();
    $previous_page = $products->currentPage() - 1;
    if($current_page == $products->lastPage()){
        $show_end = $total_number_of_items;
    }else{
        $show_end = $total_number_of_item_per_page * $current_page;
    }


    $show_start = 0;
    if($total_number_of_items > 0){
        $show_start = ($total_number_of_item_per_page * $previous_page) + 1;
    }


@endphp
    <div class="row ">
        <div class="col-12">
            <div class="box_header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="font_16 f_w_500 mr_10 mb-0">{{__('defaultTheme.showing')}} @if($show_start == $show_end) {{getNumberTranslate($show_end)}} @else {{getNumberTranslate($show_start)}} - {{getNumberTranslate($show_end)}} @endif {{__('defaultTheme.out_of_total')}} {{getNumberTranslate($total_number_of_items)}} {{__('common.products')}}</h5>
                <div class="box_header_right ">
                    <div class="short_select d-flex align-items-center gap_10 flex-wrap">
                        <div class="prduct_showing_style">
                            <ul class="nav align-items-center" id="myTab" role="tablist">
                                <li class="nav-item lh-1">
                                    <a class="nav-link view-product active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <img src="{{ showImage('frontend/amazy/img/svg/grid_view.svg') }}" alt="Grid View" title="Grid View">
                                    </a>
                                </li>
                                <li class="nav-item lh-1">
                                    <a class="nav-link view-product" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                        <img src="{{ showImage('frontend/amazy/img/svg/list_view.svg') }}" alt="List View" title="List View">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="shorting_box">
                            <select name="paginate_by" class="amaz_select getFilterUpdateByIndex" id="paginate_by">
                                <option value="9" @if (isset($paginate) && $paginate == "9") selected @endif>{{__('common.show')}} {{getNumberTranslate(9)}} {{__('common.item’s')}}</option>
                                <option value="12" @if (isset($paginate) && $paginate == "12") selected @endif>{{__('common.show')}} {{getNumberTranslate(12)}} {{__('common.item’s')}}</option>
                                <option value="16" @if (isset($paginate) && $paginate == "16") selected @endif>{{__('common.show')}} {{getNumberTranslate(16)}} {{__('common.item’s')}}</option>
                                <option value="25" @if (isset($paginate) && $paginate == "25") selected @endif>{{__('common.show')}} {{getNumberTranslate(25)}} {{__('common.item’s')}}</option>
                                <option value="30" @if (isset($paginate) && $paginate == "30") selected @endif>{{__('common.show')}} {{getNumberTranslate(30)}} {{__('common.item’s')}}</option>
                            </select>
                        </div>
                        <div class="shorting_box">
                            <select class="amaz_select getFilterUpdateByIndex" name="sort_by" id="product_short_list">
                                <option value="new" @if (isset($sort_by) && $sort_by == "new") selected @endif>{{ __('common.new') }}</option>
                                <option value="old" @if (isset($sort_by) && $sort_by == "old") selected @endif>{{ __('common.old') }}</option>
                                <option value="alpha_asc" @if (isset($sort_by) && $sort_by == "alpha_asc") selected @endif>{{ __('defaultTheme.name_a_to_z') }}</option>
                                <option value="alpha_desc" @if (isset($sort_by) && $sort_by == "alpha_desc") selected @endif>{{ __('defaultTheme.name_z_to_a') }}</option>
                                <option value="low_to_high" @if (isset($sort_by) && $sort_by == "low_to_high") selected @endif>{{ __('defaultTheme.price_low_to_high') }}</option>
                                <option value="high_to_low" @if (isset($sort_by) && $sort_by == "high_to_low") selected @endif>{{ __('defaultTheme.price_high_to_low') }}</option>
                            </select>
                        </div>
                        <div class="flex-fill text-end">
                            <div class="category_toggler d-inline-block d-lg-none  gj-cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19.5" height="13" viewBox="0 0 19.5 13">
                                    <g id="filter-icon" transform="translate(28)">
                                        <rect id="Rectangle_1" data-name="Rectangle 1" width="19.5" height="2" rx="1" transform="translate(-28)" fill="#fd4949"/>
                                        <rect id="Rectangle_2" data-name="Rectangle 2" width="15.5" height="2" rx="1" transform="translate(-26 5.5)" fill="#fd4949"/>
                                        <rect id="Rectangle_3" data-name="Rectangle 3" width="5" height="2" rx="1" transform="translate(-20.75 11)" fill="#fd4949"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content mb_30" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <!-- content  -->
            <div class="row custom_rowProduct product_page_list">
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <input type="hidden" name="base_sku_price" id="base_sku_price" value="
                        @if(@$product->hasDeal)
                            {{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                        @else
                            @if(@$product->hasDiscount == 'yes')
                            {{ selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) }}
                            @else
                            {{ @$product->skus->first()->selling_price }}
                            @endif
                        @endif
                        ">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                            <div class="product_widget5 mb_30 style5">
                                <div class="product_thumb_upper">
                                    @php
                                        if(@$product->thum_img != null){
                                            $thumbnail = showImage(@$product->thum_img);
                                        }else {
                                            $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                        }

                                        $price_qty = getProductDiscountedPrice(@$product);
                                        $showData = [
                                            'name' => @$product->product_name,
                                            'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                                            'price' => $price_qty,
                                            'thumbnail' => $thumbnail
                                        ];
                                    @endphp
                                    <a href="{{singleProductURL($product->seller->slug, $product->slug)}}" class="thumb">
                                        <img data-src="{{$thumbnail}}" src="{{showImage(themeDefaultImg())}}" alt="{{@$product->product_name}}" title="{{@$product->product_name}}" class="lazyload">
                                    </a>
                                    <div class="product_action">
                                        <a href="" class="addToCompareFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }} data-product-id={{ $product->id }}>
                                            <i class="ti-control-shuffle"></i>
                                        </a>
                                        <a href="" class="add_to_wishlist {{$product->is_wishlist() == 1?'is_wishlist':''}}" id="wishlistbtn_{{$product->id}}" data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}">
                                            <i class="ti-heart"></i>
                                        </a>
                                        <a class="quickView" data-product_id="{{$product->id}}" data-type="product">
                                            <i class="ti-eye"></i>
                                        </a>
                                    </div>
                                    @if($product->hasDeal)
                                        @if($product->hasDeal->discount > 0)
                                            <span class="badge_1">
                                                @if(@$product->hasDeal->discount_type == 0)
                                                    -{{getNumberTranslate(@$product->hasDeal->discount)}}%
                                                @else
                                                    -{{single_price(@$product->hasDeal->discount)}}
                                                @endif

                                            </span>
                                        @endif
                                    @else
                                        @if(@$product->hasDiscount == 'yes')
                                            @if(@$product->discount > 0)
                                                <span class="badge_1">
                                                    @if(@$product->discount_type == 0)
                                                    -{{getNumberTranslate(@$product->discount)}}%
                                                    @else
                                                    -{{single_price(@$product->discount)}}
                                                    @endif

                                                </span>
                                            @endif

                                        @endif
                                    @endif
                                </div>
                                <div class="product__meta text-center">

                                    <span class="product_banding ">{{ @$product->brand->name ?? __('amazy.no_brand') }}</span>
                                    <a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}">
                                        <h4>@if ($product->product_name) {{ textLimit(@$product->product_name, 50) }} @else {{ textLimit(@$product->product->product_name, 50) }} @endif</h4>
                                    </a>
                                    <div class="stars justify-content-center">
                                        @php
                                            $reviews = @$product->reviews->where('status',1)->pluck('rating');
                
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
                                            <span>
                                                @if(getProductwitoutDiscountPrice(@$product) != single_price(0))
                                                    {{getProductwitoutDiscountPrice(@$product)}}
                                                @endif
                                            </span> 
                                            {{getProductDiscountedPrice(@$product)}}
                                        </p>
                                        <a class="add_cart add_to_cart addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }} 
                                            @if(@$product->hasDeal)
                                                data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                                            @else
                                                @if(@$product->hasDiscount == 'yes')
                                                    data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) }}
                                                @else
                                                    data-base-price={{ @$product->skus->first()->selling_price }}
                                                @endif
                                            @endif
                                            data-shipping-method=0
                                            data-product-id={{ $product->id }}
                                            data-stock_manage="{{$product->stock_manage}}"
                                            data-stock="{{@$product->skus->first()->product_stock}}"
                                            data-min_qty="{{@$product->product->minimum_order_qty}}"
                                            data-prod_info="{{ json_encode($showData) }}"
                                            >{{__('defaultTheme.add_to_cart')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center alert alert-danger">
                            {{ __('defaultTheme.no_product_found') }}
                        </div>
                    </div>
                @endif
            </div>

            <!--/ content  -->
        </div>
        <div class="tab-pane fade " id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <!-- content  -->
            <div class="row">
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <input type="hidden" name="base_sku_price" id="base_sku_price" value="
                        @if(@$product->hasDeal)
                            {{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                        @else
                            @if(@$product->hasDiscount == 'yes')
                            {{ selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) }}
                            @else
                            {{ @$product->skus->first()->selling_price }}
                            @endif
                        @endif
                        ">
                        <div class="col-xl-12">
                            <div class="product_widget5 mb_30 list_style_product">
                                <div class="product_thumb_upper m-0">
                                    @php
                                        if(@$product->thum_img != null){
                                            $thumbnail = showImage(@$product->thum_img);
                                        }else {
                                            $thumbnail = showImage(@$product->product->thumbnail_image_source);
                                        }
                                        $price_qty = getProductDiscountedPrice(@$product->product);
                                        $showData = [
                                            'name' => @$product->product->product_name,
                                            'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                                            'price' => $price_qty,
                                            'thumbnail' => $thumbnail
                                        ];
                                    @endphp
                                    <a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}" class="thumb">
                                        <img src="{{$thumbnail}}" alt="{{@$product->product_name?@$product->product_name:@$product->product->product_name}}" title="{{@$product->product_name?@$product->product_name:@$product->product->product_name}}" >
                                    </a>
                                    <div class="product_action">
                                        <a href="" class="addToCompareFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }} data-product-id={{ $product->id }}>
                                            <i class="ti-control-shuffle"></i>
                                        </a>
                                        <a href="" class="add_to_wishlist {{$product->is_wishlist() == 1?'is_wishlist':''}}" id="wishlistbtn_{{$product->id}}" data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}">
                                            <i class="ti-heart"></i>
                                        </a>
                                        <a class="quickView" data-product_id="{{$product->id}}" data-type="product">
                                            <i class="ti-eye"></i>
                                        </a>
                                    </div>
                                    @if($product->hasDeal)
                                        @if($product->hasDeal->discount > 0)
                                            <span class="badge_1">
                                                @if(@$product->hasDeal->discount_type == 0)
                                                    -{{getNumberTranslate(@$product->hasDeal->discount)}}%
                                                @else
                                                    -{{single_price(@$product->hasDeal->discount)}}
                                                @endif

                                            </span>
                                        @endif
                                    @else
                                        @if(@$product->hasDiscount == 'yes')
                                            @if(@$product->discount > 0)
                                                <span class="badge_1">
                                                    @if(@$product->discount_type == 0)
                                                    -{{getNumberTranslate(@$product->discount)}}%
                                                    @else
                                                    -{{single_price(@$product->discount)}}
                                                    @endif

                                                </span>
                                            @endif

                                        @endif
                                    @endif
                                </div>
                                <div class="product__meta">
                                    <span class="product_banding ">
                                        @if($product->product->brand->name )
                                            {{ $product->product->brand->name ?? __('amazy.no_brand') }}
                                        @endif
                                    </span>
                                    <a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}">
                                        <h4>
                                            @if ($product->product_name) {{ textLimit(@$product->product_name, 60) }} @else {{ textLimit(@$product->product->product_name, 60) }} @endif
                                        </h4>
                                    </a>
                                        @php
                                            $reviews = @$product->reviews->where('status',1)->pluck('rating');

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
                                        <div class="stars">
                                            <x-rating :rating="$rating"/>
                                        </div>
                                    
                                    <div class="product_prise justify-content-start">
                                        <p>
                                            @if(getProductwitoutDiscountPrice(@$product) != single_price(0))
                                            <span>
                                                {{getProductwitoutDiscountPrice(@$product)}}
                                            </span>
                                            @endif 
                                            {{getProductDiscountedPrice($product)}}
                                        </p>
                                        <a class="add_cart add_to_cart addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
                                            @if(@$product->hasDeal)
                                                data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                                            @else
                                            @if(@$product->hasDiscount == 'yes')
                                                data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) }}
                                            @else
                                                data-base-price={{ @$product->skus->first()->selling_price }}
                                            @endif
                                            @endif
                                            data-shipping-method=0
                                            data-product-id={{ $product->id }}
                                            data-stock_manage="{{$product->stock_manage}}"
                                            data-stock="{{@$product->skus->first()->product_stock}}"
                                            data-min_qty="{{$product->product->minimum_order_qty}}"
                                            data-prod_info= "{{json_encode($showData)}}"
                                            href="javascript:void(0)"
                                            >{{__('common.add_to_cart')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center alert alert-danger">
                            {{ __('defaultTheme.no_product_found') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <input type="hidden" name="filterCatCol" class="filterCatCol" value="0">
        <!--/ content  -->
        <x-pagination-component :items="$products" type=""/>
    </div>
