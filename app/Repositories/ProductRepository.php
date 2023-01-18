<?php

namespace App\Repositories;

use App\Http\Resources\SearchProductResource;
use App\Http\Resources\SearchSellerResource;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SearchTerm;
use App\Models\RecentViewProduct;
use Modules\Seller\Entities\SellerProduct;
use Modules\Product\Entities\Product;
use Modules\GiftCard\Entities\GiftCard;
use Modules\Product\Entities\Category;
use Carbon\Carbon;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\ProductTag;
use Modules\Review\Entities\ProductReview;
use Modules\Setup\Entities\Tag;
use Modules\Shipping\Entities\PickupLocation;
use Modules\Shipping\Entities\ShippingMethod;

class ProductRepository
{

    protected $product;

    public function __construct(SellerProduct $product)
    {
        $this->product = $product;
    }

    public function getProductByID($id)
    {
        return $this->product::with('product', 'skus')->where('id', $id)->firstOrFail();
    }

    public function recentViewedProducts($product_ids)
    {
        return $this->product::with('product', 'skus')->whereRaw("id in ('". implode("','",$product_ids)."')")->latest()->get();
    }

    public function getProductBySlug($slug)
    {
        return $this->product::with('product', 'skus')->where('slug', $slug)->firstOrFail();
    }

    public function getActiveSellerProductBySlug($slug, $seller_slug = null)
    {
        if(isModuleActive('MultiVendor')){
            return $this->product::where('slug', $slug)->with('product.tags','related_sales.related_seller_products.seller','cross_sales.cross_seller_products.seller','up_sales.up_seller_products.seller', 'skus','seller')->whereHas('seller', function($q) use ($seller_slug){
                return $q->where('slug', $seller_slug);
            })->activeSeller()->firstOrFail();
        }
        return $this->product::where('slug', $slug)->with('product.tags','related_sales.related_seller_products.seller','cross_sales.cross_seller_products.seller','up_sales.up_seller_products.seller', 'skus','seller')->activeSeller()->firstOrFail();
    }

    public function recentViewIncrease($id)
    {
        $sellerProduct = $this->getProductByID($id);
        return $sellerProduct->update([
            'recent_view' => Carbon::now(),
        ]);
    }

    public function recentViewStore($seller_product_id)
    {
        $total_products = RecentViewProduct::where('user_id', auth()->user()->id)->get();
        if (count($total_products) == app('recently_viewed_config')['max_limit']) {
            $old_product = RecentViewProduct::where('user_id', auth()->user()->id)->first()->delete();
        }
        $infoExist = RecentViewProduct::where('user_id', auth()->user()->id)->where('seller_product_id', $seller_product_id)->first();
        if ($infoExist) {
            return $infoExist->update([
                'viewed_at' => date('y-m-d')
            ]);
        } else {
            return RecentViewProduct::create([
                'user_id' => auth()->user()->id,
                'seller_product_id' => $seller_product_id,
                'viewed_at' => date('y-m-d')
            ]);
        }
    }

    public function lastRecentViewinfo()
    {
        return RecentViewProduct::where('user_id', auth()->user()->id)->latest()->pluck('seller_product_id')->toArray();
    }

    public function getReviewByPage($data)
    {
        return ProductReview::where('product_id', $data['product_id'])->latest()->paginate(10);
    }

    public function searchProduct($request)
    {
        $slugs = explode(' ', $request['keyword']);
        
        // for tag
        $tags = Tag::where(function($q) use ($slugs){
            foreach($slugs as $slug){
                $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
            }
            return $q;
        });
        if (isset($request['cat_id']) && $request['cat_id'] != 0) {
            $productIds = CategoryProduct::where('category_id',$request['cat_id'])->pluck('product_id')->toArray();
 
            $tags = $tags->whereHas('products', function($q)use($productIds){
                return $q->whereIn('product_id',$productIds);
            });

        }

        // for product & giftcard
        $products = SellerProduct::with('product')->where(function($q) use ($slugs){
            foreach($slugs as $slug){
                $q = $q->orWhere('product_name', 'LIKE', "%{$slug}%")->orWhere('slug', 'LIKE', "%{$slug}%");
            }
            return $q;
        })->activeSeller()->limit(5)->get();

        $giftcards = GiftCard::where('status', 1)->where(function($q) use ($slugs){
            foreach($slugs as $slug){
                $q = $q->orWhere('name', 'LIKE', "%{$slug}%")->orWhere('sku', 'LIKE', "%{$slug}%");
            }
            return $q;
        })->limit(5)->get();

        $products = $products->merge($giftcards);

        // for category
        $categories = Category::where(function($q) use ($slugs){
            foreach($slugs as $slug){
                $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
            }
            return $q;
        })->where('status', 1)->where('searchable', 1)->limit(6)->get();

        // for seller

        if(isModuleActive('MultiVendor')){
            $sellers = User::activeSeller()->where(function($q) use ($slugs){
                foreach($slugs as $slug){
                    $q = $q->orWhere('first_name', 'LIKE', "%{$slug}%")->orWhere('last_name', 'LIKE', "%{$slug}%")->orWhere('slug', 'LIKE', "%{$slug}%");
                }
                return $q;
            })->orWhereHas('SellerAccount', function($query) use ($slugs){
                return $query->where(function($q) use ($slugs){
                    foreach($slugs as $slug){
                        $q = $q->orWhere('seller_shop_display_name', 'LIKE', "%{$slug}%");
                    }
                    return $q;
                });
            })->activeSeller();
            $sellers = $sellers->with('SellerAccount','SellerBusinessInformation')->limit(5)->get();
            $data = [
                'tags' => $tags->limit(6)->select('name','id')->get(),
                'products' => SearchProductResource::collection($products),
                'categories' => $categories,
                'sellers' => SearchSellerResource::collection($sellers)
            ];
        }else{
            $data = [
                'tags' => $tags->limit(6)->select('name','id')->get(),
                'products' => SearchProductResource::collection($products),
                'categories' => $categories
            ];
        }

        return $data;
    }

    public function recentViewedLast3Product($id){
        if(auth()->check()){
            $ids = RecentViewProduct::where('user_id', auth()->user()->id)->where('seller_product_id','!=',$id)->latest()->take(3)->pluck('seller_product_id')->toArray();
        }elseif(session()->has('recent_viewed_products')){
            $ids = session()->get('recent_viewed_products')->unique('product_id')->where('product_id','!=',$id)->take(3)->pluck('product_id')->toArray();
        }else{
            $ids = [];
        }

        return $this->product::with('product', 'skus')->whereRaw("id in ('". implode("','",$ids)."')")->get();
    }

    public function getPickupByCity($data){
        return PickupLocation::where('created_by', $data['seller_id'])->where('city_id', $data['city_id'])->where('status', 1)->get();
    }

    public function getPickupById($data){
        return PickupLocation::with(['country','state','city'])->where('id', $data['pickup_id'])->where('created_by', $data['seller_id'])->first();
    }

    public function getLowestShippingFromSeller($data){
        return ShippingMethod::where('request_by_user', $data['seller_id'])->where('is_active', 1)->where('id','>', 1)->orderBy('cost')->first();
    }
}
