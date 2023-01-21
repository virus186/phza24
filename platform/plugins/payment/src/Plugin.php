<?php

namespace Botble\Payment;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('payments');

        Setting::query()
            ->whereIn('key', [
                'default_payment_method',
                'payment_cod_status',
                'payment_cod_description',
                'payment_cod_name',
                'payment_bank_transfer_status',
                'payment_bank_transfer_description',
                'payment_bank_transfer_name',
            ])
            ->delete();
    }
}
