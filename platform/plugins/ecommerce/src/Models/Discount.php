<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_discounts';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'start_date',
        'end_date',
        'quantity',
        'total_used',
        'value',
        'type',
        'can_use_with_promotion',
        'type_option',
        'target',
        'min_order_price',
        'discount_on',
        'product_quantity',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function isExpired(): bool
    {
        if ($this->end_date && strtotime($this->end_date) < strtotime(now()->toDateTimeString())) {
            return true;
        }

        return false;
    }

    public function productCollections(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCollection::class,
            'ec_discount_product_collections',
            'discount_id',
            'product_collection_id'
        );
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'ec_discount_customers', 'discount_id', 'customer_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_discount_products', 'discount_id', 'product_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Discount $discount) {
            $discount->productCollections()->detach();
            $discount->customers()->detach();
            $discount->products()->detach();
        });
    }
}
