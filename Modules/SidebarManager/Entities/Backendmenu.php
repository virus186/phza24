<?php

namespace Modules\SidebarManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Backendmenu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function children(){
        return $this->hasMany(Backendmenu::class, 'parent_id', 'id')->with('children')->orderBy('position');
    }
}
