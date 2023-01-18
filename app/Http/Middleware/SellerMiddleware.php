<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role->type == 'superadmin' || Auth::check() && Auth::user()->role->type == 'admin' || Auth::check() && Auth::user()->role->type == 'staff' || Auth::check() && Auth::user()->role->type == 'seller')) {
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
