<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\ShippingCodStatusEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_shipments';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'weight',
        'shipment_id',
        'note',
        'status',
        'cod_amount',
        'cod_status',
        'cross_checking_status',
        'price',
        'store_id',
        'tracking_id',
        'shipping_company_name',
        'tracking_link',
        'estimate_date_shipped',
        'date_shipped',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'estimate_date_shipped',
        'date_shipped',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => ShippingStatusEnum::class,
        'cod_status' => ShippingCodStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Shipment $shipment) {
            ShipmentHistory::where('shipment_id', $shipment->id)->delete();
        });
    }

    public function store(): HasOne
    {
        return $this->hasOne(StoreLocator::class, 'id', 'store_id')->withDefault();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ShipmentHistory::class, 'shipment_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
