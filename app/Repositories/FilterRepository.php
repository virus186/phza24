<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\FrontendCMS\Entities\HomepageCustomProduct;
use Modules\FrontendCMS\Entities\HomePageSection;
use Modules\GiftCard\Entities\GiftCard;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\AttributeRepository;
use Modules\Product\Repositories\BrandRepository;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Seller\Entities\SellerProduct;
use Modules\Setup\Entities\Tag;

class FilterRepository
{
    protected $joins = [];

    public function getAllActiveProduct($sort_by, $paginate)
    {
        // $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) {
        //     $query->where('status', 1);
        // })->activeSeller();
        $products = SellerProduct::with('skus', 'product')->activeSeller()->select('seller_products.*')->join('products', function ($query) {
            $query->on('products.id','=','seller_products.product_id')->where('products.status', 1);
        })->distinct('seller_products.id');
        return $this->sortAndPaginate($products, $sort_by, $paginate);
    }

    public function getAllActiveProductId()
    {
        return SellerProduct::where('status', 1)->whereHas('product', function ($query) {
            $query->where('status', 1);
        })->latest()->activeSeller()->pluck('id')->toArray();
    }

    public function filterProductBlade($data, $sort_by, $paginate_by)
    {
        if (session()->has('filterDataFromCat')) {
            session()->forget('filterDataFromCat');
        }
        $requestType = $data['requestItemType'];
        $requestItem = $data['requestItem'];

        $slugs = explode(' ',$requestItem);
        $giftCards = collect();
        if($data['requestItemType'] == "search"){
            $products = $this->search_query($data['requestItem']);
            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($slugs){
                return $q->where(function($q) use ($slugs){
                    foreach($slugs as $slug){
                        $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                    }
                    return $q;
                });
                
            })->select(['*', 'name as product_name','sku as slug']);
        }
        elseif($data['requestItemType'] == "tag"){
            $tag = Tag::where('name',$requestItem)->first();
            $mainProducts = ProductTag::where('tag_id', $tag->id)->pluck('product_id')->toArray();
            $products = SellerProduct::with('product')->activeSeller()->select('seller_products.*')->join('products', function($query) use($mainProducts){
                return $query->on('products.id','=','seller_products.product_id')->whereRaw("products.id in ('". implode("','",$mainProducts)."')");
            });
            $this->joins = ['products'];
            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($tag){
                return $q->where('tag_id', $tag->id);
                
            })->select(['*', 'name as product_name','sku as slug'])->where('status', 1);
        }
        elseif($data['requestItemType'] == "product"){
            $result = $this->getSectionProducts($requestItem);
            $products = $result['products'];
            $this->joins = ['products'];
        }
        elseif($data['requestItemType'] == "brand"){
            $products = SellerProduct::query()->with('product', 'reviews', 'skus')->activeSeller()->select('seller_products.*')->join('products', function ($query) {
                $query->on('products.id','=','seller_products.product_id')->where('products.status', 1);
            });
            $this->joins = ['products'];
        }
        else{
            $products = SellerProduct::query()->with('product', 'reviews', 'skus')->activeSeller()->select('seller_products.*')->join('products', function ($query) {
                $query->on('products.id','=','seller_products.product_id')->where('products.status', 1)->join('category_product', function ($q) {
                    $q->on('products.id', 'category_product.product_id')->join('categories', function ($q1) {
                        $q1->on('category_product.category_id', 'categories.id')->orOn('category_product.category_id', 'categories.parent_id');
                    });
                });
            });
            $this->joins = ['products','category_product','categories'];
            
        }
        foreach ($data['filterType'] as $key => $filter) {
            if ($filter['filterTypeId'] == "cat" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughCat($typeVal, $products);
                $giftCards = collect();
            }
            if ($filter['filterTypeId'] == "brand" && !empty($filter['filterTypeValue'])) {

                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughBrand($typeVal, $products, $requestType, $requestItem);
                $giftCards = collect();
                
            }
            if (is_numeric($filter['filterTypeId']) && !empty($filter['filterTypeValue'])) {
                $typeId = $filter['filterTypeId'];
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughAttribute($typeId, $typeVal, $products, $requestType, $requestItem);
                $giftCards = collect();
            }

            if ($filter['filterTypeId'] == "price_range") {
                $min_price = round(end($filter['filterTypeValue'])[0])/$this->getConvertRate();
                $max_price = round(end($filter['filterTypeValue'])[1])/$this->getConvertRate();
                $products = $this->productThroughPriceRange($min_price, $max_price, $requestType, $requestItem, $products);
                $giftCards = $giftCards->whereBetween('selling_price', [$min_price,$max_price]);
            }
            if ($filter['filterTypeId'] == "rating") {
                $rating = $filter['filterTypeValue'][0];
                $products = $this->productThroughRating($rating, $requestType, $requestItem, $products);
                $giftCards = $giftCards->where('avg_rating', '>=', $rating);
            }

            if ($data['requestItemType'] == "category" && empty($filter['filterTypeValue'])) {
                $cat = $data['requestItem'];
                $catRepo = new CategoryRepository(new Category());
                $subCat = $catRepo->getAllSubSubCategoryID($cat);
                $subCat[] = intval($cat);
                // dd($subCat);
                // $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat, $subCat) {
                //     $query->whereHas('categories', function($q) use($cat){
                //         $q->where('category_id',$cat)->orWhereHas('subCategories',function($q2) use($cat){
                //             $q2->where('parent_id', $cat);
                //         });
                //     });
                // })->activeSeller();

                // $products = $products->join('category_product', function($q1) use($cat, $subCat){
                //     $q1->on('products.id', '=', 'category_product.product_id')->where('category_product.category_id', $cat)->join('categories', function($q2) use($subCat){
                //         $q2->whereRaw("categories.id in ('". implode("','", $subCat)."')");
                //     });
                // });
                $products = $products->where('category_product.category_id', $cat)->whereRaw("categories.id in ('". implode("','", $subCat)."')");
                $giftCards = collect();
            }
            if ($data['requestItemType'] == "brand" && empty($filter['filterTypeValue'])) {
                $cat = $data['requestItem'];
                $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat) {
                    $query->where('brand_id', $cat);
                })->activeSeller();
                $giftCards = collect();
            }
            
        }
        session()->put('filterDataFromCat', $data);

        if($giftCards->count()){
            $giftCards = $giftCards->get();
        }else{
            $giftCards = [];
        }
        $products = $products->distinct('seller_products.id')->get();
        $products = $products->merge($giftCards);

        return $this->sortAndPaginate($products, $sort_by, $paginate_by);
    }

    public function search_query($slug){
        $slugs = explode(' ',$slug);


            $mainProducts = Product::whereHas('tags', function($q) use($slugs){
                return $q->where(function($q) use ($slugs){
                    foreach($slugs as $slug){
                        $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                    }
                    return $q;
                });
                
            })->pluck('id')->toArray();

        // $products = SellerProduct::with('product')->where('status', 1)->whereHas('product', function($query) use($mainProducts,$slug){
        //     return $query->whereIn('product_id',$mainProducts)->orWhere('product_name','LIKE', "%{$slug}%")->orWhere('description','LIKE',"%{$slug}%")->orWhere('specification','LIKE',"%{$slug}%");
        // })->orWhere('product_name', 'LIKE', "%{$slug}%")->activeSeller();

        $products = SellerProduct::with('product')->activeSeller()->select('seller_products.*')->join('products', function($qq){
            $qq->on('products.id','=','seller_products.product_id')->where('products.status', 1);
        })->whereHas('product', function($query) use($mainProducts,$slug){
            return $query->whereIn('products.id',$mainProducts)->orWhere('products.product_name','LIKE', "%{$slug}%")->orWhere('products.description','LIKE',"%{$slug}%")->orWhere('products.specification','LIKE',"%{$slug}%");
        })->orWhere('seller_products.product_name', 'LIKE', "%{$slug}%")->activeSeller();
        return $products;
    }


    public function filterProductAPI($data, $sort_by, $paginate_by)
    {

        $requestType = $data['requestItemType'];
        $requestItem = $data['requestItem'];
        // $products = SellerProduct::query()->with('product', 'reviews')->whereHas('product', function ($query) {
        //     $query->where('status', 1);
        // })->where('status', 1)->activeSeller();

        $products = SellerProduct::query()->with('product', 'reviews', 'skus')->activeSeller()->select('seller_products.*')->join('products', function ($query) {
            $query->on('products.id','=','seller_products.product_id')->where('products.status', 1)->join('category_product', function ($q) {
                $q->on('products.id', 'category_product.product_id')->join('categories', function ($q1) {
                    $q1->on('category_product.category_id', 'categories.id')->orOn('category_product.category_id', 'categories.parent_id');
                });
            })->join('product_variations', function($q3){
                return $q3->on('products.id', '=', 'product_variations.product_id');
            });
            $this->joins = ['products','category_product','categories','product_variations'];
        });

        foreach ($data['filterType'] as $key => $filter) {
            if ($filter['filterTypeId'] == "cat" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughCat($typeVal, $products);
            }
            if ($filter['filterTypeId'] == "brand" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughBrand($typeVal, $products, $requestType, $requestItem);
            }
            if (is_numeric($filter['filterTypeId']) && !empty($filter['filterTypeValue'])) {
                $typeId = $filter['filterTypeId'];
                $typeVal = $filter['filterTypeValue'];

                $products = $this->productThroughAttribute($typeId, $typeVal, $products, $requestType, $requestItem);
            }

            if ($filter['filterTypeId'] == "price_range") {
                $min_price = round(end($filter['filterTypeValue'])[0])/$this->getConvertRate();
                $max_price = round(end($filter['filterTypeValue'])[1])/$this->getConvertRate();
                $products = $this->productThroughPriceRange($min_price, $max_price, $requestType, $requestItem, $products);
            }
            if ($filter['filterTypeId'] == "rating") {
                $rating = $filter['filterTypeValue'][0];
                $products = $this->productThroughRating($rating, $requestType, $requestItem, $products);
            }
            if ($data['requestItemType'] == "category" && empty($filter['filterTypeId'])) {
                $cat = $data['requestItem'];
                $catRepo = new CategoryRepository(new Category());
                $subCat = $catRepo->getAllSubSubCategoryID($cat);
                $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat, $subCat) {
                    $query->whereHas('categories', function($q) use($cat){
                        $q->where('category_id',$cat)->orWhereHas('subCategories',function($q2) use($cat){
                            $q2->where('parent_id', $cat);
                        });
                    });
                })->activeSeller();
            }
            if ($data['requestItemType'] == "brand" && empty($filter['filterTypeId'])) {
                $cat = $data['requestItem'];
                $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat) {
                    $query->where('brand_id', $cat);
                })->activeSeller();
            }
        }
        return $this->sortAndPaginate($products->distinct('seller_products.id'), $sort_by, $paginate_by);
    }


    public function filterSortProductBlade(array $data, $session_data)
    {
        $requestType = $session_data['requestItemType'];
        $requestItem = $session_data['requestItem'];
        $slugs = explode(' ',$requestItem);
        $giftCards = collect();
        if($session_data['requestItemType'] == 'search'){
            $products = $this->search_query($requestItem);
            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($slugs){
                return $q->where(function($q) use ($slugs){
                    foreach($slugs as $slug){
                        $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                    }
                    return $q;
                });
                
            })->select(['*', 'name as product_name','sku as slug']);
        }
        elseif($session_data['requestItemType'] == "tag"){
            $tag = Tag::where('name',$requestItem)->first();
            $mainProducts = ProductTag::where('tag_id', $tag->id)->pluck('product_id')->toArray();
            // $products = SellerProduct::where('status', 1)->whereHas('product', function($query) use($mainProducts){
            //     return $query->whereIn('product_id',$mainProducts);
            // })->activeSeller();
            $products = SellerProduct::with('product')->activeSeller()->select('seller_products.*')->join('products', function($query) use($mainProducts){
                return $query->on('products.id','=','seller_products.product_id')->whereRaw("products.id in ('". implode("','",$mainProducts)."')");
            });
            $this->joins = ['products'];
            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($tag){
                return $q->where('tag_id', $tag->id);
                
            })->select(['*', 'name as product_name','sku as slug'])->where('status', 1);
        }
        elseif($session_data['requestItemType'] == "product"){
            $result = $this->getSectionProducts($requestItem);
            $products = $result['products']->join('product_variations', function($q3){
                return $q3->on('products.id', '=', 'product_variations.product_id');
            });
            $this->joins = ['products'];
        }
        else{
            // $products = SellerProduct::query()->with('product', 'reviews', 'skus')->whereHas('product', function ($query) {
            //     $query->where('status', 1);
            // })->where('status', 1)->activeSeller();

            $products = SellerProduct::query()->with('product', 'reviews', 'skus')->activeSeller()->select('seller_products.*')->join('products', function ($query) {
                $query->on('products.id','=','seller_products.product_id')->where('products.status', 1)->join('category_product', function ($q) {
                    $q->on('products.id', 'category_product.product_id')->join('categories', function ($q1) {
                        $q1->on('category_product.category_id', 'categories.id')->orOn('category_product.category_id', 'categories.parent_id');
                    });
                });
            });
            $this->joins = ['products','category_product','categories'];
        }
        foreach ($session_data['filterType'] as $key => $filter) {
            if ($filter['filterTypeId'] == "cat" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughCat($typeVal, $products);
                $giftCards = collect();
            }
            if ($filter['filterTypeId'] == "brand" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughBrand($typeVal, $products, $requestType, $requestItem);
                $giftCards = collect();
            }
            if (is_numeric($filter['filterTypeId']) && !empty($filter['filterTypeValue'])) {
                $typeId = $filter['filterTypeId'];
                $typeVal = $filter['filterTypeValue'];
                $giftCards = collect();
                $products = $this->productThroughAttribute($typeId, $typeVal, $products, $requestType, $requestItem);
            }

            if ($filter['filterTypeId'] == "price_range") {
                $min_price = round(end($filter['filterTypeValue'])[0])/$this->getConvertRate();
                $max_price = round(end($filter['filterTypeValue'])[1])/$this->getConvertRate();
                $products = $this->productThroughPriceRange($min_price, $max_price, $requestType, $requestItem, $products);
                $giftCards = $giftCards->whereBetween('selling_price', [$min_price,$max_price]);
            }

            if ($filter['filterTypeId'] == "rating") {
                $rating = $filter['filterTypeValue'][0];
                $products = $this->productThroughRating($rating, $requestType, $requestItem, $products);
                $giftCards = $giftCards->where('avg_rating', '>=', $rating);
            }

            if ($session_data['requestItemType'] == "category" && empty($filter['filterTypeValue'])) {
                $cat = $data['requestItem'];
                $catRepo = new CategoryRepository(new Category());
                $subCat = $catRepo->getAllSubSubCategoryID($cat);
                $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat, $subCat) {
                    $query->whereHas('categories', function($q) use($cat){
                        $q->where('category_id',$cat)->orWhereHas('subCategories',function($q2) use($cat){
                            $q2->where('parent_id', $cat);
                        });
                    });
                })->activeSeller();
                $giftCards = collect();
            }
            if ($session_data['requestItemType'] == "brand" && empty($filter['filterTypeValue'])) {
                $cat = $data['requestItem'];
                $products = SellerProduct::with('skus', 'product')->where('status', 1)->whereHas('product', function ($query) use ($cat) {
                    $query->where('brand_id', $cat);
                })->activeSeller();
                $giftCards = collect();
            }
        }
        if (!empty($data['paginate'])) {
            $paginate = $data['paginate'];
        } else {
            $paginate = 6;
        }

        if($giftCards->count()){
            $giftCards = $giftCards->get();
            $products = $products->get()->merge($giftCards);
        }else{
            $giftCards = [];
        }
        $products->distinct('seller_products.id');
        return $this->sortAndPaginate($products, $data['sort_by'], $paginate);
    }


    public function filterProductCategoryWise($category_id, $category_ids, $sort_by, $paginate_by)
    {
        // $products = SellerProduct::with('skus', 'product')->where('status', 1)->activeSeller()->whereHas('product', function ($query) use ($category_ids, $category_id) {
        //     return $query->where('status', 1)->whereHas('categories',function($q) use($category_id,$category_ids){
        //         return $q->where('category_id', $category_id)->orWhereHas('subCategories', function($q2)use($category_id){
        //             return $q2->where('parent_id', $category_id);
        //         });
        //     });
        // });

        $products = SellerProduct::with('skus', 'product')->where('seller_products.status', 1)->activeSeller()->select("seller_products.*")->join('products', function ($query) use ($category_ids, $category_id) {
            return $query->on('products.id', '=', 'seller_products.product_id')->where('products.status', 1)->join('category_product',function($q) use($category_id,$category_ids){
                return $q->on('products.id','=', 'category_product.product_id')->where('category_product.category_id', $category_id)->join('categories', function($q2) use($category_id){
                    return $q2->on('category_product.category_id', '=', 'categories.id')->orOn('category_product.category_id', '=', 'categories.parent_id');
                });
            });
        })->distinct('seller_products.id');

        return $this->sortAndPaginate($products, $sort_by, $paginate_by);
    }

    public function filterProductBrandWise($brand_id, $sort_by, $paginate_by)
    {
        $products = SellerProduct::with('skus', 'product')->where('seller_products.status', 1)->activeSeller()->select('seller_products.*')->join('products', function ($query) use ($brand_id) {
            return $query->on('seller_products.product_id', '=', 'products.id')->where('products.brand_id', $brand_id)->where('products.status', 1);
        });

        return $this->sortAndPaginate($products, $sort_by, $paginate_by);
    }

    public function filterCategoryBrandWise($brand_id)
    {

        $categoryList = Category::select('categories.*')->join('category_product', function ($q) use ($brand_id) {
            $q->on('categories.id', '=', 'category_product.category_id')->join('products', function($q1) use($brand_id){
                $q1->on('products.id', 'category_product.product_id')->where('products.brand_id',$brand_id);
            })->where('products.status', 1);
        })->where('categories.status', 1)->where('categories.parent_id','!=',0)->distinct('categories.id')->take(20)->get();
        return $categoryList;
    }

    public function filterBrandCategoryWise($category_id, $category_ids)
    {
        
        $brnadList = Brand::select('brands.*')->where('brands.status', 1)->join('products', function($q) use($category_ids, $category_id){
            return $q->on('products.brand_id', '=', 'brands.id')->join('category_product', function($q1) use($category_ids, $category_id){
                return $q1->on('category_product.product_id', '=', 'products.id')->whereRaw("category_product.category_id in('". implode("','",$category_ids). "')");
            });
        })->distinct('brands.id')->take(20)->get();

        return $brnadList;
    }

    public function filterProductFromCategoryBlade($data, $sort_by, $paginate)
    {
        if (session()->has('filterDataFromCat')) {
            session()->forget('filterDataFromCat');
        }
        
        $products = SellerProduct::query()->with('product', 'reviews')->activeSeller()->select('seller_products.*')->join('products', function($q){
            $q->on('seller_products.product_id', '=', 'products.id')->where('products.status', 1);
        });
        $this->joins = ['products'];
        foreach ($data['filterType'] as $key => $filter) {
            if ($filter['filterTypeId'] == "parent_cat" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughParentCat($typeVal, $products);
            }

            if ($filter['filterTypeId'] == "cat" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughCat($typeVal, $products);
            }

            if ($filter['filterTypeId'] == "brand" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'];
                $products = $this->productThroughBrandForAllListing($typeVal, $products);
            }
            if ($filter['filterTypeId'] != "price_range" && $filter['filterTypeId'] != "rating" && $filter['filterTypeId'] != "brand" && $filter['filterTypeId'] != "parent_cat" && $filter['filterTypeId'] != "cat" && !empty($filter['filterTypeValue'])) {
                $typeId = $filter['filterTypeId'];
                $typeVal = $filter['filterTypeValue'];
                $products = $products->where('status', 1)->whereHas('product', function ($q) use ($typeId, $typeVal) {
                    $q->whereHas('variations', function ($query) use ($typeId, $typeVal) {
                        $query->whereIn('attribute_id', [$typeId])->whereIn('attribute_value_id', $typeVal);
                    });
                });
            }
            if ($filter['filterTypeId'] == "price_range") {
                $min_price = round(end($filter['filterTypeValue'])[0])/$this->getConvertRate();
                $max_price = round(end($filter['filterTypeValue'])[1])/$this->getConvertRate();
                $products = $this->productThroughPriceRangeForAllListing($min_price, $max_price, $products);
            }

            if ($filter['filterTypeId'] == "rating") {

                $rating = $filter['filterTypeValue'][0];
                $products = $this->productThroughRatingForAllListing($rating, $products);
            }
        }
        session()->put('filterDataFromCat', $data);
        return $this->sortAndPaginate($products->distinct('seller_products.id'), $sort_by, $paginate);
    }

    public function filterProductMinPrice($product_ids = null)
    {
        if($product_ids){
            return SellerProductSKU::whereRaw("product_id in ('". implode("','", $product_ids) . "')")->min('selling_price');
        }else{
            return SellerProductSKU::whereHas('product' , function($q){
                $q->where('status', 1)->whereHas('product', function ($query) {
                    $query->where('status', 1);
                })->latest()->activeSeller();
            })->min('selling_price');
        }
    }

    public function filterProductMaxPrice($product_ids = null)
    {
        if($product_ids){
            return SellerProductSKU::whereRaw("product_id in ('". implode("','",$product_ids)."')")->max('selling_price');
        }else{
            return SellerProductSKU::whereHas('product' , function($q){
                $q->where('status', 1)->whereHas('product', function ($query) {
                    $query->where('status', 1);
                })->latest()->activeSeller();
            })->max('selling_price');
        }
    }

    public function productThroughCat($typeVal, $products)
    {
        
        $ids = [];
        foreach($typeVal as $cat){
            $ids[] = $cat;
        }
        // $products = $products->whereHas('categories', function($q1) use ($ids){
        //     $q1->whereRaw("category_id in ('" . implode("','", $ids) . "')")->orWhereHas('subCategories', function($q3)use($ids){
        //         $q3->whereRaw("parent_id in ('" . implode("','", $ids) . "')");
        //     });
        //     $q1->whereRaw("category_id in ('" . implode("','", $ids) . "')");
        // });
        if(!in_array('products',$this->joins)){
            // $products = $products->join('products', function($q){
            //     $q->on('seller_products.product_id','=', 'products.id');
            // });
            $products = $products;
            array_push($this->joins,'products');
        }
        if(!in_array('category_product',$this->joins)){
            $products = $products->join('category_product', function($q1) use ($ids){
                $q1->on('category_product.product_id','=', 'products.id')->join('categories', function ($q2) use ($ids) {
                    $q2->on('categories.id', '=','category_product.category_id')->whereRaw("categories.id in ('". implode("','", $ids)."')");
                });
            });
            array_push($this->joins, 'category_product', 'categories');
        }else{
            $products = $products->whereRaw("categories.id in ('". implode("','", $ids)."')");
        }
        return $products;
    }

    public function productThroughBrandForAllListing($typeVal, $products)
    {
        return $products->whereRaw("products.brand_id in ('". implode("','", $typeVal). "')");
    }

    public function productThroughParentCat($typeVal, $products)
    {
        $category_ids = Category::whereHas('parentCategory', function ($q) use ($typeVal) {
            $q->whereRaw("id in ('".implode("','", $typeVal)."')");
        })->pluck('id')->toArray();
 
        foreach ($typeVal as $key => $value) {
            array_push($category_ids, intval($value));
        }
        // $products = $products->whereHas('categories', function ($q) use ($category_ids) {
        //     $q->whereRaw("category_id in ('". implode("','", $category_ids)."')")->orWhereHas('subCategories', function($q1) use($category_ids){
        //         $q1->whereRaw("id in ('". implode("','", $category_ids)."')");
        //     });
        //     $q->whereRaw("category_id in ('". implode("','", $category_ids)."')");
        // });
        if(!in_array('products',$this->joins)){
            $products = $products->join('products', function($q){
                $q->on('seller_products.product_id','=', 'products.id');
            });
            array_push($this->joins,'products');
        }
        if(!in_array('category_product',$this->joins)){
            $products = $products->join('category_product', function($q1) use ($category_ids){
                $q1->on('category_product.product_id','=', 'products.id')->join('categories', function ($q2) use ($category_ids) {
                    $q2->on('categories.id', '=','category_product.category_id')->whereRaw("categories.id in ('". implode("','", $category_ids)."')");
                });
            });
            array_push($this->joins, 'category_product', 'categories');
        }
        else{
            $products = $products->whereRaw("categories.id in ('". implode("','", $category_ids)."')");
        }
        return $products;
    }

    public function productThroughBrand($typeVal, $products, $requestType, $requestItem)
    {
        if ($requestType == "category") {            
            // $products = $products->where('status', 1)->whereHas('product', function ($q) use ($typeVal, $requestItem) {
            //     $q->WhereHas('categories', function ($q1) use ($requestItem) {
            //         $q1->where('category_id', $requestItem)->orWhereHas('subCategories', function($q2)use($requestItem){
            //             $q2->where('parent_id', $requestItem);
            //         });
            //     })->whereIn('brand_id', $typeVal);
            // });

            // $products = $products->join('category_product', function ($q) use ($typeVal, $requestItem) {
            //     $q->on('products.id', 'category_product.product_id')->where('category_product.category_id', $requestItem)->join('categories', function ($q1) use ($requestItem) {
            //         $q1->on('category_product.category_id', 'categories.id')->orOn('category_product.category_id', 'categories.parent_id');
            //     })->whereRaw("products.brand_id in ('". implode("','", $typeVal). "')");
            // });

            $products = $products->whereRaw("products.brand_id in ('". implode("','", $typeVal). "')")->where('category_product.category_id', $requestItem);
            return $products;
        }
        if ($requestType == "search") {
            $products = $products->whereHas('product', function($query) use($typeVal){
                $query->whereRaw("brand_id in ('". implode("','",$typeVal)."')");
            });
            return $products;
        }
        else{
            // $products = $products->whereHas('product', function($query)use($typeVal){
            //     $query->whereIn('brand_id', $typeVal);
            // });
            
            $products = $products->whereRaw("products.brand_id in ('". implode("','",$typeVal)."')");
            return $products;
        }
    }

    public function productThroughAttribute($typeId, $typeVal, $products, $requestType, $requestItem)
    {
        if ($requestType ==  "category") {
            if(!in_array('product_variations',$this->joins)){
                $products->join('product_variations', function($q3){
                    return $q3->on('products.id', '=', 'product_variations.product_id');
                });
                array_push($this->joins,'product_variations');
            }
            $products = $products->where('products.status', 1)->where('category_product.category_id', $requestItem)->whereRaw("product_variations.attribute_id in ('". implode("','",[$typeId])."')")->whereRaw("product_variations.attribute_value_id in ('". implode("','",$typeVal)."')");

        } elseif ($requestType ==  "brand") {
            
            if(!in_array('product_variations',$this->joins)){
                $products->join('product_variations', function($q3){
                    return $q3->on('products.id', '=', 'product_variations.product_id');
                });
                array_push($this->joins,'product_variations');
            }
            $products = $products->where('products.status', 1)->whereRaw("products.brand_id in ('". implode("','",[$requestItem])."')")->whereRaw("product_variations.attribute_id in ('". implode("','",[$typeId])."')")->whereRaw("product_variations.attribute_value_id in ('". implode("','",$typeVal)."')");

        } else {
            if(!in_array('product_variations',$this->joins)){
                $products->join('product_variations', function($q3){
                    return $q3->on('products.id', '=', 'product_variations.product_id');
                });
                array_push($this->joins,'product_variations');
            }
            $products = $products->where('products.status', 1)->whereRaw("product_variations.attribute_id in ('". implode("','",[$typeId])."')")->whereRaw("product_variations.attribute_value_id in ('". implode("','",$typeVal)."')");
        }
        return $products;
    }

    public function productThroughPriceRange($min_price, $max_price, $requestType, $requestItem, $products)
    {
        if ($requestType ==  "category") {
            // $products = $products->whereHas('product', function ($q) use ($requestItem) {
            //     $q->whereHas('categories', function($q1) use($requestItem){
            //         $q1->where('category_id',$requestItem)->orWhereHas('subCategories', function($q2) use($requestItem){
            //             $q2->where('parent_id', $requestItem);
            //         });
            //     })->where('products.status', 1);
            // });

            // $products = $products->where('products.status', 1)->join('category_product', function($q1) use ($requestItem){
            //     return $q1->on('products.id', '=','category_product.product_id')->where('category_product.category_id', $requestItem)->join('categories', function($q2) use($requestItem){
            //         return $q2->on('category_product.category_id', '=', 'categories.parent_id')->orOn('category_product.category_id', '=', 'categories.id');
            //     });
            // });

            $products = $products->where('products.status', 1)->where('category_product.category_id', $requestItem);
        }
        elseif ($requestType ==  "brand") {
            // $products = $products->whereHas('product', function ($q) use ($requestItem) {
            //     $q->whereIn('brand_id', [$requestItem])->where('products.status', 1);
            // });
            $products = $products->where('products.status', 1)->whereRaw("products.brand_id in ('". implode("','",[$requestItem])."')");
        }
        // $products = $products->whereHas('skus', function ($q) use ($min_price, $max_price) {
        //     $q->whereBetween('selling_price', array($min_price, $max_price));
        // });
        if(!in_array('seller_product_s_k_us',$this->joins)){
            $products = $products->join('seller_product_s_k_us', function ($q) use ($min_price, $max_price) {
                $q->on('seller_products.id', '=', 'seller_product_s_k_us.product_id')->whereBetween('seller_product_s_k_us.selling_price', array($min_price, $max_price));
            });
            array_push($this->joins,'seller_product_s_k_us');
        }else{
            $products = $products->whereBetween('seller_product_s_k_us.selling_price', array($min_price, $max_price));
        }
        return $products;
    }

    private function productThroughRating($rating, $requestType, $requestItem, $products)
    {
        if ($requestType ==  "category") {

            $products = $products->where('products.status', 1)->where('category_product.category_id', $requestItem);
        }
        if ($requestType ==  "brand") {
            $products = $products->where('products.status', 1)->whereRaw("products.brand_id in ('". implode("','",[$requestItem])."')");
        }

        $products = $products->where('seller_products.avg_rating', '>=', $rating);
        return $products;
    }

    public function productThroughPriceRangeForAllListing($min_price, $max_price, $products)
    {
        $products = $products->join('seller_product_s_k_us', function ($q) use ($min_price, $max_price) {
            $q->on('seller_products.id', '=', 'seller_product_s_k_us.product_id')->whereBetween('seller_product_s_k_us.selling_price', array($min_price, $max_price));
        });
        return $products;
    }

    private function productThroughRatingForAllListing($rating, $products)
    {
        $products = $products->where('seller_products.avg_rating', '>=', $rating);

        return $products;
    }

    public function sortAndPaginate($products, $sort_by, $paginate_by)
    {
        $sort = 'desc';
            $column = 'created_at';
            if(in_array($sort_by,['old','alpha_asc','low_to_high'])){
                $sort = 'asc';
            }
            if(in_array($sort_by,['alpha_asc','alpha_desc'])){
                $column = 'product_name';
            }
            elseif ($sort_by == "low_to_high") {
                $column = 'min_sell_price';
            }
            elseif ($sort_by == "high_to_low") {
                $column = 'max_sell_price';
            }
        if(get_class($products) == \Illuminate\Database\Eloquent\Builder::class){
            $products = $products->orderBy($column, $sort);
            
        }else{
            if($sort == 'asc'){
                $products = $products->sortBy($column);
            }else{
                $products = $products->sortByDesc($column);
            }
    
        }
        return $products->paginate(($paginate_by != null) ? $paginate_by : 9);   
    }

    public function productSortByCategory($itemType, $id, $sort_by, $paginate_by)
    {
        $category_id = 0;

        $item = $itemType;
        $data = [];

        if ($item == 'category') {
            $category_id = $id;
            $catRepo = new CategoryRepository(new Category());
            $data['CategoryList'] = $catRepo->subcategory($category_id);
            $data['filter_name'] = $catRepo->show($category_id);
            $category_ids = $catRepo->getAllSubSubCategoryID($category_id);
            $data['brandList'] = $this->filterBrandCategoryWise($category_id, $category_ids);
            $data['products'] = $this->filterProductCategoryWise($category_id, $category_ids, $sort_by, $paginate_by);
            $attributeRepo = new AttributeRepository;
            $data['attributeLists'] = $attributeRepo->getAttributeForSpecificCategory($category_id, $category_ids);
            $data['category_id'] = $category_id;
            $data['color'] = $attributeRepo->getColorAttributeForSpecificCategory($category_id, $category_ids);
        }

        if ($item == 'brand') {
            $brand_id = $id;
            $brandRepo = new BrandRepository(new Brand());
            $data['filter_name'] = $brandRepo->find($brand_id);
            $data['brand_id'] = $brand_id;
            $data['products'] = $this->filterProductBrandWise($brand_id, $sort_by, $paginate_by);
            $data['CategoryList'] = $this->filterCategoryBrandWise($brand_id);
            $attributeRepo = new AttributeRepository;
            $data['attributeLists'] = $attributeRepo->getAttributeForSpecificBrand($brand_id);
            $data['color'] = $attributeRepo->getColorAttributeForSpecificBrand($brand_id);
        }

        if ($item == 'tag') {

            $tag = Tag::where('name',$id)->first();
            $mainProducts = ProductTag::where('tag_id', $tag->id)->pluck('product_id')->toArray();
            $products = SellerProduct::with('product')->where('status', 1)->whereHas('product', function($query) use($mainProducts){
                return $query->whereIn('product_id',$mainProducts);
            })->activeSeller();

            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($tag){
                return $q->where('tag_id', $tag->id);
                
            })->select(['*', 'name as product_name','sku as slug'])->where('status', 1);

            if($giftCards->count()){
                $giftCards = $giftCards->get();
            }else{
                $giftCards = [];
            }
            $products = $products->get()->merge($giftCards);
            

            $data['products'] = $this->sortAndPaginate($products, $sort_by, $paginate_by);
        }
        if($item == 'search'){

            $slugs = explode(' ',$id);
            $giftCards = collect();

            $giftCards = GiftCard::where('status', 1)->whereHas('tags', function($q) use($slugs){
                return $q->where(function($q) use ($slugs){
                    foreach($slugs as $slug){
                        $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                    }
                    return $q;
                });
                
            })->select(['*', 'name as product_name','sku as slug']);

            $products = $this->search_query($id);
        
            if($giftCards->count()){
                $giftCards = $giftCards->get();
            }else{
                $giftCards = [];
            }
            $products = $products->get()->merge($giftCards);

            $data['products'] = $this->sortAndPaginate($products, $sort_by, $paginate_by);
        }
        if ($item == 'product') {
            $result = $this->getSectionProducts($id);
            $products = $result['products'];

            $data['products'] = $this->sortAndPaginate($products, $sort_by, $paginate_by);
        }

        return $data['products'];
    }

    public function getSectionProducts($section_name){
        $section = HomePageSection::where('section_name',$section_name)->first();
        // $products = SellerProduct::with('seller','reviews')->activeSeller();
        $products = SellerProduct::with('seller','reviews')->activeSeller()->select('seller_products.*')->join('products', function($q){
            return $q->on('seller_products.product_id', '=', 'products.id');
        });
        $data['products'] = $products;
        $data['section'] = $section;
        if(request()->sort_by){
            return $data;
        }
        if($section->type == 1){
            // $products = $products->whereHas('product', function($query){
            //     return $query->where('status', 1)->whereHas('categories', function($q){
            //         return $q->orderBy('id');
            //     });
            // });
            $products = $products->join('category_product', function($q1){
                $q1->on('products.id','=', 'category_product.product_id')->orderBy('category_product.category_id');
            })->where('products.status', 1);
        }else{
            $products = $products->where('products.status', 1);
        }
        if($section->type == 2){
            $products = $products->latest();
        }
        if($section->type == 3){
            $products->orderByDesc('recent_view');
        }
        if($section->type == 4){
            $products->orderByDesc('total_sale');

        }
        if($section->type == 5){
            $products = $products->withCount('reviews')->orderByDesc('reviews_count');
        }
        if($section->type == 6){
            $product_ids = HomepageCustomProduct::where('section_id',$section->id)->pluck('seller_product_id')->toArray();
            $products =  $products->whereRaw("seller_products.id in ('". implode("','",$product_ids). "')");

        }
        $data['products'] = $products->distinct('seller_products.id');
        return $data;
    }

    public function getConvertedMin($value){
        if(auth()->check() && auth()->user()->currency->code != app('general_setting')->currency_code){
            $rate = auth()->user()->currency->convert_rate;
            $value = $value * $rate;
        }else{
            if(Session::has('currency')){
                $currency = DB::table('currencies')->where('id', Session::get('currency'))->first();
                $rate = $currency->convert_rate;
                $value = $value * $rate;
            }
        }
        return $value;
    }
    public function getConvertedMax($value){
        if(auth()->check() && auth()->user()->currency->code != app('general_setting')->currency_code){
            $rate = auth()->user()->currency->convert_rate;
            $value = $value * $rate;
        }else{
            if(Session::has('currency')){
                $currency = DB::table('currencies')->where('id', Session::get('currency'))->first();
                $rate = $currency->convert_rate;
                $value = $value * $rate;
            }
        }
        return $value;
    }

    public function getConvertRate(){
        $rate = 1;
        if(auth()->check() && auth()->user()->currency->code != app('general_setting')->currency_code){
            $rate = auth()->user()->currency->convert_rate;
        }else{
            if(Session::has('currency')){
                $currency = DB::table('currencies')->where('id', Session::get('currency'))->first();
                $rate = $currency->convert_rate;
            }
        }
        return $rate;
    }
}
