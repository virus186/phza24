<?php

namespace Modules\FrontendCMS\Entities;

use App\Repositories\FilterRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\SellerProduct;

class HomePageSection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function products()
    {
        return $this->hasMany(HomepageCustomProduct::class, 'section_id', 'id');
    }
    public function brands()
    {
        return $this->hasMany(HomepageCustomBrand::class, 'section_id', 'id');
    }
    public function categories()
    {
        return $this->hasMany(HomepageCustomCategory::class, 'section_id', 'id');
    }

    public function getProductByQuery()
    {
        $filterRepo = new FilterRepository();
        $data = $filterRepo->getSectionProducts($this->section_name);
        return $data['products']->with('skus','product.gallary_images')->take(12)->get();
    }

    public function getHomePageProductByQuery()
    {
        $filterRepo = new FilterRepository();
        $data = $filterRepo->getSectionProducts($this->section_name);
        $paginate = 18;
        if(app('theme')->folder_path == 'amazy'){
            $paginate = 20;
        }
        return $data['products']->with('skus','wishList','product.shippingMethods')->paginate($paginate);
    }



    public function getCategoryByQuery()
    {
        // $categories = Category::with('products.sellerProducts')->where('status', 1)->whereHas('products',function($query){
        //     return $query->whereHas('sellerProducts', function($q){
        //         return $q->activeSeller();
        //     })->where('status', 1);
        // });

        $categories = Category::with(['sellerProducts.product', 'sellerProducts.seller'])->whereHas('sellerProducts');
        if ($this->type == 1) {
            $categories = $categories->orderByDesc('total_sale');
        }
        if ($this->type == 2) {
            $categories = $categories->latest();
        }
        if ($this->type == 3) {
            $categories = $categories->orderByDesc('total_sale');
        }
        if ($this->type == 4) {
            $categories = $categories->orderByDesc('avg_rating');
        }
        if ($this->type == 5) {
            return $categories->withCount('sellerProducts')->orderByDesc('seller_products_count')->take(12)->get();
        }

        if ($this->type == 6) {
            $category_ids = HomepageCustomCategory::where('section_id', $this->id)->pluck('category_id')->toArray();
            $categories = $categories->whereRaw("id in ('". implode("','",$category_ids)."')");
        }
        $paginate = 12;
        if(app('theme')->folder_path == 'amazy'){
            $paginate = 6;
        }
        return $categories = $categories->take($paginate)->get();

        
    }

    public function getBrandByQuery()
    {
        // $brands = Brand::where('status', 1)->has('products.sellerProducts');
        $brands = Brand::select('brands.*')->where('brands.status', 1)->join('products',function($q){
            $q->on('products.brand_id','=', 'brands.id')->join('seller_products', function($q1){
                $q1->on('seller_products.product_id', '=', 'products.id');
            });
        });

        if ($this->type == 1) {
            $brands = $brands->orderByDesc('brands.total_sale');
        }
        if ($this->type == 2) {
            $brands = $brands->orderBy('id', 'desc');
        }
        if ($this->type == 3) {
            $brands = $brands->orderByDesc('brands.featured');
        }
        if ($this->type == 4) {
            $brands = $brands->orderByDesc('brands.total_sale');
        }
        if ($this->type == 5) {
            $brands = $brands->orderByDesc('brands.avg_rating');
        }
        if ($this->type == 6) {
            $brand_ids = HomepageCustomBrand::where('section_id', $this->id)->pluck('brand_id')->toArray();
            $brands = $brands->whereIn('brands.id', $brand_ids);
        }
        $paginate = 12;
        if(app('theme')->folder_path == 'amazy'){
            $paginate = 10;
        }
        return $brands->distinct('brands.id')->take($paginate)->get();
    }



    public function getApiProductByQuery()
    {
        $products = SellerProduct::with(
            'product.shippingMethods.shippingMethod',
            'product.upSales.up_seller_products',
            'product.crossSales.cross_seller_products',
            'product.relatedProducts.related_seller_products',
            'product.gallary_images',
            'product.brand',
            'product.categories',
            'product.unit_type',
            'product.variations',
            'product.skus',
            'product.tags',
            'product.gallary_images'
        )->activeSeller();

        if ($this->type == 1) {
            $products->whereHas('product',function($query){
                $query->whereHas('categories', function($q){
                    $q->orderByDesc('category_id');
                })->where('status',1);
            });
        }else{
            $products = $products->whereHas('product', function($query){
                $query->where('status', 1);
            });
        }
        if ($this->type == 2) {
            $products = $products->latest();
        }
        if ($this->type == 3) {
            $products->orderByDesc('recent_view');
        }
        if ($this->type == 4) {
            $products->orderByDesc('total_sale');
        }
        if ($this->type == 5) {
            $products = $products->withCount('reviews')->orderByDesc('reviews_count');
        }
        if ($this->type == 6) {
            $product_ids = HomepageCustomProduct::where('section_id', $this->id)->get();
            $products->whereIn('id', $product_ids->pluck('seller_product_id'));
        }
        return $products->paginate(10);
    }

    public function customSection(){
        return $this->hasOne(HomePageCustomSection::class,'section_id', 'id');
    }
}
