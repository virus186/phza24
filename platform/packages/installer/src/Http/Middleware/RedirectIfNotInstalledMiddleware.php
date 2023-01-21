<?php

namespace Botble\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Route;

class RedirectIfNotInstalledMiddleware extends InstallerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->alreadyInstalled() && Route::current()->getPrefix() !== 'install') {
            return redirect()->route('installers.welcome');
        }

        return $next($request);
    }
}
