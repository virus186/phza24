<?php

namespace Botble\ACL\Models;

use Botble\Base\Models\BaseModel;

class Activation extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'activations';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'completed',
        'completed_at',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'completed' => 'bool',
    ];
}
