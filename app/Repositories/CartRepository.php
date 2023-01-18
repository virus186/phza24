<?php
namespace App\Repositories;

use App\Models\Cart;
use App\Traits\GoogleAnalytics4;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Shipping\Entities\ShippingMethod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Modules\GiftCard\Entities\GiftCard;

class CartRepository{

    use GoogleAnalytics4;
    protected $cart;

    public function __construct(Cart $cart){
        $this->cart = $cart;
    }

    public function store($data){

        $is_out_of_stock = 0;
        if(isset($data['is_buy_now']) && $data['is_buy_now'] == 'yes'){
            $is_buy_now = 1;
        }else{
            $is_buy_now = 0;
        }


        if(auth()->check()){
            $product = $this->cart::where('user_id',auth()->id())->where('product_id',$data['product_id'])->where('seller_id', $data['seller_id'])->where('product_type',$data['type'])->first();
        }else{
            $product = $this->cart::where('session_id',session()->getId())->where('product_id',$data['product_id'])->where('seller_id', $data['seller_id'])->where('product_type',$data['type'])->first();
        }
        $price = 0;
        if($data['type'] == 'product'){

            if (isModuleActive('WholeSale')){
                $sku = SellerProductSKU::with('product', 'wholeSalePrices')->find($data['product_id']);
                if ($sku['wholeSalePrices']){
                    foreach ($sku['wholeSalePrices'] as $w_sale_p){
                        if ( ($w_sale_p->min_qty<=$data['qty']) && ($w_sale_p->max_qty>=$data['qty']) ){
                            $sku->selling_price = $w_sale_p->selling_price;
                        }
                        elseif ($w_sale_p->max_qty < $data['qty']){
                            $sku->selling_price = $sku->selling_price;
                        }
                    }
                }

            }else{
                $sku = SellerProductSKU::with('product')->find($data['product_id']);
            }

            //$sku = SellerProductSKU::with('product')->find($data['product_id']);

            if($sku->product->hasDeal){
                $price = selling_price(@$sku->selling_price,@$sku->product->hasDeal->discount_type,@$sku->product->hasDeal->discount);
            }else{
                if($sku->product->hasDiscount == 'yes'){
                    $price = selling_price(@$sku->selling_price,@$sku->product->discount_type,@$sku->product->discount);
                }else{
                    $price = @$sku->selling_price;
                }
            }

        }elseif($data['type'] == 'gift_card'){
            $sku = GiftCard::find($data['product_id']);
            if($sku->hasDiscount()){
                $price = selling_price($sku->selling_price, $sku->discount_type, $sku->discount);
            }else{
                $price = $sku->selling_price;
            }
        }
        $total_price = $price*$data['qty'];

        if($data['type'] == 'product' && $product){
            if($sku->product_stock <= $sku->product->product->minimum_order_qty && $sku->product->stock_manage == 1){
                $is_out_of_stock = 1;
            }
        }

        if($is_out_of_stock == 0 && $sku){
            if($product){
                if($is_buy_now){
                    $product->delete();
                    $user_id = null;
                    $session_id = null;
                    if(auth()->check()){
                        $user_id = auth()->id();
                    }else{
                        $session_id = session()->getId();
                    }
                    $this->cart::create([
                        'user_id' => $user_id,
                        'session_id' => $session_id,
                        'product_type' => ($data['type'] == 'gift_card') ? 'gift_card' : 'product',
                        'product_id' => $data['product_id'],
                        'price' => $price,
                        'qty' => $data['qty'],
                        'total_price' => $total_price,
                        'seller_id' => $data['seller_id'],
                        'shipping_method_id' => $data['shipping_method_id'],
                        'sku' => null,
                        'is_select' => 1,
                        'is_buy_now' => $is_buy_now
                    ]);
                }else{
                    $product->update([
                        'qty' => $product->qty+$data['qty'],
                        'total_price' => $product->total_price + $total_price
                    ]);
                }
            }else{
                $user_id = null;
                $session_id = null;
                if(auth()->check()){
                    $user_id = auth()->id();
                }else{
                    $session_id = session()->getId();
                }
                $this->cart::create([
                    'user_id' => $user_id,
                    'session_id' => $session_id,
                    'product_type' => ($data['type'] == 'gift_card') ? 'gift_card' : 'product',
                    'product_id' => $data['product_id'],
                    'price' => $price,
                    'qty' => $data['qty'],
                    'total_price' => $total_price,
                    'seller_id' => $data['seller_id'],
                    'shipping_method_id' => $data['shipping_method_id'],
                    'sku' => null,
                    'is_select' => 1,
                    'is_buy_now' => $is_buy_now
                ]);
                //ga4
                if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
                    $e_productName = 'Product';
                    $e_sku = 'sku';
                    if($data['type'] == 'product'){
                        $product = SellerProductSKU::find($data['product_id']);
                        if($product){
                            $e_productName = $product->product->product_name;
                            $e_sku = $product->sku->sku;
                        }
                    }else{
                        $product = GiftCard::find($data['product_id']);
                        if($product){
                            $e_productName = $product->name;
                            $e_sku = $product->sku;

                        }
                    }
                    $eData = [
                        'name' => 'add_to_cart',
                        'params' => [
                            "currency" => currencyCode(),
                            "value"=> 1,
                            "items" => [
                                [
                                    "item_id"=> $e_sku,
                                    "item_name"=> $e_productName,
                                    "currency"=> currencyCode(),
                                    "price"=> $price
                                ]
                            ],
                        ],
                    ];
                    $this->postEvent($eData);
                }
                //end ga4
            }
        }else{
            return 'out_of_stock';
        }

    }

    public function update($data){
        if($data['cart_id']){
            foreach($data['cart_id'] as $key => $id){
                $cart = Cart::where('id', $id)->first();
                $price = $cart->price;
                $qty = $data['qty'][$key];
                if(isModuleActive('WholeSale') && $cart->product_type == 'product'){
                    $sku = $cart->product;
                    if(@$sku->product->hasDeal){
                        $discount_type = @$sku->product->hasDeal->discount_type;
                        $discount = @$sku->product->hasDeal->discount;
                    }else{
                        $discount_type = @$sku->product->discount_type;
                        $discount = @$sku->product->discount;
                    }

                    if($sku && $sku->wholeSalePrices->count()){
                        foreach($sku->wholeSalePrices as $wholesale_price){
                            if($wholesale_price->min_qty <= $qty && $wholesale_price->max_qty >= $qty){
                                $price = selling_price($wholesale_price->selling_price, @$discount_type,@$discount);
                            }
                            elseif($wholesale_price->max_qty < $qty){
                                $price = selling_price($wholesale_price->selling_price, $discount,@$discount_type);
                            }
                        }
                    }
                }
                $cart->update([
                    'qty' => $data['qty'][$key],
                    'total_price' => $price * $data['qty'][$key],
                    'price' => $price
                ]);
            }
            return true;
        }
        return false;
    }

    public function updateCartShippingInfo($data){
        if (auth()->check()) {
            $product =  $this->cart::findOrFail($data['cartId']);
            $product->update([
                'shipping_method_id' => $data['shipping_method_id']
            ]);
        }else {
            if(Session::has('cart')){
                $cart = session()->get('cart', collect([]));
                $cart = $cart->map(function ($object, $key) use ($data) {
                    if($object['cart_id'] == $data['cartId']){
                        $object['shipping_method_id'] = intval($data['shipping_method_id']);
                    }
                    return $object;
                });
                Session::put('cart', $cart);
            }
        }
    }

    public function getCartData(){
        $cart_ids =[];
        if(auth()->check()){
            $cart_ids = $this->cart::where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product', function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->where('status', 1)->activeSeller();
                });
            })->orWhere('user_id',auth()->user()->id)->where('product_type', 'gift_card')->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->pluck('id')->toArray();
        }else{
            $cart_ids = $this->cart::where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product', function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->where('status', 1)->activeSeller();
                });
            })->orWhere('session_id',session()->getId())->where('product_type', 'gift_card')->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->pluck('id')->toArray();
        }
        $query = $this->cart::with('product.product')->whereIn('id',$cart_ids)->where('is_select', 1)->get();
        $cartData = $query->groupBy('seller_id');

        $recs = new \Illuminate\Database\Eloquent\Collection($query);

//        $grouped = $recs->groupBy('seller_id')->transform(function($item, $k) {
//            return $item->groupBy('shipping_method_id');
//        });

        $grouped = $recs->groupBy('seller_id');

        $shipping_charge = 0;
        $method_shipping_cost = 0;
        $additional_charge = 0;
        foreach($grouped as $key => $item){
//            foreach($group as $key=> $item){
                //  $method_shipping_cost += $item[0]->shippingMethod->cost;
                 foreach($item as $key => $data){
                    if($data->product_type != "gift_card" && $data->product->sku->additional_shipping > 0){
                        $additional_charge +=  $data->product->sku->additional_shipping;
                    }
                 }
//            }

        }
        $shipping_charge = $method_shipping_cost + $additional_charge;

        return [
            'shipping_charge' => $shipping_charge,
            'cartData' => $cartData
        ];

    }

    function group_by($key, $data) {
        $result = array();
        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }


    public function updateQty($data){
        $cart =  $this->cart::find($data['id']);

        // $product->update([
        //     'qty' => $data['qty'],
        //     'total_price' => $product->price *$data['qty']
        // ]);
        // return 1;

        if($cart){
            $price = $cart->price;
            $qty = $data['qty'];
            if(isModuleActive('WholeSale') && $cart->product_type == 'product'){
                $sku = $cart->product;
                if(@$sku->product->hasDeal){
                    $discount_type = @$sku->product->hasDeal->discount_type;
                    $discount = @$sku->product->hasDeal->discount;
                }else{
                    $discount_type = @$sku->product->discount_type;
                    $discount = @$sku->product->discount;
                }
                $price = 0;
                if($sku && $sku->wholeSalePrices->count()){
                    foreach($sku->wholeSalePrices as $wholesale_price){
                        if($wholesale_price->min_qty <= $qty && $wholesale_price->max_qty >= $qty){
                            $price = selling_price($wholesale_price->selling_price, $discount_type,$discount);
                        }
                        elseif($wholesale_price->max_qty < $qty){
                            $price = selling_price($wholesale_price->selling_price,$discount_type,$discount);
                        }
                    }
                }
                if($price == 0){
                    $price = selling_price($sku->selling_price,$discount_type, $discount);
                }
            }
            $cart->update([
                'qty' => $qty,
                'total_price' => $price *$qty,
                'price' => $price
            ]);
            return 1;
        }
    }
    public function updateSidebarQty($data){

        $cart =  $this->cart::find($data['id']);
        if($cart){
            $price = $cart->price;
            $qty = $data['qty'];
            if(isModuleActive('WholeSale') && $cart->product_type == 'product'){
                $sku = $cart->product;
                if(@$sku->product->hasDeal){
                    $discount_type = @$sku->product->hasDeal->discount_type;
                    $discount = @$sku->product->hasDeal->discount;
                }else{
                    $discount_type = @$sku->product->discount_type;
                    $discount = @$sku->product->discount;
                }
                $price = 0;
                if($sku && $sku->wholeSalePrices->count()){
                    foreach($sku->wholeSalePrices as $wholesale_price){
                        if($wholesale_price->min_qty <= $qty && $wholesale_price->max_qty >= $qty){
                            $price = selling_price($wholesale_price->selling_price, $discount_type,$discount);
                        }
                        elseif($wholesale_price->max_qty < $qty){
                            $price = selling_price($wholesale_price->selling_price,$discount_type,$discount);
                        }
                    }
                }
                if($price == 0){
                    $price = selling_price($sku->selling_price,$discount_type, $discount);
                }
            }
            $cart->update([
                'qty' => $qty,
                'total_price' => $price *$qty,
                'price' => $price
            ]);
            return 1;
        }
        return 0;
    }

    public function selectAll($data){
        $carts = [];
        if(auth()->check()){
            $carts = $this->cart::where('user_id',auth()->user()->id)->get();

        }else{
            $carts = $this->cart::where('session_id',session()->getId())->get();
        }

        foreach($carts as $key => $cart){
            $cart->update([
                'is_select' => intval($data['checked'])
            ]);
        }

        return 1;
    }
    public function selectAllSeller($data){
        $carts = [];
        if(auth()->check()){
            $carts = $this->cart::where('user_id',auth()->user()->id)->get();

        }else{
            $carts = $this->cart::where('session_id',session()->getId())->get();
        }
        foreach($carts as $key => $cart){
            if($cart->seller_id == $data['seller_id']){
                $cart->update([
                    'is_select' => intval($data['checked'])
                ]);
            }
        }
        return 1;
    }
    public function selectItem($data){
        $cart = null;
        if(auth()->check()){
            $cart = $this->cart::where('user_id',auth()->user()->id)->where('product_id',$data['product_id'])->where('product_type', $data['product_type'])->firstorFail();
        }else{
            $cart = $this->cart::where('session_id',session()->getId())->where('product_id',$data['product_id'])->where('product_type', $data['product_type'])->firstorFail();
        }
        if($cart){
            $cart->update([
                'is_select' => intval($data['checked'])
            ]);
        }
        return 1;
    }

    public function deleteCartProduct($data){

        $cartItem = $this->cart::findOrFail($data['id']);

        //ga4
        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $e_productName = 'Product';
            $e_sku = 'sku';
            if($cartItem['product_type'] == 'product'){
                $product = SellerProductSKU::find($cartItem['product_id']);
                if($product){
                    $e_productName = $product->product->product_name;
                    $e_sku = $product->sku->sku;
                }
            }else{
                $product = GiftCard::find($cartItem['product_id']);
                if($product){
                    $e_productName = $product->name;
                    $e_sku = $product->sku;

                }
            }
            $eData = [
                'name' => 'remove_from_cart',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 1,
                    "items" => [
                        [
                            "item_id"=> $e_sku,
                            "item_name"=> $e_productName,
                            "currency"=> currencyCode(),
                            "price"=> $cartItem['price']
                        ]
                    ],
                ],
            ];
            $this->postEvent($eData);
        }
        //end ga4

        return $cartItem->delete();
    }
    public function deleteAll(){
        if(auth()->check()){
            $carts = $this->cart::where('user_id',auth()->user()->id)->get();

        }else{
            $carts = $this->cart::where('session_id',session()->getId())->get();
        }
        foreach($carts as $cart){
            $cart->delete();
        }
        return 1;
    }

    public function getFreeShipping(){
        return ShippingMethod::where('request_by_user', 1)->where('id', '>', 1)->where('cost', 0)->orderBy('minimum_shopping')->first();
    }
}
