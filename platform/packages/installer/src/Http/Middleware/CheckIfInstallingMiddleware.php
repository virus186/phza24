<?php

namespace Botble\Installer\Http\Middleware;

use BaseHelper;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckIfInstallingMiddleware
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
        try {
            $content = BaseHelper::getFileData(storage_path(INSTALLING_SESSION_NAME));

            $startingDate = Carbon::parse($content);

            if (!$content || \Carbon\Carbon::now()->diffInMinutes($startingDate) > 30) {
                return redirect()->route('public.index');
            }
        } catch (Exception $exception) {
            return redirect()->route('public.index');
        }

        return $next($request);
    }
}
