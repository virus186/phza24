@php

    $adminColor = Modules\Appearance\Entities\AdminColor::where('is_active',1)->first();

    $popupContent = \Modules\FrontendCMS\Entities\SubscribeContent::findOrFail(2);

    $modal = false;
    if(Session::get('ip') == NULL){
    Session::put('ip',request()->ip());
    $modal = true;
    }
    $langs = app('langs');
    $currencies = app('currencies');

    $locale = app('general_setting')->language_code;
    $ship = app('general_setting')->country_name;
    if(\Session::has('locale')){
        $locale = \Session::get('locale');
    }

    if(auth()->check()){
        $locale = auth()->user()->lang_code;
    }
    $currency_code = getCurrencyCode();

    $carts = collect();
    $compares = 0;
    $wishlists = 0;
    if(auth()->check()){
        $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product',function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->whereHas('giftCard', function($query){
            return $query->where('status', 1);
        })->get();
        $compares = count(\App\Models\Compare::with('sellerProductSKU.product')->where('customer_id', auth()->user()->id)->whereHas('sellerProductSKU', function($query){
            return $query->where('status',1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->pluck('id'));
        $wishlists = count(\App\Models\Wishlist::where('user_id', auth()->user()->id)->pluck('id'));
    }else {
        $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product',function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->orWhere('product_type', 'gift_card')->where('session_id', session()->getId())->whereHas('giftCard', function($query){
            return $query->where('status', 1);
        })->get();

        if(\Session::has('compare')){
            $dataList = Session::get('compare');
            $collcets =  collect($dataList);

            $collcets =  $collcets->sortByDesc('product_type');
            $products = [];
            foreach($collcets as $collcet){
                $product = \Modules\Seller\Entities\SellerProductSKU::with('product')->where('id',$collcet['product_sku_id'])->whereHas('product', function($query){
                    return $query->activeSeller();
                })->pluck('id');
                if($product){
                    $products[] = $product;
                }
            }
            $compares = count($products);
        }

    }
    $items = 0;
    foreach($carts as $cart){
        $items += $cart->qty;
    }

    $regular_menus = Modules\Menu\Entities\Menu::with('elements.page','elements.childs','elements.childs.page')->where('menu_type', 'normal_menu')->where('menu_position','top_navbar')->whereIn('id',[1,2])->orderBy('id')->where('status', 1)->get();
    $topnavbar_left_menu = null;
    $topnavbar_right_menu = null;
    foreach ($regular_menus as $menu) {
        if($menu->name == 'Top Navbar left menu'){
            $topnavbar_left_menu = $menu;
        }
        elseif ($menu->name == 'Top Navbar right menu') {
            $topnavbar_right_menu = $menu;
        }
    }

    $top_bar = Modules\FrontendCMS\Entities\HomePageSection::where('section_name','top_bar')->first();
@endphp



@include('frontend.default.partials._header',[$popupContent,$compares])

    @section('content')
        @show
    <!-- jquery plugin here -->

    <!-- project estimate section -->
    @include('frontend.default.partials._newsletter')
    <!-- project estimate section end-->

@include('frontend.default.partials._footer')
