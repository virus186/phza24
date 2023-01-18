<?php

namespace Modules\Product\Entities;

use App\Models\UsedMedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\GST\Entities\GSTGroup;
use Modules\Seller\Entities\SellerProduct;
use Modules\Setup\Entities\Tag;
use Modules\Shipping\Entities\ProductShipping;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use Sluggable, HasTranslations;
    protected $table = "products";
    protected $guarded = ["id"];
    protected $appends = [];
    public $translatable = [];
    public function __construct()
    {
        parent::__construct();
        if (isModuleActive('FrontendMultiLang')) {
            $this->translatable = ['product_name','subtitle_1','subtitle_2','description','specification','meta_title','meta_description'];
            $this->appends = ['translateProductName','TranslateProductSubtitle1','TranslateProductSubtitle2'];
        }
    }
    

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->created_by == null) {
                $model->created_by = Auth::user()->id ?? null;
            }
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()->id ?? null;
        });

        self::created(function ($model) {
            Cache::forget('MegaMenu');
            Cache::forget('HeaderSection');
        });
        self::updated(function ($model) {
            Cache::forget('MegaMenu');
            Cache::forget('HeaderSection');
        });
        self::deleted(function ($model) {
            Cache::forget('MegaMenu');
            Cache::forget('HeaderSection');
        });
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class)->withDefault();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withPivot('category_id', 'product_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'product_name'
            ]
        ];
    }
    public function scopeFindSimilarSlugs(Builder $query, string $attribute, array $config, string $slug): Builder
    {
        $seller_id = getParentSellerId();
        if($seller_id != 0){
            $separator = $config['separator'];
            return $query->where(function(Builder $q) use ($attribute, $slug, $separator) {
                $q->where($attribute, '=', $slug)
                    ->orWhere($attribute, 'LIKE', $slug . $separator . '%');
            })->where('created_by', $seller_id);
        }else{
            return $query;
        }
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id")->withDefault();
    }

    public function variations()
    {
        return $this->hasMany(ProductVariations::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
    public function activeSkus(){

        return $this->hasMany(ProductSku::class)->where('status', 1);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withPivot('tag_id', 'product_id');
    }

    public function gallary_images()
    {
        return $this->hasMany(ProductGalaryImage::class);
    }

    public function seller(){
        return $this->belongsTo(User::class, "created_by","id");
    }

    public function sellerProducts(){
        return $this->hasMany(SellerProduct::class,'product_id','id');
    }

    public function relatedProducts(){
        return $this->hasMany(ProductRelatedSale::class,'product_id','id');
    }
    public function upSales(){
        return $this->hasMany(ProductUpSale::class,'product_id','id');
    }
    public function crossSales(){
        return $this->hasMany(ProductCrossSale::class,'product_id','id');
    }
    public function shippingMethods(){
        return $this->hasMany(ProductShipping::class,'product_id','id');
    }

    public function scopeBarcodeList($query)
    {
        return $array = array("C39", "C39+", "C39E", "C39E+", "C93", "I25", "POSTNET", "EAN2", "EAN5", "PHARMA2T");
    }

    public function gstGroup(){
        return $this->belongsTo(GSTGroup::class, 'gst_group_id', 'id');
    }

    public function meta_image_media(){
        return $this->morphOne(UsedMedia::class, 'usable')->where('used_for', 'meta_image');
    }
    public function getTranslateProductNameAttribute(){
        return $this->attributes['product_name'];
    }
    public function getTranslateProductSubtitle1Attribute(){
        return $this->attributes['subtitle_1'];
    }
    public function getTranslateProductSubtitle2Attribute(){
        return $this->attributes['subtitle_2'];
    }
    
}
