<?php

namespace Modules\SidebarManager\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SidebarManager\Entities\Backendmenu;
use Modules\SidebarManager\Entities\BackendmenuUser;

class SidebarManagerController extends Controller
{

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }

    public function index()
    {
        try {
            $role_id = auth()->user()->role->type;
            if ($role_id == 'seller') {
                $backend_menus = Backendmenu::where(function($q){
                    $q->where('user_id', auth()->id())->orWhereNull('user_id');
                })->where('is_seller', 1)->get();
            }else{
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
            
            $data['unused_menus'] = BackendmenuUser::with('backendMenu')->where('user_id', auth()->id())->where('status', 0)->get();
            $data['backendMenuUser'] = BackendmenuUser::with('children', 'backendMenu')->whereNull('parent_id')->where('user_id', auth()->id())->orderBy('position')->get();

            return view('sidebarmanager::index')->with($data);
        } catch (\Exception $e) {
            Toastr::error(trans('common.error_message'));
            return back();
        }
    }


    public function sectionStore(Request $request){
        $request->validate([
            'name' => ['required']
        ]);

        try{
            $backendmenu = Backendmenu::create([
                'name' => $request->name,
                'position' => 7874,
                'user_id' => auth()->id()
            ]);
            BackendmenuUser::create([
                'backendmenu_id' => $backendmenu->id,
                'user_id' => auth()->id(),
                'position' => 7874,
                'status' => 1
            ]);
            return $this->reloadWithData();
        }catch(Exception $e){

        }
    }

    public function deleteSection(Request $request){
        DB::beginTransaction();
        try{
            $section = BackendmenuUser::where('user_id', auth()->id())->where('id', $request->id)->first();
            $childs = $section->children;
            foreach($childs as $child){
                $child->update([
                    'status' => 0
                ]);
            }
            if($section->backendMenu->user_id == auth()->id()){
                $section->backendMenu->delete();
            }
            $section->delete();
            DB::commit();
            return $this->reloadWithData();
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'msg' => __('common.error_message')
            ], 500);
        }
        
        
    }

    public function menuUpdate(Request $request){
        $request->validate([
            'ids' => 'required'
        ]);

        // dd(json_decode($request->ids));
        $datas = json_decode($request->ids);
        $parent_id = null;
        $list_ids = [];
        foreach($datas as $key => $data){
            $list_ids[] = $data->id;
            $menu = BackendmenuUser::where('user_id', auth()->id())->where('id', $data->id)->first();
            if($menu){
                if(!isset($data->is_sub_menu)){
                    $menu->update([
                        'parent_id' => isset($data->section_id)?$data->section_id:$data->parent->id,
                        'position' => $key + 1,
                        'status' => 1
                    ]);
                    $parent_id = $menu->id;
                }else{
                    $menu->update([
                        'parent_id' => $parent_id??$menu->parent_id,
                        'position' => $key + 1,
                        'status' => 1
                    ]);
                }
            }
        }
        if(app('theme')->folder_path == 'amazy'){
            $locations = BackendmenuUser::where('user_id', auth()->id())->whereIn('backendmenu_id', [157,158,159,16])->pluck('id')->toArray();
        }else{
            $locations = BackendmenuUser::where('user_id', auth()->id())->whereIn('backendmenu_id', [157,158,159,23,24,27])->pluck('id')->toArray();
        }
        foreach($locations as $location){
            array_push($list_ids, $location);
        }
        $not_use_menu = BackendmenuUser::where('user_id', auth()->id())->whereNotIn('id', $list_ids)->whereNotNull('parent_id')->get();
        foreach($not_use_menu as $menu){
            $menu->update([
                'status' => 0
            ]);
        }

        return $this->reloadWithData();
    }

    public function addToMenu(Request $request){
        $request->validate([
            'parent_id' => 'required'
        ]);
        $menu = BackendmenuUser::where('user_id', auth()->id())->where('id', $request->id)->first();
        if($request->parent_id == 'remove'){
            if($menu){
                $menu->status = 0;
                $menu->save();
                return $this->reloadWithData();
            }
        }else{
            if($menu){
                $menu->parent_id = $request->parent_id;
                $menu->status = 1;
                $menu->save();
                return $this->reloadWithData();
            }
        }
        return $this->reloadWithData();
    }
    public function sortSection(Request $request){
        $request->validate([
            'ids' => 'required'
        ]);
        foreach($request->ids as $key => $id){
            $section = BackendmenuUser::where('user_id', auth()->id())->where('backendmenu_id', $id)->first();
            if($section){
                $section->update([
                    'position' => $key + 1
                ]);
            }
        }
        return $this->reloadWithData();
    }

    public function resetMenu(Request $request){
        DB::beginTransaction();
        try{
            $ownBackendMenus = Backendmenu::where('user_id', auth()->id())->pluck('id')->toArray();
            $myMenus = BackendmenuUser::where('user_id', auth()->id())->pluck('id')->toArray();
            BackendmenuUser::destroy($myMenus);
            Backendmenu::destroy($ownBackendMenus);
            DB::commit();
            return response()->json([
                'msg' => 'success'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'msg' => 'failed'
            ], 500);
        }
        
    }

    private function reloadWithData(){
        $backendMenuUser = BackendmenuUser::with('children', 'backendMenu')->whereNull('parent_id')->where('user_id', auth()->id())->orderBy('position')->get();
        $unused_menus = BackendmenuUser::with('backendMenu')->where('user_id', auth()->id())->where('status', 0)->get();
        return response()->json([
            'msg' => 'success',
            'available_list' => (string)view('sidebarmanager::components.available_list', compact('unused_menus')),
            'menus' => (string)view('sidebarmanager::components.components', compact('backendMenuUser')),
            'live_preview' => (string)view('sidebarmanager::components.live_preview', compact('backendMenuUser'))
        ],200);
    }
}
