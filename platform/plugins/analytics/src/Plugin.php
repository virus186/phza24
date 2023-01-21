<?php

namespace Botble\Analytics;

use Botble\Dashboard\Models\DashboardWidget;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;
use Exception;

class Plugin extends PluginOperationAbstract
{
    /**
     * @throws Exception
     */
    public static function remove()
    {
        $widgets = app(DashboardWidgetInterface::class)
            ->advancedGet([
                'condition' => [
                    [
                        'name',
                        'IN',
                        [
                            'widget_analytics_general',
                            'widget_analytics_page',
                            'widget_analytics_browser',
                            'widget_analytics_referrer',
                        ],
                    ],
                ],
            ]);

        foreach ($widgets as $widget) {
            /**
             * @var DashboardWidget $widget
             */
            $widget->delete();
        }

        Setting::query()
            ->whereIn('key', [
                'google_analytics',
                'analytics_view_id',
                'analytics_service_account_credentials',
            ])
            ->delete();
    }
}
