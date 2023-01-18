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
<div class="dashboard_white_box style2 bg-white mb_25">
    <div class="d-flex align-items-center gap_20 mb_30">
        <h5 class="font_14 f_w_400 flex-fill">{{__('defaultTheme.showing')}} @if($show_start == $show_end) {{getNumberTranslate($show_end)}} @else {{getNumberTranslate($show_start)}} - {{getNumberTranslate($show_end)}} @endif {{__('common.of')}} {{getNumberTranslate($total_number_of_items)}} {{__('common.results')}}</h5>
        <div class="wish_selects d-flex align-items-center gap_10 flex-wrap">
            <select class="amaz_select4" name="paginate_by" id="paginate_by">
                <option value="8" @if (isset($paginate) && $paginate == "8") selected @endif>{{__('common.show')}} {{getNumberTranslate(8)}} {{__('common.item’s')}}</option>
                <option value="12" @if (isset($paginate) && $paginate == "12") selected @endif>{{__('common.show')}} {{getNumberTranslate(12)}} {{__('common.item’s')}}</option>
                <option value="16" @if (isset($paginate) && $paginate == "16") selected @endif>{{__('common.show')}} {{getNumberTranslate(16)}} {{__('common.item’s')}}</option>
                <option value="24" @if (isset($paginate) && $paginate == "24") selected @endif>{{__('common.show')}} {{getNumberTranslate(24)}} {{__('common.item’s')}}</option>
                <option value="32" @if (isset($paginate) && $paginate == "32") selected @endif>{{__('common.show')}} {{getNumberTranslate(32)}} {{__('common.item’s')}}</option>
            </select>
            <select name="sort_by" class="amaz_select4" id="product_short_list">
                <option value="new" @if (isset($sort_by) && $sort_by == "new") selected @endif>{{__('common.new')}}</option>
                <option value="old" @if (isset($sort_by) && $sort_by == "old") selected @endif>{{__('common.old')}}</option>
                <option value="low_to_high" @if (isset($sort_by) && $sort_by == "low_to_high") selected @endif>{{__('common.price')}} ({{__('amazy.Low to high')}})</option>
                <option value="high_to_low" @if (isset($sort_by) && $sort_by == "high_to_low") selected @endif>{{__('common.price')}} ({{__('amazy.High to low')}})</option>
            </select>
        </div>
    </div>
    <input type="hidden" name="filterCatCol" class="filterCatCol" value="0">
    <div class="dashboard_wishlist_grid mb_40">
        @if(count($products) > 0)
            @foreach($products as $product)
                @if($product->type =='product')
                    @php
                         if(@$product->product->thum_img != null){
                            $thumbnail = showImage(@$product->product->thum_img);
                         } 
                         else {
                            $thumbnail = showImage(@$product->product->product->thumbnail_image_source);
                        }
                    @endphp
                    <div class="product_widget5 style3 bg-white">
                        <div class="product_thumb_upper">
                            <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug)}}" class="thumb">
                                <img src="{{$thumbnail}}" alt="{{@$product->product->product_name}}" title="{{@$product->product->product_name}}">
                            </a>
                            <div class="product_action">
                                <a class="addToCompareFromThumnail" data-producttype="{{ @$product->product->product->product_type }}"
                                     data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }} data-product-id={{ @$product->product->id }}>
                                    <i class="ti-control-shuffle"></i>
                                </a>
                                <a class="quickView" data-product_id="{{$product->seller_product_id}}" data-type="product">
                                    <i class="ti-eye"></i>
                                </a>
                                <a class="removeWishlist" data-id="{{ $product->id }}">
                                    <i class="ti-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product__meta text-center">
                            <span class="product_banding ">
                                @if(@$product->product->brand)
                                    {{@$product->product->brand->name}}
                                @else
                                  {{__('amazy.no_brand')}}
                                @endif
                            </span>
                            <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug)}}">
                                <h4>@if (@$product->product->product_name) {{ textLimit(@$product->product->product_name, 28) }}  @else {{ textLimit(@$product->product->product->product_name, 28) }} @endif</h4>
                            </a>
                            @php
                                $reviews = @$product->product->reviews->where('status',1)->pluck('rating');
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
                            <div class="stars justify-content-center">
                                <x-rating :rating="$rating"/>
                            </div>

                            <div class="product_prise">

                                @php
                                    $price_qty = getProductDiscountedPrice(@$product->product);
                                    $showData = [
                                        'name' => @$product->product->product_name,
                                        'url' => singleProductURL(@$product->product->seller->slug, @$product->product->slug),
                                        'price' => $price_qty,
                                        'thumbnail' => $thumbnail
                                    ];
                                @endphp 
                                <p>{{$price_qty}}</p>
                                <a class="add_cart add_to_cart addToCartFromThumnail"data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} 
                                    data-product-sku={{ @$product->product->skus->first()->id }} data-base-price={{ @$product->product->skus->first()->selling_price }} 
                                    data-shipping-method=0
                                    data-product-id={{ $product->product->id }}
                                    data-stock_manage="{{$product->product->stock_manage}}"
                                    data-stock="{{@$product->product->skus->first()->product_stock}}"
                                    data-min_qty="{{$product->product->product->minimum_order_qty}}"
                                    data-prod_info = "{{json_encode($showData)}}"
                                    href="javascript:void(0)">{{__('common.add_to_cart')}}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="product_widget5 style3 bg-white">
                        @php
                            $thumbnail = showImage(@$product->giftcard->thumbnail_image);
                            $prod_url = route('frontend.gift-card.show',@$product->giftcard->sku);
                        @endphp
                        <div class="product_thumb_upper">
                            <a href="{{$prod_url}}" class="thumb">
                                <img src="{{$thumbnail}}" alt="{{textLimit(@$product->giftcard->name,28)}}" title="{{textLimit(@$product->giftcard->name,28)}}">
                            </a>
                            <div class="product_action">
                                <a data-bs-toggle="modal" data-bs-target="#theme_modal">
                                    <i class="ti-eye"></i>
                                </a>
                                <a class="removeWishlist" data-id="{{ $product->id }}">
                                    <i class="ti-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product__meta text-center">
                            <span class="product_banding ">{{__('amazy.no_brand')}}</span>
                            <a href="{{$prod_url}}">
                                <h4>{{textLimit(@$product->giftcard->name,28)}}</h4>
                            </a>
                            @php
                                $reviews = @$product->giftcard->reviews->where('status',1)->pluck('rating');
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
                            <div class="stars justify-content-center">
                                <!-- rating component -->
                                <x-rating :rating="$rating"/>
                                <!-- rating component -->
                            </div>

                            @php
                                $price_qty = getGiftcardwithDiscountPrice(@$product->giftcard);
                                $showData = [
                                    'name' => @$product->giftcard->name,
                                    'url' => $prod_url,
                                    'price' => $price_qty,
                                    'thumbnail' => $thumbnail
                                ];
                            @endphp
                            <div class="product_prise">
                                <p>{{$price_qty}}</p>
                                <a class="add_cart add_to_cart add_to_cart_gift_thumnail" data-prod_info = "{{json_encode($showData)}}" data-gift-card-id="{{ @$product->giftcard->id }}" data-seller="{{ App\Models\User::where('role_id', 1)->first()->id }}" data-base-price="@if(@$product->giftcard->hasDiscount()) {{selling_price(@$product->giftcard->selling_price, @$product->giftcard->discount_type, @$product->giftcard->discount)}} @else {{@$product->giftcard->selling_price}} @endif"  href="#">{{__('common.add_to_cart')}}</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        
    </div>
    @if($products->lastPage() > 1)
    <x-pagination-component :items="$products" type=""/>
    @endif
</div>