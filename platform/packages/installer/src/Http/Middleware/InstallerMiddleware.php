<?php

namespace Botble\Installer\Http\Middleware;

use Botble\Base\Supports\Helper;
use Illuminate\Support\Facades\File;

abstract class InstallerMiddleware
{
    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled(): bool
    {
        if (Helper::isConnectedDatabase() && !File::exists(storage_path(INSTALLING_SESSION_NAME))) {
            return true;
        }

        return File::exists(storage_path(INSTALLED_SESSION_NAME));
    }
}
