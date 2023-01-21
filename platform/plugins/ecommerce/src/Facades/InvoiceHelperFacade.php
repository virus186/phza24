<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\InvoiceHelper;
use Illuminate\Support\Facades\Facade;

class InvoiceHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return InvoiceHelper::class;
    }
}
