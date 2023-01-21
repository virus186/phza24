<?php

namespace Botble\Ecommerce\Listeners;

use Illuminate\Support\Facades\Cache;

class ClearProductCategoriesCacheListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forget('ecommerce-product-category-tree');
    }
}
