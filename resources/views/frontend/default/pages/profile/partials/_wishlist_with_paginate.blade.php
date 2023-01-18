

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

<div class="category_product_page">
<div class="product_page_tittle">
  <div class="row">
    <div class="col-lg-4 mb_20 ">
      <p class="text-lowercase">{{__('defaultTheme.showing')}} @if($show_start == $show_end) {{$show_end}} @else {{$show_start}} - {{$show_end}} @endif {{__('defaultTheme.out_of_total')}} {{$total_number_of_items}} {{__('common.products')}}</p>
    </div>
    <div class="col-lg-4 mb_20 ">
      <div class="short_by">
        <select name="paginate_by" class="getFilterUpdateByIndex w-100" id="paginate_by">
            <option value="6" @if (isset($paginate) && $paginate == "6") selected @endif>6</option>
            <option value="9" @if (isset($paginate) && $paginate == "9") selected @endif>9</option>
            <option value="12" @if (isset($paginate) && $paginate == "12") selected @endif>12</option>
            <option value="15" @if (isset($paginate) && $paginate == "15") selected @endif>15</option>
            <option value="30" @if (isset($paginate) && $paginate == "30") selected @endif>30</option>
        </select>
    </div>
    </div>
    <div class="col-lg-4 mb_20 ">
      <div class="short_by">
        <select name="sort_by" class="getFilterUpdateByIndex w-100" id="product_short_list">
            <option value="new" @if (isset($sort_by) && $sort_by == "new") selected @endif>{{__('common.new')}}</option>
            <option value="old" @if (isset($sort_by) && $sort_by == "old") selected @endif>{{__('common.old')}}</option>
            <option value="low_to_high" @if (isset($sort_by) && $sort_by == "low_to_high") selected @endif>{{__('common.price')}} ({{__('defaultTheme.low_to_high')}})</option>
            <option value="high_to_low" @if (isset($sort_by) && $sort_by == "high_to_low") selected @endif>{{__('common.price')}} ({{__('defaultTheme.high_to_low')}})</option>
        </select>
    </div>
    </div>
  </div>

</div>
<input type="hidden" name="filterCatCol" class="filterCatCol" value="0">
@if(count($products) > 0)
  <div class="row ">
    @foreach($products as $product)
      @if($product->type =='product')
        <div class="col-lg-4 col-sm-6 col-md-6 single_product_item">
          <div class="single_product_list product_tricker">
            <div class="product_img">
              <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug)}}" target="_blank" class="product_img_iner">
                <img src="@if(@$product->product->thum_img != null) {{showImage(@$product->product->thum_img)}} @else {{showImage(@$product->product->product->thumbnail_image_source)}} @endif" alt="{{@$product->product->product->product_name}}" class="img-fluid" />
              </a>
              <div class="socal_icon">
                <a href="" class="addToCompareFromThumnail" data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }} data-product-id={{ @$product->product->id }}> <i class="ti-exchange-vertical"></i> </a>
                <a href="" class="addToCartFromThumnail" data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }}
                    @if(@$product->product->hasDeal)
                        data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->hasDeal->discount_type,@$product->product->hasDeal->discount) }}
                    @else
                      @if(@$product->product->hasDiscount == 'yes')
                        data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->discount_type,@$product->product->discount) }}
                      @else
                        data-base-price={{ @$product->product->skus->first()->selling_price }}
                      @endif
                    @endif
                    data-shipping-method=0
                    data-product-id={{ $product->product->id }}
                    data-stock_manage="{{$product->product->stock_manage}}"
                    data-stock="{{@$product->product->skus->first()->product_stock}}"
                    data-min_qty="{{$product->product->product->minimum_order_qty}}"
                    > <i class="ti-bag"></i> </a>
                <a href="" class="removeWishlist" data-id="{{ $product->id }}"> <i class="ti-trash"></i> </a>
              </div>
            </div>
            <div class="product_text">
              <h5>
                <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug)}}" target="_blank">@if (@$product->product->product_name) {{ \Illuminate\Support\Str::limit(@$product->product->product_name, 28, $end='...') }}  @else {{ \Illuminate\Support\Str::limit(@$product->product->product->product_name, 28, $end='...') }} @endif</a>
              </h5>
              <div class="product_review_star d-flex justify-content-between align-items-center">
                <p>
                    {{getProductDiscountedPrice(@$product->product)}}
                </p>
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
                {{-- rating component --}}
                <x-rating :rating="$rating"/>
                {{-- rating component --}}
              </div>
              <div class="product_review_count d-flex justify-content-between align-items-center">
                @if(getProductwitoutDiscountPrice(@$product->product) != single_price(0)) 
                    <span>{{getProductwitoutDiscountPrice(@$product->product)}}</span>
                @endif

                <p>{{sprintf("%.2f",$rating)}}/5 ({{$total_review<10?'0':''}}{{$total_review}} {{__('defaultTheme.review')}})</p>
              </div>

              @if($product->product->hasDeal)
                @if($product->product->hasDeal->discount > 0)
                  <span class="price_off">

                    @if($product->product->hasDeal->discount_type == 0)
                      {{$product->product->hasDeal->discount}} % {{__('common.off')}}
                    @else
                    {{single_price($product->product->hasDeal->discount)}} {{__('common.off')}}
                    @endif


                  </span>
                @endif
              @else
                @if(@$product->product->hasDiscount == 'yes')
                  <span class="price_off">
                    @if($product->product->product->discount_type == 0)
                      {{$product->product->product->discount}} % {{__('common.off')}}
                    @else
                    {{single_price($product->product->product->discount)}} {{__('common.off')}}
                    @endif


                  </span>
                @endif
              @endif


            </div>

          </div>
      </div>
      @else

        <div class="col-lg-4 col-sm-6 col-md-6 single_product_item">
          <div class="single_product_list product_tricker">
              <div class="product_img">
                  <a href="{{route('frontend.gift-card.show',@$product->giftcard->sku)}}" class="product_img_iner">
                      <img src="{{showImage(@$product->giftcard->thumbnail_image)}}" alt="#" class="img-fluid">
                  </a>
                  <div class="socal_icon">
                      <a href="" class="add_to_cart_gift_thumnail" data-gift-card-id="{{ @$product->giftcard->id }}" data-seller="{{ App\Models\User::where('role_id', 1)->first()->id }}" data-base-price="@if(@$product->giftcard->hasDiscount()) {{selling_price(@$product->giftcard->selling_price, @$product->giftcard->discount_type, @$product->giftcard->discount)}} @else {{@$product->giftcard->selling_price}} @endif"> <i class="ti-bag"></i> </a>
                      <a href="" class="removeWishlist" data-id="{{ $product->id }}"> <i class="ti-trash"></i> </a>
                  </div>
              </div>
              <div class="product_text">
                  <h5>
                      <a href="{{route('frontend.gift-card.show',@$product->giftcard->sku)}}">{{@$product->giftcard->name}}</a>
                  </h5>
                  <div class="product_review_star d-flex justify-content-between align-items-center">
                        @if(@$product->giftcard->hasDiscount())
                            <p>{{single_price(selling_price(@$product->giftcard->selling_price, @$product->giftcard->discount_type, @$product->giftcard->discount))}}</p>
                        @else
                            <p>{{single_price(@$product->giftcard->selling_price)}}</p>
                        @endif

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
                        {{-- rating component --}}
                        <x-rating :rating="$rating"/>
                        {{-- rating component --}}
                  </div>
                  <div class="product_review_count d-flex justify-content-between align-items-center">
                      <span>
                          @if(@$product->giftcard->hasDiscount())
                              @if(@$product->giftcard->discount > 0)
                              {{single_price(@$product->giftcard->selling_price)}}
                              @endif
                          @endif
                      </span>
                      <p>{{sprintf("%.2f",$rating)}}/5 ({{$total_review<10?'0':''}}{{$total_review}} Review)</p>
                  </div>
              </div>
          </div>
      </div>


      @endif
    @endforeach

</div>

  <div class="col-lg-12">
    <x-pagination-component :items="$products" type=""/>
  </div>
@else
<div class="row mt-20">
  <div class="col-lg-12 text-center">
    <p class="mt-200">{{__('defaultTheme.no_product_in_wishlist')}}</p>
  </div>
</div>
@endif

</div>
