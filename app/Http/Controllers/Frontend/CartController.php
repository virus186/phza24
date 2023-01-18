<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\GoogleAnalytics4;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Modules\GiftCard\Entities\GiftCard;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\UserActivityLog\Traits\LogActivity;

class CartController extends Controller
{
    use GoogleAnalytics4;
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware('maintenance_mode');

    }

    public function index()
    {
        try{
            $Data = $this->cartService->getCartData();
            $cartData = $Data['cartData'];

            //ga4
            if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
                $e_productName = 'Product';
                $e_sku = 'sku';
                $e_items = [];
                foreach ($cartData as $cD){
                    foreach ($cD as $c){
                        if($c['product_type'] == 'product'){
                            $e_product = SellerProductSKU::find($c['product_id']);
                            if($e_product){
                                $e_productName = $e_product->product->product_name;
                                $e_sku = $e_product->sku->sku;
                            }
                        }else{
                            $e_product = GiftCard::find($c['product_id']);
                            if($e_product){
                                $e_productName = $e_product->name;
                                $e_sku = $e_product->sku;

                            }
                        }
                        $e_items[]=[
                            "item_id"=> $e_sku,
                            "item_name"=> $e_productName,
                            "currency"=> currencyCode(),
                            "price"=> $c['price']
                        ];
                    }
                }
                $eData = [
                    'name' => 'view_cart',
                    'params' => [
                        "currency" => currencyCode(),
                        "value"=> 1,
                        "items" => $e_items,
                    ],
                ];
                $this->postEvent($eData);
            }
            //end ga4

            $shipping_costs = $Data['shipping_charge'];
            $free_shipping = $this->cartService->getFreeShipping();
            if(isModuleActive('MultiVendor') && app('general_setting')->seller_wise_payment){
                return view(theme('pages.cart_seller_to_seller'),compact('cartData','shipping_costs','free_shipping'));
            }
            return view(theme('pages.cart'),compact('cartData','shipping_costs','free_shipping'));
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());

            return $e;
        }
    }

    public function store(Request $request){
        $result = $this->cartService->store($request->except('_token'));
        if($result == 'out_of_stock'){
            return 'out_of_stock';
        }else{
            $carts = collect();
            if(auth()->check()){
                $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product',function($query){
                    return $query->where('status', 1)->whereHas('product', function($q){
                        return $q->activeSeller();
                    });
                })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->whereHas('giftCard', function($query){
                    return $query->where('status', 1);
                })->get();

            }else {
                $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product',function($query){
                    return $query->where('status', 1)->whereHas('product', function($q){
                        return $q->activeSeller();
                    });
                })->orWhere('product_type', 'gift_card')->where('session_id', session()->getId())->whereHas('giftCard', function($query){
                    return $query->where('status', 1);
                })->get();
            }
            $items = 0;
            foreach($carts as $cart){
                $items += $cart->qty;
            }
            LogActivity::successLog('cart store successful.');
            return response()->json([
                'cart_details_submenu' => (string) view(theme('partials._cart_details_submenu'),compact('carts','items')),
                'count_bottom' => $items
            ]);
        }

    }

    public function update(Request $request){
        $this->cartService->update($request->except('_token'));
        return $this->reloadWithData();
    }

    public function updateCartShippingInfo(Request $request)
    {
        try {
            $this->cartService->updateCartShippingInfo($request->except('_token'));
            LogActivity::successLog('update cart info successful.');
            return $this->reloadWithData();
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());

            return $e;
        }

    }


    public function updateQty(Request $request)
    {
        $this->cartService->updateQty($request->except('_token'));
        LogActivity::successLog('update cart qty successful.');
        return $this->reloadWithData();
    }

    public function sidebarUpdateQty(Request $request){
        $this->cartService->updateSidebarQty($request->except('_token'));
        LogActivity::successLog('update cart qty successful.');
        return $this->reloadWithData();
    }

    public function selectAll(Request $request)
    {
        $this->cartService->selectAll($request->except('_token'));
        $Data = $this->cartService->getCartData();
        $cartData = $Data['cartData'];
        $shipping_costs = $Data['shipping_charge'];
        return view(theme('partials._cart_details'),compact('cartData','shipping_costs'));
    }
    public function selectAllSeller(Request $request)
    {

        $this->cartService->selectAllSeller($request->except('_token'));
        $Data = $this->cartService->getCartData();
        $cartData = $Data['cartData'];
        $shipping_costs = $Data['shipping_charge'];
        return view(theme('partials._cart_details'),compact('cartData','shipping_costs'));
    }
    public function selectItem(Request $request)
    {
        $this->cartService->selectItem($request->except('_token'));
        $Data = $this->cartService->getCartData();
        $cartData = $Data['cartData'];
        $shipping_costs = $Data['shipping_charge'];
        return view(theme('partials._cart_details'),compact('cartData','shipping_costs'));

    }

    public function destroy(Request $request)
    {

        $this->cartService->deleteCartProduct($request->except('_token'));
        LogActivity::successLog('delete cart successful.');

        return $this->reloadWithData();

    }
    public function destroyAll(Request $request)
    {

        $this->cartService->deleteAll();
        LogActivity::successLog('delete all cart successful.');

        return $this->reloadWithData();
    }

    private function reloadWithData(){

        $Data = $this->cartService->getCartData();
        $cartData = $Data['cartData'];
        $shipping_costs = $Data['shipping_charge'];
        $carts = collect();

        if(auth()->check()){
            $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product',function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();
        }else {
            $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product',function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('session_id', session()->getId())->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();
        }
        $items = 0;
        foreach($carts as $cart){
            $items += $cart->qty;
        }
        $free_shipping = $this->cartService->getFreeShipping();
        if(isModuleActive('MultiVendor') && app('general_setting')->seller_wise_payment){
            $main_cart = (string)view(theme('partials._cart_details_seller_to_seller'),compact('cartData','shipping_costs','free_shipping'));
        }else{
            $main_cart = (string)view(theme('partials._cart_details'),compact('cartData','shipping_costs','free_shipping'));
        }
        
        return response()->json([
            'MainCart' =>  $main_cart,
            'SubmenuCart' =>  (string)view(theme('partials._cart_details_submenu'),compact('carts','items')),
            'count_bottom' => $items
        ]);
    }

}
