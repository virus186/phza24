<?php

namespace Botble\Razorpay;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'payment_razorpay_name',
                'payment_razorpay_description',
                'payment_razorpay_key',
                'payment_razorpay_secret',
                'payment_razorpay_status',
            ])
            ->delete();
    }
}
