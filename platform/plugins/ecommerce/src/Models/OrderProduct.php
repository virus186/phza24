<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderProduct extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_order_product';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'qty',
        'weight',
        'price',
        'tax_amount',
        'options',
        'product_options',
        'restock_quantity',
        'product_type',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'json',
        'product_options' => 'json',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function getAmountFormatAttribute(): string
    {
        return format_price($this->price);
    }

    public function getTotalFormatAttribute(): string
    {
        return format_price($this->price * $this->qty);
    }

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id');
    }
}
