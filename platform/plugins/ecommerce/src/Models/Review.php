<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_reviews';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'customer_id',
        'star',
        'comment',
        'status',
        'images',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'images' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function getProductNameAttribute(): ?string
    {
        return $this->product->name;
    }

    public function getUserNameAttribute(): ?string
    {
        return $this->user->name;
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Review $review) {
            if (!$review->images || !is_array($review->images) || !count($review->images)) {
                $review->images = null;
            }
        });

        self::updating(function (Review $review) {
            if (!$review->images || !is_array($review->images) || !count($review->images)) {
                $review->images = null;
            }
        });
    }
}
