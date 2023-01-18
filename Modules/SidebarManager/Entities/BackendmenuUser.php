<?php

namespace Modules\SidebarManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BackendmenuUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function backendMenu(){
        return $this->belongsTo(Backendmenu::class, 'backendmenu_id', 'id');
    }

    public function parent(){
        return $this->belongsTo(BackendmenuUser::class,'parent_id', 'id');
    }

    public function children(){
        return $this->hasMany(BackendmenuUser::class, 'parent_id', 'id')->with('children', 'backendMenu')->where('status', 1)->where('user_id', auth()->id())->orderBy('position');
    }
}
