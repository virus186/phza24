<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->role->type == "superadmin")
        {
            return $next($request);
        }
        $roles = app('permission_list');

        $role = $roles->where('id',auth()->user()->role_id)->first();
        
        if(".create" == substr($request->route()->getName(), -7)){
            if($role != null && $role->permissions->contains('route',str_replace(".create", ".store",$request->route()->getName()))){
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }elseif ($role != null && $role->permissions->contains('route',$request->route()->getName())) {
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }else{
                abort(401);
            }
        }
        if(".store" == substr($request->route()->getName(), -6)){
            if($role != null && $role->permissions->contains('route',str_replace(".store", ".create",$request->route()->getName()))){
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }elseif ($role != null && $role->permissions->contains('route',$request->route()->getName())) {
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }else{
                abort(401);
            }
        }
        if(".update" == substr($request->route()->getName(), -7)){
            if($role != null && $role->permissions->contains('route',str_replace(".update", ".edit",$request->route()->getName()))){
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }elseif ($role != null && $role->permissions->contains('route',$request->route()->getName())) {
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }else{
                abort(401);
            }
        }
        if(".edit" == substr($request->route()->getName(), -5)){
            if($role != null && $role->permissions->contains('route',str_replace(".edit", ".update",$request->route()->getName()))){
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }elseif ($role != null && $role->permissions->contains('route',$request->route()->getName())) {
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }else{
                abort(401);
            }
        }
        else{
            if($role != null && $role->permissions->contains('route',$request->route()->getName())){
                if($role->name == 'Sub Seller'){
                    if(auth()->user()->permissions->contains('route',$request->route()->getName())){
                        return $next($request);
                    }else{
                        abort(401);
                    }
                }
                return $next($request);
            }else{
                abort(401);
            }
        }
    }
}
