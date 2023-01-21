<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariationItem extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_product_variation_items';

    /**
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'variation_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id')->withDefault();
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id')->withDefault();
    }

    public function attributeSet(): HasMany
    {
        return $this->hasMany(ProductAttributeSet::class, 'attribute_set_id');
    }
}
