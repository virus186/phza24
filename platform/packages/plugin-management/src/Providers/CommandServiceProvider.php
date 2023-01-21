<?php

namespace Botble\PluginManagement\Providers;

use Botble\PluginManagement\Commands\PluginActivateAllCommand;
use Botble\PluginManagement\Commands\PluginActivateCommand;
use Botble\PluginManagement\Commands\PluginAssetsPublishCommand;
use Botble\PluginManagement\Commands\PluginDeactivateAllCommand;
use Botble\PluginManagement\Commands\PluginDeactivateCommand;
use Botble\PluginManagement\Commands\PluginRemoveAllCommand;
use Botble\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateCommand::class,
            PluginDeactivateAllCommand::class,
            PluginRemoveCommand::class,
            PluginRemoveAllCommand::class,
        ]);
    }
}
