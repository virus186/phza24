
<div class="category_product_page">
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


    <div class="product_page_tittle d-flex justify-content-between grid_Title_page w-100">
        <div class="d-flex align-items-center">
          <p class="flex-fill">Showing @if($show_start == $show_end) {{$show_end}} @else {{$show_start}} - {{$show_end}} @endif out of total {{$total_number_of_items}} products</p>
          <p class="toggleFilter d-inline-block d-custom-none"><i class="ti-filter"></i></p>
        </div>
        <div class="short_by">
            <select name="paginate_by" class="getFilterUpdateByIndex" id="paginate_by">
                <option value="9" @if (isset($paginate) && $paginate == "9") selected @endif>9</option>
                <option value="12" @if (isset($paginate) && $paginate == "12") selected @endif>12</option>
                <option value="16" @if (isset($paginate) && $paginate == "16") selected @endif>16</option>
                <option value="25" @if (isset($paginate) && $paginate == "25") selected @endif>25</option>
                <option value="30" @if (isset($paginate) && $paginate == "30") selected @endif>30</option>
            </select>
        </div>
        <div class="short_by">
            <select name="sort_by" class="getFilterUpdateByIndex" id="product_short_list">
                <option value="new" @if (isset($sort_by) && $sort_by == "new") selected @endif>{{ __('common.new') }}</option>
                <option value="old" @if (isset($sort_by) && $sort_by == "old") selected @endif>{{ __('common.old') }}</option>
                <option value="alpha_asc" @if (isset($sort_by) && $sort_by == "alpha_asc") selected @endif>{{ __('defaultTheme.name_a_to_z') }}</option>
                <option value="alpha_desc" @if (isset($sort_by) && $sort_by == "alpha_desc") selected @endif>{{ __('defaultTheme.name_z_to_a') }}</option>
                <option value="low_to_high" @if (isset($sort_by) && $sort_by == "low_to_high") selected @endif>{{ __('defaultTheme.price_low_to_high') }}</option>
                <option value="high_to_low" @if (isset($sort_by) && $sort_by == "high_to_low") selected @endif>{{ __('defaultTheme.price_high_to_low') }}</option>
            </select>
        </div>
    </div>

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
				<div class="col-lg-4 col-sm-6 col-md-6 single_product_item">
					<div class="single_product_list product_tricker">
						<div class="product_img">
							<a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}" class="product_img_iner">
								<img @if ($product->thum_img != null) src="{{showImage($product->thum_img)}}" @else src="{{showImage(@$product->product->thumbnail_image_source)}}" @endif alt="{{@$product->product_name?@$product->product_name:@$product->product->product_name}}" class="img-fluid" />
							</a>
							<div class="socal_icon">
								<a href="" class="add_to_wishlist {{@$product->is_wishlist() == 1?'is_wishlist':''}}" id="wishlistbtn_{{$product->id}}" data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}"> <i class="ti-heart"></i> </a>
								<a href="" class="addToCompareFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }} data-product-id={{ $product->id }}> <i class="ti-exchange-vertical"></i> </a>
								<a class="addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
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
									> <i class="ti-bag"></i> </a>
							</div>
						</div>
						<div class="product_text">
							<h5>
								<a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}">@if ($product->product_name) {{ substr($product->product_name,0,28) }} @if(strlen($product->product_name) > 28)... @endif @else {{substr(@$product->product->product_name,0,28)}} @if(strlen(@$product->product->product_name) > 28)... @endif @endif</a>
							</h5>
							<div class="product_review_star d-flex justify-content-between align-items-center">
								<p>
									{{getProductDiscountedPrice($product)}}
								</p>

								<div class="review_star_icon">
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
							</div>
							<div class="product_review_count d-flex justify-content-between align-items-center">
								<span>
									@if(getProductwitoutDiscountPrice(@$product) != single_price(0))
									{{getProductwitoutDiscountPrice(@$product)}}
									@endif
								</span>
								<p>{{sprintf("%.2f",$rating)}}/5 ({{$total_review<10?'0':''}}{{$total_review}} {{ __('review.review') }})</p>
							</div>

							@if(@$product->hasDeal)
								@if(@$product->hasDeal->discount >0)
									<span class="price_off">

									@if(@$product->hasDeal->discount_type ==0)
										{{@$product->hasDeal->discount}} % off
									@else
									{{single_price(@$product->hasDeal->discount)}} off
									@endif
									</span>
								@endif
							@else
								@if(@$product->hasDiscount == 'yes')
									@if($product->discount > 0)
									<span class="price_off">

										@if($product->discount_type == 0)
										{{$product->discount}} % off
										@else
										{{single_price($product->discount)}} off
										@endif
									</span>
									@endif
								@endif
							@endif
						</div>

					</div>
				</div>
			@endforeach
		@else
		<div class="text-center no_product_found">
			<p>{{ __('defaultTheme.no_product_found') }}</p>
		</div>
		@endif
	</div>

  <input type="hidden" name="filterCatCol" class="filterCatCol" value="0">

  @if(count($products) > 0)
	<div class="col-lg-12">
		<x-pagination-component :items="$products" type=""/>
	</div>
  @endif

</div>
