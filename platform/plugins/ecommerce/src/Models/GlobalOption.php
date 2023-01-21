<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalOption extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_global_options';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'option_type',
        'required',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(GlobalOptionValue::class, 'option_id')
            ->orderBy('order', 'ASC');
    }
}
