<div class="row ">
    @foreach($allCouponCategoryProducts as $key => $product)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product_widget5 mb_25">
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
                                    -{{@$product->hasDeal->discount}}%
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
                                    -{{@$product->discount}}%
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
                        <h4>@if ($product->product_name) {{ textLimit(@$product->product_name, 56) }} @else {{ textLimit(@$product->product->product_name, 56) }} @endif</h4>
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

</div>

@if($allCouponCategoryProducts->lastPage() > 1)
    <x-pagination-component :items="$allCouponCategoryProducts" type="all_coupon_category_page_item"/>
@endif