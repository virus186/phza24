<?php

namespace Modules\Seller\Entities;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariations;
use Modules\Product\Entities\ProductSku;
use Modules\WholeSale\Entities\WholesalePrice;
use Znck\Eloquent\Traits\BelongsToThrough;

class SellerProductSKU extends Model
{
    use HasFactory, BelongsToThrough ;

    public static function boot()
    {
        parent::boot();

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

    protected $guarded = ['id'];
    protected $table = 'seller_product_s_k_us';


    public function product(){
        return $this->belongsTo(SellerProduct::class,'product_id','id');
    }

    public function mainProduct(){
        return $this->belongsToThrough(Product::class, SellerProduct::class,
            null,
            '',
            [SellerProduct::class => 'product_id']
        );
    }

    public function sku(){
        return $this->belongsTo(ProductSku::class,'product_sku_id');
    }

    public function product_variations(){
        return $this->hasMany(ProductVariations::class,'product_sku_id','product_sku_id');
    }

    public function seller(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function cartProducts(){
        return $this->hasMany(Cart::class,'product_id', 'id')->where('product_type', 'product');
    }

    public function wholeSalePrices(){
        return $this->hasMany(WholesalePrice::class,'sku_id','id');
    }
}
