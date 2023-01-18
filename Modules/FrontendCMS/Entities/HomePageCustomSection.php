<?php

namespace Modules\FrontendCMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Category;

class HomePageCustomSection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function category(){
        return $this->belongsTo(Category::class,'field_1', 'id')->with('subCategories');
    }
}
