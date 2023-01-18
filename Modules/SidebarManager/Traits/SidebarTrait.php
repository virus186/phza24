<?php

namespace Modules\SidebarManager\Traits;

use Modules\SidebarManager\Entities\Backendmenu;
use Modules\SidebarManager\Entities\BackendmenuUser;

trait SidebarTrait
{
    public static function latestSidebar(){
        if(auth()->user()->role->type == 'seller'){
            $backend_menus = Backendmenu::where(function($q){
                $q->where('user_id', auth()->id())->orWhereNull('user_id');
            })->where('is_seller', 1)->get();
        }
        else{
            $backend_menus = Backendmenu::where(function($q){
                $q->where('user_id', auth()->id())->orWhereNull('user_id');
            })->where('is_admin', 1)->get();
        }
        $backendMenuUser = BackendmenuUser::with('backendMenu')->where('user_id', auth()->id())->get();
        if($backendMenuUser->count() != $backend_menus->count()){
                
            $backend_menu_not_exsist = $backend_menus->whereNotIn('id', $backendMenuUser->pluck('backendmenu_id')->toArray());
            foreach($backend_menu_not_exsist as $menu){
                
                $parent_id = null;
                $position = 0;
                if($menu->parent_id){
                    $parentMenu = BackendmenuUser::where('backendmenu_id', $menu->parent_id)->where('user_id', auth()->id())->first();
                    if($parentMenu){
                        $parent_id  = $parentMenu->id;
                        $position = BackendmenuUser::where('parent_id', $parent_id)->where('user_id', auth()->id())->count() + 1;
                    }
                }
                
                BackendmenuUser::create(['parent_id' => $parent_id, 'user_id' => auth()->id(), 'backendmenu_id' => $menu->id, 'position' => $position]);
            }
        }
        return true;
    }

}