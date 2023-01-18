<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $middleware = Route::current()->gatherMiddleware();
            if(in_array('admin', $middleware)){
                return url('/admin/login');
            }
            if(in_array('seller', $middleware)){
                if(isModuleActive('MultiVendor')){
                    return url('/seller/login');
                }
                return url('/admin/login');
            }
            return url('/login');
        }
    }
}
