<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RvMedia;

class ProductAttribute extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_attributes';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'color',
        'status',
        'order',
        'attribute_set_id',
        'image',
        'is_default',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAttributeSetIdAttribute(?int $value): int
    {
        return (int)$value;
    }

    public function productAttributeSet(): BelongsTo
    {
        return $this->belongsTo(ProductAttributeSet::class, 'attribute_set_id');
    }

    public function getGroupIdAttribute(?int $value): int
    {
        return (int)$value;
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductAttribute $productAttribute) {
            ProductVariationItem::where('attribute_id', $productAttribute->id)->delete();
        });
    }

    public function productVariationItems(): HasMany
    {
        return $this->hasMany(ProductVariationItem::class, 'attribute_id');
    }

    /**
     * @param ProductAttributeSet|null $attributeSet
     * @param array $productVariations
     * @return string
     */
    public function getAttributeStyle(?ProductAttributeSet $attributeSet = null, $productVariations = []): string
    {
        if ($attributeSet && $attributeSet->use_image_from_product_variation) {
            foreach ($productVariations as $productVariation) {
                $attribute = $productVariation->productAttributes->where('attribute_set_id', $attributeSet->id)->first();
                if ($attribute && $attribute->id == $this->id && $productVariation->product->image) {
                    return 'background-image: url(' . RvMedia::getImageUrl($productVariation->product->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
                }
            }
        }

        if ($this->image) {
            return 'background-image: url(' . RvMedia::getImageUrl($this->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
        }

        return 'background-color: ' . ($this->color ?: '#000') . ';';
    }
}
