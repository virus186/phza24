<?php

namespace Botble\Setting\Models;

use Botble\Base\Models\BaseModel;

class Setting extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];
}
