<?php

namespace Botble\Paypal;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'payment_paypal_name',
                'payment_paypal_description',
                'payment_paypal_client_id',
                'payment_paypal_client_secret',
                'payment_paypal_mode',
                'payment_paypal_status',
            ])
            ->delete();
    }
}
