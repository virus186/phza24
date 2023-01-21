<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_options';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'option_type',
        'required',
        'product_id',
        'order',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(OptionValue::class, 'option_id')->orderBy('order', 'ASC');
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Option $option) {
            OptionValue::where('option_id', $option->id)->delete();
        });
    }
}
