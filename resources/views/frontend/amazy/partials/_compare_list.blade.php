<div class="col-xl-10">
    <div class="compare_title_div">
        <h3 class="fs-4 fw-bold mb_30">{{ __('defaultTheme.product_compare') }}</h3>
        @if(count($products) > 0)
            <a href="#" class="reset_compare_text reset_compare">{{ __('defaultTheme.reset_compare') }}</a>
        @endif
    </div>
    <div class="comparing_box_area mb_30">
        @if(count($products) > 0)
        <div class="compare_product_descList">
            
            <div class="single_product_list product_tricker compare_product">
                
                <ul class="comparison_lists style2">
                    <li>
                        {{__('common.name')}}
                    </li>
                    <li>
                        {{__('defaultTheme.sku')}}
                    </li>
                    @if(isModuleActive('MultiVendor'))
                    <li>
                        {{__('common.seller')}}
                    </li>
                    @endif
                    @php
                        $data = $products[0];
                        $total_key = 2;
                        $attribute_list = [];
                    @endphp
                    @if(@$data->product->product->product_type == 2)
                        @foreach(@$data->product_variations as $key => $combination)
                        @php
                            $total_key += 1;
                            $attribute_list[] = @$combination->attribute->name;
                        @endphp
                            <li>{{@$combination->attribute->name}}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="compare_product_carousel">
            <div class="compare_product_active owl-carousel">
                @foreach($products as $key => $sellerProductSKU)
                    <!-- single item  -->
                    <div class="single_product_list product_tricker compare_product">
                        <div class="compare_product_inner">
                            <div class="product_widget5 border-0">
                                <div class="product_thumb_upper">
                                    @php
                                        if(@$sellerProductSKU->product->product->product_type == 1){
                                            if(@$sellerProductSKU->product->thum_img != null){
                                                $thumbnail = showImage(@$sellerProductSKU->product->thum_img);
                                            }else{
                                                $thumbnail = showImage(@$sellerProductSKU->product->product->thumbnail_image_source);
                                            }
                                        }else{
                                            $thumbnail = showImage(@$sellerProductSKU->sku->variant_image?@$sellerProductSKU->sku->variant_image:@$sellerProductSKU->product->product->thumbnail_image_source);
                                        }

                                        $price_qty = getProductDiscountedPrice(@$sellerProductSKU->product);
                                        $showData = [
                                            'name' => @$sellerProductSKU->product->product_name,
                                            'url' => singleProductURL(@$sellerProductSKU->product->seller->slug, @$sellerProductSKU->product->slug),
                                            'price' => $price_qty,
                                            'thumbnail' => $thumbnail
                                        ];
                                    @endphp
                                    <a href="{{singleProductURL(@$sellerProductSKU->product->seller->slug, @$sellerProductSKU->product->slug)}}" class="thumb">
                                        <img src="{{$thumbnail}}" alt="{{@$sellerProductSKU->product->product_name}}" title="{{@$sellerProductSKU->product->product_name}}">
                                    </a>
                                    <div class="product_action">
                                        <a href="" class="add_to_wishlist {{$sellerProductSKU->product->is_wishlist() == 1?'is_wishlist':''}}" data-product_id="{{$sellerProductSKU->product->id}}" data-seller_id="{{$sellerProductSKU->product->user_id}}">
                                            <i class="ti-heart"></i>
                                        </a>
                                        <a href="" class="remove_from_compare" data-id="{{$sellerProductSKU->id}}">
                                            <i class="ti-trash"></i>
                                        </a>
                                    </div>
                                    
                                    @if($sellerProductSKU->product->hasDeal)
                                        @if($sellerProductSKU->product->hasDeal->discount > 0)
                                            <span class="badge_1 text-nowrap">
                                                @if($sellerProductSKU->product->hasDeal->discount_type == 0)
                                                    {{getNumberTranslate($sellerProductSKU->product->hasDeal->discount)}} % {{__('common.off')}}
                                                @else
                                                    {{single_price($sellerProductSKU->product->hasDeal->discount)}} {{__('common.off')}}
                                                @endif
                                            </span>
                                        @endif
                                    @else
                                        @if(@$sellerProductSKU->product->hasDiscount == 'yes')
                                            <span class="badge_1 text-nowrap">
                                                @if($sellerProductSKU->product->product->discount_type == 0)
                                                    {{getNumberTranslate($sellerProductSKU->product->product->discount)}} % {{__('common.off')}}
                                                @else
                                                    {{single_price($sellerProductSKU->product->product->discount)}} {{__('common.off')}}
                                                @endif
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="product__meta text-center">
                                    <span class="product_banding ">
                                        @if(@$sellerProductSKU->product->product->brand->name)
                                            {{@$sellerProductSKU->product->product->brand->name}}
                                        @else
                                            {{__('amazy.no_brand')}}
                                        @endif
                                    </span>
                                    <a href="{{singleProductURL(@$sellerProductSKU->product->seller->slug, @$sellerProductSKU->product->slug)}}">
                                        <h4 class="text-nowrap">@if(@$sellerProductSKU->product->product_name) {{textLimit(@$sellerProductSKU->product->product_name,25)}} @else {{textLimit(@$sellerProductSKU->product->product->product_name,25)}} @endif</h4>
                                    </a>
                                    @php
                                        $reviews = @$sellerProductSKU->product->reviews->where('status',1)->pluck('rating');
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
                                    <div class="product_prise">
                                        <p>
                                            <span>
                                                @if($sellerProductSKU->product->hasDeal)
                                                    @if($sellerProductSKU->product->hasDeal->discount > 0)
                                                        {{single_price($sellerProductSKU->selling_price)}}
                                                    @endif
                                                @else
                                                    @if($sellerProductSKU->product->hasDiscount == 'yes')
                                                        {{single_price($sellerProductSKU->selling_price)}}
                                                    @endif
                                                @endif
                                             </span>  
                                            @if(@$sellerProductSKU->product->hasDeal)
                                                {{single_price(selling_price(@$sellerProductSKU->selling_price,@$sellerProductSKU->product->hasDeal->discount_type,@$sellerProductSKU->product->hasDeal->discount))}}

                                            @else
                                                @if(@$sellerProductSKU->product->hasDiscount == 'yes')
                                                    {{single_price(selling_price(@$sellerProductSKU->selling_price,@$sellerProductSKU->product->discount_type,@$sellerProductSKU->product->discount))}}
                                                @else
                                                    {{single_price(@$sellerProductSKU->selling_price)}}
                                                @endif
                                            @endif
                                        </p>
                                        @php
                                            $price = 0;
                                            $shipping_method = 0;

                                            if(@$sellerProductSKU->product->hasDeal){
                                                $price = selling_price(@$sellerProductSKU->selling_price,@$sellerProductSKU->product->hasDeal->discount_type,@$sellerProductSKU->product->hasDeal->discount);
                                            }
                                            else{
                                                if($sellerProductSKU->product->hasDiscount == 'yes'){
                                                    $price = selling_price(@$sellerProductSKU->selling_price,@$sellerProductSKU->product->discount_type,@$sellerProductSKU->product->discount);
                                                }else{
                                                    $price = @$sellerProductSKU->selling_price;
                                                }
                                            }
                                        @endphp
                                        <a href="" class="add_cart add_to_cart addToCart" data-product_sku_id="{{$sellerProductSKU->id}}" data-seller_id="{{@$sellerProductSKU->product->user_id}}" data-shipping_method="{{$shipping_method}}" data-price="{{$price}}" data-prod_info="{{ json_encode($showData) }}">{{__('defaultTheme.add_to_cart')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="comparison_lists">
                            <li>
                                {{textLimit($sellerProductSKU->product->product_name,35)}}
                            </li>
                            <li>
                                {{@$sellerProductSKU->sku->sku??'-'}}
                            </li>
                            @if(isModuleActive('MultiVendor'))
                                <li>
                                    @if($sellerProductSKU->product->seller->role->type == 'seller')
                                        @if (@$sellerProductSKU->product->seller->SellerAccount->seller_shop_display_name)
                                            {{ @$sellerProductSKU->product->seller->SellerAccount->seller_shop_display_name }}
                                        @else
                                            {{$sellerProductSKU->product->seller->first_name .' '.$sellerProductSKU->product->seller->last_name}}
                                        @endif
                                    @else
                                        {{ app('general_setting')->company_name }}
                                    @endif
                                </li>
                            @endif
                            
                            @php
                                $key_count = 2;
                            @endphp
                            @if(@$sellerProductSKU->product->product->product_type == 2)
                                @foreach(@$sellerProductSKU->product_variations as $key => $combination)
                                    @php
                                        $key_count += 1;
                                    @endphp
                                    @if($attribute_list[$key] == @$combination->attribute->name)
                                        @if(@$combination->attribute->name == 'Color')
                                            <li>{{@$combination->attribute_value->color->name}}</li>
                                        @else
                                            <li>{{@$combination->attribute_value->value}}</li>
                                        @endif
                                    @else
                                        <li>-</li>
                                    @endif

                                @endforeach
                            @endif

                            @if($total_key > $key_count)
                                @for($key_count; $key_count < $total_key; $key_count++)
                                    <li>-</li>
                                @endfor
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
        @else
            <h4 class="test-center compare_empty">{{ __('defaultTheme.compare_list_is_empty') }}</h4>
        @endif
    </div>
</div>