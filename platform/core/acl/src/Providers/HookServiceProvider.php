<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Hooks\UserWidgetHook;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [UserWidgetHook::class, 'addUserStatsWidget'], 12, 2);
    }
}
