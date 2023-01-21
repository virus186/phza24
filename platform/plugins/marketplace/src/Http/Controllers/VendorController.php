<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Tables\VendorTable;
use Illuminate\Contracts\View\Factory;

class VendorController extends BaseController
{
    /**
     * @param VendorTable $table
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(VendorTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::marketplace.vendors'));

        return $table->renderTable();
    }
}
